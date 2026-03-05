<div class="table-responsive mt-20">
    <table class="table custom-table font-14">

        <tr class="text-uppercase">
            @foreach($tableHeaders as $tableHeader)
                <th class="{{ $loop->first ? 'text-left' : 'text-center' }}">{{ trans($tableHeader) }}</th>
            @endforeach
        </tr>

        @if(count($errorsItems))
            @foreach($errorsItems as $errorsItemKey => $errorsItemData)
                <tr>
                    @foreach($errorsItemData as $erritemKey => $erritemData)
                        <td class="font-weight-400 {{ $loop->first ? 'text-left' : 'text-center' }}">
                            <div class="flex-grow-1">
                                <p class="mb-0">{{ !empty($erritemData) ? truncate($erritemData, 120) : '-' }}</p>

                                @if(!empty($errors) and !empty($errors[$errorsItemKey]) and !empty($errors[$errorsItemKey][$erritemKey]))
                                    <span class="text-danger fs-11">{{ $errors[$errorsItemKey][$erritemKey][0] }}</span>
                                @endif
                            </div>
                        </td>
                    @endforeach
                </tr>
            @endforeach
        @endif

    </table>
</div>
