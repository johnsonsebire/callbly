<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Contacts Export</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        h1 {
            font-size: 18px;
            text-align: center;
            margin-bottom: 20px;
            color: #1e1e2d;
        }
        .info {
            font-size: 10px;
            text-align: right;
            margin-bottom: 10px;
            color: #7e8299;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background-color: #f5f8fa;
            border-bottom: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
            color: #3f4254;
        }
        td {
            border-bottom: 1px solid #eee;
            padding: 8px;
            font-size: 11px;
        }
        tr:nth-child(even) {
            background-color: #fbfbfb;
        }
        .footer {
            text-align: center;
            font-size: 10px;
            color: #7e8299;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h1>Contacts Export</h1>
    <div class="info">Generated on: {{ date('F j, Y, g:i a') }}</div>
    
    <table>
        <thead>
            <tr>
                @foreach($headers as $header)
                    <th>{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($contacts as $contact)
                <tr>
                    @foreach(array_keys($headers) as $column)
                        <td>
                            @if(strpos($column, 'custom_field_') === 0)
                                @php
                                    $fieldName = str_replace('custom_field_', '', $column);
                                    $customFieldValue = $contact->custom_fields[$fieldName] ?? '';
                                    
                                    // Format the value based on field type if we have the field definition
                                    if(isset($customFields)) {
                                        $customField = $customFields->firstWhere('name', $fieldName);
                                        if ($customField) {
                                            if ($customField->type === 'checkbox') {
                                                $customFieldValue = $customFieldValue ? 'Yes' : 'No';
                                            } elseif ($customField->type === 'date' && $customFieldValue) {
                                                $customFieldValue = \Carbon\Carbon::parse($customFieldValue)->format('Y-m-d');
                                            }
                                        }
                                    }
                                @endphp
                                {{ $customFieldValue }}
                            @else
                                {{ $contact->{$column} ?? '' }}
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        &copy; {{ date('Y') }} Callbly - Total contacts: {{ count($contacts) }}
    </div>
</body>
</html>