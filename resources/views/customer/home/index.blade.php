@extends('layouts.app')
@section('title','Rent a Car')
@push('styles')
<style>
.hero{position:relative;min-height:520px;display:flex;align-items:center;overflow:hidden;background:linear-gradient(135deg,#0c0c0c 0%,#1a0800 60%,#0c0c0c 100%)}
.hero-bg{position:absolute;inset:0;background:radial-gradient(ellipse 60% 50% at 70% 50%,rgba(220,38,38,.12) 0%,transparent 70%)}
.hero-content{position:relative;z-index:1;max-width:1200px;margin:0 auto;padding:80px 24px;width:100%}
.hero-title{font-family:'Barlow Condensed',sans-serif;font-size:68px;font-weight:800;line-height:.95;letter-spacing:-1px;margin-bottom:16px}
.hero-title span{color:var(--red)}
.search-box{background:rgba(20,20,20,.95);border:1px solid var(--border);border-radius:14px;padding:20px 24px;max-width:780px;box-shadow:0 8px 48px rgba(0,0,0,.6)}
.search-grid{display:grid;grid-template-columns:1fr 1fr 1fr auto;gap:12px;align-items:end}
.stats-bar{display:flex;gap:40px;padding:20px 0;margin-top:32px}
.stat-item{display:flex;flex-direction:column;gap:2px}
.stat-big{font-family:'Barlow Condensed',sans-serif;font-size:28px;font-weight:700;color:var(--white)}
.stat-big span{color:var(--red)}
.stat-lbl{font-size:12px;color:var(--text-muted)}
.stat-sep{width:1px;background:var(--border)}
.city-tabs{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:28px}
.city-tab{padding:8px 20px;border-radius:100px;border:1px solid var(--border);font-size:13px;font-weight:500;color:var(--text-muted);text-decoration:none;display:inline-block;transition:all .15s}
.city-tab:hover{color:var(--text);border-color:var(--border-hover)}
.city-tab.active{background:var(--red);border-color:var(--red);color:#fff}
.why-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px}
.why-card{background:var(--bg-card);border:1px solid var(--border);border-radius:12px;padding:24px;text-align:center;transition:var(--transition)}
.why-card:hover{border-color:var(--red)}
.input-lbl{display:block;font-size:10px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--text-muted);margin-bottom:6px}
.inp{display:block;width:100%;background:var(--bg-input);border:1px solid var(--border);color:var(--text);padding:9px 12px;font-size:13px;border-radius:6px;-webkit-appearance:none;appearance:none}
.inp:focus{outline:none;border-color:var(--red)}
select.inp{background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%23737373' stroke-width='1.5' fill='none'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 10px center;padding-right:30px}
@media(max-width:700px){.search-grid{grid-template-columns:1fr;}.why-grid{grid-template-columns:1fr 1fr;}.stats-bar{gap:20px;}.hero-title{font-size:42px;}}
</style>
@endpush
@section('content')
<section class="hero">
    <div class="hero-bg"></div>
    <div class="hero-content">
        <div style="display:inline-flex;align-items:center;gap:8px;font-size:11px;letter-spacing:2px;font-weight:600;color:var(--red);text-transform:uppercase;margin-bottom:16px;padding:5px 12px;background:var(--red-glow);border:1px solid rgba(220,38,38,.2);border-radius:100px">⚡ Premium Fleet Rental</div>
        <h1 class="hero-title">RENT YOUR<br>PERFECT <span>RIDE</span></h1>
        <p style="color:var(--text-muted);font-size:16px;max-width:420px;margin-bottom:32px;line-height:1.6">Access premium vehicles across Cambodia's major cities. Book in minutes, drive with confidence.</p>
        <form action="{{ route('cars.index') }}" method="GET">
            <div class="search-box">
                <div class="search-grid">
                    <div><label class="input-lbl">📍 Pick-up Location</label><select name="city" class="inp"><option value="">All Cities</option>@foreach($cities as $city)<option value="{{ $city }}">{{ $city }}</option>@endforeach</select></div>
                    <div><label class="input-lbl">📅 Start Date</label><input type="date" name="start" class="inp" value="{{ now()->format('Y-m-d') }}" min="{{ now()->format('Y-m-d') }}"/></div>
                    <div><label class="input-lbl">📅 End Date</label><input type="date" name="end" class="inp" value="{{ now()->addDay()->format('Y-m-d') }}" min="{{ now()->addDay()->format('Y-m-d') }}"/></div>
                    <button type="submit" class="btn btn-primary" style="padding:10px 20px">Search</button>
                </div>
            </div>
        </form>
        <div class="stats-bar">
            <div class="stat-item"><div class="stat-big">{{ $stats['total_cars'] }}<span>+</span></div><div class="stat-lbl">Vehicles Available</div></div>
            <div class="stat-sep"></div>
            <div class="stat-item"><div class="stat-big">{{ $stats['total_cities'] }}<span> Cities</span></div><div class="stat-lbl">Across Cambodia</div></div>
            <div class="stat-sep"></div>
            <div class="stat-item"><div class="stat-big">{{ $stats['avg_rating'] ?: '4.8' }}<span>★</span></div><div class="stat-lbl">Average Rating</div></div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="flex items-center justify-between mb-24">
            <div><h2 class="section-title">Available Cars</h2><p class="section-sub">Live from the DriveFlow fleet database</p></div>
            <a href="{{ route('cars.index') }}" class="btn btn-outline">View All →</a>
        </div>
        <div class="city-tabs">
            <a href="{{ route('home') }}" class="city-tab {{ !request('city')?'active':'' }}">All Cities</a>
            @foreach($cities as $city)<a href="{{ route('home',['city'=>$city]) }}" class="city-tab {{ request('city')===$city?'active':'' }}">📍 {{ $city }}</a>@endforeach
        </div>
        @php $display = request('city') ? $featuredCars->filter(fn($c)=>$c->city===request('city')) : $featuredCars; @endphp
        @if($display->isEmpty())
            <div class="empty-state"><div style="font-size:48px;margin-bottom:12px">🚗</div><div style="font-size:17px;font-weight:600">No cars available in {{ request('city') }}</div></div>
        @else
            <div class="cars-grid">@foreach($display as $car)@include('customer.cars._card',['car'=>$car])@endforeach</div>
        @endif
    </div>
</section>

<section class="section" style="background:var(--bg-card);border-top:1px solid var(--border);border-bottom:1px solid var(--border)">
    <div class="container">
        <div style="text-align:center;margin-bottom:40px"><h2 class="section-title">Why Choose <span style="color:var(--red)">DriveFlow</span>?</h2><p class="section-sub">The most trusted fleet rental platform in Cambodia</p></div>
        <div class="why-grid">
            @foreach([['🛡️','Fully Insured','Every rental includes comprehensive coverage'],['⚡','Instant Booking','Book in under 2 minutes, no paperwork'],['🏎️','Premium Fleet','Carefully maintained vehicles from trusted hosts'],['📱','24/7 Support','Our team is always here to help']] as [$i,$t,$d])
            <div class="why-card"><div style="font-size:36px;margin-bottom:12px">{{ $i }}</div><div style="font-weight:600;margin-bottom:6px">{{ $t }}</div><div style="font-size:13px;color:var(--text-muted);line-height:1.5">{{ $d }}</div></div>
            @endforeach
        </div>
    </div>
</section>
@endsection
