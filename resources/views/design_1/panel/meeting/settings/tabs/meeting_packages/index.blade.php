<div class="row">
    {{-- New Package --}}
    <div class="col-12 col-lg-6">
        @include('design_1.panel.meeting.settings.tabs.meeting_packages.package_form')
    </div>

    {{-- Packages Lists --}}
    <div class="col-12 col-lg-6 mt-24 mt-lg-0">
        <div class="bg-white py-16 rounded-16 border-gray-200 h-100">
            <div class="px-16">
                <h4 class="font-16 font-weight-bold">{{ trans('update.meeting_packages') }}</h4>
                <p class="mt-2 text-gray-500">{{ trans('update.view_and_manage_your_meeting_packages') }}</p>
            </div>

            {{-- List Table --}}
            @if(!empty($meetingPackages) and $meetingPackages->isNotEmpty())
                <div id="MeetingPackageTableListContainer" class="mt-20" data-view-data-path="/panel/meetings/packages">
                    <table class="table panel-table">
                        <thead>
                        <tr>
                            <th class="text-left">{{ trans('public.title') }}</th>
                            <th class="text-center">{{ trans('public.duration') }}</th>
                            <th class="text-center">{{ trans('public.price') }}</th>
                            <th class="text-center">{{ trans('panel.sales') }}</th>
                            <th class="text-center">{{ trans('public.status') }}</th>
                            <th class="text-right">{{ trans('update.actions') }}</th>
                        </tr>
                        </thead>
                        <tbody class="js-meeting-packages-lists">
                        @foreach($meetingPackages as $meetingPackageRow)
                            @include('design_1.panel.meeting.settings.tabs.meeting_packages.table_items', ['meetingPackage' => $meetingPackageRow])
                        @endforeach
                        </tbody>
                    </table>

                    {{-- Pagination --}}
                    <div id="pagination" class="js-ajax-pagination" data-container-id="MeetingPackageTableListContainer"
                         data-container-items=".js-meeting-packages-lists">
                        {!! $meetingPackagesPagination !!}
                    </div>
                </div>
            @else
                @include('design_1.panel.includes.no-result',[
                    'file_name' => 'meeting_packages.svg',
                    'title' => trans('update.my_meeting_package_no_result_title'),
                    'hint' =>  trans('update.my_meeting_package_no_result_hint') ,
                ])
            @endif
        </div>
    </div>
</div>

@push('scripts_bottom')
    <script src="{{ getDesign1ScriptPath("get_view_data") }}"></script>
@endpush
