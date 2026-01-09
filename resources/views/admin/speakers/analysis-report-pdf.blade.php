<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NAAC Curriculum Feedback Analysis Report</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            font-size: 10pt;
            line-height: 1.4;
            color: #333;
        }
        
        .header {
            text-align: center;
            background: linear-gradient(135deg, #2563eb 0%, #4f46e5 100%);
            color: white;
            padding: 20px 15px;
            margin-bottom: 15px;
        }
        
        .header h1 {
            font-size: 18pt;
            margin-bottom: 8px;
            font-weight: bold;
        }
        
        .header h2 {
            font-size: 14pt;
            margin-bottom: 5px;
        }
        
        .header h3 {
            font-size: 12pt;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid rgba(255,255,255,0.3);
        }
        
        .header p {
            font-size: 9pt;
            margin-top: 5px;
        }
        
        .content {
            padding: 0 15px;
        }
        
        .section {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        
        .section-title {
            font-size: 14pt;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 2px solid #2563eb;
        }
        
        .analysis-list {
            list-style: none;
            padding-left: 0;
        }
        
        .analysis-list li {
            margin-bottom: 8px;
            padding-left: 15px;
            position: relative;
            text-align: justify;
        }
        
        .analysis-list li:before {
            content: "•";
            color: #2563eb;
            font-weight: bold;
            position: absolute;
            left: 0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 8pt;
        }
        
        table th {
            background-color: #2563eb;
            color: white;
            padding: 6px 4px;
            text-align: center;
            font-weight: bold;
            border: 1px solid #1e40af;
            font-size: 7pt;
        }
        
        table td {
            padding: 6px 4px;
            border: 1px solid #d1d5db;
            text-align: center;
        }
        
        table tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }
        
        .stat-cell-excellent { background-color: #d1fae5 !important; }
        .stat-cell-verygood { background-color: #dbeafe !important; }
        .stat-cell-good { background-color: #fef3c7 !important; }
        .stat-cell-satisfactory { background-color: #fed7aa !important; }
        .stat-cell-needs { background-color: #fee2e2 !important; }
        
        .overall-table th {
            background-color: #4f46e5;
        }
        
        .interpretation-box {
            background-color: #eff6ff;
            border-left: 4px solid #2563eb;
            padding: 10px;
            margin: 10px 0;
        }
        
        .interpretation-box h4 {
            font-size: 10pt;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .interpretation-box p {
            text-align: justify;
        }
        
        .recommendation {
            background-color: #f9fafb;
            border: 1px solid #d1d5db;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
            page-break-inside: avoid;
        }
        
        .recommendation-header {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
        }
        
        .recommendation-number {
            background-color: #2563eb;
            color: white;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 9pt;
            margin-right: 8px;
        }
        
        .recommendation-title {
            font-size: 10pt;
            font-weight: bold;
            flex: 1;
        }
        
        .priority-badge {
            font-size: 7pt;
            padding: 2px 6px;
            border-radius: 10px;
            font-weight: bold;
        }
        
        .priority-critical { background-color: #fee2e2; color: #991b1b; }
        .priority-high { background-color: #fed7aa; color: #9a3412; }
        .priority-medium { background-color: #fef3c7; color: #92400e; }
        .priority-low { background-color: #d1fae5; color: #065f46; }
        
        .recommendation-desc {
            margin-top: 5px;
            text-align: justify;
            font-size: 9pt;
            line-height: 1.4;
        }
        
        .conclusion-box {
            background: linear-gradient(135deg, #eff6ff 0%, #eef2ff 100%);
            border: 1px solid #bfdbfe;
            border-radius: 5px;
            padding: 12px;
        }
        
        .conclusion-box p {
            text-align: justify;
            margin-bottom: 8px;
        }
        
        .signature-section {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #d1d5db;
            display: flex;
            justify-content: space-between;
        }
        
        .signature-block {
            font-size: 9pt;
        }
        
        .signature-block p {
            margin-bottom: 3px;
        }
        
        .note {
            font-size: 8pt;
            font-style: italic;
            color: #6b7280;
            margin-top: 5px;
        }
        
        .page-break {
            page-break-after: always;
        }
        
        strong {
            font-weight: bold;
            color: #1e40af;
        }
    </style>
</head>
<body>
    
    {{-- Header --}}
    <div class="header">
        <h1>{{ $title_info['institute'] }}</h1>
        <h2>{{ $title_info['department'] }}</h2>
        <h3>Analysis of Feedback on Curriculum (External Expert)</h3>
        <p>Academic Year: {{ $title_info['academic_year'] }}</p>
        <p>Report Generated: {{ $title_info['report_date'] }} | Total Responses: {{ $title_info['total_responses'] }}</p>
    </div>

    <div class="content">
        
        {{-- 1. Descriptive Analysis --}}
        <div class="section">
            <h3 class="section-title">1. Descriptive Analysis</h3>
            <ul class="analysis-list">
                @foreach($descriptive_analysis as $point)
                    <li>{{ $point }}</li>
                @endforeach
            </ul>
        </div>

        {{-- Page break before statistical table --}}
        <div class="page-break"></div>

        {{-- 2. Question-wise Statistical Analysis --}}
        <div class="section">
            <h3 class="section-title">2. Question-wise Statistical Analysis</h3>
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;">S.No</th>
                        <th style="width: 35%;">Question / Parameter</th>
                        <th style="width: 10%;">Excellent<br/>(%)</th>
                        <th style="width: 10%;">Very Good<br/>(%)</th>
                        <th style="width: 10%;">Good<br/>(%)</th>
                        <th style="width: 10%;">Satisfactory<br/>(%)</th>
                        <th style="width: 10%;">Needs Improvement<br/>(%)</th>
                        <th style="width: 10%;">Average Rating</th>
                    </tr>
                </thead>
                <tbody>
                    @php $sno = 1; @endphp
                    @foreach($statistics as $questionKey => $stat)
                        <tr>
                            <td>{{ $sno++ }}</td>
                            <td style="text-align: left; padding-left: 6px;">{{ $stat['label'] }}</td>
                            <td class="stat-cell-excellent"><strong>{{ number_format($stat['percentages'][5], 1) }}%</strong></td>
                            <td class="stat-cell-verygood"><strong>{{ number_format($stat['percentages'][4], 1) }}%</strong></td>
                            <td class="stat-cell-good"><strong>{{ number_format($stat['percentages'][3], 1) }}%</strong></td>
                            <td class="stat-cell-satisfactory"><strong>{{ number_format($stat['percentages'][2], 1) }}%</strong></td>
                            <td class="stat-cell-needs"><strong>{{ number_format($stat['percentages'][1], 1) }}%</strong></td>
                            <td><strong style="color: #2563eb;">{{ number_format($stat['average'], 2) }}</strong></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- 3. Overall Consolidated Summary --}}
        <div class="section">
            <h3 class="section-title">3. Overall Consolidated Summary</h3>
            <table class="overall-table">
                <thead>
                    <tr>
                        <th>Excellent (%)</th>
                        <th>Very Good (%)</th>
                        <th>Good (%)</th>
                        <th>Satisfactory (%)</th>
                        <th>Needs Improvement (%)</th>
                        <th>Overall Average Rating</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="stat-cell-excellent"><strong>{{ number_format($overall_summary['percentages'][5], 2) }}%</strong></td>
                        <td class="stat-cell-verygood"><strong>{{ number_format($overall_summary['percentages'][4], 2) }}%</strong></td>
                        <td class="stat-cell-good"><strong>{{ number_format($overall_summary['percentages'][3], 2) }}%</strong></td>
                        <td class="stat-cell-satisfactory"><strong>{{ number_format($overall_summary['percentages'][2], 2) }}%</strong></td>
                        <td class="stat-cell-needs"><strong>{{ number_format($overall_summary['percentages'][1], 2) }}%</strong></td>
                        <td><strong style="color: #4f46e5; font-size: 10pt;">{{ number_format($overall_summary['average'], 2) }} / 5.00</strong></td>
                    </tr>
                </tbody>
            </table>
            <p class="note">
                Note: Overall summary is calculated based on {{ $overall_summary['total_questions'] }} questions 
                across {{ $overall_summary['total_responses'] }} expert responses 
                (Total data points: {{ $overall_summary['total_questions'] * $overall_summary['total_responses'] }}).
            </p>
        </div>

        {{-- Page break --}}
        <div class="page-break"></div>

        {{-- 4. Interpretation & Inference --}}
        <div class="section">
            <h3 class="section-title">4. Interpretation & Inference</h3>
            <div class="interpretation-box">
                <h4>Overall Assessment:</h4>
                <p>{{ $interpretations['overall'] }}</p>
            </div>
            <div class="interpretation-box">
                <h4>Parameter-wise Performance:</h4>
                <p>{{ $interpretations['balance'] }}</p>
            </div>
        </div>

        {{-- 5. Actionable Recommendations --}}
        <div class="section">
            <h3 class="section-title">5. Actionable Recommendations</h3>
            @foreach($recommendations as $index => $recommendation)
                <div class="recommendation">
                    <div class="recommendation-header">
                        <span class="recommendation-number">{{ $index + 1 }}</span>
                        <span class="recommendation-title">{{ $recommendation['title'] }}</span>
                        <span class="priority-badge priority-{{ strtolower($recommendation['priority']) }}">
                            {{ $recommendation['priority'] }} Priority
                        </span>
                    </div>
                    <div class="recommendation-desc">{{ $recommendation['description'] }}</div>
                </div>
            @endforeach
        </div>

        {{-- 6. Conclusion --}}
        <div class="section">
            <h3 class="section-title">6. Conclusion</h3>
            <div class="conclusion-box">
                <p>
                    The comprehensive analysis of curriculum feedback from external industry and academic experts 
                    provides valuable insights into the strengths and areas for improvement of the current curriculum. 
                    With an overall average rating of <strong>{{ number_format($overall_summary['average'], 2) }} out of 5.00</strong>, 
                    the curriculum demonstrates 
                    @if($overall_summary['average'] >= 4.5)
                        exceptional quality and industry alignment.
                    @elseif($overall_summary['average'] >= 4.0)
                        very good quality with strong industry alignment.
                    @elseif($overall_summary['average'] >= 3.5)
                        satisfactory quality with scope for enhancement.
                    @else
                        significant potential for improvement.
                    @endif
                </p>
                <p>
                    The feedback validates the curriculum's effectiveness in achieving learning outcomes while highlighting 
                    opportunities for continuous enhancement. Implementation of the recommended actions will further strengthen 
                    the curriculum's relevance, rigor, and responsiveness to evolving industry requirements. Regular stakeholder 
                    engagement and systematic curriculum review mechanisms will ensure sustained quality improvement and 
                    alignment with NAAC/NBA accreditation standards.
                </p>
                <p>
                    The department is committed to addressing the feedback constructively and implementing necessary modifications 
                    to enhance curriculum quality, thereby ensuring that graduates are well-prepared to meet the challenges of 
                    the professional world and contribute meaningfully to society.
                </p>
            </div>
        </div>

        {{-- Signature Section --}}
        <div class="signature-section">
            <div class="signature-block">
                <p><strong>Prepared by:</strong> Academic Coordinator</p>
                <p>Date: {{ $title_info['report_date'] }}</p>
            </div>
            <div class="signature-block" style="text-align: right;">
                <p><strong>Reviewed by:</strong> Head of Department</p>
                <p>Signature: _________________</p>
            </div>
        </div>

    </div>

</body>
</html>
