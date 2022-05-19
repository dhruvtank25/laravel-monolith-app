@if (count($errors) > 0)
<div class="alert alert-danger">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	 @foreach ($errors->all() as $error)
		<p>{{ $error }}</p>
	 @endforeach
</div>
@endif

@if($message = session('success'))
<div class="alert alert-success">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	@if(is_array($message))
	    @foreach ($message as $m)
	       <p>{{ $m }}</p>
	    @endforeach
	@else
	    {{ $message }}
	@endif
</div>
@endif

@if ($message = session('error'))
<div class="alert alert-danger">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    @if(is_array($message))
		@foreach ($message as $m)
			<p>{{ $m }}</p>
		@endforeach
    @else
		{{ $message }}
    @endif
</div>
@endif

@if ($message = session('warning'))
<div class="alert alert-warning">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    @if(is_array($message))
		@foreach ($message as $m)
			<p>{{ $m }}</p>
		@endforeach
    @else
		{{ $message }}
    @endif
</div>
@endif

@if ($message = session('info'))
<div class="alert alert-info">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    @if(is_array($message))
		@foreach ($message as $m)
			<p>{{ $m }}</p>
		@endforeach
    @else
    {{ $message }}
    @endif
</div>
@endif