@extends('layouts.main')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card w-100">
            <div class="card-header d-flex flex-column flex-lg-row justify-content-md-between align-items-center">
                <h1 class="card-title">All Enrollments</h1>
                <div class="d-flex flex-wrap flex-md-nowrap justify-content-center justify-content-md-end align-items-center gap-3">
                    <a href="{{ route('enrollments.index') }}" class="btn btn-danger order-1 order-md-0"><i class="ti ti-reload"></i></a>
                    <form action="{{ route('enrollments.index') }}" method="get" class="order-0 order-md-1">
                        <div class="d-flex flex-wrap flex-md-nowrap  align-items-center">
                            <input type="text" name="search" class="form-control" placeholder="Search by Invoice or Student id" aria-describedby="search">
                            <span class="d-flex align-items-center gap-2 ms-2">
                                <label for="" class="label flex-shrink-0">To</label>
                                <input type="date" name="to_date" class="form-control" placeholder="From Date" aria-describedby="todate">
                            </span>
                            <span class="d-flex align-items-center gap-2 ms-2">
                                <label for="" class="label flex-shrink-0">From</label>
                                <input type="date" name="to_date" class="form-control" placeholder="From Date" aria-describedby="todate">
                            </span>
                            <button class="btn btn-primary ms-2"id="search"><i class="ti ti-search"></i></button>
                        </div>
                    </form>
                    <button type="button" class="btn btn-primary order-2 order-md-2" data-bs-toggle="modal" data-bs-target="#addEnrollment"><span class="me-2"><i class="ti ti-user-plus"></i></span>Enroll</button>
                </div>
            </div>
            <div class="card-body p-2 overflow-x-scroll">
                <table class="table table-borderless text-nowrap table-hover mb-0 align-middle">
                    <thead class="text-dark fs-4">
                    <tr>
                        <th class="border-bottom-0">
                        <h6 class="fw-semibold mb-0">Enroll Id</h6>
                        </th>
                        <th class="border-bottom-0">
                        <h6 class="fw-semibold mb-0">Installment</h6>
                        </th>
                        <th class="border-bottom-0">
                        <h6 class="fw-semibold mb-0">Upfron Amount</h6>
                        </th>
                        <th class="border-bottom-0">
                        <h6 class="fw-semibold mb-0">installment_number</h6>
                        </th>
                        <th class="border-bottom-0">
                        <h6 class="fw-semibold mb-0">amount_paid</h6>
                        </th>
                        <th class="border-bottom-0">
                        <h6 class="fw-semibold mb-0">payment_type</h6>
                        </th>
                        <th class="border-bottom-0">
                        <h6 class="fw-semibold mb-0">notes</h6>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($payments as $payment)
                            <tr>
                                <td>{{ $payment->enrollment_id }}</td>
                                <td>{{ $payment->is_installment }}</td>
                                <td>{{ $payment->upfront_payment_amount }}</td>
                                <td>{{ $payment->installment_number }}</td>
                                <td>{{ $payment->amount_paid }}</td>
                                <td>{{ $payment->payment_type }}</td>
                                <td>{{ $payment->notes }}</td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
