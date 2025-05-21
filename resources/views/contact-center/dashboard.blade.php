@extends('layouts.master')

@section('content')
<div class="app-main flex-column flex-row-fluid">
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-fluid">
                <!--begin::Row-->
                <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                    <!--begin::Col-->
                    <div class="col-xl-4">
                        <!--begin::Card widget-->
                        <div class="card card-flush h-md-100">
                            <!--begin::Header-->
                            <div class="card-header pt-5">
                                <!--begin::Title-->
                                <div class="card-title d-flex flex-column">
                                    <!--begin::Amount-->
                                    <span class="fs-2hx fw-bold text-dark me-2 lh-1">{{ $todayCalls }}</span>
                                    <!--end::Amount-->
                                    <!--begin::Subtitle-->
                                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Today's Calls</span>
                                    <!--end::Subtitle-->
                                </div>
                                <!--end::Title-->
                            </div>
                            <!--end::Header-->
                            <!--begin::Card body-->
                            <div class="card-body d-flex flex-column justify-content-end pe-0">
                                <!--begin::Title-->
                                <span class="fs-6 fw-bolder text-gray-800 d-block mb-2">Today's Call Statistics</span>
                                <!--end::Title-->
                                <!--begin::Users group-->
                                <div class="symbol-group symbol-hover flex-nowrap">
                                    <span class="symbol symbol-35px symbol-circle">
                                        <span class="symbol-label bg-success text-inverse-warning fw-bold">{{ $successRate }}%</span>
                                    </span>
                                    <span class="symbol symbol-35px symbol-circle">
                                        <span class="symbol-label bg-danger text-inverse-danger fw-bold">{{ $failureRate }}%</span>
                                    </span>
                                </div>
                                <!--end::Users group-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card widget-->
                    </div>
                    <!--end::Col-->
                    <!--begin::Col-->
                    <div class="col-xl-4">
                        <!--begin::Card widget-->
                        <div class="card card-flush h-md-100">
                            <!--begin::Header-->
                            <div class="card-header pt-5">
                                <!--begin::Title-->
                                <div class="card-title d-flex flex-column">
                                    <!--begin::Amount-->
                                    <span class="fs-2hx fw-bold text-dark me-2 lh-1">{{ $totalCredits }}</span>
                                    <!--end::Amount-->
                                    <!--begin::Subtitle-->
                                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Available Credits</span>
                                    <!--end::Subtitle-->
                                </div>
                                <!--end::Title-->
                            </div>
                            <!--end::Header-->
                            <!--begin::Card body-->
                            <div class="card-body d-flex flex-column justify-content-end pe-0">
                                <!--begin::Title-->
                                <span class="fs-6 fw-bolder text-gray-800 d-block mb-2">Credit Usage Today</span>
                                <!--end::Title-->
                                <!--begin::Progress-->
                                <div class="progress h-8px bg-light-primary">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $creditUsagePercent }}%" aria-valuenow="{{ $creditUsagePercent }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <!--end::Progress-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card widget-->
                    </div>
                    <!--end::Col-->
                    <!--begin::Col-->
                    <div class="col-xl-4">
                        <!--begin::Card widget-->
                        <div class="card card-flush h-md-100">
                            <!--begin::Header-->
                            <div class="card-header pt-5">
                                <!--begin::Title-->
                                <div class="card-title d-flex flex-column">
                                    <!--begin::Amount-->
                                    <span class="fs-2hx fw-bold text-dark me-2 lh-1">{{ $totalMinutes }}</span>
                                    <!--end::Amount-->
                                    <!--begin::Subtitle-->
                                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Minutes Used</span>
                                    <!--end::Subtitle-->
                                </div>
                                <!--end::Title-->
                            </div>
                            <!--end::Header-->
                            <!--begin::Card body-->
                            <div class="card-body pt-2 pb-4 d-flex flex-wrap align-items-center">
                                <!--begin::Chart-->
                                <div class="d-flex flex-center me-5 pt-2">
                                    <div id="minutesChart" style="min-width: 70px; min-height: 70px" data-kt-size="70" data-kt-line="11">
                                    </div>
                                </div>
                                <!--end::Chart-->
                                <!--begin::Labels-->
                                <div class="d-flex flex-column content-justify-center flex-row-fluid">
                                    <!--begin::Label-->
                                    <div class="d-flex fw-semibold align-items-center">
                                        <!--begin::Bullet-->
                                        <div class="bullet w-8px h-3px rounded-2 bg-success me-3"></div>
                                        <!--end::Bullet-->
                                        <!--begin::Label-->
                                        <div class="text-gray-500 flex-grow-1 me-4">Successful Calls</div>
                                        <!--end::Label-->
                                        <!--begin::Stats-->
                                        <div class="fw-bolder text-gray-700 text-xxl-end">{{ $successfulCalls }}</div>
                                        <!--end::Stats-->
                                    </div>
                                    <!--end::Label-->
                                    <!--begin::Label-->
                                    <div class="d-flex fw-semibold align-items-center my-3">
                                        <!--begin::Bullet-->
                                        <div class="bullet w-8px h-3px rounded-2 bg-danger me-3"></div>
                                        <!--end::Bullet-->
                                        <!--begin::Label-->
                                        <div class="text-gray-500 flex-grow-1 me-4">Failed Calls</div>
                                        <!--end::Label-->
                                        <!--begin::Stats-->
                                        <div class="fw-bolder text-gray-700 text-xxl-end">{{ $failedCalls }}</div>
                                        <!--end::Stats-->
                                    </div>
                                    <!--end::Label-->
                                </div>
                                <!--end::Labels-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card widget-->
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Row-->

                <!--begin::Recent Calls-->
                <div class="card mb-5 mb-xl-8">
                    <!--begin::Card header-->
                    <div class="card-header border-0 pt-6">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <!--begin::Search-->
                            <div class="d-flex align-items-center position-relative my-1">
                                <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                                <input type="text" data-kt-call-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="Search Calls" />
                            </div>
                            <!--end::Search-->
                        </div>
                        <!--begin::Card title-->
                        <!--begin::Card toolbar-->
                        <div class="card-toolbar">
                            <!--begin::Toolbar-->
                            <div class="d-flex justify-content-end" data-kt-call-table-toolbar="base">
                                <!--begin::Add Call-->
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_call">
                                    <i class="ki-outline ki-plus fs-2"></i>New Call
                                </button>
                                <!--end::Add Call-->
                            </div>
                            <!--end::Toolbar-->
                        </div>
                        <!--end::Card toolbar-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <!--begin::Table-->
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_calls_table">
                            <thead>
                                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-125px">Caller</th>
                                    <th class="min-w-125px">Receiver</th>
                                    <th class="min-w-125px">Duration</th>
                                    <th class="min-w-125px">Status</th>
                                    <th class="min-w-125px">Date</th>
                                    <th class="min-w-70px">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                @foreach($recentCalls as $call)
                                <tr>
                                    <td>{{ $call->caller_number }}</td>
                                    <td>{{ $call->receiver_number }}</td>
                                    <td>{{ $call->duration }} seconds</td>
                                    <td>
                                        @if($call->status === 'completed')
                                        <span class="badge badge-success">Completed</span>
                                        @elseif($call->status === 'failed')
                                        <span class="badge badge-danger">Failed</span>
                                        @else
                                        <span class="badge badge-primary">{{ ucfirst($call->status) }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $call->created_at->diffForHumans() }}</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-light btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            Actions
                                            <i class="ki-outline ki-down fs-5 ms-1"></i>
                                        </a>
                                        <!--begin::Menu-->
                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                            @if($call->recording_url)
                                            <div class="menu-item px-3">
                                                <a href="{{ $call->recording_url }}" class="menu-link px-3" target="_blank">
                                                    Listen
                                                </a>
                                            </div>
                                            @endif
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3" data-call-id="{{ $call->id }}">
                                                    Details
                                                </a>
                                            </div>
                                        </div>
                                        <!--end::Menu-->
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <!--end::Table-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Recent Calls-->
            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Content wrapper-->
</div>

<!--begin::Modal - Add Call-->
<div class="modal fade" id="kt_modal_add_call" tabindex="-1" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header" id="kt_modal_add_call_header">
                <!--begin::Modal title-->
                <h2 class="fw-bold">New Call</h2>
                <!--end::Modal title-->
                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-calls-modal-action="close">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
                <!--end::Close-->
            </div>
            <!--end::Modal header-->
            <!--begin::Modal body-->
            <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                <!--begin::Form-->
                <form id="kt_modal_add_call_form" class="form" action="#">
                    <!--begin::Input group-->
                    <div class="fv-row mb-7">
                        <!--begin::Label-->
                        <label class="required fw-semibold fs-6 mb-2">From Number</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input type="text" name="from_number" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Enter caller number" />
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="fv-row mb-7">
                        <!--begin::Label-->
                        <label class="required fw-semibold fs-6 mb-2">To Number</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input type="text" name="to_number" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Enter receiver number" />
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="fv-row mb-7">
                        <!--begin::Label-->
                        <label class="fw-semibold fs-6 mb-2">Record Call</label>
                        <!--end::Label-->
                        <!--begin::Switch-->
                        <div class="form-check form-switch form-check-custom form-check-solid">
                            <input class="form-check-input" type="checkbox" name="recording_enabled" value="1" id="recording_enabled" />
                            <label class="form-check-label" for="recording_enabled">
                                Enable call recording
                            </label>
                        </div>
                        <!--end::Switch-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Actions-->
                    <div class="text-center pt-15">
                        <button type="reset" class="btn btn-light me-3" data-kt-calls-modal-action="cancel">
                            Discard
                        </button>
                        <button type="submit" class="btn btn-primary" data-kt-calls-modal-action="submit">
                            <span class="indicator-label">
                                Submit
                            </span>
                            <span class="indicator-progress">
                                Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                    <!--end::Actions-->
                </form>
                <!--end::Form-->
            </div>
            <!--end::Modal body-->
        </div>
        <!--end::Modal content-->
    </div>
    <!--end::Modal dialog-->
</div>
<!--end::Modal - Add Call-->

@endsection

@push('scripts')
<script>
    "use strict";

    // Class definition
    var KTCallsDashboard = function () {
        // Private functions
        var initCallTable = function () {
            // Set up the datatable
            var table = $("#kt_calls_table").DataTable({
                "info": false,
                'order': [],
                "pageLength": 10,
                "lengthChange": false,
                'columnDefs': [
                    { orderable: false, targets: 5 }, // Disable ordering on the actions column
                ]
            });

            // Search functionality
            var handleSearchDatatable = () => {
                const filterSearch = document.querySelector('[data-kt-call-table-filter="search"]');
                filterSearch.addEventListener('keyup', function (e) {
                    table.search(e.target.value).draw();
                });
            }

            handleSearchDatatable();
        }

        var initMinutesChart = function () {
            var element = document.getElementById('minutesChart');
            
            var options = {
                size: parseInt(element.getAttribute('data-kt-size')),
                lineWidth: parseInt(element.getAttribute('data-kt-line')),
                rotate: parseInt(element.getAttribute('data-kt-rotate')),
            }

            var minutesUsed = {{ $totalMinutes }};
            var totalAllowed = {{ $totalAllowedMinutes }};
            var percentage = (minutesUsed / totalAllowed) * 100;

            // Create chart using Chart.js
            new Chart(element, {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: [percentage, 100 - percentage],
                        backgroundColor: ['#009EF7', '#E4E6EF']
                    }]
                },
                options: {
                    cutout: '75%',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }

        var handleNewCallForm = function() {
            const form = document.getElementById('kt_modal_add_call_form');
            const submitButton = form.querySelector('[data-kt-calls-modal-action="submit"]');
            const modal = new bootstrap.Modal(document.getElementById('kt_modal_add_call'));

            submitButton.addEventListener('click', function (e) {
                e.preventDefault();

                submitButton.setAttribute('data-kt-indicator', 'on');
                submitButton.disabled = true;

                const fromNumber = form.querySelector('[name="from_number"]').value;
                const toNumber = form.querySelector('[name="to_number"]').value;
                const recordingEnabled = form.querySelector('[name="recording_enabled"]').checked;

                fetch('{{ route("contact-center.initiate-call") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        from_number: fromNumber,
                        to_number: toNumber,
                        recording_enabled: recordingEnabled
                    })
                })
                .then(response => response.json())
                .then(data => {
                    submitButton.removeAttribute('data-kt-indicator');
                    submitButton.disabled = false;

                    if (data.success) {
                        form.reset();
                        modal.hide();
                        
                        // Show success message and reload table
                        Swal.fire({
                            text: data.message,
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        }).then(function (result) {
                            if (result.isConfirmed) {
                                location.reload(); // Reload to show new call in table
                            }
                        });
                    } else {
                        Swal.fire({
                            text: data.message,
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        });
                    }
                })
                .catch(error => {
                    submitButton.removeAttribute('data-kt-indicator');
                    submitButton.disabled = false;

                    Swal.fire({
                        text: "Sorry, it seems there are some errors detected, please try again.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });
                });
            });
        }

        // Public methods
        return {
            init: function () {
                initCallTable();
                initMinutesChart();
                handleNewCallForm();
            }
        }
    }();

    // On document ready
    document.addEventListener("DOMContentLoaded", function() {
        KTCallsDashboard.init();
    });
</script>
@endpush