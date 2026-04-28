<?php

namespace App\Http\Controllers;

use App\Models\FamousPeople;
use App\Models\GovernorateDetail;
use Illuminate\Http\Request;

class GovernorateController extends Controller
{
    public function about()
    {
        $info = GovernorateDetail::all()->keyBy('key');

        $famousByCat = FamousPeople::orderBy('sort_order')
            ->get()
            ->groupBy('category');

        return view('governorate.about', compact('info', 'famousByCat'));
    }
}
