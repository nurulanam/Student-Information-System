<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    public function index()
    {
        Carbon::setlocale('en_US');

        // Today's date
        $today = now();

        // Today's students
        $todaysStudents = Student::whereDate('created_at', $today)->count();

        // Today's payments
        $todaysPayments = Payment::whereDate('created_at', $today)->sum('amount_paid');

        // Debugging: Print start and end of the week
        $startOfWeek = $today->copy()->startOfWeek(Carbon::SUNDAY)->startOfDay(); // Start of Sunday
        $endOfWeek = $today->copy()->endOfWeek(Carbon::SATURDAY)->endOfDay();       // End of Saturday

        // Weekly payments
        $weeklyPayments = Payment::whereBetween('created_at', [$startOfWeek, $endOfWeek])->sum('amount_paid');



        $startOfMonth = $today->copy()->startOfMonth()->startOfDay();
        $endOfMonth = $today->copy()->endOfMonth()->endOfDay();
        // Monthly payments
        $monthlyPayments = Payment::whereBetween('created_at', [$startOfMonth, $endOfMonth])->sum('amount_paid');

        // Today's enrollments
        $todaysEnrollments = Enrollment::whereDate('created_at', $today)->count();

        return view('updates', compact('todaysStudents', 'todaysPayments', 'weeklyPayments', 'monthlyPayments', 'todaysEnrollments'));

    }
}
