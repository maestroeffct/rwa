@if(!empty($course->noticeboards) and count($course->noticeboards))
    @foreach($course->noticeboards as $noticeboard)
        <div class="p-12 rounded-20 mb-20 alert alert-{{ $noticeboard->color }}">
            <div class="d-flex align-items-center">
                <div class="d-flex-center size-48 rounded-12 bg-{{ $noticeboard->color }}">
                    @php
                        $icon = $noticeboard->getIcon();
                        $iconColor = ($noticeboard->color == "neutral") ? 'text-gray-500' : 'text-white';
                    @endphp

                    @svg("iconsax-bul-{$icon}", ['height' => 24, 'width' => 24, 'class' => "icons {$iconColor}"])
                </div>

                <div class="ml-8">
                    <h4 class="font-14 ">{{ $noticeboard->title }}</h4>
                    <div class="mt-4 font-12">{{ trans('update.posted_by_:user_on_:date', ['user' => $noticeboard->creator->full_name, 'date' => dateTimeFormat($noticeboard->created_at, 'j M Y H:i')]) }}</div>
                </div>
            </div>

            <div class="mt-8">{!! $noticeboard->message !!}</div>
        </div>
    @endforeach
@endif
