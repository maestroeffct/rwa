@if(!empty($seoContent) and !empty($seoContent['title']) and !empty($seoContent['description']))
    <section class="bg-gray-100 p-16 rounded-24 border-gray-200 mt-48">
        <h3 class="font-14">{{ $seoContent['title'] }}</h3>
        <div class="mt-12 text-gray-500">{!! nl2br($seoContent['description']) !!}</div>
    </section>
@endif
