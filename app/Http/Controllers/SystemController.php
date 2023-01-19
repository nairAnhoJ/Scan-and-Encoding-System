<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Batch;
use App\Models\DeletedBatch;
use App\Models\Department;
use App\Models\DocType;
use App\Models\EncodeForm;
use App\Models\FolderList;
use Illuminate\Http\Request;
use Illuminate\Queue\Console\BatchesTableCommand;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use PhpParser\Node\Expr\New_;

class SystemController extends Controller
{
    public function index(){

        $batches = DB::table('batches')->orderBy('name', 'asc')->get();
        $docTypes = DB::table('doc_types')->orderBy('name', 'asc')->get();
        $folders = DB::table('folder_lists')->get();
        $departments = DB::table('departments')->orderBy('name', 'asc')->get();
        $accounts = DB::select('SELECT accounts.id, accounts.name, accounts.username, accounts.department AS deptid, departments.name AS department FROM (accounts INNER JOIN departments ON accounts.department = departments.id) WHERE accounts.id != "1"');

        return view('/system-management/index', compact('batches','docTypes','folders','departments','accounts'))->with('tab', '1');
    }



























    // ====================================================== B A T C H ======================================================

        public function getBatch(Request $request){
            $deptID = $request->deptBatch;

            $deptBatches = DB::table('batches')->where('dept_id', $deptID)->orderBy('name', 'asc')->get();

            if($deptBatches->count() > 0){
                $output =   '';
                $x = 1;
    
                foreach ($deptBatches as $deptBatch){
                    $output .=  '
                                <tr class="bg-white border-b">
                                    <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">
                                        '.$x++.'
                                    </th>
                                    <td class="py-4 px-6">
                                        '.$deptBatch->name.'
                                    </td>
                                    <td class="py-4 px-6">
                                        <a type="button" data-id="'.$deptBatch->id.'" data-name="'.$deptBatch->name.'" class="btnEditThisBatch font-medium text-blue-600 hover:underline cursor-pointer">Edit</a>
                                        <span class="mx-2">|</span>
                                        <a type="submit" data-id="'.$deptBatch->id.'" data-name="'.$deptBatch->name.'" class="btnDeleteThisBatch font-medium text-red-600 hover:underline cursor-pointer">Delete</a>
                                    </td>
                                </tr>
                                ';
                }
    
            }else{
                $output =   '
                                <tr class="bg-white border-b">
                                    <td colspan="4" class="py-4 px-6 text-center">No data.</td>
                                </tr>
                            ';
            }
            echo $output;
        }

        public function batchAdd(Request $request){
            $batchDeptID = $request->batchDeptId;

            $request->validate([
                'batchName' => 'required',
            ]);

            $batch = New Batch();
            $batch->dept_id = $batchDeptID;
            $batch->name = strtoupper($request->batchName);
            $batch->save();

            // $lastBatch = DB::table('batches')->get()->last();

            // $folder = New FolderList();
            // $folder->dept_id = $batchDeptID;
            // $folder->batch_id = $lastBatch->id;
            // $folder->name = '1';
            // $folder->save();

            // $dirDoc = public_path().'/documents';
            // if (!file_exists($dirDoc)) {
            //     File::makeDirectory($dirDoc);
            // }

            // $dirDept = $dirDoc.'/'.$batchDeptID;
            // if (!file_exists($dirDept)) {
            //     File::makeDirectory($dirDept);
            // }

            // $dir2 = $dirDept.'/'.$lastBatch->id;
            // File::makeDirectory($dir2);

            // $dir3 = $dir2.'/1';
            // File::makeDirectory($dir3);

            $deptBatches = DB::table('batches')->where('dept_id', $batchDeptID)->orderBy('name', 'asc')->get();

            if($deptBatches->count() > 0){
                $output =   '';
                $x = 1;
    
                foreach ($deptBatches as $deptBatch){
                    $output .=  '
                                <tr class="bg-white border-b">
                                    <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">
                                        '.$x++.'
                                    </th>
                                    <td class="py-4 px-6">
                                        '.$deptBatch->name.'
                                    </td>
                                    <td class="py-4 px-6">
                                        <a type="button" data-id="'.$deptBatch->id.'" data-name="'.$deptBatch->name.'" class="btnEditThisBatch font-medium text-blue-600 hover:underline cursor-pointer">Edit</a>
                                        <span class="mx-2">|</span>
                                        <a type="submit" data-id="'.$deptBatch->id.'" data-name="'.$deptBatch->name.'" class="btnDeleteThisBatch font-medium text-red-600 hover:underline cursor-pointer">Delete</a>
                                    </td>
                                </tr>
                                ';
                }
    
            }else{
                $output =   '
                                <tr class="bg-white border-b">
                                    <td colspan="4" class="py-4 px-6 text-center">No data.</td>
                                </tr>
                            ';
            }
            echo $output;
        }

        public function batchEdit(Request $request){
            $batchName = $request->batchName;
            $batchDeptId = $request->batchDeptId;
            $thisBatchId = $request->thisBatchId;

            $request->validate([
                'batchName' => 'required',
            ]);

            DB::update('update batches SET name = ? WHERE id = ?', [strtoupper($batchName), $thisBatchId]);

            $deptBatches = DB::table('batches')->where('dept_id', $batchDeptId)->orderBy('name', 'asc')->get();

            if($deptBatches->count() > 0){
                $output = '';
                $x = 1;
    
                foreach ($deptBatches as $deptBatch){
                    $output .=  '
                                <tr class="bg-white border-b">
                                    <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">
                                        '.$x++.'
                                    </th>
                                    <td class="py-4 px-6">
                                        '.$deptBatch->name.'
                                    </td>
                                    <td class="py-4 px-6">
                                        <a type="button" data-id="'.$deptBatch->id.'" data-name="'.$deptBatch->name.'" class="btnEditThisBatch font-medium text-blue-600 hover:underline cursor-pointer">Edit</a>
                                        <span class="mx-2">|</span>
                                        <a type="submit" data-id="'.$deptBatch->id.'" data-name="'.$deptBatch->name.'" class="btnDeleteThisBatch font-medium text-red-600 hover:underline cursor-pointer">Delete</a>
                                    </td>
                                </tr>
                                ';
                }
    
            }else{
                $output =   '
                                <tr class="bg-white border-b">
                                    <td colspan="4" class="py-4 px-6 text-center">No data.</td>
                                </tr>
                            ';
            }
            echo $output;
        }

        public function batchDelete(Request $request){
            $deleteId = $request->hdnDeleteId;
            $deptID = $request->hdnSelected1;

            $batchRow = DB::table('batches')->where('id', $deleteId)->get();

            $batchName = $batchRow[0]->name;

            $delBatch = New DeletedBatch();
            $delBatch->prev_id = $deleteId;
            $delBatch->name = $batchName;
            $delBatch->save();

            Batch::where('id',$deleteId)->delete();

            $deptBatches = DB::table('batches')->where('dept_id', $deptID)->orderBy('name', 'asc')->get();

            if($deptBatches->count() > 0){
                $output =   '';
            }else{
                $output =   '
                                <tr class="bg-white border-b">
                                    <td colspan="4" class="py-4 px-6 text-center">No data.</td>
                                </tr>
                            ';
            }
            $x = 1;

            foreach ($deptBatches as $deptBatch){
                $output .=  '
                            <tr class="bg-white border-b">
                                <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">
                                    '.$x++.'
                                </th>
                                <td class="py-4 px-6">
                                    '.$deptBatch->name.'
                                </td>
                                <td class="py-4 px-6">
                                    <a type="button" data-id="'.$deptBatch->id.'" data-name="'.$deptBatch->name.'" class="btnEditThisBatch font-medium text-blue-600 hover:underline cursor-pointer">Edit</a>
                                    <span class="mx-2">|</span>
                                    <a type="submit" data-id="'.$deptBatch->id.'" data-name="'.$deptBatch->name.'" class="btnDeleteThisBatch font-medium text-red-600 hover:underline cursor-pointer">Delete</a>
                                </td>
                            </tr>
                            ';
            }

            echo $output;
        }
    // BATCH END






























    // ====================================================== D O C - T Y P E ======================================================

        public function getdoctype(Request $request){
            $deptID = $request->dept;

            $deptDocTypes = DB::table('doc_types')->where('dept_id', $deptID)->orderBy('id', 'asc')->get();

            if($deptDocTypes->count() > 0){
                $output =   '';
                $x = 1;
    
                foreach ($deptDocTypes as $deptDocType){
                    $output .=  '
                                <tr class="bg-white border-b">
                                    <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">
                                        '.$x++.'
                                    </th>
                                    <td class="py-4 px-6">
                                        '.$deptDocType->name.'
                                    </td>
                                    <td class="py-4 px-6">
                                        <a type="button" data-id="'.$deptDocType->id.'" data-name="'.$deptDocType->name.'" data-modal-toggle="docTypeModal" class="btnEditDocType font-medium text-blue-600 hover:underline cursor-pointer">Edit</a>
                                        <span class="mx-2">|</span>
                                        <a type="submit" data-id="'.$deptDocType->id.'" data-name="'.$deptDocType->name.'" data-modal-toggle="deleteModal" class="btnDeleteDocType font-medium text-red-600 hover:underline cursor-pointer">Delete</a>
                                    </td>
                                </tr>
                                ';
                }
            }else{
                $output =   '
                                <tr class="bg-white border-b">
                                    <td colspan="4" class="py-4 px-6 text-center">No data.</td>
                                </tr>
                            ';
            }
            echo $output;
        }
        
        public function doctypeAdd(Request $request){
            $deptID = $request->deptId;

            $request->validate([
                'docTypeName' => 'required',
            ]);

            $docType = New DocType();
            $docType->dept_id = $deptID;
            $docType->name = strtoupper($request->docTypeName);
            $docType->save();

            

            $deptDocTypes = DB::table('doc_types')->where('dept_id', $deptID)->orderBy('id', 'asc')->get();

            if($deptDocTypes->count() > 0){
                $output =   '';
                $x = 1;
    
                foreach ($deptDocTypes as $deptDocType){
                    $output .=  '
                                <tr class="bg-white border-b">
                                    <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">
                                        '.$x++.'
                                    </th>
                                    <td class="py-4 px-6">
                                        '.$deptDocType->name.'
                                    </td>
                                    <td class="py-4 px-6">
                                        <a type="button" data-id="'.$deptDocType->id.'" data-name="'.$deptDocType->name.'" data-modal-toggle="docTypeModal" class="btnEditDocType font-medium text-blue-600 hover:underline cursor-pointer">Edit</a>
                                        <span class="mx-2">|</span>
                                        <a type="submit" data-id="'.$deptDocType->id.'" data-name="'.$deptDocType->name.'" data-modal-toggle="deleteModal" class="btnDeleteDocType font-medium text-red-600 hover:underline cursor-pointer">Delete</a>
                                    </td>
                                </tr>
                                ';
                }
            }else{
                $output =   '
                                <tr class="bg-white border-b">
                                    <td colspan="4" class="py-4 px-6 text-center">No data.</td>
                                </tr>
                            ';
            }
            echo $output;
        }

        public function doctypeEdit(Request $request){
            $docTypeName = $request->docTypeName;
            $deptID = $request->deptId;
            $thisTypeId = $request->thisTypeId;

            $request->validate([
                'docTypeName' => 'required',
            ]);

            DB::update('update doc_types SET name = ? WHERE id = ?', [strtoupper($docTypeName), $thisTypeId]);

            $deptDocTypes = DB::table('doc_types')->where('dept_id', $deptID)->orderBy('id', 'asc')->get();

            if($deptDocTypes->count() > 0){
                $output =   '';
                $x = 1;
    
                foreach ($deptDocTypes as $deptDocType){
                    $output .=  '
                                <tr class="bg-white border-b">
                                    <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">
                                        '.$x++.'
                                    </th>
                                    <td class="py-4 px-6">
                                        '.$deptDocType->name.'
                                    </td>
                                    <td class="py-4 px-6">
                                        <a type="button" data-id="'.$deptDocType->id.'" data-name="'.$deptDocType->name.'" data-modal-toggle="docTypeModal" class="btnEditDocType font-medium text-blue-600 hover:underline cursor-pointer">Edit</a>
                                        <span class="mx-2">|</span>
                                        <a type="submit" data-id="'.$deptDocType->id.'" data-name="'.$deptDocType->name.'" data-modal-toggle="deleteModal" class="btnDeleteDocType font-medium text-red-600 hover:underline cursor-pointer">Delete</a>
                                    </td>
                                </tr>
                                ';
                }
            }else{
                $output =   '
                                <tr class="bg-white border-b">
                                    <td colspan="4" class="py-4 px-6 text-center">No data.</td>
                                </tr>
                            ';
            }
            echo $output;
        }


        public function doctypeDelete(Request $request){
            $thisTypeId = $request->hdnDeleteId;
            $deptId = $request->hdnSelected1;
            
            DocType::where('id',$thisTypeId)->delete();

            $deptDocTypes = DB::table('doc_types')->where('dept_id', $deptId)->orderBy('id', 'asc')->get();

            if($deptDocTypes->count() > 0){
                $output =   '';
                $x = 1;
    
                foreach ($deptDocTypes as $deptDocType){
                    $output .=  '
                                <tr class="bg-white border-b">
                                    <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">
                                        '.$x++.'
                                    </th>
                                    <td class="py-4 px-6">
                                        '.$deptDocType->name.'
                                    </td>
                                    <td class="py-4 px-6">
                                        <a type="button" data-id="'.$deptDocType->id.'" data-name="'.$deptDocType->name.'" data-modal-toggle="docTypeModal" class="btnEditDocType font-medium text-blue-600 hover:underline cursor-pointer">Edit</a>
                                        <span class="mx-2">|</span>
                                        <a type="submit" data-id="'.$deptDocType->id.'" data-name="'.$deptDocType->name.'" data-modal-toggle="deleteModal" class="btnDeleteDocType font-medium text-red-600 hover:underline cursor-pointer">Delete</a>
                                    </td>
                                </tr>
                                ';
                }
            }else{
                $output =   '
                                <tr class="bg-white border-b">
                                    <td colspan="4" class="py-4 px-6 text-center">No data.</td>
                                </tr>
                            ';
            }
            echo $output;
        }
    // DOC TYPE END



































    // ===================================================== D O C T Y P E - F O R M =====================================================

    public function getType(Request $request){
        $deptID = $request->formDept;

        $docTypes = DB::table('doc_types')->where('dept_id', $deptID)->orderBy('name', 'asc')->get();

        $output =   '<option style="display: none;" selected>Choose a Document Type</option>';

        foreach($docTypes as $docType){
            $output .= '
                            <option value="'.$docType->id.'">'.$docType->name.'</option>
                        ';
        }

        echo $output;
    }


    public function getforms(Request $request){
        $formType = $request->formType;
        $output =   '';
        $y = 1;

        $docTypeForms = DB::table('encode_forms')->where('doctype_id', $formType)->orderBy('id', 'asc')->get();

        if($docTypeForms->count() > 0){

            for($z = 1; $z <= 15; $z++){
                $colName1 = 'field'.$z.'_name';
                $colName3 = 'field'.$z.'_type';

                if($docTypeForms[0]->$colName1 != null){
                    $output .=  '
                                    <tr class="bg-white border-b">
                                        <td class="py-4 px-6">'.$y++.'</td>
                                        <td class="py-4 px-6">'.$docTypeForms[0]->$colName1.'</td>
                                        <td class="py-4 px-6">'.strtoupper($docTypeForms[0]->$colName3).'</td>
                                        <td class="py-4 px-6">
                                            <a type="button" data-id="'.$docTypeForms[0]->id.'" data-name="'.$docTypeForms[0]->$colName1.'" data-type="'.$docTypeForms[0]->$colName3.'" data-col="'.$z.'" data-modal-toggle="docTypeFormIndexModal" class="btnEditIndex font-medium text-blue-600 hover:underline cursor-pointer">Edit</a>
                                            <span> | </span>
                                            <a type="button" data-id="'.$docTypeForms[0]->id.'" data-name="'.$docTypeForms[0]->$colName1.'" data-type="'.$docTypeForms[0]->$colName3.'" data-col="'.$z.'" data-modal-toggle="deleteModal" class="btnDeleteIndex font-medium text-red-600 hover:underline cursor-pointer">Delete</a>
                                        </td>
                                    </tr>
                                ';
                }
            }
        }else{
            $output =   '
                            <tr class="bg-white border-b">
                                <td colspan="4" class="py-4 px-6 text-center">No Data.</td>
                            </tr>
                        ';
        }

        echo $output;
    }
        
    public function indexAdd(Request $request){
        $formType = $request->hdnFormType;
        $IndexName = $request->docTypeFormIndexName;
        $IndexType = $request->docTypeFormIndexType;
        $output = '';
        $y = 1;

        $request->validate([
            'docTypeFormIndexName' => 'required',
            'docTypeFormIndexType' => 'required',
        ]);

        $colVal1 = strtoupper($IndexName);
        $colVal2 = preg_replace('/[^\p{L}\p{N}\s]/u', '', strtolower(str_replace(' ', '', $IndexName)));
        $colVal3 = $IndexType;

        $docTypeForms = DB::table('encode_forms')->where('doctype_id', $formType)->orderBy('id', 'asc')->get();

        // echo $docTypeForms->count();

        if($docTypeForms->count() > 0){
            for($x = 1; $x <= 15; $x++){
                $colName1 = 'field'.$x.'_name';
                $colName2 = 'field'.$x.'_name_nospace';
                $colName3 = 'field'.$x.'_type';

                if($docTypeForms[0]->$colName1 === null){
                    DB::table('encode_forms')->where(['doctype_id' => $formType])->update([$colName1 => $colVal1, $colName2 => $colVal2, $colName3 => $colVal3]);
                    break;
                }
            }

        }else{
            $typeForm = New EncodeForm();
            $typeForm->doctype_id = $formType;
            $typeForm->field1_name = $colVal1;
            $typeForm->field1_name_nospace = $colVal2;
            $typeForm->field1_type = $colVal3;
            $typeForm->save();
        }
        
        $docTypeForms = DB::table('encode_forms')->where('doctype_id', $formType)->orderBy('id', 'asc')->get();

        for($z = 1; $z <= 15; $z++){
            $colName1 = 'field'.$z.'_name';
            $colName3 = 'field'.$z.'_type';

            if($docTypeForms[0]->$colName1 != null){
                $output .=  '
                                <tr class="bg-white border-b">
                                    <td class="py-4 px-6">'.$y++.'</td>
                                    <td class="py-4 px-6">'.$docTypeForms[0]->$colName1.'</td>
                                    <td class="py-4 px-6">'.strtoupper($docTypeForms[0]->$colName3).'</td>
                                    <td class="py-4 px-6">
                                        <a type="button" data-id="'.$docTypeForms[0]->id.'" data-name="'.$docTypeForms[0]->$colName1.'" data-type="'.$docTypeForms[0]->$colName3.'" data-col="'.$z.'" data-modal-toggle="docTypeFormIndexModal" class="btnEditIndex font-medium text-blue-600 hover:underline cursor-pointer">Edit</a>
                                        <span> | </span>
                                        <a type="button" data-id="'.$docTypeForms[0]->id.'" data-name="'.$docTypeForms[0]->$colName1.'" data-type="'.$docTypeForms[0]->$colName3.'" data-col="'.$z.'" data-modal-toggle="deleteModal" class="btnDeleteIndex font-medium text-red-600 hover:underline cursor-pointer">Delete</a>
                                    </td>
                                </tr>
                            ';
            }
        }

        echo $output;
    }

    public function indexEdit(Request $request){
        $editId = $request->hdnFormId;
        $formCol = $request->hdnFormCol;
        $IndexName = $request->docTypeFormIndexName;
        $IndexType = $request->docTypeFormIndexType;
        $output = '';
        $y = 1;

        $request->validate([
            'docTypeFormIndexName' => 'required',
            'docTypeFormIndexType' => 'required',
        ]);

        $colName1 = 'field'.$formCol.'_name';
        $colName2 = 'field'.$formCol.'_name_nospace';
        $colName3 = 'field'.$formCol.'_type';

        $colVal1 = strtoupper($IndexName);
        $colVal2 = preg_replace('/[^\p{L}\p{N}\s]/u', '', strtolower(str_replace(' ', '', $IndexName)));
        $colVal3 = $IndexType;

        DB::table('encode_forms')->where(['id' => $editId])->update([$colName1 => $colVal1, $colName2 => $colVal2, $colName3 => $colVal3]);

        $docTypeForms = DB::table('encode_forms')->where('id', $editId)->orderBy('id', 'asc')->get();

        for($z = 1; $z <= 15; $z++){
            $colName1 = 'field'.$z.'_name';
            $colName3 = 'field'.$z.'_type';

            if($docTypeForms[0]->$colName1 != null){
                $output .=  '
                                <tr class="bg-white border-b">
                                    <td class="py-4 px-6">'.$y++.'</td>
                                    <td class="py-4 px-6">'.$docTypeForms[0]->$colName1.'</td>
                                    <td class="py-4 px-6">'.strtoupper($docTypeForms[0]->$colName3).'</td>
                                    <td class="py-4 px-6">
                                        <a type="button" data-id="'.$docTypeForms[0]->id.'" data-name="'.$docTypeForms[0]->$colName1.'" data-type="'.$docTypeForms[0]->$colName3.'" data-col="'.$z.'" data-modal-toggle="docTypeFormIndexModal" class="btnEditIndex font-medium text-blue-600 hover:underline cursor-pointer">Edit</a>
                                        <span> | </span>
                                        <a type="button" data-id="'.$docTypeForms[0]->id.'" data-name="'.$docTypeForms[0]->$colName1.'" data-type="'.$docTypeForms[0]->$colName3.'" data-col="'.$z.'" data-modal-toggle="deleteModal" class="btnDeleteIndex font-medium text-red-600 hover:underline cursor-pointer">Delete</a>
                                    </td>
                                </tr>
                            ';
            }
        }

        echo $output;
    }

    public function indexDelete(Request $request){
        $deleteId = $request->hdnDeleteId;
        $formType = $request->hdnSelected1;
        $IndexCol = $request->hdnCol;

        $output = '';
        $y = 1;

        $colName1 = 'field'.$IndexCol.'_name';
        $colName2 = 'field'.$IndexCol.'_name_nospace';
        $colName3 = 'field'.$IndexCol.'_type';

        DB::table('encode_forms')->where(['id' => $deleteId])->update([$colName1 => null, $colName2 => null, $colName3 => null]);

        $docTypeForms = DB::table('encode_forms')->where('id', $deleteId)->orderBy('id', 'asc')->get();

        for($z = 1; $z <= 15; $z++){
            $colName1 = 'field'.$z.'_name';
            $colName3 = 'field'.$z.'_type';

            if($docTypeForms[0]->$colName1 != null){
                $output .=  '
                                <tr class="bg-white border-b">
                                    <td class="py-4 px-6">'.$y++.'</td>
                                    <td class="py-4 px-6">'.$docTypeForms[0]->$colName1.'</td>
                                    <td class="py-4 px-6">'.strtoupper($docTypeForms[0]->$colName3).'</td>
                                    <td class="py-4 px-6">
                                        <a type="button" data-id="'.$docTypeForms[0]->id.'" data-name="'.$docTypeForms[0]->$colName1.'" data-type="'.$docTypeForms[0]->$colName3.'" data-col="'.$z.'" data-modal-toggle="docTypeFormIndexModal" class="btnEditIndex font-medium text-blue-600 hover:underline cursor-pointer">Edit</a>
                                        <span> | </span>
                                        <a type="button" data-id="'.$docTypeForms[0]->id.'" data-name="'.$docTypeForms[0]->$colName1.'" data-type="'.$docTypeForms[0]->$colName3.'" data-col="'.$z.'" data-modal-toggle="deleteModal" class="btnDeleteIndex font-medium text-red-600 hover:underline cursor-pointer">Delete</a>
                                    </td>
                                </tr>
                            ';
            }
        }

        echo $output;
    }












































    // ====================================================== F O L D E R ======================================================

    public function getfolder(Request $request){
        $batchID = $request->batch;

        $batchFolders = DB::table('folder_lists')->where('batch_id', $batchID)->orderBy('id', 'desc')->get();

        $output =   '';

        foreach ($batchFolders as $batchFolder){
            $output .=  '
                            <tr class="bg-white border-b">
                                <td class="py-4 px-6">'.$batchFolder->name.'</td>
                                <td class="py-4 px-6">
                                    <a href="#" class="font-medium text-blue-600 hover:underline">Edit</a>
                                    <span> | </span>
                                    <a href="#" class="font-medium text-red-600 hover:underline">Delete</a>
                                </td>
                            </tr>
                        ';
        }

        echo $output;
    }

















    
    // ====================================================== D E P A R T M E N T S ======================================================

    public function deptAdd(Request $request){
        $request->validate([
            'deptName' => 'required',
        ]);

        $dept = New Department();
        $dept->name = strtoupper($request->deptName);
        $dept->save();


        $departments = DB::table('departments')->orderBy('id', 'asc')->get();

        $output = '';

        $x = 1;

        foreach ($departments as $department){
            $output .=  '
                            <tr class="bg-white border-b">
                                <td class="py-4 px-6">'.$x++.'</td>
                                <td class="py-4 px-6">'.$department->name.'</td>
                                <td class="py-4 px-6">
                                    <a type="button" data-id="'.$department->id.'" data-name="'.$department->name.'" class="btnEditThisDept font-medium text-blue-600 hover:underline cursor-pointer">Edit</a>
                                    <span> | </span>
                                    <a type="button" data-id="'.$department->id.'" data-name="'.$department->name.'" class="btnDeleteThisDept font-medium text-red-600 hover:underline cursor-pointer">Delete</a>
                                </td>
                            </tr>
                        ';
        }

        echo $output;
    }

    public function deptEdit(Request $request){
        $deptName = $request->deptName;
        $thisDeptId = $request->thisDeptId;

        $request->validate([
            'deptName' => 'required',
        ]);

        DB::update('update departments SET name = ? WHERE id = ?', [strtoupper($deptName), $thisDeptId]);


        $departments = DB::table('departments')->orderBy('id', 'asc')->get();

        $output = '';

        $x = 1;

        foreach ($departments as $department){
            $output .=  '
                            <tr class="bg-white border-b">
                                <td class="py-4 px-6">'.$x++.'</td>
                                <td class="py-4 px-6">'.$department->name.'</td>
                                <td class="py-4 px-6">
                                    <a type="button" data-id="'.$department->id.'" data-name="'.$department->name.'" class="btnEditThisDept font-medium text-blue-600 hover:underline cursor-pointer">Edit</a>
                                    <span> | </span>
                                    <a type="button" data-id="'.$department->id.'" data-name="'.$department->name.'" class="btnDeleteThisDept font-medium text-red-600 hover:underline cursor-pointer">Delete</a>
                                </td>
                            </tr>
                        ';
        }

        echo $output;
    }

    public function deptDelete(Request $request){
        $deptId = $request->hdnDeleteId;

        Department::where('id',$deptId)->delete();


        $departments = DB::table('departments')->orderBy('id', 'asc')->get();

        $output = '';

        $x = 1;

        foreach ($departments as $department){
            $output .=  '
                            <tr class="bg-white border-b">
                                <td class="py-4 px-6">'.$x++.'</td>
                                <td class="py-4 px-6">'.$department->name.'</td>
                                <td class="py-4 px-6">
                                    <a type="button" data-id="'.$department->id.'" data-name="'.$department->name.'" class="btnEditThisDept font-medium text-blue-600 hover:underline cursor-pointer">Edit</a>
                                    <span> | </span>
                                    <a type="button" data-id="'.$department->id.'" data-name="'.$department->name.'" class="btnDeleteThisDept font-medium text-red-600 hover:underline cursor-pointer">Delete</a>
                                </td>
                            </tr>
                        ';
        }

        echo $output;
    }

















    
    // ====================================================== U S E R S ======================================================

    public function userAdd(Request $request){
        $userName = ucwords(strtolower($request->userName));
        $userUsername = $request->userUsername;
        $userPass = $request->userPass;
        $userDept = $request->userDept;


        $request->validate([
            'userName' => 'required',
            'userUsername' => 'required',
            'userPass' => 'required_with:userPass_confirmation|same:userPass_confirmation',
            'userDept' => 'required'
        ]);


        $user = New Account();
        $user->name = $userName;
        $user->username = $userUsername;
        $user->password = Hash::make($userPass);
        $user->department = $userDept;
        $user->role = '0';
        $user->save();

        
        $users = DB::select('SELECT accounts.id, accounts.name, accounts.username, accounts.department AS deptid, departments.name AS department FROM (accounts INNER JOIN departments ON accounts.department = departments.id) WHERE accounts.id != "1"');

        $output = '';

        $x = 1;

        foreach ($users as $user){
            $output .=  '
                            <tr class="bg-white border-b">
                                <td class="py-4 px-6">'.$x++.'</td>
                                <td class="py-4 px-6">'.$user->name.'</td>
                                <td class="py-4 px-6">'.$user->username.'</td>
                                <td class="py-4 px-6">'.$user->department.'</td>
                                <td class="py-4 px-6">
                                    <a type="button" data-id="'.$user->id.'" data-name="'.$user->name.'" data-name="'.$user->username.'" data-dept="'.$user->deptid.'" class="btnEditThisUser font-medium text-blue-600 hover:underline cursor-pointer">Edit</a>
                                    <span> | </span>
                                    <a type="button" data-id="'.$user->id.'" data-name="'.$user->name.'" data-name="'.$user->username.'" class="btnDeleteThisUser font-medium text-red-600 hover:underline cursor-pointer">Delete</a>
                                </td>
                            </tr>
                        ';
        }

        echo $output;
    }

    public function userEdit(Request $request){
        $userName = ucwords(strtolower($request->userName));
        $userUsername = $request->userUsername;
        $userPass = $request->userPass;
        $userDept = $request->userDept;
        $thisUserId = $request->thisUserId;

        if($userPass == ''){
            $request->validate([
                'userName' => 'required',
                'userUsername' => 'required',
                'userDept' => 'required'
            ]);
            DB::update('update accounts SET name = ? , username = ? , department = ? WHERE id = ?', [$userName, $userUsername, $userDept, $thisUserId]);
        }else{
            $request->validate([
                'userName' => 'required',
                'userUsername' => 'required',
                'userPass' => 'required_with:userPass_confirmation|same:userPass_confirmation',
                'userDept' => 'required'
            ]);
            DB::update('update accounts SET name = ? , username = ? , password = ? , department = ? WHERE id = ?', [$userName, $userUsername, Hash::make($userPass), $userDept, $thisUserId]);
        }
        
        $users = DB::select('SELECT accounts.id, accounts.name, accounts.username, accounts.department AS deptid, departments.name AS department FROM (accounts INNER JOIN departments ON accounts.department = departments.id) WHERE accounts.id != "1"');

        $output = '';

        $x = 1;

        foreach ($users as $user){
            $output .=  '
                            <tr class="bg-white border-b">
                                <td class="py-4 px-6">'.$x++.'</td>
                                <td class="py-4 px-6">'.$user->name.'</td>
                                <td class="py-4 px-6">'.$user->username.'</td>
                                <td class="py-4 px-6">'.$user->department.'</td>
                                <td class="py-4 px-6">
                                    <a type="button" data-id="'.$user->id.'" data-name="'.$user->name.'" data-username="'.$user->username.'" data-dept="'.$user->deptid.'" class="btnEditThisUser font-medium text-blue-600 hover:underline cursor-pointer">Edit</a>
                                    <span> | </span>
                                    <a type="button" data-id="'.$user->id.'" data-name="'.$user->name.'" class="btnDeleteThisUser font-medium text-red-600 hover:underline cursor-pointer">Delete</a>
                                </td>
                            </tr>
                        ';
        }

        echo $output;
    }

    public function userDelete(Request $request){
        $userId = $request->hdnDeleteId;

        Account::where('id',$userId)->delete();
        
        $users = DB::select('SELECT accounts.id, accounts.name, accounts.username, accounts.department AS deptid, departments.name AS department FROM (accounts INNER JOIN departments ON accounts.department = departments.id) WHERE accounts.id != "1"');

        $output = '';

        $x = 1;

        foreach ($users as $user){
            $output .=  '
                            <tr class="bg-white border-b">
                                <td class="py-4 px-6">'.$x++.'</td>
                                <td class="py-4 px-6">'.$user->name.'</td>
                                <td class="py-4 px-6">'.$user->username.'</td>
                                <td class="py-4 px-6">'.$user->department.'</td>
                                <td class="py-4 px-6">
                                    <a type="button" data-id="'.$user->id.'" data-name="'.$user->name.'" data-username="'.$user->username.'" data-dept="'.$user->deptid.'" class="btnEditThisUser font-medium text-blue-600 hover:underline cursor-pointer">Edit</a>
                                    <span> | </span>
                                    <a type="button" data-id="'.$user->id.'" data-name="'.$user->name.'" class="btnDeleteThisUser font-medium text-red-600 hover:underline cursor-pointer">Delete</a>
                                </td>
                            </tr>
                        ';
        }

        echo $output;
    }


}
