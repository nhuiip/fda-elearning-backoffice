<div class="page-title pt-0">
    <div class="row">
        <div class="col-12 col-sm-6">
            <h3>{{ $title }}</h3>
        </div>
        <div class="col-12 col-sm-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html" data-bs-original-title="" title=""> <svg
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-home">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg></a>
                </li>
                @foreach ($breadcrumbs as $index => $item)
                    @if ($index !== count($breadcrumbs) - 1)
                        <li class="breadcrumb-item"><a href="{{ $item['route'] }}">{{ $item['name'] }}</a></li>
                    @else
                        <li class="breadcrumb-item active">{{ $item['name'] }}</li>
                    @endif
                @endforeach
            </ol>
        </div>
    </div>
</div>
