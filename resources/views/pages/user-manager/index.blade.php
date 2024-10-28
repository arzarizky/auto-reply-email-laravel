@extends('layouts.app', [
    'title' => 'User Manager',
])

@section('konten')
    {{-- search dan sort --}}
    @include('pages.user-manager.search-sort-user')
    {{-- search dan sort --}}

    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUser"
        style=" border-bottom-left-radius: 0px; border-bottom-right-radius: 0px;">
        <span class="tf-icons bx bx-user-plus me-1"></span>Add User
    </button>

    {{-- data table user --}}
    @include('pages.user-manager.table-user')
    {{-- data table user --}}

    {{-- add user --}}
    @include('pages.user-manager.add')
    {{-- / add user --}}

    {{-- edit user --}}
    @include('pages.user-manager.edit')
    {{-- / edit user --}}

    {{-- view avatar user --}}
    @include('pages.user-manager.view-avatar')
    {{-- / view avatar user --}}

    {{-- edit password user --}}
    @include('pages.user-manager.edit-password')
    {{-- / edit password user --}}

     {{-- edit password user --}}
     @include('pages.user-manager.delete')
     {{-- / edit password user --}}
@endsection

@push('css-konten')
    <link rel="stylesheet" href="{{ asset('template') }}/assets/vendor/css/pages/page-auth.css" />
@endpush

@push('js-konten')
    <script src="{{ asset('template') }}/assets/js/pages-account-settings-account.js"></script>
@endpush
