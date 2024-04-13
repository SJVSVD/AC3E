<?php

namespace App\Http\Controllers;
use App\Models\thesisStudent;
use App\Models\User;
use Illuminate\Http\Request;

class thesisStudentController extends Controller
{
    public function store(Request $request)
    {
        $input = $request->all();
        if($request->hasFile('file')){
            $input['file'] = $request->file('file')->store('thesisExtracts','public');
        }
        $thesisStudent = thesisStudent::create($input);
        return response()->json("Thesis Creada!");
    }

    public function verifyThesis(Request $request)
    {
        $query = thesisStudent::where('runOrPassport', $request['runOrPassport'])
            ->where('degreeDenomination', $request['degreeDenomination'])
            ->whereNotNull('runOrPassport')
            ->whereNotNull('degreeDenomination');
    
        // Si el request trae el campo 'id', agregamos una condición para excluir ese ID de la búsqueda
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
        $thesisStudents = thesisStudent::where('idUsuario', $userID)->with('usuario')->get();
        
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
            $thesisStudents = thesisStudent::where(function($query) use ($userName, $userID) {
                $query->where('researcherInvolved', 'LIKE', "%{$userName}.%")
                      ->orWhere('idUsuario', $userID);
            })->with('usuario')->get();
        }else{
            $thesisStudents = thesisStudent::with('usuario')->get();
        }
        return $thesisStudents;
    }

    public function addFile(Request $request){
        $input = $request->all();
        
        $thesis = thesisStudent::where('id', $input['id'])->first();
        if(gettype($input['file']) == 'object'){
            if($request->hasFile('file')){
                $input['file'] = $request->file('file')->store('thesisExtracts','public');
            }
        }else if($input['file'] == 'null'){
            unset($input['file']);
        }
        $thesis = thesisStudent::find($request['id'])->update($input);
        return response()->json($thesis);
    }

    public function thesisDownload($id){
        $thesis = thesisStudent::find($id);
        $pathtoFile = public_path().'/defaults/'.$thesis['file'];
        return response()->download($pathtoFile);
    }

    public function update(Request $request, $id)
    {
        $thesis = thesisStudent::find($id);
        $input = $request->all();
        $thesis->update($input);
        return response()->json('se ha guardado');
    }

    public function importThesis(Request $request)
    {
        $data = $request->input('data');
        foreach ($data as $rowData) {
            $gender =  '';
            if($rowData['Gender (M/F)'] == 'M'){
                $gender =  'Male';
            }else{
                $gender = 'Female';
            }

            $academicDegreeMapping = [
                "1" => "Undergraduate degree or profesional title",
                "2" => "Master o equivalent",
                "3" => "PhD degree"
            ];
            
            // Verificar si el campo 'Academic Degree' está presente y es un valor válido en el mapeo
            $academicDegree = isset($rowData['Academic Degree']) && isset($academicDegreeMapping[$rowData['Academic Degree']]) ? $academicDegreeMapping[$rowData['Academic Degree']] : '';
            
            $activityName = '';

            $optionsMapping = [
                '1' => 'Equipment',
                '2' => 'Information',
                '3' => 'Infrastructure',
                '4' => 'Other'
            ];
        

            // Verificar si el campo 'Type of Event' está presente
            if (isset($rowData['Resources provided by the Center'])) {
                // Separar el campo 'Type of Event' por comas
                $events = explode(',', $rowData['Resources provided by the Center']);
            
                // Iterar sobre cada evento
                foreach ($events as &$event) {
                    // Eliminar espacios en blanco alrededor del evento
                    $event = trim($event);
            
                    // Reemplazar el 6 seguido de un espacio y paréntesis por 'Other'
                    $event = preg_replace('/\b4\s*\(/', 'Other (', $event);
            
                    // Verificar si el evento es un número válido en el mapeo
                    if (isset($optionsMapping[$event])) {
                        // Si es un número válido, asignar el valor correspondiente del mapeo
                        $event = $optionsMapping[$event];
                    } elseif ($event === '6') {
                        // Si el evento es '6', asignar el valor 'Other'
                        $event = 'Other';
                    }
                }
            
                // Unir los eventos nuevamente en una cadena separada por comas
                $activityName = implode(', ', $events);
            
                // Agregar un punto al final de la cadena si no está presente
                if (!empty($activityName)) {
                    $activityName .= '.';
                }
            }      

            $optionsMapping2 = [
                '1' => "Private Education",
                '2' => "Business",
                '3' => "Own entrepreneurship",
                '4' => "Government",
                '5' => "Public Education",
                '6' => "Social-ONG",
                '7' => "In the Center",
                '8' => "None of the above"
            ];
            
            // Verificar si el campo 'options' está presente y es un valor válido en el mapeo
            $posteriorArea = isset($rowData['Posterior working area']) && isset($optionsMapping2[$rowData['Posterior working area']]);

            $thesisStudent = thesisStudent::create([
                'idUsuario' => $rowData['idUsuario'],
                'status' => $rowData['Status'],
                'identification' => $rowData['Identification'],
                'studentName' => $rowData['Student Name'],
                'runOrPassport' => $rowData['RUN or Passport'],
                'gender' => $gender,
                'studentMail' => $rowData['Student Mail'],
                'thesisStatus' => $rowData['Thesis Status'],
                'thesisTitle' => $rowData['Thesis Title'],
                'academicDegree' => $academicDegree,
                'degreeDenomination' => $rowData['Degree Denomination'],
                'tutorName' => $rowData['Name1'],
                'tutorInstitution' => $rowData['Institution1'],
                'cotutorName' => $rowData['Name2'],
                'cotutorInstitution' => $rowData['Institution2'],
                'otherName' => $rowData['Name3'],
                'otherInstitution' => $rowData['Institution3'],
                'university' => $rowData['University that gives the degree'],
                'yearStart' => $rowData['Year in which thesis starts'],
                'yearThesisEnd' => $rowData['Year in which thesis ends'],
                'resourcesCenter' => $activityName,
                'posteriorArea' => $posteriorArea,
                'institutionPosteriorArea' => $rowData['Institution of Posterior working area'],
                'comments' => $rowData['Comentarios'],
                'progressReport' => $rowData['Progress Report'],
            ]);
        }
        
        return response()->json("Publicaciónes importadas");
    }

    public function destroy($id)
    {
        thesisStudent::find($id)->delete();
        return response()->json("Thesis Eliminado");
    }
}
