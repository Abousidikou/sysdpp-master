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

   /*  public function comb(){
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
        //$indics  =  Indicators::whereIn('id', [225,226,251,252])->get();
        $indic  =  Indicators::where('id',225)->first();

        /******************* Hash order : "indic-sexe-statuts-categorie-structure-corps" */
        //$i = 0; // 10
        // relever le point de départ
        /* $timestart=microtime(true);
        foreach ($corps as $cor) {
            foreach ($structures as  $structure) {
                foreach ($categories as $categorie) {
                    foreach ($statuts as $statut) {
                        foreach ($sexes as $sexe) {
                            $agg = new Aggregat();
                            $h = $indic->wording.$sexe->wording.$statut->wording.$categorie->wording.$structure->wording.$cor->wording;
                            $agg->hash_value = $h;
                            $agg->indicateurcode = base64_encode($h);
                            $agg->indicateurname = $indic->wording;
                            $agg->sexecode = $sexe->id;
                            $agg->sexename = $sexe->wording;
                            $agg->statuscode = $statut->id;
                            $agg->statusname = $statut->wording;
                            $agg->categoriecode = $categorie->id;
                            $agg->categoriename = $categorie->wording;
                            $agg->structurecode = $structure->id;
                            $agg->structurename = $structure->wording;
                            $agg->corpscode = $cor->id;
                            $agg->corpsname = $cor->wording;
                            $agg->unit = "Nombre";
                            $agg->save();
                            
                        }
                        dd();
                    }
                }
            }
            //Fin du code PHP
            $timeend=microtime(true);
            $time=$timeend-$timestart;
            //Afficher le temps d'éxecution
            $execution_time = number_format($time, 3);
            if($i == 1) dd("Debut du script: ".date("H:i:s", $timestart),"<br>Fin du script: ".date("H:i:s", $timeend),"<br>Script execute en " . $execution_time . " sec");
            $i ++;
        } */
        
        /* $i = 0;
        foreach ($indics as $indic) {
            $i ++;
            foreach ($sexes as $sexe){
                
                foreach ($statuts as $statut) {
                    
                    foreach ($categories as $categorie) {
                        
                        foreach ($structures as  $structure) {
        
                            foreach ($corps as $cor) {
    
                                $withcorps = new Aggregat();
                                $h = $indic->id.$sexe->id.$statut->id.$categorie->id.$structure->id.$cor->id;
                                $withcorps->hash_value = $h;
                                $withcorps->indicateurcode = $indic->id;
                                $withcorps->indicateurname = $indic->wording;
                                $withcorps->sexecode = $sexe->id;
                                $withcorps->sexename = $sexe->wording;
                                $withcorps->statuscode = $statut->id;
                                $withcorps->statusname = $statut->wording;
                                $withcorps->categoriecode = $categorie->id;
                                $withcorps->categoriename = $categorie->wording;
                                $withcorps->structurecode = $structure->id;
                                $withcorps->structurename = $structure->wording;
                                $withcorps->corpscode = $cor->id;
                                $withcorps->corpsname = $cor->wording;
                                $withcorps->unit = "Nombre";
                                $withcorps->save();
                                dd();
                            }

                            $withstruct = new Aggregat();
                            $h = $indic->id.$sexe->id.$statut->id.$categorie->id.$structure->id;
                            $withstruct->hash_value = $h;
                            $withstruct->indicateurcode = $indic->id;
                            $withstruct->indicateurname = $indic->wording;
                            $withstruct->sexecode = $sexe->id;
                            $withstruct->sexename = $sexe->wording;
                            $withstruct->statuscode = $statut->id;
                            $withstruct->statusname = $statut->wording;
                            $withstruct->categoriecode = $categorie->id;
                            $withstruct->categoriename = $categorie->wording;
                            $withstruct->structurecode = $structure->id;
                            $withstruct->structurename = $structure->wording;
                            $withstruct->corpscode = null;
                            $withstruct->corpsname = null;
                            $withstruct->unit = "Nombre";
                            $withstruct->save();
                        }


                        $withcat = new Aggregat();
                        $h = $indic->id.$sexe->id.$statut->id.$categorie->id;
                        $withcat->hash_value = $h;
                        $withcat->indicateurcode = $indic->id;
                        $withcat->indicateurname = $indic->wording;
                        $withcat->sexecode = $sexe->id;
                        $withcat->sexename = $sexe->wording;
                        $withcat->statuscode = $statut->id;
                        $withcat->statusname = $statut->wording;
                        $withcat->categoriecode = $categorie->id;
                        $withcat->categoriename = $categorie->wording;
                        $withcat->structurecode = null;
                        $withcat->structurename = null;
                        $withcat->corpscode = null;
                        $withcat->corpsname = null;
                        
                        $withcat->unit = "Nombre";
                        $withcat->save();
                    }


                    $withstatus = new Aggregat();
                    $h = $indic->id.$sexe->id.$statut->id;
                    $withstatus->hash_value = $h;
                    $withstatus->indicateurcode = $indic->id;
                    $withstatus->indicateurname = $indic->wording;
                    $withstatus->sexecode = $sexe->id;
                    $withstatus->sexename = $sexe->wording;
                    $withstatus->statuscode = $statut->id;
                    $withstatus->statusname = $statut->wording;
                    $withstatus->categoriecode = null;
                    $withstatus->categoriename = null;
                    $withstatus->corpscode = null;
                    $withstatus->corpsname = null;
                    $withstatus->structurecode = null;
                    $withstatus->structurename = null;
                    $withstatus->unit = "Nombre";
                    $withstatus->save();
        
                }

                $withsexe = new Aggregat();
                $h = $indic->id.$sexe->id;
                $withsexe->hash_value = $h;
                $withsexe->indicateurcode = $indic->id;
                $withsexe->indicateurname = $indic->wording;
                $withsexe->sexecode = $sexe->id;
                $withsexe->sexename = $sexe->wording;
                $withsexe->corpscode = null;
                $withsexe->corpsname = null;
                $withsexe->structurecode = null;
                $withsexe->structurename = null;
                $withsexe->statuscode = null;
                $withsexe->statusname = null;
                $withsexe->categoriecode = null;
                $withsexe->categoriename = null;
                $withsexe->unit = "Nombre";
                $withsexe->save();

            }
            $indicsOnly = new Aggregat();
            $h = $indic->id;
            $indicsOnly->hash_value = $h;
            $indicsOnly->indicateurcode = $indic->id;
            $indicsOnly->indicateurname = $indic->wording;
            $indicsOnly->sexecode = null;
            $indicsOnly->sexename = null;
            $indicsOnly->corpscode = null;
            $indicsOnly->corpsname = null;
            $indicsOnly->structurecode = null;
            $indicsOnly->structurename = null;
            $indicsOnly->statuscode = null;
            $indicsOnly->statusname = null;
            $indicsOnly->categoriecode = null;
            $indicsOnly->categoriename = null;
            $indicsOnly->unit = "Nombre";
            $indicsOnly->save();
            dd($i);
            break;
        } */
    /*    
        return false;
    } */

    public function index()
    {
        //$combinaison = $this->comb();                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  
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
        $aggV = DB::table('mise_en_stages')->select('annee_stage')->distinct('annee_stage')->get();
        return view('admin.aggregat.index',compact('aggV','sexes','statuts','categories','corps','structures','indics'));
        
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
