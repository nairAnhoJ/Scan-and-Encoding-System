<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(){

        $batches = DB::table('batches')->get();
        $docTypes = DB::table('doc_types')->get();
        $users = DB::table('accounts')->where('id', '!=', '1')->get();
        $batchID = '0';
        $docTypeID = '0';
        $userID = '0';
        $uploadCount = '0';
        $EncodeCount = '0';
        $CheckedCount = '0';
        $dateStart = date('m-d-Y');
        $dateEnd = date('m-d-Y');

        return view('reports/index', compact('batches', 'docTypes', 'users', 'docTypeID', 'batchID', 'userID', 'dateStart', 'dateEnd', 'uploadCount', 'EncodeCount', 'CheckedCount'));
    }

    public function genReport(Request $request){

        $batches = DB::table('batches')->get();
        $docTypes = DB::table('doc_types')->get();
        $users = DB::table('accounts')->where('id', '!=', '1')->get();

        $dateStart = $request->startDate;
        $newDateStart = date("Y-m-d", strtotime($dateStart));  
        $dateEnd = $request->endDate;
        $nEndDate = $dateEnd.' 23:59:59';
        $newDateEnd = date("Y-m-d H:i:s", strtotime($nEndDate));  
        $batchID = $request->batch;
        $docTypeID = $request->docType;
        $userID = $request->user;

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

        $documents = DB::select('SELECT documents.id, departments.name AS department, batches.name AS batch, doc_types.name AS docType, documents.name, documents.is_Checked, documents.created_at, accounts.name AS uploader FROM ((((documents INNER JOIN departments ON documents.dept_id = departments.id) INNER JOIN batches ON documents.batch_id = batches.id) INNER JOIN doc_types ON documents.doctype_id = doc_types.id) INNER JOIN accounts ON documents.uploader = accounts.id) WHERE documents.uploader LIKE ? AND documents.batch_id LIKE ? AND documents.doctype_id LIKE ? AND documents.created_at BETWEEN ? AND ? ORDER BY documents.id DESC', [$nuserID, $nbatchID, $ndocTypeID, $newDateStart, $newDateEnd]);

        $uploadCount = 0;
        $EncodeCount = 0;
        $CheckedCount = 0;
        foreach ($documents as $doc){
            $uploadCount++;
            $docId = $doc->id;
            $fileCount = DB::table('file_details')->where('document_id', $docId)->get()->count();
            if($fileCount > 0){
                $EncodeCount++;
            }
            if($doc->is_Checked == 1){
                $CheckedCount++;
            }
        }

        return view('reports/index', compact('batches', 'docTypes', 'users', 'documents', 'dateStart', 'dateEnd', 'batchID', 'docTypeID', 'userID', 'uploadCount', 'EncodeCount', 'CheckedCount'));

    }
}
