<?php

namespace App\Exports;

use App\Models\Kp;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;

class PurchaseExport implements FromCollection, WithStyles, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    
    protected $supplier;
    protected $etd;

    public function __construct($supplier, $etd)
    {
        $this->supplier = $supplier;
        $this->etd = $etd;
    }

    protected function dateRange($date)
    {
        if(!is_null($date)){
            $dateRange = explode(' - ', $date);
            $startDate = $dateRange[0];
            $endDate = $dateRange[1];
            return [
                $startDate, $endDate
            ];
        } else {
            return null;
        }        
    }

    public function collection()
    {        
        $etdRange = $this->dateRange($this->etd);
        $supplierData = $this->supplier;

        /**
         * Gunakan query dimana jika data $etdRange dan $supplierData kosong, maka akan di lewati
         * Menggunakan metode ini karena untuk menghindari if-else yang terlalu banyak
         */

        $query = Kp::query();
        $query->when($etdRange, function ($get) use ($etdRange) {
            return $get->whereBetween('etd', $etdRange);
        });
        $query->when($supplierData, function ($get) use ($supplierData) {
            return $get->where('supp', $supplierData);
        });
        $purchaseData = $query->get();

        /**
         * Jika hasil $query tidak null, maka data yang berada di dalam excel sesuai dengan data $query
         * Jika hasil $query adalah null, maka data di excel berisi data kosong
         */
        
        if($purchaseData->isNotEmpty()) {
            $data = $this->addCustomHeaderRow($purchaseData);
            return $data;
        } else {
            return collect();
        }        
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
