
  <?php if(user()->status('guest')): ?>
  <ul class="navbar float-right">
    <li><a href="<?php echo htmlspecialchars(LOGIN_URI); ?>">Login</a></li>
    <li><a href="<?php echo htmlspecialchars(REGISTER_URI); ?>">Register</a></li>
  </ul>
  <?php else: ?>
  <ul class="navbar float-right">
    <li><a href="/logout">Logout</a></li>
  </ul>
  <?php endif; ?>