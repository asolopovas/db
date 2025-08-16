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
</body>

</html>
