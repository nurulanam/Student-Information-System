@extends('layouts.main')
@section('title', 'Students')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card w-100">
                <div class="card-header d-flex flex-column flex-md-row justify-content-md-between align-items-center">
                    <h1 class="card-title">All Students</h1>
                    <div class="d-flex flex-wrap flex-md-nowrap justify-content-center justify-content-md-end align-items-center gap-3">
                        <a href="{{ route('students.index') }}" class="btn btn-danger order-1 order-md-0"><i class="ti ti-reload"></i></a>
                        <form action="{{ route('students.index') }}" method="get" class="order-0 order-md-1">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Search by Id, Name, E-mail, Phone" aria-describedby="search">
                                <button class="btn btn-primary"id="search"><i class="ti ti-search"></i></button>
                            </div>
                        </form>
                        <button type="button" class="btn btn-primary order-2 order-md-2" data-bs-toggle="modal" data-bs-target="#addStudent"><span class="me-2"><i class="ti ti-user-plus"></i></span>Add Student</button>
                    </div>
                </div>
                <div class="card-body p-2 overflow-x-scroll">
                    <table class="table text-nowrap table-hover mb-0 align-middle">
                        <thead class="text-dark fs-4">
                          <tr>
                            <th class="border-bottom-0">
                              <h6 class="fw-semibold mb-0">Id</h6>
                            </th>
                            <th class="border-bottom-0">
                              <h6 class="fw-semibold mb-0">Name</h6>
                            </th>
                            <th class="border-bottom-0">
                              <h6 class="fw-semibold mb-0">Phone</h6>
                            </th>
                            <th class="border-bottom-0">
                              <h6 class="fw-semibold mb-0">Email</h6>
                            </th>
                            <th class="border-bottom-0">
                              <h6 class="fw-semibold mb-0">Action</h6>
                            </th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $student)
                            <tr>
                                <td class="border-bottom-0">{{ $student->std_id }}</td>
                                <td class="border-bottom-0">{{ $student->full_name }}</td>
                                <td class="border-bottom-0">{{ $student->phone }}</td>
                                <td class="border-bottom-0">{{ $student->email }}</td>
                                <td class="border-bottom-0">
                                    <div class="d-flex align-items-center gap-2">
                                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#editStudent{{ $student->std_id }}"><i class="ti ti-pencil"></i></button>
                                        <a href="{{ route('students.destroy', $student->std_id) }}" class="btn btn-danger btn-sm" data-confirm-delete="true">Delete</a>
                                    </div>
                                </td>
                            </tr>
                            {{-- edit modal  --}}
                            <div class="modal fade" id="editStudent{{ $student->std_id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Update Student</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('students.update') }}" method="post">
                                            @method('PUT')
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $student->id }}">
                                            <div class="mb-3">
                                                <label for="newName" class="form-label">Full Name <span class="text-danger">*</span></label>
                                                <input type="text" name="new_name" class="form-control" value="{{ $student->full_name }}" id="newName" required>
                                                @error('new_name')
                                                    <p class="text-danger">{{  $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="newPhone" class="form-label">Phone number <span class="text-danger">*</span></label>
                                                <input type="tel" name="new_phone" class="form-control" value="{{ $student->phone }}" id="newPhone" required>
                                                @error('new_phone')
                                                    <p class="text-danger">{{  $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="newEmail" class="form-label">Email address <span class="text-danger">*</span></label>
                                                <input type="email" name="new_email" class="form-control" value="{{ $student->email }}" id="newEmail" required>
                                                @error('new_email')
                                                    <p class="text-danger">{{  $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="d-flex justify-content-end align-items-center gap-2">
                                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                                                <button class="btn btn-primary">Update Student</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="d-flex justify-content-end align-items-end">
                {!! $students->links() !!}
            </div>

            <!-- Modal -->
            <div class="modal fade" id="addStudent" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Create Student</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('students.store') }}" method="post">
                                @csrf
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" id="name" required>
                                    @error('name')
                                        <p class="text-danger">{{  $message }}</p>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone number <span class="text-danger">*</span></label>
                                    <input type="tel" name="phone" class="form-control" value="{{ old('phone') }}" id="phone" required>
                                    @error('phone')
                                        <p class="text-danger">{{  $message }}</p>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email address <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" id="email" required>
                                    @error('email')
                                        <p class="text-danger">{{  $message }}</p>
                                    @enderror
                                </div>
                                <div class="d-flex justify-content-end align-items-center gap-2">
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                                    <button class="btn btn-primary">Add Student</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
