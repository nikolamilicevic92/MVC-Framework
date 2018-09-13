
<?php if(isset($errors)): ?>
  <?php foreach($errors as $error): ?>
    <div class="alert-danger text-center">
      <?php echo htmlspecialchars($error); ?>
    </div>
  <?php endforeach; ?>
<?php endif; ?>
<?php if(isset($success)): ?>
  <?php foreach($success as $successMessage): ?>
    <div class="alert-success text-center">
      <?php echo htmlspecialchars($successMessage); ?>
    </div>
  <?php endforeach; ?>
<?php endif; ?>