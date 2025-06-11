<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #333;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
        }
        .header h1 {
            color: #0A2E2E;
            margin-bottom: 5px;
        }
        .header p {
            color: #666;
            margin-top: 0;
        }
        .section {
            margin-bottom: 25px;
        }
        .section h2 {
            color: #2A6363;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }
        .setting-item {
            margin-bottom: 10px;
        }
        .setting-label {
            font-weight: bold;
            display: inline-block;
            width: 40%;
        }
        .setting-value {
            display: inline-block;
            width: 58%;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p>Exporté le {{ $date }} par {{ $user }}</p>
    </div>
    
    @foreach($settings as $sectionName => $sectionSettings)
        <div class="section">
            <h2>{{ $sectionName }}</h2>
            
            @foreach($sectionSettings as $label => $value)
                <div class="setting-item">
                    <span class="setting-label">{{ $label }}:</span>
                    <span class="setting-value">{{ $value }}</span>
                </div>
            @endforeach
        </div>
    @endforeach
    
    <div class="footer">
        <p>Document généré automatiquement - {{ now()->format('Y') }} &copy; {{ config('app.name') }}</p>
    </div>
</body>
</html>