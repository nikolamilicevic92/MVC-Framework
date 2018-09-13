
@if(isset($errors))
  @foreach($errors as $error)
    <div class="alert-danger text-center">
      {{$error}}
    </div>
  @endforeach
@endif
@if(isset($success))
  @foreach($success as $successMessage)
    <div class="alert-success text-center">
      {{$successMessage}}
    </div>
  @endforeach
@endif