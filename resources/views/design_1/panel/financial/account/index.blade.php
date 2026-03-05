@extends('design_1.panel.layouts.panel')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
@endpush

@section('content')

    {{-- Overview --}}
    @include("design_1.panel.financial.account.overview")

    {{-- Charge Account Form --}}
    @include("design_1.panel.financial.account.charge_account_form")

    {{-- Offline transactions --}}
    @include("design_1.panel.financial.account.offline_transactions.index")

@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="{{ getDesign1ScriptPath("get_view_data") }}"></script>

    <script src="/assets/design_1/js/panel/charge_account.min.js"></script>
@endpush
