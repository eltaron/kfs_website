<?php

namespace App\Http\Controllers;

use App\Models\Suggestion;
use App\Models\User;
use App\Notifications\NewSuggestionSubmitted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class SuggestionController extends Controller
{
    public function create()
    {
        return view('suggestions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
            'national_id' => 'nullable|string|size:14',
        ]);

        $suggestion = Suggestion::create($validated);

        // Send notification to admins
        $admins = User::role(['Super Admin', 'Admin'])->get();
        Notification::send($admins, new NewSuggestionSubmitted($suggestion));

        return back()->with('success', 'شكرًا لك! تم استلام مقترحك بنجاح ونقدر مساهمتك.');
    }
}
