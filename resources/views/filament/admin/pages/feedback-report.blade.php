<x-filament-panels::page>
<style>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap');

:root {
    --ink: #0f1117; --ink2: #374151; --muted: #9ca3af; --line: #e5e7eb; --bg: #f8f9fb; --w: #fff;
    --ind: #4f46e5; --teal: #0d9488; --amb: #d97706; --rose: #e11d48; --grn: #16a34a; --sky: #0284c7;
}
*, *::before, *::after { box-sizing: border-box; }
.fd { font-family: 'Outfit', sans-serif; color: var(--ink); }

/* ── Filter bar ─────────────────────────────────── */
.fbar { background: var(--w); border: 1px solid var(--line); border-radius: 14px; padding: 16px 20px; margin-bottom: 16px; }
.fgrid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 10px; }
.flbl { font-size: 9.5px; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; color: var(--muted); margin-bottom: 5px; }
.fsel {
    width: 100%; 
    padding: 8px 30px 8px 10px; /* Increased right padding so text doesn't hit the arrow */
    border: 1.5px solid var(--line); 
    border-radius: 8px;
    font-family: 'Outfit', sans-serif; 
    font-size: 12.5px; 
    color: var(--ink);
    
    /* THE FIX IS HERE */
    background-color: var(--bg); 
    background-repeat: no-repeat;
    background-position: right 10px center;
    background-size: 16px 12px;
    appearance: none; 
    -webkit-appearance: none;
    
    outline: none; 
    transition: border-color .15s; 
    cursor: pointer;
}
.fsel:focus { border-color: var(--ind); background: var(--w); }

/* ── Stat cards ──────────────────────────────────── */
.stats { display: grid; grid-template-columns: repeat(5, 1fr); gap: 12px; margin-bottom: 16px; }
.stat { background: var(--w); border: 1px solid var(--line); border-radius: 12px; padding: 14px 16px; position: relative; overflow: hidden; transition: .15s; }
.stat::after { content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 3px; background: var(--ac, var(--ind)); }
.stat:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,.07); }
.s-lbl { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: var(--muted); margin-bottom: 4px; }
.s-val { font-size: 26px; font-weight: 800; color: var(--ink); line-height: 1; }
.s-sub { font-size: 10px; color: var(--muted); margin-top: 2px; }

/* ── Tabs ────────────────────────────────────────── */
.tabs { display: flex; border-bottom: 2px solid var(--line); margin-bottom: 18px; gap: 0; }
.tab-btn {
    padding: 10px 22px; font-size: 13px; font-weight: 600; color: var(--muted); cursor: pointer;
    border: none; border-bottom: 2.5px solid transparent; margin-bottom: -2px;
    background: none; font-family: 'Outfit', sans-serif; transition: .15s;
}
.tab-btn.on { color: var(--ind); border-bottom-color: var(--ind); }
.tab-btn:hover:not(.on) { color: var(--ink2); }
.tab-panel { display: none; }
.tab-panel.on { display: block; }

/* ── Cards ───────────────────────────────────────── */
.card { background: var(--w); border: 1px solid var(--line); border-radius: 14px; overflow: hidden; margin-bottom: 16px; }
.card-hd { padding: 13px 20px; border-bottom: 1px solid var(--line); display: flex; align-items: center; justify-content: space-between; gap: 10px; flex-wrap: wrap; }
.card-title { font-size: 13px; font-weight: 700; color: var(--ink); margin: 0; }
.card-body { padding: 18px; }

/* ── Buttons ─────────────────────────────────────── */
.btn { display: inline-flex; align-items: center; gap: 5px; padding: 7px 13px; border-radius: 8px; border: none; font-size: 12px; font-weight: 600; cursor: pointer; font-family: 'Outfit', sans-serif; transition: all .15s; }
.btn-dk { background: var(--ink); color: #fff; } .btn-dk:hover { background: #1f2937; }
.btn-in { background: var(--ind); color: #fff; } .btn-in:hover { background: #4338ca; }
.btn-tl { background: var(--teal); color: #fff; } .btn-tl:hover { background: #0f766e; }
.btn-ol { background: var(--w); border: 1.5px solid var(--line); color: var(--ink2); } .btn-ol:hover { border-color: var(--ink); color: var(--ink); }
.btn-sm { padding: 4px 9px; font-size: 11px; border-radius: 6px; }
.btn:disabled { opacity: .5; cursor: not-allowed; }

/* ── Search ──────────────────────────────────────── */
.srw { position: relative; width: 200px; }
.srin {
    width: 100%; padding: 7px 10px 7px 30px; border: 1.5px solid var(--line); border-radius: 8px;
    font-size: 12px; font-family: 'Outfit', sans-serif; color: var(--ink); background: var(--bg); outline: none; transition: .15s;
}
.srin:focus { border-color: var(--ind); background: var(--w); }
.sric { position: absolute; left: 9px; top: 50%; transform: translateY(-50%); width: 12px; height: 12px; color: var(--muted); pointer-events: none; }

/* ── Table ───────────────────────────────────────── */
.dt { width: 100%; border-collapse: collapse; font-size: 12.5px; }
.dt th { padding: 8px 11px; text-align: left; font-size: 9px; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; color: var(--muted); border-bottom: 1.5px solid var(--line); background: #f9fafb; white-space: nowrap; }
.dt td { padding: 9px 11px; color: var(--ink2); border-bottom: 1px solid #f3f4f6; vertical-align: middle; }
.dt tbody tr:hover { background: #fafafa; }
.dt tbody tr:last-child td { border-bottom: none; }

/* ── Chips ───────────────────────────────────────── */
.chip { display: inline-flex; padding: 2px 7px; border-radius: 99px; font-size: 9.5px; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; }
.c-grn { background: #dcfce7; color: #15803d; }
.c-blu { background: #dbeafe; color: #1d4ed8; }
.c-amb { background: #fef3c7; color: #b45309; }
.c-red { background: #fee2e2; color: #dc2626; }

/* ── Score colours ───────────────────────────────── */
.sc-h { color: #16a34a; font-weight: 700; }
.sc-m { color: #d97706; font-weight: 700; }
.sc-l { color: #e11d48; font-weight: 700; }

/* ── Metric pills ────────────────────────────────── */
.mpill { display: flex; flex-direction: column; align-items: center; gap: 3px; padding: 12px 8px; background: #f9fafb; border: 1px solid var(--line); border-radius: 10px; text-align: center; }
.mpv { font-size: 20px; font-weight: 800; line-height: 1; font-family: 'JetBrains Mono', monospace; }
.mpl { font-size: 8px; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; color: var(--muted); }
.mpbar { width: 100%; background: #e5e7eb; border-radius: 99px; height: 3px; margin-top: 3px; overflow: hidden; }
.mpfill { height: 3px; border-radius: 99px; display: block; }

/* ── Progress bar ────────────────────────────────── */
.pb { background: #f3f4f6; border-radius: 99px; height: 5px; overflow: hidden; min-width: 60px; }
.pbf { height: 5px; border-radius: 99px; background: linear-gradient(90deg, var(--ind), #818cf8); display: block; }

/* ── Centre tiles ────────────────────────────────── */
.tiles { display: grid; grid-template-columns: repeat(auto-fill, minmax(272px, 1fr)); gap: 14px; }
.tile {
    background: var(--w); border: 1.5px solid var(--line); border-radius: 16px; padding: 18px;
    cursor: pointer; position: relative; overflow: hidden; transition: all .2s;
}
.tile::before { content: ''; position: absolute; top: 0; left: 0; bottom: 0; width: 4px; background: var(--tc, var(--ind)); transition: width .2s; }
.tile:hover { border-color: var(--tc, var(--ind)); box-shadow: 0 8px 24px rgba(0,0,0,.09); transform: translateY(-2px); }
.tile:hover::before { width: 6px; }
.tile-name { font-size: 13px; font-weight: 700; color: var(--ink); padding-left: 9px; flex: 1; }
.tile-badge { font-size: 18px; font-weight: 800; font-family: 'JetBrains Mono', monospace; padding: 3px 8px; border-radius: 7px; white-space: nowrap; }
.tile-mets { display: grid; grid-template-columns: repeat(3, 1fr); gap: 6px; margin: 10px 0; }
.tile-met .v { font-size: 14px; font-weight: 700; color: var(--ink); text-align: center; }
.tile-met .l { font-size: 8px; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; color: var(--muted); text-align: center; }
.tile-bar { height: 4px; background: #f3f4f6; border-radius: 99px; overflow: hidden; margin-bottom: 8px; }
.tile-bar-f { height: 4px; border-radius: 99px; display: block; transition: width .6s ease; }

/* ── Drill header ────────────────────────────────── */
.dhd { background: linear-gradient(135deg, #0f1117, #1e293b); border-radius: 16px; padding: 22px 24px; color: #fff; margin-bottom: 14px; }
.dscores { display: flex; gap: 16px; flex-wrap: wrap; margin-top: 12px; }
.dscore .dv { font-size: 22px; font-weight: 800; font-family: 'JetBrains Mono', monospace; }
.dscore .dl { font-size: 9px; font-weight: 600; letter-spacing: .08em; text-transform: uppercase; color: rgba(255,255,255,.45); }

/* ── Expandable training rows ────────────────────── */
.tr-row { border: 1.5px solid var(--line); border-radius: 12px; margin-bottom: 8px; overflow: hidden; transition: .15s; }
.tr-row:hover { border-color: var(--ind); }
.tr-hd { display: flex; align-items: center; gap: 10px; padding: 11px 14px; cursor: pointer; background: var(--w); flex-wrap: wrap; }
.tr-hd:hover { background: #fafafa; }
.tr-title { font-size: 13px; font-weight: 600; color: var(--ink); flex: 1; min-width: 120px; }
.tr-scores { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; }
.trs .sv { font-size: 13px; font-weight: 700; font-family: 'JetBrains Mono', monospace; text-align: center; }
.trs .sl { font-size: 8.5px; font-weight: 600; text-transform: uppercase; letter-spacing: .06em; color: var(--muted); text-align: center; }
.tr-body { display: none; padding: 0 14px 14px; background: #fafafa; border-top: 1px solid var(--line); }
.tr-body.open { display: block; }
.tr-arr { color: var(--muted); transition: transform .2s; flex-shrink: 0; }
.tr-arr.open { transform: rotate(180deg); }

/* ── Compare layout ──────────────────────────────── */
.cmp-grid { display: grid; grid-template-columns: 1fr 40px 1fr; align-items: start; }
.cmp-vs { display: flex; align-items: center; justify-content: center; padding-top: 44px; font-size: 15px; font-weight: 800; color: var(--muted); }
.cmp-col { border: 1.5px solid var(--line); border-radius: 12px; overflow: hidden; }
.cmp-hd { padding: 12px 16px; font-size: 12px; font-weight: 700; color: #fff; }
.cmp-row { display: flex; justify-content: space-between; padding: 8px 16px; border-bottom: 1px solid #f3f4f6; font-size: 11.5px; }
.cmp-row:last-child { border-bottom: none; }
.cmp-lbl { color: var(--muted); font-weight: 500; }
.cmp-val { font-weight: 700; font-family: 'JetBrains Mono', monospace; }
.win { color: var(--grn); } .lose { color: var(--rose); }

/* ── Breadcrumb ──────────────────────────────────── */
.bc { display: flex; align-items: center; gap: 5px; font-size: 12px; color: var(--muted); }
.bc-active { color: var(--ink); font-weight: 700; }

/* ── Empty state ─────────────────────────────────── */
.empty { padding: 40px; text-align: center; color: var(--muted); }
.empty p { font-size: 13px; }

/* ── Modal ───────────────────────────────────────── */
.modal-bg { position: fixed; inset: 0; background: rgba(0,0,0,.6); z-index: 9998; display: flex; align-items: center; justify-content: center; padding: 20px; }
.modal-box { background: var(--w); border-radius: 18px; width: 100%; max-width: 740px; max-height: 90vh; overflow-y: auto; z-index: 9999; box-shadow: 0 30px 70px rgba(0,0,0,.28); }
.modal-hd { padding: 18px 22px; border-bottom: 1px solid var(--line); display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; background: var(--w); z-index: 1; }
.modal-cl { width: 28px; height: 28px; border-radius: 99px; border: 1.5px solid var(--line); background: none; cursor: pointer; display: flex; align-items: center; justify-content: center; color: var(--muted); font-size: 15px; transition: .15s; font-family: 'Outfit', sans-serif; }
.modal-cl:hover { background: var(--ink); color: #fff; border-color: var(--ink); }
.modal-body { padding: 22px; }
.fs-sec { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; color: var(--muted); padding-bottom: 6px; border-bottom: 1px solid var(--line); margin-bottom: 10px; }
.fi { background: #f9fafb; border-radius: 9px; padding: 10px 11px; }
.fi-lbl { font-size: 9.5px; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: var(--muted); margin-bottom: 3px; }
.fi-val { font-size: 18px; font-weight: 800; font-family: 'JetBrains Mono', monospace; }
.fi-bar { height: 3px; background: #e5e7eb; border-radius: 99px; margin-top: 4px; overflow: hidden; }
.fi-bar-f { height: 3px; border-radius: 99px; display: block; }
.fb-txt { background: #f9fafb; border-radius: 9px; padding: 11px; font-size: 12.5px; color: var(--ink2); line-height: 1.6; margin-top: 5px; }

@media (max-width: 700px) {
    .fgrid, .stats { grid-template-columns: 1fr 1fr; }
    .tiles { grid-template-columns: 1fr; }
    .cmp-grid { grid-template-columns: 1fr; } .cmp-vs { display: none; }
}
@keyframes fadeUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
.tile { animation: fadeUp .3s ease both; }
</style>

@php
    function sc($v): string { if ($v === null) return ''; return $v >= 4 ? 'sc-h' : ($v >= 3 ? 'sc-m' : 'sc-l'); }
    function chipStatus($s): string { return match($s) { 'completed' => 'c-grn', 'approved' => 'c-blu', 'pending' => 'c-amb', default => 'c-red' }; }
    $COLORS = ['#4f46e5','#0d9488','#d97706','#e11d48','#0284c7','#7c3aed','#059669','#dc2626','#2563eb','#b45309','#0891b2','#65a30d'];
@endphp

<div class="fd">

<div class="fbar">
    <div class="fgrid">
        <div>
            <div class="flbl">Year</div>
            <select class="fsel" wire:model.live="filterYear">
                <option value="">All Years</option>
                @foreach(range(2020, 2030) as $y)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <div class="flbl">Centre</div>
            <select class="fsel" wire:model.live="filterCentreId">
                <option value="">All Centres</option>
                @foreach($this->centreOptions as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <div class="flbl">Designation</div>
            <select class="fsel" wire:model.live="filterDesignation">
                <option value="">All Designations</option>
                @foreach($this->designationOptions as $d)
                    <option value="{{ $d }}">{{ $d }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <div class="flbl">Training Program</div>
            <select class="fsel" wire:model.live="filterTrainingId">
                <option value="">All Programs</option>
                @foreach($this->trainingOptions as $id => $title)
                    <option value="{{ $id }}">{{ $title }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <div class="flbl">Mode</div>
            <select class="fsel" wire:model.live="filterMode">
                <option value="">All Modes</option>
                <option value="in_person">In Person</option>
                <option value="online">Online</option>
            </select>
        </div>
    </div>
</div>

<div class="stats">
    <div class="stat" style="--ac:#4f46e5">
        <div class="s-lbl">Centres</div>
        <div class="s-val">{{ $this->allCentres->count() }}</div>
        <div class="s-sub">With feedback data</div>
    </div>
    <div class="stat" style="--ac:#0d9488">
        <div class="s-lbl">Total Feedbacks</div>
        <div class="s-val">{{ number_format($this->totalFeedbacks) }}</div>
        <div class="s-sub">Responses collected</div>
    </div>
    <div class="stat" style="--ac:#d97706">
        <div class="s-lbl">Global Average</div>
        <div class="s-val" style="font-family:'JetBrains Mono',monospace">{{ number_format($this->globalAvg, 1) }}</div>
        <div class="s-sub">Out of 5.0</div>
    </div>
    @if($this->topCentre)
    <div class="stat" style="--ac:#16a34a">
        <div class="s-lbl">Top Centre</div>
        <div class="s-val" style="font-size:14px;line-height:1.4">{{ $this->topCentre->centre_name }}</div>
        <div class="s-sub">{{ $this->topCentre->avg_overall }}/5.0</div>
    </div>
    @endif
    <div class="stat" style="--ac:#e11d48">
        <div class="s-lbl">Completions</div>
        <div class="s-val">{{ $this->allCentres->sum('completed_count') }}</div>
        <div class="s-sub">Trainings done</div>
    </div>
</div>

<div class="tabs">
    <button class="tab-btn {{ $activeTab==='infograph' ? 'on' : '' }}" wire:click="setTab('infograph')"> Infographic</button>
    <button class="tab-btn {{ $activeTab==='centres'   ? 'on' : '' }}" wire:click="setTab('centres')"> Centres</button>
    <button class="tab-btn {{ $activeTab==='search'    ? 'on' : '' }}" wire:click="setTab('search')"> Search &amp; Records</button>
</div>

<div class="tab-panel {{ $activeTab==='infograph' ? 'on' : '' }}">
    <div class="card">
        <div class="card-hd">
            <h3 class="card-title">All Metric Averages (/ 5.0)</h3>
            <div style="display:flex;gap:8px">
                <button class="btn btn-dk" onclick="openPDF()">
                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    PDF Report
                </button>
                <button class="btn btn-tl" wire:click="downloadExcel" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="downloadExcel">↓ Excel</span>
                    <span wire:loading wire:target="downloadExcel">Exporting…</span>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(88px,1fr));gap:10px">
                @foreach($this->globalMetrics as $ml => $mv)
                @php $mc = $mv >= 4 ? '#16a34a' : ($mv >= 3 ? '#d97706' : '#e11d48'); @endphp
                <div class="mpill">
                    <span class="mpv" style="color:{{ $mc }}">{{ number_format($mv,1) }}</span>
                    <span class="mpl">{{ $ml }}</span>
                    <div class="mpbar"><span class="mpfill" style="width:{{ ($mv/5)*100 }}%;background:{{ $mc }}"></span></div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
        <div class="card">
            <div class="card-hd"><h3 class="card-title">Score Radar</h3></div>
            <div class="card-body"><div style="position:relative;height:240px"><canvas id="radarChart"></canvas></div></div>
        </div>
        <div class="card">
            <div class="card-hd"><h3 class="card-title">Rating Distribution</h3></div>
            <div class="card-body"><div style="position:relative;height:240px"><canvas id="pieChart"></canvas></div></div>
        </div>
    </div>

    <div class="card">
        <div class="card-hd"><h3 class="card-title">Centre-wise Avg Overall Rating</h3></div>
        <div class="card-body"><div style="position:relative;height:200px"><canvas id="centreBar"></canvas></div></div>
    </div>

    <div class="card">
        <div class="card-hd"><h3 class="card-title">Category Breakdown</h3></div>
        <div class="card-body"><div style="position:relative;height:260px"><canvas id="catBar"></canvas></div></div>
    </div>

    <div class="card">
        <div class="card-hd"><h3 class="card-title">Centre Performance Summary</h3></div>
        <div style="overflow-x:auto">
            @if($this->allCentres->isEmpty())
                <div class="empty"><p>No data for selected filters.</p></div>
            @else
            <table class="dt">
                <thead><tr>
                    <th>#</th><th>Centre</th><th>Feedbacks</th><th>Overall</th>
                    <th>Clarity</th><th>Relevance</th><th>Quality</th><th>Knowledge</th>
                    <th>Venue</th><th>Engagement</th><th>Done</th><th>Bar</th>
                </tr></thead>
                <tbody>
                    @foreach($this->allCentres as $ci => $c)
                    <tr>
                        <td style="color:var(--muted);font-size:10px">{{ $ci+1 }}</td>
                        <td style="font-weight:600">{{ $c->centre_name }}</td>
                        <td style="text-align:center">{{ $c->total_feedbacks }}</td>
                        <td class="{{ sc($c->avg_overall) }}" style="text-align:center">{{ number_format($c->avg_overall,1) }}</td>
                        <td style="text-align:center">{{ number_format($c->avg_clarity,1) }}</td>
                        <td style="text-align:center">{{ number_format($c->avg_relevance,1) }}</td>
                        <td style="text-align:center">{{ number_format($c->avg_quality,1) }}</td>
                        <td style="text-align:center">{{ number_format($c->avg_knowledge,1) }}</td>
                        <td style="text-align:center">{{ number_format($c->avg_venue,1) }}</td>
                        <td style="text-align:center">{{ number_format($c->avg_engagement,1) }}</td>
                        <td style="text-align:center">{{ $c->completed_count }}</td>
                        <td><div class="pb"><span class="pbf" style="width:{{ ($c->avg_overall/5)*100 }}%"></span></div></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>
</div>

<div class="tab-panel {{ $activeTab==='centres' ? 'on' : '' }}">
    <div style="display:flex;align-items:center;justify-content:space-between;gap:10px;flex-wrap:wrap;margin-bottom:12px">
        <div style="display:flex;align-items:center;gap:8px">
            @if($activeCentreId)
                <button class="btn btn-ol" wire:click="backToOverview">← Back</button>
                <div class="bc"><span>Centres</span><span>›</span><span class="bc-active">{{ $this->activeCentreData?->centre_name }}</span></div>
            @elseif($compareMode)
                <button class="btn btn-ol" wire:click="cancelCompare">← Cancel Compare</button>
                <div class="bc"><span>Centres</span><span>›</span><span class="bc-active">Compare Mode</span></div>
            @else
                <div class="bc"><span class="bc-active">All Centres</span></div>
            @endif
        </div>
        <div style="display:flex;gap:8px;flex-wrap:wrap">
            @if(!$activeCentreId && !$compareMode)
                <button class="btn btn-in" wire:click="startCompare">⇌ Compare</button>
            @endif
            @if($activeCentreId)
                <button class="btn btn-tl" wire:click="downloadCentreExcel" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="downloadCentreExcel">↓ Centre Excel</span>
                    <span wire:loading wire:target="downloadCentreExcel">Exporting…</span>
                </button>
            @endif
            <button class="btn btn-dk" wire:click="downloadExcel" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="downloadExcel">↓ Download All</span>
                <span wire:loading wire:target="downloadExcel">Exporting…</span>
            </button>
        </div>
    </div>

    @if($compareMode)
    <div class="card">
        <div class="card-hd"><h3 class="card-title">Compare Two Centres</h3></div>
        <div class="card-body">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px">
                <div>
                    <div class="flbl">Centre A</div>
                    <select class="fsel" wire:model.live="compareCentreA">
                        <option value="">Select Centre A</option>
                        @foreach($this->allCentresList as $cl)
                            <option value="{{ $cl->id }}">{{ $cl->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <div class="flbl">Centre B</div>
                    <select class="fsel" wire:model.live="compareCentreB">
                        <option value="">Select Centre B</option>
                        @foreach($this->allCentresList as $cl)
                            <option value="{{ $cl->id }}">{{ $cl->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            @if(!empty($this->compareData) && $this->compareData['a'] && $this->compareData['b'])
            @php
                $ca = $this->compareData['a'];
                $cb = $this->compareData['b'];
                $cmpFields = [
                    'Overall'     => 'avg_overall',
                    'Clarity'     => 'avg_clarity',
                    'Relevance'   => 'avg_relevance',
                    'Quality'     => 'avg_quality',
                    'Knowledge'   => 'avg_knowledge',
                    'Venue'       => 'avg_venue',
                    'Engagement'  => 'avg_engagement',
                    'Feedbacks'   => 'total_feedbacks',
                    'Completions' => 'completed_count',
                ];
            @endphp
            <div class="cmp-grid">
                <div class="cmp-col">
                    <div class="cmp-hd" style="background:var(--ind)">{{ $ca->centre_name }}</div>
                    @foreach($cmpFields as $fl => $ff)
                    @php $av = $ca->$ff ?? 0; $bv = $cb->$ff ?? 0; $win = $av >= $bv; @endphp
                    <div class="cmp-row">
                        <span class="cmp-lbl">{{ $fl }}</span>
                        <span class="cmp-val {{ $win ? 'win' : 'lose' }}">
                            {{ is_float($av + 0) ? number_format($av, 2) : $av }}
                            {{ ($win && $av != $bv) ? ' ↑' : '' }}
                        </span>
                    </div>
                    @endforeach
                </div>
                <div class="cmp-vs">VS</div>
                <div class="cmp-col">
                    <div class="cmp-hd" style="background:var(--teal)">{{ $cb->centre_name }}</div>
                    @foreach($cmpFields as $fl => $ff)
                    @php $av = $ca->$ff ?? 0; $bv = $cb->$ff ?? 0; $win = $bv >= $av; @endphp
                    <div class="cmp-row">
                        <span class="cmp-lbl">{{ $fl }}</span>
                        <span class="cmp-val {{ $win ? 'win' : 'lose' }}">
                            {{ is_float($bv + 0) ? number_format($bv, 2) : $bv }}
                            {{ ($win && $bv != $av) ? ' ↑' : '' }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
            <div style="margin-top:16px"><div style="position:relative;height:240px"><canvas id="compareChart"></canvas></div></div>
            @else
                <div class="empty"><p>Select two centres to compare.</p></div>
            @endif
        </div>
    </div>

    @elseif($activeCentreId && $this->activeCentreData)
    @php $acd = $this->activeCentreData; @endphp
    <div class="dhd">
        <div style="font-size:19px;font-weight:800">{{ $acd->centre_name }}</div>
        <div style="font-size:11px;color:rgba(255,255,255,.5);margin-top:3px">{{ $acd->total_feedbacks }} feedbacks · {{ $acd->completed_count }} completions</div>
        <div class="dscores">
            @foreach(['Overall'=>'avg_overall','Clarity'=>'avg_clarity','Relevance'=>'avg_relevance','Quality'=>'avg_quality','Knowledge'=>'avg_knowledge','Venue'=>'avg_venue','Engagement'=>'avg_engagement'] as $dl => $df)
            <div class="dscore">
                <div class="dv">{{ number_format($acd->$df, 1) }}</div>
                <div class="dl">{{ $dl }}</div>
            </div>
            @endforeach
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px">
        <div class="card">
            <div class="card-hd"><h3 class="card-title">Score Radar</h3></div>
            <div class="card-body"><div style="position:relative;height:230px"><canvas id="drillRadar"></canvas></div></div>
        </div>
        <div class="card">
            <div class="card-hd"><h3 class="card-title">Trainings Comparison</h3></div>
            <div class="card-body"><div style="position:relative;height:230px"><canvas id="drillBar"></canvas></div></div>
        </div>
    </div>

    <div class="card">
        <div class="card-hd">
            <h3 class="card-title">Trainings ({{ $this->activeCentreTrainings->count() }})</h3>
            <div class="srw">
                <svg class="sric" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
                <input class="srin" placeholder="Search trainings…" oninput="filterAttr('#trList .tr-row','data-t',this.value)">
            </div>
        </div>
        <div class="card-body" id="trList">
            @forelse($this->activeCentreTrainings as $ti => $tr)
            <div class="tr-row" data-t="{{ strtolower($tr->training_title) }}">
                <div class="tr-hd" onclick="toggleRow({{ $ti }})">
                    <div class="tr-title">
                        {{ $tr->training_title }}
                        <span class="chip {{ $tr->training_mode === 'online' ? 'c-blu' : 'c-grn' }}" style="margin-left:6px">{{ $tr->training_mode === 'online' ? 'Online' : 'In Person' }}</span>
                    </div>
                    <div class="tr-scores">
                        @foreach(['Overall'=>'avg_overall','Clarity'=>'avg_clarity','Quality'=>'avg_quality','Knowledge'=>'avg_knowledge'] as $sl => $sf)
                        <div class="trs">
                            <div class="sv {{ sc($tr->$sf) }}">{{ number_format($tr->$sf,1) }}</div>
                            <div class="sl">{{ $sl }}</div>
                        </div>
                        @endforeach
                        <span style="font-size:10px;color:var(--muted)">{{ $tr->total_feedbacks }} responses</span>
                    </div>
                    <svg class="tr-arr" id="arr{{ $ti }}" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
                <div class="tr-body" id="trb{{ $ti }}">
                    @php $tFbs = $this->activeCentreFeedbacks->filter(fn($f) => $f->trainingApplication?->training_id == $tr->training_id); @endphp
                    @if($tFbs->isNotEmpty())
                    <div style="overflow-x:auto;padding-top:11px">
                    <table class="dt">
                        <thead><tr>
                            <th>#</th><th>Employee</th><th>Emp ID</th><th>Status</th>
                            <th>Overall</th><th>Clarity</th><th>Relevance</th><th>Quality</th>
                            <th>Date</th><th>View</th><th>DL</th>
                        </tr></thead>
                        <tbody>
                            @foreach($tFbs->values() as $fi => $fb)
                            @php $fa = $fb->trainingApplication; $fu = $fa?->user; $fs = $fa?->status ?? 'pending'; @endphp
                            <tr>
                                <td style="color:var(--muted);font-size:10px">{{ $fi+1 }}</td>
                                <td style="font-weight:600;white-space:nowrap">{{ $fu?->name ?? 'N/A' }}</td>
                                <td style="font-family:'JetBrains Mono',monospace;font-size:10px;color:var(--muted)">{{ $fu?->employee_code ?? '—' }}</td>
                                <td><span class="chip {{ chipStatus($fs) }}">{{ ucfirst($fs) }}</span></td>
                                <td class="{{ sc($fb->overall_rating) }}" style="text-align:center">{{ $fb->overall_rating }}</td>
                                <td style="text-align:center">{{ $fb->clarity_objectives }}</td>
                                <td style="text-align:center">{{ $fb->relevance_to_role }}</td>
                                <td style="text-align:center">{{ $fb->quality_materials }}</td>
                                <td style="font-size:10px;color:var(--muted);white-space:nowrap">{{ $fb->created_at?->format('d M Y') }}</td>
                                <td><button class="btn btn-in btn-sm" wire:click="viewFeedback({{ $fb->id }})">View</button></td>
                                <td><button class="btn btn-tl btn-sm" wire:click="downloadSingleExcel({{ $fb->id }})">↓</button></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table></div>
                    @else
                        <p style="font-size:12px;color:var(--muted);padding:11px 0">No feedbacks for this training.</p>
                    @endif
                </div>
            </div>
            @empty
                <div class="empty"><p>No trainings found.</p></div>
            @endforelse
        </div>
    </div>

    @else
    <div class="card">
        <div class="card-hd">
            <h3 class="card-title">All Centres <span style="font-size:11px;font-weight:400;color:var(--muted);margin-left:5px">Click to drill in</span></h3>
            <div class="srw">
                <svg class="sric" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
                <input class="srin" placeholder="Search centres…" oninput="filterAttr('#cTiles .tile','data-n',this.value)">
            </div>
        </div>
        <div class="card-body">
            @if($this->allCentres->isEmpty())
                <div class="empty"><p>No centre data for selected filters.</p></div>
            @else
            <div class="tiles" id="cTiles">
                @foreach($this->allCentres as $ci => $c)
                @php $col = $COLORS[$ci % count($COLORS)]; @endphp
                <div class="tile" style="--tc:{{ $col }}" data-n="{{ strtolower($c->centre_name) }}"
                     wire:click="drillInto({{ $c->centre_id }})">
                    <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:10px">
                        <div class="tile-name">{{ $c->centre_name }}</div>
                        <div class="tile-badge" style="color:{{ $col }};background:{{ $col }}22">{{ number_format($c->avg_overall,1) }}</div>
                    </div>
                    <div class="tile-mets">
                        @foreach(['Clarity'=>'avg_clarity','Relevance'=>'avg_relevance','Quality'=>'avg_quality','Knowledge'=>'avg_knowledge','Venue'=>'avg_venue','Engage'=>'avg_engagement'] as $ml => $mf)
                        <div class="tile-met">
                            <div class="v">{{ number_format($c->$mf,1) }}</div>
                            <div class="l">{{ $ml }}</div>
                        </div>
                        @endforeach
                    </div>
                    <div class="tile-bar"><span class="tile-bar-f" style="width:{{ ($c->avg_overall/5)*100 }}%;background:{{ $col }}"></span></div>
                    <div style="display:flex;justify-content:space-between;font-size:10px;color:var(--muted)">
                        <span>{{ $c->total_feedbacks }} feedbacks · {{ $c->completed_count }} done</span>
                        <span style="font-weight:700;color:{{ $col }}">View →</span>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
    @endif
</div>

<div class="tab-panel {{ $activeTab==='search' ? 'on' : '' }}">
    <div class="card">
        <div class="card-hd">
            <h3 class="card-title">
                Feedback Records
                <span style="font-size:10px;font-weight:400;color:var(--muted);margin-left:5px">{{ $this->allFeedbacks->count() }} records</span>
            </h3>
            <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap">
                <div class="srw" style="width:220px">
                    <svg class="sric" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
                    <input class="srin" style="width:220px" placeholder="Search name, ID, centre…" oninput="filterFbTable(this.value)">
                </div>
                <button class="btn btn-dk" onclick="openPDF()">
                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    PDF Report
                </button>
                <button class="btn btn-tl" wire:click="downloadExcel" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="downloadExcel">↓ Download All Excel</span>
                    <span wire:loading wire:target="downloadExcel">Exporting…</span>
                </button>
            </div>
        </div>
        <div style="overflow-x:auto">
            @if($this->allFeedbacks->isEmpty())
                <div class="empty"><p>No records found. Adjust the filters above.</p></div>
            @else
            <table class="dt" id="fbTable">
                <thead>
                    <tr>
                        <th>S.No.</th>
                        <th>Employee Name</th>
                        <th>Employee ID</th>
                        <th>Centre</th>
                        <th>Training</th>
                        <th>Designation</th>
                        <th>Completion Status</th>
                        <th>View</th>
                        <th>Download</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($this->allFeedbacks as $fi => $fb)
                    @php
                        $fa  = $fb->trainingApplication;
                        $fu  = $fa?->user;
                        $ft  = $fa?->training;
                        $fc  = $fa?->centreRel;
                        $fs  = $fa?->status ?? 'pending';
                        $str = strtolower(($fu?->name ?? '') . ' ' . ($fu?->employee_code ?? '') . ' ' . ($fc?->name ?? '') . ' ' . ($ft?->title ?? '') . ' ' . ($fa?->nominee_designation ?? ''));
                    @endphp
                    <tr data-s="{{ $str }}">
                        <td style="color:var(--muted);font-size:10px">{{ $fi+1 }}</td>
                        <td style="font-weight:600;white-space:nowrap">{{ $fu?->name ?? 'N/A' }}</td>
                        <td style="font-family:'JetBrains Mono',monospace;font-size:11px;color:var(--muted)">{{ $fu?->employee_code ?? '—' }}</td>
                        <td style="font-size:12px">{{ $fc?->name ?? 'N/A' }}</td>
                        <td style="max-width:150px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:12px" title="{{ $ft?->title }}">{{ $ft?->title ?? 'N/A' }}</td>
                        <td style="font-size:11px">{{ $fa?->nominee_designation ?? '—' }}</td>
                        <td><span class="chip {{ chipStatus($fs) }}">{{ ucfirst($fs) }}</span></td>
                        <td>
                            <button class="btn btn-in btn-sm" wire:click="viewFeedback({{ $fb->id }})">View</button>
                        </td>
                        <td>
                            <button class="btn btn-ol btn-sm" wire:click="downloadSingleExcel({{ $fb->id }})">Excel</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>
</div>

</div>{{-- /.fd --}}

@if($showModal && $this->modalFeedback)

@php
$mf = $this->modalFeedback;
$ma = $mf->trainingApplication;
$mu = $ma?->user;
$mt = $ma?->training;
$mc = $ma?->centreRel;

$groups = [
'Content & Relevance' => [
'Clarity of objectives' => $mf->clarity_objectives,
'Relevance to your role' => $mf->relevance_to_role,
'Quality of materials/examples' => $mf->quality_materials,
'Practical applicability' => $mf->practical_applicability,
],

'Trainer Effectiveness' => [
'Subject knowledge' => $mf->subject_knowledge,
'Clarity & communication' => $mf->instructor_clarity,
'Engagement & interaction' => $mf->interaction_engagement,
'Time management' => $mf->time_management,
],

'Logistics' => [
'Venue / Platform quality' => $mf->venue_platform_quality,
'Organization & coordination' => $mf->organization_coordination,
'Accommodation & Boarding' => $mf->accommodation_boarding,
'Transportation' => $mf->transportation,
]
];
@endphp


<div class="modal-bg" wire:click.self="closeModal" style="background: rgba(10,25,47,.85);">

<div class="modal-box"
style="max-width:900px;background:#0f172a;color:#fff;border:1px solid #1e293b;">

{{-- HEADER --}}
<div class="modal-hd"
style="padding:16px 24px;border-bottom:1px solid #1e293b;display:flex;justify-content:space-between;align-items:center;">

<div style="font-size:18px;font-weight:700;">
Detailed Feedback: {{ $mu?->name ?? 'Participant' }}
</div>

<button wire:click="closeModal"
style="color:#94a3b8;border:none;background:none;font-size:18px;">
✕
</button>

</div>



<div class="modal-body" style="padding:24px">

{{-- TRAINING INFO CARD --}}
<div style="background:#1e293b;padding:16px;border-radius:10px;margin-bottom:24px;
display:grid;grid-template-columns:1fr 1fr;gap:10px;">

<div>
<div style="font-size:12px;color:#94a3b8">Training Program</div>
<div style="font-weight:700">{{ $mt?->title ?? 'N/A' }}</div>
</div>

<div>
<div style="font-size:12px;color:#94a3b8">Centre</div>
<div style="font-weight:700">{{ $mc?->name ?? 'N/A' }}</div>
</div>

</div>


{{-- FEEDBACK TABLE --}}
<table style="width:100%;border-collapse:collapse">

<thead style="background:#1e293b">

<tr>

<th style="padding:12px 24px;text-align:left;color:#94a3b8;font-size:13px">
Category / Aspect
</th>

@foreach([5=>'Excellent',4=>'Very Good',3=>'Good',2=>'Fair',1=>'Poor'] as $num=>$lbl)

<th style="text-align:center;padding:10px">

<div style="font-size:13px">{{ $num }}</div>
<div style="font-size:10px;color:#94a3b8">{{ $lbl }}</div>

</th>

@endforeach

</tr>

</thead>


<tbody style="font-size:13px">

@foreach($groups as $groupTitle => $metrics)

<tr style="background:rgba(30,41,59,.5)">

<td colspan="6"
style="padding:8px 24px;color:#38bdf8;font-weight:700;font-size:12px">
{{ $groupTitle }}
</td>

</tr>


@foreach($metrics as $label => $val)

<tr style="border-bottom:1px solid #1e293b">

<td style="padding:12px 24px;color:#cbd5e1">
{{ $label }}
</td>

@for($i=5;$i>=1;$i--)

<td style="text-align:center">

@if(round($val)==$i)

<div style="width:10px;height:10px;background:#fff;border-radius:50%;display:inline-block"></div>

@else

<span style="color:#475569">-</span>

@endif

</td>

@endfor

</tr>

@endforeach
@endforeach

</tbody>

</table>


{{-- OVERALL --}}
<div style="padding-top:24px;margin-top:24px;border-top:1px solid #1e293b">

<h4 style="margin-bottom:20px;font-size:15px">
Overall Assessment
</h4>

<div style="display:flex;gap:60px;margin-bottom:30px">

<div>
<div style="font-size:12px;color:#94a3b8">
Programme met expectations
</div>

<div style="font-weight:700;font-size:16px">
{{ $mf->expectations_met ?? '5' }}
</div>
</div>


<div>
<div style="font-size:12px;color:#94a3b8">
Overall Rating
</div>

<span style="background:#fbbf24;color:#000;padding:3px 8px;border-radius:4px;font-weight:800">
{{ $mf->overall_rating }}
</span>

</div>

</div>


<div style="margin-bottom:20px">

<div style="font-size:12px;color:#94a3b8;margin-bottom:8px">
Most Useful Aspect
</div>

<p style="background:#1e293b;padding:15px;border-radius:8px">
{{ $mf->most_useful_aspect ?? 'N/A' }}
</p>

</div>


<div>

<div style="font-size:12px;color:#94a3b8;margin-bottom:8px">
Areas for Improvement
</div>

<p style="background:#1e293b;padding:15px;border-radius:8px">
{{ $mf->areas_for_improvement ?? 'N/A' }}
</p>

</div>

</div>


{{-- FOOTER --}}
<div style="display:flex;gap:10px;justify-content:flex-end;margin-top:24px">

<button class="btn btn-tl"
wire:click="downloadSingleExcel({{ $mf->id }})">
Download Excel
</button>

<button class="btn btn-dk"
wire:click="closeModal">
Close
</button>

</div>

</div>
</div>
</div>

@endif

{{-- Data bridge for JavaScript so Chart.js refreshes smoothly --}}
<div id="chartData" style="display:none;"
     data-gm="{{ json_encode($this->globalMetrics) }}"
     data-rd="{{ json_encode($this->ratingDist) }}"
     data-ac="{{ json_encode($this->allCentres) }}"
     data-act="{{ json_encode($this->activeCentreData) }}"
     data-tr="{{ json_encode($this->activeCentreTrainings) }}"
     data-cmp="{{ json_encode($this->compareData) }}"
     data-drill="{{ $activeCentreId ? 'true' : 'false' }}"
     data-cmode="{{ $compareMode ? 'true' : 'false' }}">
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<script>
(function () {
    const TC = ['#4f46e5','#0d9488','#d97706','#e11d48','#0284c7','#7c3aed','#059669','#dc2626','#2563eb','#b45309','#0891b2','#65a30d'];
    let charts = {};
    const destroy = id => { try { charts[id]?.destroy(); } catch(e){} delete charts[id]; };
    const destroyAll = () => Object.keys(charts).forEach(destroy);

    function mkChart(id, cfg) {
        const el = document.getElementById(id);
        if (!el) return;
        destroy(id);
        charts[id] = new Chart(el, cfg);
    }

    window.drawAll = function() {
        destroyAll();
        const dataEl = document.getElementById('chartData');
        if(!dataEl) return;

        const GM = JSON.parse(dataEl.getAttribute('data-gm') || '{}');
        const RD = JSON.parse(dataEl.getAttribute('data-rd') || '{}');
        const AC = JSON.parse(dataEl.getAttribute('data-ac') || '[]');
        const ACT = JSON.parse(dataEl.getAttribute('data-act') || 'null');
        const TR = JSON.parse(dataEl.getAttribute('data-tr') || '[]');
        const CMP = JSON.parse(dataEl.getAttribute('data-cmp') || '{}');
        const DRILL = dataEl.getAttribute('data-drill') === 'true';
        const CMODE = dataEl.getAttribute('data-cmode') === 'true';

        const gml = Object.keys(GM), gmv = Object.values(GM);

        mkChart('radarChart', {
            type: 'radar',
            data: { labels: gml, datasets: [{ label: 'Score', data: gmv, backgroundColor: 'rgba(79,70,229,.15)', borderColor: '#4f46e5', pointBackgroundColor: '#4f46e5', borderWidth: 2 }] },
            options: { responsive: true, maintainAspectRatio: false, scales: { r: { beginAtZero: true, max: 5, ticks: { stepSize: 1, font: { size: 9 } } } }, plugins: { legend: { display: false } } }
        });

        mkChart('pieChart', {
            type: 'doughnut',
            data: { labels: Object.keys(RD).map(k => 'Rating ' + k), datasets: [{ data: Object.values(RD), backgroundColor: ['#ef4444','#f97316','#eab308','#22c55e','#3b82f6'], borderWidth: 2, borderColor: '#fff' }] },
            options: { responsive: true, maintainAspectRatio: false, cutout: '58%', plugins: { legend: { position: 'right' } } }
        });

        if (AC && AC.length) {
            mkChart('centreBar', {
                type: 'bar',
                data: { labels: AC.map(c => c.centre_name), datasets: [{ label: 'Avg Overall', data: AC.map(c => c.avg_overall), backgroundColor: AC.map((_, i) => TC[i % TC.length]), borderRadius: 6 }] },
                options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, max: 5 } }, plugins: { legend: { display: false } } }
            });
        }

        mkChart('catBar', {
            type: 'bar',
            data: { labels: gml, datasets: [{ label: 'Score', data: gmv, backgroundColor: TC, borderRadius: 5 }] },
            options: { responsive: true, maintainAspectRatio: false, indexAxis: 'y', scales: { x: { beginAtZero: true, max: 5 } }, plugins: { legend: { display: false } } }
        });

        if (DRILL && ACT) {
            mkChart('drillRadar', {
                type: 'radar',
                data: { labels: ['Clarity','Relevance','Quality','Knowledge','Venue','Engagement','Practical','Met'], datasets: [{ label: ACT.centre_name, data: [ACT.avg_clarity,ACT.avg_relevance,ACT.avg_quality,ACT.avg_knowledge,ACT.avg_venue,ACT.avg_engagement,ACT.avg_practical,ACT.avg_met], backgroundColor: 'rgba(79,70,229,.15)', borderColor: '#4f46e5', borderWidth: 2 }] },
                options: { responsive: true, maintainAspectRatio: false, scales: { r: { beginAtZero: true, max: 5 } }, plugins: { legend: { display: false } } }
            });
            if (TR && TR.length) {
                mkChart('drillBar', {
                    type: 'bar',
                    data: { labels: TR.map(t => t.training_title.length > 24 ? t.training_title.slice(0,24)+'…' : t.training_title), datasets: [{ label: 'Avg Overall', data: TR.map(t => t.avg_overall), backgroundColor: TR.map((_, i) => TC[i % TC.length]), borderRadius: 5 }] },
                    options: { responsive: true, maintainAspectRatio: false, indexAxis: 'y', scales: { x: { beginAtZero: true, max: 5 } }, plugins: { legend: { display: false } } }
                });
            }
        }

        if (CMODE && CMP && CMP.a && CMP.b) {
            const fl = ['Overall','Clarity','Relevance','Quality','Knowledge','Venue','Engagement'];
            const av = [CMP.a.avg_overall,CMP.a.avg_clarity,CMP.a.avg_relevance,CMP.a.avg_quality,CMP.a.avg_knowledge,CMP.a.avg_venue,CMP.a.avg_engagement];
            const bv = [CMP.b.avg_overall,CMP.b.avg_clarity,CMP.b.avg_relevance,CMP.b.avg_quality,CMP.b.avg_knowledge,CMP.b.avg_venue,CMP.b.avg_engagement];
            mkChart('compareChart', {
                type: 'bar',
                data: { labels: fl, datasets: [{ label: CMP.a.centre_name, data: av, backgroundColor: '#4f46e5', borderRadius: 4 }, { label: CMP.b.centre_name, data: bv, backgroundColor: '#0d9488', borderRadius: 4 }] },
                options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, max: 5 } } }
            });
        }
    }

    if (document.readyState === 'loading') { document.addEventListener('DOMContentLoaded', () => setTimeout(drawAll, 80)); } else { setTimeout(drawAll, 80); }

    // Livewire 3 hook for updates
    document.addEventListener('livewire:initialized', () => {
        Livewire.hook('morph.updated', ({ el, component }) => { setTimeout(window.drawAll, 50); });
    });

    window.toggleRow = function (i) { document.getElementById('trb' + i)?.classList.toggle('open'); document.getElementById('arr' + i)?.classList.toggle('open'); };
    window.filterAttr = function (sel, attr, q) { q = q.toLowerCase(); document.querySelectorAll(sel).forEach(el => { el.style.display = (el.getAttribute(attr) || '').includes(q) ? '' : 'none'; }); };
    window.filterFbTable = function (q) { q = q.toLowerCase(); document.querySelectorAll('#fbTable tbody tr').forEach(tr => { tr.style.display = (tr.dataset.s || '').includes(q) ? '' : 'none'; }); };
    window.openPDF = function () {
        const get = prop => document.querySelector(`[wire\\:model\\.live="${prop}"]`)?.value || '';
        const p = new URLSearchParams({ year: get('filterYear'), centre_id: get('filterCentreId'), training_id: get('filterTrainingId'), training_type: get('filterMode'), designation: get('filterDesignation') });
        window.open('/admin/feedback-pdf?' + p.toString(), '_blank');
    };
})();
</script>
</x-filament-panels::page>