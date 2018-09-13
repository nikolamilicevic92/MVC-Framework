<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="assets/css/core/framework.css">
  <title>Dev Panel</title>
  <style>
    body {
      padding: 50px;
    }
    form {
      width: 550px;
      margin: auto;
    }
    input[type="checkbox"] {
      vertical-align: middle;
    }
    label {
      display: inline-block;
      width: 128px;
      text-align: right;
    }
    label:nth-of-type(2) {
      width: 100px;
    }
  </style>
</head>
<body>

  <?php require $__includes__ . "includes.redirect-information.php"; ?>
  
  <form action="/dev-panel/controller" method="post">
    <h2>Create a controller</h2>
    <label for="name">Controller name</label>
    <input type="text" name="name" id="name" class="form-control inline">
    <label for="resource">Resource</label>
    <input type="checkbox" name="resource" id="resource">
    <?php echo ( csrfFormField() ); ?>
    <input type="submit" class="btn btn-primary" value="Create">
  </form>
  <form action="/dev-panel/model" method="post">
    <h2>Create a model</h2>
    <label for="name">Model name</label>
    <input type="text" name="name" id="name" class="form-control inline">
    <label for="create_table">Create table</label>
    <input type="checkbox" name="create_table" id="create_table">
    <?php echo ( csrfFormField() ); ?>
    <input type="submit" class="btn btn-primary" value="Create">
  </form>
</body>
</html>