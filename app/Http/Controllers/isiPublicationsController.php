<?php

namespace App\Http\Controllers;
use App\Models\isiPublication;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class isiPublicationsController extends Controller
{
    public function store(Request $request)
    {
        $input = $request->all();
        $isiPublication = isiPublication::create($input);
        return response()->json("Publicación Creada!");
    }

    public function verifyIsi(Request $request)
    {
        $doi = $request['doi'];
        $query = isiPublication::where('doi', $doi)
            ->whereNotNull('doi');
    
        if ($request->has('id')) {
            $query->where('id', '!=', $request->input('id'));
        }
    
        $existentes = $query->count();
    
        return response()->json($existentes); 
    }

    public function show($userID){
        // Seleccionar datos relacionados con el usuario:
        $roles = [];
        $administrador = false;
        $isiPublications = isiPublication::where('idUsuario', $userID)->with('usuario')->get();
        
        $user = User::where('id', $userID)->with('roles')->get();
        // Mantener aquellos que cumplen con los roles del usuario:
        if ($user[0]['roles'] == "[]"){
            array_push($roles,'');
        }
        else{
            foreach ($user[0]['roles'] as $rol){
                if ($rol['name'] == 'Administrator'){
                    array_push($roles, $rol['name']);
                    $administrador = true;
                }
            }
        }
        if($administrador == false){
            $userName = User::findOrFail($userID)->name;
            $isiPublications = IsiPublication::where(function($query) use ($userName, $userID) {
                $query->where('researcherInvolved', 'LIKE', "%{$userName}.%")
                      ->orWhere('idUsuario', $userID);
            })->with('usuario')->get();
        }else{
            $isiPublications = isiPublication::with('usuario')->get();
        }
        return $isiPublications;
    }

    public function update(Request $request, $id)
    {
        $isiPublication = isiPublication::find($id);

        $input = $request->all();
        $isiPublication->update($input);
        return response()->json("Publicación Editada");
    }

    public function destroy($id)
    {
        isiPublication::find($id)->delete();
        return response()->json("Publicación Eliminado");
    }

    public function useDoi(Request $request)
    {
        $doi = $request->input('doi');
        $url = "https://wos-api.clarivate.com/api/woslite/?databaseId=WOS&usrQuery=DO=".$doi."&count=10&firstRecord=1";
        $headers = [
            "accept" => "application/json",
            "X-ApiKey" => "cc369e7fe729a62bbb01048470df4ed604027c45"
        ];

        $response = Http::withHeaders($headers)->get($url);

        $data = $response->json();

        return response()->json($data);
    }
}
