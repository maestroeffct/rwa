@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ $pageTitle }}</div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card-statistic">
                        <div class="card-statistic__mask"></div>
                        <div class="card-statistic__wrap">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="text-gray-500 mt-8">{{trans('update.total_generated')}}</span>
                                <div class="d-flex-center size-48 bg-primary-30 rounded-12">
                                    <x-iconsax-bul-document class="icons text-primary" width="24px" height="24px"/>
                                </div>
                            </div>
                            <h5 class="font-24 mt-12 line-height-1 text-black">{{ $totalGenerated }}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card-statistic">
                        <div class="card-statistic__mask"></div>
                        <div class="card-statistic__wrap">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="text-gray-500 mt-8">{{trans('update.text_generated')}}</span>
                                <div class="d-flex-center size-48 bg-success-30 rounded-12">
                                    <x-iconsax-bul-document-text class="icons text-success" width="24px" height="24px"/>
                                </div>
                            </div>
                            <h5 class="font-24 mt-12 line-height-1 text-black">{{ $textGenerated }}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card-statistic">
                        <div class="card-statistic__mask"></div>
                        <div class="card-statistic__wrap">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="text-gray-500 mt-8">{{trans('update.image_generated')}}</span>
                                <div class="d-flex-center size-48 bg-accent-30 rounded-12">
                                    <x-iconsax-bul-gallery class="icons text-accent" width="24px" height="24px"/>
                                </div>
                            </div>
                            <h5 class="font-24 mt-12 line-height-1 text-black">{{ $imageGenerated }}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card-statistic">
                        <div class="card-statistic__mask"></div>
                        <div class="card-statistic__wrap">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="text-gray-500 mt-8">{{trans('admin/main.users')}}</span>
                                <div class="d-flex-center size-48 bg-primary-30 rounded-12">
                                    <x-iconsax-bul-user class="icons text-primary" width="24px" height="24px"/>
                                </div>
                            </div>
                            <h5 class="font-24 mt-12 line-height-1 text-black">{{ $usersCount }}</h5>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card mt-32">
                        {{--<div class="card-header">

                        </div>--}}

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table custom-table font-14">
                                    <tr>
                                        <th class="text-left">{{ trans('admin/main.user') }}</th>
                                        <th>{{ trans('update.service_type') }}</th>
                                        <th>{{ trans('update.service') }}</th>
                                        <th>{{ trans('update.keyword') }}</th>
                                        <th>{{ trans('auth.language') }}</th>
                                        <th>{{ trans('update.generated_date') }}</th>
                                        <th width="120">{{ trans('admin/main.actions') }}</th>
                                    </tr>

                                    @foreach($contents as $content)
                                        <tr>

                                            <td class="text-left">
                                                {{ !empty($content->user) ? $content->user->full_name : '' }}
                                                <div class="text-primary text-small font-600-bold">ID : {{  !empty($content->user) ? $content->user->id : '' }}</div>
                                            </td>

                                            <td>
                                                {{ trans($content->service_type) }}
                                            </td>

                                            <td>
                                                @if(!empty($content->template))
                                                    {{ $content->template->title }}
                                                @else
                                                    {{ trans('update.custom') }}
                                                @endif
                                            </td>

                                            <td>
                                                <span class="">{{ truncate($content->keyword, 100) }}</span>
                                            </td>

                                            <td>
                                                <span class="">{{ truncate($content->language, 100) }}</span>
                                            </td>

                                            <td>{{ dateTimeFormat($content->created_at, 'j F Y H:i') }}</td>

                                            <td>
    <div class="btn-group dropdown table-actions position-relative">
        <input type="hidden" class="js-prompt" value="{{ $content->prompt }}">
        <input type="hidden" class="js-result" value="{{ $content->result }}">

        <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
            <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
        </button>

        <div class="dropdown-menu dropdown-menu-right">
            <button type="button" class="js-view-content dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                <x-iconsax-lin-eye class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                <span class="text-gray-500 font-14">{{ trans('public.view') }}</span>
            </button>

            @can('admin_users_edit')
                <a href="{{ getAdminPanelUrl() }}/users/{{ $content->user_id }}/edit"
                   class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                    <x-iconsax-lin-profile-2user class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                    <span class="text-gray-500 font-14">{{ trans('update.edit_user') }}</span>
                </a>
            @endcan

            @can('admin_sales_refund')
                @include('admin.includes.delete_button',[
                    'url' => getAdminPanelUrl().'/ai-contents/'.$content->id.'/delete',
                    'btnClass' => 'dropdown-item text-danger mb-0 py-3 px-0 font-14',
                    'btnText' => trans('admin/main.delete'),
                    'btnIcon' => 'close-circle',
                    'iconType' => 'lin',
                    'iconClass' => 'text-danger mr-2'
                ])
            @endcan
        </div>
    </div>
</td>
                                        </tr>
                                    @endforeach

                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            {{ $contents->appends(request()->input())->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Modal -->
    <div class="modal fade" id="contentModal" tabindex="-1" aria-labelledby="contactMessageLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="contactMessageLabel">{{ trans('update.generated_content') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="js-prompt-card">
                        <h6 class="fs-12">{{ trans('update.prompt') }}:</h6>
                        <p class=""></p>
                    </div>

                    {{-- Text Generated --}}
                    <div id="generatedTextContents" class="d-none"></div>

                    <div class="js-text-generated-modal mt-20 p-15 bg-info-light border-gray300 rounded-sm d-none">
                        <div class="d-flex align-items-center justify-content-between">
                            <h4 class="font-14 text-gray-500">{{ trans('update.generated_content') }}</h4>

                            <div class="form-group mb-0">
                                <button type="button" class="btn-transparent d-flex align-items-center js-copy-content-modal" data-toggle="tooltip" data-placement="top" title="{{ trans('public.copy') }}" data-copy-text="{{ trans('public.copy') }}" data-done-text="{{ trans('public.done') }}">
                                    <i data-feather="copy" width="18" height="18" class="text-gray-500"></i>
                                    <span class="text-gray-500 font-12 ml-5">{{ trans('public.copy') }}</span>
                                </button>
                            </div>
                        </div>

                        <div class="mt-15 font-14 text-gray-500 js-content-modal"></div>
                    </div>


                    {{-- Text Generated --}}
                    <div class="js-image-generated-modal mt-20 p-15 bg-info-light border-gray300 rounded-sm d-none">
                        <div class="">
                            <h4 class="font-14 text-gray-500">{{ trans('update.generated_content') }}</h4>
                            <p class="mt-1 text-gray-500 font-12">{{ trans('update.click_on_images_to_download_them') }}</p>
                        </div>

                        <div class="js-content-modal mt-15 d-flex-center flex-wrap">

                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('admin/main.close') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts_bottom')
    <script>
        var generatedContentLang = '{{ trans('update.generated_content') }}';
        var copyLang = '{{ trans('public.copy') }}';
        var doneLang = '{{ trans('public.done') }}';
    </script>

    <script src="/assets/admin/js/parts/ai_contents_lists.min.js"></script>
@endpush
