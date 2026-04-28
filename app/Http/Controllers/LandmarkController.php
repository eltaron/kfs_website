<?php

namespace App\Http\Controllers;

use App\Models\Landmark;

class LandmarkController extends Controller
{
    public function index()
    {
        $landmarks = Landmark::orderBy('order')->paginate(9);
        return view('landmarks.index', compact('landmarks'));
    }

    public function show(Landmark $landmark)
    {
        $landmark->load('images');
        return view('landmarks.show', compact('landmark'));
    }
}
