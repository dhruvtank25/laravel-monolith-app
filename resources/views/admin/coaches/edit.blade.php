@extends('layouts.main')

@section('styles')
<!----------------<partial style >------------------->
<style>
    .month{
        margin-top: 10px;
    }
    .year{
        margin-top: 10px;
        margin-left: 10px;
    }
    .company_name{
        margin-top: 10px;
    }
</style>
<link rel="stylesheet" href="{{ asset('frontend/css/dropzone.css') }}">
<!----------------</partial style> ------------------->
@endsection

@section('content')
    <!-- Upload doc Modal -->
    <div class="modal fade cerificate_modal" id="certificate_modal" tabindex="-1" role="dialog" aria-hidden="true">
       <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="doc_title">Lade dein Zertifikat oder <span>eine offizielle Bestätigung hoch.</span></h5>
                </div>
                <div class="modal-body">
                    <p class="upload_info mt-3 mb-5" id="doc_type">Upload von PDF, JPEG und PNG möglich</p>
                    <form action="{{ url('upload-documents') }}" method="POST" id="dropzone" class="dropzone">
                        @csrf
                        <input type="hidden" name="type" value="avatar">
                        <div class="row">
                            <div class=" dropzone-previews">
                                <div class=" dz-message"></div>
                            </div>
                            
                        </div>
                        <div class="fallback">
                            <input name="file" type="file" />
                        </div>
                    </form>
                    <button type="button" id="cancel_upload_btn" class="btn stop_modal_btn mt-5">Abbrechen</button>
                    <button type="button" id="save_file_btn" class="btn choose_modal_btn mt-5">Speichern</button>
                </div>
                  <!-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                  </div> -->
            </div>
       </div>
    </div>
    <!-- End Upload Doc -->

    <!-- begin row -->
    <div class="row">
        <!-- begin col-6 -->
        <div class="col-lg-12">
            <!-- begin panel -->
            <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
                <!-- begin panel-body -->
                <div class="panel-body">
                    <!-- <div class="alert alert-danger">
                        Some error here
                    </div> -->
                    @include('layouts.section.notifications')
                    <form action="{{  url('admin/users/'.$user->id) }}" method="POST" id="userform" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">Avatar</label>
                            <div class="col-md-9">
                                <div class="setpreviewimg">
                                    @if($user->avatar)
                                        <img src="{{ FileUploadHelper::getDocPath($user->avatar, 'avatar') }}" alt="" class="previewimg m-b-10" id="userimg" width="180">
                                    @else
                                        <img src="{{ FileUploadHelper::getDocPath('default.jpg', 'avatar') }}" alt="" class="previewimg m-b-10" id="userimg" width="180" height="180">
                                    @endif
                                </div>
                                <input type="file" name="avatar" class="form-control m-b-5 fileuploaderpreview">
                            </div>
                        </div>
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">Banner</label>
                            <div class="col-md-9">
                                <div class="setpreviewimg" data-width='100%' data-height='350'>
                                    @if($user->banner)
                                        <img src="{{ FileUploadHelper::getDocPath($user->banner, 'banner') }}" alt="" class="previewimg m-b-10" width="100%" height="350">
                                    @else
                                        <img src="{{ FileUploadHelper::getDocPath($user->banner, 'banner') }}" alt="" class="previewimg m-b-10" width="100%" height="350">
                                    @endif
                                </div>
                                <input type="file" name="banner" accept="image/*" class="form-control m-b-5 fileuploaderpreview">
                                <span class="f-w-600 text-black-darker">(1920x350 Image)</span>
                            </div>
                        </div>
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">Name <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <div class="row row-space-10">
                                <div class="col-md-6">
                                    <input id="first_name" name="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" placeholder="First name" value="{{ old('first_name')?old('first_name'):$user->first_name }}" required />
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <input id="last_name" name="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" placeholder="Last name" value="{{ old('last_name')?old('last_name'):$user->last_name }}" required />
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            </div>
                        </div>
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">Phone Number <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="text" name="phone_number" class="form-control m-b-5 @error('phone_number') is-invalid @enderror" placeholder="Enter Phone Number" value="{{ old('phone_number')?old('phone_number'):$user->phone_number }}" required />
                                @error('phone_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">Email address <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="email" name="email" class="form-control m-b-5 @error('email') is-invalid @enderror" placeholder="Enter email" value="{{ old('email')?old('email'):$user->email }}" required />
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="f-s-12 text-grey-darker">We'll never share your email with anyone else.</small>
                            </div>
                        </div>
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">Email Confirmation <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="email" name="email" class="form-control m-b-5 @error('email') is-invalid @enderror" placeholder="Enter email" value="{{ old('email')?old('email'):$user->email }}" required />
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="f-s-12 text-grey-darker">We'll never share your email with anyone else.</small>
                            </div>
                        </div>
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">Password</label>
                            <div class="col-md-9">
                                <input type="password" name="password" class="form-control m-b-5 @error('password') is-invalid @enderror" placeholder="Enter new password" value="" />
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="f-s-12 text-grey-darker">Leave empty to keep the same.</small>
                            </div>
                        </div>
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">Birth Date <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="text" id="birth_date" name="birth_date" class="form-control m-b-5 @error('birth_date') is-invalid @enderror" placeholder="Birth Date" value="{{ old('birth_date')?old('birth_date'):$user->birth_date->format('Y-m-d') }}" required />
                                @error('birth_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">Nationality <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <select name="nationality" id="nationality" class="form-control m-b-5 @error('nationality') is-invalid @enderror" required>
                                    <option value="">---Wählen Nationalität---</option>
                                    @foreach ($countries as $country)
                                        <option value="{{$country->code}}" {{old('nationality') && old('nationality')==$country->code?'selected':($user->nationality==$country->code?'selected':'')}}>{{$country->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- Address start -->
                        <legend>Address</legend>
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">location</label>
                            <div class="col-md-9">
                                <input id="autocomplete_loc" type="text" class="form-control " placeholder="type to search location" />
                            </div>
                        </div>
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">Street <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input id="street" name="street" type="text" class="form-control @error('street') is-invalid @enderror" placeholder="Street" value="{{ old('street')?old('street'):$user->street }}" required />
                                @error('street')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">Post Code <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input id="post_code" name="post_code" type="text" class="form-control @error('post_code') is-invalid @enderror" placeholder="Post Code" value="{{ old('post_code')?old('post_code'):$user->post_code }}" required />
                                @error('post_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">Place <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input id="place" name="place" type="text" class="form-control @error('place') is-invalid @enderror" placeholder="Place" value="{{ old('place')?old('place'):$user->place }}" required />
                                @error('place')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude')?old('latitude'):$user->latitude }}" required>
                            <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude')?old('longitude'):$user->longitude }}" required>
                            <input type="hidden" id="country" name="country" value="{{ old('country')?old('country'):$user->country }}" required>
                            <input type="hidden" id="country_code" name="country_code" value="{{ old('country_code')?old('country_code'):$user->country_code }}" required>
                        </div>
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">Different Offline Consultation Place?</label>
                            <div class="col-md-9">
                                <div class="switcher">
                                  <input type="checkbox" name="different_work" id="different_work" value="1" {{ old('different_work')?'checked':($user->different_work?'checked':'') }}>
                                  <label for="different_work"></label>
                                </div>
                                @error('different_work')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- Work Address -->
                        <div id="otherwork_div" style="{{ old('different_work')?'':($user->different_work?'':'display:none') }}">
                            <div class="form-group row m-b-15">
                                <label class="col-form-label col-md-3">location</label>
                                <div class="col-md-9">
                                    <input id="diff_autocomplete_loc" type="text" class="form-control " placeholder="type to search location" />
                                </div>
                            </div>
                            <div class="form-group row m-b-15">
                                <label class="col-form-label col-md-3">Street <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <input id="work_street" name="work_street" type="text" class="form-control @error('work_street') is-invalid @enderror" placeholder="Street" value="{{ old('work_street')?old('work_street'):$user->work_street }}" required />
                                    @error('work_street')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row m-b-15">
                                <label class="col-form-label col-md-3">Post Code <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <input id="work_post_code" name="work_post_code" type="text" class="form-control @error('work_post_code') is-invalid @enderror" placeholder="Post Code" value="{{ old('work_post_code')?old('work_post_code'):$user->work_post_code }}" required />
                                    @error('work_post_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row m-b-15">
                                <label class="col-form-label col-md-3">Place <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <input id="work_place" name="work_place" type="text" class="form-control @error('work_place') is-invalid @enderror" placeholder="Place" value="{{ old('work_place')?old('work_place'):$user->work_place }}" required />
                                    @error('work_place')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <input type="hidden" id="work_latitude" name="work_latitude" value="{{ old('work_latitude')?old('work_latitude'):$user->work_latitude }}" required>
                                <input type="hidden" id="work_longitude" name="work_longitude" value="{{ old('work_longitude')?old('work_longitude'):$user->work_longitude }}" required>
                                <input type="hidden" id="work_country" name="work_country" value="{{ old('work_country')?old('work_country'):$user->work_country }}" required>
                                <input type="hidden" id="work_country_code" name="work_country_code" value="{{ old('work_country_code')?old('work_country_code'):$user->work_country_code }}" required>
                            </div>
                        </div>
                        <!-- Address end -->
                        {{-- 
                        <legend>Professional</legend>
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">Company <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <select id = "companies" name="companies" class="form-control @error('company') is-invalid @enderror" required>
                                    @foreach ($companies as $company)
                                        <option value='{{$company->id}}'>{{$company->name}}</option>
                                    @endforeach
                                    <option value="other">Other</option>
                                    @error('company')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </select>
                            </div>
                        </div>
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">Designation <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input id="designation" name="designation" type="text" class="form-control @error('designation') is-invalid @enderror" placeholder="Designation" value="{{ old('designation')?old('designation'):$user->designation }}" required />
                                @error('designation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">Community <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input id="community" name="community" type="text" class="form-control @error('community') is-invalid @enderror" placeholder="Community" value="{{ old('community')?old('community'):$user->community }}" required />
                                @error('community')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">UST-ID (TAX ID) <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input id="ust_id" name="ust_id" type="text" class="form-control @error('ust_id') is-invalid @enderror" placeholder="UST-ID" value="{{ old('ust_id')?old('ust_id'):$user->ust_id }}" required />
                                @error('ust_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">Offline Coaching?</label>
                            <div class="col-md-9">
                                <div class="switcher">
                                  <input type="checkbox" name="offline_coaching" id="offline_coaching" value="1" {{ old('offline_coaching')?'checked':$user->coaching_method=='offline' || $user->coaching_method=='both'?'checked':'' }}>
                                  <label for="offline_coaching"></label>
                                </div>
                                @error('offline_coaching')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">Online Coaching?</label>
                            <div class="col-md-9">
                                <div class="switcher">
                                  <input type="checkbox" name="online_coaching" id="online_coaching" value="1" {{ old('online_coaching')?'checked':$user->coaching_method=='online' || $user->coaching_method=='both'?'checked':'' }}>
                                  <label for="online_coaching"></label>
                                </div>
                                @error('online_coaching')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">Price Per Hour <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <input id="price_per_hour" name="price_per_hour" type="text" class="form-control @error('price_per_hour') is-invalid @enderror" placeholder="Price per hour" value="{{ old('price_per_hour')?old('price_per_hour'):$user->price_per_hour }}" required />
                                    <span class="input-group-append"><span class="input-group-text  bg-primary">€</span></span>
                                </div>
                                @error('price_per_hour')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">Categories <span class="text-danger">*</span></label>
                            <div class="col-md-9 form-check-group">
                                @foreach ($categories as $category)
                                    <div class="checkbox checkbox-css">
                                        <input type="checkbox" class="category_chk" id="cat_chk_{{$category->id}}" name="categories[]" value="{{$category->id}}" {{in_array($category->id, $user_cats)?'checked':''}}>
                                        <label for="cat_chk_{{$category->id}}">{{$category->title}}</label>
                                    </div>
                                @endforeach
                                @error('categories')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">Languages (comma separated) <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input id="language" name="language" type="text" class="form-control @error('language') is-invalid @enderror" placeholder="languages" value="{{ old('language')?old('language'):$user->language }}" required />
                                @error('language')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">Priorities/Focus (comma separated) <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input id="priorities" name="priorities" type="text" class="form-control @error('priorities') is-invalid @enderror" placeholder="Priorities" value="{{ old('priorities')?old('priorities'):$user->priorities }}" required />
                                @error('priorities')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">Description <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" cols="30" rows="10" required>{{ old('description')?old('description'):$user->description }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                         --}}
                        <legend>KYC</legend>
                        @if($user->status!='incomplete')
                            <div class="form-group row m-b-15">
                                <label class="col-form-label col-md-3">Status <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <select name="status" id="status" class="form-control m-b-5 @error('status') is-invalid @enderror" required>
                                            <option value="active" {{old('status') && old('status')=='active'?'selected':($user->status=='active'?'selected':'')}}>Active</option>
                                            <option value="incomplete" {{old('status') && old('status')=='incomplete'?'selected':($user->status=='incomplete'?'selected':'')}}>Incomplete</option>
                                            <option value="approval" {{old('status') && old('status')=='approval'?'selected':($user->status=='approval'?'selected':'')}}>Approval</option>
                                            <option value="kyc pending" {{old('status') && old('status')=='kyc pending'?'selected':($user->status=='kyc pending'?'selected':'')}}>Kyc Pending</option>
                                            <option value="ubo pending" {{old('status') && old('status')=='ubo pending'?'selected':($user->status=='ubo pending'?'selected':'')}}>Ubo Pending</option>
                                    </select>
                                </div>
                            </div>
                        @endif
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">KYC Status <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <select name="kyc_status" id="kyc_status" class="form-control m-b-5 @error('kyc_status') is-invalid @enderror" required>
                                        <option value="pending" {{old('kyc_status') && old('kyc_status')=='pending'?'selected':($user->kyc_status=='pending'?'selected':'')}}>Pending</option>
                                        <option value="asked" {{old('kyc_status') && old('kyc_status')=='asked'?'selected':($user->kyc_status=='asked'?'selected':'disabled')}} >Asked</option>
                                        <option value="failed" {{old('kyc_status') && old('kyc_status')=='failed'?'selected':($user->kyc_status=='failed'?'selected':'disabled')}} >Failed</option>
                                        <option value="validated" {{old('kyc_status') && old('kyc_status')=='validated'?'selected':($user->kyc_status=='validated'?'selected':'disabled')}} >Validated</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">Id Card Doc <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="hidden" class="do-not-ignore" name="id_doc" data-url="{{FileUploadHelper::getMultipleDocPath($user->id_doc, 'id_doc')}}" value="{{$user->id_doc}}">
                                <button type="button" class="btn btn-success mt-3 modal_file_upload" data-type="id_doc">Upload Id proof</button>
                            </div>
                        </div>
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">Registration Doc <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="hidden" class="do-not-ignore" name="ustid_doc" data-url="{{FileUploadHelper::getMultipleDocPath($user->ustid_doc, 'ustid_doc')}}" value="{{$user->ustid_doc}}">
                                <button type="button" class="btn btn-success mt-3 modal_file_upload" data-type="ustid_doc">Upload Registration proof</button>
                            </div>
                        </div>
                        @if($user->person_type=='business')
                            <div class="form-group row m-b-15">
                                <label class="col-form-label col-md-3">UBO Status <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <select name="ubo_status" id="ubo_status" class="form-control m-b-5 @error('ubo_status') is-invalid @enderror" required>
                                            <option value="pending" {{old('ubo_status') && old('ubo_status')=='pending'?'selected':($user->ubo_status=='pending'?'selected':'')}}>Pending</option>
                                            <option value="asked" {{old('ubo_status') && old('ubo_status')=='asked'?'selected':($user->ubo_status=='asked'?'selected':'disabled')}}>Asked</option>
                                            <option value="incomplete" {{old('ubo_status') && old('ubo_status')=='incomplete'?'selected':($user->ubo_status=='incomplete'?'selected':'disabled')}}>Incomplete</option>
                                            <option value="refused" {{old('ubo_status') && old('ubo_status')=='refused'?'selected':($user->ubo_status=='refused'?'selected':'disabled')}}>Refused</option>
                                            <option value="validated" {{old('ubo_status') && old('ubo_status')=='validated'?'selected':($user->ubo_status=='validated'?'selected':'disabled')}}>Validated</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row m-b-15">
                                <label class="col-form-label col-md-3">Commerical Doc <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <input type="hidden" class="do-not-ignore" name="commercial_doc" data-url="{{FileUploadHelper::getMultipleDocPath($user->commercial_doc, 'commercial_doc')}}" value="{{$user->commercial_doc}}">
                                    <button type="button" class="btn btn-success mt-3 modal_file_upload" data-type="commercial_doc">Upload Commercial proof</button>
                                </div>
                            </div>
                        @endif
                        <div class="form-group row">
                            <div class="col-md-7 offset-md-3">
                                <button type="submit" class="btn btn-sm btn-primary m-r-5">Save</button>
                                <a href="{{ url('admin/coaches') }}" class="btn btn-sm btn-default">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="{{ asset('frontend/js/dropzone.js') }}"></script>
<script>
    Dropzone.autoDiscover = false;
</script>
<script src="{{ asset('frontend/js/custom/update_coach_profile.js') }}"></script>
<script>
    $(document).ready(function() {

        // Jquery Ui datepicker
        $("#birth_date").datepicker({
            dateFormat: "yy-mm-dd",
            yearRange: "-100:-18",
            maxDate: "-18Y",
            changeYear:true,
            changeMonth:true,
            showMonthAfterYear: true,
        });

        $("#userform").validate({
            ignore: ':hidden:not(.category_chk)',
            //ignore: [],
            errorClass: "is-invalid",
            errorElement: 'div',
            errorPlacement: function(error, element) {
                console.log(element);
                $(error).addClass('invalid-feedback');
                if(element.attr('type')=='checkbox'){
                    if(element.parent().parent().hasClass('form-check-group')){
                        error.appendTo(element.parent().parent());
                    }else{
                        error.appendTo(element.parent());
                    }
                }else{
                    error.appendTo(element.parent());
                }
            },
            highlight: function(element) {
                element = $(element);
                if(element.attr('type')=='checkbox'){
                    if(element.parent().parent().hasClass('form-check-group')){
                        var group_div = element.parent().parent();
                        group_div.find('input[type=checkbox]').addClass('is-invalid');
                    }else{
                        element.addClass('is-invalid');
                    }
                }else{
                    element.addClass('is-invalid');
                }
            },
            unhighlight: function(element) {
                element = $(element);
                if(element.attr('type')=='checkbox'){
                    if(element.parent().parent().hasClass('form-check-group')){
                        var group_div = element.parent().parent();
                        group_div.find('input[type=checkbox]').removeClass('is-invalid');
                    }else{
                        element.removeClass('is-invalid');
                    }
                }else{
                    element.removeClass('is-invalid');
                }
            },
            rules: {
                "categories[]":{
                    required:true,
                    minlength:1
                },
            },
            messages: {
                "categories[]": {
                    required: 'Select atleast one option'
                }
            }
        });

        $("#different_work").change(function(event) {
            if($(this).is(':checked')){
                $("#otherwork_div").show();
            }else{
                $("#otherwork_div").hide();
            }
        });

        $("#online_coaching, #offline_coaching").change(function(event) {
            if(!$(this).is(':checked')) {
                if($(this).attr('id')=='online_coaching')
                    $("#offline_coaching").prop('checked', true);
                else
                    $("#online_coaching").prop('checked', true);
            }
        });


        $("#companies").change(function(event){
            if($(this).val() == "other"){
                html =  "<div id='joining_from' class='col-md-8 row'> \
                            <label class='col-form-label col-md-3'>Company Name:</label> \
                            <input id='company_name' name='company_name' type='text' class='form-control col-md-2 company_name @error('company_name') is-invalid @enderror' placeholder='Company Name' value='{{ old('company_name') }}' required /> \
                            <label class='col-form-label col-md-2'>Join From:</label> \
                                <select id ='doj_month' name='doj_month' class='form-control col-md-2 month'> \
                                    <option value=''>Month</option> \
                                    <option value='01'>Jan</option> \
                                    <option value='02'>Feb</option> \
                                    <option value='03'>Mar</option> \
                                    <option value='04'>Apr</option> \
                                    <option value='05'>May</option> \
                                    <option value='06'>Jun</option> \
                                    <option value='07'>Jul</option> \
                                    <option value='08'>Aug</option> \
                                    <option value='09'>Sep</option> \
                                    <option value='10'>Oct</option> \
                                    <option value='11'>Nov</option> \
                                    <option value='12'>Dec</option> \
                                </select> \
                                <select id ='doj_year' name='doj_year' class='form-control col-md-2 year'> \
                                    <option value=''>Year</option> \
                                    <option value='2022'>2022</option> \
                                    <option value='2021'>2021</option> \
                                    <option value='2020'>2020</option> \
                                    <option value='2019'>2019</option> \
                                    <option value='2018'>2018</option> \
                                    <option value='2017'>2017</option> \
                                    <option value='2016'>2016</option> \
                                    <option value='2015'>2015</option> \
                                    <option value='2014'>2014</option> \
                                    <option value='2013'>2013</option> \
                                    <option value='2012'>2012</option> \
                                    <option value='2011'>2011</option> \
                                    <option value='2010'>2010</option> \
                                    <option value='2009'>2009</option> \
                                    <option value='2008'>2008</option> \
                                    <option value='2007'>2007</option> \
                                    <option value='2006'>2006</option> \
                                    <option value='2005'>2005</option> \
                                    <option value='2004'>2004</option> \
                                    <option value='2003'>2003</option> \
                                    <option value='2002'>2002</option> \
                                    <option value='2001'>2001</option> \
                                    <option value='2000'>2000</option> \
                                    <option value='1999'>1999</option> \
                                    <option value='1998'>1998</option> \
                                    <option value='1997'>1997</option> \
                                    <option value='1996'>1996</option> \
                                    <option value='1995'>1995</option> \
                                    <option value='1994'>1994</option> \
                                    <option value='1993'>1993</option> \
                                    <option value='1992'>1992</option> \
                                    <option value='1991'>1991</option> \
                                    <option value='1990'>1990</option> \
                                    <option value='1989'>1989</option> \
                                    <option value='1988'>1988</option> \
                                    <option value='1987'>1987</option> \
                                    <option value='1986'>1986</option> \
                                    <option value='1985'>1985</option> \
                                    <option value='1984'>1984</option> \
                                    <option value='1983'>1983</option> \
                                    <option value='1982'>1982</option> \
                                    <option value='1981'>1981</option> \
                                </select> \
                        </div>";
                $("#companies").after(html);
            }else{
                $("#joining_from").remove();
            }
        })
        
    });
</script>
<script>
    var home_autocomplete, work_autocomplete;
    function initAutoComplete() {
        var input      = document.getElementById('autocomplete_loc');
        var diff_input = document.getElementById('diff_autocomplete_loc');
        var options    = {
            //types: ['geocode'], //this should work !
            //region:'EU',
            //componentRestrictions: {country: "AU"}
        };
        home_autocomplete = new google.maps.places.Autocomplete(input, options);
        home_autocomplete.addListener('place_changed', fillInAddress);
        preventMapFormSubmit(input);

        // Different work address
        work_autocomplete = new google.maps.places.Autocomplete(diff_input, options);
        work_autocomplete.addListener('place_changed', fillInDiffAddress);
        preventMapFormSubmit(diff_input);
    }

    function preventMapFormSubmit (input) {
        google.maps.event.addDomListener(input, 'keydown', function(event) { 
           if (event.keyCode === 13) { 
               event.preventDefault(); 
           }
        });
    }

    function fillInDiffAddress () {
        fillAddress('work');
    }

    function fillInAddress() {
        fillAddress('home');
    }

    function fillAddress (type) {
        // Get the place details from the autocomplete object.
        var place = type=='work'?work_autocomplete.getPlace():home_autocomplete.getPlace();
        console.log(place);
        var address_obj = {lat:"", lng:"", street_no:"", route:"", city:"", city1:"", state:"", post_code:"", country:"",country_code:""};

        // Address co-ordinates
        address_obj.lat = place.geometry.location.lat();
        address_obj.lng = place.geometry.location.lng();

        // Get each component of the address from the place details
        // and fill the corresponding field on the form.
        for (var i = 0; i < place.address_components.length; i++) {
            var addressType = place.address_components[i].types[0];
            switch (addressType) {
                case "administrative_area_level_1":
                    address_obj.state = place.address_components[i]["short_name"];
                    break;
                case "administrative_area_level_2":
                    address_obj.city1 = place.address_components[i]["short_name"];
                    break;
                case "postal_code":
                    address_obj.post_code = place.address_components[i]["short_name"];
                    break;
                case "locality":
                    address_obj.place = place.address_components[i]["long_name"];
                    break;
                case "street_number":
                    address_obj.street_no = place.address_components[i]["short_name"];
                    break;
                case "route":
                    address_obj.route = place.address_components[i]["long_name"];
                    break;
                case "country":
                    address_obj.country = place.address_components[i]["long_name"];
                    address_obj.country_code = place.address_components[i]["short_name"];
                    break;
                default:
                    console.log(addressType);
                    break;
            }
        }

        var place = '';
        if(address_obj.city!='')
          place  = address_obj.city;
        else if(address_obj.city1!='')
          place  = address_obj.city1;
        else if(address_obj.place!='')
          place  = address_obj.place;

        if(type=='work') {
            document.getElementById("work_latitude").value     = address_obj.lat;
            document.getElementById("work_longitude").value    = address_obj.lng;
            document.getElementById("work_post_code").value    = address_obj.post_code;
            document.getElementById("work_place").value        = place;
            document.getElementById("work_country").value      = address_obj.country;
            document.getElementById("work_country_code").value = address_obj.country_code;
            document.getElementById("work_street").value       = address_obj.street_no+' '+address_obj.route;
        } else {
            document.getElementById("latitude").value          = address_obj.lat;
            document.getElementById("longitude").value         = address_obj.lng;
            document.getElementById("post_code").value         = address_obj.post_code;
            document.getElementById("place").value             = place;
            document.getElementById("country").value           = address_obj.country;
            document.getElementById("country_code").value      = address_obj.country_code;
            document.getElementById("street").value            = address_obj.street_no+' '+address_obj.route;
        }
    }
</script>
<script async defer
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBR5FirOPNOnP9WBqT0ZMBbzyQ8reeVLhI&libraries=places&callback=initAutoComplete">
</script>
@endsection