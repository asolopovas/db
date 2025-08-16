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
    <div id="pages" class="disabled">
    </div>
    <div id="last-page" class="disabled">
        <div class="row">
            <div class="small-5 column">
                @if ($company->id === 2)
                    {{ $company->name }} trading as 3 Oak Wood Flooring:<br>
                @else
                    {{ $company->name }}:<br>
                @endif
                {{ strip_tags($company->address) }}<br>
                @if ($company->vat_number)
                    VAT: {{ $company->vat_number }}
                @endif
                Tel: {{ $company->telephone1 }}
                @if ($company->telephone2)
                    Tel 2: {{ $company->telephone2 }}<br>
                @endif
                @if ($company->email)
                    Email: {{ $company->email }}
                @endif
            </div>
            <div class="small-7 column footnotes">
                N.B.:<br>
                {!! $company->notes !!}
            </div>
        </div>
        <div class="row">
            <div class="column padding-top-1">
                Important: Full or partial payment for this invoice is deemed as acceptance of our Terms and Condition.
                If you have any questions please clarify before making the payment.
            </div>
        </div>
    </div>
</body>

</html>
