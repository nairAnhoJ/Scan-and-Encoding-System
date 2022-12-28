<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if(auth()->user()->id == 1){
            $documents = DB::table('documents')->get();
        }else{
            $documents = DB::table('documents')->where('dept_id', auth()->user()->department)->get();
        }

        $docCount = count($documents);

        $uploadCount = 0;
        $EncodeCount = 0;
        $CheckedCount = 0;

        foreach ($documents as $doc){
            $uploadCount++;
            $docId = $doc->id;
            $fileCount = DB::table('file_details')->where('document_id', $docId)->get()->count();
            $fileDetails  = DB::table('file_details')->where('document_id', $docId)->get();
            // array_push($fileDetailsArray, $fileDetails);
            if($fileCount > 0){
                $EncodeCount++;
            }
            if($doc->is_Checked == 1){
                $CheckedCount++;
            }
        }


        return view('home', compact('uploadCount', 'EncodeCount', 'CheckedCount'));
    }
}
