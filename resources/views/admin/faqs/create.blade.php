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
                    <form action="{{  url('admin/faqs/') }}" method="POST" id="faqform">
                        @csrf
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">For <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <select name="type" id="type" class="form-control m-b-5 @error('type') is-invalid @enderror" required>
                                    <option value="user">User</option>
                                    <option value="coach">Coach</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">Title<span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input id="title" name="title" type="text" class="form-control @error('title') is-invalid @enderror" placeholder="FAQ title" value="{{ old('title') }}" required />
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">Description<span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <textarea name="description" id="description" cols="30" rows="10" class="form-control @error('description') is-invalid @enderror" placeholder="FAQ description"  required>{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-7 offset-md-3">
                                <button type="submit" class="btn btn-sm btn-primary m-r-5">Save</button>
                                <a href="{{ url('admin/faqs') }}" class="btn btn-sm btn-default">Cancel</a>
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
        $("#faqform").validate({
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