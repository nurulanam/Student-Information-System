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
        $payments = Payment::paginate(20);
        return view('payments', compact('payments'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'enrollment_id' => 'required|exists:enrollments,id',
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
            $enrollment = Enrollment::findOrFail($request->enrollment_id);
            if (isset($enrollment)){
                $installment_number = ++$enrollment->installment_completed;
                $current_amount = $enrollment->total_paid + $request->amount_paid;

                if ($installment_number === 1 || ($installment_number <= $enrollment->total_installment && $enrollment->total_installment > 0))
                {
                    $payment = Payment::create([
                        'enrollment_id' => $enrollment->id,
                        'is_installment' => true,
                        'installment_number' => $installment_number,
                        'amount_paid' => $request->amount_paid,
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
        $enrollment = Enrollment::find($enrollmentId);

        if ($enrollment) {
            // If the enrollment is found, return its data as JSON
            return response()->json([
                'total_cost' => $enrollment->total_cost,
                // Add other enrollment data fields as needed
            ]);
        } else {
            // If the enrollment is not found, return an error
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
