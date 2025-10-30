<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Mail;

new class extends Component {
    public string $name = '';
    public string $email = '';
    public string $message = '';

    public function sendMessage()
    {
        $this->validate([
            'name' => 'required|min:3',
            'email' => 'required|email',
            'message' => 'required|min:5',
        ]);

        // Example email sending (works if Mail config is set)
        Mail::raw("Message from: {$this->name} <{$this->email}>\n\n{$this->message}", function ($msg) {
            $msg->to('cloacalkissed14@yahoo.com')->subject('New Contact Message');
        });

        session()->flash('success', '✅ Thank you! Your message has been sent successfully.');

        $this->reset(['name', 'email', 'message']);
    }
};
?>

<div class="max-w-7xl mx-auto px-6 py-12 space-y-16">
    <!-- About Header -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl p-10 text-center">
        <h1 class="text-3xl md:text-4xl font-bold mb-4">
            About <span class="text-red-600">Drive & Go</span>
        </h1>
        <p class="text-gray-600 dark:text-gray-300 text-lg">
            Learn more about who we are and how we make your car rental experience simple, fast, and reliable.
        </p>
    </div>

    <!-- About Sections -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl p-10 space-y-8">
        <section>
            <h2 class="text-2xl font-semibold text-red-600 mb-4">Our Story</h2>
            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                <span class="font-semibold">Drive & Go</span> started at
                <span class="font-semibold">Twayne Garage Car Rental, Bislig City</span> — born out of a vision to
                provide hassle-free, affordable, and trustworthy car rental services for locals and travelers alike.
                What began as a small family-run garage has grown into a full-service car rental solution
                with a focus on customer satisfaction, convenience, and trust.
            </p>
        </section>

        <section>
            <h2 class="text-2xl font-semibold text-red-600 mb-4">Our Mission</h2>
            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                Our mission is simple — to make every trip smooth and worry-free.
                Whether you’re commuting around Bislig City or heading out on a long drive,
                we ensure your journey begins with a well-maintained vehicle and friendly service.
            </p>
        </section>

        <section>
            <h2 class="text-2xl font-semibold text-red-600 mb-4">Why Choose Us?</h2>
            <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-2">
                <li>✅ Clean, well-maintained, and fuel-efficient vehicles</li>
                <li>✅ Affordable rates with no hidden charges</li>
                <li>✅ Fast, easy booking and reliable customer support</li>
                <li>✅ Locally trusted team right here in Bislig City</li>
            </ul>
        </section>

        <section>
            <h2 class="text-2xl font-semibold text-red-600 mb-4">Our Location</h2>
            <p class="text-gray-700 dark:text-gray-300 mb-6">
                Visit us at <span class="font-semibold">Twayne Garage Car Rental</span>,
                Andres Soriano Avenue, Mangagoy, Bislig City, Philippines 8311.
            </p>

            <div class="rounded-xl overflow-hidden shadow-md">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d1285.8217970684019!2d126.3545703!3d8.1846658!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x32fdbb7fb300f80d%3A0x814d0e431e43ed14!2sTwayne%20Garage%20Car%20Rental%20Bislig!5e0!3m2!1sen!2sph!4v1700000000000!5m2!1sen!2sph"
                    width="100%" height="350" style="border:0;" allowfullscreen="" loading="lazy">
                </iframe>
            </div>
        </section>
    </div>

    <!-- Contact Section -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl p-10">
        <h2 class="text-3xl font-bold text-center mb-10 text-red-600">Contact Us</h2>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
            <!-- Left Info -->
            <div class="space-y-6">
                <p class="text-gray-700 dark:text-gray-300">
                    Have questions or need to book a ride? Reach out to us via the form or contact details below.
                </p>

                <ul class="space-y-4 text-gray-700 dark:text-gray-300">
                    <li class="flex items-start space-x-3">
                        <i class="fa-solid fa-envelope text-red-600 text-xl mt-1"></i>
                        <span>cloacalkissed14@yahoo.com</span>
                    </li>
                    <li class="flex items-start space-x-3">
                        <i class="fa-solid fa-phone text-red-600 text-xl mt-1"></i>
                        <span>+63 977 300 5696 / +63 929 304 8310</span>
                    </li>
                    <li class="flex items-start space-x-3">
                        <i class="fa-solid fa-location-dot text-red-600 text-xl mt-1"></i>
                        <span>Andres Soriano Avenue, Mangagoy, Bislig City, Philippines 8311</span>
                    </li>
                    <li class="flex items-start space-x-3">
                        <i class="fa-solid fa-clock text-red-600 text-xl mt-1"></i>
                        <span>Open Daily — 8:00 AM to 6:00 PM</span>
                    </li>
                </ul>
            </div>

            <!-- Right Form -->
            <div>
                @if (session('success'))
                    <div class="bg-green-100 text-green-800 px-4 py-3 rounded-lg mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <form wire:submit="sendMessage" class="space-y-5">
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 font-medium mb-2">Full Name</label>
                        <input type="text" wire:model.defer="name"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white"
                            placeholder="Enter your full name">
                        @error('name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 font-medium mb-2">Email Address</label>
                        <input type="email" wire:model.defer="email"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white"
                            placeholder="Enter your email">
                        @error('email')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 font-medium mb-2">Message</label>
                        <textarea wire:model.defer="message" rows="5"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white"
                            placeholder="Write your message..."></textarea>
                        @error('message')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="text-center">
                        <button type="submit"
                            class="bg-red-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-red-700 transition">
                            Send Message
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
