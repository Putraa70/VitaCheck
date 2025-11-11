@props(['title' => config('app.name', 'Laravel')])
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $title }}</title>
  {{-- sementara tanpa @vite --}}
</head>
<body class="bg-gray-100">
  {{ $slot }}
</body>
</html>
