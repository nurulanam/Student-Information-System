<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Program;
use App\Models\Student;
use Illuminate\View\View;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;

class EnrollmentController extends Controller
{
    public function index(): View
    {
        $title = 'Change Status!';
        $text = "Are you sure to change status?";
        confirmDelete($title, $text);

        $enrollments = Enrollment::latest()->with(['student', 'program'])->paginate(20);
        $programs = Program::all();
        return view("enrollments", compact("enrollments", "programs"));
    }

    public function store(Request $request): RedirectResponse
    {
        $validation = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,std_id',
            'program_id' => 'required|exists:programs,id',
            'total_cost' => 'required|integer|min:0',
            'payment_option' => 'required|in:full,upfront,installment',
            'total_installment' => 'required_if:payment_option,in:installment,upfront|integer|min:1',
            'amount_paid' => 'required_if:payment_option,in:full,upfront',
            'payment_type' => 'required|in:cash,bank_transfer,direct_debit,credit_card',
            'notes' => 'nullable|string',
        ]);

        if ($validation->fails()) {
            Alert::error('Error', "Please fill correct information.");
            return redirect()->back()->withErrors($validation)->withInput();
        }else{
            $student = Student::where('std_id', $request->student_id)->first();
            if (!$student) {
                Alert::error('Error', "Student not found.");
                return redirect()->back()->withErrors($validation)->withInput();
            }else{
                // Create enrollment record
                $enrollment = new Enrollment;
                $enrollment->student_id = $student->id;
                $enrollment->program_id = $request->program_id;
                $enrollment->enroll_id = $this->generateUniqueEnrollID();
                $enrollment->total_cost = $request->total_cost;
                $enrollment->payment_mode = $request->payment_option;
                $enrollment->total_installment = $request->total_installment;

                // Assign upfront_paid amount based on payment mode
                if ($request->payment_option == 'upfront') {
                    $enrollment->upfront_paid = $request->amount_paid;
                }
                $enrollment->notes = $request->notes;
                $enrollment->save();

                if (strtolower($enrollment->payment_mode) === 'full' || strtolower($enrollment->payment_mode) === 'upfront') {
                    $payment = $this->handlePayments($enrollment, $request);
                    if ($payment !== false) {
                        $enrollment->update(['status' => 'active']);

                        Alert::success('Success', "Enrollment active successfully.");
                        return redirect()->route('enrollments.index');
                    }else{
                        $enrollment->delete();
                        Alert::error('Error', "Payment Failed.");
                        return redirect()->route('enrollments.index');
                    }
                }elseif($enrollment->payment_mode === 'installment'){
                    $enrollment->update([
                        'installment_completed' => 0,
                        'status' => 'active'
                    ]);
                    Alert::success('Success', "Enrollment active successfully.");
                    return redirect()->route('enrollments.index');
                }

            }
        }

    }

    //handle payments
    private function handlePayments(Enrollment $enrollment, Request $request)
    {
        // For full or upfront payment, create a single payment record
        $payment = Payment::create([
            'enrollment_id' => $enrollment->id,
            'amount_paid' => $request->amount_paid,
            'payment_type' => $request->payment_type,
            'notes' => $request->notes,
        ]);
        $enrollment->increment('total_paid', $request->amount_paid);
        return $payment;
    }

    // generate unique enrollment Id
    private function generateUniqueEnrollID()
    {
        $prefix = 'ENR';
        $timestamp = now()->timestamp; // Get the current timestamp

        // Combine the prefix, timestamp, and a random number for uniqueness
        $uniqueID = $prefix . $timestamp . mt_rand(1000, 9999);

        while (Enrollment::where('enroll_id', $uniqueID)->exists()) {
            $uniqueID = $prefix . $timestamp . mt_rand(1000, 9999);
        }

        return $uniqueID;
    }
}
