<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\User;

class ErpController extends Controller
{
    public function index()
    {
        $employeeData = Employee::where('national_id', auth()->user()->national_id)->first();

        $stats = [
            'tasks_count' => 5,
            'pending_requests' => 12,
            'unread_circulars' => 3
        ];

        return view('employees.erp.index', compact('employeeData', 'stats'));
    }
}
