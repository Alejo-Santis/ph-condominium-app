<?php

namespace App\Http\Controllers;

use App\Models\Charge;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ResidentPortalController extends Controller
{
    /**
     * Panel principal del residente: sus cobros y resumen.
     */
    public function index(Request $request)
    {
        $user   = $request->user();
        $person = $user->person;

        if (! $person) {
            return Inertia::render('Portal/NoPerson');
        }

        $charges = Charge::where('person_id', $person->id)
            ->with('unit.tower', 'latestTransaction')
            ->orderByDesc('billing_month')
            ->get();

        $stats = [
            'pending'  => $charges->where('status', 'pending')->count(),
            'overdue'  => $charges->where('status', 'overdue')->count(),
            'paid'     => $charges->where('status', 'paid')->count(),
            'total_pending_amount' => $charges
                ->whereIn('status', ['pending', 'link_generated', 'overdue'])
                ->sum('amount'),
        ];

        return Inertia::render('Portal/Index', [
            'person'  => $person,
            'charges' => $charges,
            'stats'   => $stats,
        ]);
    }

    /**
     * Detalle de un cobro del residente.
     */
    public function show(Request $request, Charge $charge)
    {
        $user = $request->user();

        // Ensure the charge belongs to this resident
        if (! $user->person || $charge->person_id !== $user->person->id) {
            abort(403);
        }

        $charge->load('unit.tower', 'latestTransaction');

        return Inertia::render('Portal/ChargeDetail', [
            'charge' => $charge,
        ]);
    }
}
