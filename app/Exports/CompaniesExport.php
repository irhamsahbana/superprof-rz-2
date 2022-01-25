<?php

namespace App\Exports;

use Illuminate\Support\Facades\Http;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\{
    // FromView,
    FromArray,
    ShouldAutoSize,
    WithHeadings,
    WithStyles,
    WithEvents,
};

class CompaniesExport implements ShouldAutoSize, WithHeadings, FromArray, WithStyles, WithEvents
{
    public function __construct()
    {
        $this->response = Http::get('http://127.0.0.1:8000/api/all-company')->json();
        date_default_timezone_set('Asia/Jakarta');
    }

    public function array(): array
    {
        $array = $this->response;
        foreach ($array as $arr) {
            $arr['created_at'] = (string) $arr['created_at'];
        };
        return $array;
    }

    public function headings(): array
    {
        return [
            ['Export Data User'],
            ['Jumlah User ', Count($this->response)],
            ['Di Export Pada ', date("Y-m-d h:i:s")],
            [],
            ['No', 'Nama', 'Email', 'Phone', 'Address', 'Created', 'Update'],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            5    => ['font' => ['bold' => true]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {

                $event->sheet->getDelegate()->getStyle('A5:G5')
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}
