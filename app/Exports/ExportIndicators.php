<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ExportIndicators implements FromCollection, WithTitle, WithHeadings, ShouldAutoSize, WithStyles, WithEvents
{
    protected $models = [
        'Funding Sources' => \App\Models\fundingSources::class,
        'Technology Knowledge' => \App\Models\technologyKnowledge::class,
        'Public Private' => \App\Models\publicPrivate::class,
        'Patents' => \App\Models\patents::class,
        'Outreach Activities' => \App\Models\outreachActivities::class,
        'Post Doc' => \App\Models\postDoc::class,
        'Thesis Student' => \App\Models\thesisStudent::class,
        'SC Collaborations' => \App\Models\scCollaborations::class,
        'Participation SC Events' => \App\Models\participationScEvents::class,
        'Organizations SC Events' => \App\Models\organizationsScEvents::class,
        'Awards' => \App\Models\awards::class,
        'Books' => \App\Models\books::class,
        'Non-ISI Publications' => \App\Models\nonIsiPublication::class,
        'ISI Publications' => \App\Models\isiPublication::class,
    ];


    public function title(): string
    {
        return 'Indicators';
    }

    public function collection()
    {
        $data = [];

        foreach ($this->models as $title => $modelClass) {
            $recordCount = $modelClass::count();
            $progressCount = $modelClass::where('progressReport', 2)->count();
            $data[] = [$title, $recordCount, $progressCount];
        }

        return new Collection($data);
    }

    public function headings(): array
    {
        return ['Module', 'Registers Count', 'Registers in progress year 2'];
    }


    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:C1')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'ED8D1D'],
            ],
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;

                $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
            },
        ];
    }
}
