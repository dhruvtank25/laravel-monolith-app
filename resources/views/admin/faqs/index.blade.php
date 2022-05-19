@extends('layouts.main')

@section('styles')
@endsection

@section('content')
    {{ csrf_field() }}
    <div class="row">
        <div class="col-md-6">
            <a class="btn btn-large btn-info width-100 m-5" href="{{url('admin/faqs/create')}}"><span class="fa fa-plus-circle"></span> Add New</a>
        </div>
    </div>
    <table id="faqs_table" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th class="text-nowrap">For</th>
                <th class="text-nowrap">Title</th>
                <th class="text-nowrap">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($faqs as $faq)
                <tr>
                    <td>{{ucfirst($faq->type)}}</td>
                    <td>{{$faq->title}}</td>
                    <td>
                        <a class="btn btn-sm btn-info m-r-2 viewUser" href="{{ url('admin/faqs/'.$faq->id) }}"><i class="fa fa-eye"></i> View</a>
                        <a class="btn btn-sm btn-primary m-r-2 editUser" href="{{ url('admin/faqs/'.$faq->id.'/edit') }}" class=""><i class="fa fa-edit"></i> Edit</a>
                        <a href="javascript:void(0)" class="btn btn-sm btn-danger m-r-2 delFAQ" data-id="{{$faq->id}}"><i class="fa fa-trash-alt"></i> Delete</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $("#faqs_table").dataTable();
        }); 

        $(document).on('click', '.delFAQ', function(event) {
            event.preventDefault();
            var $_this      = $(this);
            var company_id = $_this.data('id');
            $.ajax({
                url: baseUrl+'/faqs/'+company_id,
                type: 'DELETE',
                data: {_token: $('input[name="_token"]').val()},
            })
            .done(function(data) {
                console.log("success");
                console.log(data);
                if(data){
                    toastr.success('Faq Deleted');
                    $_this.closest('tr').remove();
                }else{
                    toastr.error('Something went wrong!');
                }
            })
            .fail(function(data) {
                console.log("error");
                console.log(data);
                toastr.error('Request failed!');
            })
            .always(function() {
                console.log("complete");
            });
        });
    </script>
@endsection