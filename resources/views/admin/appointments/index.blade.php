@extends('layouts.main')

@section('styles')
    <link href="{{ asset('backend/assets/plugins/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet"/>
@endsection

@section('content')
    {{ csrf_field() }}
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Filter By</h3>
                </div>
                <div class="panel-body">
                <div class="filter_option_wrapper">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Coach</label>
                            <select class="form-control default-select2" id="filter_coach">
                                <option value=""></option>
                                @foreach ($coaches as $coach)
                                    <option value="{{$coach->id}}">{{$coach->first_name.' '.$coach->last_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>User</label>
                            <select class="form-control default-select2" id="filter_user">
                                <option value=""></option>
                                @foreach ($users as $user)
                                    <option value="{{$user->id}}">{{$user->first_name.' '.$user->last_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Category</label>
                            <select class="form-control default-select2" id="filter_category">
                                <option value=""></option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Status</label>
                            <select class="form-control default-select2" id="filter_status">
                                <option value=""></option>
                                <option value="cancelled" {{$filter_status=='cancelled'?'selected':''}}>Cancelled</option>
                                <option value="completed" {{$filter_status=='completed'?'selected':''}}>Completed</option>
                                <option value="scheduled" {{$filter_status=='scheduled'?'selected':''}}>Scheduled</option>
                            </select>
                        </div>
                        <div class="col-md-12 m-t-10">
                            <label>Date Range</label>
                            <div class="input-group" id="default-daterange">
                                <input type="text" readonly name="default-daterange" class="form-control bg-white" value="" placeholder="click to select the date range">
                                <span class="input-group-append">
                                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-12 m-t-10 text-center">
                            <button type="button" id="filterBtn" class="btn btn-primary">Filter</button>
                            <button type="button" id="resetBtn" class="btn btn-primary">Reset</button>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
    <table id="appointments_table" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th class="text-nowrap">Booking No</th>
                <th class="text-nowrap">Date</th>
                <th class="text-nowrap">From</th>
                <th class="text-nowrap">To</th>
                <th class="text-nowrap">Student</th>
                <th class="text-nowrap">Coach</th>
                <th class="text-nowrap">Status</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

    <!-- APPOINTMENT DETAILS MODAL -->
    @include('admin.appointments._appointment_info')
    <!-- APPOINTMENT DETAILS MODAL END -->

    <!-- USER DETAILS MODAL START -->
    @include('admin.users._user_info')
    <!-- USER DETAILS MODAL END -->

@endsection

@section('scripts')
    <script src="{{ asset('backend/assets/plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <!-- ==================   PARTIAL INFO JS   ================== -->
    <script src="{{ asset('backend/js/appointment_info.js') }}"></script>
    <script src="{{ asset('backend/js/user_info.js')}}"></script>
    <!-- ================== PARTIAL INFO JS END ================== -->
    <script>
        var datataTableUrl = baseUrl+'/appointment/datatables';
        var appointmentDataTable;
        $(document).ready(function() {

            // Initialize daterange
            $('#default-daterange').daterangepicker({
                opens:"right",
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear'
                }
            });
            $('#default-daterange').on('apply.daterangepicker', function(ev, picker) {
                $(this).find('input').val(picker.startDate.format('YYYY-MM-DD') + ' TO ' + picker.endDate.format('YYYY-MM-DD'));
            });
            $('#default-daterange').on('cancel.daterangepicker', function(ev, picker) {
                $(this).find('input').val('');
            });

            // Initialize Datatables
            var apiurl  = getUrl();
            appointmentDataTable = $('#appointments_table').DataTable({
                                        responsive: true,
                                        processing: true,
                                        serverSide: true,
                                        ajax: apiurl,
                                        columns: [
                                                {
                                                    data: 'id',
                                                    render: function (data, type, row) {
                                                        return '<a href="javascript:void(0);" onclick="show_appointment_info('+row.id+')"><i class="fa fa-eye"></i> '+row.id+'</a>';
                                                    },
                                                    name: 'id'
                                                },
                                                { 
                                                    data: 'start', 
                                                    name:'start'
                                                },
                                                { data: 'start_time', name:'start', searchable: false},
                                                { data: 'end', name:'end', searchable: false},
                                                { 
                                                    render: function (data, type, row) {
                                                        return '<a href="javascript:void(0);" onclick="showUserInfo('+row.user.id+')"><i class="fa fa-eye"></i> '+row.user.first_name+' '+row.user.last_name+'</a>';
                                                    }, 
                                                    name:'user.first_name'
                                                },
                                                { 
                                                    render: function (data, type, row) {
                                                        return '<a href="javascript:void(0);" onclick="showUserInfo('+row.coach.id+')"><i class="fa fa-eye"></i> '+row.coach.first_name+' '+row.coach.last_name+'</a>';
                                                    },
                                                    name:'coach.first_name'
                                                },
                                                {data: 'status', name: 'status'}
                                            ],
                                        order: [[1, "desc"]],
                                        deferRender: true
                                    });

            $("#filterBtn").click(function(event) {
                var apiurl = getUrl();
                // Refresh Table with filtered data
                appointmentDataTable.ajax.url( apiurl ).load();
            });

            $("#resetBtn").click(function(event) {
                // Refresh Table with unfiltered data
                appointmentDataTable.ajax.url( datataTableUrl ).load();

                // Reset all filter fields
                $(".default-select2").val(null).trigger("change");
                $('#default-daterange input').val(null);
            });

        });

        function getUrl () {
            var apiurl         = datataTableUrl;
            var coach_id       = $("#filter_coach").val();
            var user_id        = $("#filter_user").val();
            var status         = $("#filter_status").val();
            var category_id    = $("#filter_category").val();
            apiurl            += '?status='+status+'&coach_id='+coach_id+'&user_id='+user_id+'&category_id='+category_id;
            var range_val      = $('#default-daterange input').val();
            if(range_val!='') {
                var range_arr  = range_val.split(' TO ');
                var start      = range_arr[0];
                var end        = range_arr[1];
                apiurl        += '&start_range='+start+'&end_range='+end;
            }
            return apiurl;
        }
    </script>
@endsection