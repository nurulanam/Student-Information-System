@extends('layouts.main')
@section('extraMeta')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('title', 'Payments')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card w-100">
            <div class="card-header d-flex flex-column flex-lg-row justify-content-md-between align-items-center">
                <h1 class="card-title">All Enrollments</h1>
                <div class="d-flex flex-wrap flex-md-nowrap justify-content-center justify-content-md-end align-items-center gap-3">
                    <a href="{{ route('payments.index') }}" class="btn btn-danger order-1 order-md-0"><i class="ti ti-reload"></i></a>
                    <form action="{{ route('payments.index') }}" method="get" class="order-0 order-md-1">
                        <div class="d-flex flex-wrap flex-md-nowrap  align-items-center">
                            <input type="text" name="search" class="form-control" placeholder="Search by Enrollment Id" value="{{ request('search') }}" aria-describedby="search">
                            <span class="d-flex align-items-center gap-2 ms-2">
                                <label for="" class="label flex-shrink-0">From</label>
                                <input type="date" name="from_date" class="form-control" placeholder="From Date" value="{{ request('from_date') }}" aria-describedby="fromdate">
                            </span>
                            <span class="d-flex align-items-center gap-2 ms-2">
                                <label for="" class="label flex-shrink-0">To</label>
                                <input type="date" name="to_date" class="form-control" placeholder="To Date" value="{{ request('to_date') }}" aria-describedby="todate">
                            </span>
                            <button class="btn btn-primary ms-2"id="search"><i class="ti ti-search"></i></button>
                        </div>
                    </form>
                    <button type="button" class="btn btn-primary order-2 order-md-2" data-bs-toggle="modal" data-bs-target="#addPayment"><span class="me-2"><i class="ti ti-file"></i></span>Pay Installment</button>
                </div>
            </div>
            <div class="card-body p-2 overflow-x-scroll">
                <table class="table table-borderless text-nowrap table-hover mb-0 align-middle">
                    <thead class="text-dark fs-4">
                    <tr>
                        <th>
                            <h6 class="fw-semibold mb-0">Enroll Id</h6>
                        </th>
                        <th>
                            <h6 class="fw-semibold mb-0">Installment</h6>
                        </th>
                        <th>
                            <h6 class="fw-semibold mb-0">Installment No</h6>
                        </th>
                        <th>
                            <h6 class="fw-semibold mb-0">Amount</h6>
                        </th>
                        <th>
                            <h6 class="fw-semibold mb-0">Payment Type</h6>
                        </th>
                        <th>
                            <h6 class="fw-semibold mb-0">Notes</h6>
                        </th>
                        <th>
                            <h6 class="fw-semibold mb-0">Action</h6>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($payments as $payment)
                            <tr>
                                <td>{{ $payment->enrollment->enroll_id}}</td>
                                <td>
                                    @if ($payment->is_installment === 1)
                                        <span class="badge bg-primary">True</span>
                                    @else
                                        <span class="badge bg-danger">False</span>
                                    @endif
                                </td>
                                <td><span class="badge bg-primary">{{ $payment->installment_number }}</span></td>
                                <td>{{ $payment->amount_paid }}</td>
                                <td>
                                    @if ($payment->payment_type === 'cash')
                                        Cash
                                    @elseif ($payment->payment_type === 'bank_transfer')
                                        Bank Transfer
                                    @elseif ($payment->payment_type === 'direct_debit')
                                        Direct Debit
                                    @elseif ($payment->payment_type === 'credit_card')
                                        Credit Card
                                    @endif
                                </td>
                                <td>{{ $payment->notes }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#editEnroll{{ $payment->id }}"><i class="ti ti-pencil"></i></button>
                                        <a href="{{ route('payments.destroy', $payment->id) }}" class="btn btn-danger btn-sm" data-confirm-delete="true">Delete</a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
        <div class="d-flex justify-content-end align-items-end">
            {!! $payments->links() !!}
        </div>

        <div class="modal fade" id="addPayment" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Pay Installment</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- resources/views/installments/create.blade.php -->

                    <form action="{{ route('payments.store') }}" method="post">
                        @csrf
                        <div class="mb-3">
                            <label for="enrollmentId" class="form-label">Enrollment Id<span class="text-danger">*</span></label>
                            <input type="text" name="enrollment_id" class="form-control" id="enrollmentId" required>
                            @error('enrollment_id')
                                <p class="text-danger">{{  $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="installmentAmount" class="form-label">Installment Amount<span class="text-danger">*</span></label>
                            <input type="number" name="amount_paid" class="form-control" step="any" min="0" id="installmentAmount" required>
                        </div>
                        <div class="mb-3" id="returnMessageBox">
                            <p id="returnMessage"></p>
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
                            <button class="btn btn-primary" id="PayInstallmentBtn">Pay Installment</button>
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
    $(document).ready(function () {
        // Initial AJAX request on page load
        if ($('#enrollmentId').val()) {
            fetchEnrollmentData();
        }

        var timer;

        // Attach the event handler for keyup and change on #enrollmentId
        $('#enrollmentId').on('keyup change', function () {
            // Clear previous timer
            if ($('#enrollmentId').val()) {
                timer = setTimeout(function () {
                    fetchEnrollmentData();
                }, 500);
            }else{
                $('#installmentAmount').val(' ');
            }
        });

        function fetchEnrollmentData() {
            var enrollmentId = $('#enrollmentId').val();
            console.log(enrollmentId);

            // Set the CSRF token value
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Make an AJAX request
            $.ajax({
                type: 'POST',
                url: '/dashboard/payments/find-enrollment',
                data: {
                    enrollmentId: enrollmentId,
                },
                dataType: 'json',
                success: function (data) {
                    var dueAmount = data.due_amount;
                    var message = data.message;
                    var installmentLeft = data.installment_left;

                    $('#installmentAmount').val(' ');
                    if (data.success) {
                        $('#returnMessage').text(message +' // '+ installmentLeft);
                        $('#returnMessage').removeClass('text-danger');
                        $('#returnMessage').addClass('text-dark');
                        $('#returnMessageBox').show();

                        $('#PayInstallmentBtn').removeAttr('type');
                        $('#PayInstallmentBtn').prop('disabled', false);
                    } else {
                        $('#returnMessage').text(message);
                        $('#returnMessage').removeClass('text-dark');
                        $('#returnMessage').addClass('text-danger');
                        $('#returnMessageBox').show();

                        $('#PayInstallmentBtn').attr('type', 'button');
                        $('#PayInstallmentBtn').prop('disabled', true);

                    }
                },
                error: function (error) {
                    $('#installmentAmount').val(' ');
                    $('#returnMessageBox').hide();

                    $('#PayInstallmentBtn').removeAttr('type');
                    $('#PayInstallmentBtn').prop('disabled', false);
                    // console.error('Error fetching data:', error.responseText);
                }
            });
        }
    });
</script>

@endsection
