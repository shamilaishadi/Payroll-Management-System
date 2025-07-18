<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Paysheet - {{ $summary['month'] }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
        }
        .container {
            max-width: 700px;
            margin: auto;
            padding: 20px;
            border: 1px solid #eaeaea;
            border-radius: 6px;
            background: #fafafa;
        }
        h2 {
            color: #0056b3;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        td, th {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        th {
            background: #f2f2f2;
        }
        .total {
            font-weight: bold;
            background-color: #e6f2ff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Paysheet - {{ $summary['month'] }}</h2>
        <p><strong>Name:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>

        <table>
            <tr><th>Description</th><th>Amount</th></tr>
            <tr><td>Basic Salary</td><td>Rs. {{ number_format($summary['basic_salary'], 2) }}</td></tr>
            <tr><td>Worked Hours</td><td>{{ $summary['worked_hours'] }} hrs</td></tr>
            <tr><td>Overtime Hours</td><td>{{ $summary['ot_hours'] }} hrs</td></tr>
            <tr><td>Overtime Pay</td><td>Rs. {{ number_format($summary['ot_pay'], 2) }}</td></tr>
            <tr><td>Absent Days</td><td>{{ $summary['absent_days'] }}</td></tr>
            <tr><td>Absent Deduction</td><td>- Rs. {{ number_format($summary['absent_deduction'], 2) }}</td></tr>
            <tr><td>Late Deduction</td><td>- Rs. {{ number_format($summary['late_deduction'], 2) }}</td></tr>
            <tr><td>EPF Deduction (8%)</td><td>- Rs. {{ number_format($summary['epf_deduction'], 2) }}</td></tr>
            <tr class="total"><td>Total Pay</td><td>Rs. {{ number_format($summary['total_pay'], 2) }}</td></tr>
        </table>

        <p style="margin-top: 20px;">If you have any questions, please contact the HR department.</p>
    </div>
</body>
</html>
