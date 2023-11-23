@extends('layouts.main')
@section('title', 'Users')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card w-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h1 class="card-title">All Users</h1>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUser"><span class="me-2"><i class="ti ti-user-plus"></i></span>Add User</button>
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
                              <h6 class="fw-semibold mb-0">E-mail</h6>
                            </th>
                            <th class="border-bottom-0">
                              <h6 class="fw-semibold mb-0">Role</h6>
                            </th>
                            <th class="border-bottom-0">
                              <h6 class="fw-semibold mb-0">Action</h6>
                            </th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $key => $user)
                            @php
                                $serialNumber = ($users->currentPage() - 1) * $users->perPage() + $key + 1;
                                $optionName = ($user->role === 'manager') ? 'Manager' : (($user->role === 'finance') ? 'Finance' :  'Admin');
                            @endphp
                                <tr>
                                    <td class="border-bottom-0"><h6 class="fw-semibold mb-0">{{ $serialNumber }}</h6></td>
                                    <td class="border-bottom-0">
                                        <h6 class="fw-semibold mb-1">{{ $user->name }}</h6>
                                    </td>
                                    <td class="border-bottom-0">
                                        <p>{{ $user->email }}</p>
                                    </td>
                                    <td class="border-bottom-0">
                                        <span class="badge bg-primary rounded-3 pt-1 pb-2">
                                            {{ ucfirst($user->role === 'super-admin' ? 'Super Admin' : ($user->role === 'manager' ? 'Manager' : ($user->role === 'finance' ? 'Finance' : ($user->role === 'admin' ? 'Admin' : 'Undfined')))) }}
                                        </span>

                                    </td>
                                    <td class="border-bottom-0">
                                    <div class="d-flex align-items-center gap-2">
                                        <button class="btn btn-success btn-sm"><i class="ti ti-pencil"></i></button>
                                        <a href="{{ route('users.destroy', $user->id) }}" class="btn btn-danger btn-sm" data-confirm-delete="true">Delete</a>
                                    </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                      </table>
                </div>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="addUser" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Create User</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('users.store') }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" id="name" required>
                                @error('name')
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
                            <div class="mb-3">
                                <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                                <select name="role" class="form-control" id="role">
                                    <option value="">Select a role</option>
                                    @foreach ($roles as $key => $role)
                                    @php
                                        $optionName = ($role->name === 'manager') ? 'Manager' : (($role->name === 'finance') ? 'Finance' : 'Admin');
                                    @endphp
                                        @if ($role->name !== 'super-admin')
                                            <option value="{{ $role->name }}" @if (old('role') == $role->name) selected @endif>{{ $optionName }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('role')
                                    <p class="text-danger">{{  $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control" value="{{ old('password') }}" id="password" required>
                                @error('password')
                                    <p class="text-danger">{{  $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                <input type="password" name="confirm_password" class="form-control" value="{{ old('confirm_password') }}" id="confirm_password" required>
                                @error('confirm_password')
                                    <p class="text-danger">{{  $message }}</p>
                                @enderror
                            </div>
                            <div class="d-flex justify-content-end align-items-center gap-2">
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                                <button class="btn btn-primary">Add User</button>
                            </div>
                        </form>
                    </div>
                </div>
                </div>
            </div>

        </div>
    </div>
@endsection
