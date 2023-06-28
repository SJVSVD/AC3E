<?php

namespace App\Exports;
use App\Models\User;
use App\Models\CTB_facturas as Facturas;
use App\Models\SYS_autorizadores as Autorizadores;
use App\Models\SYS_proveedores as Proveedores;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
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
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\ValueBinder;
use PhpOffice\PhpSpreadsheet\Worksheet\AutoFilter\Column\Rule;
use PhpOffice\PhpSpreadsheet\Worksheet\AutoFilter\Column;
use PhpOffice\PhpSpreadsheet\Worksheet\AutoFilter\Column\Filter\ColumnFilter;
use PhpOffice\PhpSpreadsheet\Worksheet\AutoFilter\ColumnsAutoFilter;

class FacturasExportPropuesta implements WithDefaultStyles, WithEvents, WithStyles, FromCollection, WithHeadings, WithProperties, ShouldAutoSize
{
    public $idUsuarioDescarga;
    public $idEmpresaSeleccionada;

    public function __construct($idUsuarioDescarga, $idEmpresaSeleccionada)
    {
        $this->idUsuarioDescarga = $idUsuarioDescarga;
        $this->idEmpresaSeleccionada = $idEmpresaSeleccionada;
    }

    public function properties(): array
    {
        $nombreUsuarioDescarga = User::select('name')->find($this->idUsuarioDescarga);
        return [
            'creator'        => $nombreUsuarioDescarga['name'],
            'lastModifiedBy' => $nombreUsuarioDescarga['name'],
            'title'          => 'Propuesta',
            'description'    => 'Propuesta.',
            'subject'        => 'Propuesta',
            'keywords'       => 'facturas,propuesta,contabilidad,exportar,exportado,exportación',
            'category'       => 'Propuesta',
            'manager'        => 'Departamento de Informática',
            'company'        => 'Interandina de Comercio LTDA',
        ];
    }

    public function collection()
    {
        if ($this->idEmpresaSeleccionada == "Todas"){
            $facturas = Facturas::select('id','estado','fechaDcto','folio','rutProveedor','razonSocial','montoTotal','autorizable','idAutorizador1','autorizadoAuth1','idAutorizador2','autorizadoAuth2','observaciones','objeciones','idUsuarioSolicitante','idEmpresa')->get();
        }
        else{
            $facturas = Facturas::select('id','estado','fechaDcto','folio','rutProveedor','razonSocial','montoTotal','autorizable','idAutorizador1','autorizadoAuth1','idAutorizador2','autorizadoAuth2','observaciones','objeciones','idUsuarioSolicitante','idEmpresa')->where('idEmpresa', $this->idEmpresaSeleccionada)->get();
        }
        foreach($facturas as $factura){
            // Añadir Nombre Autorizador 1:
            if($factura['idAutorizador1'] !== null){
                $autorizador1 = Autorizadores::select('idUsuario')->find($factura['idAutorizador1']);
                $autorizador1 = User::select('name')->find($autorizador1);
                $factura['nombreAutorizador1'] = $autorizador1[0]['name'];
            }
            else{
                $factura['nombreAutorizador1'] = "-----";
            }
            // Añadir Nombre Autorizador 2:
            if($factura['idAutorizador2'] !== null){
                $autorizador2 = Autorizadores::select('idUsuario')->find($factura['idAutorizador2']);
                $autorizador2 = User::select('name')->find($autorizador2);
                $factura['nombreAutorizador2'] = $autorizador2[0]['name'];
            }
            else{
                $factura['nombreAutorizador2'] = "-----";
            }
            // Añadir Código Socio de Negocios:
            $proveedor = Proveedores::where('rut', $factura['rutProveedor'])->first();
            if($proveedor){
                $factura['codSocioNegocio'] = $proveedor['codSocioNegocio'];
            }
            if(!$proveedor || $proveedor['codSocioNegocio'] == null){
                $factura['codSocioNegocio'] = "-----";
            }
        }

        // Ordenar elementos de las facturas:
        $facturasDoc = [];
        foreach ($facturas as $factura) {
            $authTemp = "-----";
            if($factura['nombreAutorizador1'] != "-----"){
                $authTemp = $factura['nombreAutorizador1'];
                if($factura['nombreAutorizador2'] != "-----"){
                    $authTemp = $factura['nombreAutorizador1']." & ".$factura['nombreAutorizador2'];
                }
            }
            $newFactura = [
                'id' => $factura['id'],
                'estado' => $factura['estado'],
                'fechaDocumento' => $factura['fechaDcto'],
                'folio' => $factura['folio'],
                'nroTransaccion' => "-----",
                'cuentaAsociada' => "-----",
                'comentarios' => "-----",
                'autorizadores' => $authTemp,
                'codSocioNegocios' => $factura['codSocioNegocio'],
                'razonSocial' => $factura['razonSocial'],
                'montoTotal' => $factura['montoTotal'],
                'referencia1' => "-----",
            ];
            array_push($facturasDoc, $newFactura);
        }
        // Convierte el array de objetos en una colección:
        $colection = new Collection($facturasDoc);
        $colection = $colection->groupBy('codSocioNegocios');

        // Convierte la colección en modelos de Eloquent:
        $model = User::hydrate($colection->toArray());
        return $model;
    }

    public function headings(): array
    {
        return ['ID en Sistema','Estado','Fecha','Nº folio','Nº de transacción','Cuenta Asociada','Comentarios','Autorizador/es','Código SN','Socio Negocio','Saldo','Referencia 1 (Cabecera)'];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Valores iniciales
                $inicio = 2;
                $fin = null;
                // Obtiene la hoja activa
                $sheet = $event->sheet;
                // Se le aplica autofilter al header
                $sheet->setAutoFilter('A1:L1');
                // Obtener datos de la hoja
                $datos = $sheet->getDelegate()->toArray();
                array_shift($datos);
                // Formar cada grupo
                $grupos = [];
                $grupoTemporal = [];
                $datoAnterior = null;
                foreach ($datos as $dato) {
                    if ($dato == $datos[0]) {
                        array_push($grupoTemporal, $dato);
                    }
                    else{
                        if ($dato[8] == $datoAnterior[8]) {
                            array_push($grupoTemporal, $dato);
                            if ($dato == $datos[count($datos)-1]) {
                                array_push($grupos, $grupoTemporal);
                            }
                        }
                        else{
                            array_push($grupos, $grupoTemporal);
                            $grupoTemporal = [];
                            array_push($grupoTemporal, $dato);
                            if ($dato == $datos[count($datos)-1]) {
                                array_push($grupos, $grupoTemporal);
                            }
                        }
                    }
                    $datoAnterior = $dato;
                }
                // Recorrer grupos para sumar "Saldos"
                foreach ($grupos as $grupo) {
                    // Obtener el número de la última fila del grupo
                    $fin = $inicio + count($grupo) - 1;
                    // SUMA DE SALDOS:
                    // Creación formula
                    $formula = '=SUM(K'.$inicio.':K'.$fin.')';
                    // Inserta una nueva fila para el subtotal
                    $sheet->insertNewRowBefore($fin + 1);
                    // Establecer la fórmula en la fila creada
                    $sheet->setCellValue('K' . ($fin + 1), $formula);
                    // Establecer el nombre del subtotal
                    $sheet->setCellValue('J' . ($fin + 1), 'Total '.$grupo[0][9]);
                    // Actualizar el número de la primera fila para el próximo grupo
                    $inicio = $fin + 2;
                    // Estilo del subtotal:
                    $sheet->getStyle('A' . ($fin + 1) . ':L' . ($fin + 1))->applyFromArray([
                        'font' => [
                            'Calibri' => true,
                            'bold' => true,
                            'size' => 11,
                            'color' => [
                                'rgb' => '000000',
                            ],
                        ],
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'BDD7EE',
                            ],
                        ],
                    ]);
                }
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
        $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
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
}
