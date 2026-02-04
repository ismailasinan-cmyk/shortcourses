@extends('layouts.app')

@section('content')
<div class="container text-center py-5">
    <h1 class="display-1">404</h1>
    <h2>{{ __('Page Not Found') }}</h2>
    <p class="lead">{{ __('The page you are looking for does not exist or has been moved.') }}</p>
    <a href="{{ route('home') }}" class="btn btn-primary mt-3">{{ __('Go Home') }}</a>
</div>
@endsection
