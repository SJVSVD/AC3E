<?php

namespace App\Exports;
use App\Models\User;
use App\Models\fundingSources;
use App\Models\technologyKnowledge;
use App\Models\publicPrivate;
use App\Models\patents;
use App\Models\outreachActivities;
use App\Models\postDoc;
use App\Models\thesisStudent;
use App\Models\scCollaborations;
use App\Models\participationScEvents;
use App\Models\organizationsScEvents;
use App\Models\awards;
use App\Models\books;
use App\Models\nonIsiPublication;
use App\Models\isiPublication;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Maatwebsite\Excel\Concerns\WithDefaultStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Sheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

use function Ramsey\Uuid\v1;

class exportIndividual implements WithMultipleSheets, WithDefaultStyles, WithEvents, WithStyles, FromCollection, WithHeadings, ShouldAutoSize, WithProperties, WithTitle
{
    public $idUsuarioDescarga;

    public function __construct($idUsuarioDescarga)
    {
        $this->idUsuarioDescarga = $idUsuarioDescarga;
    }

    public function properties(): array
    {
        $nombreUsuarioDescarga = User::select('name')->find($this->idUsuarioDescarga);
        return [
            'creator'        => $nombreUsuarioDescarga['name'],
            'lastModifiedBy' => $nombreUsuarioDescarga['name'],
            'title'          => 'Consolidado AC3E',
            'description'    => 'Consolidado AC3E',
            'subject'        => 'Consolidado AC3E',
            'keywords'       => 'Consolidado AC3E, Export, Exportar, Excel, Consolidado',
            'category'       => 'Consolidado',
            'manager'        => 'Reporting System',
            'company'        => 'AC3E',
        ];
    }

    public function collection()
    {
        $isiPublications = isiPublication::where('status', 'Finished')->where('idUsuario',$this->idUsuarioDescarga)->get();
        // Convierte el array de objetos en una colección:
        foreach($isiPublications as $isiPublication){
            // Añadir Nombre Usuario:
            $nombreUsuario = User::where('id',$isiPublication['idUsuario'])->get();
            $isiPublication['idUsuario'] = $nombreUsuario[0]['name'];
            
            if($isiPublication['mainResearchers'] == true){
                $isiPublication['mainResearchers'] = 1;
            }else{
                $isiPublication['mainResearchers'] = 0;
            }

            if($isiPublication['associativeResearchers'] == true){
                $isiPublication['associativeResearchers'] = 1;
            }else{
                $isiPublication['associativeResearchers'] = 0;
            }

            if($isiPublication['postDoc'] == true){
                $isiPublication['postDoc'] = 1;
            }else{
                $isiPublication['postDoc'] = 0;
            }

            if($isiPublication['thesisStudents'] == true){
                $isiPublication['thesisStudents'] = 1;
            }else{
                $isiPublication['thesisStudents'] = 0;
            }

            if($isiPublication['nationalExternalResearchers'] == true){
                $isiPublication['nationalExternalResearchers'] = 1;
            }else{
                $isiPublication['nationalExternalResearchers'] = 0;
            }
            
            if($isiPublication['internationalExternalResearchers'] == true){
                $isiPublication['internationalExternalResearchers'] = 1;
            }else{
                $isiPublication['internationalExternalResearchers'] = 0;
            }

        }
        // Ordenar elementos de las facturas:
        $isis = [];
        foreach ($isiPublications as $isiPublication) {
            $newisiPublication = [
                'Id' => $isiPublication['id'],
                'User' => $isiPublication['idUsuario'],
                'Authors' => $isiPublication['authors'],
                'Article Title' => $isiPublication['articleTitle'],
                'Journal Name' => $isiPublication['journalName'],
                'Digital Object Identifier (DOI)' => $isiPublication['doi'],
                'Volume' => $isiPublication['volume'],
                'First Page' => $isiPublication['firstPage'],
                'Last Page' => $isiPublication['lastPage'],
                'Funding' => $isiPublication['funding'],
                'Main Researchers' => $isiPublication['mainResearchers'],
                'Associative Researchers' => $isiPublication['associativeResearchers'],
                'Post Doc' => $isiPublication['postDoc'],
                'Thesis Students' => $isiPublication['thesisStudents'],
                'National External Researchers' => $isiPublication['nationalExternalResearchers'],
                'International External Researchers' => $isiPublication['internationalExternalResearchers'],
                'Comments' => $isiPublication['comments'],
            ];
            array_push($isis, $newisiPublication);
        }
        // Convierte el array de objetos en una colección:
        $colection = new Collection($isis);
        // Convierte la colección en modelos de Eloquent:
        $model = User::hydrate($colection->toArray());
        return $model;
    }

    public function headings(): array
    {
        return ['No.','User','Authors','Article Title','Journal Name','Digital Object Identifier (DOI)','Volume','First Page', 'Last Page','Funding','Main Researchers','Associative Researchers','Postdoc','Thesis Students','National External Researchers','Internacional External Researchers','Comments'];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Obtiene la hoja activa
                $sheet = $event->sheet;
                // Se le aplica autofilter al header
                $sheet->setAutoFilter('A1:Q1');
            },
        ];
    }

    public function defaultStyles(Style $defaultStyle)
    {
        // FONT:
        $font = $defaultStyle->getFont();
        $font->setSize(11);
        $font->setName('Calibri');
        $font->setBold(false);
        $font->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK));
        // FILL:
        $fill = $defaultStyle->getFill();
        $fill->setFillType(Fill::FILL_NONE);
        // $fill->getStartColor()->setARGB('FF0000');
        // ALIGNMENT:
        $alignment = $defaultStyle->getAlignment();
        $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        return $defaultStyle;
    }

    public function styles(Worksheet $sheet){
        // Header:
        $sheet->getStyle('A1:Q1')->applyFromArray([
            'font' => [
                'Calibri' => true,
                'bold' => true,
                'size' => 12,
                'color' => [
                    'rgb' => '000000',
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'FFC000',
                ],
            ],
        ]);
    }

    public function title(): string
    {
        return 'A1 ISI PUBLICATIONS';
    }

    public function sheets(): array
    {
        $idUsuarioDescarga = $this->idUsuarioDescarga;
        // Crea la primera hoja con los datos actuales
        $sheets = [
            'A1 ISI PUBLICATIONS' => $this,
        ];
        // Agrega la segunda hoja con los nuevos datos
        $sheets['A2 NON ISI PUBLICATIONS'] = new class ($idUsuarioDescarga) implements FromCollection, WithHeadings, WithTitle, WithDefaultStyles, WithEvents, WithStyles, ShouldAutoSize {
            
            private $idUsuarioDescarga;

            public function __construct($idUsuarioDescarga)
            {
                $this->idUsuarioDescarga = $idUsuarioDescarga;
            }

            public function collection()
            {
                // Convierte el array de objetos en una colección:
                $nonIsiPublications = nonIsiPublication::where('status', 'Finished')->where('idUsuario',$this->idUsuarioDescarga)->get();
                foreach($nonIsiPublications as $nonIsiPublication){
                    // Añadir Nombre Usuario:
                    $nombreUsuario = User::where('id',$nonIsiPublication['idUsuario'])->get();
                    $nonIsiPublication['idUsuario'] = $nombreUsuario[0]['name'];
                }
                // Ordenar elementos de las facturas:
                $nonIsis = [];
                foreach ($nonIsiPublications as $nonIsiPublication) {
                    $newNonIsiPublication = [
                        'Id' => $nonIsiPublication['id'],
                        'User' => $nonIsiPublication['idUsuario'],
                        // 'Progress Report' => $nonIsiPublication['progressReport'],
                        'Authors' => $nonIsiPublication['authors'],
                        'Article Title' => $nonIsiPublication['articleTitle'],
                        'Journal Name' => $nonIsiPublication['journalName'],
                        'Volume' => $nonIsiPublication['volume'],
                        'First Page' => $nonIsiPublication['firstPage'],
                        'Last Page' => $nonIsiPublication['lastPage'],
                        'Funding' => $nonIsiPublication['funding'],
                        'Main Researchers' => $nonIsiPublication['mainResearchers'],
                        'Associative Researchers' => $nonIsiPublication['associativeResearchers'],
                        'Post Doc' => $nonIsiPublication['postDoc'],
                        'Thesis Students' => $nonIsiPublication['thesisStudents'],
                        'National External Researchers' => $nonIsiPublication['nationalExternalResearchers'],
                        'International External Researchers' => $nonIsiPublication['internationalExternalResearchers'],
                        'Comments' => $nonIsiPublication['comments'],
                    ];
                    array_push($nonIsis, $newNonIsiPublication);
                }
                // Convierte el array de objetos en una colección:
                $colection = new Collection($nonIsis);
                // Convierte la colección en modelos de Eloquent:
                $model = User::hydrate($colection->toArray());
                return $model;
            }

            public function headings(): array
            {
                return ['Id', 'User','Authors','Article Title','Journal Name','Volume','First Page','Last Page','Funding','Main Researchers','Associative Researchers','Post Doc','Thesis Students','National External Researchers','International External Researchers','Comments'];
            }

            public function defaultStyles(Style $defaultStyle)
            {
                // FONT:
                $font = $defaultStyle->getFont();
                $font->setSize(11);
                $font->setName('Calibri');
                $font->setBold(false);
                $font->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK));
                // FILL:
                $fill = $defaultStyle->getFill();
                $fill->setFillType(Fill::FILL_NONE);
                // $fill->getStartColor()->setARGB('FF0000');
                // ALIGNMENT:
                $alignment = $defaultStyle->getAlignment();
                $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                return $defaultStyle;
            }

            public function styles(Worksheet $sheet){
                // Header:
                $sheet->getStyle('A1:P1')->applyFromArray([
                    'font' => [
                        'Calibri' => true,
                        'bold' => true,
                        'size' => 12,
                        'color' => [
                            'rgb' => '000000',
                        ],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'FFC000',
                        ],
                    ],
                ]);
            }

            public function registerEvents(): array
            {
                return [
                    AfterSheet::class => function (AfterSheet $event) {
                        // Obtiene la hoja activa
                        $sheet = $event->sheet;
                        // Se le aplica autofilter al header
                        $sheet->setAutoFilter('A1:P1');
                    },
                ];
            }

            public function title(): string
            {
                return 'A2 NON ISI PUBLICATIONS';
            }

            
        };

        $sheets['A3 BOOKS'] = new class ($idUsuarioDescarga) implements FromCollection, WithHeadings, WithTitle, WithDefaultStyles, WithEvents, WithStyles, ShouldAutoSize {
            
            private $idUsuarioDescarga;

            public function __construct($idUsuarioDescarga)
            {
                $this->idUsuarioDescarga = $idUsuarioDescarga;
            }

            public function collection()
            {
                $books = books::where('status', 'Finished')->where('centerResearcher',$this->idUsuarioDescarga)->get();
                // Convierte el array de objetos en una colección:
                foreach($books as $book){
                    // Añadir Nombre Usuario:
                    $nombreUsuario = User::where('id',$book['centerResearcher'])->get();
                    $book['centerResearcher'] = $nombreUsuario[0]['name'];
                }
                // Ordenar elementos de las facturas:
                $booksArray = [];
                foreach ($books as $book) {
                    $newBook = [
                        'Id' => $book['id'],
                        'User' => $book['centerResearcher'],
                        // 'Progress Report' => $book['progressReport'],
                        'Books Authors' => $book['bookAuthors'],
                        'Chapter Authors' => $book['chapterAuthors'],
                        'Title of the Book' => $book['bookTitle'],
                        'Chapter Title' => $book['chapterTitle'],
                        'First Page' => $book['firstPage'],
                        'Last Page' => $book['lastPage'],
                        'Editorial/City/Country' => $book['editorialCityCountry'],
                        'Date' => $book['year'],
                        'Comments' => $book['comments'],
                    ];
                    array_push($booksArray, $newBook);
                }
                // Convierte el array de objetos en una colección:
                $colection = new Collection($booksArray);
                // Convierte la colección en modelos de Eloquent:
                $model = User::hydrate($colection->toArray());
                return $model;
            }

            public function headings(): array
            {
                return ['Id', 'User','Books Authors','Chapter Authors','Title of the Book','Chapter Title','First Page','Last Page','Editorial/City/Country','Date','Comments'];
            }

            public function defaultStyles(Style $defaultStyle)
            {
                // FONT:
                $font = $defaultStyle->getFont();
                $font->setSize(11);
                $font->setName('Calibri');
                $font->setBold(false);
                $font->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK));
                // FILL:
                $fill = $defaultStyle->getFill();
                $fill->setFillType(Fill::FILL_NONE);
                // $fill->getStartColor()->setARGB('FF0000');
                // ALIGNMENT:
                $alignment = $defaultStyle->getAlignment();
                $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                return $defaultStyle;
            }

            public function styles(Worksheet $sheet){
                // Header:
                $sheet->getStyle('A1:K1')->applyFromArray([
                    'font' => [
                        'Calibri' => true,
                        'bold' => true,
                        'size' => 12,
                        'color' => [
                            'rgb' => '000000',
                        ],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'FFC000',
                        ],
                    ],
                ]);
            }

            public function registerEvents(): array
            {
                return [
                    AfterSheet::class => function (AfterSheet $event) {
                        // Obtiene la hoja activa
                        $sheet = $event->sheet;
                        // Se le aplica autofilter al header
                        $sheet->setAutoFilter('A1:K1');
                    },
                ];
            }

            public function title(): string
            {
                return 'A3 BOOKS';
            }

            
        };


        $sheets['A4 AWARDS'] = new class ($idUsuarioDescarga) implements FromCollection, WithHeadings, WithTitle, WithDefaultStyles, WithEvents, WithStyles, ShouldAutoSize {
            
            private $idUsuarioDescarga;

            public function __construct($idUsuarioDescarga)
            {
                $this->idUsuarioDescarga = $idUsuarioDescarga;
            }

            public function collection()
            {
                $awards = awards::where('status', 'Finished')->where('idUsuario',$this->idUsuarioDescarga)->get();
                // Convierte el array de objetos en una colección:
                foreach($awards as $award){
                    // Añadir Nombre Usuario:
                    $nombreUsuario = User::where('id',$award['idUsuario'])->get();
                    $award['idUsuario'] = isset($nombreUsuario[0]['name']) ? $nombreUsuario[0]['name'] : 'Undefined';;
                }
                // Ordenar elementos de las facturas:
                $awardsArray = [];
                foreach ($awards as $award) {
                    $newAward = [
                        'Id' => $award['id'],
                        'User' => $award['idUsuario'],
                        'Awardee Name' => $award['awardeeName'],
                        'Award Name' => $award['awardName'],
                        'Year' => $award['year'],
                        // 'Progress Report' => $award['progressReport'],
                        'Constribution of the Awardee' => $award['contributionAwardee'],
                        'Awarding Institution' => $award['institution'],
                        'Country' => $award['country'],
                        'Comments' => $award['comments'],
                    ];
                    array_push($awardsArray, $newAward);
                }
                // Convierte el array de objetos en una colección:
                $colection = new Collection($awardsArray);
                // Convierte la colección en modelos de Eloquent:
                $model = User::hydrate($colection->toArray());
                return $model;
            }

            public function headings(): array
            {
                return ['Id','User', 'Awardee Name','Award Name','Year','Constribution of the Awardee','Awarding Institution','Country','Comments'];
            }

            public function defaultStyles(Style $defaultStyle)
            {
                // FONT:
                $font = $defaultStyle->getFont();
                $font->setSize(11);
                $font->setName('Calibri');
                $font->setBold(false);
                $font->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK));
                // FILL:
                $fill = $defaultStyle->getFill();
                $fill->setFillType(Fill::FILL_NONE);
                // $fill->getStartColor()->setARGB('FF0000');
                // ALIGNMENT:
                $alignment = $defaultStyle->getAlignment();
                $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                return $defaultStyle;
            }

            public function styles(Worksheet $sheet){
                // Header:
                $sheet->getStyle('A1:I1')->applyFromArray([
                    'font' => [
                        'Calibri' => true,
                        'bold' => true,
                        'size' => 12,
                        'color' => [
                            'rgb' => '000000',
                        ],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'FFC000',
                        ],
                    ],
                ]);
            }

            public function registerEvents(): array
            {
                return [
                    AfterSheet::class => function (AfterSheet $event) {
                        // Obtiene la hoja activa
                        $sheet = $event->sheet;
                        // Se le aplica autofilter al header
                        $sheet->setAutoFilter('A1:I1');
                    },
                ];
            }

            public function title(): string
            {
                return 'A4 AWARDS';
            }

            
        };

        $sheets['A5 ORGANIZATION OF SC EVENTS'] = new class ($idUsuarioDescarga) implements FromCollection, WithHeadings, WithTitle, WithDefaultStyles, WithEvents, WithStyles, ShouldAutoSize {
            
            private $idUsuarioDescarga;

            public function __construct($idUsuarioDescarga)
            {
                $this->idUsuarioDescarga = $idUsuarioDescarga;
            }
            
            public function collection()
            {
                $organizationsScEvents = organizationsScEvents::where('status', 'Finished')->where('idUsuario',$this->idUsuarioDescarga)->get();
                // Convierte el array de objetos en una colección:
                foreach($organizationsScEvents as $organization){
                    // Añadir Nombre Usuario:
                    $nombreUsuario = User::where('id',$organization['idUsuario'])->get();
                    $organization['idUsuario'] = $nombreUsuario[0]['name'];
                }
                // Ordenar elementos de las facturas:
                $organizationArray = [];
                foreach ($organizationsScEvents as $organization) {
                    $newOrganization = [
                        'Id' => $organization['id'],
                        'User' => $organization['idUsuario'],
                        'Type of event' => $organization['typeEvent'],
                        // 'Progress Report' => $organization['progressReport'],
                        'Name course/congress' => $organization['eventName'],
                        'Number of participants' => $organization['numberParticipants'],
                        'Country' => $organization['country'],
                        'City' => $organization['city'],
                        'Start Date' => $organization['startDate'],
                        'Ending Date' => $organization['endingDate'],
                        'Comments' => $organization['comments'],
                    ];
                    array_push($organizationArray, $newOrganization);
                }
                // Convierte el array de objetos en una colección:
                $colection = new Collection($organizationArray);
                // Convierte la colección en modelos de Eloquent:
                $model = User::hydrate($colection->toArray());
                return $model;
            }

            public function headings(): array
            {
                return ['Id', 'User','Type of event','Name course/congress','Number of participants','Country','City','Start Date','Ending Date','Comments'];
            }

            public function defaultStyles(Style $defaultStyle)
            {
                // FONT:
                $font = $defaultStyle->getFont();
                $font->setSize(11);
                $font->setName('Calibri');
                $font->setBold(false);
                $font->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK));
                // FILL:
                $fill = $defaultStyle->getFill();
                $fill->setFillType(Fill::FILL_NONE);
                // $fill->getStartColor()->setARGB('FF0000');
                // ALIGNMENT:
                $alignment = $defaultStyle->getAlignment();
                $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                return $defaultStyle;
            }

            public function styles(Worksheet $sheet){
                // Header:
                $sheet->getStyle('A1:J1')->applyFromArray([
                    'font' => [
                        'Calibri' => true,
                        'bold' => true,
                        'size' => 12,
                        'color' => [
                            'rgb' => '000000',
                        ],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'FFC000',
                        ],
                    ],
                ]);
            }

            public function registerEvents(): array
            {
                return [
                    AfterSheet::class => function (AfterSheet $event) {
                        // Obtiene la hoja activa
                        $sheet = $event->sheet;
                        // Se le aplica autofilter al header
                        $sheet->setAutoFilter('A1:J1');
                    },
                ];
            }

            public function title(): string
            {
                return 'A5 ORGANIZATION OF SC EVENTS';
            }

            
        };

        $sheets['A6 PARTICIPATION OF SC EVENTS'] = new class ($idUsuarioDescarga) implements FromCollection, WithHeadings, WithTitle, WithDefaultStyles, WithEvents, WithStyles, ShouldAutoSize {
            
            private $idUsuarioDescarga;

            public function __construct($idUsuarioDescarga)
            {
                $this->idUsuarioDescarga = $idUsuarioDescarga;
            }
            
            public function collection()
            {
                $participationScEvents = participationScEvents::where('status', 'Finished')->where('idUsuario',$this->idUsuarioDescarga)->get();
                // Convierte el array de objetos en una colección:
                foreach($participationScEvents as $participation){
                    // Añadir Nombre Usuario:
                    $nombreUsuario = User::where('id',$participation['idUsuario'])->get();
                    $participation['idUsuario'] = $nombreUsuario[0]['name'];
                }
                // Ordenar elementos de las facturas:
                $participationArray = [];
                foreach ($participationScEvents as $participation) {
                    $newParticipation = [
                        'Id' => $participation['id'],
                        'User' => $participation['idUsuario'],
                        'Progress report' => $participation['progressReport'],
                        'Type of event' => $participation['typeEvent'],
                        // 'Progress Report' => $participation['progressReport'],
                        'Name course/congress' => $participation['eventName'],
                        'Number of participants' => $participation['numberParticipants'],
                        'Country' => $participation['country'],
                        'City' => $participation['city'],
                        'Start Date' => $participation['startDate'],
                        'Ending Date' => $participation['endingDate'],
                        'Comments' => $participation['comments'],
                    ];
                    array_push($participationArray, $newParticipation);
                }
                // Convierte el array de objetos en una colección:
                $colection = new Collection($participationArray);
                // Convierte la colección en modelos de Eloquent:
                $model = User::hydrate($colection->toArray());
                return $model;
            }

            public function headings(): array
            {
                return ['Id', 'User','Progress report','Type of event','Name course/congress','Number of participants','Country','City','Start Date','Ending Date','Type of participation','Comments'];
            }

            public function defaultStyles(Style $defaultStyle)
            {
                // FONT:
                $font = $defaultStyle->getFont();
                $font->setSize(11);
                $font->setName('Calibri');
                $font->setBold(false);
                $font->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK));
                // FILL:
                $fill = $defaultStyle->getFill();
                $fill->setFillType(Fill::FILL_NONE);
                // $fill->getStartColor()->setARGB('FF0000');
                // ALIGNMENT:
                $alignment = $defaultStyle->getAlignment();
                $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                return $defaultStyle;
            }

            public function styles(Worksheet $sheet){
                // Header:
                $sheet->getStyle('A1:L1')->applyFromArray([
                    'font' => [
                        'Calibri' => true,
                        'bold' => true,
                        'size' => 12,
                        'color' => [
                            'rgb' => '000000',
                        ],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'FFC000',
                        ],
                    ],
                ]);
            }

            public function registerEvents(): array
            {
                return [
                    AfterSheet::class => function (AfterSheet $event) {
                        // Obtiene la hoja activa
                        $sheet = $event->sheet;
                        // Se le aplica autofilter al header
                        $sheet->setAutoFilter('A1:L1');
                    },
                ];
            }

            public function title(): string
            {
                return 'A6 PARTICIPATION OF SC EVENTS';
            }

            
        };

        $sheets['A7 COLLABORATION'] = new class ($idUsuarioDescarga) implements FromCollection, WithHeadings, WithTitle, WithDefaultStyles, WithEvents, WithStyles, ShouldAutoSize {
            
            private $idUsuarioDescarga;

            public function __construct($idUsuarioDescarga)
            {
                $this->idUsuarioDescarga = $idUsuarioDescarga;
            }

            public function collection()
            {
                $collaborations = scCollaborations::where('status', 'Finished')->where('idUsuario',$this->idUsuarioDescarga)->get();
                // Convierte el array de objetos en una colección:
                foreach($collaborations as $collaboration){
                    // Añadir Nombre Usuario:
                    $nombreUsuario = User::where('id',$collaboration['idUsuario'])->get();
                    $collaboration['idUsuario'] = $nombreUsuario[0]['name'];
                }
                // Ordenar elementos de las facturas:
                $collaborationsArray = [];
                foreach ($collaborations as $collaboration) {
                    $newCollaboration = [
                        'Id' => $collaboration['id'],
                        'User' => $collaboration['idUsuario'],
                        'Progress Report' => $collaboration['progressReport'],
                        'Activity Name' => $collaboration['activityName'],
                        // 'Progress Report' => $collaboration['progressReport'],
                        'Country Origin' => $collaboration['countryOrigin'],
                        'City Origin' => $collaboration['cityOrigin'],
                        'Country Destination' => $collaboration['countryDestination'],
                        'City Destination' => $collaboration['cityDestination'],
                        'Institution with which the Center collaborates' => $collaboration['institutionCollaborates'],
                        'Beginning Date' => $collaboration['beginningDate'],
                        'Ending Date' => $collaboration['endingDate'],
                        'Comments' => $collaboration['comments'],
                    ];
                    array_push($collaborationsArray, $newCollaboration);
                }
                // Convierte el array de objetos en una colección:
                $colection = new Collection($collaborationsArray);
                // Convierte la colección en modelos de Eloquent:
                $model = User::hydrate($colection->toArray());
                return $model;
            }

            public function headings(): array
            {
                return ['Id', 'User','Progress report','Activity Name','Country Origin','City Origin','Country Destination','City Destination','Institution with which the Center collaborates','Beginning Date','Ending Date','Comments'];
            }

            public function defaultStyles(Style $defaultStyle)
            {
                // FONT:
                $font = $defaultStyle->getFont();
                $font->setSize(11);
                $font->setName('Calibri');
                $font->setBold(false);
                $font->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK));
                // FILL:
                $fill = $defaultStyle->getFill();
                $fill->setFillType(Fill::FILL_NONE);
                // $fill->getStartColor()->setARGB('FF0000');
                // ALIGNMENT:
                $alignment = $defaultStyle->getAlignment();
                $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                return $defaultStyle;
            }

            public function styles(Worksheet $sheet){
                // Header:
                $sheet->getStyle('A1:L1')->applyFromArray([
                    'font' => [
                        'Calibri' => true,
                        'bold' => true,
                        'size' => 12,
                        'color' => [
                            'rgb' => '000000',
                        ],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'FFC000',
                        ],
                    ],
                ]);
            }

            public function registerEvents(): array
            {
                return [
                    AfterSheet::class => function (AfterSheet $event) {
                        // Obtiene la hoja activa
                        $sheet = $event->sheet;
                        // Se le aplica autofilter al header
                        $sheet->setAutoFilter('A1:L1');
                    },
                ];
            }

            public function title(): string
            {
                return 'A7 COLLABORATION';
            }

            
        };

        $sheets['A8 THESES-STUDENTS'] = new class ($idUsuarioDescarga) implements FromCollection, WithHeadings, WithTitle, WithDefaultStyles, WithEvents, WithStyles, ShouldAutoSize {
            
            private $idUsuarioDescarga;

            public function __construct($idUsuarioDescarga)
            {
                $this->idUsuarioDescarga = $idUsuarioDescarga;
            }
            
            public function collection()
            {
                $theses = thesisStudent::where('status', 'Finished')->where('idUsuario',$this->idUsuarioDescarga)->get();
                // Convierte el array de objetos en una colección:
                foreach($theses as $these){
                    // Añadir Nombre Usuario:
                    $nombreUsuario = User::where('id',$these['idUsuario'])->get();
                    $these['idUsuario'] = $nombreUsuario[0]['name'];
                }
                // Ordenar elementos de las facturas:
                $thesisArray = [];
                foreach ($theses as $these) {
                    $newThesis = [
                        'Id' => $these['id'],
                        'User' => $these['idUsuario'],
                        'Student Name' => $these['studentName'],
                        'RUN or Passport' => $these['runOrPassport'],
                        // 'Progress Report' => $these['progressReport'],
                        'Gender' => $these['gender'],
                        'Student Mail' => $these['studentMail'],
                        'Thesis Status' => $these['thesisStatus'],
                        'Thesis Title' => $these['thesisTitle'],
                        'Academic Degree' => $these['academicDegree'],
                        'Degree Denomination' => $these['degreeDenomination'],
                        'Tutor Name' => $these['tutorName'],
                        'Tutor Institution' => $these['tutorInstitution'],
                        'Cotutor Name' => $these['cotutorName'],
                        'Cotutor Institution' => $these['cotutorInstitution'],
                        'Other Name' => $these['otherName'],
                        'Other Institution' => $these['otherInstitution'],
                        'University that gives the degree' => $these['university'],
                        'Year in which thesis starts' => $these['yearStart'],
                        'Year in which thesis ends' => $these['yearThesisEnd'],
                        'Resources provided by the Center' => $these['resourcesCenter'],
                        'Posterior working area' => $these['posteriorArea'],
                        'Institution of Posterior working area' => $these['institutionPosteriorArea'],
                        'Comments' => $these['comments'],
         ];
                    array_push($thesisArray, $newThesis);
                }
                // Convierte el array de objetos en una colección:
                $colection = new Collection($thesisArray);
                // Convierte la colección en modelos de Eloquent:
                $model = User::hydrate($colection->toArray());
                return $model;
            }

            public function headings(): array
            {
                return ['Id', 'User','Student Name','RUN or Passport','Gender','Student Mail','Thesis Status','Thesis Title','Academic Degree','Degree Denomination','Tutor Name','Tutor Institution','Cotutor Name','Cotutor Institution','Other Name','Other Institution','University that gives the degree','Year in which thesis starts','Year in which thesis starts','Resources provided by the Center','Posterior working area','Institution of Posterior working area','Comments'];
            }

            public function defaultStyles(Style $defaultStyle)
            {
                // FONT:
                $font = $defaultStyle->getFont();
                $font->setSize(11);
                $font->setName('Calibri');
                $font->setBold(false);
                $font->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK));
                // FILL:
                $fill = $defaultStyle->getFill();
                $fill->setFillType(Fill::FILL_NONE);
                // $fill->getStartColor()->setARGB('FF0000');
                // ALIGNMENT:
                $alignment = $defaultStyle->getAlignment();
                $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                return $defaultStyle;
            }

            public function styles(Worksheet $sheet){
                // Header:
                $sheet->getStyle('A1:W1')->applyFromArray([
                    'font' => [
                        'Calibri' => true,
                        'bold' => true,
                        'size' => 12,
                        'color' => [
                            'rgb' => '000000',
                        ],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'FFC000',
                        ],
                    ],
                ]);
            }

            public function registerEvents(): array
            {
                return [
                    AfterSheet::class => function (AfterSheet $event) {
                        // Obtiene la hoja activa
                        $sheet = $event->sheet;
                        // Se le aplica autofilter al header
                        $sheet->setAutoFilter('A1:W1');
                    },
                ];
            }

            public function title(): string
            {
                return 'A8 THESES-STUDENTS';
            }

            
        };

        $sheets['A9 POSTDOCTORAL FELLOWS'] = new class ($idUsuarioDescarga) implements FromCollection, WithHeadings, WithTitle, WithDefaultStyles, WithEvents, WithStyles, ShouldAutoSize {
            
            private $idUsuarioDescarga;

            public function __construct($idUsuarioDescarga)
            {
                $this->idUsuarioDescarga = $idUsuarioDescarga;
            }
            
            public function collection()
            {
                $postDocs = postDoc::where('status', 'Finished')->where('idUsuario',$this->idUsuarioDescarga)->get();
                // Convierte el array de objetos en una colección:
                foreach($postDocs as $postDoc){
                    // Añadir Nombre Usuario:
                    $nombreUsuario = User::where('id',$postDoc['idUsuario'])->get();
                    $postDoc['idUsuario'] = $nombreUsuario[0]['name'];
                }
                // Ordenar elementos de las facturas:
                $postDocArray = [];
                foreach ($postDocs as $postDoc) {
                    $newPostDoc = [
                        'Id' => $postDoc['id'],
                        'User' => $postDoc['idUsuario'],
                        'Name of Postdoctoral Fellows' => $postDoc['nameOfPostdoc'],
                        'RUN/Passport' => $postDoc['runOrPassport'],
                        'Gender' => $postDoc['gender'],
                        'Research Topic' => $postDoc['researchTopic'],
                        'Tutor name' => $postDoc['supervisorName'],
                        'Associated Institution' => $postDoc['institutionName'],
                        'Funding Source' => $postDoc['fundingSource'],
                        'Starting Date' => $postDoc['startYear'],
                        'Ending Date' => $postDoc['endingYear'],
                        'Resources provided by the Center' => $postDoc['resourcesProvided'],
                        'Comments' => $postDoc['comments'],
                    ];
                    array_push($postDocArray, $newPostDoc);
                }
                // Convierte el array de objetos en una colección:
                $colection = new Collection($postDocArray);
                // Convierte la colección en modelos de Eloquent:
                $model = User::hydrate($colection->toArray());
                return $model;
            }

            public function headings(): array
            {
                return ['Id', 'User','Name of Postdoctoral Fellows','RUN/Passport','Gender','Research Topic','Tutor name','Associated Institution','Funding Source','Starting Date','Ending Date','Resources provided by the Center','Comments'];
            }

            public function defaultStyles(Style $defaultStyle)
            {
                // FONT:
                $font = $defaultStyle->getFont();
                $font->setSize(11);
                $font->setName('Calibri');
                $font->setBold(false);
                $font->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK));
                // FILL:
                $fill = $defaultStyle->getFill();
                $fill->setFillType(Fill::FILL_NONE);
                // $fill->getStartColor()->setARGB('FF0000');
                // ALIGNMENT:
                $alignment = $defaultStyle->getAlignment();
                $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                return $defaultStyle;
            }

            public function styles(Worksheet $sheet){
                // Header:
                $sheet->getStyle('A1:M1')->applyFromArray([
                    'font' => [
                        'Calibri' => true,
                        'bold' => true,
                        'size' => 12,
                        'color' => [
                            'rgb' => '000000',
                        ],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'FFC000',
                        ],
                    ],
                ]);
            }

            public function registerEvents(): array
            {
                return [
                    AfterSheet::class => function (AfterSheet $event) {
                        // Obtiene la hoja activa
                        $sheet = $event->sheet;
                        // Se le aplica autofilter al header
                        $sheet->setAutoFilter('A1:M1');
                    },
                ];
            }

            public function title(): string
            {
                return 'A9 POSTDOCTORAL FELLOWS';
            }

            
        };

        $sheets['A10 OUTREACH'] = new class ($idUsuarioDescarga) implements FromCollection, WithHeadings, WithTitle, WithDefaultStyles, WithEvents, WithStyles, ShouldAutoSize {
            
            private $idUsuarioDescarga;

            public function __construct($idUsuarioDescarga)
            {
                $this->idUsuarioDescarga = $idUsuarioDescarga;
            }
            
            public function collection()
            {
                $outreachs = outreachActivities::where('status', 'Finished')->where('idUsuario',$this->idUsuarioDescarga)->get();
                // Convierte el array de objetos en una colección:
                foreach($outreachs as $outreach){
                    // Añadir Nombre Usuario:
                    $nombreUsuario = User::where('id',$outreach['idUsuario'])->get();
                    $outreach['idUsuario'] = $nombreUsuario[0]['name'];
                }
                // Ordenar elementos de las facturas:
                $outreachArray = [];
                foreach ($outreachs as $outreach) {
                    $newOutreach = [
                        'Id' => $outreach['id'],
                        'User' => $outreach['idUsuario'],
                        'Progress Report' => $outreach['progressReport'],
                        'Type of Activity' => $outreach['activityType'],
                        'Event Title' => $outreach['activityName'],
                        'Activity Description' => $outreach['activityDescription'],
                        'Date' => $outreach['date'],
                        'Attendant Amount' => $outreach['attendantsAmount'],
                        'Duration (days)' => $outreach['duration'],
                        'Place Region' => $outreach['placeRegion'],
                        'Undergraduate Students' => $outreach['undergraduateStudents'],
                        'Primary Education Students' => $outreach['primaryEducationStudents'],
                        'Secondary Education Students' => $outreach['secondaryEducationStudents'],
                        'General Community' => $outreach['generalCommunity'],
                        'Companies,Industries,Services' => $outreach['companiesIndustriesServices'],
                        'School Teachers' => $outreach['schoolTeachers'],
                        'Government Official' => $outreach['governmentOfficial'],
                        'Other' => $outreach['other'],
                        'Comments' => $outreach['comments'],
                    ];
                    array_push($outreachArray, $newOutreach);
                }
                // Convierte el array de objetos en una colección:
                $colection = new Collection($outreachArray);
                // Convierte la colección en modelos de Eloquent:
                $model = User::hydrate($colection->toArray());
                return $model;
            }

            public function headings(): array
            {
                return ['Id', 'User','Progress Report','Type of Activity','Event Title','Activity Description','Date','Attendant Amount','Duration (days)','Place Region','Undergraduate Students','Primary Education Students','Secondary Education Students','General Community','Companies,Industries,Services','School Teachers','Government Official','Other','Comments'];
            }

            public function defaultStyles(Style $defaultStyle)
            {
                // FONT:
                $font = $defaultStyle->getFont();
                $font->setSize(11);
                $font->setName('Calibri');
                $font->setBold(false);
                $font->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK));
                // FILL:
                $fill = $defaultStyle->getFill();
                $fill->setFillType(Fill::FILL_NONE);
                // $fill->getStartColor()->setARGB('FF0000');
                // ALIGNMENT:
                $alignment = $defaultStyle->getAlignment();
                $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                return $defaultStyle;
            }

            public function styles(Worksheet $sheet){
                // Header:
                $sheet->getStyle('A1:S1')->applyFromArray([
                    'font' => [
                        'Calibri' => true,
                        'bold' => true,
                        'size' => 12,
                        'color' => [
                            'rgb' => '000000',
                        ],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'FFC000',
                        ],
                    ],
                ]);
            }

            public function registerEvents(): array
            {
                return [
                    AfterSheet::class => function (AfterSheet $event) {
                        // Obtiene la hoja activa
                        $sheet = $event->sheet;
                        // Se le aplica autofilter al header
                        $sheet->setAutoFilter('A1:S1');
                    },
                ];
            }

            public function title(): string
            {
                return 'A10 OUTREACH';
            }

            
        };

        $sheets['A11 PATENTS'] = new class ($idUsuarioDescarga) implements FromCollection, WithHeadings, WithTitle, WithDefaultStyles, WithEvents, WithStyles, ShouldAutoSize {
            
            private $idUsuarioDescarga;

            public function __construct($idUsuarioDescarga)
            {
                $this->idUsuarioDescarga = $idUsuarioDescarga;
            }
            
            public function collection()
            {
                $patents = patents::where('status', 'Finished')->where('idUsuario',$this->idUsuarioDescarga)->get();
                // Convierte el array de objetos en una colección:
                foreach($patents as $patent){
                    // Añadir Nombre Usuario:
                    $nombreUsuario = User::where('id',$patent['idUsuario'])->get();
                    $patent['idUsuario'] = $nombreUsuario[0]['name'];
                }
                // Ordenar elementos de las facturas:
                $patentsArray = [];
                foreach ($patents as $patent) {
                    $newPatent = [
                        'Id' => $patent['id'],
                        'User' => $patent['idUsuario'],
                        'Progress Report' => $patent['progressReport'],
                        'IP Type' => $patent['ipType'],
                        'Authors' => $patent['authors'],
                        'Institution Owner(s)' => $patent['institutionOwner'],
                        'Country of Registration' => $patent['countryOfRegistration'],
                        'Application Date' => $patent['applicationDate'],
                        'Grant Date' => $patent['grantDate'],
                        'Application Status' => $patent['applicationStatus'],
                        'Registration Number' => $patent['registrationNumber'],
                        'State' => $patent['state'],
                        'Researcher Involved' => $patent['researcherInvolved'],
                        'Comments' => $patent['comments'],
                    ];
                    array_push($patentsArray, $newPatent);
                }
                // Convierte el array de objetos en una colección:
                $colection = new Collection($patentsArray);
                // Convierte la colección en modelos de Eloquent:
                $model = User::hydrate($colection->toArray());
                return $model;
            }

            public function headings(): array
            {
                return ['Id', 'User','Progress Report','IP Type','Authors','Institution Owner(s)','Country of Registration','Application Date','Grant Date','Application Status','Registration Number','State','Researcher Involved','Comments'];
            }

            public function defaultStyles(Style $defaultStyle)
            {
                // FONT:
                $font = $defaultStyle->getFont();
                $font->setSize(11);
                $font->setName('Calibri');
                $font->setBold(false);
                $font->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK));
                // FILL:
                $fill = $defaultStyle->getFill();
                $fill->setFillType(Fill::FILL_NONE);
                // $fill->getStartColor()->setARGB('FF0000');
                // ALIGNMENT:
                $alignment = $defaultStyle->getAlignment();
                $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                return $defaultStyle;
            }

            public function styles(Worksheet $sheet){
                // Header:
                $sheet->getStyle('A1:N1')->applyFromArray([
                    'font' => [
                        'Calibri' => true,
                        'bold' => true,
                        'size' => 12,
                        'color' => [
                            'rgb' => '000000',
                        ],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'FFC000',
                        ],
                    ],
                ]);
            }

            public function registerEvents(): array
            {
                return [
                    AfterSheet::class => function (AfterSheet $event) {
                        // Obtiene la hoja activa
                        $sheet = $event->sheet;
                        // Se le aplica autofilter al header
                        $sheet->setAutoFilter('A1:N1');
                    },
                ];
            }

            public function title(): string
            {
                return 'A11 PATENTS';
            }

            
        };

        $sheets['A12 PUBLIC-PRIVATE CONECTIONS'] = new class ($idUsuarioDescarga) implements FromCollection, WithHeadings, WithTitle, WithDefaultStyles, WithEvents, WithStyles, ShouldAutoSize {
            
            private $idUsuarioDescarga;

            public function __construct($idUsuarioDescarga)
            {
                $this->idUsuarioDescarga = $idUsuarioDescarga;
            }
            
            public function collection()
            {
                $publicPrivates = publicPrivate::where('status', 'Finished')->where('idUsuario',$this->idUsuarioDescarga)->get();
                // Convierte el array de objetos en una colección:
                foreach($publicPrivates as $publicPrivate){
                    // Añadir Nombre Usuario:
                    $nombreUsuario = User::where('id',$publicPrivate['idUsuario'])->get();
                    $publicPrivate['idUsuario'] = $nombreUsuario[0]['name'];
                }
                // Ordenar elementos de las facturas:
                $publicPrivateArray = [];
                foreach ($publicPrivates as $publicPrivate) {
                    $newPublicPrivate = [
                        'Id' => $publicPrivate['id'],
                        'User' => $publicPrivate['idUsuario'],
                        'Progress Report' => $publicPrivate['progressReport'],
                        'Name of Activity' => $publicPrivate['nameOfActivity'],
                        'Results/Goals' => $publicPrivate['resultsGoals'],
                        'Name Of Organization' => $publicPrivate['nameOfOrganization'],
                        'Country Origin' => $publicPrivate['countryOrigin'],
                        'Start Date' => $publicPrivate['startDate'],
                        'Ending Date' => $publicPrivate['endingDate'],
                        'Comments' => $publicPrivate['comments'],         
                    ];
                    array_push($publicPrivateArray, $newPublicPrivate);
                }
                // Convierte el array de objetos en una colección:
                $colection = new Collection($publicPrivateArray);
                // Convierte la colección en modelos de Eloquent:
                $model = User::hydrate($colection->toArray());
                return $model;
            }

            public function headings(): array
            {
                return ['Id', 'User','Progress Report','Name of Activity','Results/Goals','Name Of Organization','Country Origin','Start Date','Ending Date','Comments'];
            }

            public function defaultStyles(Style $defaultStyle)
            {
                // FONT:
                $font = $defaultStyle->getFont();
                $font->setSize(11);
                $font->setName('Calibri');
                $font->setBold(false);
                $font->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK));
                // FILL:
                $fill = $defaultStyle->getFill();
                $fill->setFillType(Fill::FILL_NONE);
                // $fill->getStartColor()->setARGB('FF0000');
                // ALIGNMENT:
                $alignment = $defaultStyle->getAlignment();
                $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                return $defaultStyle;
            }

            public function styles(Worksheet $sheet){
                // Header:
                $sheet->getStyle('A1:J1')->applyFromArray([
                    'font' => [
                        'Calibri' => true,
                        'bold' => true,
                        'size' => 12,
                        'color' => [
                            'rgb' => '000000',
                        ],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'FFC000',
                        ],
                    ],
                ]);
            }

            public function registerEvents(): array
            {
                return [
                    AfterSheet::class => function (AfterSheet $event) {
                        // Obtiene la hoja activa
                        $sheet = $event->sheet;
                        // Se le aplica autofilter al header
                        $sheet->setAutoFilter('A1:J1');
                    },
                ];
            }

            public function title(): string
            {
                return 'A12 PUBLIC-PRIVATE CONECTIONS';
            }

            
        };

        $sheets['A13 TEC. AND KNOW. TRANSFER'] = new class ($idUsuarioDescarga) implements FromCollection, WithHeadings, WithTitle, WithDefaultStyles, WithEvents, WithStyles, ShouldAutoSize {
            
            private $idUsuarioDescarga;

            public function __construct($idUsuarioDescarga)
            {
                $this->idUsuarioDescarga = $idUsuarioDescarga;
            }
            
            public function collection()
            {
                $technologyKnowledges = technologyKnowledge::where('status', 'Finished')->where('idUsuario',$this->idUsuarioDescarga)->get();
                // Convierte el array de objetos en una colección:
                foreach($technologyKnowledges as $technologyKnowledge){
                    // Añadir Nombre Usuario:
                    $nombreUsuario = User::where('id',$technologyKnowledge['idUsuario'])->get();
                    $technologyKnowledge['idUsuario'] = $nombreUsuario[0]['name'];
                }
                // Ordenar elementos de las facturas:
                $technologyKnowledgeArray = [];
                foreach ($technologyKnowledges as $technologyKnowledge) {
                    $newTechnologyKnowledge = [
                        'Id' => $technologyKnowledge['id'],
                        'User' => $technologyKnowledge['idUsuario'],
                        'Progress Report' => $technologyKnowledge['progressReport'],
                        'Technology Transfer' => $technologyKnowledge['technologyTransfer'],
                        'Knowledge Transfer' => $technologyKnowledge['knowledgeTransfer'],
                        'Type of Transfer' => $technologyKnowledge['typeOfTransfer'],
                        'Country' => $technologyKnowledge['country'],
                        'City' => $technologyKnowledge['city'],
                        'Date' => $technologyKnowledge['year'],
                        'Comments' => $technologyKnowledge['comments'],
                    ];
                    array_push($technologyKnowledgeArray, $newTechnologyKnowledge);
                }
                // Convierte el array de objetos en una colección:
                $colection = new Collection($technologyKnowledgeArray);
                // Convierte la colección en modelos de Eloquent:
                $model = User::hydrate($colection->toArray());
                return $model;
            }

            public function headings(): array
            {
                return ['Id', 'User','Progress Report','Technology Transfer','Knowledge Transfer','Type of Transfer','Country','City','Date','Comments'];
            }

            public function defaultStyles(Style $defaultStyle)
            {
                // FONT:
                $font = $defaultStyle->getFont();
                $font->setSize(11);
                $font->setName('Calibri');
                $font->setBold(false);
                $font->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK));
                // FILL:
                $fill = $defaultStyle->getFill();
                $fill->setFillType(Fill::FILL_NONE);
                // $fill->getStartColor()->setARGB('FF0000');
                // ALIGNMENT:
                $alignment = $defaultStyle->getAlignment();
                $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                return $defaultStyle;
            }

            public function styles(Worksheet $sheet){
                // Header:
                $sheet->getStyle('A1:J1')->applyFromArray([
                    'font' => [
                        'Calibri' => true,
                        'bold' => true,
                        'size' => 12,
                        'color' => [
                            'rgb' => '000000',
                        ],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'FFC000',
                        ],
                    ],
                ]);
            }

            public function registerEvents(): array
            {
                return [
                    AfterSheet::class => function (AfterSheet $event) {
                        // Obtiene la hoja activa
                        $sheet = $event->sheet;
                        // Se le aplica autofilter al header
                        $sheet->setAutoFilter('A1:J1');
                    },
                ];
            }

            public function title(): string
            {
                return 'A13 TEC.AND KNOW. TRANSFER';
            }

            
        };

        $sheets['A14 FUNDING SOURCES'] = new class ($idUsuarioDescarga) implements FromCollection, WithHeadings, WithTitle, WithDefaultStyles, WithEvents, WithStyles, ShouldAutoSize {
            
            private $idUsuarioDescarga;

            public function __construct($idUsuarioDescarga)
            {
                $this->idUsuarioDescarga = $idUsuarioDescarga;
            }
            
            public function collection()
            {
                $fundings = fundingSources::where('status', 'Finished')->where('idUsuario',$this->idUsuarioDescarga)->get();
                // Convierte el array de objetos en una colección:
                foreach($fundings as $funding){
                    // Añadir Nombre Usuario:
                    $nombreUsuario = User::where('id',$funding['idUsuario'])->get();
                    $funding['idUsuario'] = $nombreUsuario[0]['name'];
                }
                // Ordenar elementos de las facturas:
                $fundingArray = [];
                foreach ($fundings as $funding) {
                    $newFunding = [
                        'Id' => $funding['id'],
                        'User' => $funding['idUsuario'],
                        'Progress Report' => $funding['progressReport'],
                        'Type Sources' => $funding['typeSources'],
                        'Name of the institution' => $funding['nameOfInstitution'],
                        'Program/contest' => $funding['programContest'],
                        'Project Title' => $funding['projectTitle'],
                        'Principal Researcher' => $funding['principalResearcher'],
                        'Start' => $funding['startDate'],
                        'Finish' => $funding['finishDate'],
                        'In Cash' => $funding['inCash'],
                        'Type of collaboration' => $funding['typeOfCollaboration'],
                        'State' => $funding['state'],
                        'Comments' => $funding['comments'],
                    ];
                    array_push($fundingArray, $newFunding);
                }
                // Convierte el array de objetos en una colección:
                $colection = new Collection($fundingArray);
                // Convierte la colección en modelos de Eloquent:
                $model = User::hydrate($colection->toArray());
                return $model;
            }

            public function headings(): array
            {
                return ['Id', 'User','Progress Report','Type Sources','Name of the institution','Program/contest','Project Title','Principal Researcher','Start','Finish','In Cash','Type of collaboration','State','Comments'];
            }

            public function defaultStyles(Style $defaultStyle)
            {
                // FONT:
                $font = $defaultStyle->getFont();
                $font->setSize(11);
                $font->setName('Calibri');
                $font->setBold(false);
                $font->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK));
                // FILL:
                $fill = $defaultStyle->getFill();
                $fill->setFillType(Fill::FILL_NONE);
                // $fill->getStartColor()->setARGB('FF0000');
                // ALIGNMENT:
                $alignment = $defaultStyle->getAlignment();
                $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                return $defaultStyle;
            }

            public function styles(Worksheet $sheet){
                // Header:
                $sheet->getStyle('A1:N1')->applyFromArray([
                    'font' => [
                        'Calibri' => true,
                        'bold' => true,
                        'size' => 12,
                        'color' => [
                            'rgb' => '000000',
                        ],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'FFC000',
                        ],
                    ],
                ]);
            }

            public function registerEvents(): array
            {
                return [
                    AfterSheet::class => function (AfterSheet $event) {
                        // Obtiene la hoja activa
                        $sheet = $event->sheet;
                        // Se le aplica autofilter al header
                        $sheet->setAutoFilter('A1:N1');
                    },
                ];
            }

            public function title(): string
            {
                return 'A14 FUNDING SOURCES';
            }

            
        };


        return $sheets;
    }
}
