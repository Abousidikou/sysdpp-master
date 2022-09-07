<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Data;
use App\Models\Domain;
use App\Models\Indicators;
use App\Models\Level;
use App\Models\Structures;
use App\Models\SubDomain;
use App\Models\Type;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */ 
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $indicators = Indicators::all();
        $types = Type::all();
        $levels = Level::all();
        $subDomains = SubDomain::all();
        $data = Data::all();

        $numberOfIndicators = $indicators->count();
        $numberOfTypes = $types->count();
        $numberOfLevels = $levels->count();
        $numberOfData = $data->count();

        $returnedArray = $this->getNumberOfDataPerDomain($data);
        $domainArray = $returnedArray['dom'];
        $domainData = $returnedArray['dat'];
        $dataStatArray = [];
        $total = 0;

        $i = 0;
        foreach($domainArray as $dom)
        {
            $value = floor((($domainData[$i]/$data->count())*100));
            $dataStatArray[] = array('label' => $dom, 'value' => $value);
            $i++;
        }
        //dd($dataStatArray);
        $infos = Data::orderBy('id','desc')->limit(5)->get();
        //dd($infos);
        foreach($infos as $info)
        {

            $info->userfn = $info->user->name; 
            $info->indicat = $info->indicator->wording; 

            $numberOfLevel = $info->levels()->count();
            if($numberOfLevel == 0)
            {
                $info->levelof = "Aucun niveau";
                $info->multilevel = false;
            }
            else if($numberOfLevel == 1)
            {   
                $info->levelof = $info->levels()->first()->wording;
                $info->multilevel = false;
            }
            else
            {
                $info->levelof = $info->levels()->first()->wording;
                $info->multilevel = true;
            }

            $info->subd = $info->indicator->subdomain->wording;
            $info->date_start = date_format(date_create($info->date_start),'d-m-Y');
            $info->date_end = date_format(date_create($info->date_end),'d-m-Y');
        }
        return view('index',compact('numberOfIndicators','numberOfTypes','numberOfLevels','numberOfData','dataStatArray','infos'));
    }

    public function redirect() 
    {
        return redirect('/dashboard');
    }

    public function getNumberOfDataPerDomain($allData)
    {
        $domainArray = [];
        $domainData = [];
        foreach($allData as $data)
        {
            $domain = $data->indicator->subdomain->domain->wording;
            if(in_array($domain,$domainArray))
            {
                $index = array_search($domain, $domainArray);
                $domainData[$index] = $domainData[$index] + 1;
            }
            else
            {
                $domainArray[] = $domain;
                $domainData[] = 1;
            }

        }
        
        return array('dom' => $domainArray, 'dat' => $domainData);
    }

    public function stat(Request $request)
    {
        $data = Data::all();
        $returnedArray = $this->getNumberOfDataPerDomain($data);
        $domainArray = $returnedArray['dom'];
        $domainData = $returnedArray['dat'];
        $dataStatArray = [];
        $total = 0;

        $i = 0;
        foreach($domainArray as $dom)
        {
            $value = floor((($domainData[$i]/$data->count())*100));
            $dataStatArray[] = array('label' => $dom, 'value' => $value);
            $i++;
        }
        //dd($dataStatArray);
        return json_encode($dataStatArray);
    }
}
