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
        $totalUploadCount = DB::select('SELECT COUNT(*) FROM documents');
        $totalEncodeCount = DB::select('SELECT COUNT(*) FROM documents WHERE is_Encoded = ?', [1]);
        $totalCheckedCount = DB::select('SELECT COUNT(*) FROM documents WHERE is_Checked = ?', [1]);

        // if(auth()->user()->id == 1){
        //     $uploadCount = count((DB::select("SELECT * FROM documents WHERE created_at >= (LAST_DAY(NOW()) + INTERVAL 1 DAY - INTERVAL 1 MONTH) AND created_at <  (LAST_DAY(NOW()) + INTERVAL 1 DAY)")));
        //     $EncodeCount = count((DB::select("SELECT * FROM documents WHERE is_Encoded = 1 AND created_at >= (LAST_DAY(NOW()) + INTERVAL 1 DAY - INTERVAL 1 MONTH) AND created_at <  (LAST_DAY(NOW()) + INTERVAL 1 DAY)")));
        //     $CheckedCount = count((DB::select("SELECT * FROM documents WHERE is_Checked = 1 AND created_at >= (LAST_DAY(NOW()) + INTERVAL 1 DAY - INTERVAL 1 MONTH) AND created_at <  (LAST_DAY(NOW()) + INTERVAL 1 DAY)")));
        // }else{
        //     $deptID = auth()->user()->department;

        //     $uploadCount = count((DB::select("SELECT * FROM documents WHERE dept_id = $deptID AND created_at >= (LAST_DAY(NOW()) + INTERVAL 1 DAY - INTERVAL 1 MONTH) AND created_at <  (LAST_DAY(NOW()) + INTERVAL 1 DAY)")));
        //     $EncodeCount = count((DB::select("SELECT * FROM documents WHERE dept_id = $deptID AND is_Encoded = 1 AND created_at >= (LAST_DAY(NOW()) + INTERVAL 1 DAY - INTERVAL 1 MONTH) AND created_at <  (LAST_DAY(NOW()) + INTERVAL 1 DAY)")));
        //     $CheckedCount = count((DB::select("SELECT * FROM documents WHERE dept_id = $deptID AND is_Checked = 1 AND created_at >= (LAST_DAY(NOW()) + INTERVAL 1 DAY - INTERVAL 1 MONTH) AND created_at <  (LAST_DAY(NOW()) + INTERVAL 1 DAY)")));
        // }

        return view('home', compact('totalUploadCount', 'totalEncodeCount', 'totalCheckedCount', 'uploadCount', 'EncodeCount', 'CheckedCount'));
    }
}
