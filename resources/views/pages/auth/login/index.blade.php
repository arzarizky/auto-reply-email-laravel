@extends('layouts.app', [
    'title' => 'Login',
])

@section('konten')
    <div class="authentication-wrapper authentication-basic container-p-y pt-0">
        <div class="authentication-inner">
            <!-- Register -->
            <div class="card">
                <div class="card-body">
                    <!-- Logo -->
                    <div class="app-brand justify-content-center mb-3">
                        <a href="index.html" class="app-brand-link gap-2">
                            <h1>DWA</h1>
                        </a>
                    </div>
                    <!-- /Logo -->
                    <h4 class="mb-2">Welcome 👋</h4>
                    <p class="mb-4">Please sign-in to your account and start the adventure</p>

                    <form id="formAuthentication" method="post" class="mb-3" action="{{ route('auth.login-post') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="text" class="form-control" id="email" name="email"
                                placeholder="Enter your email" autofocus />
                        </div>
                        <div class="mb-3 form-password-toggle">
                            <div class="d-flex justify-content-between">
                                <label class="form-label" for="password">Password</label>
                            </div>
                            <div class="input-group input-group-merge">
                                <input type="password" id="password" class="form-control" name="password"
                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                    aria-describedby="password" />
                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-primary d-grid w-100" type="submit">Sign in</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /Register -->
        </div>
    </div>
@endsection

@push('js-konten')
@endpush

@push('css-konten')
    <link rel="stylesheet" href="{{ asset('template') }}/assets/vendor/css/pages/page-auth.css" />
@endpush
