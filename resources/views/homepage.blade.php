<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-gray-900 text-white font-poppins antialiased">
    {{-- HEADER --}}
    <header class="fixed top-0 left-0 w-full z-50">
        <nav class="max-w-7xl mx-auto flex items-center justify-between px-6 py-4">
            <a href="/" class="flex items-center space-x-2 hover:opacity-90 transition">
                <img src="{{ asset('favicon.ico') }}" alt="Drive & Go" class="h-10 w-10 rounded-full">
                <span class="text-xl font-semibold tracking-wide">Drive<span class="text-red-500">&</span>Go</span>
            </a>

            <div class="hidden lg:flex items-center gap-x-8">
                <a href="/about" wire:navigate.hover class="hover:text-red-500 transition">About</a>
                <a href="/contact" wire:navigate.hover class="hover:text-red-500 transition">Contact</a>
            </div>

            <div class="hidden lg:flex items-center space-x-4">
                @auth
                    <flux:dropdown position="bottom" align="end">
                        <flux:profile circle name="{{ Auth::user()->name }}"
                            avatar="{{ Auth::user()->avatar && file_exists(storage_path('app/public/' . Auth::user()->avatar))
                                ? asset('storage/' . Auth::user()->avatar)
                                : asset('storage/images/default.jpg') }}" />

                        <flux:navmenu>
                            @if (Auth::user()->role === 'admin')
                                <flux:navmenu.item href="/admin" icon="chart-bar" wire:navigate.hover>
                                    Admin Dashboard
                                </flux:navmenu.item>
                            @else
                                <flux:navmenu.item href="{{ url('profile/my-car') }}" icon="truck" wire:navigate.hover>
                                    My Rent
                                </flux:navmenu.item>
                            @endif
                            <flux:navmenu.item href="{{ url('settings/profile') }}" icon="cog" wire:navigate.hover>
                                Settings
                            </flux:navmenu.item>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <flux:navmenu.item as="button" type="submit" icon="arrow-right-start-on-rectangle"
                                    variant="danger">
                                    Log Out
                                </flux:navmenu.item>
                            </form>
                        </flux:navmenu>
                    </flux:dropdown>
                @else
                    <a href="{{ route('login') }}"
                        class="px-5 py-2.5 rounded-full bg-red-600 hover:bg-red-700 text-white font-semibold transition">
                        Sign In
                    </a>
                @endauth
            </div>

            <button id="menu-btn" class="lg:hidden p-2 rounded-md focus:outline-none">
                <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path id="menu-icon" stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </nav>

        <div id="mobile-menu" class="hidden lg:hidden flex flex-col items-center py-6 space-y-5">
            <a href="/list-cars" wire:navigate.hover class="hover:text-red-500">Browse Cars</a>
            <a href="/about" wire:navigate.hover class="hover:text-red-500">About</a>
            <a href="/contact" wire:navigate.hover class="hover:text-red-500">Contact</a>

            @auth
                <a href="{{ url('settings/profile') }}" class="font-semibold hover:text-red-500">
                    {{ Auth::user()->name }}
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="hover:text-red-500 font-semibold">Log Out</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="font-semibold hover:text-red-500">Sign In</a>
            @endauth
        </div>
    </header>

    <section class="h-screen bg-cover bg-center flex flex-col justify-center items-center text-center relative"
        style="background-image: url('{{ asset('storage/images/bg.jpg') }}')">
        <div class="absolute inset-0 bg-black/60"></div>

        <div class="relative z-10 max-w-2xl px-6">
            <h1 class="text-5xl md:text-6xl font-bold mb-4 animate-fadeIn">
                Find Your Ride with <span class="text-red-500">Drive & Go</span>
            </h1>
            <p class="text-gray-300 mb-8 text-lg">
                Rent cars effortlessly. Track them in real time. Travel without limits.
            </p>

            <div class="flex justify-center space-x-4">
                <a href="/list-cars" wire:navigate.hover
                    class="px-6 py-3 bg-red-600 hover:bg-red-700 rounded-full text-white font-semibold shadow-lg transition">
                    Browse Cars
                </a>
                <a href="/about" wire:navigate.hover
                    class="px-6 py-3 border border-gray-400 hover:border-red-600 rounded-full font-semibold transition">
                    Learn More
                </a>
            </div>
        </div>
    </section>

    @fluxScripts

    <script>
        const menuBtn = document.getElementById('menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');

        menuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    </script>

    {{-- FadeIn animation --}}
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeIn {
            animation: fadeIn 1.2s ease-out forwards;
        }
    </style>
</body>

</html>
