<table class="table-design">
    <tr>
        <td colspan="2">
            <table class="table-bordered table" style="width: 100%;">
                <thead>
                    <tr>
                        <th style="width: 150px;">Date Paid</th>
                        @if ($proforma)
                            <th>Description</th>
                        @endif
                        <th class="text-end">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($payments as $payment)
                        <tr>
                            <td>{{ $payment->date }}</td>
                            @if ($proforma)
                                <td>{{ $payment->description }}</td>
                            @endif
                            <td class="text-end">@currency($payment->amount)</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </td>
    </tr>
</table>
