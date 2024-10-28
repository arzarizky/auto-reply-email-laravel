@foreach ($users as $user)
    <div class="modal fade" id="editUser-{{ $user->id }}" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" method="POST" action="{{ route('user-manager-update', $user->id) }}"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="editUser-{{ $user->id }}Title">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="d-flex align-items-start align-items-sm-center gap-4 ">
                        @if ($user->avatar === null)
                            <img src="{{ asset('template') }}/assets/img/avatars/avatar-1.png" alt="user-avatar"
                                class="d-block rounded" height="100" width="100"
                                id="preview-upload-avatar-{{ $user->id }}" />

                            <div hidden>
                                <img src="{{ asset('template') }}/assets/img/avatars/avatar-1.png" alt="user-avatar"
                                    class="d-block rounded" height="100" width="100"
                                    id="preview-upload-avatar-before-{{ $user->id }}" />
                            </div>
                        @else
                            <img src="{{ $user->getPicAvatarAdmin() }}" alt="Avatar" alt="user-avatar"
                                class="d-block rounded" height="100" width="100"
                                id="preview-upload-avatar-{{ $user->id }}" />

                            <div hidden>
                                <img src="{{ $user->getPicAvatarAdmin() }}" alt="Avatar" alt="user-avatar"
                                    class="d-block rounded" height="100" width="100"
                                    id="preview-upload-avatar-before-{{ $user->id }}" />
                            </div>
                        @endif
                        <div class="button-wrapper">
                            <label for="upload-avatar-{{ $user->id }}" class="btn btn-primary me-2 mb-4"
                                tabindex="0">
                                <span class="d-none d-sm-block">Upload new photo</span>
                                <i class="bx bx-upload d-block d-sm-none"></i>
                                <input type="file" id="upload-avatar-{{ $user->id }}"
                                    class="account-file-input upload-avatar-{{ $user->id }}" hidden
                                    accept="image/png, image/jpeg" name="avatar" />
                            </label>
                            <button type="button" class="btn btn-outline-secondary account-image-reset mb-4"
                                id="reset-avatar-{{ $user->id }}">
                                <i class="bx bx-reset d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Reset</span>
                            </button>

                            <p class="text-muted mb-0">Allowed JPG, GIF or PNG</p>
                        </div>
                    </div>

                    <div class="mt-3 mb-3">
                        <label for="name-{{ $user->id }}" class="form-label">Nama Lengkap</label>
                        <input class="form-control" type="text" id="name-{{ $user->id }}" name="name"
                            placeholder="John Doe" value="{{ $user->name }}" required />
                    </div>


                    <div class="row">

                        <div class="mb-3 col-md-6">
                            <label for="role_id" class="form-label">Select Role</label>
                            <select class="form-select" id="role_id" name="role_id"
                                aria-label="Default select example" required>
                                <option value="" disabled {{ is_null($user->role_id) ? 'selected' : '' }}>Pilih
                                    Role</option>

                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}"
                                        {{ $user->role_id  === $role->id ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="email-{{ $user->id }}" class="form-label">E-mail</label>
                            <input class="form-control" type="text" id="email-{{ $user->id }}" name="email"
                                value="{{ $user->email }}" placeholder="john.doe@example.com" required />
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

@push('js-konten')
    <script>
        $('.edit-avatar').on('click', function() {

            var user_id = $(this).attr('user-id');

            $('#upload-avatar-' + user_id).on('change', function() {

                var fileName = $('#upload-avatar-' + user_id).val().replace('C:\\fakepath\\', " ");

                $('#upload-avatar-' + user_id).next('.upload-avatar-' + user_id).html(
                    fileName);
            })

            function readURLAddIconFasilitasEdit(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#preview-upload-avatar-' + user_id).attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }

            $("#upload-avatar-" + user_id).change(function() {
                readURLAddIconFasilitasEdit(this);
            });

            $('#reset-avatar-' + user_id).on('click', function() {
                $('#preview-upload-avatar-' + user_id).attr('src', $('#preview-upload-avatar-before-' +
                    user_id).attr('src'));
            });
        })
    </script>
@endpush
