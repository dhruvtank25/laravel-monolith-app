@extends('layouts.main')

@section('styles')
@endsection

@section('content')
    {{ csrf_field() }}
    <div class="row">
        <div class="col-md-6">
            <a class="btn btn-large btn-info width-100 m-5" href="{{url('admin/countries/create')}}"><span class="fa fa-plus-circle"></span> Add New</a>
            @if(count($countries)==0)
                <a class="btn btn-large btn-info width-100 m-5" href="{{url('admin/countries/sync')}}"><span class="fa fa-sync"></span> Sync</a>
            @endif
        </div>
    </div>
    <table id="countries_table" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th class="text-nowrap">Name</th>
                <th class="text-nowrap">Code</th>
                <th class="text-nowrap">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($countries as $country)
                <tr>
                    <td>{{$country->name}}</td>
                    <td>{{$country->code}}</td>
                    <td>
                        {{-- <a class="btn btn-sm btn-info m-r-2 viewUser" href="{{ url('admin/countries/'.$country->id) }}"><i class="fa fa-eye"></i> View</a> --}}
                        <a class="btn btn-sm btn-primary m-r-2 editUser" href="{{ url('admin/countries/'.$country->id.'/edit') }}" class=""><i class="fa fa-edit"></i> Edit</a>
                        <a href="javascript:void(0)" class="btn btn-sm btn-danger m-r-2 delUser" data-id="{{$country->id}}"><i class="fa fa-trash-alt"></i> Delete</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $("#countries_table").dataTable();
        });

        $(document).on('click', '.delUser', function(event) {
            event.preventDefault();
            var $_this      = $(this);
            var country_id = $_this.data('id');
            $.ajax({
                url: baseUrl+'/countries/'+country_id,
                type: 'DELETE',
                data: {_token: $('input[name="_token"]').val()},
            })
            .done(function(data) {
                console.log("success");
                console.log(data);
                if(data){
                    toastr.success('Country Deleted');
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