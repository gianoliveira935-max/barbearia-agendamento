<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Agendar horário</title>
</head>
<body>
    <h1>Agendar horário</h1>

    @if (session('success'))
        <p style="color: green">{{ session('success') }}</p>
    @endif

    @if ($errors->any())
        <ul style="color: red">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <h2>1. Escolha o serviço e a data</h2>
    <form method="GET" action="{{ route('appointments.create') }}">
        <p>
            Serviço:
            <select name="service_id" required>
                <option value="">Selecione</option>
                @foreach ($services as $service)
                    <option value="{{ $service->id }}" @selected(optional($selectedService)->id == $service->id)>
                        {{ $service->name }} ({{ $service->duration_minutes }} min - R$ {{ number_format($service->price_cents / 100, 2, ',', '.') }})
                    </option>
                @endforeach
            </select>
        </p>
        <p>Data: <input type="date" name="date" value="{{ $date }}" min="{{ now()->toDateString() }}" required></p>
        <button type="submit">Ver horários</button>
    </form>

    @if ($selectedService)
        <h2>2. Escolha o horário e informe seus dados</h2>

        @if (count($slots) === 0)
            <p>Nenhum horário disponível nesse dia para {{ $selectedService->name }}.</p>
        @else
            <form method="POST" action="{{ route('appointments.store') }}">
                @csrf
                <input type="hidden" name="service_id" value="{{ $selectedService->id }}">
                <input type="hidden" name="date" value="{{ $date }}">

                <p>Horários livres:</p>
                @foreach ($slots as $slot)
                    <label style="margin-right: 10px">
                        <input type="radio" name="time" value="{{ $slot }}" required> {{ $slot }}
                    </label>
                @endforeach

                <p>Nome: <input type="text" name="customer_name" value="{{ old('customer_name') }}" required></p>
                <p>Telefone: <input type="text" name="customer_phone" value="{{ old('customer_phone') }}" required></p>
                <button type="submit">Confirmar agendamento</button>
            </form>
        @endif
    @endif
</body>
</html>