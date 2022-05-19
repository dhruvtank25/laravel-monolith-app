@extends('layouts.main')

@section('styles')
@endsection

@section('content')
    @include('admin.users.show_html')
@endsection

@section('scripts')
<!-- ================== PARTIAL INFO JS ================== -->
<script src="{{ asset('backend/js/user_info.js')}}"></script>
<script>
    $(document).ready(function() {
        var user_id   = '{{$user->id}}';
        var role_name = '{{$user->roles->name}}';

        /** Set Datatable to user lesson table */
        var apiurl    = baseUrl+'/appointment/datatables';
        if(role_name=='coach')
            apiurl += '?coach_id='+user_id;
        else
            apiurl += '?user_id='+user_id;
        initializeLessonTable(apiurl);

        /** Set Datatable to user transaction table */
        var transapiurl = baseUrl+'/user-transactions/datatables/'+user_id;
        initializeUserTransactionTable(transapiurl, user_id);
    });
</script>
@endsection
