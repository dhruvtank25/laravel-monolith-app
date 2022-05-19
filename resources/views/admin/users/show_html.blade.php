<!-- begin table -->
<div class="table-responsive">
    <table class="table table-profile">
        <thead>
            <tr>
                <th></th>
                <th>
                    <img src="{{ FileUploadHelper::getDocPath($user->avatar, 'avatar') }}" alt="">
                </th>
            </tr>
            <tr>
                <th></th>
                <th>
                    <h4>{{$user->first_name}} {{$user->last_name}} <small>{{$user_role}}</small></h4>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="field">Created On</td>
                <td>{{$user->created_at->format('Y-m-d')}}</td>
            </tr>
            <tr>
                <td class="field">Status</td>
                <td>
                    @if($user->status=='active')
                        <label class="label label-theme f-s-9">{{$user->status}}</label>
                    @elseif ($user->status=='inactive')
                        <label class="label label-danger f-s-9">{{$user->status}}</label>
                    @else
                        <label class="label label-info f-s-9">{{$user->status}}</label>
                    @endif
                </td>
            </tr>
            <tr class="highlight">
                <td class="field">Email</td>
                <td><i class="fa fa-address-card fa-lg m-r-5"></i>{{$user->email}}</td>
            </tr>
            <tr class="divider">
                <td colspan="2"></td>
            </tr>
            <tr>
                <td class="field">Mobile</td>
                <td><i class="fa fa-mobile fa-lg m-r-5"></i> {{$user->phone_number}}</td>
            </tr>
            <tr class="divider">
                <td colspan="2"></td>
            </tr>
            <tr>
                <td class="field">About Me</td>
                <td>{{$user->description}}</td>
            </tr>
            @if($user->video)
                <tr>
                    <td class="field">Uploaded Video</td>
                    <td>
                    <video width="450px" height="300px" controls class="coach_presetation_video">
                        <source src="{{ FileUploadHelper::getDocPath($user->video, 'video') }}">
                        Your browser does not support the video tag.
                    </video>
                    </td>
                </tr>
            @endif
            <tr>
                <td class="field">Birthdate</td>
                <td>{{$user->birth_date->format('m.d.Y')}}</td>
            </tr>
            <tr>
                <td class="field">Nationality</td>
                <td>{{$user->nationality}}</td>
            </tr>
            <tr class="divider">
                <td colspan="2"></td>
            </tr>
            @if($user->coach_company)
            <tr class="highlight">
                <td class="field">Company</td>
                <td>{{ strtoupper($user->coach_company) }}</td>
            </tr>
            @endif
            <tr>
                <td class="field">Address</td>
                <td>{!!$user->street.' <br>'.$user->place.' '.$user->postcode.' <br> '.$user->country!!}</td>
            </tr>
            @if($user->different_work)
                <tr>
                    <td class="field">Work Address</td>
                    <td>{!!$user->work_street.' <br>'.$user->work_place.' '.$user->work_postcode.' <br> '.$user->work_country!!}</td>
                </tr>
            @endif
            @if($user_role=='coach')
                {{-- <tr>
                    <td class="field">Impressum</td>
                    <td>{!! $user->impressum !!}</td>
                </tr> --}}
                <tr>
                    <td class="field">Coaching Method</td>
                    <td>{{ucfirst($user->coaching_method=='both'?'Online & Offline':$user->coaching_method)}}</td>
                </tr>
                <tr>
                    <td class="field">Price per hour</td>
                    <td>{{$user->price_per_hour}} € / hr</td>
                </tr>
                <tr>
                    <td class="field">Business Type</td>
                    <td>{{ucwords($user->person_type)}}</td>
                </tr>
                <tr>
                    <td class="field">Is small Business ?</td>
                    <td>{{$user->small_business?'Yes':'No'}}</td>
                </tr>
                @if($user->tax_number)
                    <tr>
                        <td class="field">Tax Number</td>
                        <td>{{$user->tax_number}}</td>
                    </tr>
                @endif
                @if($user->ust_id)
                    <tr>
                        <td class="field">UST-Id</td>
                        <td>{{$user->ust_id}}</td>
                    </tr>
                @endif
                @if($user->person_type=='business')
                    <tr>
                        <td class="field">Company Type</td>
                        <td>
                            @php
                                switch ($user->company_type) {
                                    case 'llc':
                                        echo 'Gesellschaft mit beschränkter Haftung (GmbH)';
                                        break;

                                    case 'enterpreneur':
                                        echo 'Unternehmergesellschaft UG (haftungsbeschränkt)';
                                        break;

                                    case 'joint stock':
                                        echo 'Aktiengesellschaft (AG)';
                                        break;

                                    case 'open trading':
                                        echo 'Offene Handelsgesellschaft (OHG)';
                                        break;

                                    case 'limited partnership':
                                        echo 'Kommanditgesellschaft (KG)';
                                        break;

                                    case 'gmbh':
                                    default:
                                        echo 'GmbH & Co. KG';
                                        break;
                                }
                            @endphp
                        </td>
                    </tr>
                    <tr>
                        <td class="field">Company Registration Number</td>
                        <td>{{$user->company_number}}</td>
                    </tr>
                    <tr>
                        <td class="field">Shareholders</td>
                        <td>
                            <ol>
                                @foreach ($user->shareholders as $sh)
                                    <li>
                                        {{$sh->first_name.' '.$sh->last_name}}<br>
                                        {{$sh->street}}<br>
                                        {{$sh->place.' '.$sh->post_code.','.$sh->country}}<br>
                                        {{$sh->birth_date->format('d.m.Y')}}
                                        ({{$sh->birth_place.','.$sh->birth_land}})
                                    </li>
                                @endforeach
                            </ol>
                        </td>
                    </tr>
                    <!-- KYC documents only visible to MangoPay -->
                    {{-- 
                    <tr>
                        <td class="field">Commerical Documents</td>
                        <td>
                            @php 
                                $commercial_strings = explode(",", $user->commercial_doc);
                                $commerical_urls = FileUploadHelper::getMultipleDocPath($user->commercial_doc, 'commercial_doc');
                                $comerical_docs = explode(",", $commerical_urls);
                            @endphp
                            <ol>
                                @foreach($comerical_docs as $commerical_doc)
                                    <li><a target="_blank" href="{{$commerical_doc}}">{{$commercial_strings[$loop->index]}}</a></li>
                                @endforeach
                            </ol>
                        </td>
                    </tr> 
                    --}}
                @endif
                <!-- KYC documents only visible to MangoPay -->
                <tr class="divider">
                    <td colspan="2"></td>
                </tr>
                <tr>
                    <td class="field">KYC STATUS</td>
                    <td>{{ucfirst($user->kyc_status)}}</td>
                </tr>
                @if($user->kyc_message && $user->kyc_status=='failed')
                    @php $kyc_detail_arr = json_decode($user->kyc_message); @endphp
                    <tr>
                        <td class="field">KYC DETAILS</td>
                        <td>
                            <ol>
                                @foreach($kyc_detail_arr as $kyc_detail)
                                    <li>
                                        Id: {{$kyc_detail->id}}<br>
                                        <b>Type: {{$kyc_detail->type}}<br>
                                        Status: {{$kyc_detail->status}}<br></b>
                                        Reason Type: {{$kyc_detail->reason_type}}<br>
                                        Reason Message: {{$kyc_detail->reason_message}}<br>
                                    </li>    
                                @endforeach
                            </ol>
                        </td>
                    </tr>
                @endif
                @if($user->person_type=='business')
                    <tr>
                        <td class="field">UBO STATUS</td>
                        <td>{{ucfirst($user->ubo_status)}}</td>
                    </tr>
                    @if($user->ubo_message && ($user->ubo_status=='refused' || $user->ubo_status=='incomplete'))
                        @php $ubo_detail = json_decode($user->ubo_message); @endphp
                        <tr>
                            <td class="field">UBO DETAILS</td>
                            <td>
                                Id: {{$ubo_detail->id}}<br>
                                Reason Type: {{$ubo_detail->reason_type}}<br>
                                Reason Message: {{$ubo_detail->reason_message}}<br>
                            </td>
                        </tr>
                    @endif
                @endif
                {{-- <tr>
                    <td class="field">Registration Documents</td>
                    <td>
                        @php 
                            $ustid_strings = explode(",", $user->ustid_doc);
                            $ustid_urls = FileUploadHelper::getMultipleDocPath($user->ustid_doc, 'ustid_doc');
                            $ustid_docs = explode(",", $ustid_urls);
                        @endphp
                        <ol>
                            @foreach($ustid_docs as $ustid_doc)
                                <li><a target="_blank" href="{{$ustid_doc}}">{{$ustid_strings[$loop->index]}}</a></li>
                            @endforeach
                        </ol>
                    </td>
                </tr>
                <tr>
                    <td class="field">Identity Documents</td>
                    <td>
                        @php 
                            $id_strings = explode(",", $user->id_doc);
                            $id_urls = FileUploadHelper::getMultipleDocPath($user->id_doc, 'id_doc');
                            $id_docs = explode(",", $id_urls);
                        @endphp
                        <ol>
                            @foreach($id_docs as $id_doc)
                                <li><a target="_blank" href="{{$id_doc}}">{{$id_strings[$loop->index]}}</a></li>
                            @endforeach
                        </ol>
                    </td>
                </tr> --}}
                <tr class="divider">
                    <td colspan="2"></td>
                </tr>
                <tr class='highlight'>
                    <td class="field">Bank Account Holder</td>
                    <td>{{$user->owner_name}}</td>
                </tr>
                <tr>
                    <td class="field">IBAN</td>
                    <td>{{$user->iban}}</td>
                </tr>
                <tr>
                    <td class="field">BIC</td>
                    <td>{{$user->bic}}</td>
                </tr>
                <tr class="divider">
                    <td colspan="2"></td>
                </tr>
                <tr>
                    <td class="field">Education/Companies</td>
                    <td>
                        @foreach ($user_companies as $coach_company)
                            @php $company_pivot = $coach_company->pivot; @endphp
                            <ol>
                                <li>
                                    Company:
                                    @if(strtolower($coach_company->name)=='other')
                                        {{$company_pivot->company_name}}
                                    @else
                                        {{$coach_company->name}}
                                    @endif
                                    <br>
                                    Designation:
                                    {{$company_pivot->designation}}
                                    <br>
                                    Joining:
                                    {{\Carbon\Carbon::createFromFormat('Y-m-d',$company_pivot->joining_date)->format('d.m.Y')}}
                                    <br>
                                    Document:
                                    <a target="_blank" href="{{FileUploadHelper::getDocPath($company_pivot->document, 'company_doc')}}">{{$company_pivot->document}}</a>
                                </li>
                            </ol>
                        @endforeach
                    </td>
                </tr>
                <tr>
                    <td class="field">Categories</td>
                    <td>
                        @foreach($user_categories as $category)
                            <span class="badge bg-success">{{$category->title}}</span>
                        @endforeach
                    </td>
                </tr>
                <tr>
                    <td class="field">Languages</td>
                    <td>{{$user->language}}</td>
                </tr>
                <tr>
                    <td class="field">Priorities</td>
                    <td>{{$user->priorities}}</td>
                </tr>
                <tr>
                    <td class="field">Community</td>
                    <td>{{$user->community}}</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
<!-- end table -->
<!-- begin Lesson panel -->
<div class="panel panel-inverse">
    <!-- begin panel-heading -->
    <div class="panel-heading">
        <h4 class="panel-title">Bookings</h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body p-0">
        <!-- begin table -->
        <div class="table-responsive p-10">
            <table id="user-lesson-table" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th class="text-nowrap">Id</th>
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
        </div>
        <!-- end table -->
    </div>
    <!-- end panel-body -->
</div>
<!-- end Lesson panel -->

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
            <table id="user-transaction-table" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th class="text-nowrap">Transaction Id</th>
                        <th class="text-nowrap">Appointment Id</th>
                        <th class="text-nowrap">Debit/Credit</th>
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
    <h4 class="m-t-20 m-b-20">My Activities</h4>
    <ul class="timeline">
        @foreach ($user->activities()->limit(10)->get() as $activity)
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