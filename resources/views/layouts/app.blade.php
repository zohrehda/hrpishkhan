<!doctype html>
<html lang="en">
@include('layouts.head')
<body>
<div id="app">
    <main class="py-4">
        @yield('main')
        @include('layouts.messages')
        @yield('content')
    </main>
</div>
<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>
</body>
</html>
