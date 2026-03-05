<div class="tab-pane mt-3 fade @if(!empty($social)) show active @endif" id="socials" role="tabpanel" aria-labelledby="socials-tab">
    <div class="row">
        <div class="col-12 col-md-8 col-lg-6">
            <form action="{{ getAdminPanelUrl() }}/settings/socials/store" method="post">
                {{ csrf_field() }}

                <input type="hidden" name="page" value="general">
                <input type="hidden" name="social" value="{{ !empty($socialKey) ? $socialKey : 'newSocial' }}">

                <div class="form-group">
                    <label>{{ trans('admin/main.title') }}</label>
                    <input type="text" name="value[title]" value="{{ (!empty($social)) ? $social->title : old('value.title') }}" class="form-control  @error('value.title') is-invalid @enderror"/>
                    @error('value.title')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="input-label">{{ trans('admin/main.icon') }}</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <button type="button" class="input-group-text admin-file-manager" data-input="image" data-preview="holder">
                                <i class="fa fa-chevron-up"></i>
                            </button>
                        </div>
                        <input type="text" name="value[image]" id="image" value="{{ (!empty($social)) ? $social->image : old('value.image') }}" class="form-control @error('value.image')  is-invalid @enderror"/>
                        @error('value.image')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label>{{ trans('public.link') }}</label>
                    <input type="text" name="value[link]" value="{{ (!empty($social)) ? $social->link : old('value.link') }}" class="form-control  @error('value.link') is-invalid @enderror"/>
                    @error('value.link')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>{{ trans('admin/main.order') }}</label>
                    <input type="number" name="value[order]" value="{{ (!empty($social)) ? $social->order : old('value.order') }}" class="form-control  @error('value.order') is-invalid @enderror"/>
                    @error('value.order')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-success mt-3">{{ trans('admin/main.submit') }}</button>
            </form>
        </div>
    </div>

    <div class="table-responsive mt-5">
        <table class="table custom-table font-14">
            <tr>
                <th>{{ trans('admin/main.icon') }}</th>
                <th>{{ trans('public.title') }}</th>
                <th>{{ trans('public.link') }}</th>
                <th>{{ trans('public.controls') }}</th>
            </tr>

            @if(!empty($itemValue))
                @php
                    if (!is_array($itemValue)) {
                        $itemValue = json_decode($itemValue, true);
                    }
                @endphp

                @if(!empty($itemValue) and is_array($itemValue))
                    @foreach($itemValue as $key => $val)
                        <tr>
                            <td>
                                <img src="{{ $val['image'] }}" width="24"/>
                            </td>
                            <td>{{ $val['title'] }}</td>
                            <td><a href="{{ $val['link'] }}" target="_blank">{{ trans('public.view') }}</a></td>
                            <td width="80px">
                                <div class="btn-group dropdown table-actions position-relative">
                                    <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                        <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
                                    </button>

                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a href="{{ getAdminPanelUrl() }}/settings/socials/{{ $key }}/edit" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                            <x-iconsax-lin-edit-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                            <span class="text-gray-500 font-14">{{ trans('admin/main.edit') }}</span>
                                        </a>

                                        @include('admin.includes.delete_button',[
                                            'url' => getAdminPanelUrl().'/settings/socials/'. $key .'/delete',
                                            'btnClass' => 'dropdown-item text-danger mb-0 py-3 px-0 font-14',
                                            'btnText' => trans("admin/main.delete"),
                                            'btnIcon' => 'trash',
                                            'iconType' => 'lin',
                                            'iconClass' => 'text-danger mr-2',
                                        ])
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endif
            @endif
        </table>
    </div>
</div>
