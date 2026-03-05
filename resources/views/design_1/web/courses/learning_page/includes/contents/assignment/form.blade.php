<div class="p-12 rounded-12 border-dashed border-gray-200 w-100 h-100">
    <h4 class="font-14 font-weight-bold text-dark">
        @if($user->id == $assignment->creator_id)
            {{ trans('update.reply_to_the_conversation') }}
        @else
            {{ trans('update.send_assignment') }}
        @endif
    </h4>

    <div class="js-assignment-conversation-form d-flex flex-column mt-24" data-action="/course/assignment/{{ $assignment->id }}/history/{{ $assignmentHistory->id }}/message">

        @if($user->id == $assignment->creator_id)
            <input type="hidden" name="student_id" value="{{ $assignmentHistory->student_id }}">
        @endif

        <div class="form-group">
            <label class="form-group-label">{{ trans('public.description') }}</label>
            <textarea name="description" rows="14" class="js-ajax-description form-control"></textarea>
            <div class="invalid-feedback"></div>
        </div>

        <div class="form-group">
            <label class="form-group-label">{{ trans('update.file_title') }} ({{ trans('public.optional') }})</label>
            <input type="text" name="file_title" class="js-ajax-file_title form-control"/>
            <div class="invalid-feedback"></div>
        </div>

        <div class="form-group custom-input-file flex-1">
            <label class="form-group-label">{{ trans('update.attachment') }}</label>

            <div class="custom-file bg-white js-ajax-attachment">
                <input type="file" name="attachment" class="custom-file-input js-ajax-upload-file-input" id="attachmentsInput" data-upload-name="attachment">
                <span class="custom-file-text text-gray-500"></span>
                <label class="custom-file-label bg-transparent" for="attachmentsInput">
                    <x-iconsax-lin-export class="icons text-gray-400" width="24px" height="24px"/>
                </label>
            </div>

            <div class="invalid-feedback d-block"></div>
        </div>

        <button type="button" class="js-send-assignment-conversation btn btn-primary btn-lg btn-block">{{ trans('update.send') }}</button>
    </div>
</div>
