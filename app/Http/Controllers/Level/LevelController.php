<?php

namespace App\Http\Controllers\Level;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MiscController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Level;
use App\Models\Type;
use App\Models\Indicators;
use App\Models\Data;
use App\Models\SubDomain;
use App\Models\Domain;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\RichText\RichText;

class LevelController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        $levels = Level::all();
        foreach($levels as $level)
        {
            $level->type = $level->type->wording;

            $numberOfIndicators = $level->indicators()->count();
            if($numberOfIndicators == 0)
            {
                $level->indicator = "Aucun indicateur";
                $level->multiindi = false;
            }
            else if($numberOfIndicators == 1)
            {   
                $level->indicator = $level->indicators()->first()->wording;
                $level->multiindi = false;
            }
            else
            {
                $level->indicator = $level->indicators()->first()->wording;
                $level->multiindi = true;
            }
                        
        }

        return view('admin.level.levels', compact('levels'));
    }


    public function showMoreIndicator(Request $request)
    {
        if($request->ajax())
        {
            $levelId = $request->levelId;
            $indicators = Level::find($levelId)->indicators()->get();
            
            $levelWording = Level::where('id',$levelId)->first()->wording;
            $builtList = "<ol>";
            foreach($indicators as $indicator)
            {
                $builtList .= "<li>$indicator->wording</li>";
            }

            $builtList .= "</ol>";

            return json_encode(array('title' => $levelWording, 'builtList' => $builtList));
        }
    }


    public function form()
    {
        $indicatorsOptions = "";
        $indicators = [];
        $indicators = Indicators::all();

        $subdomains = SubDomain::all();
        $subdomainsOptions = "";

        foreach($subdomains as $subdomain)
        {
            $subdomainsOptions .= "<option value=$subdomain->id>$subdomain->wording</option>";
        }

        $domains = Domain::all();
        $domainsOptions = "";

        foreach($domains as $domain)
        {
            $domainsOptions .= "<option value=$domain->id>$domain->wording</option>";
        }

        foreach($indicators as $indicator)
        {
            $indicatorsOptions .= "<option value=$indicator->id>$indicator->wording</option>";
        }

        $typesOptions = "";

        $types = Type::all(); 
        foreach($types as $type)
        {
            $typesOptions .= "<option value=$type->id>$type->wording</option>";
        }

        return view('admin.level.new_level',compact('types','typesOptions','indicatorsOptions','domainsOptions','subdomainsOptions'));
    }

    public function formImportType()
    {
        return view('admin.level.import_type_file'); 
    }

    public function formImport()
    {
        return view('admin.level.import_level_file');
    }

    public function create(Request $request)
    { 
        $wording = $request->wording;
        $indicators = $request->input('indicators');
        $type = $request->type;
        $selectedChk = MiscController::chkArrayOfMultiSelect($indicators);
        
        $rules = [
            'wording' => 'unique:levelofdisintegration|required|string',
            // 'indicators' => 'required',
            'type' => 'required|integer',
        ];

        $validator = Validator::make($request->all(),$rules);

        if($validator->fails() || $selectedChk==null)
        {
            return redirect()->back()->with('validation','error');
        }
        else
        {
            $level = new Level;
            $level->wording = $wording;
            $level->id_type = $type;

            if($level->save())
            {
                if($selectedChk!=null)
                {
                    $level->indicators()->attach($selectedChk);
                }
                 
                return redirect()->back()->with('success','success');
            }
            else   
            {
                return redirect()->back()->with('error','error');
            }
        }
    } 

    public function createType(Request $request)
    {
        $wording = $request->wording;
        
        $rules = [
            'wording' => 'required|unique:type|string',
        ];

        $validator = Validator::make($request->all(),$rules);

        if($validator->fails())
        {
            return redirect()->back()->with('tvalidation','error');
        }
        else
        {
            $type = new Type;
            $type->wording = $wording;

            if($type->save())
            {
                return redirect()->back()->with('tsuccess','success');
            }
            else   
            {
                return redirect()->back()->with('terror','error');
            }
        }
    }


    public function delete($id)
    {
        $level_id = $id;

        $infos = Level::find($level_id)->data()->get(); 
        $indicators = Level::find($level_id)->indicators()->get();

        if(!$infos->isEmpty() && !$indicators->isEmpty())
        {
            $levelIsDeleted = Level::find($level_id)->delete();
            if($levelIsDeleted)
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

    public function deleteType($id)
    {
        $type_id = $id;

        $levels = Level::where('id_type',$type_id)->get();

        if($levels->isEmpty())
        {
            $typeIsDeleted = Type::find($type_id)->delete();
            if($typeIsDeleted)
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

 
    public function prepareUpdate($id)
    {
        $level = Level::where('id',$id)->first();
        $indicatorsId = [];
        // dd($level->indicators);

        $indicatorsOptions = "";
        foreach($level->indicators as $ind) {
            $indicatorsOptions .= "<option value=$ind->id selected>$ind->wording</option>";
            $indicatorsId[] = $ind->id;
        }
        $indicators = Indicators::whereNotIn('id', $indicatorsId)->get();
        
        foreach($indicators as $indicator)
        {
            $indicatorsOptions .= "<option value=$indicator->id>$indicator->wording</option>";
        }


        
        // $subdomains = SubDomain::all();
        // $subdomainsOptions = "";

        // foreach($subdomains as $subdomain)
        // {
        //     $subdomainsOptions .= "<option value=$subdomain->id>$subdomain->wording</option>";
        // }

        // $domains = Domain::all();
        // $domainsOptions = "";

        // foreach($domains as $domain)
        // {
        //     $domainsOptions .= "<option value=$domain->id>$domain->wording</option>";
        // }


        $typesOptions = "";
        $typeId = $level->type->id;
        $typeWording = $level->type->wording;
        $typesOptions .= "<option value=$typeId selected>$typeWording</option>";

        $types = Type::where('id','!=',$typeId)->get();
        foreach($types as $type)
        {
            $typesOptions .= "<option value=$type->id>$type->wording</option>";
        }

        return view('admin.level.update_level',compact('typesOptions','indicatorsOptions','level'));
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $wording = $request->wording;
        $type = $request->type;
        $indicators = $request->input('indicators');
        $selectedChk = MiscController::chkArrayOfMultiSelect($indicators);

        $level = Level::where('wording',$wording)->first();
        if($level == null)
        {

        }
        else
        {
            if($level->id == $id)
            {

            }
            else
            {
                return redirect()->back()->with('validation','error');
            }
        }


        $rules = [
            'wording' => 'required|string',
            'type' => 'required|integer',
        ];

        $validator = Validator::make($request->all(),$rules);

        if($validator->fails())
        {
            return redirect()->back()->with('validation','error');
        }
        else
        {
            $levelIsUpdated = Level::find($id)->update(["wording"=>$wording, "id_type"=>$type]);
            
            if($levelIsUpdated)
            {
                if($selectedChk!=null)
                {
                    Level::find($id)->indicators()->sync($selectedChk);
                }
                return redirect()->back()->with('success','success');
            }
            else   
            {
                return redirect()->back()->with('error','error');
            }
        }
    }

    public function prepareTypeUpdate($id)
    {
        $type = Type::where('id',$id)->first();

        return view('admin.level.update_type', compact('type'));
    }

    public function updateType(Request $request)
    {
        $id = $request->id;
        $wording = $request->wording;
        
        $rules = [
            'wording' => 'required|string',
        ];

        $validator = Validator::make($request->all(),$rules);

        if($validator->fails())
        {
            return redirect()->back()->with('validation','error');
        }
        else
        {
            $typeIsUpdated = Type::find($id)->update(["wording"=>$wording]);
            
            if($typeIsUpdated)
            {
                return redirect()->back()->with('success','success');
            }
            else   
            {
                return redirect()->back()->with('error','error');
            }
        }
    }


    public function exportType(Request $request)
    {
        $spreadsheet = new Spreadsheet();
        $typesheet = $spreadsheet->getActiveSheet();
        $types = Type::all();
        $datasetname = "types";
        
        $i = 1;
        foreach($types as $type)
        {
            $typesheet->getCellByColumnAndRow(1,$i)->setValue($type->wording);
            $i++;
        }

        $spreadsheet->setActiveSheetIndex(0);

        $writer = new Xlsx($spreadsheet);
        $path = public_path();
        $path = $path."/template/$datasetname.xlsx";
        $writer->save($path);

        return redirect()->back()->with("path","template/$datasetname.xlsx");
    }


    public function importType(Request $request)
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

            $typesheet = $spreadsheet->getSheet(0);

            $numberOfRow = $typesheet->getHighestRow();
  
            // $highestColumn = $domainsheet->getHighestColumn(); // e.g 'F'
            // $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn); // e.g. 5

            for($i = 1; $i <= $numberOfRow; $i++)
            {
                $currentType = $typesheet->getCellByColumnAndRow(1,$i)->getValue();

                if($currentType instanceof RichText)
                {
                    $currentType = $currentType->getPlainText();
                }
                else
                {
                    $currentType = (string)$currentType;
                }
                
                if($this->typeExist($currentType))
                {
                    $linesWithError[] = $i;
                }
                else
                {
                    $type = new Type();
                    $type->wording = $currentType;

                    if($type->save())
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

    public function typeExist($type)
    {
        $types = Type::all();
        $type = trim(strtolower($type));
        
        foreach($types as $typ)
        {
            $typ = trim(strtolower($typ->wording));
            if($typ == $type)
            {
                return true;
            }
            else
            {
                continue;
            }
        }

        return false;
    }

    public function copyRowsFromArray($rows, Spreadsheet $oldSpreadSheet)
    {
        $spreadsheet = new Spreadsheet();
        $numberOfSheet = $oldSpreadSheet->getSheetCount();
        $errorSheet = $spreadsheet->getActiveSheet();
        
        $oldDataSheet = $oldSpreadSheet->getSheet(($numberOfSheet-1));
        $highestColumn = $oldDataSheet->getHighestDataColumn(); // e.g 'F'
        $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn); // e.g. 5

        // for($j = 1; $j <= $highestColumnIndex; $j++)
        // {
        //     $oldCellValue = $oldDataSheet->getCellByColumnAndRow($j,1)->getValue();
        //     if(!is_string($oldCellValue))
        //     {
        //         $oldCellValue = (int)$oldCellValue;
        //         if($oldCellValue == 0)
        //         {
        //             $oldCellValue = "";
        //         }
        //     }
        //     $errorSheet->getCellByColumnAndRow($j,1)->setValue($oldCellValue);
        // }

        $numberOfRows = count($rows);
        $i = 0;
        for($row = 1; $row <= ($numberOfRows); $row++)
        {
            for($j = 1; $j <= $highestColumnIndex; $j++)
            {
                $oldCellValue = $oldDataSheet->getCellByColumnAndRow($j,$rows[$i])->getValue();
                if($oldCellValue instanceof RichText)
                {
                    $oldCellValue = $oldCellValue->getPlainText();
                }
                else
                {
                    $oldCellValue = (string)$oldCellValue;
                }

                if(!is_string($oldCellValue))
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

    public function export(Request $request)
    {
        $spreadsheet = new Spreadsheet();
        $levelsheet = $spreadsheet->getActiveSheet();
        $levels = Level::all();
        $datasetname = "levels";
        
        $levelsheet->getCellByColumnAndRow(1,1)->setValue("Type");
        $levelsheet->getCellByColumnAndRow(2,1)->setValue("Niveau de desagregation");

        $i = 2;
        foreach($levels as $level)
        {
            $levelsheet->getCellByColumnAndRow(1,$i)->setValue($level->type->wording);
            $levelsheet->getCellByColumnAndRow(2,$i)->setValue($level->wording);

            $indicators = $level->indicators()->get();
            $j = 3;
            foreach($indicators as $indicator)
            {
                $levelsheet->getCellByColumnAndRow($j,$i)->setValue($indicator->wording);
                $j++;
            }
            $i++;
        }

        $spreadsheet->setActiveSheetIndex(0);

        $writer = new Xlsx($spreadsheet);
        $path = public_path();
        $path = $path."/template/$datasetname.xlsx";
        $writer->save($path);

        return redirect()->back()->with("path","template/$datasetname.xlsx");
    }


    public function import(Request $request)
    {
        set_time_limit(300);
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

            $levelsheet = $spreadsheet->getSheet(0);

            $numberOfRow = $levelsheet->getHighestDataRow();
  
            $highestColumn = $levelsheet->getHighestDataColumn(); // e.g 'F'
            $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn); // e.g. 5
            
            for($i = 2; $i <= $numberOfRow; $i++)
            {
                $j = 3;
                $currentType = $levelsheet->getCellByColumnAndRow(1,$i)->getValue();
                $currentLevel = $levelsheet->getCellByColumnAndRow(2,$i)->getValue();
                $indicatorArray = [];
                $indicatorIdArray = [];

                if($currentType instanceof RichText)
                {
                    $currentType = $currentType->getPlainText();
                }
                else
                {
                    $currentType = (string)$currentType;
                }

                if($currentLevel instanceof RichText)
                {
                    $currentLevel = $currentLevel->getPlainText();
                }
                else
                {
                    $currentLevel = (string)$currentLevel;
                }
                
                for($j = 3; $j <= $highestColumnIndex; $j++)
                {
                    $indicator = $levelsheet->getCellByColumnAndRow($j,$i)->getValue();
                    if($indicator instanceof RichText)
                    {
                        $indicator = $indicator->getPlainText();
                    }
                    else
                    {
                        $indicator = (string)$indicator;
                    }

                    if($indicator != "" && $indicator != null)
                    {
                        $indicatorArray[] = $indicator;
                    }
                }

                //dd($indicatorArray);


                if(!$this->typeExist($currentType) || $this->levelExist($currentLevel))
                {
                    $linesWithError[] = $i;
                }
                else
                {
                    $allIndicatorExist = true;
                    //$test = [];
                    foreach($indicatorArray as $indicator)
                    {
                        if(!$this->IndicatorExist($indicator))
                        {
                            $allIndicatorExist = false;
                            $linesWithError[] = $i;
                            //$test[] = $indicator;
                            break;
                        }
                    }
                    //dd($test);
                    if($allIndicatorExist)
                    {
                        $canContinue = true;
                        //We try to get indicators id
                        foreach($indicatorArray as $indicator)
                        {
                            if($this->getIndicatorIdByWording($indicator)==0)
                            {
                                $linesWithError[] = $i;
                                $canContinue = false;
                                break;
                            }
                            else
                            {
                                $indicatorIdArray[] = $this->getIndicatorIdByWording($indicator);
                                continue;
                            }
                        }

                        if(!$canContinue)
                        {
                            continue;
                        }

                        if($this->getTypeIdByWording($currentType)==0)
                        {
                            $linesWithError[] = $i;
                            $canContinue = false;
                            continue;
                        }

                        if($canContinue)
                        {
                            $type = $this->getTypeIdByWording($currentType);
                            $level = new Level;
                            $level->id_type = $type;
                            $level->wording = $currentLevel;

                            if($level->save())
                            {
                                $level->indicators()->attach($indicatorIdArray);
                            }
                            else
                            {
                                $linesWithError[] = $i;
                            }
                            
                        }
                    }
                }
            }

            if($linesWithError == null || (count($linesWithError)==0))
            {
                return redirect()->back()->with('success','success');
            }
            else
            {
                $errorSpreadSheet = $this->copyLRowsFromArray($linesWithError, $spreadsheet);

                $writer = new Xlsx($errorSpreadSheet);
                $path = public_path();
                $path = $path."/template/unsaved.xlsx";
                $writer->save($path);

                return redirect()->back()->with("path","template/unsaved.xlsx") 
                                        ->with('warning','warning');
            }
        }
    }


    public function levelExist($level)
    {
        $levels = Level::all();
        $level = trim(strtolower($level));
        
        foreach($levels as $lev)
        {
            $lev = trim(strtolower($lev->wording));
            if($lev == $level)
            {
                return true;
            }
            else
            {
                continue;
            }
        }

        return false;
    }
    

    public function IndicatorExist($indicator)
    {
        $indicators = Indicators::all();
        $indicator = trim(strtolower($indicator));
        
        foreach($indicators as $indicat)
        {
            $indicat = trim(strtolower($indicat->wording));
            if($indicat == $indicator)
            {
                return true;
            }
            else
            {
                continue;
            }
        }

        return false;
    }

    public function getTypeIdByWording($type)
    {
        $types = Type::all();
        $type = trim(strtolower($type));
        
        foreach($types as $typ)
        {
            $typw = trim(strtolower($typ->wording));
            if($typw == $type)
            {
                return $typ->id;
            }
            else
            {
                continue;
            }
        }

        return 0;

    }

    public function getIndicatorIdByWording($indicator)
    {
        $indicators = Indicators::all();
        $indicator = trim(strtolower($indicator));
        
        foreach($indicators as $indicat)
        {
            $indicatw = trim(strtolower($indicat->wording));
            if($indicatw == $indicator)
            {
                return $indicat->id;
            }
            else
            {
                continue;
            }
        }

        return 0;

    }



    public function copyLRowsFromArray($rows, Spreadsheet $oldSpreadSheet)
    {
        $spreadsheet = new Spreadsheet();
        $numberOfSheet = $oldSpreadSheet->getSheetCount();
        $errorSheet = $spreadsheet->getActiveSheet();
        
        $oldDataSheet = $oldSpreadSheet->getSheet(($numberOfSheet-1));
        $highestColumn = $oldDataSheet->getHighestDataColumn(); // e.g 'F'
        $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn); // e.g. 5

        for($j = 1; $j <= $highestColumnIndex; $j++)
        {
            $oldCellValue = $oldDataSheet->getCellByColumnAndRow($j,1)->getValue();
            if($oldCellValue instanceof RichText)
            {
                $oldCellValue = $oldCellValue->getPlainText();
            }
            else
            {
                $oldCellValue = (string)$oldCellValue;
            }
            
            if(!is_string($oldCellValue))
            {
                $oldCellValue = (int)$oldCellValue;
                if($oldCellValue == 0)
                {
                    $oldCellValue = "";
                }
            }
            $errorSheet->getCellByColumnAndRow($j,1)->setValue($oldCellValue);
        }

        $numberOfRows = count($rows);
        $i = 0;
        for($row = 2; $row <= ($numberOfRows + 1); $row++)
        {
            for($j = 1; $j <= $highestColumnIndex; $j++)
            {
                $oldCellValue = $oldDataSheet->getCellByColumnAndRow($j,$rows[$i])->getValue();
                if($oldCellValue instanceof RichText)
                {
                    $oldCellValue = $oldCellValue->getPlainText();
                }
                else
                {
                    $oldCellValue = (string)$oldCellValue;
                }

                if(!is_string($oldCellValue))
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
