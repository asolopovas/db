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
    <div id="pages" style="text-align: right; margin-top: 10px;">
        <span style="font-weight: bold;">
            Page <span id="pageNumber"></span> of <span id="totalPages"></span>
        </span>
    </div>
</body>

</html>
