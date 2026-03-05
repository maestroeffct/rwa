@foreach($fields as $field)
    @if($field->type == "input")
        <div class="form-group">
            <label class="form-group-label {{ $field->required ? 'is-required' : '' }}" for="code">{{ $field->title }}:</label>
            <input type="text" name="fields[{{ $field->id }}]" class="form-control @error($field->id) is-invalid @enderror" value="{{ (!empty($values) and !empty($values[$field->id])) ? $values[$field->id] : old('fields.'.$field->id) }}">
            @error($field->id)
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

    @elseif($field->type == "number")
        <div class="form-group">
            <label class="form-group-label {{ $field->required ? 'is-required' : '' }}" for="code">{{ $field->title }}:</label>
            <input type="number" name="fields[{{ $field->id }}]" class="form-control @error($field->id) is-invalid @enderror" value="{{ (!empty($values) and !empty($values[$field->id])) ? $values[$field->id] : old('fields.'.$field->id) }}">
            @error($field->id)
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

    @elseif($field->type == "textarea")
        <div class="form-group">
            <label class="form-group-label {{ $field->required ? 'is-required' : '' }}" for="code">{{ $field->title }}:</label>
            <textarea rows="4" name="fields[{{ $field->id }}]" class="form-control @error($field->id) is-invalid @enderror">{{ (!empty($values) and !empty($values[$field->id])) ? $values[$field->id] : old('fields.'.$field->id) }}</textarea>
            @error($field->id)
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

    @elseif($field->type == "upload")
        @php
            $uploadPath = null;

            if (!empty($values) and !empty($values[$field->id])) {
                $uploadPath = $values[$field->id];
            }
        @endphp

        <div class="form-group ">
            <label class="form-group-label {{ $field->required ? 'is-required' : '' }} ">{{ $field->title }}</label>

            <div class="custom-file bg-white">
                <input type="hidden" name="fields[{{ $field->id }}]" value="{{ (!empty($uploadPath)) ? $uploadPath : '' }}">
                <input type="file" name="fields[{{ $field->id }}]" class="custom-file-input bg-white @error($field->id) is-invalid @enderror" id="field_{{ $field->id }}">
                <span class="custom-file-text">{{ (!empty($uploadPath)) ? $uploadPath : '' }}</span>
                <label class="custom-file-label" for="field_{{ $field->id }}">{{ trans('browse') }}</label>
            </div>

            @error($field->id)
            <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror

            @if(!empty($uploadPath))
                <a href="{{ url($uploadPath) }}" target="_blank" class="text-primary mt-8">{{ trans('show') }}</a>
            @endif

        </div>
    @elseif($field->type == "date_picker")
        <div class="form-group">
            <label class="form-group-label {{ $field->required ? 'is-required' : '' }}">{{ $field->title }}</label>
            <span class="has-translation"><x-iconsax-lin-calendar class="icons text-gray-400" width="24px" height="24px"/></span>
            <input type="text" name="fields[{{ $field->id }}]" class="form-control text-center datetimepicker js-default-init-date-picker @error($field->id) is-invalid @enderror" data-drops="up" data-show-drops="true"
                   aria-describedby="dateRangeLabel{{ $field->id }}" autocomplete="off" value="{{ (!empty($values) and !empty($values[$field->id])) ? $values[$field->id] : old('fields.'.$field->id) }}"/>

            @error($field->id)
            <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

    @elseif($field->type == "toggle")
        <div class="form-group">
            <div class="d-flex align-items-center">
                <div class="custom-switch mr-8">
                    <input id="toggle{{ $field->id }}" type="checkbox" name="fields[{{ $field->id }}]" class="custom-control-input" {{ (!empty($values) and !empty($values[$field->id]) and $values[$field->id] == "on") ? 'checked' : ((!empty(old('fields.'.$field->id)) and old('fields.'.$field->id) == "on") ? 'checked' : '') }}>
                    <label class="custom-control-label cursor-pointer" for="toggle{{ $field->id }}"></label>
                </div>

                <div class="">
                    <label class="cursor-pointer" for="toggle{{ $field->id }}">{{ $field->title }}</label>
                </div>
            </div>

            @error($field->id)
            <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

    @elseif($field->type == "dropdown")
        @if(!empty($field->options) and count($field->options))
            <div class="form-group ">
                <label class="form-group-label {{ $field->required ? 'is-required' : '' }}">{{ $field->title }}:</label>
                <select name="fields[{{ $field->id }}]" class="form-control select2 @error($field->id) is-invalid @enderror">
                    <option value="" class="">{{ trans('update.select_a_option') }}</option>
                    @foreach($field->options as $option)
                        <option value="{{ $option->id }}" {{ (!empty($values) and !empty($values[$field->id]) and $values[$field->id] == $option->id) ? 'selected' : ((!empty(old('fields.'.$field->id)) and old('fields.'.$field->id) == $option->id) ? 'selected' : '') }}>{{ $option->title }}</option>
                    @endforeach
                </select>
                @error($field->id)
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        @endif

    @elseif($field->type == "checkbox")
        @if(!empty($field->options) and count($field->options))
            @php
                $checkboxValues = [];

                if (!empty($values) and !empty($values[$field->id])) {
                    $checkboxValues = json_decode($values[$field->id], true);
                } else if (!empty(old('fields.'.$field->id)) and is_array(old('fields.'.$field->id))) {
                    $checkboxValues = old('fields.'.$field->id);
                }
            @endphp

            <div class="form-group">
                <label class="position-relative font-12 text-gray-500 mb-8 {{ $field->required ? 'is-required' : '' }}">{{ $field->title }}:</label>

                @foreach($field->options as $option)
                    <div class="custom-control custom-checkbox mt-12">
                        <input type="checkbox" name="fields[{{ $field->id }}][]" value="{{ $option->id }}" class="custom-control-input" id="checkbox{{ $option->id }}" {{ in_array($option->id, $checkboxValues) ? 'checked' : "" }}>
                        <label class="custom-control__label cursor-pointer" for="checkbox{{ $option->id }}">{{ $option->title }}</label>
                    </div>
                @endforeach

                @error($field->id)
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        @endif

    @elseif($field->type == "radio")
        @if(!empty($field->options) and count($field->options))
            <div class="form-group">
                <label class="position-relative font-12 text-gray-500 mb-8 {{ $field->required ? 'is-required' : '' }}">{{ $field->title }}:</label>

                @foreach($field->options as $option)
                    <div class="custom-control custom-radio mt-12">
                        <input type="radio" name="fields[{{ $field->id }}]" value="{{ $option->id }}" class="custom-control-input" id="radio{{ $option->id }}" {{ (!empty($values) and !empty($values[$field->id]) and $values[$field->id] == $option->id) ? 'checked' : ((!empty(old('fields.'.$field->id)) and old('fields.'.$field->id) == $option->id) ? 'checked' : '') }}>
                        <label class="custom-control__label cursor-pointer" for="radio{{ $option->id }}">{{ $option->title }}</label>
                    </div>
                @endforeach

                @error($field->id)
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        @endif

    @endif
@endforeach
