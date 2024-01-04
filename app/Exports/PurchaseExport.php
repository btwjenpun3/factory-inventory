<?php

namespace App\Exports;

use App\Models\Kp;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;

class PurchaseExport implements FromCollection, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function collection()
    {
        $kpData = Kp::all();
        $data = $this->addCustomHeaderRow($kpData);
        return $data;
    }
    
    private function addCustomHeaderRow(Collection $data)
    {
        $customHeaderRow = [
            'No',
            'KP',
            'Item',
            'Description',
            'Color',
            'Size',
            'UOM',
            'Quantity',
            'UOM1',
            'PO Supplier',
            'PO Buyer',
            'PO Gen',
            'Create Date',
            'Approved',
            'Date Mod',
            'Supplier',
            'AWB',
            'Price',
            'IDR',
            'No Invoice',
            'ETD',
            'Quantity Gar',
            'Status',
            'Del Date',
            'Quantity Received',
            'Quantity Pass QC',
            'Quantity Request',
            'Stock'
        ];
        $result = collect([$customHeaderRow])->concat($data);
        return $result;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'C5D9F1']],
            ],
        ];
    }
    
}
