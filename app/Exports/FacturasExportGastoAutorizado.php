<?php

namespace App\Exports;
use App\Models\User;
use App\Models\CTB_facturas as Facturas;
use App\Models\SYS_autorizadores as Autorizadores;
use App\Models\SYS_proveedores as Proveedores;
use App\Models\SYS_empresas as Empresas;
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

class FacturasExportGastoAutorizado implements WithDefaultStyles, WithEvents, WithStyles, FromCollection, WithHeadings, WithProperties, ShouldAutoSize
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
            'title'          => 'Facturas en Proceso de Autorización de Pagos',
            'description'    => 'Facturas a la fecha, que aún están con autorización de pago pendiente.',
            'subject'        => 'Facturas en Proceso de Autorización de Pagos',
            'keywords'       => 'facturas,contabilidad,exportar,exportado,exportación',
            'category'       => 'Facturas en Proceso de Autorización de Pagos',
            'manager'        => 'Departamento de Informática',
            'company'        => 'Interandina de Comercio LTDA',
        ];
    }

    public function collection()
    {
        if ($this->idEmpresaSeleccionada == "Todas"){
            $facturas = Facturas::select('estado','rutProveedor','folio','tipoDocumento','tipoCompra','razonSocial','fechaDcto','fechaRecepcion','fechaAcuse','montoExento','montoNeto','montoIVARec','montoIVANoRec','montoTotal','codigoIVANoRec','montoNetoAF','IVAAF','IVAUsoComun','impSinCred','IVANoRetenido','NCEoNDE','otroImpuesto','valorOtroImpuesto','tasaOtroImpuesto','autorizable','idAutorizador1','autorizadoAuth1','idAutorizador2','autorizadoAuth2','observaciones','objeciones','fechaReq','fechaAuthGasto','fechaAuthPago','idUsuarioSolicitante','idEmpresa')->where('estado','Gasto Autorizado')->get();
        }
        else{
            $facturas = Facturas::select('estado','rutProveedor','folio','tipoDocumento','tipoCompra','razonSocial','fechaDcto','fechaRecepcion','fechaAcuse','montoExento','montoNeto','montoIVARec','montoIVANoRec','montoTotal','codigoIVANoRec','montoNetoAF','IVAAF','IVAUsoComun','impSinCred','IVANoRetenido','NCEoNDE','otroImpuesto','valorOtroImpuesto','tasaOtroImpuesto','autorizable','idAutorizador1','autorizadoAuth1','idAutorizador2','autorizadoAuth2','observaciones','objeciones','fechaReq','fechaAuthGasto','fechaAuthPago','idUsuarioSolicitante','idEmpresa')->where('estado','Gasto Autorizado')->where('idEmpresa', $this->idEmpresaSeleccionada)->get();
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
            // Añadir formato a "Autorizable":
            if($factura['autorizable'] === true){
                $factura['autorizable'] = "Autorizable";
            }
            else{
                $factura['autorizable'] = "No Autorizable";
            }
            // Añadir formato a "autorizados":
            if($factura['autorizadoAuth1'] === true){
                $factura['autorizadoAuth1'] = "Autorizado";
            }
            if($factura['autorizadoAuth1'] === false){
                $factura['autorizadoAuth1'] = "No Autorizado";
            }
            else{
                $factura['autorizadoAuth1'] = "Pendiente";
            }
            if($factura['autorizadoAuth2'] === true){
                $factura['autorizadoAuth2'] = "Autorizado";
            }
            if($factura['autorizadoAuth2'] === false){
                $factura['autorizadoAuth2'] = "No Autorizado";
            }
            else{
                $factura['autorizadoAuth2'] = "Pendiente";
            }
            // Añadir Nombre Empresa:
            $nombreEmpresa = Empresas::select('razonSocial')->find($factura['idEmpresa']);
            $factura['nombreEmpresa'] = $nombreEmpresa['razonSocial'];
            // Añadir Nombre Usuario Solicitante:
            $nombreUsuarioSolicitante = User::select('name')->find($factura['idUsuarioSolicitante']);
            $factura['nombreUsuarioSolicitante'] = $nombreUsuarioSolicitante['name'];
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
            $newFactura = [
                'estado' => $factura['estado'],
                'empresa' => $factura['nombreEmpresa'],
                'rutProveedor' => $factura['rutProveedor'],
                'folio' => $factura['folio'],
                'codigoSocioDeNegocios' => $factura['codSocioNegocio'],
                'razónSocial' => $factura['razonSocial'],
                'tipoDocumento' => $factura['tipoDocumento'],
                'tipoCompra' => $factura['tipoCompra'],
                'usuarioSolicitante' => $factura['nombreUsuarioSolicitante'],
                'observaciones' => $factura['observaciones'],
                'montoTotal' => $factura['montoTotal'],
                'montoExento' => $factura['montoExento'],
                'montoNeto' => $factura['montoNeto'],
                'montoIvaRecuperable' => $factura['montoIVARec'],
                'montoIvaNoRecuperable' => $factura['montoIVANoRec'],
                'codigoIvaNoRecuperable' => $factura['codigoIVANoRec'],
                'montoNetoActivoFijo' => $factura['montoNetoAF'],
                'ivaActivoFijo' => $factura['IVAAF'],
                'ivaUsoComun' => $factura['IVAUsoComun'],
                'impuestoSinDerechoACredito' => $factura['impSinCred'],
                'ivaNoRetenido' => $factura['IVANoRetenido'],
                'nceONde' => $factura['NCEoNDE'],
                'otroImpuesto' => $factura['otroImpuesto'],
                'valorOtroImpuesto' => $factura['valorOtroImpuesto'],
                'tasaOtroImpuesto' => $factura['tasaOtroImpuesto'],
                'autorizable' => $factura['autorizable'],
                'nombreAutorizador1' => $factura['nombreAutorizador1'],
                'autorizadoPorAutorizador1' => $factura['autorizadoAuth1'],
                'nombreAutorizador2' => $factura['nombreAutorizador2'],
                'autorizadoPorAutorizador2' => $factura['autorizadoAuth2'],
                'objeciones' => $factura['objeciones'],
                'fechaRecepcion' => $factura['fechaRecepcion'],
                'fechaDocumento' => $factura['fechaDcto'],
                'fechaAcuse' => $factura['fechaAcuse'],
                'fechaRequerimiento' => $factura['fechaReq'],
                'fechaAutorizacionGasto' => $factura['fechaAuthGasto'],
                'fechaAutorizacionPago' => $factura['fechaAuthPago'],
            ];
            array_push($facturasDoc, $newFactura);
        }
        // Convierte el array de objetos en una colección:
        $colection = new Collection($facturasDoc);

        // Convierte la colección en modelos de Eloquent:
        $model = User::hydrate($colection->toArray());
        return $model;
    }

    public function headings(): array
    {
        return ['Estado','Empresa','Rut Proveedor','Folio','Código Socio de Negocios','Razón Social','Tipo Documento','Tipo Compra','Usuario Solicitante','Observaciones','Monto Total','Monto Exento','Monto Neto','Monto IVA Recuperable','Monto IVA No Recuperable','Codigo IVA No Recuperable','Monto Neto Activo Fijo','IVA Activo Fijo','IVA Uso Comun','Impuesto sin Derecho a Credito','IVA No Retenido','NCE o NDE','Otro Impuesto','Valor Otro Impuesto','Tasa Otro Impuesto','¿Autorizable?','Nombre Autorizador 1','Autorizado por Autorizador 1','Nombre Autorizador 2','Autorizado por Autorizador 2','Objeciones','Fecha Recepcion','Fecha Documento','Fecha Acuse','Fecha Requerimiento','Fecha Autorización Gasto','Fecha Autorización Pago'];
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Obtiene la hoja activa
                $sheet = $event->sheet;
                // Se le aplica autofilter al header
                $sheet->setAutoFilter('A1:AK1');
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
        $sheet->getStyle('A1:AK1')->applyFromArray([
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
