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
        $tempLast = TempFile::all()->where('uploader',$user)->last();
        $tempCount = TempFile::all()->where('uploader',$user)->count();
        $temps = TempFile::all();
        $batchs = Batch::orderby('name', 'asc')->get();
        $docTypes = DocType::orderby('name', 'asc')->get();

        return view('document-management/upload', compact('tempLast','tempCount','temps','batchs','docTypes'));
    }

    public function uploadStore(Request $request){

        $user = auth()->user();
        $userId = auth()->user()->id;
        $tempLast = TempFile::all()->last();
        $temps = TempFile::all()->where('uploader',$userId);
        $batchID = $request->batch;
        $folderRow = DB::table('folder_lists')->where('batch_id', $batchID)->orderBy('id','desc')->take(1)->first();
        $folder = $folderRow->name;

        $request->validate([
            'batch' => 'required',
            'docType' => 'required',
        ]);

        foreach($temps as $temp){
            // Save to Database
            $document = new Document();
            $document->dept_id = $user->department;
            $document->batch_id = $request->batch;
            $document->doctype_id = $request->docType;
            $document->name = $temp->name;
            $document->unique_name = $temp->unique_name;
            $document->is_Checked = '0';
            $document->folder = $folder;
            $document->uploader = $user->id;
            $document->save();

            // Move file from temporary to designated folder
            $filename = $temp->unique_name;
            $file = 'temporary/'.$filename;
            File::move(public_path($file), public_path('documents/'.$request->batch.'/'.$folder.'/'.$filename));
        }

        // Delete specific rows from temporary table in database
        TempFile::where('uploader',$userId)->delete();

        return redirect()->back();
    }

    // UPLOAD END







    // ENCODE CONTROLLER

    public function encodeIndex(){

        $user = auth()->user()->id;
        $temps = TempFile::all()->where('uploader',$user);

        foreach($temps as $temp){
            $filename = $temp->unique_name;
            $file = 'temporary/'.$filename;
            File::delete($file);
        }

        TempFile::where('uploader',$user)->delete();

        $batchs = Batch::orderby('name', 'asc')->get();

        return view('document-management/encode', compact('batchs'));
    }

    public function encodeGetFolder(Request $request){
        $selBatch = $request->value;
        $folders = DB::table('folder_lists')->where('batch_id', $selBatch)->orderBy('name', 'desc')->get();
        $output = '<option selected style="display: none"></option>';

        foreach($folders as $folder){
            $output .= '<option value="'.$folder->name.'">'.$folder->name.'</option>';
        }
        echo $output;
    }

    public function encodeGetFiles(Request $request){
        $selBatch = $request->batchValue;
        $selFolder = $request->folderValue;
        $files = DB::table('documents')->where('batch_id', $selBatch)->where('folder', $selFolder)->orderBy('id', 'desc')->get();

        $output = '';

        foreach($files as $file){
            $fileID = $file->id;

            $fileCount = DB::table('file_details')->where('document_id', $fileID)->get()->count();

            if($fileCount > 0){
                $textColor = 'text-green-500';
            }else{
                $textColor = '';
            }

            $output .= '<option value="'.$file->id.'" class="'.$textColor.'" data-filepath="documents/'.$selBatch.'/'.$selFolder.'/'.$file->unique_name.'">'.$file->name.'</option>';
        }
        echo $output;
    }

    public function encodeGetForm(Request $request){
        $selBatch = $request->batchValue;

        if($selBatch == ''){
            $output = '';
            echo $output;
        }else{
            $selectedFile = $request->selFile[0];
            $details = DB::table('file_details')->where('document_id', $selectedFile)->orderBy('id', 'asc')->get();
            $thisFile = DB::table('documents')->where('id', $selectedFile)->get();
            $forms = DB::table('encode_forms')->where('doctype_id', $thisFile[0]->doctype_id)->orderBy('id', 'asc')->get();


            if($thisFile[0]->is_Checked == 1){
                $dis = 'disabled';
            }else{
                $dis = '';
            }

            $detailsCount = $details->count();

            if($detailsCount > 0){
                $output = ' <input type="hidden" name="selBatch" value="'.$selBatch.'"></input>
                            <input type="hidden" id="selFileVal" name="selFile" value="'.$selectedFile.'"></input>';
                $x = 0;
                foreach($forms as $form){
                    if(isset($details[$x]->response)){
                        $detailVal = $details[$x]->response;
                    }else{
                        $detailVal = '';
                    }
                    $output .= '
                                <div class="mt-2">
                                    <label for="'.$form->name.'" class="block text-sm font-medium text-sky-600">'.$form->name.'</label>
                                    <input type="'.$form->type.'" value="'.$detailVal.'" name="'.$form->name_nospace.'" id="'.$form->name_nospace.'" class="block py-1 pl-3 w-full text-gray-900 bg-gray-50 rounded-lg border border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                ';
                    $x++;
                }
                $output .= '<button '.$dis.' type="submit" id="encodeSubmit" class="disabled:pointer-events-none disabled:opacity-75 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-10 py-1.5 mr-2 mb-2 mt-2 focus:outline-none">Update</button>
                ';
            }else{
                $output = ' <input type="hidden" name="selBatch" value="'.$selBatch.'"></input>
                            <input type="hidden" id="selFileVal" name="selFile" value="'.$selectedFile.'"></input>';

                foreach($forms as $form){
                    $output .= '
                                <div class="mt-2">
                                    <label for="'.$form->name.'" class="block text-sm font-medium text-sky-600">'.$form->name.'</label>
                                    <input type="'.$form->type.'" name="'.$form->name_nospace.'" id="'.$form->name_nospace.'" class="block py-1 pl-3 w-full text-gray-900 bg-gray-50 rounded-lg border border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                ';
                }
                $output .= '<button '.$dis.' type="button" id="encodeSubmit" class="disabled:pointer-events-none disabled:opacity-75 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-10 py-1.5 mr-2 mb-2 mt-2 focus:outline-none">Save</button>
                ';
            }
            echo $output;
        }
    }

    public function encodeStore(Request $request){
        $userId = auth()->user()->id;
        $selBatch = $request->selBatch;
        $selFile = $request->selFile;
        $details = DB::table('file_details')->where('document_id', $selFile)->orderBy('id', 'asc')->get();
        $detailsCount = $details->count();
        $thisFile = DB::table('documents')->where('id', $selFile)->get();
        $forms = DB::table('encode_forms')->where('doctype_id', $thisFile[0]->doctype_id)->orderBy('id', 'asc')->get();

        foreach($forms as $form){
            $nospace = $form->name_nospace;
            $request->validate([
                $nospace => 'required',
            ]);
        }

        if($detailsCount > 0){
            
            FileDetail::where('document_id',$selFile)->delete();

            foreach($forms as $form){
                $nospace = $form->name_nospace;
                $fileDetail = new FileDetail();
                $fileDetail->document_id = $selFile;
                $fileDetail->form_id = $form->id;
                $fileDetail->response = $request->$nospace;
                $fileDetail->encoder = $userId;
                $fileDetail->save();
            }
        }else{
            foreach($forms as $form){
                $nospace = $form->name_nospace;
                $fileDetail = new FileDetail();
                $fileDetail->document_id = $selFile;
                $fileDetail->form_id = $form->id;
                $fileDetail->response = $request->$nospace;
                $fileDetail->encoder = $userId;
                $fileDetail->save();
            }
        }

        echo    '   
                    <div id="toast-success" class="flex items-center p-4 mb-4 w-80 text-gray-500 bg-green-500 rounded-lg shadow" role="alert">
                        <div class="inline-flex flex-shrink-0 justify-center items-center w-8 h-8 text-green-500 bg-green-100 rounded-lg">
                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                            <span class="sr-only">Check icon</span>
                        </div>
                        <div class="ml-3 text-md font-bold text-white">Details Successfully Saved.</div>
                        <button type="button" id="succNotif" class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-600 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex h-8 w-8" data-dismiss-target="#toast-success" aria-label="Close">
                            <span class="sr-only">Close</span>
                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                ';

        // return redirect()->back();
    }

    // ENCODE END






    // QC CONTROLLER

    public function qualityCheckIndex(){

        $user = auth()->user()->id;
        $temps = TempFile::all()->where('uploader',$user);

        foreach($temps as $temp){
            $filename = $temp->unique_name;
            $file = 'temporary/'.$filename;
            File::delete($file);
        }

        TempFile::where('uploader',$user)->delete();

        $batchs = Batch::orderby('name', 'asc')->get();
        return view('document-management/quality-check', compact('batchs'));
    }

    public function qcGetFolder(Request $request){
        $selBatch = $request->value;
        // $batchs = Batch::orderby('name', 'asc')->get();
        $folders = DB::table('folder_lists')->where('batch_id', $selBatch)->orderBy('name', 'desc')->get();
        $output = '<option selected style="display: none"></option>';

        foreach($folders as $folder){
            $output .= '<option value="'.$folder->name.'">'.$folder->name.'</option>';
        }
        echo $output;
    }

    public function qcGetFiles(Request $request){
        $selBatch = $request->batchValue;
        $selFolder = $request->folderValue;
        $files = DB::table('documents')->where('batch_id', $selBatch)->where('folder', $selFolder)->orderBy('id', 'desc')->get();

        $output = '';

        foreach($files as $file){
            if($file->is_Checked == 1){
                $textColor = 'text-green-500';
            }else{
                $textColor = '';
            }

            $output .= '<option value="'.$file->id.'" class="'.$textColor.'" data-filepath="documents/'.$selBatch.'/'.$selFolder.'/'.$file->unique_name.'">'.$file->name.'</option>';
        }
        echo $output;
    }

    public function qcGetForm(Request $request){
        $selBatch = $request->batchValue;

        if($selBatch == ''){
            $output = '';
            echo $output;
        }else{
            $selectedFile = $request->selFile[0];
            // $forms = DB::table('encode_forms')->where('batch_id', $selBatch)->orderBy('id', 'asc')->get();
            $details = DB::table('file_details')->where('document_id', $selectedFile)->orderBy('id', 'asc')->get();
            $document = DB::table('documents')->where('id', $selectedFile)->get();
            $forms = DB::table('encode_forms')->where('doctype_id', $document[0]->doctype_id)->orderBy('id', 'asc')->get();
            $isCheckDoc = $document[0]->is_Checked;
            // echo $details[0];

            if($isCheckDoc == 1){
                $ddd = 'disabled';
            }else{
                $ddd = '';
            }
            $detailsCount = $details->count();

            if($detailsCount > 0){
                $output = ' <input type="hidden" name="selBatch" value="'.$selBatch.'"></input>
                            <input type="hidden" id="selFileVal" name="selFile" value="'.$selectedFile.'"></input>';
                $x = 0;
                foreach($forms as $form){
                    if(isset($details[$x]->response)){
                        $detailVal = $details[$x]->response;
                    }else{
                        $detailVal = '';
                    }
                    $output .= '
                                <div class="mt-2">
                                    <label for="'.$form->name.'" class="block text-sm font-medium text-sky-600">'.$form->name.'</label>
                                    <input readonly type="'.$form->type.'" value="'.$detailVal.'" name="'.$form->name_nospace.'" id="'.$form->name_nospace.'" class="block py-1 pl-3 w-full text-gray-900 bg-gray-50 rounded-lg border border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                ';
                    $x++;
                }
                $output .= '<button '.$ddd.' type="submit" id="qcSubmit" class="disabled:pointer-events-none disabled:opacity-75 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-10 py-1.5 mr-2 mb-2 mt-2 focus:outline-none">Check</button>
                ';
            }else{
                $output = ' <input type="hidden" name="selBatch" value="'.$selBatch.'"></input>
                            <input type="hidden" id="selFileVal" name="selFile" value="'.$selectedFile.'"></input>';

                foreach($forms as $form){
                    $output .= '
                                <div class="mt-2">
                                    <label for="'.$form->name.'" class="block text-sm font-medium text-sky-600">'.$form->name.'</label>
                                    <input readonly type="'.$form->type.'" name="'.$form->name_nospace.'" id="'.$form->name_nospace.'" class="block py-1 pl-3 w-full text-gray-900 bg-gray-50 rounded-lg border border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                ';
                }
                $output .= '<button type="button" id="qcSubmit" class="disabled:pointer-events-none disabled:opacity-75 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-10 py-1.5 mr-2 mb-2 mt-2 focus:outline-none">Check</button>
                ';
            }
            echo $output;
        }
    }

    public function qcStore(Request $request){
        $selFile = $request->selFile;
        DB::update('update documents SET is_Checked = ? WHERE id = ?', ['1', $selFile]);

        echo    '   
                    <div id="toast-success" class="flex items-center p-4 mb-4 w-80 text-gray-500 bg-green-500 rounded-lg shadow" role="alert">
                        <div class="inline-flex flex-shrink-0 justify-center items-center w-8 h-8 text-green-500 bg-green-100 rounded-lg">
                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                            <span class="sr-only">Check icon</span>
                        </div>
                        <div class="ml-3 text-md font-bold text-white">Document Successfully Checked.</div>
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






    // VIEW CONTROLLER

    public function viewIndex(){

        $user = auth()->user()->id;
        $temps = TempFile::all()->where('uploader',$user);

        foreach($temps as $temp){
            $filename = $temp->unique_name;
            $file = 'temporary/'.$filename;
            File::delete($file);
        }

        TempFile::where('uploader',$user)->delete();

        return view('document-management/view');
    }

    // VIEW END




    

}
