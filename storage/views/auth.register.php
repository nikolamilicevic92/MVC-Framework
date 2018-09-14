<?php //This view extends default layout ?>

<?php require $__includes__ . "includes.redirect-information.php"; ?>

<form style="width:350px;margin:0 auto;" action="<?php echo htmlspecialchars(REGISTER_URI); ?>" method="post">
  <div class="form-group">
    <label for="name">Name</label>
    <input 
      type="text" class="form-control" id="name" 
      name="name" value="<?php echo htmlspecialchars($name); ?>" required
    >
  </div>
  <div class="form-group">
    <label for="email">Email</label>
    <input 
      type="email" class="form-control" id="email" 
      name="email" value="<?php echo htmlspecialchars($email); ?>" required
    >
  </div>
  <div class="form-group">
    <label for="password">Password</label>
    <input 
      type="password" class="form-control" id="password" 
      name="password" value="<?php echo htmlspecialchars($password); ?>" required
    >
  </div>
  <div class="form-group">
    <label for="confirm">Confirm password</label>
    <input 
      type="password" class="form-control" id="confirm" 
      name="confirm" value="<?php echo htmlspecialchars($confirm); ?>" required
    >
  </div>
  <div class="form-group clearfix">
    <?php echo ( csrfFormField() ); ?>
    <input type="submit" class="btn btn-primary float-right" value="Register">
  </div>
</form>
