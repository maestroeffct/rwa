@extends('design_1.panel.layouts.panel')

@push("styles_top")

@endpush

@section('content')

    @if(!empty($favorites) and !$favorites->isEmpty())
        <div id="tableListContainer" class="table-responsive-lg" data-view-data-path="/panel/courses/favorites">
            <div class="js-table-body-lists row">
                @foreach($favorites as $favoriteRow)
                    <div class="col-12 col-md-6 col-lg-3 col-xl-2 mt-20">
                        @include("design_1.panel.webinars.favorites.grid_card", ['favorite' => $favoriteRow])
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div id="pagination" class="js-ajax-pagination" data-container-id="tableListContainer" data-container-items=".js-table-body-lists">
                {!! $pagination !!}
            </div>
        </div>
    @else
        @include('design_1.panel.includes.no-result',[
            'file_name' => 'favorites.svg',
            'title' => trans('panel.no_result_favorites'),
            'hint' =>  trans('panel.no_result_favorites_hint'),
            'extraClass' => 'mt-0',
        ])
    @endif

@endsection

@push('scripts_bottom')

    <script src="{{ getDesign1ScriptPath("get_view_data") }}"></script>
@endpush
