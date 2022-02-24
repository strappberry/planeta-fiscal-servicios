<table>
    <thead>
        <tr>
            @foreach ($encabezados as $encabezado)
                <td>
                    {{ $encabezado }}
                </td>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($paginaReporte['lineas'] as $linea)
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