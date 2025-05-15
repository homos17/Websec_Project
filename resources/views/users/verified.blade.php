@extends('layouts.master')

@section('title', 'User Verified')

@section('content')
<div class="row">
    <div class="m-4 col-sm-6">
        <div class="alert alert-success">
            <strong>Congratulations!</strong> {{$user->email}} is now verified.
        </div>
    </div>
</div>
@endsection
