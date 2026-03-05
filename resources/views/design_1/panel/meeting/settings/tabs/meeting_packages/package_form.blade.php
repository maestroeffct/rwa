<form action="/panel/meetings/packages/{{ !empty($meetingPackageEditItem) ? $meetingPackageEditItem->id.'/update' : 'store' }}" method="post" enctype="multipart/form-data">
    {{ csrf_field() }}

    <div class="bg-white p-16 rounded-16 border-gray-200">
        <h4 class="font-16 font-weight-bold mb-24">{{ !empty($meetingPackageEditItem) ? trans('update.edit_package') : trans('update.new_package') }}</h4>

        @include('design_1.panel.includes.locale.locale_select',[
            'itemRow' => !empty($meetingPackageEditItem) ? $meetingPackageEditItem : null,
            'withoutReloadLocale' => false,
            'extraClass' => '',
            'extraData' => null
        ])

        <div class="form-group">
            <label class="form-group-label">{{ trans('admin/main.title') }}</label>
            <input type="text" name="title"
                   class="form-control @error('title') is-invalid @enderror"
                   value="{{ (!empty($meetingPackageEditItem) and !empty($meetingPackageEditItem->translate($locale))) ? $meetingPackageEditItem->translate($locale)->title : old('title') }}"
                   placeholder="{{ trans('admin/main.choose_title') }}"/>
            @error('title')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-group-label">{{ trans('update.icon') }}</label>
            <div class="custom-file bg-white ">
                <input type="file" name="icon" class="custom-file-input" id="packageIcon" accept="image/*">
                <span class="custom-file-text text-dark">{{ (!empty($meetingPackageEditItem) and !empty($meetingPackageEditItem->icon)) ? getFileNameByPath($meetingPackageEditItem->icon) : trans('update.select_a_icon') }}</span>
                <label class="custom-file-label bg-white" for="packageIcon">
                    <x-iconsax-lin-export class="icons text-gray-400" width="24px" height="24px"/>
                </label>
            </div>

            @error('icon')
            <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-group-label">{{ trans('update.package_validity_duration') }}</label>
            <input type="number" name="duration" class="form-control @error('duration') is-invalid @enderror" value="{{ !empty($meetingPackageEditItem) ? $meetingPackageEditItem->duration : old('duration') }}"/>

            <div class="invalid-feedback">@error('duration') {{ $message }} @enderror</div>
        </div>


        <div class="form-group">
            <label class="form-group-label">{{ trans('update.duration_type') }}</label>
            <select name="duration_type" class="form-control select2 @error('duration_type') is-invalid @enderror" data-minimum-results-for-search="Infinity">
                @foreach(['day', 'week', 'month', 'year'] as $durationType)
                    <option value="{{ $durationType }}" {{ (!empty($meetingPackageEditItem) and $meetingPackageEditItem->duration_type == $durationType) ? 'selected' : '' }}>{{ trans("update.{$durationType}") }}</option>
                @endforeach
            </select>

            <div class="invalid-feedback">@error('duration_type') {{ $message }} @enderror</div>
        </div>

        <div class="form-group">
            <label class="form-group-label">{{ trans('update.number_of_sessions') }}</label>
            <input type="number" name="sessions" class="form-control @error('sessions') is-invalid @enderror" value="{{ !empty($meetingPackageEditItem) ? $meetingPackageEditItem->sessions : old('sessions') }}"/>
            <div class="invalid-feedback">@error('sessions') {{ $message }} @enderror</div>
        </div>

        <div class="form-group">
            <label class="form-group-label">{{ trans('update.session_duration') }} ({{ trans('public.minutes') }})</label>
            <input type="number" name="session_duration" class="form-control @error('session_duration') is-invalid @enderror" value="{{ !empty($meetingPackageEditItem) ? $meetingPackageEditItem->session_duration : old('session_duration') }}"/>
            <div class="invalid-feedback">@error('session_duration') {{ $message }} @enderror</div>
        </div>

        <div class="form-group">
            <label class="form-group-label">{{ trans('public.price') }}</label>
            <span class="has-translation bg-gray-100 text-gray-500">{{ $currency }}</span>
            <input type="text" name="price" class="form-control @error('price')  is-invalid @enderror" value="{{ (!empty($meetingPackageEditItem) and !empty($meetingPackageEditItem->price)) ? convertPriceToUserCurrency($meetingPackageEditItem->price) : old('price') }}" placeholder="{{ trans('update.empty_or_0_means_free') }}" oninput="validatePrice(this)"/>
            <div class="invalid-feedback d-block">@error('price') {{ $message }} @enderror</div>
        </div>

        <div class="form-group">
            <label class="form-group-label">{{ trans('public.discount') }}</label>
            <span class="has-translation bg-gray-100 text-gray-500">%</span>
            <input type="number" name="discount" class="form-control @error('discount')  is-invalid @enderror" value="{{ (!empty($meetingPackageEditItem) and !empty($meetingPackageEditItem->discount)) ? $meetingPackageEditItem->discount : old('discount') }}"/>
            <div class="invalid-feedback d-block">@error('discount') {{ $message }} @enderror</div>
        </div>

        <div class="form-group d-flex align-items-center mt-16">
            <div class="custom-switch mr-8">
                <input id="enableNewMeetingPackageSwitch" type="checkbox" name="enable" class="custom-control-input" {{ (!empty($meetingPackageEditItem) and $meetingPackageEditItem->enable) ? 'checked' : '' }}>
                <label class="custom-control-label cursor-pointer" for="enableNewMeetingPackageSwitch"></label>
            </div>

            <div class="">
                <label class="cursor-pointer" for="enableNewMeetingPackageSwitch">{{ trans('panel.active') }}</label>
            </div>
        </div>

        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between mt-20 pt-16 border-top-gray-100">
            <div class="d-flex align-items-center">
                <div class="d-flex-center size-48 rounded-12 bg-gray-200">
                    <x-iconsax-bol-info-circle class="icons text-gray-400" width="24px" height="24px"/>
                </div>
                <div class="ml-8">
                    <h5 class="font-14 text-dark">{{ trans('update.notice') }}</h5>
                    <p class="mt-4 font-12 text-gray-500">{{ trans('update.you_can_create_packages_for_different_timeframes') }}</p>
                </div>
            </div>

            <button type="submit" class="btn btn-lg btn-primary">{{ !empty($meetingPackageEditItem) ? trans('public.save') : trans('public.create') }}</button>
        </div>
    </div>
</form>
