@extends('admin.layouts.app')

@push('styles_top')

@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('update.rewards') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a></div>
                <div class="breadcrumb-item">{{ trans('update.rewards') }}</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">

                        <div class="card-header justify-content-between">

                            <div>
                               <h5 class="font-14 mb-0">{{ trans('update.rewards') }}</h5>
                               <p class="font-12 mt-4 mb-0 text-gray-500">{{ trans('update.manage_all_items_in_a_single_place') }}</p>
                           </div>

                            <div class="d-flex align-items-center gap-12">



                            @can('admin_rewards_items')
                                   <a  class="js-add-new-reward btn btn-primary">
                                       <x-iconsax-lin-add class="icons text-white" width="18px" height="18px"/>
                                       <span class="text-white ml-4 font-12">{{ trans('admin/main.add_new') }}</span>
                                   </a>
                               @endcan

                            </div>

                       </div>


                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table custom-table font-14">
                                    <tr>
                                        <th>{{ trans('admin/main.title') }}</th>
                                        <th>{{ trans('update.score') }}</th>
                                        <th>{{ trans('admin/main.status') }}</th>
                                        <th>{{ trans('admin/main.created_at') }}</th>
                                        <th>{{ trans('admin/main.actions') }}</th>
                                    </tr>

                                    @if(!empty($rewards))
                                        @foreach($rewards as $reward)
                                            <tr>
                                                <td>{{ trans('update.reward_type_'.$reward->type) }}</td>
                                                <td>{{ $reward->score }}</td>
                                                <td>{{ trans('admin/main.'.$reward->status) }}</td>
                                                <td>{{ dateTimeFormat($reward->created_at,'j M Y') }}</td>
                                                <td>
    <div class="btn-group dropdown table-actions position-relative">
        <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
            <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
        </button>

        <div class="dropdown-menu dropdown-menu-right">
            @can('admin_rewards_items')
                <button type="button"
                        class="js-edit-reward dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4"
                        data-id="{{ $reward->id }}">
                    <x-iconsax-lin-edit-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                    <span class="text-gray-500 font-14">{{ trans('admin/main.edit') }}</span>
                </button>
            @endcan

            @can('admin_rewards_item_delete')
                @include('admin.includes.delete_button',[
                    'url' => getAdminPanelUrl().'/rewards/items/'.$reward->id.'/delete',
                    'btnClass' => 'dropdown-item text-danger mb-0 py-3 px-0 font-14',
                    'btnText' => trans('admin/main.delete'),
                    'btnIcon' => 'trash',
                    'iconType' => 'lin',
                    'iconClass' => 'text-danger mr-2'
                ])
            @endcan
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


    <div class="modal fade" id="rewardSettingModal" tabindex="-1" aria-labelledby="contactMessageLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{trans('update.new_condition')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label class="input-label">{{trans('update.condition')}}</label>
                            <select name="type" class="form-control">
                                <option selected disabled>--</option>

                                @foreach(\App\Models\Reward::getTypesLists() as $type)
                                    <option value="{{ $type }}">{{ trans('update.reward_type_'.$type) }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="js-score-input form-group">
                            <label class="input-label">{{trans('update.score')}}</label>
                            <input type="number" name="score" class="form-control"/>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="js-condition-input form-group d-none ">
                            <label class="input-label">{{trans('update.value')}}</label>
                            <input type="text" name="condition" class="form-control"/>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="custom-control custom-switch">
                            <input type="checkbox" name="status" id="statusSwitch" class="custom-control-input" checked>
                            <label class="custom-control-label" for="statusSwitch">{{ trans('admin/main.active') }}</label>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="js-save-reward btn btn-primary">{{trans('admin/main.save')}}</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('admin/main.close') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts_bottom')

    <script>
        var saveSuccessLang = '{{ trans('webinars.success_store') }}';
    </script>

    <script src="/assets/admin/js/parts/rewards_items.min.js"></script>
@endpush
