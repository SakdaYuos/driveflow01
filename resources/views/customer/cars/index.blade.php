@extends('layouts.app')
@section('title','Browse Cars')
@push('styles')
<style>
.browse-topbar{background:var(--bg-card);border-bottom:1px solid var(--border);padding:12px 0;position:sticky;top:64px;z-index:100}
.browse-inner{max-width:1280px;margin:0 auto;padding:0 24px;display:flex;align-items:center;gap:10px;flex-wrap:wrap}
.filter-toggle-btn{display:inline-flex;align-items:center;gap:8px;padding:9px 18px;border:1px solid var(--border);border-radius:20px;background:var(--bg-card);color:var(--text);font-size:13px;font-weight:600;cursor:pointer;transition:all .15s}
.filter-toggle-btn.has-filters{border-color:var(--red);background:rgba(220,38,38,.07);color:var(--red)}
.filter-count-badge{background:var(--red);color:#fff;border-radius:10px;font-size:11px;font-weight:700;padding:1px 6px}
.clear-all-btn{display:inline-flex;align-items:center;gap:6px;padding:9px 16px;border:1px solid rgba(220,38,38,.3);border-radius:20px;background:rgba(220,38,38,.07);color:var(--red);font-size:13px;font-weight:600;cursor:pointer;text-decoration:none;transition:all .15s}
.topbar-results{font-size:13px;color:var(--text-muted);margin-left:auto}
.sort-select{padding:7px 12px;border:1px solid var(--border);border-radius:8px;background:var(--bg-card);color:var(--text);font-size:13px;cursor:pointer}
.filter-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:400}
.filter-overlay.open{display:block}
.filter-panel{position:fixed;top:0;left:-420px;width:400px;height:100vh;background:var(--bg-card);border-right:1px solid var(--border);z-index:401;display:flex;flex-direction:column;transition:left .3s cubic-bezier(.4,0,.2,1);overflow:hidden}
.filter-panel.open{left:0}
.fp-header{padding:18px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;flex-shrink:0}
.fp-close{background:none;border:none;cursor:pointer;color:var(--text-muted);font-size:22px;line-height:1;padding:0}
.fp-body{flex:1;overflow-y:auto}
.fp-section{border-bottom:1px solid var(--border);padding:18px 20px}
.fp-section-title{font-size:11px;font-weight:700;letter-spacing:.8px;text-transform:uppercase;color:var(--text-muted);margin-bottom:12px}
.dp-pill{padding:8px 16px;border-radius:20px;border:1px solid var(--border);font-size:13px;font-weight:500;cursor:pointer;background:transparent;color:var(--text-muted);transition:all .15s}
.dp-pill.on{background:var(--red);border-color:var(--red);color:#fff}
.cb-row{display:flex;align-items:center;gap:12px;padding:8px 6px;cursor:pointer;font-size:13px;border-radius:7px}
.cb-row input{accent-color:var(--red);width:16px;height:16px;cursor:pointer}
.fp-footer{padding:16px 20px;border-top:1px solid var(--border);display:flex;gap:10px;flex-shrink:0}
.fp-footer-clear{flex:1;padding:11px;background:transparent;color:var(--text-muted);border:1px solid var(--border);border-radius:10px;font-size:14px;font-weight:600;cursor:pointer}
.fp-footer-apply{flex:2;padding:11px;background:var(--red);color:#fff;border:none;border-radius:10px;font-size:14px;font-weight:600;cursor:pointer}
.t-card{background:var(--bg-card);border:1px solid var(--border);border-radius:14px;overflow:hidden;transition:all .2s;text-decoration:none;color:inherit;display:block}
.t-card:hover{border-color:rgba(220,38,38,.4);box-shadow:0 6px 28px rgba(220,38,38,.08);transform:translateY(-2px)}
.t-card-img{position:relative;height:200px;background:#111;overflow:hidden}
.t-card-img img{width:100%;height:100%;object-fit:cover;transition:transform .3s}
.t-card:hover .t-card-img img{transform:scale(1.03)}
.t-card-placeholder{width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:64px;background:linear-gradient(135deg,#1a1a1a,#222)}
.t-card-badge{position:absolute;top:10px;left:10px;padding:4px 10px;border-radius:20px;font-size:11px;font-weight:600}
.badge-avail{background:rgba(34,197,94,.15);color:#22c55e;border:1px solid rgba(34,197,94,.3)}
.badge-other{background:rgba(220,38,38,.15);color:var(--red);border:1px solid rgba(220,38,38,.3)}
.t-card-body{padding:14px 16px}
.t-card-name{font-size:17px;font-weight:700}
.t-card-year{font-size:13px;color:var(--text-muted);font-weight:400}
.t-card-location{font-size:12px;color:var(--text-muted);margin:4px 0 10px}
.t-card-specs{display:flex;gap:6px;flex-wrap:wrap;margin-bottom:12px}
.t-spec{font-size:11px;padding:3px 9px;border-radius:6px;background:var(--bg-input);color:var(--text-muted);border:1px solid var(--border)}
.t-card-foot{display:flex;align-items:flex-end;justify-content:space-between;padding-top:10px;border-top:1px solid var(--border)}
.t-price{font-size:20px;font-weight:700;color:var(--red)}
.t-price small{font-size:12px;color:var(--text-muted);font-weight:400}
.cars-wrap{max-width:1280px;margin:0 auto;padding:20px 24px 60px}
.cars-grid-3{display:grid;grid-template-columns:repeat(3,1fr);gap:20px}
@media(max-width:1100px){.cars-grid-3{grid-template-columns:repeat(2,1fr)}}
@media(max-width:680px){.cars-grid-3{grid-template-columns:1fr}}
.price-row{display:flex;justify-content:space-between;font-size:13px;margin-bottom:10px}
.price-row strong{color:var(--red);font-size:15px}
.af-tag{display:inline-flex;align-items:center;gap:5px;padding:5px 12px;background:rgba(220,38,38,.1);border:1px solid rgba(220,38,38,.25);border-radius:20px;font-size:12px;font-weight:500;color:var(--red)}
.af-tag button{background:none;border:none;color:var(--red);cursor:pointer;font-size:14px;line-height:1;padding:0}
</style>
@endpush
@section('content')
@php
    $activeCount = 0;
    if(request('max_price')) $activeCount++;
    $activeCount += count((array)request('types',[]));
    $activeCount += count((array)request('brands',[]));
    if(request('seats')) $activeCount++;
    $activeCount += count((array)request('fuels',[]));
    if(request('city')) $activeCount++;
    $hasFilters = $activeCount > 0;
@endphp

<div class="browse-topbar">
    <div class="browse-inner">
        <button class="filter-toggle-btn {{ $hasFilters?'has-filters':'' }}" onclick="openPanel()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><line x1="4" y1="6" x2="20" y2="6"/><line x1="8" y1="12" x2="16" y2="12"/><line x1="11" y1="18" x2="13" y2="18"/></svg>
            Filters @if($activeCount>0)<span class="filter-count-badge">{{ $activeCount }}</span>@endif
        </button>
        @if($hasFilters)<a href="{{ route('cars.index') }}" class="clear-all-btn">✕ Clear all</a>@endif
        <div class="topbar-results"><strong>{{ $cars->total() }}</strong> cars {{ $hasFilters?'found':'available' }}</div>
        <select class="sort-select" onchange="window.location=this.value">
            @php $q=request()->except(['sort','page']); @endphp
            <option value="{{ route('cars.index',array_merge($q,['sort'=>'newest'])) }}" {{ request('sort','newest')==='newest'?'selected':'' }}>Newest</option>
            <option value="{{ route('cars.index',array_merge($q,['sort'=>'price_asc'])) }}" {{ request('sort')==='price_asc'?'selected':'' }}>Price ↑</option>
            <option value="{{ route('cars.index',array_merge($q,['sort'=>'price_desc'])) }}" {{ request('sort')==='price_desc'?'selected':'' }}>Price ↓</option>
        </select>
    </div>
</div>

<div class="filter-overlay" id="filter-overlay" onclick="closePanel()"></div>
<div class="filter-panel" id="filter-panel">
    <div class="fp-header"><div style="font-size:16px;font-weight:700">Filters</div><button class="fp-close" onclick="closePanel()">×</button></div>
    <div class="fp-body">
        <div class="fp-section">
            <div class="fp-section-title">Max price / day</div>
            <div class="price-row"><span style="color:var(--text-muted)">$0</span><strong id="price-val">${{ request('max_price',$maxDbPrice) }}</strong></div>
            <input type="range" id="price-slider" min="0" max="{{ $maxDbPrice }}" value="{{ request('max_price',$maxDbPrice) }}" oninput="document.getElementById('price-val').textContent='$'+this.value"/>
        </div>
        <div class="fp-section">
            <div class="fp-section-title">City</div>
            <div style="display:flex;flex-direction:column;gap:2px">
                <label class="cb-row"><input type="radio" name="fp_city" value="" {{ !request('city')?'checked':'' }} onchange="pendingCity=''"/> All cities</label>
                @foreach($cities as $c)<label class="cb-row"><input type="radio" name="fp_city" value="{{ $c }}" {{ request('city')===$c?'checked':'' }} onchange="pendingCity=this.value"/> 📍 {{ $c }}</label>@endforeach
            </div>
        </div>
        <div class="fp-section">
            <div class="fp-section-title">Vehicle type</div>
            <div style="display:flex;flex-wrap:wrap;gap:8px">
                @foreach($types as $t)<button type="button" class="dp-pill {{ in_array($t,(array)request('types',[]))?'on':'' }}" data-group="types" data-value="{{ $t }}" onclick="toggleDpPill(this)">{{ $t }}</button>@endforeach
            </div>
        </div>
        <div class="fp-section">
            <div class="fp-section-title">Brand</div>
            <div style="display:flex;flex-direction:column;gap:2px">
                @foreach($brands as $b)<label class="cb-row"><input type="checkbox" data-group="brands" value="{{ $b }}" {{ in_array($b,(array)request('brands',[]))?'checked':'' }}/> {{ $b }}</label>@endforeach
            </div>
        </div>
        <div class="fp-section">
            <div class="fp-section-title">Number of seats</div>
            <div style="display:flex;flex-wrap:wrap;gap:8px">
                @foreach(['2-4'=>'2–4 seats','5'=>'5 seats','7+'=>'7+ seats'] as $val=>$label)
                <button type="button" class="dp-pill {{ request('seats')===$val?'on':'' }}" data-group="seats" data-value="{{ $val }}" onclick="toggleSinglePill(this)">{{ $label }}</button>
                @endforeach
            </div>
        </div>
        <div class="fp-section">
            <div class="fp-section-title">Fuel type</div>
            <div style="display:flex;flex-wrap:wrap;gap:8px">
                @foreach(['Petrol','Diesel','Hybrid','Electric'] as $f)
                <button type="button" class="dp-pill {{ in_array($f,(array)request('fuels',[]))?'on':'' }}" data-group="fuels" data-value="{{ $f }}" onclick="toggleDpPill(this)">{{ $f }}</button>
                @endforeach
            </div>
        </div>
    </div>
    <div class="fp-footer">
        <button class="fp-footer-clear" onclick="window.location='{{ route('cars.index') }}'">Clear all</button>
        <button class="fp-footer-apply" onclick="applyAll()">Show results</button>
    </div>
</div>

@if($hasFilters)
<div style="max-width:1280px;margin:0 auto;padding:10px 24px 0;display:flex;align-items:center;gap:8px;flex-wrap:wrap">
    @if(request('max_price'))<span class="af-tag">💲 Max ${{ request('max_price') }}/day <button onclick="removeFilter('max_price')">×</button></span>@endif
    @foreach((array)request('types',[]) as $t)<span class="af-tag">🚘 {{ $t }} <button onclick="removeArrayFilter('types','{{ $t }}')">×</button></span>@endforeach
    @foreach((array)request('brands',[]) as $b)<span class="af-tag">🏷️ {{ $b }} <button onclick="removeArrayFilter('brands','{{ $b }}')">×</button></span>@endforeach
    @if(request('seats'))<span class="af-tag">💺 {{ request('seats') }} seats <button onclick="removeFilter('seats')">×</button></span>@endif
    @foreach((array)request('fuels',[]) as $f)<span class="af-tag">⛽ {{ $f }} <button onclick="removeArrayFilter('fuels','{{ $f }}')">×</button></span>@endforeach
    @if(request('city'))<span class="af-tag">📍 {{ request('city') }} <button onclick="removeFilter('city')">×</button></span>@endif
</div>
@endif

<div class="cars-wrap">
    @if($cars->isEmpty())
        <div style="text-align:center;padding:80px 20px"><div style="font-size:52px;margin-bottom:16px">🔍</div><h3 style="font-size:18px;font-weight:600;margin-bottom:8px">No cars match your filters</h3><p style="color:var(--text-muted)">Try adjusting or clearing your filters.</p><a href="{{ route('cars.index') }}" class="btn btn-outline" style="margin-top:16px;display:inline-block">Clear all filters</a></div>
    @else
        <div class="cars-grid-3">
            @foreach($cars as $car)
            <a href="{{ route('cars.show',$car) }}" class="t-card">
                <div class="t-card-img">
                    @if($car->image)<img src="{{ asset('storage/'.$car->image) }}" alt="{{ $car->brand }} {{ $car->model }}">
                    @else<div class="t-card-placeholder">🚗</div>@endif
                    <span class="t-card-badge {{ $car->status==='Available'?'badge-avail':'badge-other' }}">{{ $car->status==='Available'?'✓ Available':$car->status }}</span>
                </div>
                <div class="t-card-body">
                    <div class="t-card-name">{{ $car->brand }} {{ $car->model }} <span class="t-card-year">· {{ $car->year }}</span></div>
                    <div class="t-card-location">📍 {{ $car->city }}</div>
                    <div class="t-card-specs">
                        <span class="t-spec">💺 {{ $car->car_seat }} seats</span>
                        <span class="t-spec">⛽ {{ $car->fuel_type }}</span>
                        <span class="t-spec">🚘 {{ $car->type }}</span>
                    </div>
                    <div class="t-card-foot"><div class="t-price">${{ number_format($car->price_per_day,0) }}<small>/day</small></div>@if($car->license_plate)<span style="font-size:11px;color:var(--text-muted)">🪪 {{ $car->license_plate }}</span>@endif</div>
                </div>
            </a>
            @endforeach
        </div>
        <div style="margin-top:32px">{{ $cars->links() }}</div>
    @endif
</div>
@endsection
@push('scripts')
<script>
const baseUrl='{{ route("cars.index") }}';
let pendingCity='{{ request("city","") }}';
function openPanel(){document.getElementById('filter-panel').classList.add('open');document.getElementById('filter-overlay').classList.add('open');document.body.style.overflow='hidden';}
function closePanel(){document.getElementById('filter-panel').classList.remove('open');document.getElementById('filter-overlay').classList.remove('open');document.body.style.overflow='';}
function toggleDpPill(btn){btn.classList.toggle('on');}
function toggleSinglePill(btn){btn.closest('div').querySelectorAll('.dp-pill').forEach(p=>p.classList.remove('on'));btn.classList.add('on');}
function applyAll(){
    const params=new URLSearchParams(window.location.search);params.delete('page');
    const price=document.getElementById('price-slider').value;
    if(price&&parseInt(price)<{{ $maxDbPrice }}){params.set('max_price',price);}else{params.delete('max_price');}
    if(pendingCity){params.set('city',pendingCity);}else{params.delete('city');}
    ['types','fuels'].forEach(group=>{params.delete(group+'[]');document.querySelectorAll(`[data-group="${group}"].on`).forEach(el=>{params.append(group+'[]',el.dataset.value);});});
    params.delete('brands[]');document.querySelectorAll('input[data-group="brands"]:checked').forEach(el=>{params.append('brands[]',el.value);});
    params.delete('seats');const seatOn=document.querySelector('[data-group="seats"].on');if(seatOn){params.set('seats',seatOn.dataset.value);}
    window.location=baseUrl+'?'+params.toString();
}
function removeFilter(name){const params=new URLSearchParams(window.location.search);params.delete(name);params.delete('page');window.location=baseUrl+'?'+params.toString();}
function removeArrayFilter(name,value){const params=new URLSearchParams(window.location.search);const key=name+'[]';const remaining=params.getAll(key).filter(v=>v!==value);params.delete(key);remaining.forEach(v=>params.append(key,v));params.delete('page');window.location=baseUrl+'?'+params.toString();}
document.addEventListener('keydown',e=>{if(e.key==='Escape')closePanel();});
</script>
@endpush
