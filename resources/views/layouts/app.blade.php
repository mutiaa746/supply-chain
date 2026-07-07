<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Supply Chain Risk Monitoring</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>

        body{
            background:#f4f6f9;
        }

        main{
            min-height:100vh;
        }

    </style>

</head>

<body>

@include('partials.navbar')

<div class="container-fluid">

    <div class="row">

        @include('partials.sidebar')

        <main class="col-md-10 ms-sm-auto px-4 py-4">

            @yield('content')

        </main>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>