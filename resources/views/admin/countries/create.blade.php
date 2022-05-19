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
                    <form action="{{  url('admin/countries/') }}" method="POST" id="countriesform" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">Name <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" placeholder="Country name" value="{{ old('name') }}" required />
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">Code <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input id="code" name="code" type="text" class="form-control @error('code') is-invalid @enderror" placeholder="Country code" value="{{ old('code') }}" required />
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-7 offset-md-3">
                                <button type="submit" class="btn btn-sm btn-primary m-r-5">Save</button>
                                <a href="{{ url('admin/countries') }}" class="btn btn-sm btn-default">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="{{ asset('backend/assets/plugins/ckeditor/ckeditor.js') }}"></script>
<script>
    $(document).ready(function() {

        $("#countriesform").validate({
            errorClass: "is-invalid",
            errorElement: 'div',
            errorPlacement: function(error, element) {
                $(error).addClass('invalid-feedback');
                error.appendTo(element.parent());
            }
        });
    });
</script>

@endsection