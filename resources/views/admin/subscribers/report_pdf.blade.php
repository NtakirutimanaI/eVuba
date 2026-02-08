<!DOCTYPE html>
<html>

<head>
    <title>Subscribers Report</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: right;
            font-size: 10px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>eVuba Connect - Subscriber List</h2>
        <p>Generated on: {{ now()->format('Y-m-d H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Email Address</th>
                <th>Subscribed Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($subscribers as $subscriber)
                <tr>
                    <td>{{ $subscriber->id }}</td>
                    <td>{{ $subscriber->email }}</td>
                    <td>{{ $subscriber->created_at->format('Y-m-d H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table style="width: 100%; margin-top: 30px; border: none;">
        <tr>
            <td style="width: 50%; vertical-align: bottom; border: none; padding: 0;">
                <div style="font-size: 10px; color: #94a3b8; margin-bottom: 5px; text-transform: uppercase;">GENERATED
                    BY</div>
                <div style="font-size: 14px; font-weight: bold; color: #1e293b;">
                    {{ auth()->user()->name ?? 'System Admin' }}</div>
                <div style="font-size: 11px; color: #64748b; margin-bottom: 20px;">
                    {{ auth()->user()->role ?? 'Administrator' }}</div>
                <div style="border-bottom: 1px solid #e2e8f0; width: 200px; margin-bottom: 5px;"></div>
                <div style="font-size: 10px; color: #94a3b8;">Signature & Date</div>
            </td>
            <td style="width: 50%; vertical-align: top; border: none; padding: 0; text-align: right;">
                <div style="margin-top: 20px;">Page {PAGENO}</div>
            </td>
        </tr>
    </table>
</body>

</html>