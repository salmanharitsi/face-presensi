<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class RekapitulasiPresensiExport implements FromCollection, WithHeadings, WithStyles, WithTitle, WithColumnWidths, ShouldAutoSize
{
    private $data;
    private $year;

    public function __construct($data, $year)
    {
        $this->data = $data;
        $this->year = $year;
    }

    public function collection()
    {
        $rows = collect();
        
        foreach ($this->data as $user) {
            $row = collect([$user['name']]);
            
            // Add monthly data (H, HDL, HTLP, TH for each month)
            for ($month = 1; $month <= 12; $month++) {
                $monthData = $user['months'][$month];
                $row->push($monthData['hadir'] ?: 0);
                $row->push($monthData['hadir_dl'] ?: 0);
                $row->push($monthData['hadir_tidak_lapor_pulang'] ?: 0);
                $row->push($monthData['tidak_hadir'] ?: 0);
            }
            
            // Add yearly totals
            $row->push($user['yearly_hadir']);
            $row->push($user['yearly_hadir_dl']);
            $row->push($user['yearly_hadir_tidak_lapor_pulang']);
            $row->push($user['yearly_tidak_hadir']);
            
            $rows->push($row);
        }
        
        return $rows;
    }

    public function headings(): array
    {
        $headers = ['NAMA/BULAN'];
        
        $months = [
            'JANUARI', 'FEBRUARI', 'MARET', 'APRIL', 'MEI', 'JUNI',
            'JULI', 'AGUSTUS', 'SEPTEMBER', 'OKTOBER', 'NOVEMBER', 'DESEMBER'
        ];
        
        foreach ($months as $month) {
            $headers[] = $month . ' H';
            $headers[] = $month . ' HDL';
            $headers[] = $month . ' HTLP';
            $headers[] = $month . ' TH';
        }
        
        $headers[] = 'JUMLAH H';
        $headers[] = 'JUMLAH HDL';
        $headers[] = 'JUMLAH HTLP';
        $headers[] = 'JUMLAH TH';
        
        return $headers;
    }

    public function styles(Worksheet $sheet)
    {
        $totalRows = count($this->data) + 1; // +1 for header
        $totalColumns = $this->getTotalColumns();
        $lastColumn = $this->getColumnLetter($totalColumns);
        
        return [
            // Style the header row
            1 => [
                'font' => ['bold' => true, 'size' => 11],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'FFF2CC'] // Yellow background like the original
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ]
            ],
            
            // Style the first column (names)
            'A2:A' . $totalRows => [
                'font' => ['bold' => true],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ]
            ],
            
            // Style data cells
            'B2:' . $lastColumn . $totalRows => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ]
            ],
            
            // Style yearly total columns with different background
            $this->getYearlyTotalRange($totalRows) => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E2EFDA'] // Light green background for totals
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ]
            ]
        ];
    }

    public function columnWidths(): array
    {
        $widths = ['A' => 25]; // Name column
        
        // Calculate total columns needed (4 columns per month + 4 yearly totals)
        $totalDataColumns = (12 * 4) + 4; // 52 columns total
        
        // Generate column letters dynamically
        $letters = $this->generateColumnLetters($totalDataColumns);
        
        foreach ($letters as $letter) {
            $widths[$letter] = 6; // Smaller width since we have more columns
        }
        
        return $widths;
    }

    public function title(): string
    {
        return 'Rekapitulasi Presensi ' . $this->year;
    }

    /**
     * Generate column letters (B, C, D, ..., Z, AA, AB, etc.)
     */
    private function generateColumnLetters($count)
    {
        $letters = [];
        for ($i = 1; $i <= $count; $i++) {
            $letters[] = $this->getColumnLetter($i + 1); // +1 because A is for names
        }
        return $letters;
    }

    /**
     * Convert column number to Excel column letter
     */
    private function getColumnLetter($columnNumber)
    {
        $letter = '';
        while ($columnNumber > 0) {
            $columnNumber--;
            $letter = chr(65 + ($columnNumber % 26)) . $letter;
            $columnNumber = intval($columnNumber / 26);
        }
        return $letter;
    }

    /**
     * Get total number of columns
     */
    private function getTotalColumns()
    {
        return 1 + (12 * 4) + 4; // Name + (12 months × 4 columns) + 4 yearly totals = 53 columns
    }

    /**
     * Get the range for yearly total columns
     */
    private function getYearlyTotalRange($totalRows)
    {
        $startColumn = $this->getColumnLetter(49); // After 48 monthly columns + name column
        $endColumn = $this->getColumnLetter(52);   // Last 4 columns for yearly totals
        
        return $startColumn . '1:' . $endColumn . $totalRows;
    }
}