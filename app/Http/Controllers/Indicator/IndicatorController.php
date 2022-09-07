<?php

namespace App\Http\Controllers\Indicator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MiscController;
use Illuminate\Support\Facades\Validator;
use App\Models\Indicators;
use App\Models\SubDomain;
use App\Models\Domain;
use App\Models\Level;
use App\Models\Data;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\RichText\RichText; 

class IndicatorController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $indicators = Indicators::all();

        foreach($indicators as $indicator)
        {
            $indicator->subdomain = $indicator->subdomain->wording;
        }

        return view('admin.indicator.indicators',compact('indicators'));
    }

    public function form()
    {
        $subdomainsOptions = "";
        $levelsOptions = "";

        $subdomains = SubDomain::all(); 
        $levels = Level::all();

        foreach($subdomains as $subdomain)
        {
            $subdomainsOptions .= "<option value=$subdomain->id>$subdomain->wording</option>";
        }

        foreach($levels as $level)
        {
            $levelsOptions .= "<option value=$level->id>$level->wording</option>";
        }

        $domains = Domain::all();
        $domainsOptions = "";

        foreach($domains as $domain)
        {
            $domainsOptions .= "<option value=$domain->id>$domain->wording</option>";
        }
        
        return view('admin.indicator.new_indicator',compact('subdomainsOptions','levelsOptions', 'domainsOptions'));
    }


    public function formImport()
    {
        return view('admin.indicator.import_indicator_file');
    }

    public function create(Request $request)
    {
        $wording = $request->wording;
        $subdomain = $request->subdomain;
        
        $rules = [
            'wording' => 'unique:indicators|string|required',
            'subdomain' => 'required|integer',
            // 'levels' => 'required'
        ];

        $validator = Validator::make($request->all(),$rules);
        if($validator->fails() )
        {
            return redirect()->back()->with('validation','error');
        }
        else
        {
            $indicator = new Indicators;
            $indicator->wording = $wording;
            $indicator->id_subdomain = $subdomain;

            if($indicator->save())
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
        $indicator_id = $id;

        $levels = Indicators::find($indicator_id)->levels()->get();
        $infos = Data::where('id_indicator',$indicator_id)->get();
        if(!$levels->isEmpty() && !$infos->isEmpty()) 
        {
            $indicatorIsDeleted = Indicators::find($indicator_id)->delete();
            if($indicatorIsDeleted)
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
        $indicator = Indicators::where('id',$id)->first();
        $subdomainsOptions = "";
        
        $subdomainId = $indicator->subdomain->id;
        $subdomainWording = $indicator->subdomain->wording;
        $subdomainsOptions .= "<option value=$subdomainId selected>$subdomainWording</option>";
        
        $subdomains = SubDomain::where('id','!=',$subdomainId)->get();
        
        foreach($subdomains as $subdomain)
        {
            $subdomainsOptions .= "<option value=$subdomain->id>$subdomain->wording</option>";
        }

        $domain = Domain::where('id', $indicator->subdomain->id_domain)->first();
        $domains = Domain::where('id', '!=', $subdomainId)->get();
        $domainsOptions = "";
        $domainsOptions .= "<option value=$domain->id selected>$domain->wording</option>";

        foreach($domains as $domain)
        {
            $domainsOptions .= "<option value=$domain->id>$domain->wording</option>";
        }

        return view('admin.indicator.update_indicator',compact('subdomainsOptions','indicator','domainsOptions'));
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $wording = $request->wording;
        $subdomain = $request->subdomain;

        $rules = [
            'wording' => 'string|required',
            'subdomain' => 'required|integer',
        ];

        $indicator = Indicators::where('wording',$wording)->first();
        if($indicator == null)
        {

        }
        else
        {
            if($indicator->id == $id)
            {

            }
            else
            {
                return redirect()->back()->with('validation','error');
            }
        }
 
        $validator = Validator::make($request->all(),$rules);
        if($validator->fails())
        {
            return redirect()->back()->with('validation','error');
        }
        else
        {
            $indicatorIsUpdated = Indicators::find($id)->update(["wording"=>$wording,"id_subdomain"=>$subdomain]);
            
            if($indicatorIsUpdated)
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
        $indicatorsheet = $spreadsheet->getActiveSheet();
        $indicators = Indicators::all();
        $datasetname = "indicators";

        $indicatorsheet->getCellByColumnAndRow(1,1)->setValue("Sous-domaine");
        $indicatorsheet->getCellByColumnAndRow(2,1)->setValue("Information statistique");

        $i = 2;
        foreach($indicators as $indicator)
        {
            $indicatorsheet->getCellByColumnAndRow(1,$i)->setValue($indicator->subdomain->wording);
            $indicatorsheet->getCellByColumnAndRow(2,$i)->setValue($indicator->wording);
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
            $indicatorsheet = $spreadsheet->getSheet(($numberOfSheet-1));

            $numberOfRow = $indicatorsheet->getHighestRow();
  
            $highestColumn = $indicatorsheet->getHighestDataColumn(); // e.g 'F'
            $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn); // e.g. 5
            $indicatorColumnIndex = 1;
            

            for($i = 2; $i <= $numberOfRow; $i++)
            {
                $currentSubDomain = $indicatorsheet->getCellByColumnAndRow(1,$i)->getValue();
                $currentIndicator = $indicatorsheet->getCellByColumnAndRow(2,$i)->getValue();

                if($currentSubDomain instanceof RichText)
                {
                    $currentSubDomain = $currentSubDomain->getPlainText();
                }
                else
                {
                    $currentSubDomain = (string)$currentSubDomain;
                }

                if($currentIndicator instanceof RichText)
                {
                    $currentIndicator = $currentIndicator->getPlainText();
                }
                else
                {
                    $currentIndicator = (string)$currentIndicator;
                }


                if($this->IndicatorExist($currentIndicator) || (!$this->subDomainExist($currentSubDomain)) || $this->getSubDomainIdByWording($currentSubDomain)==0)
                {
                    $linesWithError[] = $i;
                }
                else
                {
                    $indicator = new Indicators();
                    $indicator->wording = $currentIndicator;
                    $indicator->id_subdomain = $this->getSubDomainIdByWording($currentSubDomain);

                    if($indicator->save())
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

    public function subDomainExist($subdomain)
    {
        $subdomains = SubDomain::all();
        $subdomain = trim(strtolower($subdomain));
        
        foreach($subdomains as $subdom)
        {
            $subdomw = trim(strtolower($subdom->wording));
            if($subdomw == $subdomain)
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

    public function getSubDomainIdByWording($subdomain)
    {
        $subdomains = SubDomain::all();
        $subdomain = trim(strtolower($subdomain));
        
        foreach($subdomains as $subdom)
        {
            $subdomw = trim(strtolower($subdom->wording));
            if($subdomw == $subdomain)
            {
                return $subdom->id;
            }
            else
            {
                continue;
            }
        }

        return 0;

    }


    public function copyRowsFromArray($rows, Spreadsheet $oldSpreadSheet)
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
