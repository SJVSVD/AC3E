<?php

namespace App\Http\Controllers;
use App\Models\books;
use App\Models\User;
use Illuminate\Http\Request;

class booksController extends Controller
{
    public function store(Request $request)
    {
        $input = $request->all();
        $books = books::create($input);
        return response()->json("Libro Creada!");
    }

    public function show($userID){
        // Seleccionar datos relacionados con el usuario:
        $roles = [];
        $administrador = false;
        $books = books::where('centerResearcher', $userID)->with('usuario')->get();
        
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
            $books = books::where(function($query) use ($userName, $userID) {
                $query->where('researcherInvolved', 'LIKE', "%{$userName}.%")
                      ->orWhere('idUsuario', $userID);
            })->with('usuario')->get();
        }else{
            $books = books::with('usuario')->get();
        }
        return $books;
    }

    public function verifyBook(Request $request)
    {
        $query = books::where('bookTitle', $request['bookTitle'])
            ->where('chapterTitle', $request['chapterTitle'])
            ->whereNotNull('bookTitle')
            ->whereNotNull('chapterTitle');

        if ($request->has('id')) {
            $query->where('id', '!=', $request->input('id'));
        }
    
        $existentes = $query->count();
    
        return response()->json($existentes); 
    }

    public function update(Request $request, $id)
    {
        $books = books::find($id);

        $input = $request->all();
        $books->update($input);
        return response()->json("Libro Editada");
    }

    public function destroy($id)
    {
        books::find($id)->delete();
        return response()->json("Libro Eliminado");
    }
}
