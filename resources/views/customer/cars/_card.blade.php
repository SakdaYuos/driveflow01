<div class="car-card">
    <a href="{{ route('cars.show',$car) }}" class="car-card-link">
        <div class="car-img">
            @if($car->image)<img src="{{ asset('storage/'.$car->image) }}" alt="{{ $car->brand }} {{ $car->model }}" style="width:100%;height:100%;object-fit:cover;">
            @else<div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:56px;background:rgba(255,255,255,0.03);">🚗</div>@endif
            <div class="car-img-overlay"></div>
            <div class="car-img-badge"><span class="badge {{ $car->status==='Available'?'badge-green':'badge-red' }}">{{ $car->status==='Available'?'✓ Available':$car->status }}</span></div>
        </div>
        <div class="car-body">
            <div class="car-name">{{ $car->brand }} {{ $car->model }} <span class="car-year">{{ $car->year }}</span></div>
            <div class="car-host">📍 {{ $car->city }}</div>
            <div class="car-specs">
                <div class="car-spec">💺 {{ $car->car_seat }} seats</div>
                <div class="car-spec">⛽ {{ $car->fuel_type }}</div>
                <div class="car-spec">🚘 {{ $car->type }}</div>
            </div>
            <div class="car-footer">
                <div class="car-price">${{ number_format($car->price_per_day,0) }}<small>/day</small></div>
            </div>
        </div>
    </a>
</div>
