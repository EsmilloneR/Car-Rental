<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    <flux:header container class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <a href="{{ route('home') }}" wire:navigate.hover class="flex items-center space-x-2 group">
            <img src="{{ asset('favicon.ico') }}" onerror="this.src='{{ asset('favicon.ico') }}'" alt="Drive & Go"
                class="h-9 w-9 rounded-full transition-transform duration-300 group-hover:scale-110">
            <span class="text-lg font-semibold text-gray-900 dark:text-white group-hover:text-red-600 mr-5">
                Drive <span class="text-red-500">&</span> Go
            </span>
        </a>

        <flux:navbar class="-mb-px max-lg:hidden">
            <flux:navbar.item icon="list-bullet" :href="route('list-cars')" :current="request()->routeIs('list-cars')"
                wire:navigate.hover>
                {{ __('Browse Cars') }}
            </flux:navbar.item>
        </flux:navbar>

        <flux:spacer />

        <flux:navbar class="me-1.5 space-x-0.5 rtl:space-x-reverse py-0!">
            {{-- <flux:tooltip :content="__('Search')" position="bottom">
                <flux:navbar.item class="!h-10 [&>div>svg]:size-5" icon="magnifying-glass" href="#"
                    :label="__('Search')" />
            </flux:tooltip> --}}
        </flux:navbar>

        @auth

            <!-- Desktop User Menu -->
            <flux:dropdown position="top" align="end">
                <flux:profile class="cursor-pointer"
                    avatar="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('storage/images/default.jpg') }}"
                    name="{{ Auth::user()->name }}" />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('storage/images/default.jpg') }}"
                                        alt="">
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>

                        @if (Auth::user()->role === 'admin')
                            <flux:menu.item href="/admin" icon="chart-bar" wire:navigate.hover>{{ __('Admin Analytics') }}
                            </flux:menu.item>
                        @endif
                        <flux:menu.item :href="route('profile.mycar')" icon="truck" wire:navigate.hover>
                            {{ __('My Rent') }}
                        </flux:menu.item>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate.hover>
                            {{ __('Settings') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full"
                            data-test="logout-button">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        @endauth
    </flux:header>


    <!-- Mobile Menu -->

    <flux:sidebar stashable sticky
        class="lg:hidden border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

        <a href="{{ route('home') }}" class="ms-1 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate.hover>
            <x-app-logo />
        </a>

        <flux:navlist variant="outline">
            <flux:navlist.group :heading="__('Platform')">
                <flux:navlist.item icon="list-bullet" :href="route('list-cars')"
                    :current="request()->routeIs('list-cars')" wire:navigate.hover>
                    {{ __('Browse Cars') }}
                </flux:navlist.item>
            </flux:navlist.group>
        </flux:navlist>

        <flux:spacer />
        @auth

            <flux:navlist variant="outline">

                @if (Auth::user()->role === 'admin')
                    <flux:navlist.item icon="truck" href="/admin" wire:navigate.hover>
                        {{ __('Admin Analytics') }}
                    </flux:navlist.item>
                @endif
                <flux:navlist.item icon="truck" :href="route('profile.mycar')" wire:navigate.hover>
                    {{ __('My Rent') }}
                </flux:navlist.item>

            </flux:navlist>
        @endauth

    </flux:sidebar>

    {{ $slot }}

    @fluxScripts
</body>

</html>
