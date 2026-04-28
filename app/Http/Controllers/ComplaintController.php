<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\User;
use App\Notifications\NewComplaintSubmitted; // سننشئه
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class ComplaintController extends Controller
{
    public function create()
    {
        return view('complaints.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'national_id' => 'nullable|string|size:14',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
            'attachment' => 'nullable|file|mimes:pdf,jpg,png|max:2048', // 2MB max
        ]);

        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request->file('attachment')->store('complaints', 'public');
        }

        $complaint = Complaint::create($validated);

        // Send notification to admins
        $admins = User::role(['Super Admin', 'Admin'])->get();
        Notification::send($admins, new NewComplaintSubmitted($complaint));

        return back()->with('success', 'تم استلام شكواك بنجاح. رقم المتابعة الخاص بك هو: ' . $complaint->id);
    }
}
