<div class="space-y-4">

    <div>
        <b>Employee Name:</b> {{ $record->nominee_name }}
    </div>

    <div>
        <b>Employee ID:</b> {{ $record->nominee_emp_id }}
    </div>

    <div>
        <b>Training:</b> {{ $record->training->title }}
    </div>

    <div>
        <b>Centre:</b> {{ $record->centre }}
    </div>

    <hr>

    @php $f = $record->feedback; @endphp

    <div><b>Clarity of Objectives:</b> {{ $f->clarity_objectives }}</div>
    <div><b>Relevance to Role:</b> {{ $f->relevance_to_role }}</div>
    <div><b>Quality of Materials:</b> {{ $f->quality_materials }}</div>
    <div><b>Practical Applicability:</b> {{ $f->practical_applicability }}</div>

    <div><b>Subject Knowledge:</b> {{ $f->subject_knowledge }}</div>
    <div><b>Communication:</b> {{ $f->clarity_communication }}</div>
    <div><b>Interaction:</b> {{ $f->engagement_interaction }}</div>
    <div><b>Time Management:</b> {{ $f->time_management }}</div>

    <div><b>Venue / Platform:</b> {{ $f->venue_platform_quality }}</div>
    <div><b>Organization:</b> {{ $f->organization_coordination }}</div>
    <div><b>Accommodation:</b> {{ $f->accommodation_boarding }}</div>
    <div><b>Transport:</b> {{ $f->transportation }}</div>

    <div><b>Met Expectations:</b> {{ $f->met_expectations }}</div>
    <div><b>Overall Rating:</b> {{ $f->overall_rating }}</div>

    <div>
        <b>Most Useful Aspect:</b>
        <p>{{ $f->most_useful_aspect }}</p>
    </div>

    <div>
        <b>Areas For Improvement:</b>
        <p>{{ $f->areas_for_improvement }}</p>
    </div>

</div>
