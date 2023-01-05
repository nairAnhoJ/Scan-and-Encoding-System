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
        $uploadCount = 0;
        $EncodeCount = 0;
        $CheckedCount = 0;

        $totalUploadCount = (DB::select('SELECT COUNT(*) AS count FROM documents'))[0]->count;
        $totalEncodeCount = (DB::select('SELECT COUNT(*) AS count FROM documents WHERE is_Encoded = ?', [1]))[0]->count;
        $totalCheckedCount = (DB::select('SELECT COUNT(*) AS count FROM documents WHERE is_Checked = ?', [1]))[0]->count;



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
