<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<div x-data="{
    count: 5,
    startCountdown() {
        const interval = setInterval(() => {
            if (this.count > 1) {
                this.count--;
            } else {
                clearInterval(interval);
                if (window.Livewire?.navigate) {
                    window.Livewire.navigate('/profile/my-car');
                } else {
                    window.location.href = '/profile/my-car';
                }
            }
        }, 1000);
    }
}" x-init="startCountdown()" class="flex items-center justify-center min-h-screen px-6 sm:px-10">

    <div class="w-full max-w-md rounded-2xl shadow-2xl p-10 text-center bg-white dark:bg-gray-800 transition-all duration-700 opacity-0 translate-y-4"
        x-init="$el.classList.remove('opacity-0', 'translate-y-4')" x-transition>

        <div class="flex justify-center mb-6">
            <div
                class="w-20 h-20 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center animate-bounce">
                <svg class="w-12 h-12 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                    stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12l2 2l4-4m6 2a9 9 0 11-18 0a9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <h1 class="text-3xl font-bold text-green-600 dark:text-green-400 mb-3">Thank You!</h1>

        <p class="text-gray-700 dark:text-gray-300 text-lg leading-relaxed mb-8">
            Your booking has been <span class="font-semibold">successfully confirmed.</span><br>
            Redirecting in
            <span class="text-red-600 dark:text-red-400 font-medium" x-text="count"></span>
            <span class="text-red-600 dark:text-red-400 font-medium"> seconds...</span>
        </p>

        @if (session('transaction_reference'))
            <div class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                <p><span class="font-semibold">Reference:</span> {{ session('transaction_reference') }}</p>
                <p><span class="font-semibold">Vehicle:</span> {{ session('vehicle') }}</p>
                <p><span class="font-semibold">Amount Paid:</span> ₱{{ number_format(session('amount'), 2) }}</p>
            </div>
        @endif

        <a href="/profile/my-car" wire:navigate
            class="inline-block w-full sm:w-auto px-6 py-3 rounded-lg bg-red-600 hover:bg-red-700 text-white font-semibold transition duration-300">
            Check Your Rent
        </a>
    </div>
</div>
