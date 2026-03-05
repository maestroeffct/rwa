<tr>
    <td class="text-left">
        <div class="d-flex align-items-center">
            <div class="size-40 rounded-circle">
                <img src="{{ $affiliate->referredUser->getAvatar() }}" class="js-avatar-img img-cover rounded-circle" alt="{{ $affiliate->referredUser->full_name }}">
            </div>

            <div class=" ml-8">
                <span class="d-block">{{ $affiliate->referredUser->full_name }}</span>
            </div>
        </div>
    </td>

    <td class="text-center">{{ handlePrice($affiliate->getAffiliateRegistrationAmountsOfEachReferral()) }}</td>

    <td class="text-center">{{ handlePrice($affiliate->getTotalAffiliateCommissionOfEachReferral()) }}</td>

    <td class="text-center">{{ dateTimeFormat($affiliate->created_at, 'Y M j | H:i') }}</td>
</tr>
