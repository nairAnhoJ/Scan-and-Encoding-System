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
            $uploadCount = (DB::table('documents')->get())->count();
            $EncodeCount = (DB::table('documents')->where('is_Encoded', 1)->get())->count();
            $CheckedCount = (DB::table('documents')->where('is_Checked', 1)->get())->count();
        }else{
            $uploadCount = (DB::table('documents')->where('dept_id', auth()->user()->department)->get())->count();
            $EncodeCount = (DB::table('documents')->where('dept_id', auth()->user()->department)->where('is_Encoded', 1)->get())->count();
            $CheckedCount = (DB::table('documents')->where('dept_id', auth()->user()->department)->where('is_Checked', 1)->get())->count();
        }

        return view('home', compact('uploadCount', 'EncodeCount', 'CheckedCount'));
    }
}
