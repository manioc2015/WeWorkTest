@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Github Repository Issues</div>
                <div class="panel-body" id="issues">
			@if (count($issues) > 0)
			<table style="width: 100%">
				<tr><th>#</th><th>User</th><th>Title</th><th>Issue</th></tr>
				@foreach ($issues as $issue)
				<tr><td>{{$issue->number}}</td><td>{{$issue->user->login}}</td><td>{{$issue->title}}</td><td>{{$issue->body}}</td></tr>
				@endforeach
			</table>
			@else
                        No Records!
                        @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
