<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Training Feedback Report {{ $year }}</title>
<style>
@page { margin: 16mm 14mm 18mm 14mm; }
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 10.5px; color: #1f2937; background: #fff; line-height: 1.45; }

.hdr { background: #0f1117; color: #fff; padding: 20px 22px 16px; border-radius: 8px; margin-bottom: 14px; }
.hdr-title { font-size: 19px; font-weight: 700; margin-bottom: 3px; }
.hdr-sub { font-size: 9.5px; color: rgba(255,255,255,.45); margin-bottom: 12px; }
.hdr-row { display: table; width: 100%; border-top: 1px solid rgba(255,255,255,.1); padding-top: 10px; }
.hdr-cell { display: table-cell; padding: 0 10px; }
.hdr-cell:first-child { padding-left: 0; }
.hf-l { color: rgba(255,255,255,.4); font-size: 8px; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; }
.hf-v { color: #a5b4fc; font-weight: 600; font-size: 10px; }

.section { border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden; margin-bottom: 14px; page-break-inside: avoid; }
.sec-title { background: #f9fafb; padding: 8px 13px; font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; color: #6b7280; border-bottom: 1px solid #e5e7eb; }
.sec-body { padding: 13px; }

/* Summary row */
.sum-table { width: 100%; border-collapse: separate; border-spacing: 8px; }
.sum-cell { text-align: center; padding: 10px 8px; border: 1px solid #e5e7eb; border-radius: 7px; border-top-width: 3px; }
.sum-lbl { font-size: 8px; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; color: #9ca3af; margin-bottom: 4px; }
.sum-val { font-size: 21px; font-weight: 800; color: #111827; }
.sum-sub { font-size: 9px; color: #9ca3af; }

/* Metric pills */
.pill-table { width: 100%; border-collapse: separate; border-spacing: 5px; }
.pill-cell { text-align: center; padding: 8px 4px; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 7px; }
.pill-val { font-size: 16px; font-weight: 800; }
.pill-lbl { font-size: 7.5px; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; color: #9ca3af; }
.pill-bar { height: 3px; background: #e5e7eb; border-radius: 99px; overflow: hidden; margin-top: 4px; }
.pill-fill { height: 3px; border-radius: 99px; display: block; }

/* Rating table */
.rd-table { width: 100%; border-collapse: collapse; }
.rd-table th { background: #1e293b; color: #fff; padding: 5px 9px; font-size: 8px; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; }
.rd-table td { padding: 6px 9px; border-bottom: 1px solid #f3f4f6; font-size: 10px; }
.rd-table tr:last-child td { border-bottom: none; }
.rbar { background: #f3f4f6; border-radius: 99px; height: 9px; overflow: hidden; }
.rbar-f { height: 9px; border-radius: 99px; display: block; }

/* Data table */
.data-table { width: 100%; border-collapse: collapse; font-size: 10px; }
.data-table th { background: #1e293b; color: #fff; padding: 5px 8px; text-align: left; font-size: 8px; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; white-space: nowrap; }
.data-table td { padding: 6px 8px; border-bottom: 1px solid #f3f4f6; color: #374151; vertical-align: middle; }
.data-table tbody tr:nth-child(even) td { background: #fafafa; }
.data-table tbody tr:last-child td { border-bottom: none; }

/* Score colours */
.sg { color: #16a34a; font-weight: 700; }
.sm2 { color: #d97706; font-weight: 700; }
.sr { color: #e11d48; font-weight: 700; }

.pbar { height: 5px; background: #f3f4f6; border-radius: 99px; overflow: hidden; min-width: 50px; }
.pbar-f { height: 5px; border-radius: 99px; display: block; }

.footer { margin-top: 16px; text-align: center; font-size: 8px; color: #9ca3af; border-top: 1px solid #e5e7eb; padding-top: 8px; }
</style>
</head>
<body>

{{-- Header --}}
<div class="hdr">
    <div class="hdr-title">Training Feedback — Infographic Report</div>
    <div class="hdr-sub">Generated {{ now()->format('d M Y, h:i A') }}</div>
    <table class="hdr-row"><tr>
        @foreach($filterLabels as $fl => $fv)
        <td class="hdr-cell">
            <div class="hf-l">{{ $fl }}</div>
            <div class="hf-v">{{ $fv }}</div>
        </td>
        @endforeach
    </tr></table>
</div>

{{-- Summary --}}
@php
    $maxMet = collect($metrics)->max();
    $topMet = collect($metrics)->keys()->first(fn($k) => $metrics[$k] == $maxMet) ?? '—';
    $sumRows = [
        ['Total Feedbacks', number_format($totalFeedbacks), 'Collected',    '#4f46e5'],
        ['Global Average',  number_format($globalAvg,1).'/5', 'Avg overall', '#d97706'],
        ['Best Metric',     $topMet,  number_format($maxMet,2).'/5',         '#16a34a'],
        ['Centres',         $centreData->count(), 'In report',               '#0284c7'],
        ['Report Year',     $year, 'Academic year',                          '#7c3aed'],
    ];
@endphp
<div class="section">
    <div class="sec-title">Summary</div>
    <div class="sec-body">
        <table class="sum-table"><tr>
            @foreach($sumRows as $s)
            <td class="sum-cell" style="border-top-color:{{ $s[3] }}">
                <div class="sum-lbl">{{ $s[0] }}</div>
                <div class="sum-val">{{ $s[1] }}</div>
                <div class="sum-sub">{{ $s[2] }}</div>
            </td>
            @endforeach
        </tr></table>
    </div>
</div>

{{-- Metric pills --}}
<div class="section">
    <div class="sec-title">All Metric Averages (out of 5)</div>
    <div class="sec-body">
        @php $chunks = array_chunk(array_keys($metrics), 6, true); @endphp
        @foreach($chunks as $chunk)
        <table class="pill-table" style="margin-bottom:5px"><tr>
            @foreach($chunk as $ml)
            @php $mv = $metrics[$ml]; $mc = $mv >= 4 ? '#16a34a' : ($mv >= 3 ? '#d97706' : '#e11d48'); @endphp
            <td class="pill-cell">
                <div class="pill-val" style="color:{{ $mc }}">{{ number_format($mv,1) }}</div>
                <div class="pill-lbl">{{ $ml }}</div>
                <div class="pill-bar"><span class="pill-fill" style="width:{{ ($mv/5)*100 }}%;background:{{ $mc }}"></span></div>
            </td>
            @endforeach
        </tr></table>
        @endforeach
    </div>
</div>

{{-- Rating distribution --}}
<div class="section">
    <div class="sec-title">Overall Rating Distribution</div>
    <div class="sec-body">
        @php
            $rdMax   = max(array_values($ratingDist) ?: [1]);
            $rdTotal = array_sum($ratingDist);
            $rdCols  = ['#ef4444','#f97316','#eab308','#22c55e','#3b82f6'];
        @endphp
        <table class="rd-table">
            <thead><tr><th>Rating</th><th>Count</th><th style="width:55%">Distribution</th><th>%</th></tr></thead>
            <tbody>
                @foreach($ratingDist as $r => $cnt)
                @php $pct = $rdMax > 0 ? round(($cnt / $rdMax) * 100, 1) : 0; $tp = $rdTotal > 0 ? round(($cnt / $rdTotal) * 100, 1) : 0; @endphp
                <tr>
                    <td style="white-space:nowrap">
                        @for($i = 1; $i <= 5; $i++)<span style="color:{{ $i <= $r ? '#f59e0b' : '#d1d5db' }}">★</span>@endfor
                        <strong style="margin-left:4px">{{ $r }}/5</strong>
                    </td>
                    <td style="text-align:center;font-weight:700">{{ $cnt }}</td>
                    <td><div class="rbar"><span class="rbar-f" style="width:{{ $pct }}%;background:{{ $rdCols[$r-1] }}"></span></div></td>
                    <td style="text-align:center;color:#6b7280">{{ $tp }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Centre performance --}}
@if($centreData->isNotEmpty())
<div class="section">
    <div class="sec-title">Centre-wise Performance</div>
    <div class="sec-body" style="padding:0">
        <table class="data-table">
            <thead><tr>
                <th>#</th><th>Centre</th><th>Feedbacks</th><th>Overall</th>
                <th>Clarity</th><th>Relevance</th><th>Quality</th><th>Knowledge</th>
                <th>Venue</th><th>Done</th><th style="width:70px">Bar</th>
            </tr></thead>
            <tbody>
                @foreach($centreData as $ci => $c)
                @php $ov = $c->avg_overall; $cls = $ov >= 4 ? 'sg' : ($ov >= 3 ? 'sm2' : 'sr'); $bc = $ov >= 4 ? '#16a34a' : ($ov >= 3 ? '#d97706' : '#e11d48'); @endphp
                <tr>
                    <td style="color:#9ca3af">{{ $ci+1 }}</td>
                    <td style="font-weight:700">{{ $c->centre_name }}</td>
                    <td style="text-align:center">{{ $c->total_feedbacks }}</td>
                    <td class="{{ $cls }}" style="text-align:center;font-size:12px">{{ number_format($ov,1) }}</td>
                    <td style="text-align:center">{{ number_format($c->avg_clarity,1) }}</td>
                    <td style="text-align:center">{{ number_format($c->avg_relevance,1) }}</td>
                    <td style="text-align:center">{{ number_format($c->avg_quality,1) }}</td>
                    <td style="text-align:center">{{ number_format($c->avg_knowledge,1) }}</td>
                    <td style="text-align:center">{{ number_format($c->avg_venue,1) }}</td>
                    <td style="text-align:center">{{ $c->completed_count }}</td>
                    <td><div class="pbar"><span class="pbar-f" style="width:{{ ($ov/5)*100 }}%;background:{{ $bc }}"></span></div></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- Top trainings --}}
@if($topTrainings->isNotEmpty())
<div class="section">
    <div class="sec-title">Top Rated Trainings</div>
    <div class="sec-body" style="padding:0">
        <table class="data-table">
            <thead><tr><th>#</th><th>Training Title</th><th>Feedbacks</th><th>Avg Rating</th><th style="width:80px">Bar</th></tr></thead>
            <tbody>
                @foreach($topTrainings as $ti => $t)
                @php $cls = $t->avg_rating >= 4 ? 'sg' : ($t->avg_rating >= 3 ? 'sm2' : 'sr'); $bc = $t->avg_rating >= 4 ? '#16a34a' : ($t->avg_rating >= 3 ? '#d97706' : '#e11d48'); @endphp
                <tr>
                    <td style="color:#9ca3af">{{ $ti+1 }}</td>
                    <td style="font-weight:600">{{ $t->title }}</td>
                    <td style="text-align:center">{{ $t->feedback_count }}</td>
                    <td class="{{ $cls }}" style="text-align:center;font-size:12px">{{ number_format($t->avg_rating,1) }}/5</td>
                    <td><div class="pbar"><span class="pbar-f" style="width:{{ ($t->avg_rating/5)*100 }}%;background:{{ $bc }}"></span></div></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<div class="footer">
    Training Feedback Analytics Report &nbsp;·&nbsp; Year: {{ $year }} &nbsp;·&nbsp; Generated {{ now()->format('d M Y') }} &nbsp;·&nbsp; Confidential
</div>

</body>
</html>