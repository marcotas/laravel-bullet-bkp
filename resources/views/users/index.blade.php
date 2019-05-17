@extends('layouts.app')

@section('content')
<div class="container">
    <users-list :initial="{{ json_encode($users) }}"></users-list>
</div>
@endsection
