<?php

namespace App\Http\Controllers;

use App\Models\TempFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class TempFileController extends Controller
{
    public function store(Request $request){
        ini_set('memory_limit', '1024M');


        $user = auth()->user();

        $dirTemp = public_path().'/temporary';
        if (!file_exists($dirTemp)) {
            File::makeDirectory($dirTemp,077,true);
        }
        

        // dd($request->file('file')[0]->getPathName());

        if($files = $request->file('file')){
            $docID = DB::table('documents')->get()->count();

            foreach($files as $file){
                $filename = $file->getClientOriginalName();
                $docID++;
                // $docIDLength = 10 - strlen($docID);
    
                // for($x = 1; $x <= $docIDLength; $x++){
                //     $docID = "0{$docID}";
                // }
                $nameUnique = date('Y').'_'.date('m').'_'.date('d').'_'.$docID.'.'.$file->getClientOriginalExtension();
                dd($nameUnique);
                // move_uploaded_file($file->getPathName(), "F:/DMS/".$nameUnique);
                // Storage::disk('dms')->put($nameUnique, $request->file('file'));
                $file->move('temporary/'.$user->id, $nameUnique);

                $temp = new TempFile();
                $temp->name = $filename;
                $temp->unique_name = $nameUnique;
                $temp->uploader = $user->id;
                $temp->save();
            }
        }

        return redirect()->back();
    }

    public function clear(){

        $user = auth()->user()->id;
        $temps = TempFile::all()->where('uploader',$user);

        foreach($temps as $temp){
            $filename = $temp->unique_name;
            $file = 'temporary/'.$user.'/'.$filename;
            File::delete($file);
        }

        TempFile::where('uploader',$user)->delete();

        return redirect()->back();
    }
}