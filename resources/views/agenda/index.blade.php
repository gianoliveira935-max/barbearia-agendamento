<h1>Agenda do dia</h1>

<form method="GET" action="{{ route('agenda.index') }}">
    <input type="date" name="date" value="{{ $date }}">
    <button type="submit">Ver</button>
</form>

@if ($appointments->isEmpty())
    <p>Nenhum agendamento neste dia.</p>
@else
    <table border="1" cellpadding="6">
        <tr>
            <th>Horário</th>
            <th>Cliente</th>
            <th>Telefone</th>
            <th>Serviço</th>
        </tr>
        @foreach ($appointments as $a)
            <tr>
                <td>{{ $a->starts_at->format('H:i') }} - {{ $a->ends_at->format('H:i') }}</td>
                <td>{{ $a->customer_name }}</td>
                <td>{{ $a->customer_phone }}</td>
                <td>{{ $a->service->name }}</td>
            </tr>
        @endforeach
    </table>
@endif