<?php

namespace App\Http\Controllers;

use App\Models\ServiceSurvey;
use App\Models\User;
use App\Notifications\NewServiceSurvey; // Assuming you've created this notification
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class ServiceSurveyController extends Controller
{
    /**
     * Display the survey form.
     */
    public function create()
    {
        // Example list of service centers. You can fetch these from the database later.
        $centers = [
            'المركز التكنولوجي بمدينة كفر الشيخ',
            'المركز التكنولوجي بمدينة دسوق',
            'المركز التكنولوجي بمدينة بيلا',
            'المركز التكنولوجي بمدينة الحامول',
        ];

        return view('surveys.service', [
            'centers' => $centers
        ]);
    }

    /**
     * Store a newly created survey in storage.
     */
    public function store(Request $request)
    {
        // Comprehensive validation rules for all fields from the survey
        $validatedData = $request->validate([
            // Personal Info
            'center_name' => 'required|string|max:255',
            'name'        => 'nullable|string|max:255',
            'phone'       => 'nullable|string|max:20',
            'age_group'   => 'required|string|max:255',
            'gender'      => 'required|string|in:ذكر,أنثى',

            // Part 1: Service Quality Assessment (required, integer, between 1 and 5)
            'q1_1_accessibility'        => 'required|integer|between:1,5',
            'q1_2_procedure_clarity'    => 'required|integer|between:1,5',
            'q1_3_needs_fulfillment'    => 'required|integer|between:1,5',
            'q1_4_guidance'             => 'required|integer|between:1,5',
            'q1_5_staff_cooperation'    => 'required|integer|between:1,5',
            'q1_6_process_handling'     => 'required|integer|between:1,5',

            // Part 2: Service Speed Assessment
            'q2_1_service_speed'      => 'required|integer|between:1,5',
            'q2_2_wait_time'          => 'required|integer|between:1,5',
            'q2_3_delay_justification' => 'required|integer|between:1,5',

            // Part 3: Staff Performance Assessment
            'q3_1_staff_treatment'   => 'required|integer|between:1,5',
            'q3_2_problem_solving'   => 'required|integer|between:1,5',
            'q3_3_communication_ease' => 'required|integer|between:1,5',
            'q3_4_fees_clarity'      => 'required|integer|between:1,5',

            // Part 4: Center Environment Assessment
            'q4_1_cleanliness'          => 'required|integer|between:1,5',
            'q4_2_seating_comfort'      => 'required|integer|between:1,5',
            'q4_3_accessibility_tools' => 'required|integer|between:1,5',

            // Open Questions (nullable)
            'suggestions'              => 'nullable|string|max:5000',
            'complaint_employee_name'  => 'nullable|string|max:255',
            'complaint_reason'         => 'nullable|string|max:5000',
        ]);

        // Create the survey record in the database
        $survey = ServiceSurvey::create($validatedData);

        // Send notification to admins and super admins
        $admins = User::role(['Super Admin', 'Admin'])->get();
        if ($admins->isNotEmpty()) {
            Notification::send($admins, new NewServiceSurvey($survey));
        }

        // Redirect back with a success message
        return back()->with('success', 'شكرًا جزيلاً لمشاركتك! تقييمك يساعدنا على تحسين خدماتنا.');
    }
}
