<div class="row">
    <div class="col-12 col-md-6">

        @php
            $items = [
                'course' => ['grid_card_1', 'grid_card_2'],
                'product' => ['grid_card_1'],
                'bundle' => ['grid_card_1'],
                'upcoming_course' => ['grid_card_1'],
                'blog_post' => ['grid_card_1'],
                'instructor' => ['grid_card_1'],
                'organization' => ['grid_card_1'],
                'event' => ['grid_card_1'],
                'meeting_package' => ['grid_card_1'],
            ];

        @endphp

        @foreach($items as $itemName => $itemCards)
            <div class="form-group mb-3">
                <label class="input-label">{{ trans("update.{$itemName}") }}</label>
                <select name="contents[card_styles][{{ $itemName }}]" class="form-control">
                    <option value="">{{ trans('update.select_a_card_style') }}</option>

                    @foreach($itemCards as $cardName)
                        <option value="{{ $cardName }}" {{ (!empty($themeContents) and !empty($themeContents['card_styles']) and !empty($themeContents['card_styles'][$itemName]) and $themeContents['card_styles'][$itemName] == $cardName) ? 'selected' : '' }}>{{ trans("update.{$cardName}") }}</option>
                    @endforeach
                </select>
            </div>
        @endforeach
    </div>
</div>
