<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>SignSync - Digitale Zeiterfassung</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
        
        <header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6">
            <nav class="flex items-center justify-between gap-4">
                <div class="font-bold text-lg tracking-tight text-[#f53003]">
                    SignSync
                </div>
                
                <div class="flex gap-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] border rounded-sm text-sm">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] rounded-sm text-sm">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="inline-block px-5 py-1.5 bg-[#1b1b18] text-white dark:bg-[#eeeeec] dark:text-[#1c1c1a] rounded-sm text-sm font-medium">Registrieren</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </nav>
        </header>

        <div class="flex items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow">
            <main class="flex max-w-[335px] w-full flex-col lg:max-w-4xl lg:flex-row shadow-sm rounded-lg overflow-hidden border border-[#e3e3e0] dark:border-[#3E3E3A]">
                
                <div class="flex-1 p-6 lg:p-16 bg-white dark:bg-[#161615] dark:text-[#EDEDEC]">
                    <span class="text-[#f53003] font-semibold text-xs uppercase tracking-widest">Projekt SignSync</span>
                    <h1 class="mt-2 mb-4 text-3xl font-bold leading-tight">Zeiterfassung für Schulbegleitungen.</h1>
                    <p class="mb-8 text-[#706f6c] dark:text-[#A1A09A] text-base">
                        Schluss mit Zettelwirtschaft. Erfassen Sie Einsatzzeiten digital, lassen Sie direkt vor Ort per eSignatur unterschreiben und exportieren Sie fertige Leistungsnachweise.
                    </p>
                    
                    <div class="grid grid-cols-1 gap-4 text-sm">
                        <div class="flex items-start gap-3">
                            <div class="h-5 w-5 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center shrink-0">
                                <svg class="h-3 w-3 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                            </div>
                            <span>**Rechtssichere eSignatur** direkt auf dem Tablet oder Smartphone.</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="h-5 w-5 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center shrink-0">
                                <svg class="h-3 w-3 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                            </div>
                            <span>**Automatisierte PDF-Erstellung** für Kostenträger und Ämter.</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="h-5 w-5 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center shrink-0">
                                <svg class="h-3 w-3 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                            </div>
                            <span>**DSGVO-konform:** Sensible Schülerdaten sicher verwalten.</span>
                        </div>
                    </div>
                </div>

                <div class="lg:w-[380px] bg-[#f9f9f8] dark:bg-[#1b1b18] p-8 flex flex-col justify-center border-t lg:border-t-0 lg:border-l border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <div class="relative p-6 bg-white dark:bg-[#252523] rounded-xl shadow-md border border-[#e3e3e0] dark:border-[#3E3E3A]">
                        <div class="h-2 w-16 bg-[#f53003] rounded-full mb-4"></div>
                        <div class="space-y-3">
                            <div class="h-3 w-full bg-gray-100 dark:bg-[#3E3E3A] rounded"></div>
                            <div class="h-3 w-5/6 bg-gray-100 dark:bg-[#3E3E3A] rounded"></div>
                            <div class="h-12 w-full border-2 border-dashed border-gray-200 dark:border-[#3E3E3A] rounded mt-4 flex items-center justify-center text-[10px] text-gray-400">
                                Hier unterschreiben...
                            </div>
                        </div>
                    </div>
                    <p class="mt-6 text-center text-xs text-[#706f6c] dark:text-[#A1A09A]">
                        Bereit für den digitalen Nachweis?
                    </p>
                    <a href="{{ route('register') }}" class="mt-4 block text-center py-3 bg-[#1b1b18] text-white dark:bg-[#eeeeec] dark:text-[#1c1c1a] rounded-lg font-medium hover:opacity-90 transition-all">
                        Jetzt starten
                    </a>
                </div>
            </main>
        </div>

        <footer class="mt-10 text-xs text-[#706f6c] dark:text-[#A1A09A]">
            &copy; {{ date('Y') }} SignSync – Effiziente Begleitung, digitale Lösung.
        </footer>
    </body>
</html>
