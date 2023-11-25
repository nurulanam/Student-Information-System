@extends('layouts.main')
@section('title', 'Programs')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card w-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h1 class="card-title">All Programs</h1>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProgram"><span class="me-2"><i class="ti ti-plus"></i></span>Add Program</button>
            </div>
            <div class="card-body p-2 overflow-x-scroll">
                <table class="table text-nowrap table-hover mb-0 align-middle">
                    <thead class="text-dark fs-4">
                      <tr>
                        <th class="border-bottom-0">
                          <h6 class="fw-semibold mb-0">#</h6>
                        </th>
                        <th class="border-bottom-0">
                          <h6 class="fw-semibold mb-0">Program Name</h6>
                        </th>
                        <th class="border-bottom-0">
                          <h6 class="fw-semibold mb-0">Details</h6>
                        </th>
                        <th class="border-bottom-0">
                          <h6 class="fw-semibold mb-0">Action</h6>
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($programs as $key => $program)
                            @php
                                $serialNumber = ($programs->currentPage() - 1) * $programs->perPage() + $key + 1;
                            @endphp
                            <tr>
                                <td class="border-bottom-0">{{ $serialNumber }}</td>
                                <td class="border-bottom-0">{{ $program->name }}</td>
                                <td class="border-bottom-0">{{ Str::limit($program->details, 100) }}</td>
                                <td class="border-bottom-0">
                                    <div class="d-flex align-items-center gap-2">
                                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#editprogram{{ $program->id }}"><i class="ti ti-pencil"></i></button>
                                        <a href="{{ route('programs.destroy', $program->id) }}" class="btn btn-danger btn-sm" data-confirm-delete="true">Delete</a>
                                    </div>
                                </td>
                            </tr>
                            {{-- edit program  --}}
                            <div class="modal fade" id="editprogram{{ $program->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Update Program</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('programs.update') }}" method="post">
                                            @method('PUT')
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $program->id }}">
                                            <div class="mb-3">
                                                <label for="newName" class="form-label">Program Name <span class="text-danger">*</span></label>
                                                <input type="text" name="new_name" class="form-control" value="{{ $program->name }}" id="newName" required>
                                                @error('new_name')
                                                    <p class="text-danger">{{  $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="newDetails" class="form-label">Details <span class="text-danger">*</span></label>
                                                <textarea name="new_details" class="form-control" id="newDetails" rows="7" required>{{ $program->details }}</textarea>
                                                @error('new_details')
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
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="addProgram" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Create Program</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('programs.store') }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Program Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" id="name" required>
                                @error('name')
                                    <p class="text-danger">{{  $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="details" class="form-label">Details <span class="text-danger">*</span></label>
                                <textarea name="details" class="form-control" id="details" rows="7" required>{{ old('details') }}</textarea>
                                @error('details')
                                    <p class="text-danger">{{  $message }}</p>
                                @enderror
                            </div>
                            <div class="d-flex justify-content-end align-items-center gap-2">
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                                <button class="btn btn-primary">Add Program</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
