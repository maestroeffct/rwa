<div class="table-responsive mt-20">
    <table class="table custom-table font-14">

        <tr class="text-uppercase">
            @foreach($tableHeaders as $tableHeader)
                <th class="{{ $loop->first ? 'text-left' : 'text-center' }}">{{ trans($tableHeader) }}</th>
            @endforeach
        </tr>

        @if(count($validatedItems))
            @foreach($validatedItems as $validatedItemData)
                <tr>
                    @foreach($validatedItemData as $itemData)
                        <td class="font-weight-400 {{ $loop->first ? 'text-left' : 'text-center' }}">
                            <div class="flex-grow-1">
                                <p class="mb-0">{{ !empty($itemData) ? truncate($itemData, 120) : '-' }}</p>
                            </div>
                        </td>
                    @endforeach
                </tr>
            @endforeach
        @endif

    </table>
</div>

