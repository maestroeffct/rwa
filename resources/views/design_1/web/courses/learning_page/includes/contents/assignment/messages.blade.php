@if(!empty($assignmentHistory->messages) and count($assignmentHistory->messages))
    <div class="assignment-history-messages px-16" data-simplebar @if((!empty($isRtl))) data-simplebar-direction="rtl" @endif>
        @foreach($assignmentHistory->messages as $message)
            <div class="bg-white p-16 rounded-16 border-gray-200 mb-16">
                <div class="d-flex align-items-center">
                    <div class="size-40 rounded-circle">
                        <img src="{{ $message->sender->getAvatar(40) }}" class="img-cover rounded-circle" alt="{{ $message->sender->full_name }}">
                    </div>
                    <div class="ml-4">
                        <h4 class="font-14 font-weight-bold text-dark">{{ $message->sender->full_name }}</h4>
                        <span class="d-block font-12 text-gray-500 mt-2">{{ dateTimeFormat($message->created_at, 'j M Y H:i') }}</span>
                    </div>
                </div>

                <div class="mt-12 text-gray-500">{!! $message->message !!}</div>

                @if(!empty($message->file_path))
                    <a href="{{ $message->getDownloadUrl($assignment->id) }}" target="_blank" class="d-inline-flex-center p-8 mt-16 rounded-8 border-gray-300 bg-white bg-hover-gray-100">
                        <x-iconsax-lin-document-download class="icons text-gray-500" width="16px" height="16px"/>
                        <span class="ml-4 font-12 text-gray-500">{{ !empty($message->file_title) ? $message->file_title : trans('update.attachment') }}</span>
                    </a>
                @endif
            </div>
        @endforeach
    </div>
@else
    <div class="d-flex-center flex-column text-center w-100 h-100">
        <div class="">
            <img src="/assets/design_1/img/courses/learning_page/assignment/no-messages.svg" alt="" class="img-fluid" width="285px" height="212px">
        </div>

        <h5 class="mt-12 font-16 text-dark">{{ trans('update.no_assignment') }}</h5>
        <div class="mt-8 text-gray-500">{{ trans('update.submit_your_assignment_and_evaluate_your_learning') }}</div>
    </div>
@endif
