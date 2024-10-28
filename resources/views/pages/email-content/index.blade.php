@extends('layouts.app', [
    'title' => 'Mail Server',
])

@section('konten')
    <form action="{{ route('update-email-settings', ['id' => $datas->id]) }}" method="POST">
        @csrf
        <div class="card p-3 mb-3">
            <div class="form-group">
                <label for="auto_reply_body" class="mb-2">Auto Reply Message (body email)</label>
                <div id="editor-container"></div>
                <input type="hidden" name="auto_reply_body" id="auto_reply_body">
            </div>
        </div>

        <div class="card p-3 mb-3">
            <div class="form-group">
                <label for="auto_reply_cc">Auto Reply CC (comma-separated)</label>
                <input type="text" class="form-control" id="auto_reply_cc" name="auto_reply_cc"
                    value="{{ $datas->cc }}" placeholder="example1@mail.com, example2@mail.com">
            </div>
        </div>

        <div class="card p-3 mb-3">
            <div class="row g-6">
                <div class="col mb-0">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" value="{{ $datas->start_auto_replied }}" id="start_date" name="start_date"
                        class="form-control" required>
                </div>
                <div class="col mb-0">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" value="{{ $datas->end_auto_replied }}" id="end_date" name="end_date"
                        class="form-control" required>
                </div>
            </div>
        </div>

        <div class="card p-3 mb-3">
            <label for="auto_replied" class="form-label">Pilih Status Auto Reply</label>
            <select class="form-select" id="auto_replied" name="auto_replied" aria-label="select product"
                required>
                <option selected="" value="">Pilih Type</option>
                <option value="Aktif" {{ $datas->auto_replied == 1 ? 'selected' : '' }}>Aktif</option>
                <option value="Non Akti" {{ $datas->auto_replied == 0 ? 'selected' : '' }}>Non Aktif</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary w-100">Update Auto Reply</button>
    </form>
@endsection

@push('js-konten')
    <script>
        var toolbarOptions = [
            [{
                'font': []
            }],
            [{
                'header': [1, 2, 3, 4, 5, 6, false]
            }],
            ['bold', 'italic', 'underline', 'strike'],
            [{
                'color': []
            }, {
                'background': []
            }],
            [{
                'list': 'ordered'
            }, {
                'list': 'bullet'
            }],
            [{
                'align': []
            }],
            ['link', 'blockquote', 'code-block'],
            ['clean']
        ];

        var quill = new Quill('#editor-container', {
            theme: 'snow',
            modules: {
                toolbar: toolbarOptions
            }
        });

        // Set the old value for the editor body
        var oldBody = {!! json_encode($datas->body) !!};
        quill.root.innerHTML = oldBody;

        // Store the editor content in the hidden input before submitting the form
        document.querySelector('form').onsubmit = function() {
            document.querySelector('#auto_reply_body').value = quill.root.innerHTML;
        };
    </script>
@endpush
