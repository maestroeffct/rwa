<div class="no-result default-no-result d-flex-center flex-column bg-white rounded-16 py-120 px-32 mt-20 text-center {{ !empty($extraClass) ? $extraClass : '' }}">
    <div class="no-result-logo {{ !empty($logoSm) ? 'logo-sm' : '' }}">
        <img src="/assets/design_1/img/no-result/{{ $file_name }}" alt="{{ $title }}" class="img-cover">
    </div>

    <h3 class="font-16 font-weight-bold mt-16">{{ $title }}</h3>
    <p class="mt-4 font-14 text-gray-500">{!! $hint !!}</p>

    @if(!empty($btn))
        <a href="{{ $btn['url'] }}" class="btn btn-primary mt-16">{{ $btn['text'] }}</a>
    @endif
</div>
