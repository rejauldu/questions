<?php

namespace App\Http\Controllers;


class ToolController extends Controller
{
    public function svg() {
        return view('tools.svg');
    }
    
    public function flowchart() {
        return view('tools.flowchart');
    }
}