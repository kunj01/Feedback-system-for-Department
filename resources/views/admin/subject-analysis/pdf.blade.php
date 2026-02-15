<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Subject Analysis Report - {{ $subject->name }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 18px;
            margin: 0 0 8px 0;
            color: #1f2937;
        }
        .header h2 {
            font-size: 16px;
            margin: 0 0 10px 0;
            color: #2563eb;
        }
        .header h3 {
            font-size: 14px;
            margin: 0 0 10px 0;
            color: #374151;
        }
        .header p {
            margin: 3px 0;
            font-size: 9px;
            color: #6b7280;
        }
        .section {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 10px;
            padding: 8px;
            background-color: #f3f4f6;
            border-left: 4px solid #2563eb;
        }
        .subsection-title {
            font-size: 11px;
            font-weight: bold;
            color: #374151;
            margin: 15px 0 8px 0;
            padding: 6px;
            background-color: #e5e7eb;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 8px;
        }
        th {
            background-color: #f9fafb;
            padding: 6px 4px;
            text-align: center;
            border: 1px solid #d1d5db;
            font-weight: bold;
            font-size: 7px;
        }
        td {
            padding: 5px 4px;
            border: 1px solid #e5e7eb;
            text-align: center;
        }
        td:first-child {
            text-align: left;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .avg-badge {
            display: inline-block;
            padding: 3px 6px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 8px;
        }
        .avg-excellent { background-color: #d1fae5; color: #065f46; }
        .avg-good { background-color: #dbeafe; color: #1e40af; }
        .avg-fair { background-color: #fef3c7; color: #92400e; }
        .avg-poor { background-color: #fee2e2; color: #991b1b; }
        .summary-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        .summary-item {
            display: table-cell;
            width: 50%;
            padding: 10px;
            border: 1px solid #d1d5db;
            vertical-align: top;
        }
        .summary-item h4 {
            font-size: 10px;
            margin: 0 0 8px 0;
            color: #1f2937;
        }
        .big-number {
            font-size: 24px;
            font-weight: bold;
            color: #2563eb;
            margin: 5px 0;
        }
        .distribution-bar {
            margin: 5px 0;
            font-size: 8px;
        }
        .analysis-point {
            margin: 8px 0;
            padding-left: 15px;
            border-left: 3px solid #60a5fa;
            font-size: 9px;
            line-height: 1.6;
        }
        .strength-list, .weakness-list {
            margin: 8px 0;
            padding-left: 15px;
            font-size: 9px;
        }
        .strength-list li:before {
            content: "✓ ";
            color: #10b981;
            font-weight: bold;
        }
        .weakness-list li:before {
            content: "⚠ ";
            color: #f59e0b;
            font-weight: bold;
        }
        .recommendation {
            margin: 6px 0;
            padding: 6px;
            background-color: #eff6ff;
            border-left: 3px solid #3b82f6;
            font-size: 9px;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 8px;
            color: #6b7280;
            padding: 10px 0;
            border-top: 1px solid #e5e7eb;
        }
        @page {
            margin: 15mm;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>Smt. K. D. Patel Department of Information Technology</h1>
        <h2>Subject Analysis Report</h2>
        <h3>{{ $subject->name }} ({{ $subject->code }})</h3>
        <p><strong>Semester:</strong> {{ $analysis['title_info']['semester'] }}</p>
        <p><strong>Teachers:</strong> {{ $analysis['title_info']['teachers'] }}</p>
        <p><strong>Academic Year:</strong> {{ $analysis['title_info']['academic_year'] }}</p>
        <p><strong>Report Generated:</strong> {{ $analysis['title_info']['report_date'] }}</p>
        <p><strong>Total Responses:</strong> {{ $analysis['title_info']['total_responses'] }}</p>
    </div>

    <!-- Descriptive Analysis -->
    <div class="section">
        <div class="section-title">1. Descriptive Analysis</div>
        @foreach($analysis['descriptive_analysis'] as $statement)
            <div class="analysis-point">{!! strip_tags($statement, '<strong><b><em><i>') !!}</div>
        @endforeach
    </div>

    <!-- Overall Performance Summary -->
    <div class="section">
        <div class="section-title">2. Overall Performance Summary</div>
        <div class="summary-grid">
            <div class="summary-item">
                <h4>Overall Average Rating</h4>
                <div class="big-number">{{ $analysis['overall_average'] }}/5.0</div>
                <p style="font-size: 8px; color: #6b7280;">Based on {{ $analysis['total_responses'] }} responses</p>
            </div>
            <div class="summary-item">
                <h4>Rating Distribution</h4>
                @foreach($analysis['rating_distribution'] as $rating => $percentage)
                    <div class="distribution-bar">
                        {{ $rating }}: <strong>{{ $percentage }}%</strong>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Teacher-wise Breakdown (if multiple teachers) -->
    @if(count($analysis['teacher_breakdown']) > 1)
    <div class="section">
        <div class="section-title">3. Teacher-wise Performance Breakdown</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 50%;">Teacher Name</th>
                    <th>Responses</th>
                    <th>Average Rating</th>
                </tr>
            </thead>
            <tbody>
                @foreach($analysis['teacher_breakdown'] as $teacher)
                    <tr>
                        <td style="text-align: left;">{{ $teacher['name'] }}</td>
                        <td>{{ $teacher['response_count'] }}</td>
                        <td>
                            <span class="avg-badge 
                                @if($teacher['average_rating'] >= 4.5) avg-excellent
                                @elseif($teacher['average_rating'] >= 4.0) avg-good
                                @elseif($teacher['average_rating'] >= 3.0) avg-fair
                                @else avg-poor
                                @endif">
                                {{ $teacher['average_rating'] }}/5.0
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Strengths and Weaknesses -->
    <div class="section">
        <div class="section-title">{{ count($analysis['teacher_breakdown']) > 1 ? '4' : '3' }}. Strengths and Areas for Improvement</div>
        
        <h4 style="font-size: 10px; margin: 10px 0 5px 0;">Strengths (≥4.5):</h4>
        @if(!empty($analysis['strengths_weaknesses']['strengths']))
            <ul class="strength-list" style="list-style: none;">
                @foreach($analysis['strengths_weaknesses']['strengths'] as $strength)
                    <li>{{ $strength['question'] }} ({{ $strength['average'] }}/5.0)</li>
                @endforeach
            </ul>
        @else
            <p style="font-size: 9px; font-style: italic; color: #6b7280;">No parameters scored above 4.5</p>
        @endif

        <h4 style="font-size: 10px; margin: 10px 0 5px 0;">Areas for Improvement (<3.5):</h4>
        @if(!empty($analysis['strengths_weaknesses']['weaknesses']))
            <ul class="weakness-list" style="list-style: none;">
                @foreach($analysis['strengths_weaknesses']['weaknesses'] as $weakness)
                    <li>{{ $weakness['question'] }} ({{ $weakness['average'] }}/5.0)</li>
                @endforeach
            </ul>
        @else
            <p style="font-size: 9px; font-style: italic; color: #6b7280;">No parameters scored below 3.5</p>
        @endif
    </div>

    <!-- Question-wise Statistical Analysis -->
    <div class="section">
        <div class="section-title">{{ count($analysis['teacher_breakdown']) > 1 ? '5' : '4' }}. Question-wise Statistical Analysis</div>

        @php
            $questionCategories = [
                'Student Experience' => ['prepare_for_class', 'ask_questions_freely', 'actively_participate', 'feel_comfortable_sharing', 'developing_skills'],
                'Instructor Experience' => ['instructor_approachable', 'instructor_effective', 'presentations_clear', 'instructor_stimulated', 'instructor_used_time', 'instructor_introduces_concepts', 'instructor_positive_environment', 'instructor_communicates'],
                'Course Structure' => ['course_objectives_clear', 'course_material_relevant', 'assignments_helpful', 'feedback_timely'],
                'Learning Environment' => ['classroom_conducive', 'resources_adequate', 'overall_satisfaction'],
            ];
        @endphp

        @foreach($questionCategories as $categoryName => $categoryFields)
            @php
                $categoryStats = array_filter($analysis['question_stats'], function($key) use ($categoryFields) {
                    return in_array($key, $categoryFields);
                }, ARRAY_FILTER_USE_KEY);
            @endphp

            @if(!empty($categoryStats))
                <div class="subsection-title">Section: {{ $categoryName }}</div>
                <table>
                    <thead>
                        <tr>
                            <th style="width: 35%;">Question</th>
                            <th>Strongly<br>Agree</th>
                            <th>Agree</th>
                            <th>Neutral</th>
                            <th>Disagree</th>
                            <th>Strongly<br>Disagree</th>
                            <th>Average</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categoryFields as $field)
                            @if(isset($analysis['question_stats'][$field]))
                                @php $stats = $analysis['question_stats'][$field]; @endphp
                                <tr>
                                    <td style="text-align: left;">{{ $stats['question'] }}</td>
                                    <td>{{ $stats['percentages']['Strongly Agree'] ?? 0 }}%<br><small>({{ $stats['responses']['Strongly Agree'] }})</small></td>
                                    <td>{{ $stats['percentages']['Agree'] ?? 0 }}%<br><small>({{ $stats['responses']['Agree'] }})</small></td>
                                    <td>{{ $stats['percentages']['Neutral'] ?? 0 }}%<br><small>({{ $stats['responses']['Neutral'] }})</small></td>
                                    <td>{{ $stats['percentages']['Disagree'] ?? 0 }}%<br><small>({{ $stats['responses']['Disagree'] }})</small></td>
                                    <td>{{ $stats['percentages']['Strongly Disagree'] ?? 0 }}%<br><small>({{ $stats['responses']['Strongly Disagree'] }})</small></td>
                                    <td>
                                        <span class="avg-badge 
                                            @if($stats['average'] >= 4.5) avg-excellent
                                            @elseif($stats['average'] >= 4.0) avg-good
                                            @elseif($stats['average'] >= 3.0) avg-fair
                                            @else avg-poor
                                            @endif">
                                            {{ $stats['average'] }}
                                        </span>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            @endif
        @endforeach
    </div>

    <!-- Recommendations -->
    <div class="section">
        <div class="section-title">{{ count($analysis['teacher_breakdown']) > 1 ? '6' : '5' }}. Recommendations</div>
        @foreach($analysis['recommendations'] as $index => $recommendation)
            <div class="recommendation">
                <strong>{{ $index + 1 }}.</strong> {{ $recommendation }}
            </div>
        @endforeach
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>This report is automatically generated by the Student Feedback Management System</p>
        <p>© {{ date('Y') }} Smt. K. D. Patel Department of Information Technology</p>
    </div>
</body>
</html>
