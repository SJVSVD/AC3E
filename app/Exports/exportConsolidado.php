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

use Maatwebsite\Excel\Concerns\WithColumnLimit;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
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

class exportConsolidado implements WithMultipleSheets, WithDefaultStyles, WithEvents, WithStyles, FromCollection, WithHeadings, WithColumnWidths, WithProperties, WithTitle
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
        $isiPublications = isiPublication::where('status', 'Finished')->get();
        // Convierte el array de objetos en una colección:
        foreach($isiPublications as $isiPublication){
            // Añadir Nombre Usuario:
            $nombreUsuario = User::where('id',$isiPublication['idUsuario'])->get();
            $isiPublication['idUsuario'] = isset($nombreUsuario[0]['name']) ? $nombreUsuario[0]['name'] : 'Undefined';;
            
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

            $researchers = [];

            if (isset($isiPublication['mainResearchers']) && $isiPublication['mainResearchers'] == 1) {
                $researchers[] = 'Main Researchers';
            }
            if (isset($isiPublication['associativeResearchers']) && $isiPublication['associativeResearchers'] == 1) {
                $researchers[] = 'Associative Researchers';
            }
            if (isset($isiPublication['postDoc']) && $isiPublication['postDoc'] == 1) {
                $researchers[] = 'Post Doc';
            }
            if (isset($isiPublication['thesisStudents']) && $isiPublication['thesisStudents'] == 1) {
                $researchers[] = 'Thesis Students';
            }
            if (isset($isiPublication['nationalExternalResearchers']) && $isiPublication['nationalExternalResearchers'] == 1) {
                $researchers[] = 'National External Researchers';
            }
            if (isset($isiPublication['internationalExternalResearchers']) && $isiPublication['internationalExternalResearchers'] == 1) {
                $researchers[] = 'International External Researchers';
            }
            
            $researchersField = implode(', ', $researchers);

            $newisiPublication = [
                'Id' => $isiPublication['id'],
                'Progress Report Year' => $isiPublication['progressReport'],
                'User' => $isiPublication['idUsuario'],
                'Researchers Involved' => $isiPublication['researcherInvolved'],
                'Research Lines Involved' => $isiPublication['researchLinesInvolved'],
                'Authors' => $isiPublication['authors'],
                'Article Title' => $isiPublication['articleTitle'],
                'Journal Name' => $isiPublication['journalName'],
                'Digital Object Identifier (DOI)' => $isiPublication['doi'],
                'Volume' => $isiPublication['volume'],
                'First Page' => $isiPublication['firstPage'],
                'Last Page' => $isiPublication['lastPage'],
                'Funding' => $isiPublication['funding'],
                'Year Published' => $isiPublication['yearPublished'],
                'Month' => $isiPublication['month'],
                'Keywords' => $isiPublication['keywords'],
                'Participations' => $researchersField,
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
        return ['No.','Progress Report Year','User','Researchers Involved','Research Lines Involved','Authors','Article Title','Journal Name','Digital Object Identifier (DOI)','Volume','First Page', 'Last Page','Funding','Year','Month','Keywords','Participations','Comments'];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Obtiene la hoja activa
                $sheet = $event->sheet;
                // Se le aplica autofilter al header
                $sheet->setAutoFilter('A1:R1');
            },
        ];
    }

    public function columnWidths(): array
    {
        // Generar automáticamente un arreglo con todas las letras de la A a la Q y asignarles un ancho de 50 unidades
        $specificWidths = array_fill_keys(range('C', 'R'), 40);
    
        // Devolver el arreglo de anchos específicos
        return $specificWidths;
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
        return $defaultStyle;
    }

    public function styles(Worksheet $sheet)
    {
        // Establecer estilos para el encabezado
        $sheet->getStyle('A1:R1')->applyFromArray([
            'font' => [
                'Calibri' => true,
                'bold' => true,
                'size' => 12,
                'color' => [
                    'rgb' => '000000', // Color blanco
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'ED8D1D', // Color naranja
                ],
            ],
        ]);

        // Establecer estilos para las celdas (excepto el encabezado)
        $sheet->getStyle('A2:R'.$sheet->getHighestRow())->applyFromArray([
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'F2F2F2', // Color gris claro
                ],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'], // Color negro
                ],
            ],
        ]);
    }

    public function title(): string
    {
        return 'A1 WoS PUBLICATIONS';
    }

    public function sheets(): array
    {
        // Crea la primera hoja con los datos actuales
        $sheets = [
            'A1 WoS PUBLICATIONS' => $this,
        ];
        // Agrega la segunda hoja con los nuevos datos
        $sheets['A2 NON WoS PUBLICATIONS'] = new class implements FromCollection, WithHeadings, WithTitle, WithDefaultStyles, WithEvents, WithStyles,WithColumnWidths  {
            public function collection()
            {
                $nonIsiPublications = nonIsiPublication::where('status', 'Finished')->get();
                // Convierte el array de objetos en una colección:
                foreach($nonIsiPublications as $nonIsiPublication){
                    // Añadir Nombre Usuario:
                    $nombreUsuario = User::where('id',$nonIsiPublication['idUsuario'])->get();
                    $nonIsiPublication['idUsuario'] = isset($nombreUsuario[0]['name']) ? $nombreUsuario[0]['name'] : 'Undefined';;
                }
                // Ordenar elementos de las facturas:
                $nonIsis = [];
                foreach ($nonIsiPublications as $nonIsiPublication) {
                    $newNonIsiPublication = [
                        'Id' => $nonIsiPublication['id'],
                        'Progress Report Year' => $nonIsiPublication['progressReport'],
                        'User' => $nonIsiPublication['idUsuario'],
                        'Researchers Involved' => $nonIsiPublication['researcherInvolved'],
                        'Research Lines Involved' => $nonIsiPublication['researchLinesInvolved'],
                        'Authors' => $nonIsiPublication['authors'],
                        'Article Title' => $nonIsiPublication['articleTitle'],
                        'Journal Name' => $nonIsiPublication['journalName'],
                        'Volume' => $nonIsiPublication['volume'],
                        'First Page' => $nonIsiPublication['firstPage'],
                        'Last Page' => $nonIsiPublication['lastPage'],
                        'Funding' => $nonIsiPublication['funding'],
                        'Year Published' => $nonIsiPublication['yearPublished'],
                        'Month' => $nonIsiPublication['month'],
                        'Indexed By' => $nonIsiPublication['indexedBy'],
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
                return ['Id','Progress Report Year','User','Researchers Involved','Research Lines Involved','Authors','Article Title','Journal Name','Volume','First Page','Last Page','Funding','Year Published','Month','Indexed By','Comments'];
            }

            public function columnWidths(): array
            {
                // Generar automáticamente un arreglo con todas las letras de la A a la Q y asignarles un ancho de 50 unidades
                $specificWidths = array_fill_keys(range('C', 'P'), 40);
            
                // Devolver el arreglo de anchos específicos
                return $specificWidths;
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
                return $defaultStyle;
            }
        
            public function styles(Worksheet $sheet)
            {
                // Establecer estilos para el encabezado
                $sheet->getStyle('A1:P1')->applyFromArray([
                    'font' => [
                        'Calibri' => true,
                        'bold' => true,
                        'size' => 12,
                        'color' => [
                            'rgb' => '000000', // Color blanco
                        ],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'ED8D1D', // Color naranja
                        ],
                    ],
                ]);
        
                // Establecer estilos para las celdas (excepto el encabezado)
                $sheet->getStyle('A2:P'.$sheet->getHighestRow())->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'F2F2F2', // Color gris claro
                        ],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'], // Color negro
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
                return 'A2 NON WoS PUBLICATIONS';
            }

            
        };

        $sheets['A3 BOOKS'] = new class implements FromCollection, WithHeadings, WithTitle, WithDefaultStyles, WithEvents, WithStyles, WithColumnWidths {
            public function collection()
            {
                $books = books::where('status', 'Finished')->get();
                // Convierte el array de objetos en una colección:
                foreach($books as $book){
                    // Añadir Nombre Usuario:
                    $nombreUsuario = User::where('id',$book['centerResearcher'])->get();
                    $book['centerResearcher'] = isset($nombreUsuario[0]['name']) ? $nombreUsuario[0]['name'] : 'Undefined';;
                }
                // Ordenar elementos de las facturas:
                $booksArray = [];
                foreach ($books as $book) {
                    $newBook = [
                        'Id' => $book['id'],
                        'Progress Report Year' => $book['progressReport'],
                        'User' => $book['centerResearcher'],
                        'Researchers Involved' => $book['researcherInvolved'],
                        'Research Lines Involved' => $book['researchLinesInvolved'],
                        'Books Authors' => $book['bookAuthors'],
                        'Chapter Authors' => $book['chapterAuthors'],
                        'Title of the Book' => $book['bookTitle'],
                        'Chapter Title' => $book['chapterTitle'],
                        'First Page' => $book['firstPage'],
                        'Last Page' => $book['lastPage'],
                        'Editorial/City/Country' => $book['editorialCityCountry'],
                        'Year' => $book['year'],
                        'Month' => $book['month'],
                        'Work Type' => $book['workType'],
                        'ISBN' => $book['ISBN'],
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
                return ['Id', 'Progress Report Year','User','Researchers Involved','Research Lines Involved','Books Authors','Chapter Authors','Title of the Book','Chapter Title','First Page','Last Page','Editorial/City/Country','Year','Month','Work Type','ISBN','Comments'];
            }

            public function columnWidths(): array
            {
                // Generar automáticamente un arreglo con todas las letras de la A a la Q y asignarles un ancho de 50 unidades
                $specificWidths = array_fill_keys(range('C', 'Q'), 40);
            
                // Devolver el arreglo de anchos específicos
                return $specificWidths;
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
                return $defaultStyle;
            }
        
            public function styles(Worksheet $sheet)
            {
                // Establecer estilos para el encabezado
                $sheet->getStyle('A1:Q1')->applyFromArray([
                    'font' => [
                        'Calibri' => true,
                        'bold' => true,
                        'size' => 12,
                        'color' => [
                            'rgb' => '000000', // Color blanco
                        ],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'ED8D1D', // Color naranja
                        ],
                    ],
                ]);
        
                // Establecer estilos para las celdas (excepto el encabezado)
                $sheet->getStyle('A2:Q'.$sheet->getHighestRow())->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'F2F2F2', // Color gris claro
                        ],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'], // Color negro
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
                        $sheet->setAutoFilter('A1:Q1');
                    },
                ];
            }

            public function title(): string
            {
                return 'A3 BOOKS';
            }

            
        };


        $sheets['A4 AWARDS'] = new class implements FromCollection, WithHeadings, WithTitle, WithDefaultStyles, WithEvents, WithStyles, WithColumnWidths {
            public function collection()
            {
                $awards = awards::where('status', 'Finished')->get();
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
                        'Progress Report Year' => $award['progressReport'],
                        'User' => $award['idUsuario'],
                        'Researchers Involved' => $award['researcherInvolved'],
                        'Research Lines Involved' => $award['researchLinesInvolved'],
                        'Awardee Name' => $award['awardeeName'],
                        'Award Name' => $award['awardName'],
                        'Year' => $award['year'],
                        'Month' => $award['month'],
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
                return ['Id','Progress Report Year','User','Researchers Involved','Research Lines Involved', 'Awardee Name','Award Name','Year','Month','Constribution of the Awardee','Awarding Institution','Country','Comments'];
            }

            public function columnWidths(): array
            {
                // Generar automáticamente un arreglo con todas las letras de la A a la Q y asignarles un ancho de 50 unidades
                $specificWidths = array_fill_keys(range('C', 'M'), 40);
            
                // Devolver el arreglo de anchos específicos
                return $specificWidths;
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
                return $defaultStyle;
            }
        
            public function styles(Worksheet $sheet)
            {
                // Establecer estilos para el encabezado
                $sheet->getStyle('A1:M1')->applyFromArray([
                    'font' => [
                        'Calibri' => true,
                        'bold' => true,
                        'size' => 12,
                        'color' => [
                            'rgb' => '000000', // Color blanco
                        ],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'ED8D1D', // Color naranja
                        ],
                    ],
                ]);
        
                // Establecer estilos para las celdas (excepto el encabezado)
                $sheet->getStyle('A2:M'.$sheet->getHighestRow())->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'F2F2F2', // Color gris claro
                        ],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'], // Color negro
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
                return 'A4 AWARDS';
            }

            
        };

        $sheets['A5 ORGANIZATION OF SC EVENTS'] = new class implements FromCollection, WithHeadings, WithTitle, WithDefaultStyles, WithEvents, WithStyles, WithColumnWidths {
            public function collection()
            {
                $organizationsScEvents = organizationsScEvents::where('status', 'Finished')->get();
                // Convierte el array de objetos en una colección:
                foreach($organizationsScEvents as $organization){
                    // Añadir Nombre Usuario:
                    $nombreUsuario = User::where('id',$organization['idUsuario'])->get();
                    $organization['idUsuario'] = isset($nombreUsuario[0]['name']) ? $nombreUsuario[0]['name'] : 'Undefined';;
                }
                // Ordenar elementos de las facturas:
                $organizationArray = [];
                foreach ($organizationsScEvents as $organization) {
                    $newOrganization = [
                        'Id' => $organization['id'],
                        'Progress Report Year' => $organization['progressReport'],
                        'User' => $organization['idUsuario'],
                        'Researchers Involved' => $organization['researcherInvolved'],
                        'Research Lines Involved' => $organization['researchLinesInvolved'],
                        'Type of event' => $organization['typeEvent'],
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
                return ['Id', 'Progress Report Year','User','Researchers Involved','Research Lines Involved','Type of event','Name course/congress','Number of participants','Country','City','Start Date','Ending Date','Comments'];
            }

            public function columnWidths(): array
            {
                // Generar automáticamente un arreglo con todas las letras de la A a la Q y asignarles un ancho de 50 unidades
                $specificWidths = array_fill_keys(range('C', 'M'), 40);
            
                // Devolver el arreglo de anchos específicos
                return $specificWidths;
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
                return $defaultStyle;
            }
        
            public function styles(Worksheet $sheet)
            {
                // Establecer estilos para el encabezado
                $sheet->getStyle('A1:M1')->applyFromArray([
                    'font' => [
                        'Calibri' => true,
                        'bold' => true,
                        'size' => 12,
                        'color' => [
                            'rgb' => '000000', // Color blanco
                        ],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'ED8D1D', // Color naranja
                        ],
                    ],
                ]);
        
                // Establecer estilos para las celdas (excepto el encabezado)
                $sheet->getStyle('A2:M'.$sheet->getHighestRow())->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'F2F2F2', // Color gris claro
                        ],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'], // Color negro
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
                return 'A5 ORGANIZATION OF SC EVENTS';
            }

            
        };

        $sheets['A6 PARTICIPATION OF SC EVENTS'] = new class implements FromCollection, WithHeadings, WithTitle, WithDefaultStyles, WithEvents, WithStyles, WithColumnWidths {
            public function collection()
            {
                $participationScEvents = participationScEvents::where('status', 'Finished')->get();
                // Convierte el array de objetos en una colección:
                foreach($participationScEvents as $participation){
                    // Añadir Nombre Usuario:
                    $nombreUsuario = User::where('id',$participation['idUsuario'])->get();
                    $participation['idUsuario'] = isset($nombreUsuario[0]['name']) ? $nombreUsuario[0]['name'] : 'Undefined';;
                }
                // Ordenar elementos de las facturas:
                $participationArray = [];
                foreach ($participationScEvents as $participation) {
                    $newParticipation = [
                        'Id' => $participation['id'],
                        'Progress report' => $participation['progressReport'],
                        'User' => $participation['idUsuario'],
                        'Researchers Involved' => $participation['researcherInvolved'],
                        'Research Lines Involved' => $participation['researchLinesInvolved'],
                        'Presentation Title' => $participation['presentationTitle'],
                        'Type of event' => $participation['typeEvent'],
                        'Type of participation' => $participation['typeOfParticipation'],
                        'Event name' => $participation['eventName'],
                        'Name of participants' => $participation['nameOfParticipants'],
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
                return ['Id', 'Progress report','User','Researchers Involved','Research Lines Involved','Presentation Title','Type of event','Type of participation','Event name','Name of participants','Country','City','Start Date','Ending Date','Comments'];
            }

            public function columnWidths(): array
            {
                // Generar automáticamente un arreglo con todas las letras de la A a la Q y asignarles un ancho de 50 unidades
                $specificWidths = array_fill_keys(range('C', 'O'), 40);
            
                // Devolver el arreglo de anchos específicos
                return $specificWidths;
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
                return $defaultStyle;
            }
        
            public function styles(Worksheet $sheet)
            {
                // Establecer estilos para el encabezado
                $sheet->getStyle('A1:O1')->applyFromArray([
                    'font' => [
                        'Calibri' => true,
                        'bold' => true,
                        'size' => 12,
                        'color' => [
                            'rgb' => '000000', // Color blanco
                        ],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'ED8D1D', // Color naranja
                        ],
                    ],
                ]);
        
                // Establecer estilos para las celdas (excepto el encabezado)
                $sheet->getStyle('A2:O'.$sheet->getHighestRow())->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'F2F2F2', // Color gris claro
                        ],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'], // Color negro
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
                        $sheet->setAutoFilter('A1:O1');
                    },
                ];
            }

            public function title(): string
            {
                return 'A6 PARTICIPATION OF SC EVENTS';
            }

            
        };

        $sheets['A7 VISITS AND STAYS '] = new class implements FromCollection, WithHeadings, WithTitle, WithDefaultStyles, WithEvents, WithStyles, WithColumnWidths {
            public function collection()
            {
                $collaborations = scCollaborations::where('status', 'Finished')->get();
                // Convierte el array de objetos en una colección:
                foreach($collaborations as $collaboration){
                    // Añadir Nombre Usuario:
                    $nombreUsuario = User::where('id',$collaboration['idUsuario'])->get();
                    $collaboration['idUsuario'] = isset($nombreUsuario[0]['name']) ? $nombreUsuario[0]['name'] : 'Undefined';;
                }
                // Ordenar elementos de las facturas:
                $collaborationsArray = [];
                foreach ($collaborations as $collaboration) {
                    $newCollaboration = [
                        'Id' => $collaboration['id'],
                        'Progress Report Year' => $collaboration['progressReport'],
                        'User' => $collaboration['idUsuario'],
                        'Researchers Involved' => $collaboration['researcherInvolved'],
                        'Research Lines Involved' => $collaboration['researchLinesInvolved'],
                        'Activity Name' => $collaboration['activityName'],
                        'Student or Researcher' => $collaboration['studentOrResearcher'],
                        'Name of External Researcher' => $collaboration['nameOfExternalResearcher'],
                        'Collaboration Stay' => $collaboration['collaborationStay'],
                        'Institution Country' => $collaboration['countryOrigin'],
                        'Institution City' => $collaboration['cityOrigin'],
                        'Institution with which the Center collaborates' => $collaboration['institutionCollaborates'],
                        'Beginning Date' => $collaboration['beginningDate'],
                        'Ending Date' => $collaboration['endingDate'],
                        'Comments' => $collaboration['comments'],
                        'International/National' => (trim(strtolower($collaboration['countryOrigin'])) === 'chile') ? 'National' : 'International'
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
                return ['Id', 'Progress report','User','Researchers Involved','Research Lines Involved','Activity Name','Student or Researcher','Name of External Researcher','Collaboration Stay','Institution Country','Institution City','Institution with which the Center collaborates','Beginning Date','Ending Date','Comments','International/National'];
            }

            public function columnWidths(): array
            {
                // Generar automáticamente un arreglo con todas las letras de la A a la Q y asignarles un ancho de 50 unidades
                $specificWidths = array_fill_keys(range('C', 'P'), 40);
            
                // Devolver el arreglo de anchos específicos
                return $specificWidths;
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
                return $defaultStyle;
            }
        
            public function styles(Worksheet $sheet)
            {
                // Establecer estilos para el encabezado
                $sheet->getStyle('A1:P1')->applyFromArray([
                    'font' => [
                        'Calibri' => true,
                        'bold' => true,
                        'size' => 12,
                        'color' => [
                            'rgb' => '000000', // Color blanco
                        ],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'ED8D1D', // Color naranja
                        ],
                    ],
                ]);
        
                // Establecer estilos para las celdas (excepto el encabezado)
                $sheet->getStyle('A2:P'.$sheet->getHighestRow())->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'F2F2F2', // Color gris claro
                        ],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'], // Color negro
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
                return 'A7 VISITS AND STAYS';
            }

            
        };

        $sheets['A8 THESES-STUDENTS'] = new class implements FromCollection, WithHeadings, WithTitle, WithDefaultStyles, WithEvents, WithStyles, WithColumnWidths {
            public function collection()
            {
                $theses = thesisStudent::where('status', 'Finished')->get();
                // Convierte el array de objetos en una colección:
                foreach($theses as $these){
                    // Añadir Nombre Usuario:
                    $nombreUsuario = User::where('id',$these['idUsuario'])->get();
                    $these['idUsuario'] = isset($nombreUsuario[0]['name']) ? $nombreUsuario[0]['name'] : 'Undefined';;
                }
                // Ordenar elementos de las facturas:
                $thesisArray = [];
                foreach ($theses as $these) {
                    if($these['thesisStatus'] == 1){
                        $these['thesisStatus'] = "Finished";
                    }
                    if($these['thesisStatus'] == 2){
                        $these['thesisStatus'] = "In progress";
                    }
                    $newThesis = [
                        'Id' => $these['id'],
                        'Progress Report Year' => $these['progressReport'],
                        'User' => $these['idUsuario'],
                        'Researchers Involved' => $these['researcherInvolved'],
                        'Research Lines Involved' => $these['researchLinesInvolved'],
                        'Student Name' => $these['studentName'],
                        'RUN or Passport' => $these['runOrPassport'],
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
                return ['Id', 'Progress Report Year','User','Researchers Involved','Research Lines Involved','Student Name','RUN or Passport','Gender','Student Mail','Thesis Status','Thesis Title','Academic Degree','Degree Denomination','Tutor Name','Tutor Institution','Cotutor Name','Cotutor Institution','Other Name','Other Institution','University that gives the degree','Year in which thesis starts','Year in which thesis ends','Resources provided by the Center','Posterior working area','Institution of Posterior working area','Comments'];
            }

            public function columnWidths(): array
            {
                // Generar automáticamente un arreglo con todas las letras de la A a la Q y asignarles un ancho de 50 unidades
                $specificWidths = array_fill_keys(range('C', 'Z'), 40);
            
                // Devolver el arreglo de anchos específicos
                return $specificWidths;
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
                return $defaultStyle;
            }
        
            public function styles(Worksheet $sheet)
            {
                // Establecer estilos para el encabezado
                $sheet->getStyle('A1:Z1')->applyFromArray([
                    'font' => [
                        'Calibri' => true,
                        'bold' => true,
                        'size' => 12,
                        'color' => [
                            'rgb' => '000000', // Color blanco
                        ],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'ED8D1D', // Color naranja
                        ],
                    ],
                ]);
        
                // Establecer estilos para las celdas (excepto el encabezado)
                $sheet->getStyle('A2:Z'.$sheet->getHighestRow())->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'F2F2F2', // Color gris claro
                        ],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'], // Color negro
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
                        $sheet->setAutoFilter('A1:Z1');
                    },
                ];
            }

            public function title(): string
            {
                return 'A8 THESES-STUDENTS';
            }

            
        };

        $sheets['A9 POSTDOCTORAL FELLOWS'] = new class implements FromCollection, WithHeadings, WithTitle, WithDefaultStyles, WithEvents, WithStyles, WithColumnWidths {
            public function collection()
            {
                $postDocs = postDoc::where('status', 'Finished')->get();
                // Convierte el array de objetos en una colección:
                foreach($postDocs as $postDoc){
                    // Añadir Nombre Usuario:
                    $nombreUsuario = User::where('id',$postDoc['idUsuario'])->get();
                    $postDoc['idUsuario'] = isset($nombreUsuario[0]['name']) ? $nombreUsuario[0]['name'] : 'Undefined';;
                }
                // Ordenar elementos de las facturas:
                $postDocArray = [];
                foreach ($postDocs as $postDoc) {
                    $newPostDoc = [
                        'Id' => $postDoc['id'],
                        'Progress Report Year' => $postDoc['progressReport'],
                        'User' => $postDoc['idUsuario'],
                        'Researchers Involved' => $postDoc['researcherInvolved'],
                        'Research Lines Involved' => $postDoc['researchLinesInvolved'],
                        'Name of Postdoctoral Fellows' => $postDoc['nameOfPostdoc'],
                        'RUN/Passport' => $postDoc['runOrPassport'],
                        'Gender' => $postDoc['gender'],
                        'Personal Email' => $postDoc['personalEmail'],
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
                return ['Id', 'Progress Report Year','User','Researchers Involved','Research Lines Involved','Name of Postdoctoral Fellows','RUN/Passport','Gender','Personal Email','Research Topic','Tutor name','Associated Institution','Funding Source','Starting Date','Ending Date','Resources provided by the Center','Comments'];
            }

            public function columnWidths(): array
            {
                // Generar automáticamente un arreglo con todas las letras de la A a la Q y asignarles un ancho de 50 unidades
                $specificWidths = array_fill_keys(range('C', 'Q'), 40);
            
                // Devolver el arreglo de anchos específicos
                return $specificWidths;
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
                return $defaultStyle;
            }
        
            public function styles(Worksheet $sheet)
            {
                // Establecer estilos para el encabezado
                $sheet->getStyle('A1:Q1')->applyFromArray([
                    'font' => [
                        'Calibri' => true,
                        'bold' => true,
                        'size' => 12,
                        'color' => [
                            'rgb' => '000000', // Color blanco
                        ],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'ED8D1D', // Color naranja
                        ],
                    ],
                ]);
        
                // Establecer estilos para las celdas (excepto el encabezado)
                $sheet->getStyle('A2:Q'.$sheet->getHighestRow())->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'F2F2F2', // Color gris claro
                        ],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'], // Color negro
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
                        $sheet->setAutoFilter('A1:Q1');
                    },
                ];
            }

            public function title(): string
            {
                return 'A9 POSTDOCTORAL FELLOWS';
            }

            
        };

        $sheets['A10 OUTREACH'] = new class implements FromCollection, WithHeadings, WithTitle, WithDefaultStyles, WithEvents, WithStyles, WithColumnWidths {
            public function collection()
            {
                $outreachs = outreachActivities::where('status', 'Finished')->get();
                // Convierte el array de objetos en una colección:
                foreach($outreachs as $outreach){
                    // Añadir Nombre Usuario:
                    $nombreUsuario = User::where('id',$outreach['idUsuario'])->get();
                    $outreach['idUsuario'] = isset($nombreUsuario[0]['name']) ? $nombreUsuario[0]['name'] : 'Undefined';;

                    // Crear el campo Target
                    $targetFields = [
                        'Undergraduate Students' => $outreach['undergraduateStudents'],
                        'Primary Education Students' => $outreach['primaryEducationStudents'],
                        'Secondary Education Students' => $outreach['secondaryEducationStudents'],
                        'General Community' => $outreach['generalCommunity'],
                        'Companies,Industries,Services' => $outreach['companiesIndustriesServices'],
                        'School Teachers' => $outreach['schoolTeachers'],
                        'Government Official' => $outreach['governmentOfficial'],
                        'Other' => $outreach['other'],
                    ];

                    $targetString = implode(', ', array_keys(array_filter($targetFields)));
                    $outreach['target'] = $targetString;
                }
                // Ordenar elementos de las facturas:
                $outreachArray = [];
                foreach ($outreachs as $outreach) {
                    $newOutreach = [
                        'Id' => $outreach['id'],
                        'Progress Report Year' => $outreach['progressReport'],
                        'User' => $outreach['idUsuario'],
                        'Researchers Involved' => $outreach['researcherInvolved'],
                        'Research Lines Involved' => $outreach['researchLinesInvolved'],
                        'Responsability' => $outreach['responsability'],
                        'Type of Activity' => $outreach['activityType'],
                        'Event Title' => $outreach['activityName'],
                        'Activity Description' => $outreach['activityDescription'],
                        'Date' => $outreach['date'],
                        'Attendant Amount' => $outreach['attendantsAmount'],
                        'Duration (days)' => $outreach['duration'],
                        'Place Region' => $outreach['placeRegion'],
                        'City' => $outreach['city'],
                        'Target' => $outreach['target'], // Agregado el campo Target
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
                return ['Id', 'Progress Report Year','User','Researchers Involved','Research Lines Involved','Responsability','Type of Activity','Event Title','Activity Description','Date','Attendant Amount','Duration (days)','Place Region','City','Target','Comments'];
            }

            public function columnWidths(): array
            {
                // Generar automáticamente un arreglo con todas las letras de la A a la Q y asignarles un ancho de 50 unidades
                $specificWidths = array_fill_keys(range('C', 'W'), 40);
            
                // Devolver el arreglo de anchos específicos
                return $specificWidths;
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
                return $defaultStyle;
            }
        
            public function styles(Worksheet $sheet)
            {
                // Establecer estilos para el encabezado
                $sheet->getStyle('A1:W1')->applyFromArray([
                    'font' => [
                        'Calibri' => true,
                        'bold' => true,
                        'size' => 12,
                        'color' => [
                            'rgb' => '000000', // Color blanco
                        ],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'ED8D1D', // Color naranja
                        ],
                    ],
                ]);
        
                // Establecer estilos para las celdas (excepto el encabezado)
                $sheet->getStyle('A2:W'.$sheet->getHighestRow())->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'F2F2F2', // Color gris claro
                        ],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'], // Color negro
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
                return 'A10 OUTREACH';
            }

            
        };

        $sheets['A11 PATENTS'] = new class implements FromCollection, WithHeadings, WithTitle, WithDefaultStyles, WithEvents, WithStyles, WithColumnWidths {
            public function collection()
            {
                $patents = patents::where('status', 'Finished')->get();
                // Convierte el array de objetos en una colección:
                foreach($patents as $patent){
                    // Añadir Nombre Usuario:
                    $nombreUsuario = User::where('id',$patent['idUsuario'])->get();
                    $patent['idUsuario'] = isset($nombreUsuario[0]['name']) ? $nombreUsuario[0]['name'] : 'Undefined';;
                }
                // Ordenar elementos de las facturas:
                $patentsArray = [];
                foreach ($patents as $patent) {
                    $newPatent = [
                        'Id' => $patent['id'],
                        'Progress Report Year' => $patent['progressReport'],
                        'User' => $patent['idUsuario'],
                        'Researchers Involved' => $patent['researcherInvolved'],
                        'Research Lines Involved' => $patent['researchLinesInvolved'],
                        'Name of Patent' => $patent['nameOfPatent'],
                        'IP Type' => $patent['ipType'],
                        'Authors' => $patent['authors'],
                        'Institution Owner(s)' => $patent['institutionOwner'],
                        'Country of Registration' => $patent['countryOfRegistration'],
                        'Application Date' => $patent['applicationDate'],
                        'Grant Date' => $patent['grantDate'],
                        'Application Status' => $patent['applicationStatus'],
                        'Registration Number' => $patent['registrationNumber'],
                        'Application granted n.º' => $patent['applicationGranted'],
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
                return ['Id', 'Progress Report Year','User','Researchers Involved','Research Lines Involved','Name of Patent','IP Type','Authors','Institution Owner(s)','Country of Registration','Application Date','Grant Date','Application Status','Registration Number','Application granted n.º','Comments'];
            }

            public function columnWidths(): array
            {
                // Generar automáticamente un arreglo con todas las letras de la A a la Q y asignarles un ancho de 50 unidades
                $specificWidths = array_fill_keys(range('C', 'P'), 40);
            
                // Devolver el arreglo de anchos específicos
                return $specificWidths;
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
                return $defaultStyle;
            }
        
            public function styles(Worksheet $sheet)
            {
                // Establecer estilos para el encabezado
                $sheet->getStyle('A1:P1')->applyFromArray([
                    'font' => [
                        'Calibri' => true,
                        'bold' => true,
                        'size' => 12,
                        'color' => [
                            'rgb' => '000000', // Color blanco
                        ],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'ED8D1D', // Color naranja
                        ],
                    ],
                ]);
        
                // Establecer estilos para las celdas (excepto el encabezado)
                $sheet->getStyle('A2:P'.$sheet->getHighestRow())->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'F2F2F2', // Color gris claro
                        ],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'], // Color negro
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
                return 'A11 PATENTS';
            }

            
        };

        $sheets['A12 PUBLIC-PRIVATE CONECTIONS'] = new class implements FromCollection, WithHeadings, WithTitle, WithDefaultStyles, WithEvents, WithStyles, WithColumnWidths {
            public function collection()
            {
                $publicPrivates = publicPrivate::where('status', 'Finished')->get();
                // Convierte el array de objetos en una colección:
                foreach($publicPrivates as $publicPrivate){
                    // Añadir Nombre Usuario:
                    $nombreUsuario = User::where('id',$publicPrivate['idUsuario'])->get();
                    $publicPrivate['idUsuario'] = isset($nombreUsuario[0]['name']) ? $nombreUsuario[0]['name'] : 'Undefined';;
                }
                // Ordenar elementos de las facturas:
                $publicPrivateArray = [];
                foreach ($publicPrivates as $publicPrivate) {
                    $newPublicPrivate = [
                        'Id' => $publicPrivate['id'],
                        'Progress Report Year' => $publicPrivate['progressReport'],
                        'User' => $publicPrivate['idUsuario'],
                        'Researchers Involved' => $publicPrivate['researcherInvolved'],
                        'Research Lines Involved' => $publicPrivate['researchLinesInvolved'],
                        'Agent Type' => $publicPrivate['agentType'],
                        'Type of Connection' => $publicPrivate['typeOfConnection'],
                        'Place where activity was executed' => $publicPrivate['placeWhereWasExecuted'],
                        'International/National' => $publicPrivate['internationalNational'],
                        'Participation in definition of public policies' => $publicPrivate['participationPublicPolicies'],
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
                return ['Id', 'Progress Report Year','User','Researchers Involved','Research Lines Involved','Agent Type','Type of Connection','Place where activity was executed','International/National','Participation in definition of public policies','Name of Activity','Results/Goals','Name Of Organization','Country Origin','Start Date','Ending Date','Comments'];
            }

            public function columnWidths(): array
            {
                // Generar automáticamente un arreglo con todas las letras de la A a la Q y asignarles un ancho de 50 unidades
                $specificWidths = array_fill_keys(range('C', 'Q'), 40);
            
                // Devolver el arreglo de anchos específicos
                return $specificWidths;
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
                return $defaultStyle;
            }
        
            public function styles(Worksheet $sheet)
            {
                // Establecer estilos para el encabezado
                $sheet->getStyle('A1:Q1')->applyFromArray([
                    'font' => [
                        'Calibri' => true,
                        'bold' => true,
                        'size' => 12,
                        'color' => [
                            'rgb' => '000000', // Color blanco
                        ],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'ED8D1D', // Color naranja
                        ],
                    ],
                ]);
        
                // Establecer estilos para las celdas (excepto el encabezado)
                $sheet->getStyle('A2:Q'.$sheet->getHighestRow())->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'F2F2F2', // Color gris claro
                        ],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'], // Color negro
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
                        $sheet->setAutoFilter('A1:Q1');
                    },
                ];
            }

            public function title(): string
            {
                return 'A12 PUBLIC-PRIVATE CONECTIONS';
            }

            
        };

        $sheets['A13 TEC. AND KNOW. TRANSFER'] = new class implements FromCollection, WithHeadings, WithTitle, WithDefaultStyles, WithEvents, WithStyles, WithColumnWidths {
            public function collection()
            {
                $technologyKnowledges = technologyKnowledge::where('status', 'Finished')->get();
                // Convierte el array de objetos en una colección:
                foreach($technologyKnowledges as $technologyKnowledge){
                    // Añadir Nombre Usuario:
                    $nombreUsuario = User::where('id',$technologyKnowledge['idUsuario'])->get();
                    $technologyKnowledge['idUsuario'] = isset($nombreUsuario[0]['name']) ? $nombreUsuario[0]['name'] : 'Undefined';
                }
                // Ordenar elementos de las facturas:
                $technologyKnowledgeArray = [];
                foreach ($technologyKnowledges as $technologyKnowledge) {

                    $transfers = [];

                    if (isset($technologyKnowledge['technologyTransfer']) && $technologyKnowledge['technologyTransfer'] == 1) {
                        $transfers[] = 'Technology Transfer';
                    }
                    if (isset($technologyKnowledge['knowledgeTransfer']) && $technologyKnowledge['knowledgeTransfer'] == 1) {
                        $transfers[] = 'Knowledge Transfer';
                    }

                    $transfersField = implode(', ', $transfers);

                    $newTechnologyKnowledge = [
                        'Id' => $technologyKnowledge['id'],
                        'Progress Report Year' => $technologyKnowledge['progressReport'],
                        'User' => $technologyKnowledge['idUsuario'],
                        'Researchers Involved' => $technologyKnowledge['researcherInvolved'],
                        'Research Lines Involved' => $technologyKnowledge['researchLinesInvolved'],
                        'Category of Transfer' => $transfersField,
                        'Type of Transfer' => $technologyKnowledge['typeOfTransfer'],
                        'Name of the Institution Involved' => $technologyKnowledge['nameOfInstitutionInvolved'],
                        'Name of Beneficiary Institution' => $technologyKnowledge['nameOfBeneficiary'],
                        'Description' => $technologyKnowledge['description'],
                        'Country' => $technologyKnowledge['country'],
                        'City' => $technologyKnowledge['city'],
                        'Place/Region' => $technologyKnowledge['placeRegion'],
                        'Year' => $technologyKnowledge['year'],
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
                return ['Id', 'Progress Report Year','User','Researchers Involved','Research Lines Involved','Category of Transfer','Type of Transfer','Name of the Institution Involved','Name of Beneficiary Institution','Description','Country','City','Place/Region','Year','Comments'];
            }

            public function columnWidths(): array
            {
                // Generar automáticamente un arreglo con todas las letras de la A a la Q y asignarles un ancho de 50 unidades
                $specificWidths = array_fill_keys(range('C', 'O'), 40);
            
                // Devolver el arreglo de anchos específicos
                return $specificWidths;
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
                return $defaultStyle;
            }
        
            public function styles(Worksheet $sheet)
            {
                // Establecer estilos para el encabezado
                $sheet->getStyle('A1:O1')->applyFromArray([
                    'font' => [
                        'Calibri' => true,
                        'bold' => true,
                        'size' => 12,
                        'color' => [
                            'rgb' => '000000', // Color blanco
                        ],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'ED8D1D', // Color naranja
                        ],
                    ],
                ]);
        
                // Establecer estilos para las celdas (excepto el encabezado)
                $sheet->getStyle('A2:O'.$sheet->getHighestRow())->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'F2F2F2', // Color gris claro
                        ],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'], // Color negro
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
                        $sheet->setAutoFilter('A1:O1');
                    },
                ];
            }

            public function title(): string
            {
                return 'A13 TEC.AND KNOW. TRANSFER';
            }

            
        };

        $sheets['A14 FUNDING SOURCES'] = new class implements FromCollection, WithHeadings, WithTitle, WithDefaultStyles, WithEvents, WithStyles, WithColumnWidths {
            public function collection()
            {
                $fundings = fundingSources::where('status', 'Finished')->get();
                // Convierte el array de objetos en una colección:
                foreach($fundings as $funding){
                    // Añadir Nombre Usuario:
                    $nombreUsuario = User::where('id',$funding['idUsuario'])->get();
                    $funding['idUsuario'] = isset($nombreUsuario[0]['name']) ? $nombreUsuario[0]['name'] : 'Undefined';;
                }
                // Ordenar elementos de las facturas:
                $fundingArray = [];
                foreach ($fundings as $funding) {
                    $newFunding = [
                        'Id' => $funding['id'],
                        'Progress Report Year' => $funding['progressReport'],
                        'User' => $funding['idUsuario'],
                        'Researchers Involved' => $funding['researcherInvolved'],
                        'Research Lines Involved' => $funding['researchLinesInvolved'],
                        'Type Sources' => $funding['typeSources'],
                        'Name of the institution' => $funding['nameOfInstitution'],
                        'Program/contest' => $funding['programContest'],
                        'Project Title' => $funding['projectTitle'],
                        'Principal Researcher' => $funding['principalResearcher'],
                        'Start' => $funding['startDate'],
                        'Finish' => $funding['finishDate'],
                        'In Cash' => $funding['inCash'],
                        'Type of collaboration' => $funding['typeOfCollaboration'],
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
                return ['Id', 'Progress Report Year','User','Researchers Involved','Research Lines Involved','Type Sources','Name of the institution','Program/contest','Project Title','Principal Researcher','Start','Finish','In Cash','Type of collaboration','Comments'];
            }

            public function columnWidths(): array
            {
                // Generar automáticamente un arreglo con todas las letras de la A a la Q y asignarles un ancho de 50 unidades
                $specificWidths = array_fill_keys(range('C', 'N'), 40);
            
                // Devolver el arreglo de anchos específicos
                return $specificWidths;
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
                return $defaultStyle;
            }
        
            public function styles(Worksheet $sheet)
            {
                // Establecer estilos para el encabezado
                $sheet->getStyle('A1:O1')->applyFromArray([
                    'font' => [
                        'Calibri' => true,
                        'bold' => true,
                        'size' => 12,
                        'color' => [
                            'rgb' => '000000', // Color blanco
                        ],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'ED8D1D', // Color naranja
                        ],
                    ],
                ]);
        
                // Establecer estilos para las celdas (excepto el encabezado)
                $sheet->getStyle('A2:O'.$sheet->getHighestRow())->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'F2F2F2', // Color gris claro
                        ],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'], // Color negro
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
                        $sheet->setAutoFilter('A1:O1');
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
