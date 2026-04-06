@extends('layouts.app')
@section('title','Checkout')
@push('styles')
<style>
.co-layout{display:grid;grid-template-columns:1fr 360px;gap:26px;max-width:1100px;margin:0 auto;padding:32px 24px;align-items:start}
.co-section{background:var(--bg-card);border:1px solid var(--border);border-radius:14px;margin-bottom:18px;overflow:hidden}
.co-head{padding:14px 18px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:10px}
.co-num{width:24px;height:24px;border-radius:50%;background:var(--red);display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:#fff;flex-shrink:0}
.co-title{font-family:'Barlow Condensed',sans-serif;font-size:15px;font-weight:700;letter-spacing:.5px}
.co-body{padding:18px}
.g2{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.g3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px}
.trip-detail-grid{display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;padding:12px;background:var(--bg-input);border:1px solid var(--border);border-radius:var(--radius-sm)}
.tdl{font-size:10px;font-weight:700;color:var(--text-dim);text-transform:uppercase;letter-spacing:.5px}
.tdv{font-size:13px;font-weight:600}
.pickup-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:12px}
.pickup-opt{border:1px solid var(--border);border-radius:var(--radius-sm);padding:12px;cursor:pointer;transition:var(--transition);display:flex;gap:10px;align-items:flex-start}
.pickup-opt.sel{border-color:var(--red);background:var(--red-glow)}
.rate-card{border:1px solid var(--border);border-radius:var(--radius-sm);padding:14px;cursor:pointer;transition:var(--transition);display:flex;gap:12px;align-items:flex-start;margin-bottom:10px}
.rate-card.sel{border-color:var(--red);background:var(--red-glow)}
.sum-panel{position:sticky;top:80px;background:var(--bg-card);border:1px solid var(--border);border-radius:14px;overflow:hidden}
.sum-row{display:flex;justify-content:space-between;font-size:13px;margin-bottom:7px;color:var(--text-muted)}
.sum-row strong{color:var(--text)}
.sum-total{display:flex;justify-content:space-between;font-weight:700;font-size:15px;padding:10px 0 0;border-top:1px solid var(--border);margin-top:6px}
.sum-total-price{font-family:'Barlow Condensed',sans-serif;font-size:22px;font-weight:800;color:var(--red)}
@media(max-width:900px){.co-layout{grid-template-columns:1fr;}}
</style>
@endpush
@section('content')
<div style="border-bottom:1px solid var(--border);padding:12px 24px">
    <div style="max-width:1100px;margin:0 auto;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
        <div style="font-size:12px;color:var(--text-muted)">
            <a href="{{ route('home') }}" class="text-red">Home</a> / <a href="{{ route('cars.index') }}" class="text-red">Cars</a> / <a href="{{ route('cars.show',$car) }}" class="text-red">{{ $car->name }}</a> / Checkout
        </div>
        <div class="steps">
            <div class="step-item done"><div class="step-num">✓</div><span>Select Car</span></div>
            <div class="step-line"></div>
            <div class="step-item active"><div class="step-num">2</div><span>Checkout</span></div>
            <div class="step-line"></div>
            <div class="step-item"><div class="step-num">3</div><span>Confirmed</span></div>
        </div>
    </div>
</div>
<form action="{{ route('booking.store',$car) }}" method="POST" id="booking-form">
@csrf
<input type="hidden" name="start_date" value="{{ $startDate }}">
<input type="hidden" name="end_date" value="{{ $endDate }}">
<input type="hidden" name="start_time" value="{{ $startTime }}">
<input type="hidden" name="end_time" value="{{ $endTime }}">
<div class="co-layout">
    <div>
        <div class="co-section">
            <div class="co-head"><div class="co-num">1</div><div class="co-title">TRIP SUMMARY</div></div>
            <div class="co-body">
                <div style="display:flex;gap:14px;align-items:center;margin-bottom:16px">
                    <div style="width:72px;height:58px;border-radius:10px;overflow:hidden;flex-shrink:0;background:#1a1a1a">
                        @if($car->image)<img src="{{ asset('storage/'.$car->image) }}" style="width:100%;height:100%;object-fit:cover">@else<div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:32px">🚗</div>@endif
                    </div>
                    <div>
                        <div style="font-family:'Barlow Condensed',sans-serif;font-size:20px;font-weight:700">{{ $car->brand }} {{ $car->model }} <span style="font-size:14px;color:var(--text-muted);font-weight:400">{{ $car->year }}</span></div>
                        <div style="font-size:13px;color:var(--text-muted)">📍 {{ $car->city }}</div>
                    </div>
                </div>
                <div class="trip-detail-grid">
                    <div><div class="tdl">Pick-up</div><div class="tdv">{{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }}</div><div style="font-size:12px;color:var(--text-muted)">{{ $startTime }}</div></div>
                    <div><div class="tdl">Return</div><div class="tdv">{{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}</div><div style="font-size:12px;color:var(--text-muted)">{{ $endTime }}</div></div>
                    <div><div class="tdl">Duration</div><div class="tdv">{{ $days }} day{{ $days>1?'s':'' }}</div><div style="font-size:12px;color:var(--green)">${{ number_format($car->price_per_day,0) }}/day</div></div>
                </div>
                <div style="margin-top:14px">
                    <div style="font-size:10px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px">PICK-UP / DROP-OFF OPTION</div>
                    <div class="pickup-grid">
                        <div class="pickup-opt sel" onclick="selPickup(this,'self')"><input type="radio" name="pickup_option" value="self" checked/><div><div style="font-weight:600;font-size:13px;margin-bottom:2px">🔑 Self Pick-up</div><div style="font-size:12px;color:var(--text-muted)">Pick up from host. Free.</div></div></div>
                        <div class="pickup-opt" onclick="selPickup(this,'delivery')"><input type="radio" name="pickup_option" value="delivery"/><div><div style="font-weight:600;font-size:13px;margin-bottom:2px">🚚 Delivery to Me</div><div style="font-size:12px;color:var(--text-muted)">Host delivers. +$10 fee.</div></div></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="co-section">
            <div class="co-head"><div class="co-num">2</div><div class="co-title">PRIMARY DRIVER</div></div>
            <div class="co-body">
                <div class="g2" style="margin-bottom:16px">
                    <div><label class="form-label">First Name *</label><input type="text" name="driver_first_name" class="form-control" value="{{ old('driver_first_name', explode(' ', auth()->user()->name)[0] ?? '') }}" required/></div>
                    <div><label class="form-label">Last Name *</label><input type="text" name="driver_last_name" class="form-control" value="{{ old('driver_last_name', explode(' ', auth()->user()->name)[1] ?? '') }}"/></div>
                </div>
                <div class="g2" style="margin-bottom:16px">
                    <div><label class="form-label">📱 Phone Number *</label><input type="tel" name="driver_phone" class="form-control" value="{{ old('driver_phone', auth()->user()->phone ?? '') }}" required/></div>
                    <div><label class="form-label">✉️ Email *</label><input type="email" name="driver_email" class="form-control" value="{{ old('driver_email', auth()->user()->email) }}" required/></div>
                </div>
                <div class="g2">
                    <div><label class="form-label">🪪 Driver License No. *</label><input type="text" name="driver_license" class="form-control" value="{{ old('driver_license') }}" required/></div>
                    <div><label class="form-label">License Expiry *</label><input type="date" name="driver_license_expiry" class="form-control" value="{{ old('driver_license_expiry') }}" min="{{ now()->addDay()->format('Y-m-d') }}" required/></div>
                </div>
            </div>
        </div>
        <div class="co-section">
            <div class="co-head"><div class="co-num">3</div><div class="co-title">BOOKING RATE</div></div>
            <div class="co-body">
                <div class="rate-card sel" onclick="selRate(this,'non_refundable',0)"><input type="radio" name="rate_type" value="non_refundable" checked/><div style="flex:1"><div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:4px"><span style="font-weight:600;font-size:14px">Non-Refundable Rate</span><span class="badge badge-green">Save $15</span></div><div style="font-size:12px;color:var(--text-muted);line-height:1.5">Best price — no cancellations after confirmation.</div></div></div>
                <div class="rate-card" onclick="selRate(this,'refundable',15)"><input type="radio" name="rate_type" value="refundable"/><div style="flex:1"><div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:4px"><span style="font-weight:600;font-size:14px">Refundable Rate</span><span style="font-family:'Barlow Condensed',sans-serif;font-size:15px;font-weight:700;color:var(--red)">+$15</span></div><div style="font-size:12px;color:var(--text-muted);line-height:1.5">Free cancellation up to 24hrs before trip.</div></div></div>
            </div>
        </div>
        <div class="co-section">
            <div class="co-head"><div class="co-num">4</div><div class="co-title">PAYMENT METHOD</div></div>
            <div class="co-body">
                <input type="hidden" name="payment_method" id="pay-method" value="card"/>
                <div style="margin-bottom:12px;padding:12px;background:var(--bg-input);border:1px solid var(--border);border-radius:8px;display:flex;align-items:center;gap:10px"><span style="font-size:20px">💳</span><span style="font-size:13px;color:var(--text-muted)">Demo mode — no real payment processed</span></div>
                <div style="margin-bottom:16px"><label class="form-label">Card Number</label><input type="text" class="form-control" placeholder="4242 4242 4242 4242" oninput="let v=this.value.replace(/\D/g,'').substring(0,16);this.value=v.replace(/(.{4})/g,'$1  ').trim()" maxlength="19"/></div>
                <div class="g3" style="margin-bottom:16px">
                    <div style="grid-column:span 2"><label class="form-label">Expiration</label><input type="text" class="form-control" placeholder="MM / YY" maxlength="7"/></div>
                    <div><label class="form-label">CVV</label><input type="text" class="form-control" placeholder="123" maxlength="4"/></div>
                </div>
                <div><label class="form-label">Name on Card</label><input type="text" class="form-control" placeholder="{{ auth()->user()->name }}"/></div>
            </div>
        </div>
    </div>
    <div>
        <div class="sum-panel">
            <div style="padding:14px 18px;border-bottom:1px solid var(--border);background:linear-gradient(135deg,var(--bg-card),#1e0800)">
                <div style="font-family:'Barlow Condensed',sans-serif;font-size:17px;font-weight:700;letter-spacing:.5px">ORDER SUMMARY</div>
            </div>
            <div style="padding:16px 18px">
                <div style="display:flex;gap:12px;align-items:center;padding-bottom:14px;border-bottom:1px solid var(--border);margin-bottom:14px">
                    <div style="width:72px;height:58px;border-radius:10px;overflow:hidden;flex-shrink:0;background:#1a1a1a">
                        @if($car->image)<img src="{{ asset('storage/'.$car->image) }}" style="width:100%;height:100%;object-fit:cover">@else<div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:32px">🚗</div>@endif
                    </div>
                    <div><div style="font-weight:700;font-size:14px">{{ $car->name }} {{ $car->year }}</div><div style="font-size:12px;color:var(--text-muted)">📍 {{ $car->city }}</div></div>
                </div>
                <div class="sum-row"><span>${{ number_format($car->price_per_day,0) }} × {{ $days }} day{{ $days>1?'s':'' }}</span><strong>${{ number_format($subtotal,0) }}</strong></div>
                <div class="sum-row"><span>Service fee (10%)</span><strong>${{ number_format($serviceFee,0) }}</strong></div>
                <div class="sum-row" id="sum-delivery" style="display:none"><span>Delivery fee</span><strong>+$10</strong></div>
                <div class="sum-row" id="sum-refund" style="display:none"><span>Refundable upgrade</span><strong>+$15</strong></div>
                <div class="sum-total"><span>Total Due</span><span class="sum-total-price" id="sum-total">${{ number_format($baseTotal,0) }}</span></div>
                <div style="height:1px;background:var(--border);margin:14px 0"></div>
                <div style="font-size:12px;color:var(--text-muted);line-height:1.8">
                    <div>📅 {{ \Carbon\Carbon::parse($startDate)->format('M d') }} – {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}</div>
                    <div id="sum-pickup-text">📍 Self Pick-up</div>
                    <div style="margin-top:6px">By confirming you agree to DriveFlow's <span class="text-red" style="cursor:pointer">Terms of Service</span>.</div>
                </div>
                <div style="height:1px;background:var(--border);margin:14px 0"></div>
                <button type="submit" class="btn btn-primary btn-full btn-lg">🔒 Confirm &amp; Pay <span id="btn-total">${{ number_format($baseTotal,0) }}</span></button>
                <a href="{{ route('cars.show',$car) }}" class="btn btn-ghost btn-full" style="margin-top:8px">← Back to Car</a>
                <div style="display:flex;gap:8px;margin-top:16px;flex-wrap:wrap"><div style="font-size:11px;color:var(--text-dim)">🔒 Secure Checkout</div><div style="font-size:11px;color:var(--text-dim)">🛡 Buyer Protection</div></div>
            </div>
        </div>
    </div>
</div>
</form>
@endsection
@push('scripts')
<script>
const BASE={{ $baseTotal }};let delivery=0;let rateExtra=0;
function calcTotal(){const t=BASE+delivery+rateExtra;document.getElementById('sum-total').textContent='$'+t;document.getElementById('btn-total').textContent='$'+t;}
function selPickup(el,type){document.querySelectorAll('.pickup-opt').forEach(p=>p.classList.remove('sel'));el.classList.add('sel');el.querySelector('input[type=radio]').checked=true;delivery=type==='delivery'?10:0;document.getElementById('sum-delivery').style.display=delivery?'flex':'none';document.getElementById('sum-pickup-text').textContent=delivery?'📍 Delivery to you (+$10)':'📍 Self Pick-up';calcTotal();}
function selRate(el,type,extra){document.querySelectorAll('.rate-card').forEach(c=>c.classList.remove('sel'));el.classList.add('sel');el.querySelector('input[type=radio]').checked=true;rateExtra=extra;document.getElementById('sum-refund').style.display=extra?'flex':'none';calcTotal();}
</script>
@endpush
