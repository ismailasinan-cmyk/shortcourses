@extends('layouts.app')

@section('content')
<div class="container text-center py-5">
    <h1 class="display-1">500</h1>
    <h2>{{ __('Server Error') }}</h2>
    <p class="lead">{{ __('Something went wrong on our servers. Please try again later.') }}</p>
    <a href="{{ route('home') }}" class="btn btn-primary mt-3">{{ __('Go Home') }}</a>
</div>
@endsection
