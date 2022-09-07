<?php

namespace App\Http\Controllers\Domain;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;
use App\Models\Domain;
use App\Models\SubDomain;
use App\User;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\RichText\RichText;


class DomainController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        $domains = Domain::all();
        //dd($domains);
        return view('admin.domain.domains',compact('domains'));
    }

    public function form()
    {
        return view('admin.domain.new_domain');
    }

    public function formImport()
    {
        return view('admin.domain.import_domain_file');
    }

    public function create(Request $request)
    {
        $wording = $request->wording;
 
        $rules = [
            'wording' =>'unique:domains|required'
        ];
 
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails())
        {
            return redirect()->back()->with('validation','error');
        }
        else
        {
            $domain = new Domain;
            $domain->wording = $wording;

            if($domain->save())
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
        $subdomains = SubDomain::where('id_domain',$id)->get();
        if(!$subdomains->isEmpty())
        {
            $domainIsDeleted = Domain::find($id)->delete();
            if($domainIsDeleted)
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
        $domain = Domain::where('id',$id)->first();

        return view('admin.domain.update_domain',compact('domain'));
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $wording = $request->wording;

        $rules = [
            'wording' =>'required|string',
        ];

        $domain = Domain::where('wording',$wording)->first();
        if($domain == null)
        {

        }
        else
        {
            if($domain->id == $id)
            {

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
            $domainIsUpdated = Domain::find($id)->update(["wording" =>$wording]);

            if($domainIsUpdated)
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
        $domainsheet = $spreadsheet->getActiveSheet();
        $domains = Domain::all();
        $datasetname = "domains";

        $i = 1;
        foreach($domains as $domain)
        {
            $domainsheet->getCellByColumnAndRow(1,$i)->setValue($domain->wording);
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

            $domainsheet = $spreadsheet->getSheet(0);

            $numberOfRow = $domainsheet->getHighestRow();
  
            // $highestColumn = $domainsheet->getHighestColumn(); // e.g 'F'
            // $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn); // e.g. 5

            for($i = 1; $i <= $numberOfRow; $i++)
            {
                $currentDomain = $domainsheet->getCellByColumnAndRow(1,$i)->getValue();
                if($currentDomain instanceof RichText)
                {
                    $currentDomain = $currentDomain->getPlainText();
                }
                else
                {
                    $currentDomain = (string)$currentDomain;
                }
                if($this->domainExist($currentDomain))
                {
                    $linesWithError[] = $i;
                }
                else
                {
                    $domain = new Domain;
                    $domain->wording = $currentDomain;

                    if($domain->save())
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
