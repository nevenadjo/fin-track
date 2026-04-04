<h2>Report ({{ $start }} - {{ $end }})</h2>

<h3>Summary</h3>
<table>
    <tr>
        <th>Total Income</th>
        <th>Total Expense</th>
        <th>Balance</th>
    </tr>
    <tr>
        <td>{{ $summary['total_income'] }}</td>
        <td>{{ $summary['total_expense'] }}</td>
        <td>{{ $summary['balance'] }}</td>
    </tr>
</table>

<h3>Transactions</h3>
<table>
    <tr>
        <th>Date</th>
        <th>Category</th>
        <th>Amount</th>
    </tr>

    @foreach($transactions as $t)
        <tr>
            <td>{{ $t->date }}</td>
            <td>{{ $t->category->name ?? '-' }}</td>
            <td>{{ round($t->amount, 2) }}</td>
        </tr>
    @endforeach
</table>

<style>
    body { font-family: DejaVu Sans, sans-serif; }
    h2, h3 { margin-bottom: 10px; }
    table { border-collapse: collapse; margin-bottom: 20px; width:100%; border:1px solid gray; }
    th { background: #f2f2f2; }
    td { text-align: center; }
</style>
