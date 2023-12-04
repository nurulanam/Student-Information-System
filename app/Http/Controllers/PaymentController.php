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
    public function index(Request $request)
    {
        $search = $request->get('search');
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');

        $payments = Payment::latest();

        if ($search) {
            $enrollmentId = Enrollment::where('enroll_id', $search)->value('id');
            if ($enrollmentId) {
                $payments = $payments->where('enrollment_id', $enrollmentId);
            }
        }
        if ($fromDate) {
            $payments = $payments->whereDate('created_at', '>=', $fromDate);
        }
        if ($toDate) {
            $payments = $payments->whereDate('created_at', '<=', $toDate);
        }

        $payments = $payments->paginate(20)->appends(['search' => $search, 'from_date' => $fromDate, 'to_date' => $toDate]);

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
                $max_installment_amount = $enrollment->total_cost - $enrollment->total_paid;

                if($enrollment->payment_mode == 'full'){
                    Alert::error('Error', "Payment Failed.");
                    return redirect()->back();
                }elseif($enrollment->payment_mode == 'upfront' || $enrollment->payment_mode == 'installment'){
                    if($max_installment_amount < $request->amount_paid){
                        Alert::error('Error', "Can't take more than total cost.");
                        return redirect()->back();
                    }else{
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
            else{
                Alert::error('Error', "No enrollment record found.");
                return redirect()->route('payments.index');
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
                    'installment_left' => 0,
                    'due_amount' => 0,
                ]);
            }

            // Calculate installment amount and handle potential errors
            if ($enrollment->payment_mode == 'upfront' || $enrollment->payment_mode == 'installment') {
                if ($enrollment->total_installment == $enrollment->installment_completed) {
                    $message = 'Already Full Paid';
                    $success = false;
                    $installment_left = 0;
                    $due_amount = 0;
                } else {
                    $due_amount = $enrollment->total_cost - $enrollment->total_paid;
                    $message = 'Total Due Amount: ' . $due_amount;
                    $installment_left = 'Installment Due: '.$enrollment->total_installment - $enrollment->installment_completed;
                    $success = true;
                }
            } else {
                $message = 'Invalid payment mode';
                $success = false;
                $due_amount = 0;
            }

            // Return response based on success or error
            return response()->json([
                'success' => $success,
                'message' => $message,
                'installment_left' => $installment_left,
                'due_amount' => $due_amount,
            ]);
        } else {
            // Return error if enrollment not found
            return response()->json([
                'success' => false,
                'message' => 'Enrollment Not Found',
                'installment_left' => 0,
                'due_amount' => ' ',
            ]);
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
