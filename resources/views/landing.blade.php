@extends('layouts.app')
@section('title', 'SignSync - AI Signature Generator')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center">
    <div class="max-w-4xl mx-auto p-8 text-center">
        <h1 class="text-5xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent mb-6">
            SignSync AI
        </h1>
        <p class="text-xl text-gray-700 mb-8 max-w-2xl mx-auto">
            Generiere professionelle Unterschriften in Sekunden. Für E-Mails, PDFs & Web.
        </p>
        <div class="space-x-4">
            <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-xl font-semibold text-lg">
                Loslegen
            </a>
            <a href="{{ route('register') }}" class="border-2 border-blue-600 text-blue-600 hover:bg-blue-50 px-8 py-4 rounded-xl font-semibold text-lg">
                Kostenlos testen
            </a>
        </div>
    </div>
</div>
@endsection
