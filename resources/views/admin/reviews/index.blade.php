@extends('layouts.main')

@section('styles')
@endsection

@section('content')
    {{ csrf_field() }}
    <table id="reviews_table" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th class="text-nowrap">Coach</th>
                <th class="text-nowrap">User</th>
                <th class="text-nowrap">Rating</th>
                <th class="text-nowrap">Comment</th>
                <th class="text-nowrap">Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

@endsection

@section('scripts')
    <script>
        var datataTableUrl = baseUrl+'/reviews/datatables';

        $(document).ready(function() {
            var reviewDataTable;
            reviewDataTable = $('#reviews_table').DataTable({
                                responsive: true,
                                processing: true,
                                serverSide: true,
                                ajax: datataTableUrl,
                                columns: [
                                        { name: 'coach.first_name', render: setCoachName},
                                        { name: 'user.first_name', render: setUserName},
                                        { data: 'rating', name:'rating'},
                                        { data: 'comment', name:'comment'},
                                        { "render": getActionLinks },
                                    ],
                                deferRender: true
                            });
        });

        function setCoachName (data, type, full, meta) {
              if(full.coach!=null) {
                return full.coach.first_name+' '+full.coach.last_name;
              }
              return '-';
        }

        function setUserName (data, type, full, meta) {
              if(full.user!=null) {
                return full.user.first_name+' '+full.user.last_name;
              }
              return '-';
        }

        function getActionLinks (data, type, full, meta) {
            var action = '';
            action += '<a href="javascript:void(0)" class="btn btn-sm btn-danger m-r-2 delComment" data-reviewid="' + full.id + '"><i class="fa fa-trash-alt"></i> Delete</a>';
            return action;
        }

        $(document).on('click', '.delComment', function(event) {
            event.preventDefault();
            var $_this      = $(this);
            var review_id = $_this.data('reviewid');
            $.ajax({
                url: baseUrl+'/reviews/'+review_id,
                type: 'DELETE',
                data: {_token: $('input[name="_token"]').val()},
            })
            .done(function(data) {
                console.log("success");
                console.log(data);
                if(data){
                    toastr.success('Review Deleted');
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