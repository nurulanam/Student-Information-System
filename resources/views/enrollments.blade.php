@extends('layouts.main')
@section('title', 'Enrollments')
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
                    <table class="table text-nowrap table-hover mb-0 align-middle">
                        <thead class="text-dark fs-4">
                        <tr>
                            <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Invoice</h6>
                            </th>
                            <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Student Id</h6>
                            </th>
                            <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Program</h6>
                            </th>
                            <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Total Cost</h6>
                            </th>
                            <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Installments</h6>
                            </th>
                            <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Status</h6>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border-bottom-0">In1234568</td>
                                <td class="border-bottom-0">St1232345543</td>
                                <td class="border-bottom-0">Php with Laravel</td>
                                <td class="border-bottom-0">500</td>
                                <td class="border-bottom-0">5</td>
                                <td class="border-bottom-0">
                                    <span class="badge bg-success"><i class="ti ti-arrow-up"></i></span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
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
                                        <option value="" selected>Select Program</option>
                                        @foreach ($programs as $program )
                                            <option value="{{ $program->id }}" @if (old('program_id') === $program->id) @endif>{{ $program->name }}</option>
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
                                            <input class="form-check-input" type="radio" name="payment_option" value="full" id="paymentOptionFull" checked required>
                                            <label class="form-check-label" for="paymentOptionFull">
                                                Full Payment
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_option" value="upfront" id="paymentOptionUpFront" checked required>
                                            <label class="form-check-label" for="paymentOptionUpFront">
                                                Up Front
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_option" value="installment" id="paymentOptionInstallment">
                                            <label class="form-check-label" for="paymentOptionInstallment">
                                                Installments
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3" id="installmentDetails">
                                    <label for="totalInstallment" class="form-label">Installments <span class="text-danger">*</span></label>
                                    <div class="d-flex flex-wrap flex-md-nowrap align-items-center gap-2 gap-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="total_installment" value="1" id="totalInstallment1" data-full-payment-field>
                                            <label class="form-check-label" for="totalInstallment1">
                                             1
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="total_installment" value="2" id="totalInstallment2" data-full-payment-field>
                                            <label class="form-check-label" for="totalInstallment2">
                                             2
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="total_installment" value="3" id="totalInstallment3" data-full-payment-field>
                                            <label class="form-check-label" for="totalInstallment3">
                                             3
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="total_installment" value="4" id="totalInstallment4" data-full-payment-field>
                                            <label class="form-check-label" for="totalInstallment4">
                                             4
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="total_installment" value="5" id="totalInstallment5" data-full-payment-field>
                                            <label class="form-check-label" for="totalInstallment5">
                                             5
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="total_installment" value="6" id="totalInstallment6" data-full-payment-field>
                                            <label class="form-check-label" for="totalInstallment6">
                                             6
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="total_installment" value="7" id="totalInstallment7" data-full-payment-field>
                                            <label class="form-check-label" for="totalInstallment7">
                                             7
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="total_installment" value="8" id="totalInstallment8" data-full-payment-field>
                                            <label class="form-check-label" for="totalInstallment8">
                                             8
                                            </label>
                                        </div>
                                    </div>
                                    @error('total_installment')
                                        <p class="text-danger">{{  $message }}</p>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="paymentType" class="form-label">Payment Type <span class="text-danger">*</span></label>
                                    <div class="d-flex flex-wrap flex-md-nowrap align-items-center gap-2 gap-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_type" value="cash" id="paymentType1">
                                            <label class="form-check-label" for="paymentType1">
                                                Cash
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_type" value="bank_transfer" id="paymentType2">
                                            <label class="form-check-label" for="paymentType2">
                                                Bank Transfer
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_type" value="direct_debit" id="paymentType3">
                                            <label class="form-check-label" for="paymentType3">
                                                Direct Debit
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_type" value="credit_card" id="paymentType4">
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
                                    <label for="amountPaid" class="form-label">Paid Amount <span class="text-danger">*</span></label>
                                    <input type="number" name="amount_paid" class="form-control" id="amountPaid" value="{{ old('details') }}" required>
                                    @error('amount_paid')
                                        <p class="text-danger">{{  $message }}</p>
                                    @enderror
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
        let selectedOption = null;

        for (const paymentOptionRadio of paymentOptionRadios) {
            if (paymentOptionRadio.checked) {
            selectedOption = paymentOptionRadio.value;
            }

            paymentOptionRadio.addEventListener('change', (event) => {
                const paymentOptionValue = event.target.value;
                console.log(paymentOptionValue);

                if (paymentOptionValue === 'full' || paymentOptionValue == "upfront") {
                    // Handle full payment option
                    handleFullPayment();
                } else if (paymentOptionValue === 'installment') {
                    // Handle installment option
                    handleInstallment();
                }
            });
        }

        if (selectedOption === 'full' || selectedOption === 'upfront') {
            handleFullPayment();
        } else if (selectedOption === 'installment') {
            handleInstallment();
        }
        });

    function handleFullPayment() {
        // Disable installment details section
        const installmentDetailsElement = document.getElementById('installmentDetails');
        installmentDetailsElement.style.display = 'none';

        // Enable full payment fields
        const fullPaymentFields = document.querySelectorAll('[data-full-payment-field]');
        for (const fullPaymentField of fullPaymentFields) {
            fullPaymentField.disabled = true;
        }
    }

    function handleInstallment() {
        // Show installment details section
        const installmentDetailsElement = document.getElementById('installmentDetails');
        installmentDetailsElement.style.display = 'block'; // Change this line

        // Disable full payment fields
        const fullPaymentFields = document.querySelectorAll('[data-full-payment-field]');
        for (const fullPaymentField of fullPaymentFields) {
            fullPaymentField.disabled = false;
        }
    }


</script>
@endsection
