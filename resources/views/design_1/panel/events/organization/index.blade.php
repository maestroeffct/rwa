@extends('design_1.panel.layouts.panel')

@section('content')
    {{-- Organization --}}
    <div class="card-with-dashed-mask position-relative d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between bg-white p-16 rounded-24 mb-28">
        <div class="d-flex align-items-center">
            <div class="d-flex-center size-56 rounded-12 bg-gray-100">
                <img src="{{ $organization->getAvatar(56) }}" alt="{{ $organization->full_name }}" class="img-cover rounded-12">
            </div>

            <div class="ml-8">
                <h6 class="font-14 font-weight-bold">{{ $organization->full_name }}</h6>
                <p class="font-12 mt-4 text-gray-500">{{ $coursesCount }} {{ trans('product.courses') }} | {{ $instructorsCount }} {{ trans('home.instructors') }} | {{ $studentsCount }} {{ trans('public.students') }}</p>
            </div>
        </div>

        <a href="{{ $organization->getProfileUrl() }}" class="btn btn-primary mt-12 mt-lg-0">{{ trans('update.organization_profile') }}</a>
    </div>

    {{-- List Table --}}
    @if(!empty($events) and $events->isNotEmpty())
        <div id="tableListContainer" class="" data-view-data-path="/panel/events/my-organization">
            <div class="js-page-events-lists row mt-20">
                @foreach($events as $eventItem)
                    <div class="col-12 col-md-6 col-lg-3 mt-20">
                        @include("design_1.panel.events.organization.event_card", ['event' => $eventItem])
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div id="pagination" class="js-ajax-pagination" data-container-id="tableListContainer"
                 data-container-items=".js-page-events-lists">
                {!! $pagination !!}
            </div>
        </div>
    @else
        @include('design_1.panel.includes.no-result',[
            'file_name' => 'organization_courses.svg',
            'title' => trans('update.my_organization_events_no_result_title'),
            'hint' =>  trans('update.my_organization_events_no_result_hint') ,
        ])
    @endif

@endsection
