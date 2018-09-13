<?php //This view extends default layout ?>

<?php if(isset($errors)): ?>
  <?php foreach($errors as $error): ?>
    <div class="alert-danger text-center">
      <?php echo htmlspecialchars($error); ?>
    </div>
  <?php endforeach; ?>
<?php endif; ?>
<?php if(isset($errors)): ?>
  <?php foreach($success as $successMessage): ?>
    <div class="alert-success text-center">
      <?php echo htmlspecialchars($successMessage); ?>
    </div>
  <?php endforeach; ?>
<?php endif; ?>

<form style="width:350px;margin:0 auto;" action="<?php echo htmlspecialchars(PASSWORD_RESET_URI); ?>" method="post">
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
    <input type="submit" class="btn btn-primary float-right" value="Reset">
  </div>
</form>
