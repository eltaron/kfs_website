<?php

namespace App\Http\Controllers;

use App\Models\Setting;

class AboutPageController extends Controller
{
    public function governorMessage()
    {
        // Settings are loaded globally via ComposerServiceProvider
        return view('about.governor');
    }
}
