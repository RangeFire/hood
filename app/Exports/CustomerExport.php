<?php

namespace App\Exports;

use App\Models\System\Customer;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


class CustomerExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            'A1:Z100' => [    
                'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
        ];
    }


    public function headings(): array
    {
        return [
           ['Status', 'Unternehmen', 'Ansprechpartner', 'E-Mail', 'Telefon', 'Adresse', 'Satdt', 'Ländercode', 'Kunde Seit'],
        ];
    }


    public function array():array
    {
        return (Customer::select('status', 'company_name', 'fullname', 'email', 'telephone', 'address', 'city', 'country', 'created_at'))->get()->toArray();
    }
}
