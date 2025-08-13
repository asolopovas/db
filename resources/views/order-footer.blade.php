<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>3 Oak Database</title>
    <meta name="description" content="3 Oak Database">
    <script>
        function subst() {
            var vars = {};
            var x = document.location.search.substring(1).split('&');
            for (var i in x) {
                var z = x[i].split('=', 2);
                vars[z[0]] = z[1];
            }

            var pageNumber = document.getElementById('pageNumber')
            var totalPages = document.getElementById('totalPages')

            pageNumber.innerText = vars['page']
            totalPages.innerText = vars['topage']
        }
    </script>
    @vite(['resources/scss/pdf.scss']) <!-- Vite handles SCSS -->
</head>

<body style="font-size: 12px; margin: 14px;" onload="subst()">
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 10px;">
        <tr>
            <td>
                @php
                    $details = [
                        strip_tags($company->address),
                        $company->vat_number ? 'VAT: ' . $company->vat_number : null,
                        $company->telephone1 ? 'Tel: ' . $company->telephone1 : null,
                        $company->telephone2 ? 'Tel 2: ' . $company->telephone2 : null,
                        $company->email ? 'Email: ' . $company->email : null,
                    ];
                    $detailsString = implode('; ', array_filter($details));
                @endphp

                {{ $company->name }}
                {{ strip_tags($detailsString) }}
                {!! str_replace('<br>', '', $company->notes) !!}
            </td>
        </tr>
    </table>

    <!-- Footer Section -->
    <div id="pages" style="text-align: right; margin-top: 10px;">
        <span style="font-weight: bold;">
            Page <span id="pageNumber"></span> of <span id="totalPages"></span>
        </span>
    </div>
</body>

</html>
