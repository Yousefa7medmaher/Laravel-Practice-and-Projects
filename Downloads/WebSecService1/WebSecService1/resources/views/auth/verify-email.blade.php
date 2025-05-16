@extends('layouts.master')
@section('title', 'Verify Email')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Verify Your Email Address') }}</div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="alert alert-info">
                        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
                    </div>

                    <div class="mt-4">
                        <form method="POST" action="{{ route('verification.send') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                {{ __('Resend Verification Email') }}
                            </button>
                        </form>

                        <form method="POST" action="{{ route('logout') }}" class="d-inline ms-2">
                            @csrf
                            <button type="submit" class="btn btn-secondary">
                                {{ __('Log Out') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 