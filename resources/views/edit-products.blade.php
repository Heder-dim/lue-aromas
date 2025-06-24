<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="products-store-url" content="{{ route('products.store') }}">
    @vite('resources/css/app.css')
    <title>Luê Aromas</title>
</head>
<body>
    @include('layouts.navigation')
</body>
</html>