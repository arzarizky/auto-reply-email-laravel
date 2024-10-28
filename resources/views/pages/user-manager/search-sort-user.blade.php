<form action="">
    <div class="row">
        <div class="col-sm-10 col-lg-10 col-md-10 mb-4">
            <div class="card p-3">
                <div class="input-group input-group-merge">
                    <span class="input-group-text" id="basic-addon-search31" style="cursor: pointer;"
                        onclick="event.preventDefault();
                        document.getElementById('submit-search').click();">
                        <i class="bx bx-search"></i>
                    </span>
                    <input type="search" class="form-control" placeholder="Cari nama atau email atau role user" aria-label="Search..."
                        aria-describedby="basic-addon-search31" value="{{ request()->input('search') }}" name="search">
                </div>
            </div>
        </div>
        <div class="col-sm-2 col-lg-2 col-md-2 mb-4">
            <div class="card p-3">
                <select id="sort-value" id="defaultSelect" class="form-select" name="per_page">
                    <option value="5" @if (request('per_page') == 5 || request('per_page') == null) selected @endif>5</option>
                    <option value="10" @if (request('per_page') == 10) selected @endif>10</option>
                    <option value="25" @if (request('per_page') == 25) selected @endif>25</option>
                    <option value="50" @if (request('per_page') == 50) selected @endif>50</option>
                    <option value="100" @if (request('per_page') == 100) selected @endif>100
                </select>
            </div>
        </div>
    </div>
    <input type="hidden" name="page" value="{{ request('page', 1) }}">
    <button type="submit" id="submit-search" hidden></button>
</form>

@push('js-konten')
    <script>
        $('#sort-value').on('change', function() {
            $(this).closest('form').submit();
        });
    </script>
@endpush
