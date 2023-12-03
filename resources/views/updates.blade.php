@extends('layouts.main')
@section('title', 'Updates')
@section('content')
<div class="row">
    <div class="col-md-6 col-lg-4">
        <div class="card position-relative">
            <div class="card-header bg-primary text-white">
                Today's Registered
            </div>
            <div class="card-body">
                <h5 class="card-title text-primary">{{ $todaysStudents }}</h5>
            </div>
            <span class="position-absolute bottom-0 end-0 m-2 display-3 text-primary opacity-25"><i class="ti ti-user"></i></span>
        </div>
    </div>
    <div class="col-md-6 col-lg-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                Today's Payments
            </div>
            <div class="card-body">
                <h5 class="card-title text-success">AUD {{ number_format($todaysPayments, 2) }}</h5>
            </div>
            <span class="position-absolute bottom-0 end-0 m-2 display-3 text-success opacity-25"><i class="ti ti-clipboard"></i></span>
        </div>
    </div>
    <div class="col-md-6 col-lg-4">
        <div class="card position-relative">
            <div class="card-header bg-info text-white">
                Weekly Payments
            </div>
            <div class="card-body">
                <h5 class="card-title text-info">AUD {{ number_format($weeklyPayments, 2) }}</h5>
            </div>
            <span class="position-absolute bottom-0 end-0 m-2 display-3 text-danger opacity-25"><i class="ti ti-tag"></i></span>
        </div>
    </div>
    <div class="col-md-6 col-lg-4">
        <div class="card position-relative">
            <div class="card-header bg-warning text-white">
                Monthly Payments
            </div>
            <div class="card-body">
                <h5 class="card-title text-warning">AUD {{ number_format($monthlyPayments, 2) }}</h5>
            </div>
            <span class="position-absolute bottom-0 end-0 m-2 display-3 text-danger opacity-25"><i class="ti ti-crown"></i></span>
        </div>
    </div>
    <div class="col-md-6 col-lg-4">
        <div class="card position-relative">
            <div class="card-header bg-danger text-white">
                Today's Enrollments
            </div>
            <div class="card-body">
                <h5 class="card-title text-danger">{{ $todaysEnrollments }}</h5>
            </div>
            <span class="position-absolute bottom-0 end-0 m-2 display-3 text-danger opacity-25"><i class="ti ti-pencil"></i></span>
        </div>
    </div>
</div>
@endsection
