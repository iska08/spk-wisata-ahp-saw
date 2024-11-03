@extends('layouts.login')
@section('title')
    Register
@endsection
@section('content')
    <!-- ======= main ======= -->
    <section class="my-login-page">
        <div class="container form-Bg">
            <div class="row justify-content-md-center">
                <div class="card-wrapper">
                    <div class="brand">
                        <img src="{{ url('frontend/images/lock.png') }}" alt="logo" />
                    </div>
                    <div class="card fat">
                        <div class="card-body">
                            {{-- register --}}
                            <h4 class="card-title text-center">Register</h4>
                            <form action="{{ route('register.index') }}" method="POST" class="my-login-validation" novalidate="">
                                @csrf
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama</label>
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autofocus placeholder="Masukkan Nama Anda" />
                                    @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required placeholder="Masukkan Username" />
                                    @error('username')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required placeholder="Masukkan Alamat Email" />
                                    @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required placeholder="Masukkan Password" />
                                    @error('password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                    <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required placeholder="Konfirmasi Password" />
                                </div>
                                <div class="m-0 d-grid">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        Register
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="footer">
                        <p>Copyright &copy;
                            {{ now()->year }} &mdash; SPK
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection