<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Directorio de Clientes</title>
    <style>
        body { font-family: sans-serif; color: #333; margin: 20px; }
        h1 { font-size: 20px; margin-bottom: 5px; }
        p { color: #666; font-size: 12px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px 12px; text-align: left; font-size: 12px; }
        th { background-color: #f3f4f6; font-weight: bold; }
        @media print {
            body { margin: 0; }
        }
    </style>
</head>
<body onload="window.print()">
    <h1>Directorio de Clientes</h1>
    <p>Generado el: {{ date('d/m/Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>NOMBRE</th>
                <th>ALIAS</th>
                <th>DIRECCIÓN</th>
                <th>TELÉFONO</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clients as $client)
                <tr>
                    <td><strong>{{ $client->name }}</strong></td>
                    <td>{{ $client->alias ?? '—' }}</td>
                    <td>{{ $client->address ?? 'Sin dirección' }}</td>
                    <td>{{ $client->phone ?? 'Sin teléfono' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>