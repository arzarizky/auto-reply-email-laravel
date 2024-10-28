<div class="card" style="border-top-left-radius: 0px;">
    <div class="table-responsive text-nowrap">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Mail</th>
                    <th>Role</th>
                    <th>Avatar</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>
                            {{ $user->name }}
                        </td>
                        <td>
                            {{ $user->email }}
                        </td>
                        <td>
                            {{ $user->role->name }}
                        </td>
                        <td>
                            <ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center">
                                <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                    class="avatar avatar-xs pull-up" title="{{ $user->name }}">
                                    @if ($user->avatar === null)
                                        <img src="{{ asset('template') }}/assets/img/avatars/avatar-1.png"
                                            alt="Avatar" class="rounded-circle" data-bs-toggle="modal"
                                            data-bs-target="#viewAvatar-{{ $user->id }}" />
                                    @else
                                        <img src="{{ $user->getPicAvatarAdmin() }}" alt="Avatar"
                                            class="rounded-circle" data-bs-toggle="modal"
                                            data-bs-target="#viewAvatar-{{ $user->id }}" />
                                    @endif
                                </li>
                            </ul>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                    data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <button class="dropdown-item edit-avatar" data-bs-toggle="modal"
                                        user-id="{{ $user->id }}" data-bs-target="#editUser-{{ $user->id }}"><i
                                            class="bx bx-edit-alt me-1"></i>
                                        Edit</button>
                                    <button class="dropdown-item" data-bs-toggle="modal"
                                        data-bs-target="#editPasswordUser-{{ $user->id }}"><i
                                            class='bx bx-lock-open'></i>
                                        Update Password</button>

                                    <button class="dropdown-item" data-bs-toggle="modal"
                                        data-bs-target="#deleteUser-{{ $user->id }}"><i
                                            class="bx bx-trash me-1"></i>
                                        Delete</button>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-danger text-center" style="border: none;">
                            <h5 class="mt-5 mb-5">Data {{ request()->input('search') }} Tidak Ada</h5>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="d-flex justify-content-between" style="align-self: center;">
    <div class="ps-2" style="margin-top: 25px;" class="data-count">
       Menampilkan {{ $users->count() }} data dari {{ $users->total() }}
    </div>

    <div>
        {{ $users->appends(['search' => $search, 'per_page' => $perPage])->links('layouts.pagination') }}

    </div>
</div>
