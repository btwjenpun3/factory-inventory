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
    
    protected $purchase_supplier;
    protected $purchase_etd;

    public function __construct($purchase_supplier, $purchase_etd)
    {
        $this->purchase_supplier = $purchase_supplier;
        $this->purchase_etd = $purchase_etd;
    }

    protected function dateRange($date)
    {
        $dateRange = explode(' - ', $date);
        $startDate = $dateRange[0];
        $endDate = $dateRange[1];
        return [
            $startDate, $endDate
        ];
    }

    public function collection()
    {
        if(isset($this->purchase_etd) && isset($this->purchase_supplier)) {
            $purchaseData = Kp::whereBetween('etd', $this->dateRange($this->purchase_etd))
                            ->where('supp', $this->purchase_supplier)
                            ->get();
            if($purchaseData->isNotEmpty()) {
                $data = $this->addCustomHeaderRow($purchaseData);
                return $data;
            } else {
                return collect();
            }  
        } elseif (isset($this->purchase_supplier)) {
            $purchaseData = Kp::where('supp', $this->purchase_supplier)->get();
            if($purchaseData->isNotEmpty()) {
                $data = $this->addCustomHeaderRow($purchaseData);
                return $data;
            } else {
                return collect();
            }                  
        } elseif (isset($this->purchase_etd)) {
            $purchaseData = Kp::whereBetween('etd', $this->dateRange($this->purchase_etd))->get();
            if($purchaseData->isNotEmpty()) {
                $data = $this->addCustomHeaderRow($purchaseData);
                return $data;
            } else {
                return collect();
            }                  
        } else {
            $purchaseData = Kp::all();
            $data = $this->addCustomHeaderRow($purchaseData);
            return $data;
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
