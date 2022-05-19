@extends('layouts.main')

@section('styles')
@endsection

@section('content')
    <!-- begin row -->
    <div class="row">
        <!-- begin col-6 -->
        <div class="col-lg-12">
            <!-- begin panel -->
            <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
                <!-- begin panel-body -->
                <div class="panel-body">
                    @include('layouts.section.notifications')
                    <form action="{{  url('admin/users/') }}" method="POST" id="userform" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="role_id" value="{{$role_id}}">
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">Avatar</label>
                            <div class="col-md-9">
                                <div class="setpreviewimg">
                                    <img src="{{ FileUploadHelper::getDocPath('default.jpg', 'avatar') }}" alt="" class="previewimg m-b-10" id="userimg" width="180" height="180">
                                </div>
                                <input type="file" name="avatar" class="form-control m-b-5 fileuploaderpreview">
                            </div>
                        </div>
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">Name <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <div class="row row-space-10">
                                <div class="col-md-6">
                                    <input id="first_name" name="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" placeholder="First name" value="{{ old('first_name') }}" required />
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <input id="last_name" name="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" placeholder="Last name" value="{{ old('last_name') }}" required />
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
                                <input type="text" name="phone_number" class="form-control m-b-5 @error('phone_number') is-invalid @enderror" placeholder="Enter Phone Number" value="{{ old('phone_number') }}" required />
                                @error('phone_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">Email address <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="email" name="email" class="form-control m-b-5 @error('email') is-invalid @enderror" placeholder="Enter email" value="{{ old('email') }}" required />
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="f-s-12 text-grey-darker">We'll never share your email with anyone else.</small>
                            </div>
                        </div>
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">Confirm Email <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="email" name="email_confirmation" class="form-control m-b-5 @error('email_confirmation') is-invalid @enderror" placeholder="Confirm email" value="{{ old('email_confirmation') }}" required />
                                @error('email_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">Password <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="password" name="password" class="form-control m-b-5 @error('password') is-invalid @enderror" placeholder="Enter password" value="{{ old('password') }}" required />
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">Confirm Password <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="password" name="password_confirmation" class="form-control m-b-5 @error('password_confirmation') is-invalid @enderror" placeholder="Confirm password" value="{{ old('password_confirmation') }}" required />
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">Birth Date <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="text" id="birth_date" name="birth_date" class="form-control m-b-5 @error('birth_date') is-invalid @enderror" placeholder="Birth Date" value="{{ old('birth_date') }}" required />
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
                                        <option value="{{$country->code}}">{{$country->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- Address start -->
                        <legend>Address</legend>
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">location</label>
                            <div class="col-md-9">
                                <input id="autocomplete" type="text" class="form-control " placeholder="type to search location" />
                            </div>
                        </div>
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">Street <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input id="street" name="street" type="text" class="form-control @error('street') is-invalid @enderror" placeholder="Street" value="{{ old('street') }}" required />
                                @error('street')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">Post Code <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input id="post_code" name="post_code" type="text" class="form-control @error('post_code') is-invalid @enderror" placeholder="Post Code" value="{{ old('post_code') }}" required />
                                @error('post_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">Place <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input id="place" name="place" type="text" class="form-control @error('place') is-invalid @enderror" placeholder="Place" value="{{ old('place') }}" required />
                                @error('place')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude')}}" required>
                            <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude')}}" required>
                            <input type="hidden" id="country" name="country" value="{{ old('country')}}" required>
                            <input type="hidden" id="country_code" name="country_code" value="{{ old('country_code')}}" required>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-7 offset-md-3">
                                <button type="submit" class="btn btn-sm btn-primary m-r-5">Save</button>
                                <a href="{{ url('admin/users') }}" class="btn btn-sm btn-default">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
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
            errorClass: "is-invalid",
            errorElement: 'div',
            errorPlacement: function(error, element) {
                $(error).addClass('invalid-feedback');
                error.appendTo(element.parent());
            }
        });
    });

</script>

<script>
    var autocomplete;
    function initMap() {
        var input = document.getElementById('autocomplete');
        var options = {
            //types: ['geocode'], //this should work !
            //region:'EU',
            //componentRestrictions: {country: "AU"}
        };
        autocomplete = new google.maps.places.Autocomplete(input, options);
        autocomplete.addListener('place_changed', fillInAddress);
        preventMapFormSubmit(input);
    }

    function preventMapFormSubmit (input) {
        google.maps.event.addDomListener(input, 'keydown', function(event) { 
           if (event.keyCode === 13) { 
               event.preventDefault(); 
           }
        });
    }

    function fillInAddress () {
        // Get the place details from the autocomplete object.
        var place = autocomplete.getPlace();
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

        document.getElementById("latitude").value          = address_obj.lat;
        document.getElementById("longitude").value         = address_obj.lng;
        document.getElementById("post_code").value         = address_obj.post_code;
        document.getElementById("country").value           = address_obj.country;
        document.getElementById("country_code").value      = address_obj.country_code;
        document.getElementById("street").value            = address_obj.route+' '+address_obj.street_no;
        if(address_obj.city!='')
            document.getElementById("place").value  = address_obj.city;
        else if(address_obj.city1!='')
            document.getElementById("place").value  = address_obj.city1;
        else if(address_obj.place!='')
            document.getElementById("place").value  = address_obj.place;
    }
</script>
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBR5FirOPNOnP9WBqT0ZMBbzyQ8reeVLhI&libraries=places&callback=initMap">
    </script>
@endsection