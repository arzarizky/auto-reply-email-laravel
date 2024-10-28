@extends('layouts.app', [
    'title' => 'Email Received',
])

@section('konten')
    @include('pages.email-received.search-sort-user')

    @if ($messageType === 'error')
        <div class="alert alert-danger alert-dismissible" role="alert">
            Your Automatic Reply Data Has Been Displayed Successfully But There is an Error: {{$pesan}}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            </button>
        </div>
    @else
        <div class="alert alert-success alert-dismissible" role="alert">
            {{$pesan}}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            </button>
        </div>
        <div class="alert alert-success alert-dismissible" role="alert">
            {{$newEmail}}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            </button>
        </div>
    @endif

    @include('pages.email-received.table')

    @include('pages.email-received.modal-history-auto-reply')
@endsection

@push('css-konten')
    <link rel="stylesheet" href="{{ asset('template') }}/assets/vendor/css/pages/page-auth.css" />
@endpush

@push('js-konten')
    <script src="{{ asset('template') }}/assets/js/pages-account-settings-account.js"></script>
@endpush
