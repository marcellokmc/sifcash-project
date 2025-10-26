<?php

namespace App\Exports;

use App\Models\Epargne;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EpargnesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Epargne::with('adherent')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'N° Compte',
            'Adhérent',
            'Type d\'épargne',
            'Montant Initial (FCFA)',
            'Solde Actuel (FCFA)',
            'Intérêts Cumulés (FCFA)',
            'Taux d\'intérêt (%)',
            'Date d\'ouverture',
            'Dernière Opération',
            'Statut',
        ];
    }

    /**
     * @param mixed $epargne
     *
     * @return array
     */
    public function map($epargne): array
    {
        return [
            $epargne->numero_compte,
            $epargne->adherent->nom_complet,
            $epargne->type_epargne_formatted,
            number_format($epargne->montant_initial, 0, ',', ' '),
            number_format($epargne->solde_actuel, 0, ',', ' '),
            number_format($epargne->interet_cumule, 0, ',', ' '),
            number_format($epargne->taux_interet, 2, ',', ' '),
            $epargne->date_ouverture->format('d/m/Y'),
            $epargne->date_derniere_operation ? $epargne->date_derniere_operation->format('d/m/Y H:i') : '-',
            $epargne->statut_formatted,
        ];
    }

    /**
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style pour l'en-tête
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '3490dc']],
                'alignment' => ['horizontal' => 'center'],
            ],
            // Style pour les lignes impaires
            'A2:J' . ($sheet->getHighestRow() + 1) => [
                'borders' => [
                    'allBorders' => ['borderStyle' => 'thin', 'color' => ['rgb' => 'e2e8f0']],
                ],
            ],
            // Style pour les montants
            'D2:F' . $sheet->getHighestRow() => [
                'alignment' => ['horizontal' => 'right'],
            ],
            // Style pour le taux d'intérêt
            'G2:G' . $sheet->getHighestRow() => [
                'alignment' => ['horizontal' => 'right'],
            ],
            // Style pour les dates
            'H2:I' . $sheet->getHighestRow() => [
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
    }
}
