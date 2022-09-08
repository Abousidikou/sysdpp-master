<?php
namespace App\Http\Controllers;
ini_set('max_execution_time', 3600);
use App\Http\Controllers\Infos\InfosController;
use Illuminate\Http\Request;
use App\Models\AgentFormation;
use Illuminate\Support\Facades\DB;
use App\Models\Level;
use App\Models\Type;
use App\Models\AggregatValue;
use App\Models\Indicators;
use App\Models\MiseEnStage;
use App\Models\RetourDeStage;
use Illuminate\Support\Facades\Hash;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class AggregatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 
        $id_status =  DB::table('type')->select('id')->where('wording','Statut')->first();
        $statuts = Level::where('id_type',$id_status->id)->get();
        $id_cat =  DB::table('type')->select('id')->where('wording','Categorie')->first();
        $categories = Level::where('id_type',$id_cat->id)->get();
        $id_corps = DB::table('type')->select('id')->where('wording','Corps de la fonction publique')->first();
        $corps = Level::where('id_type',$id_corps->id)->get();
        $id_structure =  DB::table('type')->select('id')->where('wording','Structure')->first();
        $structures = Level::where('id_type',$id_structure->id)->get();
        $id_sexe =  DB::table('type')->select('id')->where('wording','Sexe')->first();
        $sexes = Level::where('id_type',$id_sexe->id)->get();
        $indics  =  Indicators::whereIn('id', [225,226,251,252])->get();

        $ann = collect();
        $aggV = DB::table('mise_en_stages')->select('annee_stage')->distinct('annee_stage')->get();
        $aggA = DB::table('retour_de_stages')->select('annee_rs')->distinct('annee_rs')->get();  
        foreach ($aggV as $val) {
            $ann[] = $val->annee_stage;
        }   
        foreach ($aggA as $val) {
            $ann[] = $val->annee_rs;
        } 
        return view('admin.aggregat.index',compact('ann','sexes','statuts','categories','corps','structures','indics'));
        
    }

    public function export(Request $request)
    {
        $valueExist = AggregatValue::first();
        if($request->indicators == null || $request->indicators == "null" || $request->annee == null || $request->annee == "null" || $valueExist == null)
        {
            return redirect()->back()->with('error','error');
        }

        /*******************        recuperation des options de generation  ****************************/
        //$levelstf = $request->all();

        $statuts = $request->statut_id;
        $id_status =  DB::table('type')->where('wording','Statut')->first();
        if($statuts == null) $statuts = array(Level::where('id_type',$id_status->id)->where('wording','null')->first()->id);
        if ($statuts[0] == "null") array_shift($statuts);

        $structure = $request->structure_id;
        $id_structure =  DB::table('type')->where('wording','Structure')->first();
        if($structure == null) $structure = array(Level::where('id_type',$id_structure->id)->where('wording',"null")->first()->id);
        if ($structure[0] == "null") array_shift($structure);
        
        $categorie = $request->categorie_id;
        $id_cat =  DB::table('type')->where('wording','Categorie')->first();
        if($categorie == null) $categorie = array(Level::where('id_type',$id_cat->id)->where('wording',"null")->first()->id);
        if ($categorie[0] == "null") array_shift($categorie);

        $corps = $request->corps_id;
        $id_corps = DB::table('type')->where('wording','Corps de la fonction publique')->first();
        if($corps == null) $corps = array(Level::where('id_type',$id_corps->id)->where('wording',"null")->first()->id);
        if ($corps[0] == "null") array_shift($corps);

        $sexe = $request->sexe_id;
        $id_sexe =  DB::table('type')->where('wording','Sexe')->first();
        if($sexe == null) $sexe = array(Level::where('id_type',$id_sexe->id)->where('wording',"null")->first()->id);
        if ($sexe[0] == "null") array_shift($sexe);

        $indic = $request->indicators;
        $indicator = Indicators::where('id',$indic)->first();
        $annee = $request->annee;
        
       
        /*************************       preparation des parametres a envoyer aux fonction builts   ************************* */
        $indicatorsArray = array($indic);
        $types = array($id_sexe->id,$id_corps->id,$id_structure->id,$id_cat->id,$id_status->id);
        
        // Preparation du fichier excell
        $infoController = new InfosController();
        $spreadsheet = new Spreadsheet();
        $datasetname = "info_exporte";
        

        //We first build dataset worksheet;
        $spreadsheet = $infoController->buildDatasetSheet($indicatorsArray, $datasetname, $types, $spreadsheet);
        $spreadsheet = $infoController->buildIndicatorsSheet($indicatorsArray, $spreadsheet);
        $spreadsheet = $infoController->buildTypesSheet($types, $spreadsheet);

        // Building de la feuille Donnees
        $dataSheet = $spreadsheet->getActiveSheet();
        $dataSheet->setTitle("Donnees");
        
        $dataSheet->getCellByColumnAndRow(1,1)->setValue("Indicateurs");
        $dataSheet->getCellByColumnAndRow(2,1)->setValue("Indicateursname");
        
        $lHIndex = 3;
        foreach($types as $type)
        {
            $typeWording = Type::find($type)->wording;
            $dataSheet->getCellByColumnAndRow($lHIndex,1)->setValue($typeWording);
            $dataSheet->getCellByColumnAndRow($lHIndex+1,1)->setValue($typeWording."name");
            $lHIndex +=2;
        }
        $dataSheet->getCellByColumnAndRow($lHIndex,1)->setValue("Unit");
        $lHIndex +=1;
        $annee = array_reverse($annee);
        foreach ($annee as $ann) {
            $dataSheet->getCellByColumnAndRow($lHIndex,1)->setValue($ann);
            $lHIndex +=1;
        }
        
        $lVIndex = 2;
        $lHIndex = 1;
        foreach ($sexe as $se) {
            foreach ($statuts as $st) {
                foreach ($categorie as $cat) {
                    foreach ($structure as $struct) {
                        foreach ($corps as $cor) {
                            $w_sexe = Level::where('id',$se)->first()->wording;
                            $w_statut = Level::where('id',$st)->first()->wording;
                            $w_cat = Level::where('id',$cat)->first()->wording;
                            $w_struct = Level::where('id',$struct)->first()->wording;
                            $w_cor = Level::where('id',$cor)->first()->wording;
                            $h = $indicator->wording.$w_sexe.$w_statut.$w_cat.$w_struct.$w_cor;
                            $aggregat = AggregatValue::where('hash_value',md5($h))->first();
                            //dd($h,base64_encode($h),$aggregat);
                            if ($aggregat == null) {
                                $dataSheet->getCellByColumnAndRow($lHIndex++,$lVIndex)->setValue($indic);
                                $dataSheet->getCellByColumnAndRow($lHIndex++,$lVIndex)->setValue($indicator->wording);
                                $dataSheet->getCellByColumnAndRow($lHIndex++,$lVIndex)->setValue($se);
                                $dataSheet->getCellByColumnAndRow($lHIndex++,$lVIndex)->setValue($w_sexe);
                                $dataSheet->getCellByColumnAndRow($lHIndex++,$lVIndex)->setValue($cor);
                                $dataSheet->getCellByColumnAndRow($lHIndex++,$lVIndex)->setValue($w_cor);
                                $dataSheet->getCellByColumnAndRow($lHIndex++,$lVIndex)->setValue($struct);
                                $dataSheet->getCellByColumnAndRow($lHIndex++,$lVIndex)->setValue($w_struct);
                                $dataSheet->getCellByColumnAndRow($lHIndex++,$lVIndex)->setValue($cat);
                                $dataSheet->getCellByColumnAndRow($lHIndex++,$lVIndex)->setValue($w_cat);
                                $dataSheet->getCellByColumnAndRow($lHIndex++,$lVIndex)->setValue($st);
                                $dataSheet->getCellByColumnAndRow($lHIndex++,$lVIndex)->setValue($w_statut);
                                $dataSheet->getCellByColumnAndRow($lHIndex++,$lVIndex)->setValue("Nombre");
                                foreach ($annee as $ann) {
                                        $dataSheet->getCellByColumnAndRow($lHIndex++,$lVIndex)->setValue(0);
                                }
                                continue;
                            }

                            //$agg = $aggregat[0];
                            //dd($aggregat->where('annee',"2017")->keys()[0]);
                            $dataSheet->getCellByColumnAndRow($lHIndex++,$lVIndex)->setValue($indic);
                            $dataSheet->getCellByColumnAndRow($lHIndex++,$lVIndex)->setValue($indicator->wording);
                            $dataSheet->getCellByColumnAndRow($lHIndex++,$lVIndex)->setValue($se);
                            $dataSheet->getCellByColumnAndRow($lHIndex++,$lVIndex)->setValue($w_sexe);
                            $dataSheet->getCellByColumnAndRow($lHIndex++,$lVIndex)->setValue($cor);
                            $dataSheet->getCellByColumnAndRow($lHIndex++,$lVIndex)->setValue($w_cor);
                            $dataSheet->getCellByColumnAndRow($lHIndex++,$lVIndex)->setValue($struct);
                            $dataSheet->getCellByColumnAndRow($lHIndex++,$lVIndex)->setValue($w_struct);
                            $dataSheet->getCellByColumnAndRow($lHIndex++,$lVIndex)->setValue($cat);
                            $dataSheet->getCellByColumnAndRow($lHIndex++,$lVIndex)->setValue($w_cat);
                            $dataSheet->getCellByColumnAndRow($lHIndex++,$lVIndex)->setValue($st);
                            $dataSheet->getCellByColumnAndRow($lHIndex++,$lVIndex)->setValue($w_statut);
                            $dataSheet->getCellByColumnAndRow($lHIndex++,$lVIndex)->setValue("Nombre");
                            foreach ($annee as $ann) {
                                $dataSheet->getCellByColumnAndRow($lHIndex++,$lVIndex)->setValue($aggregat->value_statistic);
                            }

                            $lHIndex = 1;
                            $lVIndex +=1;
                            
                        }
                    }
                }
            }
        }

        //$aggVal = AggregatValue::where('hash_value',$h)->first();
        $spreadsheet = $infoController->buildDataSheet($indicatorsArray, $types, $spreadsheet);

        $spreadsheet->setActiveSheetIndex(0);

        $writer = new Xlsx($spreadsheet);
        $path = public_path();
        $path = $path."/template/$datasetname.xlsx";
        $writer->save($path);
        return redirect()->back()->with("path","template/$datasetname.xlsx");
        

        
    }


    public function genererAggregat(Request $request){
        $indics = $request->indicator;
        $annees = $request->annee;

        $isIndicAll = in_array('-1',$indics);
        $isAnneeAll = in_array('-1',$annees);

        DB::delete('delete from aggregat_inputs');
        DB::delete('delete from aggregat_start');

        if ($isIndicAll && $isAnneeAll) {
            $indics  =  Indicators::whereIn('id', [225,226,251,252])->get(); 
            $ann = collect();
            $aggV = DB::table('mise_en_stages')->select('annee_stage')->distinct('annee_stage')->get();
            $aggA = DB::table('retour_de_stages')->select('annee_rs')->distinct('annee_rs')->get();  
            foreach ($aggV as $val) {
                $ann[] = $val->annee_stage;
            }   
            foreach ($aggA as $val) {
                $ann[] = $val->annee_rs;
            } 
            foreach ($indics as $indic) {
                foreach ($ann as $an) {
                    DB::insert('insert into aggregat_inputs (indic,annee) values (?,?)', [$indic->id,$an]);
                }
            }
            
        }elseif ($isIndicAll && !$isAnneeAll) {
            foreach ($annees as $ann) {
                DB::insert('insert into aggregat_inputs (indic,annee) values (?,?)', ["-1",$ann]);
            }
        }elseif (!$isIndicAll && $isAnneeAll) {
            foreach ($indics as $indic) {
                DB::insert('insert into aggregat_inputs (indic,annee) values (?,?)', [$indic,"-1"]);
            }
        }else {
            foreach ($indics as $indic) {
                foreach ($annees as $ann) {
                    DB::insert('insert into aggregat_inputs (indic,annee) values (?,?)', [$indic,$ann]);
                }
            }
        }

        $start  = DB::insert('insert into aggregat_start (start) values("5")');
        if($start){
            if($start)
            {
                return redirect()->back()->with('aggregat_insert_success','success');
            }
            else
            {
                return redirect()->back()->with('aggregat_insert_error','error');
            }
        }
    }
    
}
