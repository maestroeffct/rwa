<div class="bg-white p-16 rounded-24 mt-24">
    <h4 class="font-14 text-dark">{{ trans('update.top_instructors') }}</h4>

    @if(!empty($topInstructors['total']))
        <div class="d-grid grid-columns-2 gap-16 mt-16">
            {{-- Total Instructors --}}
            <div class="d-flex align-items-start justify-content-between bg-gray-100 rounded-16 p-16">
                <div class="">
                    <span class="d-block font-16 font-weight-bold text-dark">{{ $topInstructors['total'] }}</span>
                    <span class="d-block font-12 text-gray-500 mt-8">{{ trans('update.total_instructors') }}</span>
                </div>

                <x-iconsax-bul-briefcase class="icons text-primary" width="24px" height="24px"/>
            </div>

            {{-- Active Instructors --}}
            <div class="d-flex align-items-start justify-content-between bg-gray-100 rounded-16 p-16">
                <div class="">
                    <span class="d-block font-16 font-weight-bold text-dark">{{ $topInstructors['totalActive'] }}</span>
                    <span class="d-block font-12 text-gray-500 mt-8">{{ trans('update.active_instructors') }}</span>
                </div>

                <x-iconsax-bul-brifecase-tick class="icons text-success" width="24px" height="24px"/>
            </div>
        </div>


        @foreach($topInstructors['instructors'] as $topInstructor)
            <div class="d-flex align-items-center justify-content-between mt-16">
                <div class="d-flex align-items-center">
                    <div class="size-48 rounded-circle bg-gray-100">
                        <img src="{{ $topInstructor->getAvatar(48) }}" alt="" class="img-cover rounded-circle">
                    </div>
                    <div class="ml-8">
                        <h6 class="font-14 text-dark">{{ $topInstructor->full_name }}</h6>
                        <div class="mt-4 font-12 text-gray-500">{{ $topInstructor->email }}</div>
                    </div>
                </div>

                <div class="font-weight-bold text-dark">{{ handlePrice($topInstructor->getSaleAmounts()) }}</div>
            </div>
        @endforeach
    @else
        {{-- If Empty --}}
        <div class="d-flex-center flex-column text-center mt-20 border-dashed border-gray-200 bg-gray-100 p-32 rounded-16">
            <div class="d-flex-center size-48 rounded-12 bg-primary-40">
                <x-iconsax-bul-briefcase class="icons text-primary" width="24px" height="24px"/>
            </div>
            <h5 class="font-14 text-dark mt-12">{{ trans('update.no_instructor!') }}</h5>
            <div class="mt-4 font-12 text-gray-500">{{ trans('update.organization_dashboard_no_instructor_hint') }}</div>
        </div>

        <div class="d-flex align-items-center justify-content-between mt-16">
            <div class="">
                <h6 class="font-14 text-dark">{{ trans('update.new_instructor') }}</h6>
                <p class="mt-4 font-12 text-gray-500">{{ trans('update.create_instructors_for_your_organization') }}</p>
            </div>

            <a href="/panel/manage/instructors/new" target="_blank" class="d-flex-center size-40 bg-white border-gray-200 rounded-circle bg-hover-gray-100">
                <x-iconsax-lin-arrow-right class="icons text-gray-500" width="16px" height="16px"/>
            </a>
        </div>
    @endif
</div>
