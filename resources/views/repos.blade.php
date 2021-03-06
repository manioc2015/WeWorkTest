@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Github Repositories</div>
                <div class="panel-body" id="repos">
			@if (count($repos) > 0)
			<table style="width: 100%">
				<tr><th>ID</th><th>Name</th><th>Owner</th><th>Issues</th></tr>
				@foreach ($repos as $repo)
				<tr><td>{{$repo->id}}</td><td>{{$repo->name}}</td><td>{{$repo->owner->login}}</td><td><a href='/showRepoIssues/{{$repo->owner->login}}/{{$repo->name}}'>Issues</a></td></tr>
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
