<div class="d-flex-center flex-column text-center px-16">

    <div class="profile-avatar-card size-64 rounded-circle mt-32">
        <img src="{{ $user->getAvatar(64) }}" alt="{{ $user->full_name }}" class="img-cover rounded-circle">
    </div>

    <h4 class="mt-16 font-14 font-weight-bold">{{ $user->full_name }}</h4>
    <p class="mt-4 font-12 text-gray-500">{{ trans('update.send_a_message_to_the_user_directly') }}</p>
</div>

<form action="/users/{{ $user->getUsername() }}/send-message" class="mt-24">

    <div class="form-group">
        <label class="form-group-label">{{ trans('public.email') }}</label>
        <input type="text" name="email" class="js-ajax-email form-control"/>
        <div class="invalid-feedback"></div>
    </div>

    <div class="form-group">
        <label class="form-group-label">{{ trans('update.subject') }}</label>
        <input type="text" name="title" class="js-ajax-title form-control"/>
        <div class="invalid-feedback"></div>
    </div>

    <div class="form-group">
        <label class="form-group-label">{{ trans('site.message') }}</label>
        <textarea name="description" class="js-ajax-description form-control" rows="5"></textarea>
        <div class="invalid-feedback"></div>
    </div>

    @include('design_1.web.includes.captcha_input')


    <div class="form-group mb-0">
        <div class="js-ajax-server"></div>
        <div class="invalid-feedback"></div>
    </div>
</form>
