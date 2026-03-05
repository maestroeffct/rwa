<tr>
    <td class="text-left">
        {{ $promotionSale->webinar->title }}
    </td>

    <td class="text-center">
        {{ $promotionSale->promotion->title }}
    </td>

    <td class="text-center">
        {{ (!empty($promotionSale->promotion->price) and $promotionSale->promotion->price > 0) ? handlePrice($promotionSale->promotion->price) : trans('public.free') }}
    </td>

    <td class="text-center">
        {{ dateTimeFormat($promotionSale->created_at, 'j M Y | H:i') }}
    </td>
</tr>
