<?php

namespace App\Http\Controllers\SubDomain;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\MiscController;
use App\Models\Domain;
use App\Models\SubDomain;
use App\Models\Structures; 
use App\Models\Indicators;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\RichText\RichText;

class SubDomainController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $subdomains = SubDomain::all();

        foreach($subdomains as $subdomain)
        {
            $subdomain->domain = $subdomain->domain->wording;
        }

        return view('admin.subdomain.subdomains',compact('subdomains'));
    }


    public function form()
    {
       
        $domainsOptions = "";

        $domains = Domain::all();


        foreach($domains as $domain)
        {
            $domainsOptions .= "<option value=$domain->id>$domain->wording</option>";
        }

        return view('admin.subdomain.new_subdomain',compact('domainsOptions'));
    }

    public function formImport()
    {
        return view('admin.subdomain.import_subdomain_file');
    }

    public function create(Request $request)
    {
        $wording = $request->wording;
        $domain = $request->domain;

        $rules = [
            'wording' => 'unique:sub_domains|string',
            'domain' => 'required|integer',
        ];

        $validator = Validator::make($request->all(),$rules);
        if($validator->fails())
        {
            return redirect()->back()->with('validation','error');
        }
        else
        {
            $subdomain = new SubDomain;
            $subdomain->wording = $wording;
            $subdomain->id_domain = $domain;

            if($subdomain->save())
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
        //$structures = Structures::where('id_subdomain',$id)->get();

        $indicators = Indicators::where('id_subdomain',$id)->get();

        if(!$indicators->isEmpty())
        {
            $subdomainIsDeleted = SubDomain::find($id)->delete();
            if($subdomainIsDeleted)
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
        $subdomain = SubDomain::where('id',$id)->first();
        $domainsOptions = "";
        
        $domainId = $subdomain->id_domain;
        $domainWording = $subdomain->domain->wording;
        $domainsOptions .= "<option value=$domainId>$domainWording</option>";

        $domains = Domain::where('id','!=',$domainId)->get();
        
        foreach($domains as $domain)
        {
            $domainsOptions .= "<option value=$domain->id>$domain->wording</option>";
        }

        return view('admin.subdomain.update_subdomain',compact('domainsOptions','subdomain'));
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $wording = $request->wording;
        $domain = $request->domain;
        
        $rules = [
            'wording' => 'string|required',
            'domain' => 'required|integer',
        ];

        $subdomain = SubDomain::where('wording',$wording)->first();
        if($subdomain == null)
        {

        }
        else
        {
            if($subdomain->id == $id)
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
            $subdomainIsUpdated = SubDomain::find($id)->update(["wording"=>$wording,"id_domain"=>$domain]);
            
            if($subdomainIsUpdated)
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
        $subdomainsheet = $spreadsheet->getActiveSheet();
        $subdomains = SubDomain::all();
        $datasetname = "subdomains";

        $subdomainsheet->getCellByColumnAndRow(1,1)->setValue("Domaines");
        $subdomainsheet->getCellByColumnAndRow(2,1)->setValue("Sous domaines");

        $i = 2;
        foreach($subdomains as $subdomain)
        {
            $subdomainsheet->getCellByColumnAndRow(1,$i)->setValue($subdomain->domain->wording);
            $subdomainsheet->getCellByColumnAndRow(2,$i)->setValue($subdomain->wording);
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
            $subdomainsheet = $spreadsheet->getSheet(($numberOfSheet-1));

            $numberOfRow = $subdomainsheet->getHighestRow();
  
            $highestColumn = $subdomainsheet->getHighestColumn(); // e.g 'F'
            $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn); // e.g. 5
            $indicatorColumnIndex = 1;
            

            for($i = 2; $i <= $numberOfRow; $i++)
            {
                $currentDomain = $subdomainsheet->getCellByColumnAndRow(1,$i)->getValue();
                $currentSubDomain = $subdomainsheet->getCellByColumnAndRow(2,$i)->getValue();

                if($currentDomain instanceof RichText)
                {
                    $currentDomain = $currentDomain->getPlainText();
                }
                else
                {
                    $currentDomain = (string) $currentDomain;
                }

                if($currentSubDomain instanceof RichText)
                {
                    $currentSubDomain = $currentSubDomain->getPlainText();
                }
                else
                {
                    $currentSubDomain = (string) $currentSubDomain;
                }

                if($this->subDomainExist($currentSubDomain) || (!$this->domainExist($currentDomain)) || $this->getDomainIdByWording($currentDomain)==0)
                {
                    $linesWithError[] = $i;
                }
                else
                {
                    $subdom = new SubDomain();
                    $subdom->wording = $currentSubDomain;
                    $subdom->id_domain = $this->getDomainIdByWording($currentDomain);

                    if($subdom->save())
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

    public function subDomainExist($subdomain)
    {
        $subdomains = SubDomain::all();
        $subdomain = trim(strtolower($subdomain));
        
        foreach($subdomains as $subdom)
        {
            $subdom = trim(strtolower($subdom->wording));
            if($subdom == $subdomain)
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
            $dom = trim(strtolower($dom->wording));
            if($dom == $domain)
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
