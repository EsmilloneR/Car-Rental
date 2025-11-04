<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public array $id_pictures = [];
    public array $existing_id_pictures = [];

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $user = Auth::user();

        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone_number ?? '';
        $this->existing_id_pictures = $user->id_pictures ?? [];
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'min:7', 'max:20'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'id_pictures.*' => ['image', 'max:2048'],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        if (!empty($this->id_pictures)) {
            $uploadedPaths = [];
            foreach ($this->id_pictures as $photo) {
                $path = $photo->store('id_pictures', 'public');
                $uploadedPaths[] = $path;
            }

            $user->id_pictures = array_merge($this->existing_id_pictures, $uploadedPaths);
            $user->save();

            $this->existing_id_pictures = $user->id_pictures;
        }

        $this->reset('id_pictures');

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('home', absolute: false));
            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
};
?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Profile')" :subheading="__('Update your name, email, and ID pictures')">
        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">

            {{-- Name --}}
            <flux:input wire:model="name" :label="__('Name')" type="text" required autofocus autocomplete="name" />

            {{-- Email --}}
            <div>
                <flux:input wire:model="email" :label="__('Email')" type="email" required autocomplete="email" />

                @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !auth()->user()->hasVerifiedEmail())
                    <div>
                        <flux:text class="mt-4">
                            {{ __('Your email address is unverified.') }}

                            <flux:link class="text-sm cursor-pointer"
                                wire:click.prevent="resendVerificationNotification">
                                {{ __('Click here to re-send the verification email.') }}
                            </flux:link>
                        </flux:text>

                        @if (session('status') === 'verification-link-sent')
                            <flux:text class="mt-2 font-medium !dark:text-green-400 !text-green-600">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </flux:text>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Upload ID Pictures --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    {{ __('Upload ID Pictures (Front & Back)') }}
                </label>

                <input type="file" wire:model="id_pictures" multiple accept="image/*"
                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer
                           bg-gray-50 dark:bg-gray-700 dark:text-gray-300 focus:outline-none">

                {{-- Preview newly uploaded images --}}
                <div class="mt-3 flex flex-wrap gap-3">
                    @foreach ($id_pictures as $photo)
                        <img src="{{ $photo->temporaryUrl() }}" class="w-24 h-24 object-cover rounded border"
                            alt="Preview">
                    @endforeach
                </div>

                @error('id_pictures.*')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Existing ID Pictures --}}
            @if ($existing_id_pictures)
                <div class="mt-4">
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('Existing ID Pictures:') }}
                    </h4>
                    <div class="flex flex-wrap gap-3">
                        @foreach ($existing_id_pictures as $path)
                            <img src="{{ asset('storage/' . $path) }}" class="w-24 h-24 object-cover rounded border"
                                alt="Existing ID">
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Save Button --}}
            <div class="flex items-center gap-4 mt-6">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full" data-test="update-profile-button">
                        {{ __('Save') }}
                    </flux:button>
                </div>

                <x-action-message class="me-3" on="profile-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>

        <livewire:settings.delete-user-form />
    </x-settings.layout>
</section>
