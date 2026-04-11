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
.fgrid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; }
.flbl { font-size: 9.5px; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; color: var(--muted); margin-bottom: 5px; }
.fsel {
    width: 100%; padding: 8px 30px 8px 10px; border: 1.5px solid var(--line); border-radius: 8px;
    font-family: 'Outfit', sans-serif; font-size: 12.5px; color: var(--ink);
    background-color: var(--bg); background-repeat: no-repeat; background-position: right 10px center;
    background-size: 16px 12px; appearance: none; -webkit-appearance: none; outline: none; transition: border-color .15s; cursor: pointer;
}
.fsel:focus { border-color: var(--ind); background: var(--w); }

/* ── Stat cards ──────────────────────────────────── */
.stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-bottom: 16px; }
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
    border: none; border-bottom: 2.5px solid transparent; margin-bottom: -2px; background: none; font-family: 'Outfit', sans-serif; transition: .15s;
}
.tab-btn.on { color: var(--ind); border-bottom-color: var(--ind); }
.tab-btn:hover:not(.on) { color: var(--ink2); }
.tab-panel { display: none; }
.tab-panel.on { display: block; }

/* ── Cards & Buttons & Table ─────────────────────── */
.card { background: var(--w); border: 1px solid var(--line); border-radius: 14px; overflow: hidden; margin-bottom: 16px; }
.card-hd { padding: 13px 20px; border-bottom: 1px solid var(--line); display: flex; align-items: center; justify-content: space-between; gap: 10px; flex-wrap: wrap; }
.card-title { font-size: 13px; font-weight: 700; color: var(--ink); margin: 0; }
.card-body { padding: 18px; }
.btn { display: inline-flex; align-items: center; gap: 5px; padding: 7px 13px; border-radius: 8px; border: none; font-size: 12px; font-weight: 600; cursor: pointer; font-family: 'Outfit', sans-serif; transition: all .15s; }
.btn-dk { background: var(--ink); color: #fff; } .btn-dk:hover { background: #1f2937; }
.btn-in { background: var(--ind); color: #fff; } .btn-in:hover { background: #4338ca; }
.btn-tl { background: var(--teal); color: #fff; } .btn-tl:hover { background: #0f766e; }
.btn-ol { background: var(--w); border: 1.5px solid var(--line); color: var(--ink2); } .btn-ol:hover { border-color: var(--ink); color: var(--ink); }
.btn-sm { padding: 4px 9px; font-size: 11px; border-radius: 6px; }
.srw { position: relative; width: 200px; }
.srin { width: 100%; padding: 7px 10px 7px 30px; border: 1.5px solid var(--line); border-radius: 8px; font-size: 12px; font-family: 'Outfit', sans-serif; color: var(--ink); background: var(--bg); outline: none; transition: .15s; }
.srin:focus { border-color: var(--ind); background: var(--w); }
.sric { position: absolute; left: 9px; top: 50%; transform: translateY(-50%); width: 12px; height: 12px; color: var(--muted); pointer-events: none; }
.dt { width: 100%; border-collapse: collapse; font-size: 12.5px; }
.dt th { padding: 8px 11px; text-align: left; font-size: 9px; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; color: var(--muted); border-bottom: 1.5px solid var(--line); background: #f9fafb; white-space: nowrap; }
.dt td { padding: 9px 11px; color: var(--ink2); border-bottom: 1px solid #f3f4f6; vertical-align: middle; }
.dt tbody tr:hover { background: #fafafa; }
.dt tbody tr:last-child td { border-bottom: none; }
.chip { display: inline-flex; padding: 2px 7px; border-radius: 99px; font-size: 9.5px; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; }
.c-grn { background: #dcfce7; color: #15803d; } .c-blu { background: #dbeafe; color: #1d4ed8; } .c-amb { background: #fef3c7; color: #b45309; } .c-red { background: #fee2e2; color: #dc2626; }
.sc-h { color: #16a34a; font-weight: 700; } .sc-m { color: #d97706; font-weight: 700; } .sc-l { color: #e11d48; font-weight: 700; }
.mpill { display: flex; flex-direction: column; align-items: center; gap: 3px; padding: 12px 8px; background: #f9fafb; border: 1px solid var(--line); border-radius: 10px; text-align: center; }
.mpv { font-size: 20px; font-weight: 800; line-height: 1; font-family: 'JetBrains Mono', monospace; }
.mpl { font-size: 8px; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; color: var(--muted); }
.mpbar { width: 100%; background: #e5e7eb; border-radius: 99px; height: 3px; margin-top: 3px; overflow: hidden; }
.mpfill { height: 3px; border-radius: 99px; display: block; }
.dhd { background: linear-gradient(135deg, #0f1117, #1e293b); border-radius: 16px; padding: 22px 24px; color: #fff; margin-bottom: 14px; }
.dscores { display: flex; gap: 16px; flex-wrap: wrap; margin-top: 12px; }
.dscore .dv { font-size: 22px; font-weight: 800; font-family: 'JetBrains Mono', monospace; }
.dscore .dl { font-size: 9px; font-weight: 600; letter-spacing: .08em; text-transform: uppercase; color: rgba(255,255,255,.45); }
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
.empty { padding: 40px; text-align: center; color: var(--muted); }
.empty p { font-size: 13px; }

/* ── Modal ───────────────────────────────────────── */
.modal-bg { position: fixed; inset: 0; background: rgba(0,0,0,.6); z-index: 9998; display: flex; align-items: center; justify-content: center; padding: 20px; }
.modal-box { background: var(--w); border-radius: 18px; width: 100%; max-width: 740px; max-height: 90vh; overflow-y: auto; z-index: 9999; box-shadow: 0 30px 70px rgba(0,0,0,.28); }
.modal-hd { padding: 18px 22px; border-bottom: 1px solid var(--line); display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; background: var(--w); z-index: 1; }
.modal-cl { width: 28px; height: 28px; border-radius: 99px; border: 1.5px solid var(--line); background: none; cursor: pointer; display: flex; align-items: center; justify-content: center; color: var(--muted); font-size: 15px; transition: .15s; font-family: 'Outfit', sans-serif; }
.modal-cl:hover { background: var(--ink); color: #fff; border-color: var(--ink); }
.modal-body { padding: 22px; }

@media (max-width: 700px) { .fgrid, .stats { grid-template-columns: 1fr 1fr; } }
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
    <div class="stat" style="--ac:#0d9488">
        <div class="s-lbl">Total Feedbacks</div>
        <div class="s-val">{{ number_format($this->totalFeedbacks) }}</div>
        <div class="s-sub">Responses collected</div>
    </div>
    <div class="stat" style="--ac:#d97706">
        <div class="s-lbl">Overall Average</div>
        <div class="s-val" style="font-family:'JetBrains Mono',monospace">{{ number_format($this->globalAvg, 1) }}</div>
        <div class="s-sub">Out of 5.0</div>
    </div>
    <div class="stat" style="--ac:#e11d48">
        <div class="s-lbl">Completions</div>
        <div class="s-val">{{ $this->activeCentreData?->completed_count ?? 0 }}</div>
        <div class="s-sub">Trainings done</div>
    </div>
</div>

<div class="tabs">
    <button class="tab-btn {{ $activeTab==='infograph' ? 'on' : '' }}" wire:click="setTab('infograph')"> Infographic</button>
    <button class="tab-btn {{ $activeTab==='trainings' ? 'on' : '' }}" wire:click="setTab('trainings')"> Trainings Report</button>
    <button class="tab-btn {{ $activeTab==='search'    ? 'on' : '' }}" wire:click="setTab('search')"> Search &amp; Records</button>
</div>

<div class="tab-panel {{ $activeTab==='infograph' ? 'on' : '' }}">
    <div class="card">
        <div class="card-hd">
            <h3 class="card-title">Metric Averages (/ 5.0)</h3>
            <div style="display:flex;gap:8px">
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
        <div class="card-hd"><h3 class="card-title">Category Breakdown</h3></div>
        <div class="card-body"><div style="position:relative;height:260px"><canvas id="catBar"></canvas></div></div>
    </div>
</div>

<div class="tab-panel {{ $activeTab==='trainings' ? 'on' : '' }}">
    @if($this->activeCentreData)
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
    @endif

    <div class="card">
        <div class="card-hd">
            <h3 class="card-title">Trainings List</h3>
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
                    {{-- THE FIX IS HERE: using $this->allFeedbacks instead of $this->activeCentreFeedbacks --}}
                    @php $tFbs = $this->allFeedbacks->filter(fn($f) => $f->trainingApplication?->training_id == $tr->training_id); @endphp
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
                        <th>Training</th>
                        <th>Designation</th>
                        <th>Status</th>
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
                        $fs  = $fa?->status ?? 'pending';
                        $str = strtolower(($fu?->name ?? '') . ' ' . ($fu?->employee_code ?? '') . ' ' . ($ft?->title ?? '') . ' ' . ($fa?->nominee_designation ?? ''));
                    @endphp
                    <tr data-s="{{ $str }}">
                        <td style="color:var(--muted);font-size:10px">{{ $fi+1 }}</td>
                        <td style="font-weight:600;white-space:nowrap">{{ $fu?->name ?? 'N/A' }}</td>
                        <td style="font-family:'JetBrains Mono',monospace;font-size:11px;color:var(--muted)">{{ $fu?->employee_code ?? '—' }}</td>
                        <td style="max-width:150px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:12px" title="{{ $ft?->title }}">{{ $ft?->title ?? 'N/A' }}</td>
                        <td style="font-size:11px">{{ $fa?->nominee_designation ?? '—' }}</td>
                        <td><span class="chip {{ chipStatus($fs) }}">{{ ucfirst($fs) }}</span></td>
                        <td><button class="btn btn-in btn-sm" wire:click="viewFeedback({{ $fb->id }})">View</button></td>
                        <td><button class="btn btn-ol btn-sm" wire:click="downloadSingleExcel({{ $fb->id }})">Excel</button></td>
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
<div class="modal-box" style="max-width:900px;background:#0f172a;color:#fff;border:1px solid #1e293b;">
<div class="modal-hd" style="padding:16px 24px;border-bottom:1px solid #1e293b;display:flex;justify-content:space-between;align-items:center;">
<div style="font-size:18px;font-weight:700;">Detailed Feedback: {{ $mu?->name ?? 'Participant' }}</div>
<button wire:click="closeModal" style="color:#94a3b8;border:none;background:none;font-size:18px;">✕</button>
</div>
<div class="modal-body" style="padding:24px">
<div style="background:#1e293b;padding:16px;border-radius:10px;margin-bottom:24px;display:grid;grid-template-columns:1fr 1fr;gap:10px;">
<div><div style="font-size:12px;color:#94a3b8">Training Program</div><div style="font-weight:700">{{ $mt?->title ?? 'N/A' }}</div></div>
<div><div style="font-size:12px;color:#94a3b8">Centre</div><div style="font-weight:700">{{ $mc?->name ?? 'N/A' }}</div></div>
</div>
<table style="width:100%;border-collapse:collapse"><thead style="background:#1e293b"><tr>
<th style="padding:12px 24px;text-align:left;color:#94a3b8;font-size:13px">Category / Aspect</th>
@foreach([5=>'Excellent',4=>'Very Good',3=>'Good',2=>'Fair',1=>'Poor'] as $num=>$lbl)
<th style="text-align:center;padding:10px"><div style="font-size:13px">{{ $num }}</div><div style="font-size:10px;color:#94a3b8">{{ $lbl }}</div></th>
@endforeach
</tr></thead><tbody style="font-size:13px">
@foreach($groups as $groupTitle => $metrics)
<tr style="background:rgba(30,41,59,.5)"><td colspan="6" style="padding:8px 24px;color:#38bdf8;font-weight:700;font-size:12px">{{ $groupTitle }}</td></tr>
@foreach($metrics as $label => $val)
<tr style="border-bottom:1px solid #1e293b"><td style="padding:12px 24px;color:#cbd5e1">{{ $label }}</td>
@for($i=5;$i>=1;$i--)<td style="text-align:center">@if(round($val)==$i)<div style="width:10px;height:10px;background:#fff;border-radius:50%;display:inline-block"></div>@else<span style="color:#475569">-</span>@endif</td>@endfor
</tr>
@endforeach
@endforeach
</tbody></table>
<div style="padding-top:24px;margin-top:24px;border-top:1px solid #1e293b">
<h4 style="margin-bottom:20px;font-size:15px">Overall Assessment</h4>
<div style="display:flex;gap:60px;margin-bottom:30px">
<div><div style="font-size:12px;color:#94a3b8">Programme met expectations</div><div style="font-weight:700;font-size:16px">{{ $mf->expectations_met ?? '5' }}</div></div>
<div><div style="font-size:12px;color:#94a3b8">Overall Rating</div><span style="background:#fbbf24;color:#000;padding:3px 8px;border-radius:4px;font-weight:800">{{ $mf->overall_rating }}</span></div>
</div>
<div style="margin-bottom:20px"><div style="font-size:12px;color:#94a3b8;margin-bottom:8px">Most Useful Aspect</div><p style="background:#1e293b;padding:15px;border-radius:8px">{{ $mf->most_useful_aspect ?? 'N/A' }}</p></div>
<div><div style="font-size:12px;color:#94a3b8;margin-bottom:8px">Areas for Improvement</div><p style="background:#1e293b;padding:15px;border-radius:8px">{{ $mf->areas_for_improvement ?? 'N/A' }}</p></div>
</div>
<div style="display:flex;gap:10px;justify-content:flex-end;margin-top:24px">
<button class="btn btn-tl" wire:click="downloadSingleExcel({{ $mf->id }})">Download Excel</button>
<button class="btn btn-dk" wire:click="closeModal">Close</button>
</div></div></div></div>
@endif

<div id="chartData" style="display:none;"
     data-gm="{{ json_encode($this->globalMetrics) }}"
     data-rd="{{ json_encode($this->ratingDist) }}">
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

        mkChart('catBar', {
            type: 'bar',
            data: { labels: gml, datasets: [{ label: 'Score', data: gmv, backgroundColor: TC, borderRadius: 5 }] },
            options: { responsive: true, maintainAspectRatio: false, indexAxis: 'y', scales: { x: { beginAtZero: true, max: 5 } }, plugins: { legend: { display: false } } }
        });
    }

    if (document.readyState === 'loading') { document.addEventListener('DOMContentLoaded', () => setTimeout(drawAll, 80)); } else { setTimeout(drawAll, 80); }
    document.addEventListener('livewire:initialized', () => { Livewire.hook('morph.updated', () => { setTimeout(window.drawAll, 50); }); });

    window.toggleRow = function (i) { document.getElementById('trb' + i)?.classList.toggle('open'); document.getElementById('arr' + i)?.classList.toggle('open'); };
    window.filterAttr = function (sel, attr, q) { q = q.toLowerCase(); document.querySelectorAll(sel).forEach(el => { el.style.display = (el.getAttribute(attr) || '').includes(q) ? '' : 'none'; }); };
    window.filterFbTable = function (q) { q = q.toLowerCase(); document.querySelectorAll('#fbTable tbody tr').forEach(tr => { tr.style.display = (tr.dataset.s || '').includes(q) ? '' : 'none'; }); };
})();
</script>
</x-filament-panels::page>