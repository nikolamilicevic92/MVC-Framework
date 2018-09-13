@extends(default)

@include(redirect-information)

<form style="width:350px;margin:0 auto;" action="{{REGISTER_URI}}" method="post">
  <div class="form-group">
    <label for="name">Name</label>
    <input 
      type="text" class="form-control" id="name" 
      name="name" value="{{$name}}" required
    >
  </div>
  <div class="form-group">
    <label for="email">Email</label>
    <input 
      type="email" class="form-control" id="email" 
      name="email" value="{{$email}}" required
    >
  </div>
  <div class="form-group">
    <label for="password">Password</label>
    <input 
      type="password" class="form-control" id="password" 
      name="password" value="{{$password}}" required
    >
  </div>
  <div class="form-group">
    <label for="confirm">Confirm password</label>
    <input 
      type="password" class="form-control" id="confirm" 
      name="confirm" value="{{$confirm}}" required
    >
  </div>
  <div class="form-group clearfix">
    {!! csrfFormField() !!}
    <input type="submit" class="btn btn-primary float-right" value="Register">
  </div>
</form>
