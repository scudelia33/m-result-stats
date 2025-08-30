<!doctype html>
<html>

<x-header title="{{ $title }}" />

<x-sidebar />

<body data-bs-theme="dark" data-page="{{ $dataPage }}" @class([
    'container-lg',
])>
    {{ $slot }}
</body>

</html>
