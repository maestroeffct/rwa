@extends('design_1.web.emails.layout')

@section('body')
    <!-- content -->
    <td valign="top" class="bodyContent" mc:edit="body_content">
        <h1 class="h1">{{ $contact->subject }}</h1>
        <p>{{ trans('admin/main.user_name') }} : {{ $contact->name }}</p>
        <p>{!! nl2br($contact->reply) !!}</p>

        <p>{{ trans('notification.email_ignore_msg') }}</p>
    </td>
@endsection
