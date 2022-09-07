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


    public function genererExp(Request $request)
    {
        $spreadsheet = new Spreadsheet();
        $agentsheet = $spreadsheet->getActiveSheet();
        $datasetname = "agents";
        $agentsheet->getCellByColumnAndRow(1,1)->setValue("Matricule");
        $agentsheet->getCellByColumnAndRow(2,1)->setValue("Nom & Prenoms");
        $agentsheet->getCellByColumnAndRow(3,1)->setValue("Diplôme de base");
        $agentsheet->getCellByColumnAndRow(4,1)->setValue("Sexe");
        $agentsheet->getCellByColumnAndRow(5,1)->setValue("Status");
        $agentsheet->getCellByColumnAndRow(6,1)->setValue("Catégorie");
        $agentsheet->getCellByColumnAndRow(7,1)->setValue("Corps de la fonction publique");
        $agentsheet->getCellByColumnAndRow(8,1)->setValue("Structure");
        $agentsheet->getCellByColumnAndRow(9,1)->setValue("Plan de Formation");

       
        $spreadsheet->setActiveSheetIndex(0);

        $writer = new Xlsx($spreadsheet);
        $path = public_path();
        $path = $path."/template/$datasetname.xlsx";
        $writer->save($path);

        return redirect()->back()->with("examplaire","template/$datasetname.xlsx");
    }


    public function formImport()
    {
        return view('admin.agentFormation.formImport_agents');
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

            $agentsheet = $spreadsheet->getSheet(0);

            $numberOfRow = $agentsheet->getHighestRow();
  
            // $highestColumn = $domainsheet->getHighestColumn(); // e.g 'F'
            // $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn); // e.g. 5



            for($i = 2; $i <= $numberOfRow; $i++)
            {
                $currentMatricule = $agentsheet->getCellByColumnAndRow(1,$i)->getValue();
                $currentName = $agentsheet->getCellByColumnAndRow(2,$i)->getValue();
                $currentSexe = $agentsheet->getCellByColumnAndRow(4,$i)->getValue();
                $currentDiplome = $agentsheet->getCellByColumnAndRow(3,$i)->getValue();
                $status =  $agentsheet->getCellByColumnAndRow(5,$i)->getValue();
                $cate = $agentsheet->getCellByColumnAndRow(6,$i)->getValue();
                $corps = $agentsheet->getCellByColumnAndRow(7,$i)->getValue();
                $structure = $agentsheet->getCellByColumnAndRow(8,$i)->getValue();
                $plan = $agentsheet->getCellByColumnAndRow(9,$i)->getValue();

                if($plan instanceof RichText)
                {
                    $plan = $plan->getPlainText();
                }
                else
                {
                    $plan = (string)$plan;
                }

                if($status instanceof RichText)
                {
                    $status = $status->getPlainText();
                }
                else
                {
                    $status = (string)$status;
                }


                if($cate instanceof RichText)
                {
                    $cate = $cate->getPlainText();
                }
                else
                {
                    $cate = (string)$cate;
                }


                if($corps instanceof RichText)
                {
                    $corps = $corps->getPlainText();
                }
                else
                {
                    $corps = (string)$corps;
                }


                if($structure instanceof RichText)
                {
                    $structure = $structure->getPlainText();
                }
                else
                {
                    $structure = (string)$structure;
                }

                if($currentDiplome instanceof RichText)
                {
                    $currentDiplome = $currentDiplome->getPlainText();
                }
                else
                {
                    $currentDiplome = (string)$currentDiplome;
                }


                if($currentSexe instanceof RichText)
                {
                    $currentSexe = $currentSexe->getPlainText();
                }
                else
                {
                    $currentSexe = (string)$currentSexe;
                }

                if($currentName instanceof RichText)
                {
                    $currentName = $currentName->getPlainText();
                }
                else
                {
                    $currentName = (string)$currentName;
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
                    $agent->sexe = $currentSexe;
                    $agent->nom_prenoms = $currentName;
                    $agent->diplome_base = $currentDiplome;
                    $agent->categorie = $cate;
                    $agent->status = $status;
                    $agent->corps = $corps;
                    $agent->structure = $structure;
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



}
