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
        
        $enrollments = Enrollment::with(['student', 'program'])->paginate(20);
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
            'total_installment' => 'nullable|integer|min:1|max:12', // Assuming maximum of 12 installments
            'installment_completed' => 'nullable|integer|min:0|max:total_installment',
            'amount_paid' => 'nullable|numeric|min:0',
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
                $enrollment->total_cost = $request->total_cost;
                $enrollment->payment_mode = $request->payment_option;
                $enrollment->total_installment = $request->total_installment;
                $enrollment->installment_completed = 0;
                $enrollment->status = 'disable';
                $enrollment->save();

                // Create payment record
                $payment = new Payment;
                $payment->enrollment_id = $enrollment->id;
                $payment->is_installment = $request->payment_option === 'installment' ? true : false;
                $payment->upfront_payment_amount = $request->payment_option === 'upfront' ? $request->amount_paid : null;
                $payment->installment_number = $request->installment_number;
                $payment->amount_paid = $request->amount_paid;
                $payment->payment_type = $request->payment_type;
                $payment->notes = $request->notes;
                $payment->save();
            }

            // Update enrollment status for full payment
            if (isset($payment)) {
                $enrollment->status = 'active';
                $enrollment->update();
                if ($request->payment_option === 'upfront') {
                    $enrollment->total_paid = $request->amount_paid;
                    $enrollment->update();
                }
                Alert::success('Success', "Enrollment active successfully.");
            }else{
                Alert::error('Error', "Payment error.");
            }
            return redirect()->route('enrollments.index');
        }

    }
}
