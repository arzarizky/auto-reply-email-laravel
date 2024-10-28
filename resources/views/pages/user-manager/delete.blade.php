@foreach ($users as $user)
    <div class="modal fade" id="deleteUser-{{ $user->id }}" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" method="POST" action="{{ route('user-manager-delete', $user->id) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteUser-{{ $user->id }}Title">Delete User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="d-flex align-items-start align-items-sm-center gap-4 justify-content-center">
                        @if ($user->avatar === null)
                            <img src="{{ asset('template') }}/assets/img/avatars/avatar-1.png" alt="user-avatar"
                                class="d-block rounded" height="100" width="100"
                                id="preview-upload-avatar-delete-{{ $user->id }}" />
                        @else
                            <img src="{{ $user->getPicAvatarAdmin() }}" alt="Avatar" alt="user-avatar"
                                class="d-block rounded" height="100" width="100"
                                id="preview-upload-avatar-delete-{{ $user->id }}" />
                        @endif
                    </div>

                    <div class="mt-3 mb-3">
                        <label for="name-delete-{{ $user->id }}" class="form-label">Nama Lengkap</label>
                        <input class="form-control" type="text" id="name-delete-{{ $user->id }}" name="name"
                            placeholder="John Doe" value="{{ $user->name }}" readonly required />
                    </div>


                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="no_tlp-delete-{{ $user->id }}" class="form-label">No Tlp</label>
                            <input class="form-control" type="text" id="no_tlp-delete-{{ $user->id }}" name="no_tlp"
                                value="{{ $user->no_tlp }}"
                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                placeholder="082244876123" required readonly />
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="email-delete-{{ $user->id }}" class="form-label">E-mail</label>
                            <input class="form-control" type="text" id="email-delete-{{ $user->id }}" name="email"
                                value="{{ $user->email }}" placeholder="john.doe@example.com" readonly required />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary">Delete</button>
                </div>
            </form>
        </div>
    </div>
@endforeach
