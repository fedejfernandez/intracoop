<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Lista de Trabajadores</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 1cm;
        }
        
        @media print {
            body { -webkit-print-color-adjust: exact; }
            .no-print { display: none !important; }
        }
        
        * {
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 9px;
            margin: 0;
            padding: 10px;
            line-height: 1.2;
        }
        
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #4F46E5;
            padding-bottom: 8px;
            page-break-inside: avoid;
        }
        
        .header h1 {
            color: #4F46E5;
            margin: 0;
            font-size: 16px;
            font-weight: bold;
        }
        
        .header p {
            margin: 3px 0;
            color: #666;
            font-size: 9px;
        }
        
        .stats {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            background-color: #f8f9fa;
            padding: 8px;
            border-radius: 3px;
            page-break-inside: avoid;
        }
        
        .stats div {
            font-size: 8px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 7px;
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 3px;
            text-align: left;
            vertical-align: top;
        }
        
        th {
            background-color: #4F46E5 !important;
            color: white !important;
            font-weight: bold;
            font-size: 7px;
            text-align: center;
        }
        
        tr:nth-child(even) {
            background-color: #f9f9f9 !important;
        }
        
        .footer {
            position: fixed;
            bottom: 0.5cm;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 7px;
            color: #666;
            border-top: 1px solid #eee;
            padding-top: 5px;
        }
        
        .estado-activo { 
            color: #16a34a !important; 
            font-weight: bold; 
        }
        
        .estado-inactivo { 
            color: #dc2626 !important; 
            font-weight: bold; 
        }
        
        .estado-licencia { 
            color: #ea580c !important; 
            font-weight: bold; 
        }
        
        .estado-vacaciones { 
            color: #0ea5e9 !important; 
            font-weight: bold; 
        }
        
        /* Instrucciones para el usuario */
        .print-instructions {
            background: #e3f2fd;
            border: 1px solid #2196f3;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
            font-size: 11px;
        }
        
        .print-instructions h3 {
            margin: 0 0 8px 0;
            color: #1976d2;
            font-size: 12px;
        }
        
        .print-instructions ol {
            margin: 8px 0 0 0;
            padding-left: 20px;
        }
        
        @media print {
            .print-instructions { display: none; }
        }
        
        /* Responsive para pantalla */
        @media screen and (max-width: 768px) {
            body { font-size: 8px; }
            table { font-size: 6px; }
            th, td { padding: 2px; }
        }
    </style>
    <script>
        // Auto-print cuando se abre el archivo
        window.onload = function() {
            if (window.location.search.includes('auto=print')) {
                setTimeout(function() {
                    window.print();
                }, 1000);
            }
        }
    </script>
</head>
<body>
    <!-- Instrucciones para el usuario -->
    <div class="print-instructions no-print">
        <h3>📄 Instrucciones para generar PDF:</h3>
        <ol>
            <li>Presiona <strong>Ctrl+P</strong> (Windows/Linux) o <strong>Cmd+P</strong> (Mac)</li>
            <li>En el diálogo de impresión, selecciona <strong>"Guardar como PDF"</strong></li>
            <li>Configura: <strong>Orientación → Horizontal</strong> y <strong>Márgenes → Mínimos</strong></li>
            <li>Haz clic en <strong>"Guardar"</strong> y elige la ubicación</li>
        </ol>
        <p><strong>💡 Consejo:</strong> Para mejor resultado, usa Chrome o Firefox</p>
    </div>

    <div class="header">
        <h1>Sistema de Gestión de RRHH - Lista de Trabajadores</h1>
        <p>Generado el: {{ date('d/m/Y H:i:s') }}</p>
        <p>Total de trabajadores: {{ $trabajadores->count() }}</p>
    </div>

    <div class="stats">
        <div>
            <strong>Activos:</strong> {{ $trabajadores->where('Estado', 'Activo')->count() }}
        </div>
        <div>
            <strong>Inactivos:</strong> {{ $trabajadores->where('Estado', 'Inactivo')->count() }}
        </div>
        <div>
            <strong>En Licencia:</strong> {{ $trabajadores->where('Estado', 'Licencia')->count() }}
        </div>
        <div>
            <strong>En Vacaciones:</strong> {{ $trabajadores->where('Estado', 'Vacaciones')->count() }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 8%;">Legajo</th>
                <th style="width: 22%;">Nombre Completo</th>
                <th style="width: 11%;">DNI/CUIL</th>
                <th style="width: 13%;">Puesto</th>
                <th style="width: 15%;">Sector</th>
                <th style="width: 6%;">CCT</th>
                <th style="width: 7%;">Estado</th>
                <th style="width: 6%;">Días Vac.</th>
                <th style="width: 8%;">F. Ingreso</th>
                <th style="width: 4%;">Años</th>
            </tr>
        </thead>
        <tbody>
            @foreach($trabajadores as $trabajador)
                <tr>
                    <td style="text-align: center;">{{ $trabajador->NumeroLegajo }}</td>
                    <td>{{ $trabajador->NombreCompleto }}</td>
                    <td>{{ $trabajador->DNI_CUIL }}</td>
                    <td>{{ Str::limit($trabajador->Puesto ?? 'N/A', 25) }}</td>
                    <td>{{ Str::limit($trabajador->Sector ?? 'N/A', 20) }}</td>
                    <td style="text-align: center;">{{ $trabajador->CCT ?? 'N/A' }}</td>
                    <td class="estado-{{ strtolower($trabajador->Estado) }}" style="text-align: center;">
                        {{ $trabajador->Estado }}
                    </td>
                    <td style="text-align: center;">{{ $trabajador->DiasVacacionesAnuales ?? 'N/A' }}</td>
                    <td style="text-align: center;">{{ $trabajador->FechaIngreso ? $trabajador->FechaIngreso->format('d/m/Y') : 'N/A' }}</td>
                    <td style="text-align: center;">
                        @if($trabajador->FechaIngreso)
                            {{ $trabajador->FechaIngreso->diffInYears(now()) }}
                        @else
                            N/A
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Sistema de Gestión de Recursos Humanos para Cooperativas | 
        Desarrollado por Fedejfernandez | 
        Total: {{ $trabajadores->count() }} trabajadores
    </div>
</body>
</html> 