<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Batch;
use App\Models\DocType;
use App\Models\Document;
use App\Models\DocumentManagement;
use App\Models\EncodeForm;
use App\Models\FileDetail;
use App\Models\FolderList;
use App\Models\TempFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class DocumentManagementController extends Controller
{

    // UPLOAD CONTROLLER

        public function uploadIndex(){
            $user = auth()->user()->id;
            $dept = auth()->user()->department;
            $tempLast = TempFile::all()->where('uploader',$user)->last();
            $tempCount = TempFile::all()->where('uploader',$user)->count();
            $temps = TempFile::all();
            $batchs = DB::table('batches')->where('dept_id', $dept)->orderby('name', 'asc')->get();
            $docTypes = DB::table('doc_types')->where('dept_id', $dept)->orderby('name', 'asc')->get();
            $allTemps = DB::table('temp_files')->where('uploader',$user)->get();

            return view('document-management/upload', compact('tempLast','tempCount','temps','batchs','docTypes', 'allTemps'));
        }

        public function uploadStore(Request $request){
            $user = auth()->user();
            $userId = auth()->user()->id;
            $temps = TempFile::all()->where('uploader',$userId);
            $folder = date('mdY');

            // $dirDoc = public_path().'/documents/'.$user->department.'/'.$request->batch.'/'.$request->docType.'/'.$folder;
            $dirDoc = 'F:/DMS/documents/'.$user->department.'/'.$request->batch.'/'.$request->docType.'/'.$folder;
            if (!file_exists($dirDoc)) {
                File::makeDirectory($dirDoc,077,true);
                DB::insert('insert into folder_lists (dept_id, batch_id, name) values (?, ?, ?)', [$user->department, $request->batch, $folder]);
            }

            $request->validate([
                'batch' => 'required',
                'docType' => 'required',
            ]);

            $folderID = (DB::table('folder_lists')->where('dept_id', $user->department)->where('batch_id', $request->batch)->where('name', $folder)->get())[0]->id;

            foreach($temps as $temp){
                // Save to Database
                $document = new Document();
                $document->dept_id = $user->department;
                $document->batch_id = $request->batch;
                $document->doctype_id = $request->docType;
                $document->name = $temp->name;
                $document->unique_name = $temp->unique_name;
                $document->is_Encoded = '0';
                $document->is_Checked = '0';
                $document->folder = $folderID;
                $document->uploader = $user->id;
                $document->save();

                $docID = (DB::table('documents')->latest('id')->first())->id;
                $fileDetails = new FileDetail();
                $fileDetails->document_id = $docID;
                $fileDetails->encoder = $userId;
                $fileDetails->save();

                // Move file from temporary to designated folder
                $filename = $temp->unique_name;
                $file = "temporary\\".$userId.'\\'.$filename;
                $f1 = str_replace('\\', '/', public_path($file));

                // File::move(public_path($file), public_path('F:/DMS/documents/'.$user->department.'/'.$request->batch.'/'.$request->docType.'/'.$folder.'/'.$filename));
                rename($f1, 'F:/DMS/documents/'.$user->department.'/'.$request->batch.'/'.$request->docType.'/'.$folder.'/'.$filename);
            }

            // Delete specific rows from temporary table in database
            TempFile::where('uploader',$userId)->delete();

            return redirect()->back();
        }

    // UPLOAD END







    // ENCODE CONTROLLER

        public function encodeIndex(){

            $user = auth()->user()->id;
            $dept = auth()->user()->department;
            $batchs = DB::table('batches')->where('dept_id', $dept)->orderby('name', 'asc')->get();
            $temps = TempFile::all()->where('uploader',$user);

            foreach($temps as $temp){
                $filename = $temp->unique_name;
                $file = 'temporary/'.$user.'/'.$filename;
                File::delete($file);
            }

            TempFile::where('uploader',$user)->delete();


            return view('document-management.encode', compact('batchs'));
        }

        public function encodeGetFolder(Request $request){
            $selBatch = $request->value;
            $folders = DB::table('folder_lists')->where('batch_id', $selBatch)->orderBy('id', 'desc')->get();
            $output = '<option selected style="display: none"></option>';

            foreach($folders as $folder){
                $output .= '<option value="'.$folder->id.'">'.$folder->name.'</option>';
            }
            echo $output;
        }

        public function encodeGetFiles(Request $request){
            $dept = auth()->user()->department;
            $userID = auth()->user()->id;
            $selBatch = $request->batchValue;
            $selFolder = $request->folderValue;
            $doneCount = Document::where('batch_id', $selBatch)->where('folder', $selFolder)->where('is_Encoded', 1)->count();
            // $totalCount = Document::where('batch_id', $selBatch)->where('folder', $selFolder)->count();

            $files = DB::select('SELECT documents.id, documents.doctype_id, documents.name, documents.unique_name, documents.is_Encoded, folder_lists.name AS folder FROM documents INNER JOIN folder_lists ON documents.folder = folder_lists.id WHERE documents.batch_id = ? AND documents.folder = ? ORDER BY documents.id DESC', [$selBatch, $selFolder]);

            $output = '';

            foreach($files as $file){
                $is_Encoded = $file->is_Encoded;
                
                if($is_Encoded > 0){
                    $textColor = 'text-green-500';
                }else{
                    $textColor = '';
                }

                // $output .= '<option value="'.$file->id.'" class="'.$textColor.'" data-filepath="documents/'.$dept.'/'.$selBatch.'/'.$file->doctype_id.'/'.$file->folder.'/'.$file->unique_name.'">'.$file->name.'</option>';
                $output .= '<option value="'.$file->id.'" class="'.$textColor.'" data-filepath="encoding/'.$userID.'/'.$file->unique_name.'">'.$file->name.'</option>';
            }

            $data = [
                'output' => $output,
                'doneCount' => $doneCount,
                'totalCount' => count($files),
            ];

            return response()->json($data);
        }

        public function encodeGetForm(Request $request){
            $selBatch = $request->batchValue;


            if($selBatch == ''){
                $output = '';
                echo $output;
            }else{

                $userID = auth()->user()->id;
                $selectedFile = $request->selFile[0];

                $dirView = public_path().'/encoding/'.$userID;
                if (!file_exists($dirView)) {
                    File::makeDirectory($dirView,077,true);
                }else{
                    File::deleteDirectory($dirView);
                    File::makeDirectory($dirView,077,true);
                }
        
                $thisDoc = DB::select('SELECT documents.id, documents.dept_id, documents.batch_id, documents.doctype_id, documents.unique_name, folder_lists.name AS folder FROM documents INNER JOIN folder_lists ON documents.folder = folder_lists.id WHERE documents.id = ?', [$selectedFile]);

                copy('F:/DMS/documents/'.$thisDoc[0]->dept_id.'/'.$thisDoc[0]->batch_id.'/'.$thisDoc[0]->doctype_id.'/'.$thisDoc[0]->folder.'/'.$thisDoc[0]->unique_name, public_path().'/encoding/'.$userID.'/'.$thisDoc[0]->unique_name);


                for($i=0; $i<45; $i++){
                    if(file_exists(public_path().'/encoding/'.$userID.'/'.$thisDoc[0]->unique_name)){
                        break;
                    }
                    sleep(1);
                }


                $details = DB::table('file_details')->where('document_id', $selectedFile)->orderBy('id', 'asc')->get();
                $docType = (DB::table('documents')->where('id', $selectedFile)->get())[0]->doctype_id;
                $forms = DB::table('encode_forms')->where('doctype_id', $docType)->orderBy('id', 'asc')->get();

                $output = '<input type="hidden" id="selFileVal" name="selFile" value="'.$selectedFile.'"></input>';

                if($details->count() > 0){
                    for($x = 1; $x <= 15; $x++){
                        $colName1 = 'field'.$x.'_name';
                        $colName2 = 'field'.$x.'_name_nospace';
                        $colName3 = 'field'.$x.'_type';
                        $colVal = 'field'.$x;

                        if($forms[0]->$colName1 != null){

                            if($forms[0]->$colName3 == 'date' || $forms[0]->$colName3 == 'DATE'){
                                if($details[0]->$colVal != null){
                                    $dateVal = date("Y-m-d", strtotime($details[0]->$colVal));
                                }else{
                                    $dateVal = null;
                                }

                                $output .= '
                                            <div class="mt-2">
                                                <label for="'.$forms[0]->$colName2.'" class="block text-sm font-medium text-sky-600">'.$forms[0]->$colName1.'</label>
                                                <input type="'.$forms[0]->$colName3.'" value="'.$dateVal.'" name="'.$forms[0]->$colName2.'" id="'.$forms[0]->$colName2.'" autocomplete="off" class="block w-full py-1 pl-3 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500">
                                            </div>
                                            ';
                            }else{
                                $output .= '
                                            <div class="mt-2">
                                                <label for="'.$forms[0]->$colName2.'" class="block text-sm font-medium text-sky-600">'.$forms[0]->$colName1.'</label>
                                                <input type="'.$forms[0]->$colName3.'" value="'.$details[0]->$colVal.'" name="'.$forms[0]->$colName2.'" id="'.$forms[0]->$colName2.'" autocomplete="off" class="block w-full py-1 pl-3 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500">
                                            </div>
                                            ';
                            }
                        }
                    }
                }else{
                    for($x = 1; $x <= 15; $x++){
                        $colName1 = 'field'.$x.'_name';
                        $colName2 = 'field'.$x.'_name_nospace';
                        $colName3 = 'field'.$x.'_type';

                        if($forms[0]->$colName1 != null){
                            $output .= '
                                        <div class="mt-2">
                                            <label for="'.$forms[0]->$colName2.'" class="block text-sm font-medium text-sky-600">'.$forms[0]->$colName1.'</label>
                                            <input type="'.$forms[0]->$colName3.'" value="" name="'.$forms[0]->$colName2.'" id="'.$forms[0]->$colName2.'" autocomplete="off" class="block w-full py-1 pl-3 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                        ';
                        }
                        
                    }
                }

                $output .= '<button type="submit" id="encodeSubmit" class="disabled:pointer-events-none disabled:opacity-75 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-10 py-1.5 mr-2 mb-2 mt-2 focus:outline-none">Save</button>
                ';

                echo $output;
            }
        }

        public function encodeStore(Request $request){
            $userId = auth()->user()->id;
            $selFile = $request->selFile;
            $details = DB::table('file_details')->where('document_id', $selFile)->orderBy('id', 'asc')->get();
            $thisFile = DB::table('documents')->where('id', $selFile)->get();
            $forms = DB::table('encode_forms')->where('doctype_id', $thisFile[0]->doctype_id)->orderBy('id', 'asc')->get();

            if($details->count() > 0){
                for($x = 1; $x <= 15; $x++){
                    $colName = "field{$x}_name_nospace";
                    $col = "field{$x}";
                    if($forms[0]->$colName != null){
                        $inputName = $forms[0]->$colName;
                        $inputVal = strtoupper($request->$inputName);
                        if($inputVal == ''){
                            $inputVal = null;
                        }
                        DB::table('file_details')->where(['document_id' => $selFile])->update([$col => $inputVal]);
                    }
                }
            }else{
                $fileDetails = new FileDetail();
                $fileDetails->document_id = $selFile;
                $fileDetails->encoder = $userId;
                for($x = 1; $x <= 15; $x++){
                    $colName = "field{$x}_name_nospace";
                    $col = "field{$x}";
                    if($forms[0]->$colName != null){
                        $inputName = $forms[0]->$colName;
                        $inputVal = $request->$inputName;
                        $fileDetails->$col = $inputVal;
                    }
                }
                $fileDetails->save();
            }

            DB::table('documents')->where(['id' => $selFile])->update(['is_Encoded' => 1]);

            echo    '   
                        <div id="toast-success" class="flex items-center p-4 mb-4 text-gray-500 bg-green-500 rounded-lg shadow w-80" role="alert">
                            <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg">
                                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                <span class="sr-only">Check icon</span>
                            </div>
                            <div class="ml-3 font-bold text-white text-md">Details Successfully Saved.</div>
                            <button type="button" id="succNotif" class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-600 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex h-8 w-8" data-dismiss-target="#toast-success" aria-label="Close">
                                <span class="sr-only">Close</span>
                                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                    ';
        }

    // ENCODE END






    // QC CONTROLLER

        public function qualityCheckIndex(){

            $user = auth()->user()->id;
            $dept = auth()->user()->department;
            $batchs = DB::table('batches')->where('dept_id', $dept)->orderby('name', 'asc')->get();
            $temps = TempFile::all()->where('uploader',$user);

            foreach($temps as $temp){
                $filename = $temp->unique_name;
                $file = 'temporary/'.$user.'/'.$filename;
                File::delete($file);
            }

            TempFile::where('uploader',$user)->delete();

            return view('document-management/quality-check', compact('batchs'));
        }

        public function qcGetFolder(Request $request){
            $selBatch = $request->value;
            $folders = DB::table('folder_lists')->where('batch_id', $selBatch)->orderBy('id', 'desc')->get();
            $output = '<option selected style="display: none"></option>';

            foreach($folders as $folder){
                $output .= '<option value="'.$folder->id.'">'.$folder->name.'</option>';
            }
            echo $output;
        }

        public function qcGetFiles(Request $request){
            $userID = auth()->user()->id;
            $selBatch = $request->batchValue;
            $selFolder = $request->folderValue;
            $files = DB::select('SELECT documents.id, documents.doctype_id, documents.name, documents.unique_name, documents.is_Checked, folder_lists.name AS folder FROM documents INNER JOIN folder_lists ON documents.folder = folder_lists.id WHERE documents.batch_id = ? AND documents.folder = ? ORDER BY documents.id DESC', [$selBatch, $selFolder]);

            $output = '';

            foreach($files as $file){
                if($file->is_Checked == 1){
                    $textColor = 'text-green-500';
                }else{
                    $textColor = '';
                }

                $output .= '<option value="'.$file->id.'" class="'.$textColor.'" data-filepath="checking/'.$userID.'/'.$file->unique_name.'">'.$file->name.'</option>';
            }
            echo $output;
        }

        public function qcGetForm(Request $request){
            $selBatch = $request->batchValue;

            if($selBatch == ''){
                $output = '';
                echo $output;
            }else{

                $userID = auth()->user()->id;
                $selectedFile = $request->selFile[0];

                $dirView = public_path().'/checking/'.$userID;
                if (!file_exists($dirView)) {
                    File::makeDirectory($dirView,077,true);
                }else{
                    File::deleteDirectory($dirView);
                    File::makeDirectory($dirView,077,true);
                }
        
                $thisDoc = DB::select('SELECT documents.id, documents.dept_id, documents.batch_id, documents.doctype_id, documents.unique_name, folder_lists.name AS folder FROM documents INNER JOIN folder_lists ON documents.folder = folder_lists.id WHERE documents.id = ?', [$selectedFile]);

                copy('F:/DMS/documents/'.$thisDoc[0]->dept_id.'/'.$thisDoc[0]->batch_id.'/'.$thisDoc[0]->doctype_id.'/'.$thisDoc[0]->folder.'/'.$thisDoc[0]->unique_name, public_path().'/checking/'.$userID.'/'.$thisDoc[0]->unique_name);


                for($i=0; $i<45; $i++){
                    if(file_exists(public_path().'/checking/'.$userID.'/'.$thisDoc[0]->unique_name)){
                        break;
                    }
                    sleep(1);
                }


                $selectedFile = $request->selFile[0];
                $details = DB::table('file_details')->where('document_id', $selectedFile)->orderBy('id', 'asc')->get();
                $docType = (DB::table('documents')->where('id', $selectedFile)->get())[0]->doctype_id;
                $forms = DB::table('encode_forms')->where('doctype_id', $docType)->orderBy('id', 'asc')->get();

                $output = '<input type="hidden" id="selFileVal" name="selFile" value="'.$selectedFile.'"></input>';

                if($details->count() > 0){
                    for($x = 1; $x <= 15; $x++){
                        $colName1 = 'field'.$x.'_name';
                        $colName2 = 'field'.$x.'_name_nospace';
                        $colName3 = 'field'.$x.'_type';
                        $colVal = 'field'.$x;

                        if($forms[0]->$colName1 != null){

                            if($forms[0]->$colName3 == 'date' || $forms[0]->$colName3 == 'DATE'){

                                $dateVal = date("Y-m-d", strtotime($details[0]->$colVal));
                                
                                $output .= '
                                        <div class="mt-2">
                                            <label for="'.$forms[0]->$colName2.'" class="block text-sm font-medium text-sky-600">'.$forms[0]->$colName1.'</label>
                                            <input readonly type="'.$forms[0]->$colName3.'" value="'.$dateVal.'" name="'.$forms[0]->$colName2.'" id="'.$forms[0]->$colName2.'" autocomplete="off" class="block w-full py-1 pl-3 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                        ';
                            }else{
                                $output .= '
                                        <div class="mt-2">
                                            <label for="'.$forms[0]->$colName2.'" class="block text-sm font-medium text-sky-600">'.$forms[0]->$colName1.'</label>
                                            <input readonly type="'.$forms[0]->$colName3.'" value="'.$details[0]->$colVal.'" name="'.$forms[0]->$colName2.'" id="'.$forms[0]->$colName2.'" autocomplete="off" class="block w-full py-1 pl-3 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                        ';
                            }
                        }
                    }
                }else{
                    for($x = 1; $x <= 15; $x++){
                        $colName1 = 'field'.$x.'_name';
                        $colName2 = 'field'.$x.'_name_nospace';
                        $colName3 = 'field'.$x.'_type';

                        if($forms[0]->$colName1 != null){
                            $output .= '
                                        <div class="mt-2">
                                            <label for="'.$forms[0]->$colName2.'" class="block text-sm font-medium text-sky-600">'.$forms[0]->$colName1.'</label>
                                            <input readonly type="'.$forms[0]->$colName3.'" value="" name="'.$forms[0]->$colName2.'" id="'.$forms[0]->$colName2.'" autocomplete="off" class="block w-full py-1 pl-3 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                        ';
                        }
                        
                    }
                }

                $output .= '<button type="submit" id="qcSubmit" class="disabled:pointer-events-none disabled:opacity-75 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-10 py-1.5 mr-2 mb-2 mt-2 focus:outline-none">Check</button>
                ';

                echo $output;
            }
        }

        public function qcStore(Request $request){
            $selFile = $request->selFile;
            DB::update('update documents SET is_Checked = ? WHERE id = ?', ['1', $selFile]);

            echo    '   
                        <div id="toast-success" class="flex items-center p-4 mb-4 text-gray-500 bg-green-500 rounded-lg shadow w-80" role="alert">
                            <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg">
                                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                <span class="sr-only">Check icon</span>
                            </div>
                            <div class="ml-3 font-bold text-white text-md">Document Successfully Checked.</div>
                            <button type="button" id="succNotif" class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-600 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex h-8 w-8" data-dismiss-target="#toast-success" aria-label="Close">
                                <span class="sr-only">Close</span>
                                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                    ';
        }

    // QC END

}
