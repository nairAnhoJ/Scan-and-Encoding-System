<?php

namespace App\Http\Controllers;

use App\Models\TempFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;


class TempFileController extends Controller
{
    public function store(Request $request){

        $image = array();
        $user = auth()->user();

        $dirTemp = public_path().'/temporary';
        if (!file_exists($dirTemp)) {
            File::makeDirectory($dirTemp);
        }

        if($files = $request->file('file')){
            foreach($files as $file){
                $filename = $file->getClientOriginalName();
                $nameUnique = date('mdYHis').uniqid().'.'.$file->getClientOriginalExtension();
                $file->move('temporary', $nameUnique);

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
            $file = 'temporary/'.$filename;
            File::delete($file);
        }

        TempFile::where('uploader',$user)->delete();

        return redirect()->back();
    }
}
