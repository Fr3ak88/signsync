
@auth
    @if(auth()->user()->plan_name === $planId)
        <button class="btn btn-secondary w-100 py-2 fw-bold" disabled>Aktuelles Paket</button>
    @else
        <form action="{{ route('plans.store') }}" method="POST">
            @csrf
            <input type="hidden" name="plan" value="{{ $planId }}">
            <button type="submit" class="btn {{ $btnClass }} w-100 py-2 fw-bold">
                {{ auth()->user()->plan_name ? 'Paket wechseln' : 'Dieses Paket wählen' }}
            </button>
        </form>
    @endif
@else
    <a href="{{ route('register') }}" class="btn {{ $btnClass }} w-100 py-2 fw-bold">Buchen</a>
@endauth