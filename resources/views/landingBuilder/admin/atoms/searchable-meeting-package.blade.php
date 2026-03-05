<div class="form-group select2-bg-white {{ $className ?? '' }}">
    <label class="form-group-label bg-white">{{ $label }}</label>

    <select name="{{ $name }}" class="form-control {{ !empty($searchSelect2Class) ? $searchSelect2Class : 'js-searchable-select' }} bg-white {{ $selectClassName ?? '' }}" data-allow-clear="false" data-placeholder="{{ $placeholder ?? '' }}"
            data-change-action="{{ !empty($changeActionEls) ? $changeActionEls : '' }}" data-change-parent="{{ !empty($changeActionParent) ? $changeActionParent : '' }}"
            data-api-path="{{ getAdminPanelUrl('/meeting-packages/search') }}"
            data-item-column-name="title"
            data-option=""
    >
        @if(!empty($value))
            @php
                $searchSelectedPackage = \App\Models\MeetingPackage::query()->find($value);
            @endphp

            @if(!empty($searchSelectedPackage))
                <option value="{{ $searchSelectedPackage->id }}" selected>{{ $searchSelectedPackage->title }}</option>
            @endif
        @endif
    </select>

    <div class="invalid-feedback"></div>

    @if($hint)
        <div class="mt-8 font-12 text-gray-500">{{ $hint }}</div>
    @endif
</div>
