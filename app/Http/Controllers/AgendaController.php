<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->query('date');

        $day = ($date && preg_match('/^\d{4}-\d{2}-\d{2}$/', $date))
            ? Carbon::parse($date)
            : now();

        $appointments = Appointment::with('service')
            ->whereBetween('starts_at', [$day->copy()->startOfDay(), $day->copy()->endOfDay()])
            ->orderBy('starts_at')
            ->get();

        return view('agenda.index', [
            'appointments' => $appointments,
            'date' => $day->format('Y-m-d'),
        ]);
    }
}