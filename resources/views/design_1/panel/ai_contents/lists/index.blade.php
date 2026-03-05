@extends('design_1.panel.layouts.panel')

@section('content')

    @if(!empty($contents) and count($contents))
        <div class="bg-white pt-16 rounded-16">

            <div class="d-flex align-items-center justify-content-between pb-16 px-16 border-bottom-gray-100">
                <div class="">
                    <h3 class="font-16">{{ trans('update.generated_content') }}</h3>
                    <p class="font-14 text-gray-500 mt-2">{{ trans('update.view_my_generated_content') }}</p>
                </div>
            </div>

            {{-- Filters --}}

            {{-- List Table --}}
            <div id="tableListContainer" class="table-responsive-lg" data-view-data-path="/panel/ai-contents">
                <table class="table panel-table">
                    <thead>
                    <tr>
                        <th class="text-left">{{ trans('update.service_type') }}</th>
                        <th class="text-center">{{ trans('update.service') }}</th>
                        <th class="text-center">{{ trans('update.keyword') }}</th>
                        <th class="text-center">{{ trans('auth.language') }}</th>
                        <th class="text-center">{{ trans('update.generated_date') }}</th>
                        <th class="text-right">{{ trans('update.controls') }}</th>
                    </tr>
                    </thead>
                    <tbody class="js-table-body-lists">
                    @foreach($contents as $contentRow)
                        @include('design_1.panel.ai_contents.lists.table_items', ['content' => $contentRow])
                    @endforeach
                    </tbody>
                </table>

                {{-- Pagination --}}
                <div id="pagination" class="js-ajax-pagination" data-container-id="tableListContainer" data-container-items=".js-table-body-lists">
                    {!! $pagination !!}
                </div>
            </div>
        </div>
    @else
        @include('design_1.panel.includes.no-result',[
            'file_name' => 'ai_content.svg',
            'title' => trans('update.ai_contents_no_result'),
            'hint' =>  nl2br(trans('update.ai_contents_no_result_hint')) ,
        ])
    @endif
@endsection

@push('scripts_bottom')
    <script>
        var generatedContentLang = '{{ trans('update.generated_content') }}';
        var generatedImageLang = '{{ trans('update.generated_image') }}';
        var promptLang = '{{ trans('update.prompt') }}:';
        var copyLang = '{{ trans('public.copy') }}';
        var doneLang = '{{ trans('public.done') }}';
        var copyIcon = `<x-iconsax-lin-document-copy class="icons text-gray-500" width="16px" height="16px"/>`;
        var downloadIcon = `<x-iconsax-lin-import class="icons text-gray-500" width="20px" height="20px"/>`;
    </script>
    <script src="{{ getDesign1ScriptPath("get_view_data") }}"></script>

    <script src="/assets/design_1/js/panel/ai_content_generator.min.js"></script>
@endpush
