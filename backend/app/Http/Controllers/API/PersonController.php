<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
Use App\Models\Person;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
Use Log;

class PersonController extends Controller
{

    public function getAll(){
      $data = Person::get();
      return response()->json($data, 200);
    }

    public function create(Request $request){

        $validator = Validator::make($request->all(), [
            'first_last_name' => 'required|regex:/^[A-Za-z ]+$/|max:20',
            'second_last_name' => 'required|regex:/^[A-Za-z ]+$/|max:20',
            'first_name' => 'required|regex:/^[A-Za-z ]+$/|max:20',
            'other_names' => 'nullable|regex:/^[A-Za-z ]+$/|max:50',
            'country' => 'required|in:Colombia,United States',
            'identification_type' => 'required|in:Cédula de Ciudadanía,Cédula de Extranjería,Pasaporte,Permiso Especial',
            'identification_number' => 'required|max:20|unique:people,identification_number',
            'hire_date' => 'required|date|before_or_equal:today|after_or_equal:' . Carbon::now()->subMonth()->toDateString(),
            'area' => 'required|in:Administración,Financiera,Compras,Infraestructura,Operación,Talento Humano,Servicios Varios'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator,
                'error' => true
            ], 200);
        }
        $email = $this->generateEmail($request->first_name, $request->first_last_name, $request->country);

        $employee = new Person();
        $employee->first_last_name = $request->first_last_name;
        $employee->second_last_name = $request->second_last_name;
        $employee->first_name = $request->first_name;
        $employee->other_names = $request->other_names;
        $employee->country = $request->country;
        $employee->identification_type = $request->identification_type;
        $employee->identification_number = $request->identification_number;
        $employee->email = $email;
        $employee->hire_date = $request->hire_date;
        $employee->area = $request->area;
        $employee->registered_at = now();
        $employee->save();

        return response()->json([
            'message' => "Successfully created",
            'success' => true
        ], 200);
    }

    private function generateEmail($firstName, $lastName, $country)
    {
        $domain = $country === 'Colombia' ? 'global.com.co' : 'global.com.us';
        $baseEmail = strtolower($firstName . '.' . $lastName) . '@' . $domain;
        $email = $baseEmail;
        $counter = 1;

        while (Person::where('email', $email)->exists()) {
            $email = strtolower($firstName . '.' . $lastName) . '.' . $counter . '@' . $domain;
            $counter++;
        }
        return $email;
    }

    public function delete($id){
      $res = Person::find($id)->delete();
      return response()->json([
          'message' => "Successfully deleted",
          'success' => true
      ], 200);
    }

    public function get($id){
      $data = Person::find($id);
      return response()->json($data, 200);
    }

    public function update(Request $request,$id){
        $employee = Person::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'first_last_name' => 'required|regex:/^[A-Za-z ]+$/|max:20',
            'second_last_name' => 'required|regex:/^[A-Za-z ]+$/|max:20',
            'first_name' => 'required|regex:/^[A-Za-z ]+$/|max:20',
            'other_names' => 'nullable|regex:/^[A-Za-z ]+$/|max:50',
            'country' => 'required|in:Colombia,United States',
            'identification_type' => 'required|in:Cédula de Ciudadanía,Cédula de Extranjería,Pasaporte,Permiso Especial',
            'identification_number' => "required|max:20|unique:people,identification_number,{$employee->id}",
            'hire_date' => 'required|date|before_or_equal:today|after_or_equal:' . Carbon::now()->subMonth()->toDateString(),
            'area' => 'required|in:Administración,Financiera,Compras,Infraestructura,Operación,Talento Humano,Servicios Varios'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator,
                'error' => true
            ], 200);
        }
        
        $employee->first_last_name = $request->first_last_name;
        $employee->second_last_name = $request->second_last_name;
        $employee->first_name = $request->first_name;
        $employee->other_names = $request->other_names;
        $email = $this->generateEmail($request->first_name, $request->first_last_name, $request->country);
        $employee->country = $request->country;
        $employee->identification_type = $request->identification_type;
        $employee->identification_number = $request->identification_number;
        $employee->email = $email;
        $employee->hire_date = $request->hire_date;
        $employee->area = $request->area;
        $employee->registered_at = now();
        $employee->update();
        return response()->json([
            'message' => "Successfully updated",
            'success' => true
        ], 200);
    }
}
