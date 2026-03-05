@extends('admin.layouts.app')


@section('content')
    <form action="{{ getAdminPanelUrl("/themes/footers/{$footerItem->id}/update") }}" method="post" enctype="multipart/form-data" class="pb-64">
        {{ csrf_field() }}

        <div class="bg-white p-16 rounded-24 ">
            <div class="d-flex align-items-center justify-content-between pb-16 border-bottom-gray-100 pr-8">
                <div class="">
                    <h2 class="font-16 text-black">{{ $footerItem->title }}</h2>
                </div>

            </div>

            @include("admin.theme.footers.components.{$footerItem->component_name}.index")

        </div>


        <div class="d-flex align-items-center justify-content-between bg-white px-16 py-16 mt-16 rounded-24">
            <div class="d-flex align-items-center">
                <div class="d-flex-center size-48 rounded-12 bg-gray-200">
                    <x-iconsax-bol-info-circle class="icons text-gray-400" width="24px" height="24px"/>
                </div>
                <div class="ml-8">
                    <h6 class="font-14 text-black m-0">Information</h6>
                    <p class="font-12 text-gray-500 mb-0">If you don’t define a data, it will be disabled in the front side automatically</p>
                </div>
            </div>

            <button type="submit" id="saveData" class="btn btn-primary btn-lg">{{ trans('public.save') }}</button>
        </div>
    </form>
@endsection

@push('scripts_bottom')
    <script src="/assets/design_1/landing_builder/js/components.min.js"></script>
@endpush
