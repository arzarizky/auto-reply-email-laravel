<div class="card" style="border-top-left-radius: 0px;">
    <div class="table-responsive text-nowrap">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Nama</th>
                    <th>Subject</th>
                    <th>Diterima</th>
                    <th>Status Reply</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($datas as $data)
                    <tr>
                        <td>
                            {{ $data->from_email }}
                        </td>
                        <td>
                            {{ $data->from_name }}
                        </td>
                        <td class="text-primary" style="cursor: pointer;" data-bs-toggle="modal"
                            data-bs-target="#history-email-{{ $data->id }}">
                            {{ $data->subject }}
                        </td>
                        <td>
                            {{ $data->received_at }}
                        </td>
                        <td>
                            @if ($data->emailReply->success === null)
                                <span class="badge rounded-pill bg-dark">{{ 'Data Tidak Ada' ?? 'NOT DEF' }}</span>
                            @else
                                @if ($data->emailReply->success === 1)
                                    <span class="badge rounded-pill bg-success">{{ 'Sudah' ?? 'NOT DEF' }}</span>
                                @else
                                    <span class="badge rounded-pill bg-warning">{{ 'Belum' ?? 'NOT DEF' }}</span>
                                @endif
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-danger text-center" style="border: none;">
                            <h5 class="mt-5 mb-5">
                                Data
                                <span class="text-danger">{{ request()->input('search') }}</span>
                                Tidak Ada
                            </h5>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="d-flex justify-content-between" style="align-self: center;">
    <div class="ps-2" style="margin-top: 25px;" class="data-count">
        Menampilkan {{ $datas->count() }} data dari {{ $datas->total() }}
    </div>

    <div>
        {{ $datas->appends(['search' => $search, 'per_page' => $perPage])->links('layouts.pagination') }}

    </div>
</div>
