@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Login to Your Account</h4>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email or Username -->
                        <div class="mb-3">
                            <label for="login" class="form-label">Email or Username *</label>
                            <input type="text" 
                                   class="form-control @error('login') is-invalid @enderror" 
                                   id="login" 
                                   name="login" 
                                   value="{{ old('login') }}" 
                                   required 
                                   autofocus>
                            @error('login')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Password *</label>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="mb-3 form-check">
                            <input type="checkbox" 
                                   class="form-check-input" 
                                   id="remember" 
                                   name="remember">
                            <label class="form-check-label" for="remember">
                                Remember Me
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i>Login
                            </button>
                        </div>

                        <!-- Additional Links -->
                        <div class="mt-3 text-center">
                            <div class="mb-2">
                                <a href="#">Forgot Password?</a>
                            </div>
                            <div>
                                Don't have an account? <a href="{{ route('register') }}">Register here</a>
                            </div>
                        </div>

                        <!-- Display session messages -->
                        @if(session('pending'))
                            <div class="alert alert-warning mt-3">
                                <i class="fas fa-clock me-2"></i>
                                {{ session('pending') }}
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection