@extends('layouts.main')
@section('title', 'Students')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card w-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h1 class="card-title">All Students</h1>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStudent"><span class="me-2"><i class="ti ti-user-plus"></i></span>Add Student</button>
                </div>
                <div class="card-body overflow-x-scroll">
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
                                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#editUser"><i class="ti ti-pencil"></i></button>
                                        <a href="{{ route('users.destroy', $student->id) }}" class="btn btn-danger btn-sm" data-confirm-delete="true">Delete</a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
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
