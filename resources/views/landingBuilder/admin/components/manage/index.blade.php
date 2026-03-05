@extends('landingBuilder.admin.layout', [
    'sidebarFilePath' => 'landingBuilder.admin.components.includes.sidebar'
])

@section('content')
    <form action="{{ getLandingBuilderUrl("/{$landingItem->id}/components/{$landingComponent->id}/update") }}" method="post" enctype="multipart/form-data" class="pb-64">
        {{ csrf_field() }}

        <div class="bg-white p-16 rounded-24 ">
            <div class="d-flex align-items-center justify-content-between pb-16 border-bottom-gray-100 pr-8">
                <div class="">
                    <h2 class="font-16 text-dark">{{ trans("update.{$landingComponent->landingBuilderComponent->name}_component_title") }}</h2>
                    <p class="text-gray-500 mt-2">{{ trans("update.{$landingComponent->landingBuilderComponent->name}_component_hint") }}</p>
                </div>

                <div class="actions-dropdown position-relative d-flex justify-content-end align-items-center">
                    <button type="button" class="d-flex-center btn-transparent">
                        <x-iconsax-lin-more class="icons text-gray-400" width="24px" height="24px"/>
                    </button>

                    <div class="actions-dropdown__dropdown-menu dropdown-menu-width-220">
                        <ul class="my-8">
                            <li class="actions-dropdown__dropdown-menu-item">
                                <a href="{{ getLandingBuilderUrl("") }}" class="">{{ trans('update.preview') }}</a>
                            </li>

                            <li class="actions-dropdown__dropdown-menu-item">
                                <a href="{{ getLandingBuilderUrl("/{$landingItem->id}/components/{$landingComponent->id}/duplicate") }}"
                                   class="delete-action"
                                   data-title="{{ trans('update.landing_component_duplicate_msg_hit') }}"
                                   data-confirm="{{ trans('public.duplicate') }}"
                                >{{ trans('public.duplicate') }}</a>
                            </li>

                            <li class="actions-dropdown__dropdown-menu-item">
                                <a href="{{ getLandingBuilderUrl("/{$landingItem->id}/components/{$landingComponent->id}/clear-content") }}"
                                   class="delete-action text-danger"
                                   data-title="{{ trans('update.landing_component_clear_content_msg_hit') }}"
                                   data-confirm="{{ trans('update.clear_content') }}"
                                >{{ trans('update.clear_content') }}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            @include("landingBuilder.admin.components.manage.{$componentName}.index")

        </div>


        <div class="landing-builder-bottom-bar d-flex align-items-center justify-content-between bg-white px-32 py-16">
            <div class="d-flex align-items-center">
                <div class="d-flex-center size-48 rounded-12 bg-gray-200">
                    <x-iconsax-bol-info-circle class="icons text-gray-400" width="24px" height="24px"/>
                </div>
                <div class="ml-8">
                    <h6 class="font-14 text-dark">Information</h6>
                    <p class="font-12 text-gray-500 mt-2">If you don’t define a data, it will be disabled in the front side automatically</p>
                </div>
            </div>

            <button type="submit" id="saveData" class="btn btn-primary">{{ trans('public.save') }}</button>
        </div>
    </form>
@endsection

@push('scripts_bottom')
    <script src="/assets/design_1/landing_builder/js/components.min.js"></script>
@endpush
