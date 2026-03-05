<div class="form-group select2-bg-white {{ $className ?? '' }}">
    <label class="form-group-label bg-white">{{ $label }}</label>

    <select name="{{ $name }}" class="form-control js-searchable-select bg-white {{ $selectClassName ?? '' }}" data-allow-clear="false" data-placeholder="{{ $placeholder ?? '' }}"
            data-change-action="{{ !empty($changeActionEls) ? $changeActionEls : '' }}" data-change-parent="{{ !empty($changeActionParent) ? $changeActionParent : '' }}"
            data-api-path="{{ getAdminPanelUrl('/users/search') }}"
            data-item-column-name="name"
            data-option="{{ !empty($searchOption) ? $searchOption : '' }}"
            data-webinar-id=""
    >
        @if(!empty($value))
            @php
                $searchSelectedUser = \App\User::query()->find($value);
            @endphp

            @if(!empty($searchSelectedUser))
                <option value="{{ $searchSelectedUser->id }}" selected>{{ $searchSelectedUser->full_name }}</option>
            @endif
        @endif
    </select>

    <div class="invalid-feedback"></div>

    @if($hint)
        <div class="mt-8 font-12 text-gray-500">{{ $hint }}</div>
    @endif
</div>
