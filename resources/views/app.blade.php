<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="3 Oak Flooring DataBase">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    <link
        type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.4/quill.snow.min.css"
        rel="stylesheet"
    />
    <link href="https://use.fontawesome.com/releases/v6.2.0/css/all.css" rel="stylesheet">
</head>

<body class="bg-neutral-300">
    <div id="app"></div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        window.jQuery || document.write('<script src="/lib/js/jquery-3.6.0.min.js"><\/script>')
    </script>

    @vite(['resources/scss/app.scss', 'resources/app/app.ts'])

</body>

</html>
