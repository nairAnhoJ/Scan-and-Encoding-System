<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(){

        $user = auth()->user();
        $depts = DB::table('departments')->get();

        if($user->id == 1){
            $users = DB::table('accounts')->where('id', '!=', '1')->get();
            $docTypes = DB::table('doc_types')->get();
            $sbatches = DB::table('batches')->get();
        }else{
            $sbatches = DB::table('batches')->where('dept_id', $user->department)->get();
            $users = DB::table('accounts')->where('id', '!=', '1')->where('department', $user->department)->get();
            $docTypes = DB::table('doc_types')->where('dept_id', $user->department)->get();
        }


        $batchID = '0';
        $docTypeID = '0';
        $userID = '0';
        $uploadCount = '0';
        $EncodeCount = '0';
        $CheckedCount = '0';
        $maxArrayCount = '0';
        $fileDetailsArray = '';
        $dateStart = date('m-d-Y');
        $dateEnd = date('m-d-Y');


        return view('reports/index', compact('user', 'sbatches', 'depts', 'docTypes', 'users', 'docTypeID', 'batchID', 'userID', 'dateStart', 'dateEnd', 'uploadCount', 'EncodeCount', 'CheckedCount', 'fileDetailsArray', 'maxArrayCount'));
    }

    public function genReport(Request $request){
        $user = auth()->user();
        if($user->role == '1'){
            $deptID = $request->department;
        }else{
            $deptID = $user->department;
        }

        $batches = DB::table('batches')->get();
        $depts = DB::table('departments')->get();

        $dateStart = $request->startDate.' 00:00:00.000';
        $newDateStart = date("Y-m-d H:i:s", strtotime($dateStart));
        $dateEnd = $request->endDate.' 23:59:59';
        $newDateEnd = date("Y-m-d H:i:s", strtotime($dateEnd));
        $batchID = $request->batch;
        $docTypeID = $request->docType;
        $userID = $request->user;
        
        if($user->id == 1){
            $users = DB::table('accounts')->where('id', '!=', '1')->get();
            $docTypes = DB::table('doc_types')->get();
            $sbatches = DB::table('batches')->get();
        }else{
            $sbatches = DB::table('batches')->where('dept_id', $user->department)->get();
            $users = DB::table('accounts')->where('id', '!=', '1')->where('department', $user->department)->get();
            $docTypes = DB::table('doc_types')->where('dept_id', $user->department)->get();
        }

        if($batchID == '0'){
            $nbatchID = '%';
        }else{
            $nbatchID = $batchID;
        }
        if($docTypeID == '0'){
            $ndocTypeID = '%';
        }else{
            $ndocTypeID = $docTypeID;
        }
        if($userID == '0'){
            $nuserID = '%';
        }else{
            $nuserID = $userID;
        }
        if($deptID == '0'){
            $ndeptID = '%';
        }else{
            $ndeptID = $deptID;
        }

        $documents = DB::select('SELECT documents.id, documents.dept_id, departments.name AS department, batches.name AS batch, doc_types.name AS docType, documents.name, documents.is_Encoded, documents.is_Checked, documents.created_at, accounts.name AS uploader, file_details.field1, file_details.field2, file_details.field3, file_details.field4, file_details.field5, file_details.field6, file_details.field7, file_details.field8, file_details.field9, file_details.field10, file_details.field11, file_details.field12, file_details.field13, file_details.field14, file_details.field15 FROM (((((documents INNER JOIN departments ON documents.dept_id = departments.id) INNER JOIN batches ON documents.batch_id = batches.id) INNER JOIN doc_types ON documents.doctype_id = doc_types.id) INNER JOIN accounts ON documents.uploader = accounts.id) RIGHT JOIN file_details ON documents.id = file_details.document_id) WHERE documents.uploader LIKE ? AND documents.batch_id LIKE ? AND documents.doctype_id LIKE ? AND documents.dept_id LIKE ? AND documents.created_at BETWEEN CONVERT(?, DATETIME) AND CONVERT(?, DATETIME) ORDER BY documents.id DESC', [$nuserID, $nbatchID, $ndocTypeID, $ndeptID, $newDateStart, $newDateEnd]);

        $uploadCount = 0;
        $EncodeCount = 0;
        $CheckedCount = 0;

        foreach ($documents as $doc){
            $uploadCount++;
            if($doc->is_Encoded == 1){
                $EncodeCount++;
            }
            if($doc->is_Checked == 1){
                $CheckedCount++;
            }
        }

        return view('reports/index', compact('user', 'batches', 'sbatches', 'depts', 'docTypes', 'users', 'documents', 'dateStart', 'dateEnd', 'batchID', 'docTypeID', 'userID', 'uploadCount', 'EncodeCount', 'CheckedCount'));

    }

    public function reportGetBatch(Request $request){
        $deptID = $request->dept;
        if($deptID == '0'){
            $ndeptID = '%';
        }else{
            $ndeptID = $deptID;
        }

        $batches = DB::select('select * from batches where dept_id LIKE ? ORDER BY name asc', [$ndeptID]);
        $docTypes = DB::select('select * from doc_types where dept_id LIKE ? ORDER BY name asc', [$ndeptID]);
        $users = DB::select('select * from accounts where department LIKE ? AND id != 1 ORDER BY name asc', [$ndeptID]);

        $bOutput = "<option value='0'>All</option>";
        $dOutput = "<option value='0'>All</option>";
        $uOutput = "<option value='0'>All</option>";

        foreach($batches as $batch){
            $bOutput .= '<option value="'.$batch->id.'">'.$batch->name.'</option>';
        }
        
        foreach($docTypes as $docType){
            $dOutput .= '<option value="'.$docType->id.'">'.$docType->name.'</option>';
        }
        
        foreach($users as $user){
            $uOutput .= '<option value="'.$user->id.'">'.$user->name.'</option>';
        }

        $response = array(
            'batchOut' => $bOutput,
            'docTypeOut'  => $dOutput,
            'userOut'  => $uOutput
        );




        echo json_encode($response);
    }

    public function view(Request $request){
        $docID = $request->docID;
        $doc = DB::select('SELECT documents.id, documents.dept_id, departments.name AS dept_name, documents.batch_id, batches.name AS batch_name, documents.doctype_id, doc_types.name AS doctype_name, documents.name AS filename, documents.unique_name, documents.folder, accounts.name AS uploader, documents.created_at FROM documents INNER JOIN departments ON documents.dept_id = departments.id INNER JOIN batches ON documents.batch_id = batches.id INNER JOIN doc_types ON documents.doctype_id = doc_types.id INNER JOIN accounts ON documents.uploader = accounts.id WHERE documents.id = ?', [$docID]);

        $fileDetails = '';

        $detailTitles = DB::table('encode_forms')->where('doctype_id', $doc[0]->doctype_id)->get();
        $detailValues = DB::table('file_details')->where('document_id', $doc[0]->id)->get();

        $valuesCount = $detailValues->count();
        if($valuesCount > 0){
            for($x = 1; $x <= 15; $x++){
                $colName1 = 'field'.$x.'_name';
                $colVal = 'field'.$x;

                if($detailTitles[0]->$colName1 != null){
                    $fileDetails .= '<h1 class="font-semibold my-0">'.$detailTitles[0]->$colName1.'</h1><h1 class="ml-5 mt-0 mb-2">'.$detailValues[0]->$colVal.'</h1>';
                }
            }
        }else{
            for($x = 1; $x <= 15; $x++){
                $colName1 = 'field'.$x.'_name';
                $colVal = 'field'.$x;

                if($detailTitles[0]->$colName1 != null){
                    $fileDetails .= '<h1 class="font-semibold my-0">'.$detailTitles[0]->$colName1.'</h1><h1 class="ml-5 mt-0 mb-2">N/A</h1>';
                }
            }
        }

        $response = array(
            'DateUploadedOut' => $doc[0]->created_at,
            'DepartmentOut' => $doc[0]->dept_name,
            'BatchOut' => $doc[0]->batch_name,
            'DocTypeOut' => $doc[0]->doctype_name,
            'FilenameOut' => $doc[0]->filename,
            'UploaderOut' => $doc[0]->uploader,
            'FileSrcOut' => 'documents/'.$doc[0]->dept_id.'/'.$doc[0]->batch_id.'/'.$doc[0]->folder.'/'.$doc[0]->unique_name,
            'fileDetails' => $fileDetails,
        );

        echo json_encode($response);
    }
}
