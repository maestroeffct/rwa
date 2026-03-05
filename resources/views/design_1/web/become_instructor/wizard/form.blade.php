<div class="">
    <h3 class="font-24 font-weight-bold">{{ trans('update.information') }} 🎓</h3>
    <div class="mt-8 text-gray-500">{{ trans('update.become_instructor_organization_page_information_form_hint') }}</div>

    {{-- Role --}}
    <div class="form-group mt-20">
        <label class="font-12 font-weight-bold">{{ trans('financial.account_type') }}</label>

        <div class="d-flex align-items-center gap-5 p-4 border-gray-300 rounded-12 mt-8">
            <div class="custom-input-button custom-input-button-none-border-and-active-bg  position-relative flex-1">
                <input type="radio" class="" name="role" id="role_teacher" value="teacher" checked/>
                <label for="role_teacher" class="position-relative d-flex-center flex-column p-12 rounded-8 text-center text-gray-500">
                    {{ trans("update.instructor") }}
                </label>
            </div>

            <div class="custom-input-button custom-input-button-none-border-and-active-bg  position-relative flex-1">
                <input type="radio" class="" name="role" id="role_organization" value="organization">
                <label for="role_organization" class="position-relative d-flex-center flex-column p-12 rounded-8 text-center text-gray-500">
                    {{ trans("update.organization") }}
                </label>
            </div>
        </div>

        @error('role')
        <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    {{-- Occupations --}}
    <div class="form-group ">
        <label class="form-group-label">{{ trans('public.occupations') }}</label>

        <select name="occupations[]" class="form-control select2" multiple data-placeholder="{{ trans('update.select_your_occupations') }}">
            <option value="">{{ trans('update.select_your_occupations') }}</option>

            @foreach($categories as $category)
                @if(!empty($category->subCategories) and count($category->subCategories))
                    @foreach($category->subCategories as $subCategory)
                        <option value="{{ $subCategory->id }}" {{ (!empty($occupations) and in_array($subCategory->id, $occupations)) ? 'selected' : '' }}>{{ $subCategory->title }}</option>
                    @endforeach
                @else
                    <option value="{{ $category->id }}" {{ (!empty($occupations) and in_array($category->id, $occupations)) ? 'selected' : '' }}>{{ $category->title }}</option>
                @endif
            @endforeach
        </select>

        @error('occupations')
        <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    {{-- Extra Information --}}
    <h5 class="font-12 mt-24">{{ trans('public.extra_information') }}</h5>

    <div class="form-group  mt-24">
        <label class="form-group-label">{{ trans('update.select_account_type') }}</label>

        <select name="bank_id" class="js-user-bank-input form-control select2 @error('bank_id')  is-invalid @enderror" data-minimum-results-for-search="Infinity">
            <option selected disabled>{{ trans('financial.select_account_type') }}</option>

            @foreach($userBanks as $userBank)
                <option value="{{ $userBank->id }}" @if(!empty($user) and !empty($user->selectedBank) and $user->selectedBank->user_bank_id == $userBank->id) selected="selected" @endif data-specifications="{{ json_encode($userBank->specifications->pluck('name','id')->toArray()) }}">{{ $userBank->title }}</option>
            @endforeach
        </select>
        @error('bank_id')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>

    <div class="js-bank-specifications-card">
        @if(!empty($user) and !empty($user->selectedBank) and !empty($user->selectedBank->bank))
            @foreach($user->selectedBank->bank->specifications as $specification)
                @php
                    $selectedBankSpecification = $user->selectedBank->specifications->where('user_selected_bank_id', $user->selectedBank->id)->where('user_bank_specification_id', $specification->id)->first();
                @endphp
                <div class="form-group">
                    <label class="form-group-label">{{ $specification->name }}</label>
                    <input type="text" name="bank_specifications[{{ $specification->id }}]" value="{{ (!empty($selectedBankSpecification)) ? $selectedBankSpecification->value : '' }}" class="form-control"/>
                </div>
            @endforeach
        @endif
    </div>


    <div class="form-group custom-input-file flex-1 mt-24">
        <label class="form-group-label">{{ trans('update.certificate_and_documents') }}</label>

        <div class="custom-file bg-white js-ajax-certificate">
            <input type="file" name="certificate" class="custom-file-input js-ajax-upload-file-input" id="certificatesInput" data-upload-name="certificate">
            <span class="custom-file-text text-gray-500"></span>
            <label class="custom-file-label bg-transparent" for="certificatesInput">
                <x-iconsax-lin-export class="icons text-gray-400" width="24px" height="24px"/>
            </label>
        </div>

        @error('certificate')
        <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>


    <div class="form-group custom-input-file flex-1 mt-24">
        <label class="form-group-label">{{ trans('update.identity_scan') }}</label>

        <div class="custom-file bg-white js-ajax-identity_scan">
            <input type="file" name="identity_scan" class="custom-file-input js-ajax-upload-file-input" id="identity_scansInput" data-upload-name="identity_scan">
            <span class="custom-file-text text-gray-500"></span>
            <label class="custom-file-label bg-transparent" for="identity_scansInput">
                <x-iconsax-lin-export class="icons text-gray-400" width="24px" height="24px"/>
            </label>
        </div>

        @error('identity_scan')
        <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label class="form-group-label">{{ trans('public.extra_information') }}</label>
        <textarea name="description" rows="6" class="form-control">{{ !empty($lastRequest) ? $lastRequest->description : old('description') }}</textarea>

        @error('description')
        <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="js-form-fields-card">
        @if(!empty($formFields))
            {!! $formFields !!}
        @endif
    </div>

</div>
