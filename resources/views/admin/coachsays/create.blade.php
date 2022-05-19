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
                    <form action="{{  url('admin/coach-says/') }}" method="POST" id="caoachSayform">
                        @csrf
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">Coach <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <select name="coach_id" id="coach_id" class="form-control m-b-5 @error('coach_id') is-invalid @enderror" required>
                                    @foreach ($coaches as $coach)
                                        <option value="{{$coach->id}}">{{$coach->first_name.' '.$coach->last_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-3">Comment<span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <textarea name="comment" id="comment" cols="30" rows="10" class="form-control @error('comment') is-invalid @enderror" placeholder="FAQ comment"  required>{{ old('comment') }}</textarea>
                                @error('comment')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-7 offset-md-3">
                                <button type="submit" class="btn btn-sm btn-primary m-r-5">Save</button>
                                <a href="{{ url('admin/coach-says') }}" class="btn btn-sm btn-default">Cancel</a>
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
        $("#caoachSayform").validate({
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