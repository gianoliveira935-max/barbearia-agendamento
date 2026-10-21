<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Serviços</title>
</head>
<body>
    <h1>Serviços da barbearia</h1>

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

    <h2>Novo serviço</h2>
    <form method="POST" action="{{ route('services.store') }}">
        @csrf
        <p>Nome: <input type="text" name="name" value="{{ old('name') }}"></p>
        <p>Duração (minutos): <input type="number" name="duration_minutes" value="{{ old('duration_minutes') }}"></p>
        <p>Preço (R$): <input type="number" step="0.01" name="price" value="{{ old('price') }}"></p>
        <button type="submit">Cadastrar</button>
    </form>

    <h2>Cadastrados</h2>
    <ul>
        @forelse ($services as $service)
            <li>
                {{ $service->name }} - {{ $service->duration_minutes }} min -
                R$ {{ number_format($service->price_cents / 100, 2, ',', '.') }}
                <form method="POST" action="{{ route('services.destroy', $service) }}" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Excluir</button>
                </form>
            </li>
        @empty
            <li>Nenhum serviço cadastrado.</li>
        @endforelse
    </ul>
</body>
</html>