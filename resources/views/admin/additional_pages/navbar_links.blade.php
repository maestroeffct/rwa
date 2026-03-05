@extends('admin.layouts.app')


@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('admin/main.settings_navbar_links') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a></div>
                <div class="breadcrumb-item">{{ trans('admin/main.settings_navbar_links') }}</div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <div class="row">
                                <div class="col-12 col-md-8 col-lg-6">
                                    <form action="{{ getAdminPanelUrl() }}/additional_page/navbar_links/store" method="post">
                                        {{ csrf_field() }}

                                        <input type="hidden" name="navbar_link" value="{{ !empty($navbarLinkKey) ? $navbarLinkKey : 'newLink' }}">

                                        @if(!empty(getGeneralSettings('content_translate')))
                                            <div class="form-group">
                                                <label class="input-label">{{ trans('auth.language') }}</label>
                                                <select name="locale" class="form-control js-edit-content-locale">
                                                    @foreach($userLanguages as $lang => $language)
                                                        <option value="{{ $lang }}" @if(mb_strtolower(request()->get('locale', $selectedLocal)) == mb_strtolower($lang)) selected @endif>{{ $language }}</option>
                                                    @endforeach
                                                </select>
                                                @error('locale')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        @else
                                            <input type="hidden" name="locale" value="{{ getDefaultLocale() }}">
                                        @endif


                                        <div class="form-group">
                                            <label>{{ trans('admin/main.title') }}</label>
                                            <input type="text" name="value[title]" value="{{ (!empty($navbar_link)) ? $navbar_link->title : old('value.title') }}" class="form-control  @error('value.title') is-invalid @enderror"/>
                                            @error('value.title')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>{{ trans('public.link') }}</label>
                                            <input type="text" name="value[link]" value="{{ (!empty($navbar_link)) ? $navbar_link->link : old('value.link') }}" class="form-control  @error('value.link') is-invalid @enderror"/>
                                            @error('value.link')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>{{ trans('admin/main.order') }}</label>
                                            <input type="number" name="value[order]" value="{{ (!empty($navbar_link)) ? $navbar_link->order : old('value.order') }}" class="form-control  @error('value.order') is-invalid @enderror"/>
                                            @error('value.order')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>

                                        <button type="submit" class="btn btn-primary mt-1">{{ trans('admin/main.submit') }}</button>
                                    </form>
                                </div>
                            </div>

                            <div class="table-responsive mt-4">
                                <table class="table custom-table font-14">
                                    <tr>
                                        <th>{{ trans('admin/main.title') }}</th>
                                        <th>{{ trans('admin/main.link') }}</th>
                                        <th>{{ trans('admin/main.order') }}</th>
                                        <th>{{ trans('admin/main.actions') }}</th>
                                    </tr>

                                    @if(!empty($items))
                                        @foreach($items as $key => $val)
                                            <tr>
                                                <td>{{ $val['title'] }}</td>
                                                <td>{{ $val['link'] }}</td>
                                                <td>{{ $val['order'] }}</td>
                                                <td width="80px">
    <div class="btn-group dropdown table-actions position-relative">
        <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
            <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
        </button>

        <div class="dropdown-menu dropdown-menu-right">
            <a href="{{ getAdminPanelUrl() }}/additional_page/navbar_links/{{ $key }}/edit"
               class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                <x-iconsax-lin-edit-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                <span class="text-gray-500 font-14">{{ trans('admin/main.edit') }}</span>
            </a>

            @include('admin.includes.delete_button',[
                'url' => getAdminPanelUrl().'/additional_page/navbar_links/'.$key.'/delete',
                'btnClass' => 'dropdown-item text-danger mb-0 py-3 px-0 font-14',
                'btnText' => trans('admin/main.delete'),
                'btnIcon' => 'trash',
                'iconType' => 'lin',
                'iconClass' => 'text-danger mr-2'
            ])
        </div>
    </div>
</td>
                                            </tr>
                                        @endforeach
                                    @endif

                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
