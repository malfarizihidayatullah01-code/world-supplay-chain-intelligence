@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="col-md-5">
        <div class="text-center mb-4">
            <h2 class="fw-bold text-primary">Risk Intelligence</h2>
            <p class="text-muted">Sign in to your account</p>
        </div>
        
        <div class="card p-4">
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Email address</label>
                        <input type="email" class="form-control form-control-lg" id="email" name="email" value="{{ old('email') }}" required autofocus>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label fw-semibold">Password</label>
                        <input type="password" class="form-control form-control-lg" id="password" name="password" required>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100 fw-semibold">Sign In</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
