@extends('layouts.main')

@section('styles')
@endsection

@section('content')
    {{ csrf_field() }}
    <div class="row">
        <div class="col-md-6">
            <a class="btn btn-large btn-info width-100 m-5" href="{{url('admin/companies/create')}}"><span class="fa fa-plus-circle"></span> Add New</a>
        </div>
    </div>
    <table id="companies_table" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th width="1%" data-orderable="false"></th>
                <th class="text-nowrap">Title</th>
                <th class="text-nowrap">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($companies as $company)
                <tr>
                    <td><img src= "{{ FileUploadHelper::getDocPath($company->image, 'company_logo') }}" alt="x" height=50 width=50></td>
                    <td>{{$company->name}}</td>
                    <td>
                        <a class="btn btn-sm btn-info m-r-2 viewUser" href="{{ url('admin/companies/'.$company->id) }}"><i class="fa fa-eye"></i> View</a>
                        <a class="btn btn-sm btn-primary m-r-2 editUser" href="{{ url('admin/companies/'.$company->id.'/edit') }}" class=""><i class="fa fa-edit"></i> Edit</a>
                        @if($company->id!=1)
                            <a href="javascript:void(0)" class="btn btn-sm btn-danger m-r-2 delUser" data-id="{{$company->id}}"><i class="fa fa-trash-alt"></i> Delete</a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $("#companies_table").dataTable();
        });

        $(document).on('click', '.delUser', function(event) {
            event.preventDefault();
            var $_this      = $(this);
            var company_id = $_this.data('id');
            if(company_id==1) {
                toastr.error('You cannot delete this company');
                return false;
            }
            $.ajax({
                url: baseUrl+'/companies/'+company_id,
                type: 'DELETE',
                data: {_token: $('input[name="_token"]').val()},
            })
            .done(function(data) {
                console.log("success");
                console.log(data);
                if(data){
                    toastr.success('company Deleted');
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