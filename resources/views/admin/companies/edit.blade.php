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
                    <form action="{{  url('admin/companies/'.$company->id) }}" method="POST" id="companyform" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">Image</label>
                            <div class="col-md-9">
                                <div class="setpreviewimg">
                                    @if($company->image)
                                        {!! $company->image !!}
                                    @else
                                        <img src="{{ FileUploadHelper::getDocPath('default.jpg', 'avatar') }}" alt="" class="previewimg m-b-10" id="companyimg" width="180" height="180">
                                    @endif
                                </div>
                                <input type="file" name="image" accept="image" class="form-control m-b-5 fileuploaderpreview">
                            </div>
                        </div>
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">Company Name <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" placeholder="First name" value="{{ old('name')?old('name'):$company->name }}" required />
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-7 offset-md-3">
                                <button type="submit" class="btn btn-sm btn-primary m-r-5">Save</button>
                                <a href="{{ url('admin/companies') }}" class="btn btn-sm btn-default">Cancel</a>
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

        $("#companyform").validate({
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