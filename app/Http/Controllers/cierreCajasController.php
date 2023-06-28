<?php

namespace App\Http\Controllers;
use App\Models\TIE_cierreCajas as CierreCajas;
use App\Models\CTB_fofi as FondosFijos;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class cierreCajasController extends Controller
{

    public function index()
    {
        return CierreCajas::with("tienda")->with("reffondofijo")->get();
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $estadoDeposito = $request['depositoEstado'];
        unset($request['depositoEstado']);
        $campos = [
            'idTienda' => 'required',
            'diaCierre' => 'required',
            'imagenVoucher' => 'required',
            'responsableCierre' => 'required',
            'idRefFondoFijo' => Rule::requiredIf($request['fondoFijo'] > 0),
            // 'deposito' => Rule::requiredIf($estadoDeposito == true),
            // 'fechaDeposito' => [Rule::requiredIf($estadoDeposito == true),'after:diaCierre'],
            // 'imagenDeposito' => Rule::requiredIf($estadoDeposito == true),
        ];
        $mensaje = [
            'idTienda.required' => 'El campo "Tienda" es obligatorio.',
            'diaCierre.required' => 'El campo "Fecha Dia Cierre" es obligatorio.',
            'imagenVoucher.required' => 'El campo "Imagen Voucher" es obligatorio.',
            'responsableCierre.required' => 'El campo "Responsable Cierre" es obligatorio.',
            'idRefFondoFijo.required' => 'El campo "Referencia de Fondo Fijo" es obligatorio si posee gastos en fondo fijo.',
            // 'deposito.required' => 'El campo "Depósito" es obligatorio si agrega un depósito.',
            // 'fechaDeposito.required' => 'El campo "Fecha Depósito" es obligatorio si agrega un depósito.',
            // 'fechaDeposito.after' => 'El campo "Fecha Depósito" debe ser posterior a "Fecha Día Cierre".',
            // 'imagenDeposito.required' => 'El campo "Imagen Depósito" es obligatorio si agrega un depósito.',
        ];
        $this->validate($request, $campos, $mensaje);
        $contenidoBinario = file_get_contents($request['imagenVoucher']);
        $imagen = base64_encode($contenidoBinario);
        $input = $request->all();
        CierreCajas::create($input);
        $cierreCaja = CierreCajas::orderBy('id', 'desc')->first();;
        if ($request['imagenDeposito'] != null){
            $contenidoBinario2 = file_get_contents($request['imagenDeposito']);
            $imagen2 = base64_encode($contenidoBinario2);
            $cierreCaja->imagenDeposito = $imagen2;
        }
        $cierreCaja->imagenVoucher = $imagen;
        $cierreCaja->save();

        // Cambiar estado de fondo fijo referencial:
        $fondoFijo = FondosFijos::find($request['idRefFondoFijo']);
        if($fondoFijo){
            $fondoFijo->estado = 'Retirado';
            $fondoFijo->save();
        }
        return response()->json($input);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function cierreCajasDeposito(Request $request)
    {
        $idCierreCajas = $request[0];
        $cierreCajas = $request[1];
        $requestCierreCajas = new Request($cierreCajas);

        $campos = [
            'deposito' => 'required',
            'imagenDeposito' => 'required',
            'fechaDeposito' => 'required|after:diaCierre',
        ];
        $mensaje = [
            'deposito.required' => 'El campo "Depósito" es obligatorio.',
            'imagenDeposito.required' => 'El campo "Imagen Depósito" es obligatorio.',
            'fechaDeposito.required' => 'El campo "Fecha Depósito" es obligatorio.',
            'fechaDeposito.after' => 'El campo "Fecha Depósito" debe ser posterior a "Fecha Día Cierre".',
        ];
        $this->validate($requestCierreCajas, $campos, $mensaje);
        $cierreCaja = CierreCajas::find($idCierreCajas);
        $cierreCaja->deposito = $requestCierreCajas->input('deposito');
        $cierreCaja->fechaDeposito = $requestCierreCajas->input('fechaDeposito');
        $cierreCaja->observacionDeposito = $requestCierreCajas->input('observacionDeposito');

        $contenidoBinario = file_get_contents($requestCierreCajas->input('imagenDeposito'));
        $imagen = base64_encode($contenidoBinario);
        $cierreCaja->imagenDeposito = $imagen;

        $cierreCaja->diferenciaDias = $requestCierreCajas->input('diferenciaDias');
        $cierreCaja->control = $requestCierreCajas->input('control');
        $cierreCaja->save();
        return response()->json("Depósito Realizado");
    }

    public function destroy($id)
    {
        CierreCajas::find($id)->delete();
        return response()->json("Cierre Caja Eliminado");
    }
}
