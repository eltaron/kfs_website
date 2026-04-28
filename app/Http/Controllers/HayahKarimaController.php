<?php

namespace App\Http\Controllers;

use App\Models\HayahKarimaProject;
use Illuminate\Http\Request;

class HayahKarimaController extends Controller
{
    public function index()
    {
        $projects = HayahKarimaProject::orderBy('progress', 'desc')->get();
        return view('projects.hay_karima', compact('projects'));
    }
}
