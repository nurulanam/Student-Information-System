<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = Payment::latest()->paginate(20);
        return view('payments', compact('payments'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'enrollment_id' => 'required|exists:enrollments,enroll_id',
            'amount_paid' => 'required|numeric|min:0',
            'payment_type' => 'required|in:cash,bank_transfer,direct_debit,credit_card',
            'notes' => 'nullable|string',
        ]);

        if ($validation->fails())
        {
            Alert::error('Error', "Please fill correct information.");
            return redirect()->back()->withErrors($validation)->withInput();
        }
        else
        {
            $enrollment = Enrollment::where('enroll_id',$request->enrollment_id)->first();
            if (isset($enrollment)){
                $installment_amount = 0;

                if($enrollment->payment_mode == 'upfront'){
                    $installment_amount = ($enrollment->total_cost - $enrollment->upfront_paid) / $enrollment->total_installment;
                }elseif($enrollment->payment_mode == 'installment'){
                    $installment_amount = $enrollment->total_cost / $enrollment->total_installment;
                }elseif($enrollment->payment_mode == 'full'){
                    Alert::error('Error', "Payment Failed.");
                    return redirect()->back();
                }

                $installment_number = ++$enrollment->installment_completed;
                $current_amount = $enrollment->total_paid + $installment_amount;

                if ($installment_number === 1 || ($installment_number <= $enrollment->total_installment && $enrollment->total_installment > 0))
                {
                    $payment = Payment::create([
                        'enrollment_id' => $enrollment->id,
                        'is_installment' => true,
                        'installment_number' => $installment_number,
                        'amount_paid' => $installment_amount,
                        'payment_type' => $request->payment_type,
                        'notes' => $request->notes,
                    ]);
                    if(isset($payment)){
                        $enrollment->total_paid = $current_amount;
                        $enrollment->installment_completed = $installment_number;
                        $enrollment->update();

                        Alert::success('Success', "Payment successfully created.");
                        return redirect()->route('payments.index');
                    }else{
                        Alert::error('Error', "Payment Failed.");
                        return redirect()->route('payments.index');
                    }
                }else{
                    Alert::error('Error', "Already paid all installments.");
                    return redirect()->route('payments.index');
                }
            }
        }
    }



    public function findEnrollment(Request $request)
    {
        // Retrieve the enrollmentId from the request
        $enrollmentId = $request->enrollmentId;

        // Assuming you have a model named Enrollment
        $enrollment = Enrollment::where('enroll_id', $enrollmentId)->first();

        if ($enrollment) {
            // Handle full payment mode
            if ($enrollment->payment_mode == 'full') {
                return response()->json([
                    'success' => false,
                    'message' => 'Already Full Paid',
                    'installment_amount' => 0,
                ]);
            }

            // Calculate installment amount and handle potential errors
            if ($enrollment->payment_mode == 'upfront' || $enrollment->payment_mode == 'installment') {
                if ($enrollment->total_installment == $enrollment->installment_completed) {
                    $message = 'Already Full Paid';
                    $success = false;
                    $installment_amount = 0;
                } else {
                    if($enrollment->payment_mode == 'upfront'){
                        $installment_amount = ($enrollment->total_cost - $enrollment->upfront_paid) / $enrollment->total_installment;
                    }elseif($enrollment->payment_mode == 'installment'){
                        $installment_amount = $enrollment->total_cost / $enrollment->total_installment;
                    }
                    $message = 'Per Installment Amount is: ' . $installment_amount;
                    $success = true;
                }
            } else {
                $message = 'Invalid payment mode';
                $success = false;
                $installment_amount = 0;
            }

            // Return response based on success or error
            return response()->json([
                'success' => $success,
                'message' => $message,
                'installment_amount' => $installment_amount,
            ]);
        } else {
            // Return error if enrollment not found
            return response()->json(['error' => 'Enrollment not found'], 404);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


}
