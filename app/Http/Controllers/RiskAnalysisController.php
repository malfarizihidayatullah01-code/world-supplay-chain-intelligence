<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RiskAnalysisController extends Controller
{
    public function index()
    {
        return view('risk_analysis.index');
    }
}
