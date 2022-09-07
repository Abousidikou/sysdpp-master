<?php

namespace App\Http\Controllers\Structure;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MiscController;
use Illuminate\Support\Facades\Validator;
use App\Models\Structures;
use App\Models\Indicators;
use App\Models\SubDomain;
use App\Models\Domain;
use App\User;


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\RichText\RichText;

class StructureController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $structures = Structures::all();
        foreach($structures as $structure)
        {
            $numberOfSubdomain = $structure->subdomain()->count();
            if($numberOfSubdomain == 0)
            {
                $structure->subdomain = "Aucun sous domaine";
                $structure->multisubd = false;
            }
            else if($numberOfSubdomain == 1)
            {   
                $structure->subdomain = $structure->subdomain()->first()->wording;
                $structure->multisubd = false;
            }
            else
            {
                $structure->subdomain = $structure->subdomain()->first()->wording;
                $structure->multisubd = true;
            }
            
        }


        return view('admin.structure.structures',compact('structures'));
    }

    public function showMoreSubD(Request $request)
    {
        if($request->ajax())
        {
            $structId = $request->structId;
            $subdomains = Structures::find($structId)->subdomain()->get();
            
            $structWording = Structures::where('id',$structId)->first()->wording;
            $builtList = "<ol>";
            foreach($subdomains as $subdomain)
            {
                $builtList .= "<li>$subdomain->wording</li>";
            }

            $builtList .= "</ol>";

            return json_encode(array('title' => $structWording, 'builtList' => $builtList));
        }
    }

    public function form()
    {
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

        return view('admin.structure.new_structure',compact('subdomainsOptions','domainsOptions'));
    }


    public function formImport()
    {
        return view('admin.structure.import_structure_file');
    }

    public function create(Request $request)
    {
        $wording = $request->wording;
        $abr = $request->abr;
        $subdomains = $request->subdomains;
        $selectedChk = MiscController::chkArrayOfMultiSelect($subdomains);
        $rules = [
            'wording' =>'unique:structures|required',
            'abr' => 'unique:structures|required',
            // 'subdomains' => 'required'
        ];

        
 
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails() || $selectedChk == null)
        {
            return redirect()->back()->with('validation','error');
        }
        else
        {
            $structure = new Structures;
            $structure->wording = $wording;
            $structure->abr = $abr;

            if($structure->save())
            {
                if($selectedChk!=null)
                {
                    $structure->subdomain()->attach($selectedChk);
                }
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
        $structure_id = $id;

        // $indicators = Indicators::where('id_structure',$structure_id)->get();
        $agents = User::where('id_structure',$structure_id)->get();
        if(!$agents->isEmpty())
        {
            $structureIsDeleted = Structures::find($structure_id)->delete();
            if($structureIsDeleted)
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
        $structure = Structures::where('id',$id)->first();
        $subdomainsOptions = "";

        $firstSubDomain = $structure->subdomain->first();

        foreach($structure->subdomain as $subdo) {
            $subdomainsOptions .= "<option value=$subdo->id selected>$subdo->wording</option>";
        }

        $subdomains = SubDomain::where('id', '!=', $firstSubDomain->id)
                                ->where('id_domain', '=', $firstSubDomain->id_domain)
                                ->get();

        foreach($subdomains as $subdomain)
        {
            $subdomainsOptions .= "<option value=$subdomain->id>$subdomain->wording</option>";
        }

        $domain = Domain::where('id',$firstSubDomain->id_domain)->first();
        $domains = Domain::where('id', '!=', $firstSubDomain->id_domain)->get();
        $domainsOptions = "<option value=$domain->id selected>$domain->wording</option>";

        foreach($domains as $domain)
        {
            $domainsOptions .= "<option value=$domain->id>$domain->wording</option>";
        }

        return view('admin.structure.update_structure',compact('structure','subdomainsOptions','domainsOptions'));
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $wording = $request->wording;
        $abr = $request->abr;
        $subdomains = $request->subdomains;
        $selectedChk = MiscController::chkArrayOfMultiSelect($subdomains);

        $rules = [
            'wording' =>'required',
            'abr' => 'required',
        ];
 
        $structure = Structures::where('wording',$wording)->first();
        if($structure == null)
        {
            $structure = Structures::where('abr',$abr)->first();
            if($structure == null)
            {
    
            }
            else
            {
                if($structure->id == $id)
                {
    
                }
                else
                {
                    return redirect()->back()->with('validation','error');
                }
            }
        }
        else
        {
            if($structure->id == $id)
            {
                $structure = Structures::where('abr',$abr)->first();
                if($structure == null)
                {
        
                }
                else
                {
                    if($structure->id == $id)
                    {
        
                    }
                    else
                    {
                        return redirect()->back()->with('validation','error');
                    }
                }
            }
            else
            {
                return redirect()->back()->with('validation','error');
            }
        }

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails())
        {
            return redirect()->back()->with('validation','error');
        }
        else
        {
            $structureIsUpdated = Structures::find($id)->update(["wording" =>$wording,"abr"=>$abr]);

            if($structureIsUpdated)
            {
                if($selectedChk!=null)
                {
                    Structures::find($id)->subdomain()->sync($selectedChk);
                }
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
        $structuresheet = $spreadsheet->getActiveSheet();
        $structures = Structures::all();
        $datasetname = "structures";

        $structuresheet->getCellByColumnAndRow(1,1)->setValue("Structure");
        $structuresheet->getCellByColumnAndRow(2,1)->setValue("Abreviation");

        $i = 2;
        foreach($structures as $structure)
        {
            $structuresheet->getCellByColumnAndRow(1,$i)->setValue($structure->wording);
            $structuresheet->getCellByColumnAndRow(2,$i)->setValue($structure->abr);

            $subdomains = $structure->subdomain()->get();
            if($subdomains->isEmpty())
            {
                $structuresheet->getCellByColumnAndRow(3,$i)->setValue("Pas de sous-domaine associe");
            }
            else
            {
                //$structuresheet->getCellByColumnAndRow(1,$i)->setValue($subdomains[0]->domain->wording);
                $j = 3;
                foreach($subdomains as $subdomain)
                {
                    $structuresheet->getCellByColumnAndRow($j,$i)->setValue($subdomain->wording);
                    $j++;
                }
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
            $structuresheet = $spreadsheet->getSheet(($numberOfSheet-1));

            $numberOfRow = $structuresheet->getHighestRow();
  
            $highestColumn = $structuresheet->getHighestDataColumn(); // e.g 'F'
            $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn); // e.g. 5
            $indicatorColumnIndex = 1;
            for($i = 2; $i <= $numberOfRow; $i++)
            {
                $subdomainIdArray = [];
                $subdomainArray = [];
                $currentStructure = $structuresheet->getCellByColumnAndRow(1,$i)->getValue();
                $currentAbr = $structuresheet->getCellByColumnAndRow(2,$i)->getValue();
                // dd($currentStructure, $currentAbr);
                if($currentStructure instanceof RichText)
                {
                    $currentStructure = $currentStructure->getPlainText();
                }
                else
                {
                    $currentStructure = (string)$currentStructure;
                }

                if($currentAbr instanceof RichText)
                {
                    $currentAbr = $currentAbr->getPlainText();
                }
                else
                {
                    $currentAbr = (string)$currentAbr;
                }

                for($j = 3; $j <= $highestColumnIndex; $j++)
                {
                    $subdomain = $structuresheet->getCellByColumnAndRow($j,$i)->getValue();
                    
                    if($subdomain instanceof RichText)
                    {
                        $subdomain = $subdomain->getPlainText();
                    }
                    else
                    {
                        $subdomain = (string)$subdomain;
                    }

                    if($subdomain != "" && $subdomain != null)
                    {
                        $subdomainArray[] = $subdomain;
                    }
                }

                $allSubDomainExist = true;

                foreach($subdomainArray as $subdomain)
                {
                    if(!$this->subDomainExist($subdomain))
                    {
                        $allSubDomainExist = false;
                        $linesWithError[] = $i;
                        break;
                    }
                }


                if($allSubDomainExist)
                {
                    $canContinue = true;
                    //We try to get indicators id
                    foreach($subdomainArray as $subdomain)
                    {
                        $subdomainIdArray[] = $this->getSubDomainIdByWording($subdomain);
                        continue;
                    }

                    if($canContinue)
                    {
                        $rules = [
                            'wording' =>'unique:structures|required',
                            'abr' => 'unique:structures|required',
                            // 'subdomains' => 'required'
                        ];

                        $validator = Validator::make(['wording'=>$currentStructure,'abr'=>$currentAbr], $rules);
                        if($validator->fails())
                        {
                            $linesWithError[] = $i;
                            continue;
                        }
                        else
                        {
                            $structure = new Structures();
                            $structure->wording = $currentStructure;
                            $structure->abr = $currentAbr;
                            
                            if($structure->save())
                            {
                                $structure->subdomain()->attach($subdomainIdArray);
                                continue;
                            }
                            else
                            {
                                $linesWithError[] = $i;
                                continue;
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

    public function subDomainExist($subdomain)
    {
        // $domainId = $this->getDomainIdByWording($domain);
        // if($domainId == 0)
        // {
        //     return false;
        // }
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


    public function domainExist($domain)
    {
        $domains = Domain::all();
        $domain = trim(strtolower($domain));
        
        foreach($domains as $dom)
        {
            $domw = trim(strtolower($dom->wording));
            if($domw == $domain)
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


    public function getDomainIdByWording($domain)
    {
        $domains = Domain::all();
        $domain = trim(strtolower($domain));
        
        foreach($domains as $dom)
        {
            $domw = trim(strtolower($dom->wording));
            if($domw == $domain)
            {
                return $dom->id;
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
