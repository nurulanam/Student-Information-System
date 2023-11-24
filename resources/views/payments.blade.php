@extends('layouts.main')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">

            </div>
            <div class="card-body">
                <form method="post" action="/payments">
                    @csrf
                    <div

            class="mb-3">


            <label

            for="student_id"

            class="form-label">Student ID:</label>


            <input

            type="text"

            class="form-control"

            id="student_id"

            name="student_id" placeholder="Enter student ID">
                    </div>
                    <div class="mb-3">
                        <label for="installment_number" class="form-label">Installment Number:</label>
                        <input type="number" class="form-control" id="installment_number" name="installment_number" placeholder="Enter installment number">
                    </div>
                    <div class="mb-3">
                        <label for="payment_date" class="form-label">Payment Date:</label>
                        <input type="date" class="form-control" id="payment_date" name="payment_date">
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Payment Amount:</label>
                        <input type="number" class="form-control" id="amount" name="amount" placeholder="Enter payment amount">
                    </div>
                    <div class="mb-3">
                        <label for="payment_type" class="form-label">Payment Type:</label>
                        <div class="radio">
                            <label for="cash">
                                <input type="radio" name="payment_type" id="cash" value="cash" checked>
                                Cash
                            </label>
                        </div>
                        <div class="radio">
                            <label for="bank_transfer">
                                <input type="radio" name="payment_type" id="bank_transfer" value="bank_transfer">
                                Bank Transfer
                            </label>
                        </div>
                        <div class="radio">
                            <label for="direct_debit">
                                <input type="radio" name="payment_type" id="direct_debit" value="direct_debit">
                                Direct Debit
                            </label>
                        </div>
                        <div class="radio">
                            <label for="credit_card">
                                <input type="radio" name="payment_type" id="credit_card" value="credit_card">
                                Credit Card
                            </label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Payment</button>


            </form>
            </div>
        </div>
    </div>
</div>
@endsection
