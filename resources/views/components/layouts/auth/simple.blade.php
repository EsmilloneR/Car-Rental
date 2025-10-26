<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-cover bg-center bg-no-repeat antialiased"
    style="background-image: url('{{ asset('storage/images/bg.jpg') }}');">

    <div class="min-h-screen bg-black/60 flex flex-col items-center justify-center p-6 md:p-10">
        <div class="flex w-full max-w-sm flex-col gap-2">
            <a href="{{ route('home') }}" class="flex flex-col items-center gap-2 font-medium" wire:navigate>
                <span class="flex h-9 w-9 mb-1 items-center justify-center rounded-md">
                    <x-app-logo-icon class="size-9 fill-current text-white" />
                </span>
                <span class="sr-only">{{ config('app.name', 'Twayne Garage') }}</span>
            </a>

            <div
                class="flex flex-col gap-6 bg-white/90 dark:bg-neutral-900/80 p-6 rounded-xl shadow-lg backdrop-blur-md">
                {{ $slot }}
            </div>
        </div>
    </div>

    @fluxScripts
</body>

</html>
