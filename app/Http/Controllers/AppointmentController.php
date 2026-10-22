<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    private const OPEN_HOUR = 9;       
    private const CLOSE_HOUR = 18;    
    private const STEP_MINUTES = 20;   

    // mostra a página de agendamento
    public function create(Request $request)
    {
        $services = Service::orderBy('name')->get();
        $selectedService = null;
        $slots = [];
        $date = $request->query('date');

        if ($request->filled('service_id') && $date && preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            $selectedService = Service::find($request->query('service_id'));
            if ($selectedService) {
                $slots = $this->availableSlots($selectedService, Carbon::parse($date));
            }
        }

        return view('appointments.create', compact('services', 'selectedService', 'slots', 'date'));
    }

    // grava o agendamento
    public function store(Request $request)
    {
        $data = $request->validate([
            'service_id' => 'required|exists:services,id',
            'date' => 'required|date_format:Y-m-d',
            'time' => 'required|date_format:H:i',
            'customer_name' => 'required|string|max:100',
            'customer_phone' => 'required|string|max:20',
        ]);

        $service = Service::findOrFail($data['service_id']);
        $date = Carbon::parse($data['date']);

        // Confere de novo se o horário ainda está livre (outra pessoa pode ter pego)
        if (!in_array($data['time'], $this->availableSlots($service, $date))) {
            return back()->withInput()->withErrors(['time' => 'Esse horário não está mais disponível. Escolha outro.']);
        }

        $start = Carbon::parse($data['date'] . ' ' . $data['time']);

        Appointment::create([
            'service_id' => $service->id,
            'starts_at' => $start,
            'ends_at' => $start->copy()->addMinutes($service->duration_minutes),
            'customer_name' => $data['customer_name'],
            'customer_phone' => $data['customer_phone'],
        ]);

        return redirect()->route('appointments.create')->with('success', 'Agendamento confirmado!');
    }

    // calcula horários livres de um serviço em um dia
    private function availableSlots(Service $service, Carbon $date): array
    {
        $day = $date->copy()->startOfDay();
        $open = $day->copy()->setTime(self::OPEN_HOUR, 0);
        $close = $day->copy()->setTime(self::CLOSE_HOUR, 0);

        // agendamentos que já existem nesse dia
        $booked = Appointment::where('starts_at', '<', $close)
            ->where('ends_at', '>', $open)
            ->get();

        $slots = [];

        for ($start = $open->copy(); $start->lt($close); $start->addMinutes(self::STEP_MINUTES)) {
            $end = $start->copy()->addMinutes($service->duration_minutes);

            // o serviço não pode terminar depois do fechamento
            if ($end->gt($close)) {
                break;
            }

            // não oferece horário que já passou
            if ($start->lt(now())) {
                continue;
            }

            // conflito: existente.starts < novo.end E existente.ends > novo.start
            $conflict = $booked->contains(
                fn ($a) => $a->starts_at->lt($end) && $a->ends_at->gt($start)
            );

            if (!$conflict) {
                $slots[] = $start->format('H:i');
            }
        }

        return $slots;
    }
}