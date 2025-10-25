<?php

use Livewire\Volt\Component;
use App\Models\Vehicle;
use App\Models\Payment;
use App\Models\Rental;

use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

use App\Events\PaymentConfirmed;

new class extends Component {
    public $id;
    public $vehicle;
    public $trip_type = 'pickup_dropOff';
    public $pickup_location;
    public $dropOff_location;

    public $rental_start;
    public $rental_end;

    public $days = 0;
    public $hours = 0;
    public $months = 0;
    public $weeks = 0;

    public $base_amount = 0;
    public $deposit_percentage = 0.3;
    public $deposit = 0;
    public $total = 0;

    public $error = null;

    public $payment_method = 'online_payment';
    public $amount = 0;

    public $payment;
    // public $transaction_reference;

    public $paymongo_url;

    public function mount($id)
    {
        $this->id = $id;
        $this->vehicle = Vehicle::find($id);
    }

    public function confirmedPaid()
    {
        $this->validate([
            'trip_type' => 'required',
            'pickup_location' => 'nullable|string',
            'dropOff_location' => 'nullable|string',
            'rental_start' => 'nullable',
            'rental_end' => 'nullable',
            'total' => 'required|numeric|min:1',
            'deposit' => 'nullable|numeric|min:1',
            'base_amount' => 'nullable|numeric|min:1',
            'paymongo_url' => 'nullable|string',
        ]);
        return $this->handleOnlinePayment();
    }

    public function updated()
    {
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->error = null;
        $this->total = 0;

        try {
            switch ($this->trip_type) {
                case 'hrs':
                    $ratePerHour = $this->vehicle->rate_day / 10;
                    $this->rental_start = $this->rental_start ?? now();
                    $this->rental_end = $this->rental_end ?? now()->addHours(4);

                    $this->base_amount = $this->hours > 0 ? $this->hours * $ratePerHour : 0;
                    $this->deposit = $this->base_amount * $this->deposit_percentage;
                    $this->total = $this->base_amount + $this->deposit;
                    break;

                case 'days':
                    if ($this->rental_start && $this->rental_end) {
                        $start = Carbon::parse($this->rental_start);
                        $end = Carbon::parse($this->rental_end);

                        $totalHours = $start->diffInHours($end);
                        $days = max(1, ceil($totalHours / 24));

                        $this->days = $days;

                        $this->base_amount = $days * $this->vehicle->rate_day;
                        $this->deposit = $this->deposit_percentage * $this->vehicle->rate_day;

                        $this->total = $this->deposit + $this->base_amount;
                    }
                    break;

                case 'weeks':
                    if ($this->rental_start && $this->rental_end) {
                        $start = Carbon::parse($this->rental_start);
                        $end = Carbon::parse($this->rental_end);
                        $weeks = max(1, ceil($start->diffInWeeks($end)));
                        $this->weeks = $weeks;

                        $this->deposit = $this->deposit_percentage * $this->vehicle->rate_day;
                        $this->total = $this->deposit + $this->vehicle->rate_day * 7 * $weeks;
                    }
                    break;

                case 'months':
                    if ($this->rental_start && $this->rental_end) {
                        $start = Carbon::parse($this->rental_start);
                        $end = Carbon::parse($this->rental_end);
                        $months = max(1, ceil($start->diffInMonths($end)));
                        $this->months = $months;

                        // Updated: Calculate deposit based on monthly rate
                        $this->base_amount = $this->vehicle->rate_day * 30 * $months;
                        $this->deposit = $this->deposit_percentage * $this->base_amount;
                        $this->total = $this->base_amount + $this->deposit;
                    }
                    break;

                case 'pickup_dropOff':
                    $this->deposit = $this->deposit_percentage * $this->vehicle->rate_day;
                    $this->total = $this->deposit + max(250, min($this->vehicle->rate_day, 2500));

                    break;

                default:
                    $this->error = 'Invalid trip type selected.';
                    break;
            }

            if ($this->total <= 0) {
                $this->error = 'Please enter valid details for this trip type.';
            }
        } catch (\Throwable $e) {
            $this->error = 'Error calculating total: ' . $e->getMessage();
        }
    }

    public function handleOnlinePayment()
    {
        // dd($this->rental_start);
        try {
            $transactionReference = 'CRTG-' . strtoupper(uniqid());

            $rental = Rental::create([
                'user_id' => Auth::id(),
                'vehicle_id' => $this->vehicle->id,
                'trip_type' => $this->trip_type,
                'pickup_location' => $this->pickup_location,
                'dropOff_location' => $this->dropOff_location,
                'rental_start' => $this->rental_start,
                'rental_end' => $this->rental_end,
                'base_amount' => $this->base_amount,
                'deposit' => $this->deposit,
                'status' => 'pending',
                'agreement_no' => 'CRTG-' . strtoupper(uniqid()),
            ]);

            session(['pending_rental_id' => $rental->id]);

            $client = new Client();

            $response = $client->post('https://api.paymongo.com/v1/checkout_sessions', [
                'json' => [
                    'data' => [
                        'attributes' => [
                            'billing' => [
                                'name' => Auth::user()->name,
                                'email' => Auth::user()->email,
                                'phone' => Auth::user()->phone_number,
                            ],
                            'send_email_receipt' => true,
                            'show_description' => true,
                            'show_line_items' => true,
                            'cancel_url' => route('home'),
                            'success_url' => route('payment.success', ['rental_id' => $rental->id]),
                            'description' => 'Drive & Go - Twayne Garage Rental',
                            'line_items' => [
                                [
                                    'name' => "{$this->vehicle->manufacturer->brand} {$this->vehicle->model}",
                                    'currency' => 'PHP',
                                    'amount' => (int) ($this->total * 100),
                                    'description' => "{$this->vehicle->model} Rental",
                                    'quantity' => 1,
                                ],
                            ],
                            'payment_method_types' => ['card', 'gcash', 'paymaya'],
                            'statement_descriptor' => 'Drive & Go Rentals',
                            'metadata' => [
                                'rental_id' => $rental->id,
                            ],
                            'reference_number' => $rental->agreement_no,
                        ],
                    ],
                ],
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'Authorization' => 'Basic ' . base64_encode(config('services.paymongo.secret_key') . ':'),
                ],
            ]);

            // dd(json_decode($response->getBody(), true));
            $checkoutData = json_decode($response->getBody(), true);
            if (isset($checkoutData['data']['attributes']['checkout_url'])) {
                $checkoutSessionUrl = $checkoutData['data']['attributes']['checkout_url'];
                // $this->dispatch('redirect-to-paymongo', $checkoutData['data']['attributes']['checkout_url']);

                $rental->update([
                    'paymongo_url' => $checkoutSessionUrl,
                ]);

                return redirect()->away($checkoutSessionUrl);
            }

            $this->error = 'Unable to create checkout session.';
        } catch (\Exception $e) {
            if (method_exists($e, 'getResponse') && $e->getResponse()) {
                $body = $e->getResponse()->getBody()->getContents();
                Log::error('PayMongo Checkout Error Response: ' . $body);
            } else {
                Log::error('PayMongo Checkout Exception: ' . $e->getMessage());
            }
            $this->error = 'Error creating checkout session. Please try again.';
        }
    }
};
?>

<div>
    {{-- @if ($vehicle->rentals->isNotEmpty()) --}}
    {{--
        <div x-data="{
            count: 5,
            startCountdown() {
                const interval = setInterval(() => {
                    if (this.count > 1) {
                        this.count--;
                    } else {
                        clearInterval(interval);
                        window.Livewire.navigate('/list-cars');
                    }
                }, 1000);
            }
        }" x-init="startCountdown()"
            class="flex items-center justify-center min-h-[80vh] px-2 sm:px-10">
            <div class="flex items-center justify-center bg-red-50 border border-red-300 text-red-800 p-4 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01M5.07 19h13.86a2 2 0 001.74-3L13.74 4a2 2 0 00-3.48 0L3.33 16a2 2 0 001.74 3z" />
                </svg>
                <span>This car is currently reserved by another customer.</span>
            </div>
        </div> --}}
    {{-- @else --}}
    <div class="w-full max-w-[85rem] p-3 sm:px-6 lg:px-8 mx-auto">
        <section class="bg-white dark:bg-gray-800  py-11 font-poppins">
            <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8">

                <div x-data="{ mainImage: '{{ url('storage', $vehicle->avatar) }}' }">
                    <div class="mb-4">
                        <img x-bind:src="mainImage" alt="{{ $vehicle->make }} {{ $vehicle->model }}"
                            class="object-cover w-full h-[300px] rounded-lg shadow-md">
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <div class="w-20 h-20 border rounded-md overflow-hidden cursor-pointer hover:border-red-500"
                            x-on:click="mainImage='{{ url('storage', $vehicle->avatar) }}'">
                            <img src="{{ url('storage', $vehicle->avatar) }}" alt="Main Photo"
                                class="object-cover w-full h-full">
                        </div>

                        @if ($vehicle->photos)
                            @foreach ($vehicle->photos as $image)
                                <div class="w-20 h-20 border rounded-md overflow-hidden cursor-pointer hover:border-red-500"
                                    x-on:click="mainImage='{{ url('storage', $image) }}'"
                                    wire:key="{{ $vehicle->id }}">
                                    <img src="{{ url('storage', $image) }}" alt="Extra Image"
                                        class="object-cover w-full h-full">
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div>
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-2">
                            Rent <span class="text-red-600">{{ $vehicle->make }}</span>
                            {{ $vehicle->model }}
                            <span class="text-sm text-gray-500">({{ $vehicle->year }})</span>
                        </h2>
                        <p class="text-gray-600 dark:text-gray-400">
                            Rate:
                            <span class="text-green-600 font-semibold">
                                {{ Number::currency($vehicle->rate_day, 'PHP') }}/day
                            </span>
                        </p>
                    </div>

                    {{-- Pickup & Return Form --}}
                    <form wire:submit.prevent="confirmedPaid" class="space-y-6">
                        <div>
                            <label class="block text-gray-700 dark:text-gray-300 font-medium mb-2">
                                Trip Type
                            </label>
                            <select wire:model.live="trip_type" required
                                class="w-full p-3 border rounded-md focus:ring-2 focus:ring-red-500 dark:bg-gray-700 dark:text-white">
                                <option value="">-- Select Trip Type --</option>
                                <option value="pickup_dropOff">Pick Up & Drop Off Only</option>
                                <option value="hrs">Hour/s</option>
                                <option value="days">Day/s</option>
                                <option value="weeks">Week/s</option>
                                <option value="months">Months/s</option>
                            </select>
                        </div>



                        @if ($trip_type === 'weeks')
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-gray-700 dark:text-gray-300 font-medium mb-2">
                                        Rental Start
                                    </label>
                                    <input type="week" wire:model.live="rental_start" required
                                        class="w-full p-3 border rounded-md focus:ring-2 focus:ring-red-500 dark:bg-gray-700 dark:text-white">
                                </div>
                                <div>
                                    <label class="block text-gray-700 dark:text-gray-300 font-medium mb-2">
                                        Rental End
                                    </label>
                                    <input type="week" wire:model.live="rental_end" required
                                        class="w-full p-3 border rounded-md focus:ring-2 focus:ring-red-500 dark:bg-gray-700 dark:text-white">
                                </div>
                            </div>
                        @endif

                        @if ($trip_type === 'days')
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-gray-700 dark:text-gray-300 font-medium mb-2">
                                        Rental Start
                                    </label>
                                    <input type="datetime-local" wire:model.live="rental_start" required
                                        class="w-full p-3 border rounded-md focus:ring-2 focus:ring-red-500 dark:bg-gray-700 dark:text-white">
                                </div>
                                <div>
                                    <label class="block text-gray-700 dark:text-gray-300 font-medium mb-2">
                                        Rental End
                                    </label>
                                    <input type="datetime-local" wire:model.live="rental_end" required
                                        class="w-full p-3 border rounded-md focus:ring-2 focus:ring-red-500 dark:bg-gray-700 dark:text-white">
                                </div>
                            </div>
                        @endif

                        @if ($trip_type === 'months')
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-gray-700 dark:text-gray-300 font-medium mb-2">
                                        Rental Start
                                    </label>
                                    <input type="month" wire:model.live="rental_start" required
                                        class="w-full p-3 border rounded-md focus:ring-2 focus:ring-red-500 dark:bg-gray-700 dark:text-white">
                                </div>
                                <div>
                                    <label class="block text-gray-700 dark:text-gray-300 font-medium mb-2">
                                        Rental End
                                    </label>
                                    <input type="month" wire:model.live="rental_end" required
                                        class="w-full p-3 border rounded-md focus:ring-2 focus:ring-red-500 dark:bg-gray-700 dark:text-white">
                                </div>
                            </div>
                        @endif

                        @if ($trip_type === 'hrs')
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                                <div class="col-span-2">
                                    <label class="block text-gray-700 dark:text-gray-300 font-medium mb-2">
                                        Number of Hours
                                    </label>
                                    <input type="number" min="1" wire:model.live="hours"
                                        placeholder="Enter hours"
                                        class="w-full p-3 border rounded-md focus:ring-2 focus:ring-red-500 dark:bg-gray-700 dark:text-white">
                                </div>
                            </div>
                        @endif

                        @if ($trip_type === 'pickup_dropOff')
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-gray-700 dark:text-gray-300 font-medium mb-2">
                                        Pickup Location
                                    </label>
                                    <input type="text" wire:model.live="pickup_location" required
                                        class="w-full p-3 border rounded-md focus:ring-2 focus:ring-red-500 dark:bg-gray-700 dark:text-white">
                                </div>
                                <div>
                                    <label class="block text-gray-700 dark:text-gray-300 font-medium mb-2">
                                        Drop Off Location
                                    </label>
                                    <input type="text" wire:model.live="dropOff_location" required
                                        class="w-full p-3 border rounded-md focus:ring-2 focus:ring-red-500 dark:bg-gray-700 dark:text-white">
                                </div>
                            </div>
                        @endif

                        <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow-inner">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-3">Booking Summary
                            </h3>

                            @if ($error)
                                <p class="text-red-600 font-medium">{{ $error }}</p>
                            @else
                                <ul class="text-gray-700 dark:text-gray-300 space-y-1">
                                    <li>
                                        Trip Type:
                                        <span class="font-semibold capitalize text-gray-900 dark:text-gray-100">
                                            {{ str_replace('_', ' & ', $trip_type) }}
                                        </span>
                                    </li>

                                    {{-- For Hours --}}
                                    @if ($trip_type === 'hrs')
                                        <li>Hours: <span class="font-semibold">{{ $hours ?: 0 }}</span></li>
                                        <li>Rate/Hour:
                                            <span class="font-semibold text-green-600">
                                                {{ Number::currency($vehicle->rate_day / 10, 'PHP') }}
                                            </span>
                                        </li>
                                        <li>Deposit:
                                            <span class="font-semibold text-green-600">
                                                {{ Number::currency($this->deposit, 'PHP') }}
                                            </span>
                                        </li>
                                    @endif

                                    {{-- For Days --}}
                                    @if ($trip_type === 'days')
                                        <li>Days: <span class="font-semibold">{{ $days ?: 0 }}</span></li>
                                        <li>Rate/Day:
                                            <span class="font-semibold text-green-600">
                                                {{ Number::currency($vehicle->rate_day, 'PHP') }}
                                            </span>
                                        </li>
                                        <li>Deposit:
                                            <span class="font-semibold text-green-600">
                                                {{ Number::currency($this->deposit, 'PHP') }}
                                            </span>
                                        </li>
                                    @endif

                                    {{-- For Weeks --}}
                                    @if ($trip_type === 'weeks')
                                        <li>Weeks: <span class="font-semibold">{{ $weeks ?: 0 }}</span></li>
                                        <li>Rate/Week:
                                            <span class="font-semibold text-green-600">
                                                {{ Number::currency($vehicle->rate_day * 7, 'PHP') }}
                                            </span>
                                        </li>
                                        <li>Deposit:
                                            <span class="font-semibold text-green-600">
                                                {{ Number::currency($this->deposit, 'PHP') }}
                                            </span>
                                        </li>
                                    @endif

                                    {{-- For Months --}}
                                    @if ($trip_type === 'months')
                                        <li>Months: <span class="font-semibold">{{ $months ?: 0 }}</span></li>
                                        <li>Rate/Month:
                                            <span class="font-semibold text-green-600">
                                                {{ Number::currency($vehicle->rate_day * 30, 'PHP') }}
                                            </span>
                                        </li>
                                        <li>Deposit:
                                            <span class="font-semibold text-green-600">
                                                {{ Number::currency($this->deposit, 'PHP') }}
                                            </span>
                                        </li>
                                    @endif

                                    {{-- For Pickup & DropOff --}}
                                    @if ($trip_type === 'pickup_dropOff')
                                        <li>Pickup Location:
                                            <span class="font-semibold">{{ $pickup_location ?: '—' }}</span>
                                        </li>
                                        <li>Drop Off Location:
                                            <span class="font-semibold">{{ $dropOff_location ?: '—' }}</span>
                                        </li>
                                        <li>Flat Rate:
                                            <span class="font-semibold text-green-600">
                                                {{ Number::currency(max(250, min($vehicle->rate_day, 2500)), 'PHP') }}
                                            </span>
                                        </li>
                                        <li>Deposit:
                                            <span class="font-semibold text-green-600">
                                                {{ Number::currency($this->deposit, 'PHP') }}
                                            </span>
                                        </li>
                                    @endif

                                    <hr class="my-2 border-gray-300 dark:border-gray-600">

                                    <li>Total:
                                        <span class="font-bold text-green-700">
                                            {{ Number::currency($total, 'PHP') }}
                                        </span>
                                    </li>
                                </ul>
                            @endif
                        </div>


                        <input type="hidden">
                        <div class="pt-4">
                            @php
                                $isInvalid =
                                    $error ||
                                    $total <= 0 ||
                                    ($trip_type === 'hrs' && $hours <= 0) ||
                                    ($trip_type === 'days' && $days <= 0) ||
                                    ($trip_type === 'weeks' && $weeks <= 0) ||
                                    ($trip_type === 'months' && $months <= 0) ||
                                    ($trip_type === 'pickup_dropOff' &&
                                        (empty($pickup_location) || empty($dropOff_location)));
                            @endphp

                            <button type="submit"
                                class="w-full p-4 rounded-md text-white transition
               {{ $isInvalid ? 'bg-gray-400 cursor-not-allowed' : 'bg-red-600 hover:bg-red-700' }}"
                                {{ $isInvalid ? 'disabled' : '' }}>
                                <span wire:loading.remove>Continue to Confirmation</span>
                                <span wire:loading>Processing...</span>
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </section>
        <script>
            // document.addEventListener("livewire:init", () => {
            //     Livewire.on("redirect-to-paymongo", (url) => {
            //         window.location.href = url;
            //     });
            // });
        </script>
    </div>

    {{-- @endif --}}
</div>
