<html>
<head>
    <title>App Name - @yield('title')</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    @yield('css')
</head>
<body>

<div class="container pt-5">
    @yield('content')
</div>

<script src="js/jquery-3.4.1.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>