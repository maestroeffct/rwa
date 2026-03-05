<tr>
    <td class="text-left">
        <div class="d-flex align-items-center">
            <div class="d-flex-center size-48 rounded-12 border-dashed border-gray-200">
                @if($reward->status == \App\Models\RewardAccounting::ADDICTION)
                    <x-iconsax-bol-star-1 class="icons text-gray-400" width="24px" height="24px"/>
                @else
                    <x-iconsax-bol-star-slash class="icons text-gray-400" width="24px" height="24px"/>
                @endif
            </div>

            <div class="ml-16">{{ trans('update.reward_type_'.$reward->type) }}</div>
        </div>
    </td>

    <td class="text-center">
        @if($reward->status == \App\Models\RewardAccounting::ADDICTION)
            <span class="d-inline-flex-center px-8 py-6 rounded-8 font-12 bg-success-20 text-success">{{ trans('update.earn') }}</span>
        @else
            <span class="d-inline-flex-center px-8 py-6 rounded-8 font-12 bg-danger-20 text-danger">{{ trans('update.redeem') }}</span>
        @endif
    </td>

    <td class="text-center">
        @if($reward->status == \App\Models\RewardAccounting::ADDICTION)
            <span class="font-weight-bold text-dark">{{ $reward->score }}</span>
        @else
            <span class="font-weight-bold text-danger">{{ $reward->score }}</span>
        @endif
    </td>

    <td class="text-center">{{ dateTimeFormat($reward->created_at, 'j F Y H:i') }}</td>
</tr>
