<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ReportController extends Controller {

    public function index(Request $request) {
        $search = $request->input('search');
        if ($search == null) {
            $search = '';
        }
        $start = $request->input('start');
        $end = $request->input('end');
        $uploader = $request->input('uploader');
        $nStart = date('Y-m-d', strtotime($start));
        $nEnd = date('Y-m-d', strtotime($end . ' +1 day'));
        $user = auth()->user();

        if ($user->id == 1 || $user->department == 'ALL') {
            $users = DB::table('accounts')->where('id', '!=', '1')->where('viewing_only', '0')->get();

            if ($start == null || $start == '') {
                $documentsQuery = DB::table('file_details')
                    ->select('file_details.document_id', 'documents.id', 'documents.dept_id', 'departments.name AS department', 'batches.name AS batch', 'doc_types.name AS docType', 'documents.name', 'documents.is_Encoded', 'documents.is_Checked', 'documents.created_at')
                    ->join('documents', 'file_details.document_id', '=', 'documents.id')
                    ->join('departments', 'documents.dept_id', '=', 'departments.id')
                    ->join('batches', 'documents.batch_id', '=', 'batches.id')
                    ->join('doc_types', 'documents.doctype_id', '=', 'doc_types.id')
                    ->whereRaw("CONCAT_WS(' ', field1, field2, field3, field4, field5, field6, field7, field8, field9, field10, field11, field12, field13, field14, field15) LIKE '%{$search}%'")
                    ->orderBy('id', 'desc');
            } else {
                $documentsQuery = DB::table('file_details')
                    ->select('file_details.document_id', 'documents.id', 'documents.dept_id', 'departments.name AS department', 'batches.name AS batch', 'doc_types.name AS docType', 'documents.name', 'documents.is_Encoded', 'documents.is_Checked', 'documents.created_at')
                    ->join('documents', 'file_details.document_id', '=', 'documents.id')
                    ->join('departments', 'documents.dept_id', '=', 'departments.id')
                    ->join('batches', 'documents.batch_id', '=', 'batches.id')
                    ->join('doc_types', 'documents.doctype_id', '=', 'doc_types.id')
                    ->whereRaw("CONCAT_WS(' ', field1, field2, field3, field4, field5, field6, field7, field8, field9, field10, field11, field12, field13, field14, field15) LIKE '%{$search}%'")
                    ->whereBetween('documents.created_at', [$nStart, $nEnd])
                    ->orderBy('id', 'desc');

                if ($uploader != 0) {
                    $documentsQuery = $documentsQuery->where('documents.uploader', $uploader);
                }
            }
        } else {
            $users = DB::table('accounts')->where('id', '!=', '1')->where('department', $user->department)->where('viewing_only', '0')->get();

            if ($start == null || $start == '') {
                $documentsQuery = DB::table('file_details')
                    ->select('file_details.document_id', 'documents.id', 'documents.dept_id', 'departments.name AS department', 'batches.name AS batch', 'doc_types.name AS docType', 'documents.name', 'documents.is_Encoded', 'documents.is_Checked', 'documents.created_at')
                    ->join('documents', 'file_details.document_id', '=', 'documents.id')
                    ->join('departments', 'documents.dept_id', '=', 'departments.id')
                    ->join('batches', 'documents.batch_id', '=', 'batches.id')
                    ->join('doc_types', 'documents.doctype_id', '=', 'doc_types.id')
                    ->whereRaw("CONCAT_WS(' ', field1, field2, field3, field4, field5, field6, field7, field8, field9, field10, field11, field12, field13, field14, field15) LIKE '%{$search}%'")
                    ->where('documents.dept_id', auth()->user()->department)
                    ->orderBy('id', 'desc');
            } else {
                $documentsQuery = DB::table('file_details')
                    ->select('file_details.document_id', 'documents.id', 'documents.dept_id', 'departments.name AS department', 'batches.name AS batch', 'doc_types.name AS docType', 'documents.name', 'documents.is_Encoded', 'documents.is_Checked', 'documents.created_at')
                    ->join('documents', 'file_details.document_id', '=', 'documents.id')
                    ->join('departments', 'documents.dept_id', '=', 'departments.id')
                    ->join('batches', 'documents.batch_id', '=', 'batches.id')
                    ->join('doc_types', 'documents.doctype_id', '=', 'doc_types.id')
                    ->whereRaw("CONCAT_WS(' ', field1, field2, field3, field4, field5, field6, field7, field8, field9, field10, field11, field12, field13, field14, field15) LIKE '%{$search}%'")
                    ->whereBetween('documents.created_at', [$nStart, $nEnd])
                    ->where('documents.dept_id', auth()->user()->department)
                    ->orderBy('id', 'desc');


                if ($uploader != 0) {
                    $documentsQuery = $documentsQuery->where('documents.uploader', $uploader);
                }
            }
        }

        $documents = $documentsQuery->paginate(100);

        $documentCounts = $documents->total();
        $uploadCount = $documentCounts;
        $EncodeCount = $documentsQuery->where('is_Encoded', 1)->count();
        $CheckedCount = $documentsQuery->where('is_Checked', 1)->count();

        dd($documentsQuery);

        return view('reports/index', compact('documents', 'search', 'documentCounts', 'uploadCount', 'EncodeCount', 'CheckedCount', 'users', 'uploader', 'start', 'end'));
    }

    // public function paginate(Request $request, $page) {
    //     $search = $request->input('search');
    //     $start = $request->input('start');
    //     $end = $request->input('end');
    //     $uploader = $request->input('user');
    //     $nStart = date('Y-m-d', strtotime($start));
    //     $nEnd = date('Y-m-d', strtotime($end . ' +1 day'));
    //     $user = auth()->user();

    //     if (auth()->user()->id == 1 || $user->department == 'ALL') {
    //         $users = DB::table('accounts')->where('id', '!=', '1')->where('viewing_only', '0')->get();
    //         if ($start == null || $start == '') {
    //             $documents = DB::table('documents')
    //                 ->select('documents.id', 'documents.dept_id', 'departments.name AS department', 'batches.name AS batch', 'doc_types.name AS docType', 'documents.name', 'documents.is_Encoded', 'documents.is_Checked', 'documents.created_at')
    //                 ->join('departments', 'documents.dept_id', '=', 'departments.id')
    //                 ->join('batches', 'documents.batch_id', '=', 'batches.id')
    //                 ->join('doc_types', 'documents.doctype_id', '=', 'doc_types.id')
    //                 ->orderBy('id', 'desc')
    //                 ->paginate(100);

    //             $documentCounts = DB::table('documents')->count();
    //             $uploadCount = $documentCounts;
    //             $EncodeCount = DB::table('documents')->where('is_Encoded', 1)->count();
    //             $CheckedCount = DB::table('documents')->where('is_Checked', 1)->count();
    //         } else {
    //             if ($filterUser != 0) {
    //                 // $documents = DB::table('file_details')
    //                 //     ->select('file_details.document_id', 'documents.id', 'documents.dept_id', 'departments.name AS department', 'batches.name AS batch', 'doc_types.name AS docType', 'documents.name', 'documents.is_Encoded', 'documents.is_Checked', 'documents.created_at')
    //                 //     ->join('documents', 'file_details.document_id', '=', 'documents.id')
    //                 //     ->join('departments', 'documents.dept_id', '=', 'departments.id')
    //                 //     ->join('batches', 'documents.batch_id', '=', 'batches.id')
    //                 //     ->join('doc_types', 'documents.doctype_id', '=', 'doc_types.id')
    //                 //     ->whereRaw("CONCAT_WS(' ', field1, field2, field3, field4, field5, field6, field7, field8, field9, field10, field11, field12, field13, field14, field15) LIKE '%{$search}%'")
    //                 //     ->whereBetween('documents.created_at', [$nStart, $nEnd])
    //                 //     ->where('documents.uploader', $filterUser)
    //                 //     ->orderBy('id', 'desc')
    //                 //     ->paginate(100, '*', 'page', $page);

    //                 $documents = DB::table('documents')
    //                     ->select('documents.id', 'documents.dept_id', 'departments.name AS department', 'batches.name AS batch', 'doc_types.name AS docType', 'documents.name', 'documents.is_Encoded', 'documents.is_Checked', 'documents.created_at')
    //                     ->join('departments', 'documents.dept_id', '=', 'departments.id')
    //                     ->join('batches', 'documents.batch_id', '=', 'batches.id')
    //                     ->join('doc_types', 'documents.doctype_id', '=', 'doc_types.id')
    //                     ->whereBetween('documents.created_at', [$nStart, $nEnd])
    //                     ->where('uploader', $filterUser)
    //                     ->orderBy('id', 'desc')
    //                     ->paginate(100, '*', 'page', $page);

    //                 $documentCounts = DB::table('documents')->where('uploader', $filterUser)->whereBetween('documents.created_at', [$nStart, $nEnd])->count();
    //                 $uploadCount = $documentCounts;
    //                 $EncodeCount = DB::table('documents')->where('uploader', $filterUser)->where('is_Encoded', 1)->whereBetween('documents.created_at', [$nStart, $nEnd])->count();
    //                 $CheckedCount = DB::table('documents')->where('uploader', $filterUser)->where('is_Checked', 1)->whereBetween('documents.created_at', [$nStart, $nEnd])->count();
    //             } else {
    //                 $documents = DB::table('documents')
    //                     ->select('documents.id', 'documents.dept_id', 'departments.name AS department', 'batches.name AS batch', 'doc_types.name AS docType', 'documents.name', 'documents.is_Encoded', 'documents.is_Checked', 'documents.created_at')
    //                     ->join('departments', 'documents.dept_id', '=', 'departments.id')
    //                     ->join('batches', 'documents.batch_id', '=', 'batches.id')
    //                     ->join('doc_types', 'documents.doctype_id', '=', 'doc_types.id')
    //                     ->whereBetween('documents.created_at', [$nStart, $nEnd])
    //                     ->orderBy('id', 'desc')
    //                     ->paginate(100, '*', 'page', $page);

    //                 $documentCounts = DB::table('documents')->whereBetween('documents.created_at', [$nStart, $nEnd])->count();
    //                 $uploadCount = $documentCounts;
    //                 $EncodeCount = DB::table('documents')->where('is_Encoded', 1)->whereBetween('documents.created_at', [$nStart, $nEnd])->count();
    //                 $CheckedCount = DB::table('documents')->where('is_Checked', 1)->whereBetween('documents.created_at', [$nStart, $nEnd])->count();
    //             }
    //         }
    //     } else {
    //         $users = DB::table('accounts')->where('id', '!=', '1')->where('department', $user->department)->where('viewing_only', '0')->get();
    //         if ($start == null || $start == '') {

    //             $documents = DB::table('documents')
    //                 ->select('documents.id', 'documents.dept_id', 'departments.name AS department', 'batches.name AS batch', 'doc_types.name AS docType', 'documents.name', 'documents.is_Encoded', 'documents.is_Checked', 'documents.created_at')
    //                 ->join('departments', 'documents.dept_id', '=', 'departments.id')
    //                 ->join('batches', 'documents.batch_id', '=', 'batches.id')
    //                 ->join('doc_types', 'documents.doctype_id', '=', 'doc_types.id')
    //                 ->where('documents.dept_id', auth()->user()->department)
    //                 ->orderBy('id', 'desc')
    //                 ->paginate(100, '*', 'page', $page);

    //             $documentCounts = DB::table('documents')->where('documents.dept_id', auth()->user()->department)->count();
    //             $uploadCount = $documentCounts;
    //             $EncodeCount = DB::table('documents')->where('documents.dept_id', auth()->user()->department)->where('is_Encoded', 1)->count();
    //             $CheckedCount = DB::table('documents')->where('documents.dept_id', auth()->user()->department)->where('is_Checked', 1)->count();
    //         } else {
    //             if ($filterUser != 0) {
    //                 $documents = DB::table('documents')
    //                     ->select('documents.id', 'documents.dept_id', 'departments.name AS department', 'batches.name AS batch', 'doc_types.name AS docType', 'documents.name', 'documents.is_Encoded', 'documents.is_Checked', 'documents.created_at')
    //                     ->join('departments', 'documents.dept_id', '=', 'departments.id')
    //                     ->join('batches', 'documents.batch_id', '=', 'batches.id')
    //                     ->join('doc_types', 'documents.doctype_id', '=', 'doc_types.id')
    //                     ->where('documents.dept_id', auth()->user()->department)
    //                     ->where('uploader', $filterUser)
    //                     ->whereBetween('documents.created_at', [$nStart, $nEnd])
    //                     ->orderBy('id', 'desc')
    //                     ->paginate(100, '*', 'page', $page);

    //                 $documentCounts = DB::table('documents')->where('uploader', $filterUser)->whereBetween('documents.created_at', [$nStart, $nEnd])->where('documents.dept_id', auth()->user()->department)->count();
    //                 $uploadCount = $documentCounts;
    //                 $EncodeCount = DB::table('documents')->where('uploader', $filterUser)->whereBetween('documents.created_at', [$nStart, $nEnd])->where('documents.dept_id', auth()->user()->department)->where('is_Encoded', 1)->count();
    //                 $CheckedCount = DB::table('documents')->where('uploader', $filterUser)->whereBetween('documents.created_at', [$nStart, $nEnd])->where('documents.dept_id', auth()->user()->department)->where('is_Checked', 1)->count();
    //             } else {
    //                 $documents = DB::table('documents')
    //                     ->select('documents.id', 'documents.dept_id', 'departments.name AS department', 'batches.name AS batch', 'doc_types.name AS docType', 'documents.name', 'documents.is_Encoded', 'documents.is_Checked', 'documents.created_at')
    //                     ->join('departments', 'documents.dept_id', '=', 'departments.id')
    //                     ->join('batches', 'documents.batch_id', '=', 'batches.id')
    //                     ->join('doc_types', 'documents.doctype_id', '=', 'doc_types.id')
    //                     ->where('documents.dept_id', auth()->user()->department)
    //                     ->whereBetween('documents.created_at', [$nStart, $nEnd])
    //                     ->orderBy('id', 'desc')
    //                     ->paginate(100, '*', 'page', $page);

    //                 $documentCounts = DB::table('documents')->whereBetween('documents.created_at', [$nStart, $nEnd])->where('documents.dept_id', auth()->user()->department)->count();
    //                 $uploadCount = $documentCounts;
    //                 $EncodeCount = DB::table('documents')->whereBetween('documents.created_at', [$nStart, $nEnd])->where('documents.dept_id', auth()->user()->department)->where('is_Encoded', 1)->count();
    //                 $CheckedCount = DB::table('documents')->whereBetween('documents.created_at', [$nStart, $nEnd])->where('documents.dept_id', auth()->user()->department)->where('is_Checked', 1)->count();
    //             }
    //         }
    //     }

    //     $search = "";

    //     return view('reports/index', compact('documents', 'search', 'page', 'documentCounts', 'uploadCount', 'EncodeCount', 'CheckedCount', 'users', 'filterUser', 'start', 'end'));

    //     // $users = DB::table('users')->orderBy('name', 'asc')->paginate(100,'*','page',$page);
    //     // $userCount = DB::table('users')->get()->count();
    //     // $search = "";
    //     // return view('admin.system-management.users.index', compact('users', 'userCount', 'page', 'search'));
    // }

    // public function search($page, $search) {
    //     $user = auth()->user();

    //     if (auth()->user()->id == 1 || $user->department == 'ALL') {
    //         $users = DB::table('accounts')->where('id', '!=', '1')->where('viewing_only', '0')->get();
    //         $documents = DB::table('file_details')
    //             ->select('file_details.document_id', 'documents.id', 'documents.dept_id', 'departments.name AS department', 'batches.name AS batch', 'doc_types.name AS docType', 'documents.name', 'documents.is_Encoded', 'documents.is_Checked', 'documents.created_at')
    //             ->join('documents', 'file_details.document_id', '=', 'documents.id')
    //             ->join('departments', 'documents.dept_id', '=', 'departments.id')
    //             ->join('batches', 'documents.batch_id', '=', 'batches.id')
    //             ->join('doc_types', 'documents.doctype_id', '=', 'doc_types.id')
    //             ->whereRaw("CONCAT_WS(' ', field1, field2, field3, field4, field5, field6, field7, field8, field9, field10, field11, field12, field13, field14, field15) LIKE '%{$search}%'")
    //             ->orderBy('id', 'desc')
    //             ->paginate(100, '*', 'page', $page);

    //         $documentCounts = $documents->total();

    //         $uploadCount = $documents->total();

    //         $EncodeCount = DB::table('file_details')
    //             ->select('file_details.document_id', 'documents.id', 'documents.dept_id', 'departments.name AS department', 'batches.name AS batch', 'doc_types.name AS docType', 'documents.name', 'documents.is_Encoded', 'documents.is_Checked', 'documents.created_at')
    //             ->join('documents', 'file_details.document_id', '=', 'documents.id')
    //             ->join('departments', 'documents.dept_id', '=', 'departments.id')
    //             ->join('batches', 'documents.batch_id', '=', 'batches.id')
    //             ->join('doc_types', 'documents.doctype_id', '=', 'doc_types.id')
    //             ->whereRaw("CONCAT_WS(' ', field1, field2, field3, field4, field5, field6, field7, field8, field9, field10, field11, field12, field13, field14, field15) LIKE '%{$search}%'")
    //             ->where('is_Encoded', 1)
    //             ->count();

    //         $CheckedCount = DB::table('file_details')
    //             ->select('file_details.document_id', 'documents.id', 'documents.dept_id', 'departments.name AS department', 'batches.name AS batch', 'doc_types.name AS docType', 'documents.name', 'documents.is_Encoded', 'documents.is_Checked', 'documents.created_at')
    //             ->join('documents', 'file_details.document_id', '=', 'documents.id')
    //             ->join('departments', 'documents.dept_id', '=', 'departments.id')
    //             ->join('batches', 'documents.batch_id', '=', 'batches.id')
    //             ->join('doc_types', 'documents.doctype_id', '=', 'doc_types.id')
    //             ->whereRaw("CONCAT_WS(' ', field1, field2, field3, field4, field5, field6, field7, field8, field9, field10, field11, field12, field13, field14, field15) LIKE '%{$search}%'")
    //             ->where('is_Checked', 1)
    //             ->count();
    //     } else {
    //         $users = DB::table('accounts')->where('id', '!=', '1')->where('department', $user->department)->where('viewing_only', '0')->get();
    //         $documents = DB::table('file_details')
    //             ->select('file_details.document_id', 'documents.id', 'documents.dept_id', 'departments.name AS department', 'batches.name AS batch', 'doc_types.name AS docType', 'documents.name', 'documents.is_Encoded', 'documents.is_Checked', 'documents.created_at')
    //             ->join('documents', 'file_details.document_id', '=', 'documents.id')
    //             ->join('departments', 'documents.dept_id', '=', 'departments.id')
    //             ->join('batches', 'documents.batch_id', '=', 'batches.id')
    //             ->join('doc_types', 'documents.doctype_id', '=', 'doc_types.id')
    //             ->whereRaw("CONCAT_WS(' ', field1, field2, field3, field4, field5, field6, field7, field8, field9, field10, field11, field12, field13, field14, field15) LIKE '%{$search}%'")
    //             ->where('documents.dept_id', auth()->user()->department)
    //             ->orderBy('id', 'desc')
    //             ->paginate(100, '*', 'page', $page);

    //         $documentCounts = DB::table('file_details')
    //             ->select('file_details.document_id', 'documents.id', 'documents.dept_id', 'departments.name AS department', 'batches.name AS batch', 'doc_types.name AS docType', 'documents.name', 'documents.is_Encoded', 'documents.is_Checked', 'documents.created_at')
    //             ->join('documents', 'file_details.document_id', '=', 'documents.id')
    //             ->join('departments', 'documents.dept_id', '=', 'departments.id')
    //             ->join('batches', 'documents.batch_id', '=', 'batches.id')
    //             ->join('doc_types', 'documents.doctype_id', '=', 'doc_types.id')
    //             ->whereRaw("CONCAT_WS(' ', field1, field2, field3, field4, field5, field6, field7, field8, field9, field10, field11, field12, field13, field14, field15) LIKE '%{$search}%'")
    //             ->where('documents.dept_id', auth()->user()->department)
    //             ->count();

    //         $uploadCount = $documentCounts;

    //         $EncodeCount = DB::table('file_details')
    //             ->select('file_details.document_id', 'documents.id', 'documents.dept_id', 'departments.name AS department', 'batches.name AS batch', 'doc_types.name AS docType', 'documents.name', 'documents.is_Encoded', 'documents.is_Checked', 'documents.created_at')
    //             ->join('documents', 'file_details.document_id', '=', 'documents.id')
    //             ->join('departments', 'documents.dept_id', '=', 'departments.id')
    //             ->join('batches', 'documents.batch_id', '=', 'batches.id')
    //             ->join('doc_types', 'documents.doctype_id', '=', 'doc_types.id')
    //             ->whereRaw("CONCAT_WS(' ', field1, field2, field3, field4, field5, field6, field7, field8, field9, field10, field11, field12, field13, field14, field15) LIKE '%{$search}%'")
    //             ->where('documents.dept_id', auth()->user()->department)
    //             ->where('is_Encoded', 1)
    //             ->count();

    //         $CheckedCount = DB::table('file_details')
    //             ->select('file_details.document_id', 'documents.id', 'documents.dept_id', 'departments.name AS department', 'batches.name AS batch', 'doc_types.name AS docType', 'documents.name', 'documents.is_Encoded', 'documents.is_Checked', 'documents.created_at')
    //             ->join('documents', 'file_details.document_id', '=', 'documents.id')
    //             ->join('departments', 'documents.dept_id', '=', 'departments.id')
    //             ->join('batches', 'documents.batch_id', '=', 'batches.id')
    //             ->join('doc_types', 'documents.doctype_id', '=', 'doc_types.id')
    //             ->whereRaw("CONCAT_WS(' ', field1, field2, field3, field4, field5, field6, field7, field8, field9, field10, field11, field12, field13, field14, field15) LIKE '%{$search}%'")
    //             ->where('documents.dept_id', auth()->user()->department)
    //             ->where('is_Checked', 1)
    //             ->count();
    //     }

    //     $start = "";
    //     $filterUser = "";
    //     $end = "";

    //     return view('reports/index', compact('documents', 'search', 'page', 'documentCounts', 'uploadCount', 'EncodeCount', 'CheckedCount', 'users', 'filterUser', 'start', 'end'));
    // }


















    public function reportGetBatch(Request $request) {
        $deptID = $request->dept;
        if ($deptID == '0') {
            $ndeptID = '%';
        } else {
            $ndeptID = $deptID;
        }

        $batches = DB::select('select * from batches where dept_id LIKE ? ORDER BY name asc', [$ndeptID]);
        $docTypes = DB::select('select * from doc_types where dept_id LIKE ? ORDER BY name asc', [$ndeptID]);
        $users = DB::select('select * from accounts where department LIKE ? AND id != 1 ORDER BY name asc', [$ndeptID]);

        $bOutput = "<option value='0'>All</option>";
        $dOutput = "<option value='0'>All</option>";
        $uOutput = "<option value='0'>All</option>";

        foreach ($batches as $batch) {
            $bOutput .= '<option value="' . $batch->id . '">' . $batch->name . '</option>';
        }

        foreach ($docTypes as $docType) {
            $dOutput .= '<option value="' . $docType->id . '">' . $docType->name . '</option>';
        }

        foreach ($users as $user) {
            $uOutput .= '<option value="' . $user->id . '">' . $user->name . '</option>';
        }

        $response = array(
            'batchOut' => $bOutput,
            'docTypeOut'  => $dOutput,
            'userOut'  => $uOutput,
        );




        echo json_encode($response);
    }














    public function view(Request $request) {
        $docID = $request->docID;
        $userID = auth()->user()->id;
        $doc = DB::select('SELECT documents.id, documents.dept_id, departments.name AS dept_name, documents.batch_id, batches.name AS batch_name, documents.doctype_id, doc_types.name AS doctype_name, documents.name AS filename, documents.unique_name, folder_lists.name AS folder, accounts.name AS uploader, documents.created_at FROM documents INNER JOIN departments ON documents.dept_id = departments.id INNER JOIN batches ON documents.batch_id = batches.id INNER JOIN doc_types ON documents.doctype_id = doc_types.id INNER JOIN accounts ON documents.uploader = accounts.id INNER JOIN folder_lists ON documents.folder = folder_lists.id WHERE documents.id = ?', [$docID]);

        $fileDetails = '';

        $detailTitles = DB::table('encode_forms')->where('doctype_id', $doc[0]->doctype_id)->get();
        $detailValues = DB::table('file_details')->where('document_id', $doc[0]->id)->get();

        $valuesCount = $detailValues->count();
        if ($valuesCount > 0) {
            for ($x = 1; $x <= 15; $x++) {
                $colName1 = 'field' . $x . '_name';
                $colVal = 'field' . $x;

                if ($detailTitles[0]->$colName1 != null) {
                    $fileDetails .= '<h1 class="my-0 font-semibold">' . $detailTitles[0]->$colName1 . '</h1><h1 class="mt-0 mb-2 ml-5">' . $detailValues[0]->$colVal . '</h1>';
                }
            }
        } else {
            for ($x = 1; $x <= 15; $x++) {
                $colName1 = 'field' . $x . '_name';
                $colVal = 'field' . $x;

                if ($detailTitles[0]->$colName1 != null) {
                    $fileDetails .= '<h1 class="my-0 font-semibold">' . $detailTitles[0]->$colName1 . '</h1><h1 class="mt-0 mb-2 ml-5">N/A</h1>';
                }
            }
        }

        $dirView = public_path() . '/viewing/' . $userID;
        if (!file_exists($dirView)) {
            File::makeDirectory($dirView, 077, true);
        } else {
            File::deleteDirectory($dirView);
            File::makeDirectory($dirView, 077, true);
        }
        copy('F:/DMS/documents/' . $doc[0]->dept_id . '/' . $doc[0]->batch_id . '/' . $doc[0]->doctype_id . '/' . $doc[0]->folder . '/' . $doc[0]->unique_name, public_path() . '/viewing/' . $userID . '/' . $doc[0]->unique_name);

        for ($i = 0; $i < 45; $i++) {
            if (file_exists(public_path() . '/viewing/' . $userID . '/' . $doc[0]->unique_name)) {
                break;
            }
            sleep(1);
        }

        $response = array(
            'DateUploadedOut' => $doc[0]->created_at,
            'DepartmentOut' => $doc[0]->dept_name,
            'BatchOut' => $doc[0]->batch_name,
            'DocTypeOut' => $doc[0]->doctype_name,
            'FilenameOut' => $doc[0]->filename,
            'UploaderOut' => $doc[0]->uploader,
            'FileSrcOut' => url('/') . '/viewing/' . $userID . '/' . $doc[0]->unique_name,
            'fileDetails' => $fileDetails,
        );

        echo json_encode($response);
    }
}
