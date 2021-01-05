<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <title>Cadastros</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
  <h2>Cadastros efetuados</h2>           
  <table class="table">
    <thead>
      <tr>
        <th>id</th>
        <th>nome</th>
        <th>email</th>
        <th>ddd</th>
        <th>telefone</th>
      </tr>
    </thead>
    <tbody>
      @foreach($cadastros as $cadastro)
        <tr>
          <th scope="row">{{ $cadastro->id }}</th>
          <td>{{ $cadastro->first_name }}</td>
          <td>{{ $cadastro->email_address }}</td>
          <td>{{ $cadastro->ddd_home }}</td>
          <td>{{ $cadastro->phone_home }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>

</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

</body>
</html>
