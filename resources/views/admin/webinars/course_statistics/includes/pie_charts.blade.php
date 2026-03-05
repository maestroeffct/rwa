<div class="col-12 col-md-3 mt-3">
    <div class="course-statistic-cards-shadow p-16 rounded-8 bg-white">
        <span class="d-block font-16 font-weight-bold text-dark">{{ $cardTitle }}</span>
        <div class="mt-3 statistic-pie-charts">
            <canvas id="{{ $cardId }}" height="197"></canvas>
        </div>

        <div class="mt-3">
            <div class="d-flex align-items-center">
                <span class="cart-label-color rounded-circle bg-primary mr-2"></span>
                <span class="font-14 text-gray-500">{{ $cardPrimaryLabel }}</span>
            </div>
            <div class="d-flex align-items-center">
                <span class="cart-label-color rounded-circle bg-secondary mr-2"></span>
                <span class="font-14 text-gray-500">{{ $cardSecondaryLabel }}</span>
            </div>
            <div class="d-flex align-items-center">
                <span class="cart-label-color rounded-circle bg-warning mr-2"></span>
                <span class="font-14 text-gray-500">{{ $cardWarningLabel }}</span>
            </div>
        </div>
    </div>
</div>
