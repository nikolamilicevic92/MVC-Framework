@extends(default)

@include(redirect-information)

<form style="width:350px;margin:0 auto;" action="{{LOGIN_URI}}" method="post">
  <div class="form-group">
    <label for="email">Email</label>
    <input 
      type="email" class="form-control" id="email" 
      name="email" required
    >
  </div>
  <div class="form-group">
    <label for="password">Password</label>
    <input 
      type="password" class="form-control" id="password" 
      name="password" required
    >
  </div>
  <div class="form-group">
    <input type="checkbox" id="remember_me" name="remember_me">
    <label for="remember_me">Remember me</label>
  </div>
  <div class="form-group clearfix">
    {!! csrfFormField() !!}
    <a 
      style="text-decoration:none;margin-top:5px;"
      href="{{PASSWORD_RESET_URI}}" class="text-primary float-left" >
      Forgot your password?
    </a>
    <input type="submit" class="btn btn-primary float-right" value="Login">
  </div>
  <div>
    
  </div>
</form>