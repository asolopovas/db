<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>3 Oak Tm Wood Flooring Ltd. DataBase</title>
    <meta name="description" content="3 Oak Tm Wood Flooring Ltd. DataBase">
    <script>
        function subst() {
            var vars = {};
            var x = document.location.search.substring(1).split('&');
            for (var i in x) {
                var z = x[i].split('=', 2);
                vars[z[0]] = z[1];
            }
            var lastPage = document.getElementById('last-page');
            var pages = document.getElementById('pages');
            pages.innerText += 'Page ' + vars['page'] + ' of ' + vars['topage'];
            if (vars['page'] === vars['topage']) {
                lastPage.className = 'enabled';
            } else {
                pages.className = 'enabled';
            }
        }
    </script>
    @vite(['resources/scss/pdf.scss'])
</head>

<body id="footer-area" onload="subst()">

    <!-- Footer Section -->

        <table style="width: 100%; border-collapse: collapse; margin-bottom: 10px;">
            <tr>
                <td style="width: 200px">
                    @php
                        $details = [
                            $company->vat_number ? 'VAT: ' . $company->vat_number : null,
                            $company->telephone1 ? 'Tel: ' . $company->telephone1 : null,
                            $company->telephone2 ? 'Tel 2: ' . $company->telephone2 : null,
                            $company->email ? 'Email: ' . $company->email : null,
                        ];
                        $detailsString = implode(' ', array_filter($details));
                    @endphp


                    {{ $company->name }}<br>
                    {!! $company->address !!}
                    {!! $detailsString !!}
                </td>
                <td style="width:50%">
                    N.B.:<br>
                    {!! str_replace('<br>', '', $company->notes) !!}
                </td>
            </tr>
        </table>

        <div class="row">
            <div class="column padding-top-1">
                Important: Full or partial payment for this invoice is deemed as acceptance of our Terms and Condition.
                If you have any questions please clarify before making the payment.
            </div>
        </div>
        <span style="font-weight: bold;">
            <span id="totalPages"></span>
        </span>
</body>

</html>
