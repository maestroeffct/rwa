<div class="row">
    <div class="col-12 col-md-6">
        @php
            $pages = ['admin_login','admin_dashboard',
                'search', 'tags', 'categories','become_instructor','certificate_validation', 'certificate_validation_overlay_image','instructors_lists', 'instructors_header_overlay_image',
                'organizations_lists','organizations_header_overlay_image','user_cover','products_lists', 'products_lists_overlay_image', 'upcoming_courses_lists', 'upcoming_courses_lists_overlay_image',
                'categories_courses_lists_featured_courses_background', 'categories_courses_lists_featured_courses_overlay_image', 'bundles_lists', 'bundles_lists_overlay_image', 'blog_lists', 'blog_lists_overlay_image',
                'form_default_cover', 'form_default_overlay_image', 'form_default_header_icon', 'meeting_booking_step_1_image', 'meeting_booking_step_2_image', 'classes_lists', 'classes_lists_overlay_image', 'reward_courses', 'reward_courses_overlay_image',
                'event_ticket_validation', 'event_ticket_validation_overlay_image', 'events_lists', 'events_lists_overlay_image', 'meeting_packages_lists', 'meeting_packages_lists_overlay_image'
            ];
        @endphp

        @foreach($pages as $page)
            <div class="form-group">
                <label class="input-label">{{ trans('admin/main.'.$page.'_background') }}</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <button type="button" class="input-group-text admin-file-manager" data-input="{{ $page }}" data-preview="holder">
                            <i class="fa fa-chevron-up"></i>
                        </button>
                    </div>
                    <input type="text" name="contents[images][{{ $page }}]" id="{{ $page }}" value="{{ (!empty($themeContents) and !empty($themeContents['images']) and !empty($themeContents['images'][$page])) ? $themeContents['images'][$page] : '' }}" class="form-control"/>
                    <div class="input-group-append">
                        <button type="button" class="input-group-text admin-file-view" data-input="{{ $page }}">
                            <i class="fa fa-eye"></i>
                        </button>
                    </div>
                </div>
            </div>
        @endforeach

    </div>
</div>
