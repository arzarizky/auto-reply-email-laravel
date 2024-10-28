@foreach ($users as $user)
    <div class="modal fade" id="viewAvatar-{{ $user->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="d-flex justify-content-center">
                @if ($user->avatar === null)
                    <img src="{{ asset('template') }}/assets/img/avatars/avatar-1.png" alt="user-avatar"
                        class="d-block rounded" style="width: 80%; height: 90%;" />
                @else
                    <img src="{{ $user->getPicAvatarAdmin() }}" alt="Avatar" alt="user-avatar" class="d-block rounded"
                        style="width: 80%; height: 90%;" />
                @endif
            </div>
        </div>
    </div>
@endforeach
