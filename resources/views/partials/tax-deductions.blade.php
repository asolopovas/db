<table class="table-design table">
    <thead>
        <tr>
            <th>Deduction Ref</th>
            <th>Date</th>
            <th class="text-end">Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($tax_deductions as $deduction)
            <tr>
                <td>{{ $deduction->ref }}</td>
                <td>{{ $deduction->date->format('d M Y') }}</td>
                <td class="text-end">@currency($deduction->amount)</td>
            </tr>
        @endforeach
    </tbody>
</table>
