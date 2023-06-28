<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\TIE_cierreCajas;

class cierreCajasSeeder extends Seeder
{
    public function run()
    {
        $cierresCajas = [
            ['idTienda' => '1', 'responsableCierre' => 'Nombre1 Nombre2 Apellido1 Apellido2', 'diaCierre' => '2023-04-10', 'imagenVoucher' => '111111', 'observacionCierre' => '11111111', 'notaCredito' => 10000, 'ventaBruta' => 120000, 'ventaTotal' => 110000, 'fondoFijo' => 20000, 'tarjeta' => 0, 'transferencia' => 0, 'efectivo' => 100000, 'cheque' => 0, 'cornershop' => 0, 'rappi' => 0, 'personal' => 0, 'marinaOnline' => 0, 'vale' => 0, 'deposito' => 90000, 'fechaDeposito' => '2023-04-12', 'observacionDeposito' => '111111SEGUNDA', 'diferenciaDias' => -1, 'control' => -1000,],
            ['idTienda' => '2', 'responsableCierre' => 'Nombre1 Nombre2 Apellido1 Apellido2', 'diaCierre' => '2023-04-10', 'imagenVoucher' => '222222', 'observacionCierre' => '22222222', 'notaCredito' => 10000, 'ventaBruta' => 120000, 'ventaTotal' => 110000, 'fondoFijo' => 20000, 'tarjeta' => 0, 'transferencia' => 0, 'efectivo' => 100000, 'cheque' => 0, 'cornershop' => 0, 'rappi' => 0, 'personal' => 0, 'marinaOnline' => 0, 'vale' => 0, 'deposito' => 80000, 'fechaDeposito' => '2023-04-12', 'observacionDeposito' => '222222SEGUNDA', 'diferenciaDias' => 1, 'control' => 10000,],
            ['idTienda' => '3', 'responsableCierre' => 'Nombre1 Nombre2 Apellido1 Apellido2', 'diaCierre' => '2023-04-10', 'imagenVoucher' => '333333', 'observacionCierre' => '33333333', 'notaCredito' => 10000, 'ventaBruta' => 120000, 'ventaTotal' => 110000, 'fondoFijo' => 20000, 'tarjeta' => 0, 'transferencia' => 0, 'efectivo' => 100000, 'cheque' => 0, 'cornershop' => 0, 'rappi' => 0, 'personal' => 0, 'marinaOnline' => 0, 'vale' => 0, 'deposito' => 90000, 'fechaDeposito' => '2023-04-12', 'observacionDeposito' => '333333SEGUNDA', 'diferenciaDias' => 5, 'control' => 0,],
            ['idTienda' => '4', 'responsableCierre' => 'Nombre1 Nombre2 Apellido1 Apellido2', 'diaCierre' => '2023-04-10', 'imagenVoucher' => '444444', 'observacionCierre' => '44444444', 'notaCredito' => 10000, 'ventaBruta' => 120000, 'ventaTotal' => 110000, 'fondoFijo' => 20000, 'tarjeta' => 0, 'transferencia' => 0, 'efectivo' => 100000, 'cheque' => 0, 'cornershop' => 0, 'rappi' => 0, 'personal' => 0, 'marinaOnline' => 0, 'vale' => 0, 'deposito' => 50000, 'fechaDeposito' => '2023-04-12', 'observacionDeposito' => '444444SEGUNDA', 'diferenciaDias' => 9, 'control' => 40000,],
            ['idTienda' => '5', 'responsableCierre' => 'Nombre1 Nombre2 Apellido1 Apellido2', 'diaCierre' => '2023-04-10', 'imagenVoucher' => '555555', 'observacionCierre' => '55555555', 'notaCredito' => 10000, 'ventaBruta' => 120000, 'ventaTotal' => 110000, 'fondoFijo' => 20000, 'tarjeta' => 0, 'transferencia' => 0, 'efectivo' => 100000, 'cheque' => 0, 'cornershop' => 0, 'rappi' => 0, 'personal' => 0, 'marinaOnline' => 0, 'vale' => 0, 'deposito' => 90000, 'fechaDeposito' => '2023-04-12', 'observacionDeposito' => '555555SEGUNDA', 'diferenciaDias' => 15, 'control' => 0,],
            ['idTienda' => '6', 'responsableCierre' => 'Nombre1 Nombre2 Apellido1 Apellido2', 'diaCierre' => '2023-04-10', 'imagenVoucher' => '666666', 'observacionCierre' => '66666666', 'notaCredito' => 10000, 'ventaBruta' => 120000, 'ventaTotal' => 110000, 'fondoFijo' => 20000, 'tarjeta' => 0, 'transferencia' => 0, 'efectivo' => 100000, 'cheque' => 0, 'cornershop' => 0, 'rappi' => 0, 'personal' => 0, 'marinaOnline' => 0, 'vale' => 0, 'deposito' => 0, 'fechaDeposito' => null, 'observacionDeposito' => null, 'diferenciaDias' => null, 'control' => 0,],
            ['idTienda' => '7', 'responsableCierre' => 'Nombre1 Nombre2 Apellido1 Apellido2', 'diaCierre' => '2023-04-10', 'imagenVoucher' => '777777', 'observacionCierre' => '77777777', 'notaCredito' => 10000, 'ventaBruta' => 120000, 'ventaTotal' => 110000, 'fondoFijo' => 20000, 'tarjeta' => 0, 'transferencia' => 100000, 'efectivo' => 0, 'cheque' => 0, 'cornershop' => 0, 'rappi' => 0, 'personal' => 0, 'marinaOnline' => 0, 'vale' => 0, 'deposito' => 90000, 'fechaDeposito' => '2023-04-12', 'observacionDeposito' => '777777SEGUNDA', 'diferenciaDias' => 2, 'control' => 0,],
            ['idTienda' => '8', 'responsableCierre' => 'Nombre1 Nombre2 Apellido1 Apellido2', 'diaCierre' => '2023-04-10', 'imagenVoucher' => '888888', 'observacionCierre' => '88888888', 'notaCredito' => 10000, 'ventaBruta' => 120000, 'ventaTotal' => 110000, 'fondoFijo' => 20000, 'tarjeta' => 0, 'transferencia' => 0, 'efectivo' => 100000, 'cheque' => 0, 'cornershop' => 0, 'rappi' => 0, 'personal' => 0, 'marinaOnline' => 0, 'vale' => 0, 'deposito' => 0, 'fechaDeposito' => null, 'observacionDeposito' => null, 'diferenciaDias' => null, 'control' => 0,],
        ];

        foreach($cierresCajas as $cierreCaja){
            $cierreCaja1 = TIE_cierreCajas::create($cierreCaja);
        };
    }
}