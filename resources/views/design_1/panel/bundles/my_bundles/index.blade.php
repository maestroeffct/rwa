@extends('design_1.panel.layouts.panel')

@push("styles_top")

@endpush

@section('content')

    {{-- Top Stats --}}
    @include('design_1.panel.bundles.my_bundles.top_stats')

    {{-- Lists --}}
    @if(!empty($bundles) and !$bundles->isEmpty())
        <div id="tableListContainer" class="" data-view-data-path="/panel/bundles">
            <div class="js-page-bundles-lists row">
                @foreach($bundles as $bundleItem)
                    <div class="col-12 col-md-6 col-lg-4 mt-20">
                        @include("design_1.panel.bundles.my_bundles.grid_card", ['bundle' => $bundleItem])
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div id="pagination" class="js-ajax-pagination" data-container-id="tableListContainer"
                 data-container-items=".js-page-bundles-lists">
                {!! $pagination !!}
            </div>
        </div>
    @else
        @include('design_1.panel.includes.no-result',[
            'file_name' => 'bundles.svg',
            'title' => trans('update.you_not_have_any_bundle'),
            'hint' =>  trans('update.no_result_bundle_hint') ,
            'btn' => ['url' => '/panel/bundles/new','text' => trans('update.create_a_bundle') ]
        ])
    @endif

@endsection

@push('scripts_bottom')
    <script src="{{ getDesign1ScriptPath("get_view_data") }}"></script>
@endpush
