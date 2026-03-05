@if(!empty($landingComponent) and $landingComponent->enable)
    @php
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }

        $multiTabCoursesComponentsDataMixins = (new \App\Mixins\LandingBuilder\MultiTabCoursesComponentMixins());
    @endphp

    @push('styles_top')
        <link rel="stylesheet" href="{{ getLandingComponentStylePath("multi_tab_courses") }}">
    @endpush

    <div class="multi-tab-courses-section container custom-tabs">
        <div class="d-flex flex-column flex-lg-row gap-20 align-items-lg-center justify-content-lg-between">
            <div class="">
                @if(!empty($contents['main_content']))
                    @if(!empty($contents['main_content']['title']))
                        <h2 class="font-24 text-dark">{{ $contents['main_content']['title'] }}</h2>
                    @endif

                    @if(!empty($contents['main_content']['description']))
                        <p class="text-gray-500 mt-2">{{ $contents['main_content']['description'] }}</p>
                    @endif
                @endif
            </div>

            @php
                $allCourseTabsContents = (!empty($contents['course_tabs_content']) and count($contents['course_tabs_content'])) ? $contents['course_tabs_content'] : [];

                if (!empty($allCourseTabsContents['record'])) {
                    unset($allCourseTabsContents['record']);
                }
            @endphp

            {{-- Tab --}}
            @if(!empty($allCourseTabsContents) and count($allCourseTabsContents))
                <div class="position-relative d-flex align-items-center gap-24">
                    @foreach($allCourseTabsContents as $courseTabContentKey => $courseTabContent)
                        @if(!empty($courseTabContent['title']))
                            <div class="navbar-item d-inline-flex-center font-16 text-dark cursor-pointer {{ $loop->first ? 'active' : '' }}" data-tab-toggle data-tab-href="#course_tab_{{ $courseTabContentKey }}">
                                <span class="">{{ $courseTabContent['title'] }}</span>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Tabs Content --}}
        @if(!empty($allCourseTabsContents) and count($allCourseTabsContents))
            <div class="custom-tabs-body">
                @foreach($allCourseTabsContents as $courseTabContentKey => $courseTabContent)
                    @if(!empty($courseTabContent['title']) and !empty($courseTabContent['source']))
                        @php
                            $multiTabCourses = $multiTabCoursesComponentsDataMixins->getCoursesByTabSource($courseTabContent, $contents['maximum_course_cards']);
                        @endphp

                        <div class="custom-tabs-content mt-8 {{ $loop->first ? 'active' : '' }}" id="course_tab_{{ $courseTabContentKey }}">
                            @if(!empty($multiTabCourses) and count($multiTabCourses))
                                <div class="row">
                                    @include('design_1.web.courses.components.cards.grids.index',['courses' => $multiTabCourses, 'gridCardClassName' => "col-12 col-md-6 col-lg-3 mt-16 mb-8"])
                                </div>
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>
        @endif

    </div>
@endif
