@include('layouts.header')

@if (session('success'))
    <div id="successMessage" class="alert alert-success">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div id="errorMessage" class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="page-header">
    <div class="add-item d-flex">
        <div class="page-title">
            <h4 class="fw-bold">Brand</h4>
            <h6>Manage your brands</h6>
        </div>
    </div>
    <ul class="table-top-head">
        <li>
            <a data-bs-toggle="tooltip" data-bs-placement="top" title="Pdf"><img src="{{ asset('assets/img/icons/pdf.svg') }}" alt="img"></a>
        </li>
        <li>
            <a data-bs-toggle="tooltip" data-bs-placement="top" title="Excel"><img src="{{ asset('assets/img/icons/excel.svg') }}" alt="img"></a>
        </li>
        <li>
            <a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh"><i class="ti ti-refresh"></i></a>
        </li>
        <li>
            <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i class="ti ti-chevron-up"></i></a>
        </li>
    </ul>
    <div class="page-btn">
        <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-brand"><i class="ti ti-circle-plus me-1"></i>Add Brand</a>
    </div>
</div>

<!-- Brand List -->
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3">
        <div class="search-set">
            <div class="search-input">
                <span class="btn-searchset"><i class="ti ti-search fs-14 feather-search"></i></span>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table datatable">
                <thead class="thead-light">
                    <tr>
                        <th class="no-sort">
                            <label class="checkboxs">
                                <input type="checkbox" id="select-all">
                                <span class="checkmarks"></span>
                            </label>
                        </th>
                        <th>Brand</th>
                        <th>Created Date</th>
                        <th>Created By</th>
                        <th class="no-sort"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($brands as $brand)
                        <tr>
                            <td>
                                <label class="checkboxs">
                                    <input type="checkbox" name="selected_brands[]" value="{{ $brand->id }}">
                                    <span class="checkmarks"></span>
                                </label>
                            </td>
                            <td><span class="text-gray-9">{{ $brand->name }}</span></td>
                            <td>{{ \Carbon\Carbon::parse($brand->created_at)->format('d M Y') }}</td>
                            <td>
                                <span class="text-gray-9">
                                    @if ($brand->employee_id && $brand->employee)
                                        {{ $brand->employee->name }}
                                    @elseif ($brand->user_id && $brand->user)
                                        {{ $brand->user->name }}
                                    @else
                                        Unknown
                                    @endif
                                </span>
                            </td>
                            <td class="action-table-data">
                                <div class="edit-delete-action">
                                    <a class="me-2 p-2" href="#" data-bs-toggle="modal" data-bs-target="#edit-brand-{{ $brand->id }}">
                                        <i data-feather="edit" class="feather-edit"></i>
                                    </a>
                                    <a data-bs-toggle="modal" data-bs-target="#delete-modal-{{ $brand->id }}" class="p-2" href="javascript:void(0);">
                                        <i data-feather="trash-2" class="feather-trash-2"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td></td>
                            <td></td>
                            <td>No brands found.</td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Brand Modal -->
<div class="modal fade" id="add-brand">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="page-title">
                    <h4>Add Brand</h4>
                </div>
                <button type="button" class="close bg-danger text-white fs-16" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('brands.store') }}" method="POST">
                @csrf
                <div class="modal-body new-employee-field">
                    <div class="mb-3">
                        <label class="form-label">Brand<span class="text-danger ms-1">*</span></label>
                        <input type="text" class="form-control" name="name" value="{{ old('name') }}">
                        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-2 btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Brand</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Brand Modals -->
@foreach ($brands as $brand)
    <div class="modal fade" id="edit-brand-{{ $brand->id }}">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="page-title">
                        <h4>Edit Brand</h4>
                    </div>
                    <button type="button" class="close bg-danger text-white fs-16" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('brands.update', $brand->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body new-employee-field">
                        <div class="mb-3">
                            <label class="form-label">Brand<span class="text-danger ms-1">*</span></label>
                            <input type="text" class="form-control" name="name" value="{{ old('name', $brand->name) }}">
                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn me-2 btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

@include('layouts.footer')

<!-- Delete Brand Modals -->
@foreach ($brands as $brand)
    <div class="modal fade" id="delete-modal-{{ $brand->id }}">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="page-wrapper-new p-0">
                    <div class="content p-5 px-3 text-center">
                        <span class="rounded-circle d-inline-flex p-2 bg-danger-transparent mb-2"><i class="ti ti-trash fs-24 text-danger"></i></span>
                        <h4 class="fs-20 fw-bold mb-2 mt-1">Delete Brand</h4>
                        <p class="mb-0 fs-16">Are you sure you want to delete {{ $brand->name }}?</p>
                        <div class="modal-footer-btn mt-3 d-flex justify-content-center">
                            <button type="button" class="btn me-2 btn-secondary fs-13 fw-medium p-2 px-3 shadow-none" data-bs-dismiss="modal">Cancel</button>
                            <form action="{{ route('brands.destroy', $brand->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-primary fs-13 fw-medium p-2 px-3">Yes Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach

<script>
    $(document).ready(function() {
        // Hide success message after 3 seconds
        setTimeout(function() {
            $('#successMessage').fadeOut('slow');
            $('#errorMessage').fadeOut('slow');
        }, 3000); // 3000 ms = 3 seconds
    });
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Initialize DataTable
    // $('.datatable').DataTable({
    //     searching: true,
    //     paging: true,
    //     ordering: true,
    //     info: true,
    //     columnDefs: [
    //         { orderable: false, targets: ['no-sort'] }
    //     ],
    //     language: {
    //         emptyTable: "No brands found."
    //     }
    // });

    // Select All Checkbox
    $('#select-all').on('click', function () {
        $('input[name="selected_brands[]"]').prop('checked', this.checked);
    });
});
</script>