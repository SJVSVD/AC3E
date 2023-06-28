<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index(string $page)
    {
        if (view()->exists("pages.$page")) {
            return view("pages.$page");
        }
        return abort(404);
    }

    public function adminGeneral()
    {
        return view('pages.adminGeneral');
    }

    public function adminGeneralEmpresa()
    {
        return view('pages.adminGeneralEmpresa');
    }

    public function adminGeneralUbicacion()
    {
        return view('pages.adminGeneralUbicacion');
    }

    public function adminGeneralTiendas()
    {
        return view('pages.adminGeneralTiendas');
    }

    public function adminGeneralUsuarios()
    {
        return view('pages.adminGeneralUsuarios');
    }

    public function adminGeneralOtros()
    {
        return view('pages.adminGeneralOtros');
    }

    public function adminColab()
    {
        return view('pages.adminColab');
    }

    public function adminUsuarios()
    {
        return view('pages.adminUsuarios');
    }

    public function queryTests()
    {
        return view('pages.queryTests');
    }

    public function adminRoles()
    {
        return view('pages.adminRoles');
    }

    public function facturas()
    {
        return view('pages.systems.facturas');
    }

    public function fofi()
    {
        return view('pages.systems.fondoFijos');
    }

    public function gestorComex()
    {
        return view('pages.systems.gestorComex');
    }

    public function gestorOC()
    {
        return view('pages.systems.gestorOC');
    }

    public function informatica()
    {
        return view('pages.systems.informatica');
    }

    public function gestionTienda()
    {
        return view('pages.systems.gestionTienda');
    }

    public function cierreCajas()
    {
        return view('pages.systems.cierreCajas');
    }

    public function solicitarPrecios()
    {
        return view('pages.systems.solicitarPrecios');
    }

    public function remuneraciones()
    {
        return view('pages.systems.remuneraciones');
    }
    
    public function dashboard()
    {
        return view('pages.dashboard');
    }

    public function rendiciones()
    {
        return view('pages.systems.rendiciones');
    }
    
    public function userProfile()
    {
        return view('pages.userProfile');
    }

    public function gestionRobos()
    {
        return view('pages.systems.gestionRobos');
    }

    public function gestionConcesiones()
    {
        return view('pages.systems.gestionConcesiones');
    }

    public function manuales()
    {
        return view('pages.manuales');
    }

    public function ncv()
    {
        return view('pages.systems.ncv');
    }

    public function ventasVendedor()
    {
        return view('pages.systems.ventasVendedor');
    }

    
}
