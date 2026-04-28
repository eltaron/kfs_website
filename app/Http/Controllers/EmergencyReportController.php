<?php

namespace App\Http\Controllers;

use App\Models\EmergencyReport;
use Illuminate\Http\Request;

class EmergencyReportController extends Controller
{
    public function create()
    {
        // Fetch necessary data like a list of centers and report types
        $centers = ['كفر الشيخ', 'دسوق', 'بيلا', 'الحامول']; // Example, fetch from DB
        $reportTypes = ['حريق', 'حادث مروري', 'تلوث بيئي', 'انهيار مبنى', 'أخرى'];

        return view('emergency.create', compact('centers', 'reportTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // Add validation rules for all fields from the image
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'attachments.*' => 'nullable|file|max:5120', // 5MB max per file
        ]);

        if ($request->hasFile('attachments')) {
            $paths = [];
            foreach ($request->file('attachments') as $file) {
                $paths[] = $file->store('emergency_reports', 'public');
            }
            $validated['attachments'] = $paths;
        }

        $report = EmergencyReport::create($validated);

        // Send Notification
        // Notification::send($admins, new NewEmergencyReport($report));

        return back()->with('success', 'تم استلام بلاغك بنجاح! رقم المتابعة: ' . $report->id);
    }
}
