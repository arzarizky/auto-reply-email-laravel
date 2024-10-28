@extends('layouts.app', [
    'title' => 'Mail Server',
])

@section('konten')
    <form  method="POST" action="{{ route('mail-setting-update', $datas->id) }}">
        @csrf
        <div class="card p-3 mb-3">
            <div class="mb-2">
                <label for="host" class="form-label">Host</label>
                <input class="form-control" type="text" id="host" name="host" value="{{ $datas->host }}"
                    required />
            </div>
        </div>

        <div class="card p-3 mb-3">
            <div class="mb-2">
                <label for="port" class="form-label">Port</label>
                <input class="form-control" type="number" id="port" name="port" value="{{ $datas->port }}"
                    required />
            </div>
        </div>

        <div class="card p-3 mb-3">
            <div class="mb-2">
                <label for="encryption" class="form-label">Encryption</label>
                <input class="form-control" type="text" id="encryption" name="encryption"
                    value="{{ $datas->encryption }}" required />
            </div>
        </div>

        <div class="card p-3 mb-3">
            <div class="mb-2">
                <label for="username" class="form-label">Username</label>
                <input class="form-control" type="text" id="username" name="username" value="{{ $datas->username }}"
                    required disabled />
            </div>
        </div>

        <div class="card p-3 mb-3">
            <div class="mb-2">
                <label for="password" class="form-label">Password</label>
                <input class="form-control" type="text" id="password" name="password" value="{{ $datas->password }}"
                    required />
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100">Update</button>
    </form>
@endsection
