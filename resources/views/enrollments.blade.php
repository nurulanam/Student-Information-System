@extends('layouts.main')
@section('title', 'Enrollments')
@section('content')
<style>
    /* Chrome, Safari, Edge, Opera */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }

    /* Firefox */
    input[type=number] {
      -moz-appearance: textfield;
    }

    @media (min-width: 768px){
       .payment-input-box-style{
        width: 31%;
    }
    }
    </style>
    <div class="row">
        <div class="col-12">
            <div class="card w-100">
                <div class="card-header d-flex flex-column flex-lg-row justify-content-md-between align-items-center">
                    <h1 class="card-title">All Enrollments</h1>
                    <div class="d-flex flex-wrap flex-md-nowrap justify-content-center justify-content-md-end align-items-center gap-3">
                        <a href="{{ route('enrollments.index') }}" class="btn btn-danger order-1 order-md-0"><i class="ti ti-reload"></i></a>
                        <form action="{{ route('enrollments.index') }}" method="get" class="order-0 order-md-1">
                            <div class="d-flex flex-wrap flex-md-nowrap  align-items-center">
                                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search by Enrollment Id or Student id" aria-describedby="search">
                                <span class="d-flex align-items-center gap-2 ms-2">
                                    <label for="" class="label flex-shrink-0">From</label>
                                    <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}" placeholder="From Date" aria-describedby="todate">
                                </span>
                                <span class="d-flex align-items-center gap-2 ms-2">
                                    <label for="" class="label flex-shrink-0">To</label>
                                    <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}" placeholder="From Date" aria-describedby="todate">
                                </span>

                                <button class="btn btn-primary ms-2"id="search"><i class="ti ti-search"></i></button>
                            </div>
                        </form>
                        <button type="button" class="btn btn-primary order-2 order-md-2" data-bs-toggle="modal" data-bs-target="#addEnrollment"><span class="me-2"><i class="ti ti-user-plus"></i></span>Enroll</button>
                    </div>
                </div>
                <div class="card-body p-2 overflow-x-scroll">
                    <table class="table text-nowrap table-borderless table-hover mb-0 align-middle">
                        <thead class="text-dark fs-4">
                        <tr>
                            <th>
                                <h6 class="fw-semibold mb-0">Enroll Id</h6>
                            </th>
                            <th>
                                <h6 class="fw-semibold mb-0">Name</h6>
                            </th>
                            <th>
                                <h6 class="fw-semibold mb-0">Program</h6>
                            </th>
                            <th>
                                <h6 class="fw-semibold mb-0">Payment Mode</h6>
                            </th>
                            <th>
                                <h6 class="fw-semibold mb-0">Total Fee</h6>
                            </th>
                            <th>
                                <h6 class="fw-semibold mb-0">Total Paid</h6>
                            </th>
                            <th>
                                <h6 class="fw-semibold mb-0">Total Installments</h6>
                            </th>
                            <th>
                                <h6 class="fw-semibold mb-0">Paid Installments</h6>
                            </th>
                            <th>
                                <h6 class="fw-semibold mb-0">Status</h6>
                            </th>
                            <th>
                                <h6 class="fw-semibold mb-0">Action</h6>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($enrollments as $enrollment)
                                <tr>
                                    <td>{{ $enrollment->enroll_id}}</td>
                                    <td>{{ $enrollment->student->full_name }}</td>
                                    <td>{{ $enrollment->program->name }}</td>
                                    <td>
                                        @if ($enrollment->payment_mode === 'full')
                                            Full Paid
                                        @elseif ($enrollment->payment_mode === 'upfront')
                                            Up Front
                                        @elseif ($enrollment->payment_mode === 'installment')
                                            Installment
                                        @endif
                                    </td>
                                    <td>{{ $enrollment->total_cost}}</td>
                                    <td>{{ $enrollment->total_paid}}</td>
                                    <td>{{ $enrollment->total_installment}}</td>
                                    <td>{{ $enrollment->installment_completed}}</td>
                                    <td>
                                        @if ($enrollment->status === 'active')
                                            <a href="#" class="badge bg-success" data-confirm-delete="true"><i class="ti ti-arrow-up"></i></a>
                                        @elseif($enrollment->status === 'disable')
                                            <a href="#" class="badge bg-danger" ><i class="ti ti-arrow-down"></i></a>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center justify-content-between gap-2">
                                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#editEnroll{{ $enrollment->id }}"><i class="ti ti-pencil"></i></button>
                                            @if ($enrollment->payment_mode === 'upfront' || $enrollment->payment_mode === 'installment')
                                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addPayment{{ $enrollment->id }}">$</button>
                                            @endif
                                            <a href="{{ route('enrollments.destroy', $enrollment->id) }}" class="btn btn-danger btn-sm" data-confirm-delete="true">Delete</a>
                                        </div>
                                    </td>
                                </tr>
                                {{-- modal  --}}
                                <div class="modal fade common-modal" id="editEnroll{{ $enrollment->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Edit {{ $enrollment->enroll_id }}</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('enrollments.update') }}" method="post">
                                                    @method('PUT')
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $enrollment->id }}">
                                                    <div class="mb-3">
                                                        <label for="studentId" class="form-label">Student Id<span class="text-danger">*</span></label>
                                                        <input type="text" name="new_student_id" class="form-control" value="{{ $enrollment->student->std_id }}" id="studentId"  placeholder="ST0000000" required>
                                                        @error('new_student_id')
                                                            <p class="text-danger">{{  $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="programId" class="form-label">Program <span class="text-danger">*</span></label>
                                                        <select name="new_program_id" class="form-control" id="programId" required>
                                                            <option value="">Select Program</option>
                                                            @foreach ($programs as $program )
                                                                <option value="{{ $program->id }}" @if ($enrollment->program_id == $program->id) selected @endif>{{ $program->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('new_program_id')
                                                            <p class="text-danger">{{  $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="newTotalCost" class="form-label">Total Cost <span class="text-danger">*</span></label>
                                                        <input type="text" name="new_total_cost" class="form-control" value="{{ $enrollment->total_cost }}" id="newTotalCost" required>
                                                        @error('new_total_cost')
                                                            <p class="text-danger">{{  $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="notes{{ $enrollment->id }}" class="form-label">Notes</label>
                                                        <textarea name="new_notes" class="form-control" id="notes{{ $enrollment->id }}" rows="2">{{ $enrollment->notes }}</textarea>
                                                        @error('new_notes')
                                                            <p class="text-danger">{{  $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="d-flex justify-content-end align-items-center gap-2">
                                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                                                        <button class="btn btn-primary">Update</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if ($enrollment->payment_mode === 'upfront' || $enrollment->payment_mode === 'installment')
                                <div class="modal fade" id="addPayment{{ $enrollment->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Payment Details</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                            <form action="{{ route('payments.store') }}" method="post">
                                                @csrf
                                                @php
                                                    if (!$enrollment->total_paid || !$enrollment->total_installment) {
                                                        $perInstallment = 0; // or display message
                                                    } else {
                                                        $checkTotalInstallment = $enrollment->installment_completed;
                                                        $check = $checkTotalInstallment + 1;

                                                        if ($check > $enrollment->total_installment) {
                                                            $perInstallment = 0;
                                                        } else {
                                                            $dueAmount = $enrollment->total_cost - $enrollment->total_paid;
                                                            $dueInstallment = $enrollment->total_installment - $checkTotalInstallment;
                                                            $perInstallment = $dueAmount / $dueInstallment;
                                                        }
                                                    }
                                                @endphp
                                                <input type="hidden" name="enrollment_id" class="form-control" value="{{ $enrollment->enroll_id }}" id="enrollmentId" required>
                                                <div class="d-flex align-items-center justify-content-between mb-2">
                                                    <label class="form-label" >
                                                        Total Cost
                                                    </label>
                                                    <p>{{ $enrollment->total_cost }}</p>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <label class="form-label" >
                                                        Total Due
                                                    </label>
                                                    <p>
                                                        @if (!$enrollment->total_paid || !$enrollment->total_installment)
                                                          // Handle invalid data
                                                        @else
                                                          {{ $enrollment->total_cost - $enrollment->total_paid }}
                                                        @endif
                                                      </p>
                                                </div>

                                                <div class="d-md-flex align-items-stretch gap-3 flex-wrap">
                                                    @php
                                                        $dueDates = json_decode($enrollment->due_dates, true);
                                                    @endphp
                                                    @foreach (range(1, $enrollment->total_installment) as $installmentNumber)
                                                        @php
                                                            $payment = $enrollment->payments->where('installment_number', $installmentNumber)->first();
                                                            $amountPaid = $payment ? $payment->amount_paid : 0;
                                                            $disabled = true;
                                                            if($check == $installmentNumber){
                                                                $disabled = false;
                                                            }
                                                        @endphp

                                                        <div class="p-3 mb-1 card payment-input-box-style @if ($disabled && $installmentNumber < $check) bg-success @elseif($disabled && $installmentNumber > $check) bg-danger @else bg-info @endif">
                                                            <div class="d-flex align-items-center">
                                                                <div class="form-check">
                                                                    <input
                                                                        class="form-check-input amount_check"
                                                                        type="checkbox"
                                                                        @if ($disabled && $installmentNumber < $check) checked disabled @elseif($disabled && $installmentNumber > $check) disabled @else required @endif
                                                                    >
                                                                </div>
                                                                <input
                                                                    type="number"
                                                                    name="amount_paid"
                                                                    class="form-control bg-white"
                                                                    @if ($installmentNumber < $check)
                                                                        value="{{ $amountPaid }}"
                                                                    @elseif($installmentNumber >= $check)
                                                                        value="{{ number_format($perInstallment, 2) }}"
                                                                    @endif
                                                                    step="any"
                                                                    min="0"
                                                                    @if ($disabled) disabled @endif
                                                                    required>
                                                            </div>
                                                            <div class="p-2 rounded-1 mt-2 @if ($disabled && $installmentNumber < $check) bg-success-subtle @elseif($disabled && $installmentNumber > $check) bg-danger-subtle @else bg-info-subtle @endif">
                                                                @if (!empty($dueDates) && array_key_exists($installmentNumber, $dueDates))
                                                                    <div class="flex-shrink-0 w-100">
                                                                        <input
                                                                            type="date"
                                                                            name="due_dates[{{ $installmentNumber }}]"
                                                                            class="form-control bg-white"
                                                                            value="{{ $dueDates[$installmentNumber] }}"
                                                                            @if ($disabled && $installmentNumber < $check) readonly @endif >
                                                                    </div>
                                                                @endif
                                                                @if ($disabled && $installmentNumber < $check)
                                                                    <div class="flex-shrink-0 w-100 pt-2 pb-0">
                                                                        <p class="d-flex align-items-center mb-0"><span class="me-1" style="font-size: 16px;"><i class="ti ti-check"></i></span>{{  $payment->createdBy->name }}</p>
                                                                    </div>
                                                                @endif
                                                            </div>

                                                        </div>

                                                    @endforeach
                                                </div>
                                                @error('amount_paid')
                                                    <p class="text-danger">{{  $message }}</p>
                                                @enderror

                                                <div class="mb-3">
                                                    <label for="paymentType" class="form-label">Payment Type <span class="text-danger">*</span></label>
                                                    <div class="d-flex flex-wrap flex-md-nowrap align-items-center gap-2 gap-md-4">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="payment_type" value="cash" id="paymentType1" @if(old('payment_type') == 'cash') checked @endif required>
                                                            <label class="form-check-label" for="paymentType1">
                                                                Cash
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="payment_type" value="bank_transfer" id="paymentType2" @if(old('payment_type') == 'bank_transfer') checked @endif>
                                                            <label class="form-check-label" for="paymentType2">
                                                                Bank Transfer
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="payment_type" value="direct_debit" id="paymentType3" @if(old('payment_type') == 'direct_debit') checked @endif>
                                                            <label class="form-check-label" for="paymentType3">
                                                                Direct Debit
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="payment_type" value="credit_card" id="paymentType4" @if(old('payment_type') == 'credit_card') checked @endif>
                                                            <label class="form-check-label" for="paymentType4">
                                                                Credit Card
                                                            </label>
                                                        </div>
                                                    </div>
                                                    @error('payment_type')
                                                        <p class="text-danger">{{  $message }}</p>
                                                    @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label for="notes" class="form-label">Notes</label>
                                                    <textarea name="notes" class="form-control" id="notes" rows="2"></textarea>
                                                    @error('notes')
                                                        <p class="text-danger">{{  $message }}</p>
                                                    @enderror
                                                </div>

                                                <div class="d-flex justify-content-end align-items-center gap-2">
                                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                                                    <button class="btn btn-primary" @if ($enrollment->total_cost - $enrollment->total_paid == 0) disabled  @endif>Pay Installment</button>
                                                </div>
                                            </form>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="d-flex justify-content-end align-items-end">
                {!! $enrollments->links() !!}
            </div>

            {{-- modal  --}}
            <div class="modal fade" id="addEnrollment" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Enroll Program</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('enrollments.store') }}" method="post">
                                @csrf
                                <div class="mb-3">
                                    <label for="studentId" class="form-label">Student Id<span class="text-danger">*</span></label>
                                    <input type="text" name="student_id" class="form-control" value="{{ old('student_id') }}" id="studentId"  placeholder="ST0000000" required>
                                    @error('student_id')
                                        <p class="text-danger">{{  $message }}</p>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="programId" class="form-label">Program <span class="text-danger">*</span></label>
                                    <select name="program_id" class="form-control" id="programId" required>
                                        <option value="">Select Program</option>
                                        @foreach ($programs as $program )
                                            <option value="{{ $program->id }}" @if (old('program_id') == $program->id) selected @endif>{{ $program->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('program_id')
                                        <p class="text-danger">{{  $message }}</p>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="totalCost" class="form-label">Total Cost <span class="text-danger">*</span></label>
                                    <input type="text" name="total_cost" class="form-control" value="{{ old('total_cost') }}" id="totalCost" required>
                                    @error('total_cost')
                                        <p class="text-danger">{{  $message }}</p>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label  class="form-label" for="paymentOption">Payment Option <span class="text-danger">*</span></label>
                                    <div class="d-flex flex-wrap flex-md-nowrap align-items-center gap-2 gap-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_option" value="full" id="paymentOptionFull" @if(old('payment_option') == 'full') checked @endif >
                                            <label class="form-check-label" for="paymentOptionFull">
                                                Full Payment
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_option" value="upfront" id="paymentOptionUpFront" @if(old('payment_option') == 'upfront') checked @endif>
                                            <label class="form-check-label" for="paymentOptionUpFront">
                                                Up Front
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_option" value="installment" id="paymentOptionInstallment" @if(old('payment_option') == 'installment') checked @endif>
                                            <label class="form-check-label" for="paymentOptionInstallment">
                                                Installments
                                            </label>
                                        </div>
                                    </div>
                                    @error('payment_option')
                                        <p class="text-danger">{{  $message }}</p>
                                    @enderror
                                </div>
                                <div class="mb-3" id="installmentDetails" style="display: none">
                                    <label for="totalInstallment" class="form-label">Installments <span class="text-danger">*</span></label>
                                    <div class="d-flex flex-wrap flex-md-nowrap align-items-center gap-2 gap-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="total_installment" value="3" @if(old('total_installment') == '3') checked @endif id="totalInstallment3" data-full-payment-field>
                                            <label class="form-check-label" for="totalInstallment3">
                                             3
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="total_installment" value="4" @if(old('total_installment') == '4') checked @endif id="totalInstallment4" data-full-payment-field>
                                            <label class="form-check-label" for="totalInstallment4">
                                             4
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="total_installment" value="5" @if(old('total_installment') == '5') checked @endif id="totalInstallment5" data-full-payment-field>
                                            <label class="form-check-label" for="totalInstallment5">
                                             5
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="total_installment" value="6" @if(old('total_installment') == '6') checked @endif id="totalInstallment6" data-full-payment-field>
                                            <label class="form-check-label" for="totalInstallment6">
                                             6
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="total_installment" value="7" @if(old('total_installment') == '7') checked @endif id="totalInstallment7" data-full-payment-field>
                                            <label class="form-check-label" for="totalInstallment7">
                                             7
                                            </label>
                                        </div>
                                    </div>
                                    @error('total_installment')
                                        <p class="text-danger">{{  $message }}</p>
                                    @enderror
                                </div>
                                <div class="mb-3" id="allPaymentTypes">
                                    <label for="paymentType" class="form-label">Payment Type <span class="text-danger">*</span></label>
                                    <div class="d-flex flex-wrap flex-md-nowrap align-items-center gap-2 gap-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_type" value="cash" id="paymentType1" @if(old('payment_type') == 'cash') checked @endif required>
                                            <label class="form-check-label" for="paymentType1">
                                                Cash
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_type" value="bank_transfer" id="paymentType2" @if(old('payment_type') == 'bank_transfer') checked @endif>
                                            <label class="form-check-label" for="paymentType2">
                                                Bank Transfer
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_type" value="direct_debit" id="paymentType3" @if(old('payment_type') == 'direct_debit') checked @endif>
                                            <label class="form-check-label" for="paymentType3">
                                                Direct Debit
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_type" value="credit_card" id="paymentType4" @if(old('payment_type') == 'credit_card') checked @endif>
                                            <label class="form-check-label" for="paymentType4">
                                                Credit Card
                                            </label>
                                        </div>
                                    </div>
                                    @error('payment_type')
                                        <p class="text-danger">{{  $message }}</p>
                                    @enderror
                                </div>
                                <div class="mb-3" id="amountPaid">
                                    <label for="amount_paid" class="form-label">Paid Amount <span class="text-danger">*</span></label>
                                    <input type="number" name="amount_paid" class="form-control" id="amount_paid" value="{{ old('amount_paid') }}" required>
                                    @error('amount_paid')
                                        <p class="text-danger">{{  $message }}</p>
                                    @enderror
                                </div>
                                <div class="calculation mb-3">
                                    <hr>
                                    <div class="d-flex justify-content-between items-center mb-1">
                                        <p><b>Per Installment</b></p>
                                        <p class="text-end" id="perInstallment">0</p>
                                    </div>
                                    <div class="d-flex justify-content-between items-center mb-1">
                                        <p><b>Due</b></p>
                                        <p class="text-end" id="totalDue">0</p>
                                    </div>

                                </div>
                                <div class="mb-3">
                                    <label for="notes" class="form-label">Notes</label>
                                    <textarea name="notes" class="form-control" id="notes" rows="2">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <p class="text-danger">{{  $message }}</p>
                                    @enderror
                                </div>
                                <div class="d-flex justify-content-end align-items-center gap-2">
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                                    <button class="btn btn-primary">Enroll</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
@section('extraJs')

<script>
    window.addEventListener('load', () => {
        const paymentOptionRadios = document.querySelectorAll('input[name="payment_option"]');
        const totalCostInput = document.getElementById('totalCost');
        const amountPaidInput = document.getElementById('amount_paid');
        const totalInstallmentRadios = document.querySelectorAll('input[name="total_installment"]');
        const perInstallmentElement = document.getElementById('perInstallment');
        const totalDueElement = document.getElementById('totalDue');
        const amountPaidDiv = document.getElementById('amountPaid');
        const installmentDetailsDiv = document.getElementById('installmentDetails');
        const fullPaymentFields = document.querySelectorAll('[data-full-payment-field]');
        const paymentTypesDiv = document.getElementById('allPaymentTypes');
        const firstRadioInput = paymentTypesDiv.querySelector('input[type="radio"]');


        function updateCalculations() {
            const totalCost = parseFloat(totalCostInput.value) || 0;
            let amountPaid = parseFloat(amountPaidInput.value) || 0;
            const selectedOption = getSelectedPaymentOption();

            if (selectedOption === 'installment' || selectedOption === 'upfront') {
                const totalInstallmentRadio = document.querySelector('input[name="total_installment"]:checked');
                const totalInstallments = totalInstallmentRadio ? totalInstallmentRadio.value : 1;

                let perInstallment = totalCost / totalInstallments;
                perInstallmentElement.innerText = perInstallment.toFixed(2);

                if (selectedOption === 'upfront') {
                    const remainingAmount = totalCost - amountPaid;
                    totalDueElement.innerText = remainingAmount.toFixed(2);
                    amountPaid = 0;
                    amountPaidInput.disabled = false;
                    amountPaidInput.readOnly = false;
                    amountPaidDiv.style.display = 'block';
                    installmentDetailsDiv.style.display = 'block';
                    paymentTypesDiv.style.display = 'block';
                    if (firstRadioInput) {
                        firstRadioInput.required = true;
                    }
                    // Calculate perInstallment based on the remaining amount
                    if (totalInstallments > 0) {
                        perInstallment = remainingAmount / totalInstallments;
                        perInstallmentElement.innerText = perInstallment.toFixed(2);
                    }
                } else {
                    totalDueElement.innerText = totalCost.toFixed(2);
                    amountPaidInput.disabled = true;
                    amountPaidInput.readOnly = true;
                    amountPaidDiv.style.display = 'none';
                    installmentDetailsDiv.style.display = 'block';
                }
                if(selectedOption === 'installment'){
                    paymentTypesDiv.style.display = 'none';
                    if (firstRadioInput) {
                        firstRadioInput.required = false;
                    }
                }

                for (const fullPaymentField of fullPaymentFields) {
                    fullPaymentField.disabled = false;
                }
            } else if (selectedOption === 'full') {
                amountPaidInput.value = totalCost.toFixed(2);
                perInstallmentElement.innerText = '0.00';
                totalDueElement.innerText = '0.00';
                amountPaidInput.disabled = false;
                amountPaidInput.readOnly = true;
                amountPaidDiv.style.display = 'block';
                installmentDetailsDiv.style.display = 'none';
                paymentTypesDiv.style.display = 'block';
                if (firstRadioInput) {
                    firstRadioInput.required = false;
                }
                for (const fullPaymentField of fullPaymentFields) {
                    fullPaymentField.disabled = true;
                }
            }
        }

        function getSelectedPaymentOption() {
            for (const paymentOptionRadio of paymentOptionRadios) {
                if (paymentOptionRadio.checked) {
                    return paymentOptionRadio.value;
                }
            }
            return null;
        }

        function handleInstallment() {
            updateCalculations();
        }

        function handleFullPayment() {
            updateCalculations();
        }

        for (const paymentOptionRadio of paymentOptionRadios) {
            paymentOptionRadio.addEventListener('change', () => {
                const selectedOption = getSelectedPaymentOption();
                if (selectedOption === 'installment' || selectedOption === 'upfront') {
                    updateCalculations();
                } else if (selectedOption === 'full') {
                    handleFullPayment();
                }
            });
        }

        totalCostInput.addEventListener('keyup', updateCalculations);
        amountPaidInput.addEventListener('keyup', updateCalculations);

        for (const totalInstallmentRadio of totalInstallmentRadios) {
            totalInstallmentRadio.addEventListener('change', updateCalculations);
        }

        // Initial setup based on default selected payment option
        const initialOption = getSelectedPaymentOption();
        if (initialOption === 'installment' || initialOption === 'upfront') {
            updateCalculations();
        } else if (initialOption === 'full') {
            handleFullPayment();
        }
    });
    $('.amount_check').on('click', function () {
            var isChecked = $(this).prop('checked');

            if (isChecked) {
                Swal.fire({
                    title: 'Are you sure to pay?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(this).prop('checked', true);
                    } else {
                        $(this).prop('checked', false);
                    }
                });
            } else {
                Swal.fire({
                    title: 'Are you sure to uncheck?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(this).prop('checked', false);
                    } else {
                        $(this).prop('checked', true);
                    }
                });
            }
        });

</script>

{{-- <script>
    document.addEventListener('DOMContentLoaded', function () {
        var modals = document.querySelectorAll('.common-modal');

        modals.forEach(function (modal) {
            var enrollmentId = modal.id.replace('editEnroll', '');
            var modalInstance = new bootstrap.Modal(modal);

            // Handle the change event for the payment option radio buttons
            var paymentOptions = modal.querySelectorAll('input[name="new_payment_option"]');
            paymentOptions.forEach(function (option) {
                option.addEventListener('change', function () {
                    var totalInstallmentBox = document.getElementById('installmentDetails' + enrollmentId);

                    // Check the selected payment option and toggle visibility accordingly
                    if (option.value === 'full') {
                        totalInstallmentBox.style.display = 'none';

                        var innerRadioFields = totalInstallmentBox.querySelectorAll('input[type="radio"]');
                        innerRadioFields.forEach(function (innerRadio) {
                            innerRadio.disabled = true;
                        });
                    } else if(option.value === 'upfront' || option.value === 'installment') {
                        totalInstallmentBox.style.display = 'block';
                        var innerRadioFields = totalInstallmentBox.querySelectorAll('input[type="radio"]');
                        innerRadioFields.forEach(function (innerRadio) {
                            innerRadio.disabled = false;
                        });
                    }
                });
            });

            // Trigger the change event for the initially selected payment option
            var selectedPaymentOption = modal.querySelector('input[name="new_payment_option"]:checked');
            if (selectedPaymentOption) {
                selectedPaymentOption.dispatchEvent(new Event('change'));
            }
        });
    });
</script> --}}

@endsection
