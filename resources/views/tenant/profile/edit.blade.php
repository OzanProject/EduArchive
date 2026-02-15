@extends('backend.layouts.app')

@php
    /** @var \App\Models\User $user */
@endphp

@section('title', 'Profile')
@section('page_title', 'Profile User (Sekolah)')

@section('breadcrumb')
    <li class="breadcrumb-item active">User Profile</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-3">
            <!-- Profile Image -->
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center mb-3">
                        @if($user->avatar)
                            @php
                                $avatarUrl = tenant() ? tenant_asset($user->avatar) : asset('storage/' . $user->avatar);
                            @endphp
                            <img class="profile-user-img img-fluid img-circle shadow-sm" src="{{ $avatarUrl }}"
                                alt="User profile picture"
                                style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #dee2e6;">
                        @else
                            <img class="profile-user-img img-fluid img-circle shadow-sm"
                                src="{{ asset('adminlte3/dist/img/user4-128x128.jpg') }}" alt="User profile picture"
                                style="border: 3px solid #dee2e6;">
                        @endif
                    </div>
                    <h3 class="profile-username text-center font-weight-bold">{{ $user->name }}</h3>
                    <p class="text-muted text-center">
                        @if($user->role === 'admin_sekolah')
                            Admin Sekolah
                        @elseif($user->role === 'operator')
                            Operator Sekolah
                        @else
                            {{ ucfirst($user->role ?? 'User') }}
                        @endif
                    </p>
                    <p class="text-muted text-center">{{ $user->email }}</p>
                    <p class="text-muted text-center small">{{ tenant('nama_sekolah') }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">Settings</a></li>
                        <li class="nav-item"><a class="nav-link" href="#password" data-toggle="tab">Ganti Password</a></li>
                    </ul>
                </div><!-- /.card-header -->
                <div class="card-body">
                    <div class="tab-content">
                        <!-- Tab Settings (Update Profile) -->
                        <div class="active tab-pane" id="settings">
                            @if (session('status') === 'profile-updated')
                                <div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5><i class="icon fas fa-check"></i> Berhasil!</h5>
                                    Profil berhasil diperbarui.
                                </div>
                            @endif

                            <form method="post" action="{{ route('tenant.profile.update', ['tenant' => tenant('id')]) }}"
                                class="form-horizontal" enctype="multipart/form-data">
                                @csrf
                                @method('patch')

                                <div class="form-group row">
                                    <label for="name" class="col-sm-2 col-form-label">Nama Lengkap</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="{{ old('name', $user->name) }}" required autofocus>
                                        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="email" class="col-sm-2 col-form-label">Email</label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control" id="email" name="email"
                                            value="{{ old('email', $user->email) }}" required>
                                        @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="avatar" class="col-sm-2 col-form-label">Foto Profil</label>
                                    <div class="col-sm-10">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="avatar" name="avatar"
                                                accept="image/*">
                                            <label class="custom-file-label" for="avatar">Pilih file...</label>
                                        </div>
                                        <small class="text-muted">Format: JPG, PNG. Maks: 1MB.</small>
                                        @error('avatar') <span class="text-danger d-block">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="offset-sm-2 col-sm-10">
                                        <button type="submit" class="btn btn-primary">Simpan Perubahan (Sekolah)</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Tab Password -->
                        <div class="tab-pane" id="password">
                            @if (session('status') === 'password-updated')
                                <div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5><i class="icon fas fa-check"></i> Berhasil!</h5>
                                    Password berhasil diperbarui.
                                </div>
                            @endif

                            <form method="post"
                                action="{{ route('tenant.profile.password.update', ['tenant' => tenant('id')]) }}"
                                class="form-horizontal">
                                @csrf
                                @method('put')

                                <div class="form-group row">
                                    <label for="current_password" class="col-sm-3 col-form-label">Password Saat Ini</label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control" id="current_password"
                                            name="current_password" autocomplete="current-password">
                                        @error('current_password', 'updatePassword') <span
                                        class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="password" class="col-sm-3 col-form-label">Password Baru</label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control" id="password" name="password"
                                            autocomplete="new-password">
                                        @error('password', 'updatePassword') <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="password_confirmation" class="col-sm-3 col-form-label">Konfirmasi
                                        Password</label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control" id="password_confirmation"
                                            name="password_confirmation" autocomplete="new-password">
                                        @error('password_confirmation', 'updatePassword') <span
                                        class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="offset-sm-3 col-sm-9">
                                        <button type="submit" class="btn btn-danger">Ganti Password</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            // Preview Avatar
            $('#avatar').on('change', function () {
                let reader = new FileReader();
                reader.onload = (e) => {
                    $('.profile-user-img').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            });

            // File Input name fix for BS4 Custom File Input
            bsCustomFileInput.init();
        });
    </script>
@endpush