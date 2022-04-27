<table>
    <tbody>
        <tr>
            <td>RFC</td>
            <td>{{ $info['rfc'] ?? '' }}</td>
        </tr>
        <tr>
            <td>Nombre</td>
            <td>{{ $info['nombre'] ?? '' }}</td>
        </tr>
    </tbody>
</table>
<table>
    <thead>
        <tr>
            @foreach ($encabezados as $encabezado)
                <td>
                    {{ $encabezado ?? '' }}
                </td>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($pagina['lineas'] as $linea)
            <tr>
                @foreach ($linea as $columna)
                    <td>
                        {{ $columna }}
                    </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>