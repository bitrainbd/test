<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\Mcq;
use App\Models\McqAttempt;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(){
        $total_users = User::count();
        $total_mcqs = Mcq::count();
        $total_institutions = Institution::count();
        $total_attempts = McqAttempt::count();
        return view('dashboard',compact('total_users','total_mcqs','total_institutions','total_attempts'));
    }
}
