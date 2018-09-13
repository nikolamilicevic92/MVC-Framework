
  @if(user()->status('guest'))
  <ul class="navbar float-right">
    <li><a href="{{LOGIN_URI}}">Login</a></li>
    <li><a href="{{REGISTER_URI}}">Register</a></li>
  </ul>
  @else
  <ul class="navbar float-right">
    <li><a href="/logout">Logout</a></li>
  </ul>
  @endif