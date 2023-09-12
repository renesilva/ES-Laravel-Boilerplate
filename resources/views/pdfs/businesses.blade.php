<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Reportes</title>
</head>
<body>
<div class="container">
  <h1>Reporte de Negocios</h1>
  <table>
    <thead>
    <tr>
      <th>Nombre</th>
      <th>Fecha de creación</th>
      <th>Logo</th>
    </tr>
    </thead>
    <tbody>
    @foreach($businesses as $business)
      <tr>
        <td>{{ $business->name }}</td>
        <td>{{ $business->created_at }}</td>
        <td>
          @if ($business->logo)
            <img src="{{ storage_path('/app/public/images/'.$business->logo) }}"
                 style="max-width: 100px; max-height: 100px; height:auto;"
                 alt="{{ $business->name  }}"/>
          @else
            <img src="https://linkspace.la/wp-content/themes/linkspacev2/assets/img/bootcamp-backend.jpg" alt="Logo"/>
          @endif
        </td>
      </tr>
    @endforeach
    </tbody>
  </table>
</div>
</body>
</html>
