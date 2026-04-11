<div class="overflow-x-auto border border-gray-200 rounded-lg dark:border-gray-700">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-100 dark:bg-gray-800">
    <tr>
        <th class="px-4 py-2 border-b">Category / Aspect</th>

        @php
            $ratings = [
                5 => 'Excellent',
                4 => 'Very Good',
                3 => 'Good',
                2 => 'Fair',
                1 => 'Poor'
            ];
        @endphp

        @foreach($ratings as $value => $label)
            <th class="px-2 py-2 text-center border-b">
                <div class="font-semibold">{{ $value }}</div>
                <div class="text-xs text-gray-500">{{ $label }}</div>
            </th>
        @endforeach
    </tr>
</thead>
        <tbody>
            @php 
                $sections = [
                    'Content & Relevance' => [
                        'clarity_objectives' => 'Clarity of objectives',
                        'relevance_to_role' => 'Relevance to your role',
                        'quality_materials' => 'Quality of materials/examples',
                        'practical_applicability' => 'Practical applicability'
                    ],
                    'Trainer Effectiveness' => [
                        'subject_knowledge' => 'Subject knowledge',
                        'clarity_communication' => 'Clarity & communication',
                        'engagement_interaction' => 'Engagement & interaction',
                        'time_management' => 'Time management'
                    ],
                    'Logistics' => [
                        'venue_platform_quality' => 'Venue / Platform quality',
                        'organization_coordination' => 'Organization & coordination',
                        'accommodation_boarding' => 'Accommodation & Boarding',
                        'transportation' => 'Transportation'
                    ]
                ];
            @endphp

            @foreach($sections as $title => $fields)
                <tr class="bg-gray-50 dark:bg-gray-900 font-bold">
                    <td colspan="6" class="px-4 py-2 border-b text-primary-600 uppercase tracking-wider">{{ $title }}</td>
                </tr>
                @foreach($fields as $key => $label)
                    <tr class="border-b dark:border-gray-700">
                        <td class="px-4 py-2">{{ $label }}</td>
                        @foreach([5,4,3,2,1] as $r)
                            <td class="px-2 py-2 text-center">
                                @if($getRecord()->feedback->$key == $r)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800 dark:bg-primary-900 dark:text-primary-200">●</span>
                                @else
                                    <span class="text-gray-300 dark:text-gray-600">-</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</div>