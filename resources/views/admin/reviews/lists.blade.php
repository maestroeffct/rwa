@extends('admin.layouts.app')

@push('styles_top')

@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{trans('admin/main.reviews')}}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{trans('admin/main.reviews')}}</div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card-statistic">
                    <div class="card-statistic__mask"></div>
                    <div class="card-statistic__wrap">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8">{{trans('admin/main.total_reviews')}}</span>
                            <div class="d-flex-center size-48 bg-primary-30 rounded-12">
                                <x-iconsax-bul-star class="icons text-primary" width="24px" height="24px"/>
                            </div>
                        </div>
                        <h5 class="font-24 mt-12 line-height-1 text-black">{{ $totalReviews }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card-statistic">
                    <div class="card-statistic__mask"></div>
                    <div class="card-statistic__wrap">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8">{{trans('admin/main.published_reviews')}}</span>
                            <div class="d-flex-center size-48 bg-success-30 rounded-12">
                                <x-iconsax-bul-star class="icons text-success" width="24px" height="24px"/>
                            </div>
                        </div>
                        <h5 class="font-24 mt-12 line-height-1 text-black">{{ $publishedReviews }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card-statistic">
                    <div class="card-statistic__mask"></div>
                    <div class="card-statistic__wrap">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8">{{trans('admin/main.rates_average')}}</span>
                            <div class="d-flex-center size-48 bg-secondary-30 rounded-12">
                                <x-iconsax-bul-calculator class="icons text-secondary" width="24px" height="24px"/>
                            </div>
                        </div>
                        <h5 class="font-24 mt-12 line-height-1 text-black">{{ $ratesAverage }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card-statistic">
                    <div class="card-statistic__mask"></div>
                    <div class="card-statistic__wrap">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8">{{trans('admin/main.classes_without_review')}}</span>
                            <div class="d-flex-center size-48 bg-danger-30 rounded-12">
                                <x-iconsax-bul-video-play class="icons text-danger" width="24px" height="24px"/>
                            </div>
                        </div>
                        <h5 class="font-24 mt-12 line-height-1 text-black">{{ $classesWithoutReview }}</h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-body">
            <section class="card mt-32">
                <div class="card-body pb-4">
                    <form method="get" class="mb-0">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.search')}}</label>
                                    <input type="text" class="form-control" name="search" placeholder="" value="{{ request()->get('search') }}">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.start_date')}}</label>
                                    <div class="input-group">
                                        <input type="date" id="fsdate" class="text-center form-control" name="from" value="{{ request()->get('from') }}" placeholder="Start Date">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.end_date')}}</label>
                                    <div class="input-group">
                                        <input type="date" id="lsdate" class="text-center form-control" name="to" value="{{ request()->get('to') }}" placeholder="End Date">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.class')}}</label>
                                    <select name="webinar_ids[]" multiple="multiple" class="form-control search-webinar-select2"
                                            data-placeholder="Search classes">

                                        @if(!empty($webinars) and $webinars->count() > 0)
                                            @foreach($webinars as $webinar)
                                                <option value="{{ $webinar->id }}" selected>{{ $webinar->title }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.status')}}</label>
                                    <select name="status" class="form-control populate">
                                        <option value="">{{trans('admin/main.all_status')}}</option>
                                        <option value="active" @if(request()->get('status') == 'active') selected @endif>{{trans('admin/main.published')}}</option>
                                        <option value="pending" @if(request()->get('status') == 'pending') selected @endif>{{trans('admin/main.hidden')}}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2 d-flex align-items-center ">
                                <button type="submit" class="btn btn-primary btn-block btn-lg">{{trans('admin/main.show_results')}}</button>
                            </div>

                        </div>
                    </form>
                </div>
            </section>

            <section class="card">
                <div class="card-body">
                    <table class="table custom-table font-14" id="datatable-details">

                        <tr>
                            <th class="text-left">{{trans('admin/main.title')}}</th>
                            <th class="text-left">{{trans('admin/main.student')}}</th>
                            <th class="">{{trans('admin/main.type')}}</th>
                            <th class="">{{trans('admin/main.comment')}}</th>
                            <th class="">{{trans('admin/main.reply')}}</th>
                            <th class="">{{trans('admin/main.rate')}} (5)</th>
                            <th class="">{{trans('admin/main.created_at')}}</th>
                            <th class="">{{trans('admin/main.status')}}</th>
                            <th class="">{{trans('admin/main.actions')}}</th>
                        </tr>

                        @foreach($reviews as $review)
                            <tr>
                                <td class="text-dark text-left">
                                    @if(!empty($review->webinar_id))
                                        <a href="{{ $review->webinar->getUrl() }}" class="text-dark" target="_blank">{{ $review->webinar->title }}</a>
                                    @elseif(!empty($review->bundle_id))
                                        <a href="{{ $review->bundle->getUrl() }}" class="text-dark" target="_blank">{{ $review->bundle->title }}</a>
                                    @endif
                                </td>

                                <td class="text-left">{{ $review->creator->full_name }}</td>

                                <td class="">
                                    @if(!empty($review->webinar_id))
                                        <span class="">{{ trans('admin/main.course') }}</span>
                                    @elseif(!empty($review->bundle_id))
                                        <span class="">{{ trans('update.bundle') }}</span>
                                    @endif
                                </td>

                                <td>
                                    <button type="button" class="js-show-description btn btn-sm btn-outline-primary">{{ trans('admin/main.show') }}</button>
                                    <input type="hidden" value="{{ nl2br($review->description) }}">
                                </td>

                                <td class="">{{ $review->comments_count }}</td>

                                <td class="">{{ $review->rates }}</td>

                                <td class="">{{ dateTimeFormat($review->created_at,'j M Y | H:i') }}</td>

                                <td class="">
                                    @if($review->status == 'active')
                                        <span class="badge-status text-success bg-success-30">{{ trans('admin/main.published') }}</span>
                                    @else
                                    <span class="badge-status text-warning bg-warning-30">{{ trans('admin/main.hidden') }}</span>                                    @endif
                                </td>


                      <td>
                        <div class="btn-group dropdown table-actions position-relative">
                            <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px" />
                            </button>

                            <div class="dropdown-menu dropdown-menu-right">

                                @can('admin_reviews_status_toggle')
                                    <a href="{{ getAdminPanelUrl() }}/reviews/{{ $review->id }}/toggleStatus"
                                       class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                        @if($review->status == 'active')
                                            <x-iconsax-lin-eye-slash class="icons text-warning mr-2" width="18px" height="18px"/>
                                            <span class="text-warning">Hide</span>
                                        @else
                                            <x-iconsax-lin-eye class="icons text-success mr-2" width="18px" height="18px"/>
                                            <span class="text-success">Publish</span>
                                        @endif
                                    </a>
                                @endcan

                                @can('admin_reviews_detail_show')
                                    <input type="hidden" class="js-content_quality" value="{{ $review->content_quality }}">
                                    <input type="hidden" class="js-instructor_skills" value="{{ $review->instructor_skills }}">
                                    <input type="hidden" class="js-purchase_worth" value="{{ $review->purchase_worth }}">
                                    <input type="hidden" class="js-support_quality" value="{{ $review->support_quality }}">

                                    <button type="button"
                                            class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4 js-show-review-details">
                                        <x-iconsax-lin-star class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                        <span class="text-gray-500 font-14">Rate Detail</span>
                                    </button>
                                @endcan

                                @can('admin_reviews_reply')
                                    <a href="{{ getAdminPanelUrl("/reviews/{$review->id}/reply") }}"
                                       target="_blank"
                                       class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                        <x-iconsax-lin-message class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                        <span class="text-gray-500 font-14">{{ trans('admin/main.reply') }}</span>
                                    </a>
                                @endcan

                                @can('admin_reviews_delete')
                                    @include('admin.includes.delete_button', [
                                        'url' => getAdminPanelUrl() . '/reviews/' . $review->id . '/delete',
                                        'btnClass' => 'dropdown-item text-danger mb-0 py-3 px-0 font-14 d-flex align-items-center gap-4',
                                        'btnText' => trans("admin/main.delete"),
                                        'btnIcon' => 'trash',
                                        'iconType' => 'lin',
                                        'iconClass' => 'text-danger mr-2',
                                    ])
                                @endcan

                            </div>
                        </div>
                    </td>


                            </tr>
                        @endforeach

                    </table>
                </div>

                <div class="card-footer text-center">
                    {{ $reviews->appends(request()->input())->links() }}
                </div>
            </section>
        </div>
    </section>

    <div class="modal fade" id="reviewRateDetail" tabindex="-1" aria-labelledby="contactMessageLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="contactMessageLabel">{{trans('admin/main.view_rates_details')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="d-flex align-items-center justify-content-between border-bottom py-2">
                        <span class="font-weight-bold">{{ trans('product.content_quality') }}:</span>
                        <span class="js-content_quality"></span>
                    </div>

                    <div class="d-flex align-items-center justify-content-between border-bottom py-2">
                        <span class="font-weight-bold">{{ trans('product.instructor_skills') }}:</span>
                        <span class="js-instructor_skills"></span>
                    </div>

                    <div class="d-flex align-items-center justify-content-between border-bottom py-2">
                        <span class="font-weight-bold">{{ trans('product.purchase_worth') }}:</span>
                        <span class="js-purchase_worth"></span>
                    </div>

                    <div class="d-flex align-items-center justify-content-between border-bottom py-2">
                        <span class="font-weight-bold">{{ trans('product.support_quality') }}:</span>
                        <span class="js-support_quality"></span>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('admin/main.close') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="contactMessage" tabindex="-1" aria-labelledby="contactMessageLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="contactMessageLabel">{{ trans('admin/main.message') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('admin/main.close') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts_bottom')
    <script src="/assets/admin/js/parts/reviews.min.js"></script>
@endpush
