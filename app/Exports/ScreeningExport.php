<?php

namespace App\Exports;

use App\Models\Screening;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ScreeningExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithStyles
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Screening::with(['details.riskFactor', 'user.profile'])->latest();

        if ($this->request->filled('q')) {
            $q = $this->request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('client_name', 'like', "%{$q}%")
                    ->orWhere('result_level', 'like', "%{$q}%");
            });
        }

        if ($this->request->filled('filter_risk')) {
            $query->where('result_level', 'like', '%'.$this->request->filter_risk.'%');
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Nama Client',
            'Jenis Kelamin',
            'Usia (Th)',
            'Tinggi (cm)',
            'Berat (kg)',
            'BMI',
            'Tensi (mmHg)',
            'Faktor Risiko Terpilih',
            'Hasil Risiko',
        ];
    }

    public function map($screening): array
    {
        static $no = 0;
        $no++;

        // Hitung BMI
        $bmi = '-';
        if ($screening->snapshot_height && $screening->snapshot_weight) {
            $h = $screening->snapshot_height / 100;
            $bmi = round($screening->snapshot_weight / ($h * $h), 1);
        }

        // Gender
        $gender = $screening->user && $screening->user->profile
            ? ($screening->user->profile->gender == 'L' ? 'Laki-laki' : 'Perempuan')
            : '-';

        // Faktor Risiko (List turun ke bawah)
        $factors = $screening->details->map(function ($detail, $index) {
            return ($index + 1).'. '.($detail->riskFactor ? $detail->riskFactor->name : '-');
        })->implode("\n");

        if (empty($factors)) {
            $factors = 'Tidak ada faktor risiko signifikan';
        }

        return [
            $no,
            $screening->created_at->format('d/m/Y H:i'),
            $screening->client_name,
            $gender,
            $screening->snapshot_age,
            $screening->snapshot_height,
            $screening->snapshot_weight,
            $bmi,
            $screening->snapshot_systolic.'/'.$screening->snapshot_diastolic,
            $factors,
            $screening->result_level,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style Header
        $sheet->getStyle('A1:K1')->getFont()->setBold(true);

        // Enable Text Wrapping untuk kolom Faktor Risiko (J)
        $sheet->getStyle('J')->getAlignment()->setWrapText(true);

        // Align Top untuk semua cell agar rapi jika ada multiline
        $sheet->getStyle('A:K')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
