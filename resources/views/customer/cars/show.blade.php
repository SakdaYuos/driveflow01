@extends('layouts.app')
@section('title',$car->name.' '.$car->year)
@push('styles')
<style>
.detail-layout{display:grid;grid-template-columns:1fr 360px;gap:28px;max-width:1200px;margin:0 auto;padding:32px 24px;align-items:start}
.car-gallery{background:linear-gradient(135deg,#141414,#1e1e1e);border:1px solid var(--border);border-radius:16px;height:320px;display:flex;align-items:center;justify-content:center;font-size:100px;position:relative;overflow:hidden;margin-bottom:18px}
.spec-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:10px;margin-bottom:22px}
.spec-box{background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-sm);padding:12px;text-align:center}
.spec-box-icon{font-size:20px;margin-bottom:4px}
.spec-box-val{font-weight:700;font-size:13px;margin-bottom:2px}
.spec-box-lbl{font-size:10px;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px}
.detail-tabs{display:flex;gap:0;border-bottom:1px solid var(--border);margin-bottom:18px}
.dtab{padding:10px 18px;font-size:13px;font-weight:500;color:var(--text-muted);cursor:pointer;border-bottom:2px solid transparent;margin-bottom:-1px;transition:var(--transition)}
.dtab.active{color:var(--red);border-bottom-color:var(--red)}
.tab-content{display:none}.tab-content.active{display:block}
.book-panel{position:sticky;top:80px;background:var(--bg-card);border:1px solid var(--border);border-radius:16px;overflow:hidden}
.book-header{padding:18px 20px 14px;border-bottom:1px solid var(--border);background:linear-gradient(135deg,var(--bg-card),#1e0800)}
.book-price{font-family:'Barlow Condensed',sans-serif;font-size:36px;font-weight:800}
.book-price span{color:var(--red)}
.date-time-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:14px}
.trip-summary{background:var(--bg-input);border:1px solid var(--border);border-radius:var(--radius-sm);padding:14px;margin-bottom:14px}
.tr-row{display:flex;justify-content:space-between;font-size:13px;margin-bottom:7px;color:var(--text-muted)}
.tr-row strong{color:var(--text)}
.tr-total{display:flex;justify-content:space-between;font-weight:700;font-size:15px;padding-top:10px;border-top:1px solid var(--border);margin-top:8px}
.tr-total span{color:var(--red);font-family:'Barlow Condensed',sans-serif;font-size:20px}
.avail-dot{width:8px;height:8px;border-radius:50%;background:var(--green);animation:pulse 2s infinite}
@keyframes pulse{0%,100%{opacity:1}50%{opacity:.4}}
@media(max-width:900px){.detail-layout{grid-template-columns:1fr;}}
</style>
@endpush
@section('content')
<div style="border-bottom:1px solid var(--border);padding:12px 24px;font-size:12px;color:var(--text-muted)">
    <a href="{{ route('home') }}" class="text-red">Home</a> / <a href="{{ route('cars.index') }}" class="text-red">Browse Cars</a> / {{ $car->name }}
</div>
<div class="detail-layout">
    <div>
        <div class="car-gallery">
            @if($car->image)<img src="{{ asset('storage/'.$car->image) }}" alt="{{ $car->name }}" style="width:100%;height:100%;object-fit:cover;position:absolute;inset:0;border-radius:16px">
            @else<span style="position:relative;z-index:1;font-size:100px">🚗</span>@endif
        </div>
        <div style="margin-bottom:20px">
            <h1 style="font-family:'Barlow Condensed',sans-serif;font-size:38px;font-weight:800;line-height:1;margin-bottom:6px">
                {{ $car->brand }} {{ $car->model }} <span style="color:var(--text-muted);font-size:22px;font-weight:400">{{ $car->year }}</span>
            </h1>
            <div style="display:flex;gap:16px;align-items:center;flex-wrap:wrap;font-size:13px;color:var(--text-muted)">
                <span>📍 {{ $car->city }}</span>
                <span>🗓 {{ $car->trips_count }} trips</span>
                <span class="badge badge-green">✓ Available</span>
            </div>
        </div>
        <div class="spec-grid">
            <div class="spec-box"><div class="spec-box-icon">💺</div><div class="spec-box-val">{{ $car->car_seat }}</div><div class="spec-box-lbl">Seats</div></div>
            <div class="spec-box"><div class="spec-box-icon">⛽</div><div class="spec-box-val">{{ $car->fuel_type }}</div><div class="spec-box-lbl">Fuel</div></div>
            <div class="spec-box"><div class="spec-box-icon">🚗</div><div class="spec-box-val">{{ $car->type }}</div><div class="spec-box-lbl">Type</div></div>
            <div class="spec-box"><div class="spec-box-icon">📦</div><div class="spec-box-val" style="font-size:11px">Self Pick-up</div><div class="spec-box-lbl">Pick-up</div></div>
        </div>
        <div class="detail-tabs">
            <div class="dtab active" onclick="switchTab('desc',this)">Description</div>
            <div class="dtab" onclick="switchTab('rules',this)">Trip Rules</div>
            <div class="dtab" onclick="switchTab('rev',this)">Reviews ({{ $reviews->count() }})</div>
        </div>
        <div id="tab-desc" class="tab-content active">
            <p style="color:var(--text-muted);line-height:1.8;font-size:14px">{{ $car->description ?? 'A well-maintained vehicle ready for your next trip.' }}</p>
        </div>
        <div id="tab-rules" class="tab-content">
            <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-sm);padding:16px">
                @foreach(['No smoking inside the vehicle','Return with same fuel level','Valid driver\'s license required','No pets without prior approval'] as $rule)
                <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--text-muted);padding:6px 0;border-bottom:1px solid var(--border)"><span style="color:var(--green)">✅</span> {{ $rule }}</div>
                @endforeach
            </div>
        </div>
        <div id="tab-rev" class="tab-content">
            @forelse($reviews as $review)
            <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:10px;padding:14px;margin-bottom:12px">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px">
                    <div style="display:flex;gap:10px;align-items:center">
                        <div style="width:34px;height:34px;border-radius:50%;background:var(--bg-input);display:flex;align-items:center;justify-content:center;font-size:16px">👤</div>
                        <div><div style="font-weight:600;font-size:14px">{{ $review->user->name ?? 'Customer' }}</div><div style="font-size:11px;color:var(--text-muted)">{{ $review->created_at->format('M Y') }}</div></div>
                    </div>
                    <div style="color:#f59e0b;font-size:13px">{{ str_repeat('★',$review->rating??5) }}</div>
                </div>
                <div style="font-size:13px;color:var(--text-muted);line-height:1.6">{{ $review->review_text }}</div>
            </div>
            @empty
            <div style="text-align:center;padding:32px;color:var(--text-muted)"><div style="font-size:32px;margin-bottom:8px">⭐</div><div>No reviews yet. Be the first to book this car!</div></div>
            @endforelse
        </div>
        @if($relatedCars->isNotEmpty())
        <div style="margin-top:40px">
            <h3 style="font-family:'Barlow Condensed',sans-serif;font-size:22px;font-weight:700;margin-bottom:16px">More Cars in {{ $car->city }}</h3>
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px">
                @foreach($relatedCars as $rc)
                <a href="{{ route('cars.show',$rc) }}" class="t-card" style="background:var(--bg-card);border:1px solid var(--border);border-radius:14px;overflow:hidden;text-decoration:none;color:inherit;display:block;transition:all .2s">
                    <div style="height:140px;background:#111;overflow:hidden">
                        @if($rc->image)<img src="{{ asset('storage/'.$rc->image) }}" style="width:100%;height:100%;object-fit:cover">
                        @else<div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:48px">🚗</div>@endif
                    </div>
                    <div style="padding:12px 14px"><div style="font-weight:700;font-size:14px;margin-bottom:4px">{{ $rc->brand }} {{ $rc->model }}</div><div style="font-size:12px;color:var(--text-muted);margin-bottom:8px">📍 {{ $rc->city }}</div><div style="font-size:16px;font-weight:700;color:var(--red)">${{ number_format($rc->price_per_day,0) }}<span style="font-size:11px;color:var(--text-muted);font-weight:400">/day</span></div></div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <div>
        <div class="book-panel">
            <div class="book-header">
                <div class="book-price"><span>${{ number_format($car->price_per_day,0) }}</span> <span style="font-size:16px;color:var(--text-muted);font-family:var(--font-body);font-weight:400">/day</span></div>
                <div style="font-size:12px;color:var(--text-muted);margin-top:4px">Free cancellation available</div>
            </div>
            <div style="padding:18px 20px">
                <div style="display:flex;gap:8px;align-items:center;margin-bottom:16px;font-size:13px;color:var(--green)"><div class="avail-dot"></div> Available for your dates</div>
                <div class="date-time-grid">
                    <div><label style="display:block;font-size:10px;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:var(--text-muted);margin-bottom:5px">📅 Start Date</label><input type="date" class="form-control" id="trip-start" value="{{ now()->format('Y-m-d') }}" min="{{ now()->format('Y-m-d') }}" oninput="calcPrice()"/></div>
                    <div><label style="display:block;font-size:10px;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:var(--text-muted);margin-bottom:5px">⏰ Start Time</label><input type="time" class="form-control" id="trip-start-time" value="10:00" oninput="calcPrice()"/></div>
                    <div><label style="display:block;font-size:10px;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:var(--text-muted);margin-bottom:5px">📅 End Date</label><input type="date" class="form-control" id="trip-end" value="{{ now()->addDay()->format('Y-m-d') }}" min="{{ now()->addDay()->format('Y-m-d') }}" oninput="calcPrice()"/></div>
                    <div><label style="display:block;font-size:10px;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:var(--text-muted);margin-bottom:5px">⏰ End Time</label><input type="time" class="form-control" id="trip-end-time" value="10:00" oninput="calcPrice()"/></div>
                </div>
                <div class="trip-summary">
                    <div class="tr-row"><span>Duration</span><strong id="p-days">1 day</strong></div>
                    <div class="tr-row"><span id="p-breakdown">${{ number_format($car->price_per_day,0) }} × 1 day</span><strong id="p-sub">${{ number_format($car->price_per_day,0) }}</strong></div>
                    <div class="tr-row"><span>Service fee (10%)</span><strong id="p-fee">${{ number_format($car->price_per_day*0.1,0) }}</strong></div>
                    <div class="tr-total"><span>Total</span><span id="p-total">${{ number_format($car->price_per_day*1.1,0) }}</span></div>
                </div>
            </div>
            <div style="padding:0 20px 20px">
                <button onclick="goCheckout()" class="btn btn-primary btn-full btn-lg">Continue to Checkout →</button>
                <div style="text-align:center;font-size:12px;color:var(--text-dim);margin-top:8px">You won't be charged yet</div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
const PRICE={{ $car->price_per_day }};
function calcPrice(){
    const s=new Date(document.getElementById('trip-start').value+'T'+document.getElementById('trip-start-time').value);
    const e=new Date(document.getElementById('trip-end').value+'T'+document.getElementById('trip-end-time').value);
    if(isNaN(s)||isNaN(e)||e<=s)return;
    const days=Math.max(1,Math.ceil((e-s)/3600000/24));
    const sub=days*PRICE; const fee=Math.round(sub*0.10);
    document.getElementById('p-days').textContent=days+' day'+(days>1?'s':'');
    document.getElementById('p-breakdown').textContent='$'+PRICE+' × '+days+' day'+(days>1?'s':'');
    document.getElementById('p-sub').textContent='$'+sub;
    document.getElementById('p-fee').textContent='$'+fee;
    document.getElementById('p-total').textContent='$'+(sub+fee);
}
function goCheckout(){
    @auth
    const params=new URLSearchParams({start:document.getElementById('trip-start').value,end:document.getElementById('trip-end').value,start_time:document.getElementById('trip-start-time').value,end_time:document.getElementById('trip-end-time').value});
    window.location.href='{{ route("payment.checkout",$car) }}?'+params;
    @else
    window.location.href='{{ route("login") }}';
    @endauth
}
function switchTab(name,el){document.querySelectorAll('.dtab').forEach(t=>t.classList.remove('active'));document.querySelectorAll('.tab-content').forEach(t=>t.classList.remove('active'));el.classList.add('active');document.getElementById('tab-'+name).classList.add('active');}
calcPrice();
</script>
@endpush
