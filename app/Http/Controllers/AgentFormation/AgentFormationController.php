<?php

namespace App\Http\Controllers\AgentFormation;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\AgentFormation;
use App\Models\Structures;
use App\Models\Level;
use App\Models\MiseEnStage;
use App\Models\Indicators;
use App\Models\RetourDeStage;
use phpDocumentor\Reflection\PseudoTypes\False_;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\RichText\RichText;

class AgentFormationController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
    	$agents = AgentFormation::all();
        $ann = collect();
        $aggV = DB::table('mise_en_stages')->select('annee_stage')->distinct('annee_stage')->get();
        $aggA = DB::table('retour_de_stages')->select('annee_rs')->distinct('annee_rs')->get();  
        foreach ($aggV as $val) {
            $ann[] = $val->annee_stage;
        }   
        foreach ($aggA as $val) {
            $ann[] = $val->annee_rs;
        } 
    	return view('admin.agentFormation.agentFormation',compact('agents','ann'));
    }

    
    public function form()
    {
        $id_status =  DB::table('type')->select('id')->where('wording','Statut')->first();
        $status = Level::where('id_type',$id_status->id)->get();
        $id_cat =  DB::table('type')->select('id')->where('wording','Categorie')->first();
        $categories = Level::where('id_type',$id_cat->id)->get();
        $id_corps = DB::table('type')->select('id')->where('wording','Corps de la fonction publique')->first();
        $corps = Level::where('id_type',$id_corps->id)->get();
        $id_structure =  DB::table('type')->select('id')->where('wording','Structure')->first();
        $structures = Level::where('id_type',$id_structure->id)->get();
        $id_sexe =  DB::table('type')->select('id')->where('wording','Sexe')->first();
        $sexes = Level::where('id_type',$id_sexe->id)->get();
        return view('admin.agentFormation.form_agents',compact('sexes','status','categories','corps','structures'));
    }

    public function insert(Request $request)
    {
 		$rules = [
            'nom_prenoms' =>'required'
        ];
 
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails())
        {
            return redirect()->back()->with('validation','error');
        }
        else
        {
            $agent = new AgentFormation;
            $agent->matricule = $request->matricule;
            $agent->diplome_base = $request->diplome_base;
            $agent->sexe = $request->sexe;
            $agent->nom_prenoms = $request->nom_prenoms;
            $agent->status = $request->status;
            $agent->categorie = $request->categorie;
            $agent->corps = $request->corps;
            $agent->structure = $request->structure;
            $agent->plan_formation = $request->plan_formation;
            if($agent->save())
	        {
	            return redirect()->back()->with('success','success');
	        }
	        else
	        {
	            return redirect()->back()->with('error','error');
	        }
        }
        
 		
        
    }
 
    public function delete($id)
    {
        $agent = AgentFormation::where('id',$id)->get();
        if(!$agent->isEmpty())
        {
            $agentIsDeleted = AgentFormation::find($id)->delete();
            if($agentIsDeleted)
            {
                return redirect()->back()->with('success','success');
            }
            else
            {
                return redirect()->back()->with('emerror','error');
            }
        }
        else
        {
            return redirect()->back()->with('error','error');
        }
    }


    public function prepareUpdate(Request $request)
    {
        $agent = AgentFormation::where('id',$request->id)->first();
        $id_status =  DB::table('type')->select('id')->where('wording','Statut')->first();
        $status = Level::where('id_type',$id_status->id)->get();
        $id_cat =  DB::table('type')->select('id')->where('wording','Categorie')->first();
        $categories = Level::where('id_type',$id_cat->id)->get();
        $id_corps = DB::table('type')->select('id')->where('wording','Corps de la fonction publique')->first();
        $corps = Level::where('id_type',$id_corps->id)->get();
        $id_structure =  DB::table('type')->select('id')->where('wording','Structure')->first();
        $structures = Level::where('id_type',$id_structure->id)->get();
        $id_sexe =  DB::table('type')->select('id')->where('wording','Sexe')->first();
        $sexes = Level::where('id_type',$id_sexe->id)->get();
        return view('admin.agentFormation.update_agents',compact('sexes','agent','status','categories','corps','structures'));
    }

    public function update(Request $request)
    {
        
        $rules = [
            'nom_prenoms' =>'required',
            'matricule' => 'required'
        ];
 
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails())
        {
            return redirect()->back()->with('validation','error');
        }
        else
        {
            
            $agent = AgentFormation::where('matricule','123456')->first();
            $agent->diplome_base = $request->diplome_base;
            $agent->sexe = $request->sexe;
            $agent->nom_prenoms = $request->nom_prenoms;
            $agent->status = $request->status;
            $agent->categorie = $request->categorie;
            $agent->corps = $request->corps;
            $agent->structure = $request->structure;
            $agent->plan_formation = $request->plan_formation;
            if($agent->save())
            {
                return redirect()->back()->with('success','success');
            }
            else
            {
                return redirect()->back()->with('error','error');
            }
        }

    } 


    

    public function export(Request $request)
    {
        $spreadsheet = new Spreadsheet();
        $agentsheet = $spreadsheet->getActiveSheet();
        $agents = AgentFormation::all();
        $datasetname = "agents";
        $agentsheet->getCellByColumnAndRow(1,1)->setValue("Matricule");
        $agentsheet->getCellByColumnAndRow(2,1)->setValue("Nom & Prenoms");
        $agentsheet->getCellByColumnAndRow(3,1)->setValue("Diplôme de base");
        $agentsheet->getCellByColumnAndRow(4,1)->setValue("Sexe");
        $agentsheet->getCellByColumnAndRow(5,1)->setValue("Status");
        $agentsheet->getCellByColumnAndRow(6,1)->setValue("Catégorie");
        $agentsheet->getCellByColumnAndRow(7,1)->setValue("Corps de la fonction publique");
        $agentsheet->getCellByColumnAndRow(8,1)->setValue("Structure");
        $agentsheet->getCellByColumnAndRow(9,1)->setValue("Plan de formation");

        $i = 2;
        foreach($agents as $agent)
        {
            $agentsheet->getCellByColumnAndRow(1,$i)->setValue($agent->matricule);
            $agentsheet->getCellByColumnAndRow(2,$i)->setValue($agent->nom_prenoms);
            $agentsheet->getCellByColumnAndRow(3,$i)->setValue($agent->diplome_base);
            $agentsheet->getCellByColumnAndRow(4,$i)->setValue($agent->sexe);
            $agentsheet->getCellByColumnAndRow(5,$i)->setValue($agent->status);
            $agentsheet->getCellByColumnAndRow(6,$i)->setValue($agent->categorie);
            $agentsheet->getCellByColumnAndRow(7,$i)->setValue($agent->corps);
            $agentsheet->getCellByColumnAndRow(8,$i)->setValue($agent->structure);
            $agentsheet->getCellByColumnAndRow(9,$i)->setValue($agent->plan_formation);
            $i++;
        }

        $spreadsheet->setActiveSheetIndex(0);

        $writer = new Xlsx($spreadsheet);
        $path = public_path();
        $path = $path."/template/$datasetname.xlsx";
        $writer->save($path);

        return redirect()->back()->with("path","template/$datasetname.xlsx");
    }


    public function buildAncienAgent(Spreadsheet $spreadsheet)
    {
        $datasetSheet = new Worksheet($spreadsheet,'Anciens agents');
        $spreadsheet->addSheet($datasetSheet, 0);
        
        $datasetSheet = $spreadsheet->getSheet(0);
        $datasetSheet->getCellByColumnAndRow(1,1)->setValue("Matricule");
        $datasetSheet->getCellByColumnAndRow(2,1)->setValue("Nom & Prenoms");
        $datasetSheet->getCellByColumnAndRow(3,1)->setValue("Diplôme de base");
        $datasetSheet->getCellByColumnAndRow(4,1)->setValue("Sexe");
        $datasetSheet->getCellByColumnAndRow(5,1)->setValue("Status");
        $datasetSheet->getCellByColumnAndRow(6,1)->setValue("Catégorie");
        $datasetSheet->getCellByColumnAndRow(7,1)->setValue("Corps de la fonction publique");
        $datasetSheet->getCellByColumnAndRow(8,1)->setValue("Structure");
        $datasetSheet->getCellByColumnAndRow(9,1)->setValue("Plan de formation");

        $agents = AgentFormation::all();
        $i = 2;
        foreach($agents as $agent)
        {
            $datasetSheet->getCellByColumnAndRow(1,$i)->setValue($agent->matricule);
            $datasetSheet->getCellByColumnAndRow(2,$i)->setValue($agent->nom_prenoms);
            $datasetSheet->getCellByColumnAndRow(3,$i)->setValue($agent->diplome_base);
            $datasetSheet->getCellByColumnAndRow(4,$i)->setValue($agent->sexe);
            $datasetSheet->getCellByColumnAndRow(5,$i)->setValue($agent->status);
            $datasetSheet->getCellByColumnAndRow(6,$i)->setValue($agent->categorie);
            $datasetSheet->getCellByColumnAndRow(7,$i)->setValue($agent->corps);
            $datasetSheet->getCellByColumnAndRow(8,$i)->setValue($agent->structure);
            $datasetSheet->getCellByColumnAndRow(9,$i)->setValue($agent->plan_formation);
            $i++;
        }

        
        return $spreadsheet;
    }


    public function buildStructure(Spreadsheet $spreadsheet)
    {
        $datasetSheet = new Worksheet($spreadsheet,'Structures');
        $spreadsheet->addSheet($datasetSheet, 0);
        
        $datasetSheet = $spreadsheet->getSheet(0);
        $datasetSheet->getCellByColumnAndRow(1,1)->setValue("Code");
        $datasetSheet->getCellByColumnAndRow(2,1)->setValue("Structures");

        $id_structure =  DB::table('type')->select('id')->where('wording','Structure')->first();
        $structures = Level::where('id_type',$id_structure->id)->get();
        $i = 2;
        foreach($structures as $structure)
        {
            $datasetSheet->getCellByColumnAndRow(1,$i)->setValue($structure->id);
            $datasetSheet->getCellByColumnAndRow(2,$i)->setValue($structure->wording);
            $i++;
        }

        
        return $spreadsheet;
    }


    public function buildCategorie(Spreadsheet $spreadsheet)
    {
        $datasetSheet = new Worksheet($spreadsheet,'Categories');
        $spreadsheet->addSheet($datasetSheet, 0);
        
        $datasetSheet = $spreadsheet->getSheet(0);
        $datasetSheet->getCellByColumnAndRow(1,1)->setValue("Code");
        $datasetSheet->getCellByColumnAndRow(2,1)->setValue("Categories");

        $id_cat =  DB::table('type')->select('id')->where('wording','Categorie')->first();
        $categories = Level::where('id_type',$id_cat->id)->get();
        $i = 2;
        foreach($categories as $categorie)
        {
            $datasetSheet->getCellByColumnAndRow(1,$i)->setValue($categorie->id);
            $datasetSheet->getCellByColumnAndRow(2,$i)->setValue($categorie->wording);
            $i++;
        }

        
        return $spreadsheet;
    }

    public function buildCorps(Spreadsheet $spreadsheet)
    {
        $datasetSheet = new Worksheet($spreadsheet,'Corps de la fonction publique');
        $spreadsheet->addSheet($datasetSheet, 0);
        
        $datasetSheet = $spreadsheet->getSheet(0);
        $datasetSheet->getCellByColumnAndRow(1,1)->setValue("Code");
        $datasetSheet->getCellByColumnAndRow(2,1)->setValue("Corps");

        $id_corps = DB::table('type')->select('id')->where('wording','Corps de la fonction publique')->first();
        $corps = Level::where('id_type',$id_corps->id)->get();
        $i = 2;
        foreach($corps as $cor)
        {
            $datasetSheet->getCellByColumnAndRow(1,$i)->setValue($cor->id);
            $datasetSheet->getCellByColumnAndRow(2,$i)->setValue($cor->wording);
            $i++;
        }

        
        return $spreadsheet;
    }

    public function buildStatuts(Spreadsheet $spreadsheet)
    {
        $datasetSheet = new Worksheet($spreadsheet,'Statuts');
        $spreadsheet->addSheet($datasetSheet, 0);
        
        $datasetSheet = $spreadsheet->getSheet(0);
        $datasetSheet->getCellByColumnAndRow(1,1)->setValue("Code");
        $datasetSheet->getCellByColumnAndRow(2,1)->setValue("Statuts");

        $id_status =  DB::table('type')->select('id')->where('wording','Statut')->first();
        $statuts = Level::where('id_type',$id_status->id)->get();
        $i = 2;
        foreach($statuts as $statut)
        {
            $datasetSheet->getCellByColumnAndRow(1,$i)->setValue($statut->id);
            $datasetSheet->getCellByColumnAndRow(2,$i)->setValue($statut->wording);
            $i++;
        }

        
        return $spreadsheet;
    }

    public function buildCountry(Spreadsheet $spreadsheet)
    {
        $datasetSheet = new Worksheet($spreadsheet,'Pays');
        $spreadsheet->addSheet($datasetSheet, 0);
        
        $datasetSheet = $spreadsheet->getSheet(0);
        $datasetSheet->getCellByColumnAndRow(1,1)->setValue("Code");
        $datasetSheet->getCellByColumnAndRow(2,1)->setValue("Pays");

        $countries =  DB::table('countries')->get();
        $i = 2;
        foreach($countries as $country)
        {
            $datasetSheet->getCellByColumnAndRow(1,$i)->setValue($country->id);
            $datasetSheet->getCellByColumnAndRow(2,$i)->setValue($country->name);
            $i++;
        }

        
        return $spreadsheet;
    }

    public function buildCity(Spreadsheet $spreadsheet)
    {
        $datasetSheet = new Worksheet($spreadsheet,'Villes');
        $spreadsheet->addSheet($datasetSheet, 0);
        
        $datasetSheet = $spreadsheet->getSheet(0);
        $datasetSheet->getCellByColumnAndRow(1,1)->setValue("Code");
        $datasetSheet->getCellByColumnAndRow(2,1)->setValue("Ville");

        $cities =  DB::table('cities')->get();
        $i = 2;
        foreach($cities as $citie)
        {
            $datasetSheet->getCellByColumnAndRow(1,$i)->setValue($citie->id);
            $datasetSheet->getCellByColumnAndRow(2,$i)->setValue($citie->name);
            $i++;
        }

        
        return $spreadsheet;
    }

    public function buildSexe(Spreadsheet $spreadsheet)
    {
        $datasetSheet = new Worksheet($spreadsheet,'Sexe');
        $spreadsheet->addSheet($datasetSheet, 0);
        
        $datasetSheet = $spreadsheet->getSheet(0);
        $datasetSheet->getCellByColumnAndRow(1,1)->setValue("Code");
        $datasetSheet->getCellByColumnAndRow(2,1)->setValue("Sexe");

        $id_sexe =  DB::table('type')->select('id')->where('wording','Sexe')->first();
        $sexes = Level::where('id_type',$id_sexe->id)->get();
        $i = 2;
        foreach($sexes as $sexe)
        {
            $datasetSheet->getCellByColumnAndRow(1,$i)->setValue($sexe->id);
            $datasetSheet->getCellByColumnAndRow(2,$i)->setValue($sexe->wording);
            $i++;
        }

        
        return $spreadsheet;
    }


    public function buildAnnee(Spreadsheet $spreadsheet)
    {
        $datasetSheet = new Worksheet($spreadsheet,'Années');
        $spreadsheet->addSheet($datasetSheet, 0);
        
        $datasetSheet = $spreadsheet->getSheet(0);
        $datasetSheet->getCellByColumnAndRow(1,1)->setValue("Code");
        $datasetSheet->getCellByColumnAndRow(2,1)->setValue("Année");

        
        $annees = DB::table('annee')->get();
        $i = 2;
        foreach($annees as $annee)
        {
            $datasetSheet->getCellByColumnAndRow(1,$i)->setValue($annee->id);
            $datasetSheet->getCellByColumnAndRow(2,$i)->setValue($annee->value);
            $i++;
        }

        
        return $spreadsheet;
    }

    public function genererExp(Request $request)
    {


        $spreadsheet = new Spreadsheet();
        $spreadsheet = $this->buildAncienAgent($spreadsheet);
        $spreadsheet = $this->buildCategorie($spreadsheet);
        $spreadsheet = $this->buildStructure($spreadsheet);
        $spreadsheet = $this->buildCorps($spreadsheet);
        $spreadsheet = $this->buildSexe($spreadsheet);
        $spreadsheet = $this->buildStatuts($spreadsheet);
        
        $agentsheet = $spreadsheet->getActiveSheet();
        $agentsheet->setTitle('Nouvel Agent');
        $agentsheet->getCellByColumnAndRow(1,1)->setValue("Matricule");
        $agentsheet->getCellByColumnAndRow(2,1)->setValue("Nom & Prenoms");
        $agentsheet->getCellByColumnAndRow(3,1)->setValue("Diplôme de base");
        $agentsheet->getCellByColumnAndRow(4,1)->setValue("SexeCode");
        $agentsheet->getCellByColumnAndRow(5,1)->setValue("Sexe");
        $agentsheet->getCellByColumnAndRow(6,1)->setValue("StatusCode");
        $agentsheet->getCellByColumnAndRow(7,1)->setValue("Status");
        $agentsheet->getCellByColumnAndRow(8,1)->setValue("CatégorieCode");
        $agentsheet->getCellByColumnAndRow(9,1)->setValue("Catégorie");
        $agentsheet->getCellByColumnAndRow(10,1)->setValue("CorpsCode");
        $agentsheet->getCellByColumnAndRow(11,1)->setValue("Corps de la fonction publique");
        $agentsheet->getCellByColumnAndRow(12,1)->setValue("StructureCode");
        $agentsheet->getCellByColumnAndRow(13,1)->setValue("Structure");
        $agentsheet->getCellByColumnAndRow(14,1)->setValue("Plan de Formation");

        $datasetname = "agents_formation";
       
        $spreadsheet->setActiveSheetIndex(0);

        $writer = new Xlsx($spreadsheet);
        $path = public_path();
        $path = $path."/template/$datasetname.xlsx";
        $writer->save($path);

        return redirect()->back()->with("examplaire","template/$datasetname.xlsx");
    }


    public function formImport()
    {
        $id_structure =  DB::table('type')->select('id')->where('wording','Structure')->first();
        $structures = Level::where('id_type',$id_structure->id)->get();
        return view('admin.agentFormation.formImport_agents',compact('structures'));
    }


    public function import(Request $request)
    {
        $rules = array('importfile' => 'mimes:xlsx,xls');
 
        $file = $request->file('importfile');//->getClientOriginalExtension();
        $extension = $file->getClientOriginalExtension();
        $allowed = ['xlsx','xls'];
        //dd($file->getClientOriginalExtension());
        $validator = Validator::make($request->all(), $rules);
        $linesWithError = [];
        if(!in_array($extension,$allowed))
        {
            return redirect()->back()->with('validation','error');
        }
        else
        {
            
            $spreadsheet = IOFactory::load($file);

            $numberOfSheet = $spreadsheet->getSheetCount();
            $agentsheet = $spreadsheet->getSheet(($numberOfSheet-1));

            $numberOfRow = $agentsheet->getHighestRow();
            
            // $highestColumn = $domainsheet->getHighestColumn(); // e.g 'F'
            // $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn); // e.g. 5


            //dd($numberOfRow);
            for($i = 2; $i <= $numberOfRow; $i++)
            {
                $currentMatricule = $agentsheet->getCellByColumnAndRow(1,$i)->getValue();
                $currentName = $agentsheet->getCellByColumnAndRow(2,$i)->getValue();
                $currentDiplome = $agentsheet->getCellByColumnAndRow(3,$i)->getValue();
                $sexeCode = $agentsheet->getCellByColumnAndRow(4,$i)->getValue();
                if(!is_int($sexeCode)) $sexeCode =  $agentsheet->getCellByColumnAndRow(4,$i)->getCalculatedValue();
                $statusCode =  $agentsheet->getCellByColumnAndRow(6,$i)->getValue();
                if(!is_int($statusCode)) $statusCode =  $agentsheet->getCellByColumnAndRow(6,$i)->getCalculatedValue();
                $cateCode = $agentsheet->getCellByColumnAndRow(8,$i)->getValue();
                if(!is_int($cateCode)) $cateCode =  $agentsheet->getCellByColumnAndRow(8,$i)->getCalculatedValue();
                $corpsCode = $agentsheet->getCellByColumnAndRow(10,$i)->getValue();
                if(!is_int($corpsCode)) $corpsCode =  $agentsheet->getCellByColumnAndRow(10,$i)->getCalculatedValue();
                $structureCode = $agentsheet->getCellByColumnAndRow(12,$i)->getValue();
                if(!is_int($structureCode)) $structureCode =  $agentsheet->getCellByColumnAndRow(12,$i)->getCalculatedValue();
                $plan = $agentsheet->getCellByColumnAndRow(14,$i)->getValue();
                
                if($plan instanceof RichText)
                {
                    $plan = $plan->getPlainText();
                }
                else
                {
                    $plan = (string)$plan;
                }
                
                
                /* Statut */ 
                if ($statusCode == null) {
                    return redirect()->back()->with('nomenclatureError','error');
                }
                //dd($statusCode,$sexeCode,$cateCode,$corpsCode,$structureCode);
                if($statusCode instanceof RichText)
                {
                    $statusCode = $statusCode->getPlainText();
                }
                else
                {
                    $statusCode = (string)$statusCode;
                }
                $status = Level::where('id',$statusCode)->first();
                if($status == null){
                    return redirect()->back()->with('nomenclatureError','error');
                }

                /* Categorie */ 

                if ($cateCode == null) {
                    return redirect()->back()->with('nomenclatureError','error');
                }
                if($cateCode instanceof RichText)
                {
                    $cateCode = $cateCode->getPlainText();
                }
                else
                {
                    $cateCode = (string)$cateCode;
                }
                $cat = Level::where('id',$cateCode)->first();

                /* Corps */
                if ($corpsCode == null) {
                    return redirect()->back()->with('nomenclatureError','error');
                }
                if($corpsCode instanceof RichText)
                {
                    $corpsCode = $corpsCode->getPlainText();
                }
                else
                {
                    $corpsCode = (string)$corpsCode;
                }
                // Formating
                $corps = Level::where('id',$corpsCode)->first();
                if($corps == null){
                    return redirect()->back()->with('nomenclatureError','error');
                }

                if ($structureCode == null) {
                    return redirect()->back()->with('nomenclatureError','error');
                }
                if($structureCode instanceof RichText)
                {
                    $structureCode = $structureCode->getPlainText();
                }
                else
                {
                    $structureCode = (string)$structureCode;
                }
                // Formating
                $struct = Level::where('id',$structureCode)->first();
                if($struct == null){
                    return redirect()->back()->with('nomenclatureError','error');
                }


                if($currentDiplome instanceof RichText)
                {
                    $currentDiplome = $currentDiplome->getPlainText();
                }
                else
                {
                    $currentDiplome = (string)$currentDiplome;
                }


                //Formating   
                if ($sexeCode == null) {
                    return redirect()->back()->with('nomenclatureError','error');
                }
                $sexe = Level::where('id',$sexeCode)->first();
                if($sexe == null){
                    return redirect()->back()->with('nomenclatureError','error');
                }
                

                if($currentName instanceof RichText)
                {
                    $currentName = $currentName->getPlainText();
                }
                else
                {
                    $currentName = (string)$currentName;
                }
                //Formating
                if($currentName == "null" || $currentName == null){
                    return redirect()->back()->with('nomenclatureError','error');
                }


                if($currentMatricule instanceof RichText)
                {
                    $currentMatricule = $currentMatricule->getPlainText();
                }
                else
                {
                    $currentMatricule = (string)$currentMatricule;
                }
                if($this->agentExist($currentMatricule))
                {   
                    $linesWithError[] = $i;
                }
                else
                {   
                    $agent = new AgentFormation;
                    $agent->matricule = $currentMatricule;
                    $agent->sexe = $sexe->wording;
                    $agent->nom_prenoms = $currentName;
                    $agent->diplome_base = $currentDiplome;
                    $agent->categorie = $cat->wording;
                    $agent->status = $status->wording;
                    $agent->corps = $corps->wording;
                    $agent->structure = $struct->wording;
                    $agent->plan_formation = $plan;
                    if($agent->save())
                    {
                        continue;
                    }
                    else
                    {
                        $linesWithError[] = $i;
                    }
                }
            }

            if($linesWithError == null || (count($linesWithError)==0))
            {
                return redirect()->back()->with('success','success');
            }
            else
            {
                $errorSpreadSheet = $this->copyRowsFromArray($linesWithError, $spreadsheet);

                $writer = new Xlsx($errorSpreadSheet);
                $path = public_path();
                $path = $path."/template/unsaved.xlsx";
                $writer->save($path);

                return redirect()->back()->with("path","template/unsaved.xlsx") 
                                        ->with('warning','warning');
            }

        }

    }

    public function agentExist($mat)
    {
        
        $matricule = trim(strtolower($mat));
        $agent = AgentFormation::where('matricule',$matricule)->first();
        if($agent == null)
        {
            return false;
        }

        return true;
    }

    public function copyRowsFromArray($rows, Spreadsheet $oldSpreadSheet)
    {
        $spreadsheet = new Spreadsheet();
        $numberOfSheet = $oldSpreadSheet->getSheetCount();
        $errorSheet = $spreadsheet->getActiveSheet();
        
        $oldDataSheet = $oldSpreadSheet->getSheet(($numberOfSheet-1));
        $highestColumn = $oldDataSheet->getHighestDataColumn(); // e.g 'F'
        $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn); // e.g. 5

        $numberOfRows = count($rows);
        $i = 0;
        for($row = 1; $row <= $numberOfRows; $row++)
        {
            for($j = 1; $j <= $highestColumnIndex; $j++)
            {
                $oldCellValue = $oldDataSheet->getCellByColumnAndRow($j,$rows[$i])->getValue();

                if($oldCellValue instanceof  RichText)
                {
                    $oldCellValue = $oldCellValue->getPlainText();
                }
                if(!is_string($oldCellValue) && $oldCellValue != null)
                {
                     $oldCellValue = (int)$oldCellValue;
                     if($oldCellValue == 0)
                     {
                        $oldCellValue = "";
                     }
                }
                $errorSheet->getCellByColumnAndRow($j,$row)->setValue($oldCellValue);
            }
            $i++;
        }
        
        return $spreadsheet;
    }




    public function sirFunction(Request $request)
    {
        $rules = array('importfile' => 'mimes:xlsx,xls');
 
        $file = $request->file('importfile');//->getClientOriginalExtension();
        $extension = $file->getClientOriginalExtension();
        $allowed = ['xlsx','xls'];
        //dd($file->getClientOriginalExtension());
        $validator = Validator::make($request->all(), $rules);
        $linesWithError = [];
        if(!in_array($extension,$allowed))
        {
            return redirect()->back()->with('validation','error');
        }
        else
        {
            
            $spreadsheet = IOFactory::load($file);

            $numberOfSheet = $spreadsheet->getSheetCount();
            $brut = $spreadsheet->getSheet(0);

            $numberOfRow = $brut->getHighestRow();
            //dd($brut);
            // $highestColumn = $domainsheet->getHighestColumn(); // e.g 'F'
            // $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn); // e.g. 5


            //dd($numberOfRow);
            $date_op = "";
            $date_val = "";
            $sir_libelle = "";
            $credit = "";
            $debit = "";
            $solde = "";
            $spread = new Spreadsheet();
            $agentsheet = $spread->getActiveSheet();
            $agents = AgentFormation::all();
            $datasetname = "Compte";
            $agentsheet->setTitle('2013');
            $col = 1;
            $row = 1;
            $currentMonth = 1;
            $currentYear = 2013;
            $currentDay = 1;
            $writeSolde = FALSE;
            $debitarray = [];
            $creditarray = [];
            $soldePrecedent =  0;
            $anotherDay = False;
            $anotherMonth = TRUE;
            $anotherYear = FALSE;
            $CalculSolde = FALSE;
            $TotalDebit = 0;
            $TotalCredit = 0;
            $interet = 0;
            $montant_interet = 0;
            $cst = 0.08;
            $ecart = 0;
            $base_calcule = 0;
            $ecart_base_calcule = 0;
            for($i = 2; $i <= $numberOfRow; $i++)
            {
                $date_op = $brut->getCellByColumnAndRow(1,$i)->getValue();
                $sir_libelle = $brut->getCellByColumnAndRow(2,$i)->getValue();
                $date_val = $brut->getCellByColumnAndRow(3,$i)->getValue();
                $debit = $brut->getCellByColumnAndRow(4,$i)->getValue();
                $credit =  $brut->getCellByColumnAndRow(5,$i)->getValue();
                $solde = $brut->getCellByColumnAndRow(6,$i)->getCalculatedValue();
                $next_date = $brut->getCellByColumnAndRow(3,$i+1)->getValue();
                if($date_op instanceof RichText)
                {
                    $date_op = $date_op->getPlainText();
                }
                else
                {
                    $date_op = (string)$date_op;
                }
                
                if ($date_op != null && $date_op != "") {
                    //$date_op = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($date_op);
                }
                
                //dd($date_op)
                
                
                /* Statut */ 
                if($sir_libelle instanceof RichText)
                {
                    $sir_libelle = $sir_libelle->getPlainText();
                }
                else
                {
                    $sir_libelle = (string)$sir_libelle;
                }

                if($date_val instanceof RichText)
                {
                    $date_val = $date_val->getPlainText();
                }
                else
                {
                    $date_val = (string)$date_val;
                }

                $date_val = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($date_val);

                // Next day retrieval
                if($next_date instanceof RichText)
                {
                    $next_date = $next_date->getPlainText();
                }
                else
                {
                    $next_date = (string)$next_date;
                }

                if($next_date != null) $next_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($next_date);



                if($debit instanceof RichText)
                {
                    $debit = $debit->getPlainText();
                }
                else
                {
                    $debit = (int)$debit;
                }
                $debitarray[] = $debit;

                if($credit instanceof RichText)
                {
                    $credit = $credit->getPlainText();
                }
                else
                {
                    $credit = (int)$credit;
                }
                $creditarray[] = $credit;

                if($solde instanceof RichText)
                {
                    $solde = $solde->getPlainText();
                }
                else
                {
                    $solde = (int)$solde;
                }

                if($currentYear != (int)$date_val->format('Y')){
                    $anotherYear = TRUE;
                    $anotherMonth = TRUE;
                    $anotherDay = TRUE;
                    $CalculSolde = FALSE;
                }else{
                    if($currentMonth !=  (int)$date_val->format('m')){
                        $CalculSolde = TRUE;
                        $anotherMonth = TRUE;
                        $anotherDay = TRUE;
                    }else{
                        if( $currentDay != (int)$date_val->format('d')){
                            $anotherDay = TRUE;
                            $CalculSolde = TRUE;
                        }else{
                            $anotherDay = FALSE;
                        }
                    }
                }
                
                

                if ($anotherYear) {
                    $datasetSheet = new Worksheet($spread,$date_val->format('Y'));
                    $spread->addSheet($datasetSheet, 0);
                    $agentsheet = $spread->getSheet(0);
                    $currentYear = (int)$date_val->format('Y');
                }

                $sDebit = 0;
                $sCredit = 0 ;
                if($anotherDay && $CalculSolde){
                    foreach ($debitarray as $key => $value) {
                        if($key == count($debitarray) - 1) break;
                        $sDebit += $value;
                    }
                    foreach ($creditarray as $key => $value) {
                        if($key == count($creditarray) - 1) break;
                        $sCredit += $value;
                    }
                    $debitarray = array($debitarray[count($debitarray)-1]);
                    $creditarray = array($creditarray[count($creditarray)-1]);

                    $soldePrecedent = $soldePrecedent + $sCredit - $sDebit;
                    $isDebit = ($soldePrecedent < 0) ? TRUE : FALSE;
                    ++$row;
                    if ($isDebit) {
                        $col = 4;
                        $agentsheet->getCellByColumnAndRow($col,$row)->setValue(-1 * $soldePrecedent);
                        $col = 6;
                        $agentsheet->getCellByColumnAndRow($col,$row)->setValue(-1 * $soldePrecedent);
                    }else{
                        $col = 5;
                        $agentsheet->getCellByColumnAndRow($col,$row)->setValue($soldePrecedent);
                        $col = 7;
                        $agentsheet->getCellByColumnAndRow($col,$row)->setValue($soldePrecedent);
                    }
                    $anotherDay = FALSE;
                }

                if ($anotherMonth) {
                    $row = $row + 5;
                    $agentsheet->getCellByColumnAndRow(2,$row)->setValue("Total");
                    $agentsheet->getCellByColumnAndRow(9,$row)->setValue($TotalDebit);
                    $agentsheet->getCellByColumnAndRow(9,$row)->setValue($TotalDebit);
                    $row = $row + 2;
                    $agentsheet->getCellByColumnAndRow(2,$row)->setValue("Intérêts débiteurs");
                    $interet = ($TotalDebit * $cst)/360;
                    $agentsheet->getCellByColumnAndRow(9,$row)->setValue($interet);
                    $row = $row + 2;
                    $agentsheet->getCellByColumnAndRow(2,$row)->setValue("Montant des intérêt débiteurs calculés par la banque");
                    $montant_interet = 3338340;
                    $agentsheet->getCellByColumnAndRow(9,$row)->setValue($montant_interet);
                    $row = $row + 2;
                    $agentsheet->getCellByColumnAndRow(2,$row)->setValue("Ecart");
                    $ecart = $interet - $montant_interet;
                    $agentsheet->getCellByColumnAndRow(9,$row)->setValue($ecart);
                    $row = $row + 2;
                    $agentsheet->getCellByColumnAndRow(2,$row)->setValue("Base de calcul Considérant le montant des Int débiteurs banque");
                    $base_calcule = (360*$montant_interet)/$cst;
                    $agentsheet->getCellByColumnAndRow(9,$row)->setValue($base_calcule);
                    $row = $row + 2;
                    $agentsheet->getCellByColumnAndRow(2,$row)->setValue("Ecart avec la base de calcul");
                    $ecart_base_calcule = $base_calcule - $TotalDebit;
                    $agentsheet->getCellByColumnAndRow(9,$row)->setValue($ecart_base_calcule);
                    $TotalCredit = $TotalDebit = 0;
                    if($i != 2) $row = $row + 10;
                    if($anotherYear == true) {
                        $anotherYear = FALSE;
                        $row = 1;
                    }
                    $col = 1;
                    
                    $agentsheet->mergeCellsByColumnAndRow($col,$row,++$col,$row,++$col,$row,++$col,$row,++$col,$row,++$col,$row); 
                    $agentsheet->getCellByColumnAndRow($col-5,$row)->setValue($date_val->format('M'))->getStyle()->getFont()->setBold(true);
                    ++$row;
                    $col = 1;
                    $agentsheet->getCellByColumnAndRow($col,$row)->setValue("Date Op")->getStyle()->getFont()->setBold(true);
                    $agentsheet->getCellByColumnAndRow(++$col,$row)->setValue("Libellé")->getStyle()->getFont()->setBold(true);
                    $agentsheet->getCellByColumnAndRow(++$col,$row)->setValue("Date Val")->getStyle()->getFont()->setBold(true);
                    $agentsheet->mergeCellsByColumnAndRow(++$col,$row,$col++,$row); 
                    $agentsheet->getCellByColumnAndRow($col-1,$row)->setValue("Capitaux")->getStyle()->getFont()->setBold(true);
                    $agentsheet->mergeCellsByColumnAndRow(++$col,$row,++$col,$row); 
                    $agentsheet->getCellByColumnAndRow($col-1,$row)->setValue("Solde")->getStyle()->getFont()->setBold(true);
                    $agentsheet->getCellByColumnAndRow(++$col,$row)->setValue("NBj")->getStyle()->getFont()->setBold(true);
                    $agentsheet->mergeCellsByColumnAndRow(++$col,$row,++$col,$row); 
                    $agentsheet->getCellByColumnAndRow($col-1,$row)->setValue("Nombre")->getStyle()->getFont()->setBold(true);
                    ++$row;
                    $col = 4;
                    $agentsheet->getCellByColumnAndRow($col,$row)->setValue("Debit")->getStyle()->getFont()->setBold(true);
                    $agentsheet->getCellByColumnAndRow(++$col,$row)->setValue("Credit")->getStyle()->getFont()->setBold(true);
                    $agentsheet->getCellByColumnAndRow(++$col,$row)->setValue("Debit")->getStyle()->getFont()->setBold(true);
                    $agentsheet->getCellByColumnAndRow(++$col,$row)->setValue("Credit")->getStyle()->getFont()->setBold(true);
                    $col = $col + 2;
                    $agentsheet->getCellByColumnAndRow($col,$row)->setValue("Debit")->getStyle()->getFont()->setBold(true);
                    $agentsheet->getCellByColumnAndRow(++$col,$row)->setValue("Credit")->getStyle()->getFont()->setBold(true);

                    $currentMonth = (int)$date_val->format('m');
                    $anotherMonth = FALSE;
                }
                

                ++$row;             
                $col = 1;
                $agentsheet->getCellByColumnAndRow($col,$row)->setValue($date_op);//$date_op->format('d/m/Y')
                $agentsheet->getCellByColumnAndRow(++$col,$row)->setValue($sir_libelle);
                $agentsheet->getCellByColumnAndRow(++$col,$row)->setValue($date_val->format('d/m/Y'));
                $agentsheet->getCellByColumnAndRow(++$col,$row)->setValue(($debit != 0)? $debit : "");
                $agentsheet->getCellByColumnAndRow(++$col,$row)->setValue(($credit != 0)? $credit : "");
                $col = $col + 2;
                
                if($next_date != null) {
                    $diff_Date = $next_date->diff($date_val)->format("%a");
                    $agentsheet->getCellByColumnAndRow(++$col,$row)->setValue($diff_Date);
                    $agentsheet->getCellByColumnAndRow(++$col,$row)->setValue((($debit*$diff_Date)!=0)?($debit*$diff_Date) : "");
                    $agentsheet->getCellByColumnAndRow(++$col,$row)->setValue((($credit*$diff_Date)!=0)?($credit*$diff_Date) : "");
                    $TotalDebit += $debit*$diff_Date;
                    $TotalCredit += $credit*$diff_Date;
                }


                $currentDay = (int)$date_val->format('d');
                
                
                //if($i == 20) break;
                if($i == $numberOfRow ){
                    

                }

            }



        $spread->setActiveSheetIndex(0);

        $writer = new Xlsx($spread);
        $path = public_path();
        $path = $path."/template/$datasetname.xlsx";
        $writer->save($path);

        return redirect()->back()->with("path","template/$datasetname.xlsx");

        }
    }

    
}