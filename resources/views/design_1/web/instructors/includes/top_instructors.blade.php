@if(!empty($topInstructors) and count($topInstructors))
    <div class="instructors-lists__top-users rounded-32 mt-56 p-24">
        <div class="d-flex-center flex-column text-center pt-24">
            <h2 class="font-32 text-white">{{ trans('update.top_instructors') }}</h2>
            <div class="text-white opacity-75">{{ trans('update.enjoy_learning_from_top_instructors_and_get_valuable_information') }}</div>
        </div>


        <div class="d-grid grid-columns-auto grid-lg-columns-3 gap-24 mt-24">
            @include('design_1.web.instructors.components.cards.grids.index',['instructors' => $topInstructors, 'gridCardClassName' => ""])
        </div>
    </div>
@endif
