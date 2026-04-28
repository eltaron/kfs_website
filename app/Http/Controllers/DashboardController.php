<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // Fetch the user's activities, order by newest first
        $complaints = $user->complaints()->latest()->get();
        $suggestions = $user->suggestions()->latest()->get();
        $serviceSubmissions = \App\Models\ServiceSubmission::where('user_id', $user->id)
            ->with('service') // جلب بيانات الخدمة (الاسم، الأيقونة)
            ->latest()
            ->get();
        return view('citizen.dashboard.index', [
            'user' => $user,
            'complaints' => $complaints,
            'suggestions' => $suggestions,
            'serviceSubmissions' => $serviceSubmissions,
        ]);
    }
    public function show($id)
    {
        // جلب الطلب مع بيانات الخدمة المرتبطة به، والتأكد أن العميل يملك الطلب
        $submission = \App\Models\ServiceSubmission::with('service')
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        return view('citizen.submissions.show', compact('submission'));
    }
}
