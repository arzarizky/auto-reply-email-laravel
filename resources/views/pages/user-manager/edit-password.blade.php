@foreach ($users as $user)
    <div class="modal fade" id="editPasswordUser-{{ $user->id }}" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" method="POST" action="{{ route('user-manager-update-password', $user->id) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="editPasswordUser-{{ $user->id }}Title">Update Password User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="mt-3 mb-3">
                        <label for="name-{{ $user->id }}-edit-password" class="form-label">Nama Lengkap</label>
                        <input class="form-control" type="text" id="name-{{ $user->id }}-edit-password" name="name"
                            placeholder="John Doe" value="{{ $user->name }}" readonly />
                    </div>

                    <div class="mb-3 form-password-toggle">
                        <div class="d-flex justify-content-between">
                            <label class="form-label" for="password">Password</label>
                        </div>
                        <div class="input-group input-group-merge">
                            <input type="password" id="password-{{ $user->id }}-edit-password" required class="form-control" name="password"
                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                aria-describedby="password" />
                            <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
@endforeach
