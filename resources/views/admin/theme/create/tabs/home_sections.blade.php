<div class="row">
    <div class="col-12 col-md-6">

        <div class="form-group mb-3">
            <label class="input-label">{{ trans('update.home_landing') }}</label>
            <select name="home_landing_id" class="form-control @error('home_landing_id') is-invalid @enderror">
                <option value="">{{ trans('update.select_a_landing') }}</option>

                @foreach($landings as $landing)
                    <option value="{{ $landing->id }}" {{ (!empty($themeItem) and $themeItem->home_landing_id == $landing->id) ? 'selected' : '' }}>{{ $landing->title }}</option>
                @endforeach
            </select>

            @error('home_landing_id')
            <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="js-home-landing-sections mt-32 {{ (!empty($themeItem) and !empty($themeItem->home_landing_id)) ? '' : 'd-none' }}">
            @if(!empty($themeItem->homeLanding))
                <div class="">
                    <h4 class="font-14 text-dark mb-0">{{ trans('update.home_landing_sections') }}</h4>
                    <div class="font-12 text-gray-500 mb-0">{{ trans('update.home_landing_sections_sort_hint') }}</div>
                </div>

                <ul class="js-assigned-components-lists draggable-content-lists assigned-components-draggable-lists mt-16 mb-24" data-path="{{ getLandingBuilderUrl("/{$themeItem->homeLanding->id}/sort-components") }}" data-drag-class="assigned-components-draggable-lists">
                    @foreach($themeItem->homeLanding->components as $component)
                        @include('admin.theme.create.tabs.includes.landing_component_card', ['landingComponent' => $component, 'landingItemId' => $themeItem->homeLanding->id])
                    @endforeach
                </ul>
            @endif
        </div>

    </div>
</div>
