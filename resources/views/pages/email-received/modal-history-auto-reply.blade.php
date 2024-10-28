@foreach ($datas as $data)
    <div class="modal fade" id="history-email-{{ $data->id }}" tabindex="-1" style="display: none;"
        data-bs-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="history-email-{{ $data->id }}Title">History Email {{ $data->subject }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-2">
                            <label for="nameWithTitle" class="form-label">
                                <strong>
                                    Subject Pengirim
                                </strong>
                            </label>
                            <div class="subject">
                                {{$data->subject}}
                            </div>
                        </div>

                        <div class="col-12 mb-2">
                            <label for="nameWithTitle" class="form-label">
                                <strong>
                                    Body Pengirim
                                </strong>
                            </label>
                            <div class="subject">
                                {!!$data->body!!}
                            </div>
                        </div>

                        <div class="col-12 mb-2">
                            <label for="nameWithTitle" class="form-label">
                                <strong>
                                    Subject Auto Reply
                                </strong>
                            </label>
                            <div class="subject">
                                {{$data->emailReply->subject}}
                            </div>
                        </div>
                        <div class="col-12 mb-2">
                            <label for="nameWithTitle" class="form-label">
                                <strong>
                                    Body Auto Reply
                                </strong>
                            </label>
                            <div class="subject">
                                {!!$data->emailReply->body!!}
                            </div>
                        </div>
                        <div class="col-12 mb-2">
                            <label for="nameWithTitle" class="form-label">
                                <strong>
                                    CC Auto Reply
                                </strong>
                            </label>
                            <div class="subject">
                                {{$data->emailReply->cc ?? "Tidak Ada CC"}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                </div>
            </div>
        </div>
    </div>
@endforeach
