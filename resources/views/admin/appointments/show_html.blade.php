@php
    $coach = $appointment->coach;
    $user  = $appointment->user;
@endphp
<div class="table-responsive">
    <table class="table table-profile">
        <tbody>
            <tr class="highlight">
                <td class="field">Coach Name</td>
                <td>{{ $coach->first_name.' '.$coach->last_name }}</td>
            </tr>
            <tr class="divider">
                <td colspan="2"></td>
            </tr>
            <tr>
                <td class="field">Student Name</td>
                <td>{{ $user->first_name.' '.$user->last_name }}</td>
            </tr>
            <tr>
                <td class="field">Contact Number</td>
                <td><i class="fa fa-mobile fa-lg m-r-5"></i> {{ $user->phone_number }}</td>
            </tr>
            <tr>
                <td class="field">Email Address</td>
                <td><i class="fa fa-address-card fa-lg m-r-5"></i> {{ $user->email }}</td>
            </tr>
            <tr>
                <td class="field">Category</td>
                <td>{{ $appointment->category_id }}</td>
            </tr>
            <tr class="divider">
                <td colspan="2"></td>
            </tr>
            <tr class="highlight">
                <td class="field">Lesson Mode</td>
                <td>{{ $appointment->mode }}</td>
            </tr>
            <tr>
                <td class="field">Lesson Location</td>
                <td>{{ $appointment->location?$appointment->location:'-' }}</td>
            </tr>
            <tr class="divider">
                <td colspan="2"></td>
            </tr>
            <tr>
                <td class="field">Lesson Start</td>
                <td>{{ $appointment->start->format('l, d M, Y H:i A') }}</td>
            </tr>
            <tr>
                <td class="field">Lesson End</td>
                <td>{{ $appointment->end->format('l, d M, Y H:i A') }}</td>
            </tr>
            <tr>
                <td class="field">Lesson Duration</td>
                <td>{{ $appointment->end->diffInMinutes($appointment->start) }} Minutes</td>
            </tr>
            {{-- <tr class="divider">
                <td colspan="2"></td>
            </tr>
            @if($appointment->status=='scheduled')
                <tr>
                    <td class="field">Add Note</td>
                    <td>
                        @csrf
                        <input type="hidden" id="appointment_id" value="{{$appointment->id}}">
                        <textarea name="" id="add_note_txt" class="form-control" rows="3" required="required"></textarea>
                        <div class="add-note-wrapper text-right m-t-10">
                            <button type="button" class="btn btn-primary" id="add_note_btn">Add Note</button>
                        </div>
                    </td>
                </tr>
            @endif --}}
        </tbody>
    </table>
</div>

<!-- begin Transaction panel -->
<div class="panel panel-inverse">
    <!-- begin panel-heading -->
    <div class="panel-heading">
        <h4 class="panel-title">Transactions</h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body p-0">
        <!-- begin table -->
        <div class="table-responsive p-10">
            <table id="appointment-transaction-table" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th class="text-nowrap">Transaction Id</th>
                        <th class="text-nowrap">Appointment Id</th>
                        <th class="text-nowrap">Amount</th>
                        <th class="text-nowrap">Platform Fee</th>
                        <th class="text-nowrap">Type</th>
                        <th class="text-nowrap">Date</th>
                        <th class="text-nowrap">Status</th>
                        <th class="text-nowrap">Message</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <!-- end table -->
    </div>
    <!-- end panel-body -->
</div>
<!-- end Transaction panel -->

<!--Timeline -->
{{-- <div class="timeline-wrapper">
    <h4 class="m-t-20 m-b-20">Activities</h4>
    <ul class="timeline">
        @foreach ($appointment->activities()->limit(10)->get() as $activity)
            <li>
                <!-- begin timeline-icon -->
                <div class="timeline-icon">
                    <a href="javascript:;">&nbsp;</a>
                </div>
                <!-- end timeline-icon -->
                <!-- begin timeline-body -->
                <div class="timeline-body">
                    <div class="timeline-header">
                        <span class="username">{{ucwords( str_replace("_", " ", $activity->activity_type) )}}</span>
                        <span class="pull-right text-muted"><i class="fa fa-clock"></i> {{$activity->created_at->diffForHumans()}}</span>
                    </div>
                    <div class="timeline-content">
                        <p>
                            {{$activity->activity}}
                        </p>
                    </div>
                </div>
                <!-- end timeline-body -->
            </li>
        @endforeach
    </ul>
</div> --}}