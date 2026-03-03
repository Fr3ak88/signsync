<?php

namespace App\Http\Controllers;

use App\Models\Schueler;
use App\Models\Zeiteintrag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ZeiteintragController extends Controller
{
    public function index()
    {
        $eintraege = Zeiteintrag::with(['schueler', 'user'])
            ->where('user_id', Auth::id())
            ->orderByDesc('datum')
            ->paginate(20);

        return view('zeiteintraege.index', compact('eintraege'));
    }

    public function create()
    {
        $schueler = Schueler::orderBy('name')->get();
        return view('zeiteintraege.create', compact('schueler'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'schueler_id' => 'required|exists:schuelers,id',
            'datum'       => 'required|date',
            'start_zeit'  => 'required|date_format:H:i',
            'ende_zeit'   => 'required|date_format:H:i|after:start_zeit',
            'pause_minuten' => 'nullable|integer|min:0|max:240',
            'signature_data' => 'nullable|string',
        ]);

        $start = Carbon::parse($request->datum . ' ' . $request->start_zeit);
        $ende = Carbon
