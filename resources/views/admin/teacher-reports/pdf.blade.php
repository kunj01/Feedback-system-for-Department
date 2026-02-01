<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Teacher Performance Report - {{ $teacher->name }}</title>
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
        .header .teacher-info {
            background-color: #eff6ff;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
        }
        .header .teacher-info h3 {
            font-size: 14px;
            margin: 0 0 5px 0;
            color: #1f2937;
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
            line-height: 1.5;
        }
        .strengths-weaknesses {
            display: table;
            width: 100%;
        }
        .strength-col, .weakness-col {
            display: table-cell;
            width: 50%;
            padding: 10px;
            vertical-align: top;
        }
        .strength-col {
            background-color: #d1fae5;
            border: 1px solid #a7f3d0;
        }
        .weakness-col {
            background-color: #fee2e2;
            border: 1px solid #fecaca;
        }
        .strength-col h4 {
            color: #065f46;
            font-size: 10px;
            margin: 0 0 8px 0;
        }
        .weakness-col h4 {
            color: #991b1b;
            font-size: 10px;
            margin: 0 0 8px 0;
        }
        .item-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .item-list li {
            margin: 5px 0;
            font-size: 8px;
            line-height: 1.4;
        }
        .recommendation {
            margin: 8px 0;
            padding: 8px;
            background-color: #eff6ff;
            border-left: 3px solid #2563eb;
            font-size: 9px;
            page-break-inside: avoid;
        }
        .comments-box {
            margin: 10px 0;
            padding: 8px;
            background-color: #fff7ed;
            border: 1px solid #fed7aa;
            border-radius: 5px;
            page-break-inside: avoid;
        }
        .comments-box h5 {
            font-size: 9px;
            margin: 0 0 5px 0;
            color: #9a3412;
        }
        .comments-box ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .comments-box li {
            font-size: 8px;
            margin: 3px 0;
            color: #6b7280;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #d1d5db;
            font-size: 8px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>U. V. Patel College of Engineering</h1>
        <h2>Teacher Performance Report</h2>
        <div class="teacher-info">
            <h3>{{ $teacher->name }}</h3>
            @if($teacher->department)
                <p><strong>Department:</strong> {{ $teacher->department }}</p>
            @endif
            @if($teacher->subjects->isNotEmpty())
                <p><strong>Subjects:</strong> {{ $teacher->subjects->pluck('name')->implode(', ') }}</p>
            @endif
        </div>
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
                <p style="font-size: 8px; color: #6b7280;">Based on {{ $responses->count() }} responses across 8 parameters</p>
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

    <!-- Question-wise Statistical Analysis -->
    <div class="section">
        <div class="section-title">3. Question-wise Statistical Analysis</div>
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
                @foreach($analysis['question_stats'] as $stats)
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
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Strengths and Areas for Improvement -->
    <div class="section">
        <div class="section-title">4. Strengths and Areas for Improvement</div>
        <div class="strengths-weaknesses">
            <div class="strength-col">
                <h4>Strengths (Rating ≥ 4.5)</h4>
                @if(!empty($analysis['strengths_weaknesses']['strengths']))
                    <ul class="item-list">
                        @foreach($analysis['strengths_weaknesses']['strengths'] as $strength)
                            <li><strong>{{ $strength['average'] }}</strong> - {{ $strength['question'] }}</li>
                        @endforeach
                    </ul>
                @else
                    <p style="font-size: 8px; font-style: italic;">No parameters scored 4.5 or higher</p>
                @endif
            </div>
            <div class="weakness-col">
                <h4>Areas for Improvement (Rating < 3.0)</h4>
                @if(!empty($analysis['strengths_weaknesses']['weaknesses']))
                    <ul class="item-list">
                        @foreach($analysis['strengths_weaknesses']['weaknesses'] as $weakness)
                            <li><strong>{{ $weakness['average'] }}</strong> - {{ $weakness['question'] }}</li>
                        @endforeach
                    </ul>
                @else
                    <p style="font-size: 8px; font-style: italic;">No parameters scored below 3.0 - Good overall performance!</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Student Comments on Low Ratings -->
    @if(!empty($analysis['low_rating_reasoning']))
        <div class="section">
            <div class="section-title">5. Student Comments on Low Ratings</div>
            @foreach($analysis['low_rating_reasoning'] as $field => $reasons)
                @if(!empty($reasons))
                    <div class="comments-box">
                        <h5>{{ $analysis['question_stats'][$field]['question'] ?? $field }}</h5>
                        <ul>
                            @foreach($reasons as $reason)
                                <li>• {{ $reason }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            @endforeach
        </div>
    @endif

    <!-- Recommendations -->
    <div class="section">
        <div class="section-title">6. Recommendations</div>
        @foreach($analysis['recommendations'] as $index => $recommendation)
            <div class="recommendation">
                <strong>{{ $index + 1 }}.</strong> {{ $recommendation }}
            </div>
        @endforeach
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>This report was automatically generated by the Student Feedback System</p>
        <p>U. V. Patel College of Engineering - Academic Year 2023-24</p>
    </div>
</body>
</html>
