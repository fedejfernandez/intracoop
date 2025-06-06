<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TrabajadoresExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $trabajadores;

    public function __construct($trabajadores)
    {
        $this->trabajadores = $trabajadores;
    }

    public function collection()
    {
        return $this->trabajadores;
    }

    public function headings(): array
    {
        return [
            'Legajo',
            'Nombre Completo',
            'DNI/CUIL',
            'Email',
            'Teléfono',
            'Fecha Nacimiento',
            'Fecha Ingreso',
            'Puesto',
            'Sector',
            'CCT',
            'Estado',
            'Días Vacaciones Anuales',
            'Banco',
            'CBU',
            'Dirección',
            'Localidad',
        ];
    }

    public function map($trabajador): array
    {
        return [
            $trabajador->NumeroLegajo,
            $trabajador->NombreCompleto,
            $trabajador->DNI_CUIL,
            $trabajador->Email,
            $trabajador->Telefono,
            $trabajador->FechaNacimiento ? $trabajador->FechaNacimiento->format('d/m/Y') : '',
            $trabajador->FechaIngreso ? $trabajador->FechaIngreso->format('d/m/Y') : '',
            $trabajador->Puesto,
            $trabajador->Sector,
            $trabajador->CCT,
            $trabajador->Estado,
            $trabajador->DiasVacacionesAnuales,
            $trabajador->Banco,
            $trabajador->CBU,
            $trabajador->Direccion,
            $trabajador->Localidad,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Estilo para los encabezados
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F46E5'],
                ],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 12, // Legajo
            'B' => 25, // Nombre
            'C' => 15, // DNI/CUIL
            'D' => 25, // Email
            'E' => 15, // Teléfono
            'F' => 12, // Fecha Nac
            'G' => 12, // Fecha Ing
            'H' => 20, // Puesto
            'I' => 15, // Sector
            'J' => 10, // CCT
            'K' => 10, // Estado
            'L' => 12, // Días Vac
            'M' => 15, // Banco
            'N' => 25, // CBU
            'O' => 30, // Dirección
            'P' => 20, // Localidad
        ];
    }
} 