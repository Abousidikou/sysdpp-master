<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Validator,Redirect,Response;
use App\Models\{Country,State,City};
 
class CountryStateCityController extends Controller
{
 
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data['countries'] = Country::get(["name","id"]);
        return view('country-state-city',$data);
    }
    
    public function getState($id)
    {
        $data['states'] = State::where("country_id",$id)
                    ->get(["name","id"]);
        return response()->json($data);
    }

    public function getCity($id)
    {
        $data['cities'] = City::where("state_id",$id)
                    ->get(["name","id"]);
        return response()->json($data);
    }
 
}