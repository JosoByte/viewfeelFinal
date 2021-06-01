@extends('layouts.master')

@section('content')
<div class="container" style="background-color:#ff004c;padding:2em;width:45em;">
<div style="background-color:#cf004c;height:0.5em;">
    </div>
    <div class="row justify-content-center">
        <div class="">
        <div class="card" style="background-color:#20253d;color:white;">
          <div class="card-header" style="text-align:center;background-color:#20253d;color:white;"><h1> {{ __('Registro') }}</h1></div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right"><h4>{{ __('Nombre') }}</h4></label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" style="background-color:#20253d;color:white;border-color: #ff004c;" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right" ><h4>{{ __('E-Mail') }}</h4></label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required style="background-color:#20253d;color:white;border-color: #ff004c;" autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right"><h4>{{ __('Contraseña') }}</h4></label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required style="background-color:#20253d;color:white;border-color: #ff004c;" autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right"><h5>{{ __('Confirmar contraseña') }}</h5></label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required style="background-color:#20253d;color:white;border-color: #ff004c;" autocomplete="new-password">
                            </div>
                        </div>
                        <hr>
                        <h4> ¡Dinos más de ti!: </h4>
                        <div class="form-group row">
                            <label for="imageProfile" class="col-md-4 col-form-label text-md-right" ><h4>{{ __('Imagen perfil') }}</h4></label>

                            <div class="col-md-6">
                                <input id="imageProfile" type="file" class="form-control @error('imageProfile') is-invalid @enderror" name="imageProfile" accept="image/*" value="{{ old('imageProfile') }}" required style="background-color:#20253d;color:white;border-color: #ff004c;" autocomplete="imageProfile">
                                <br>
                                <textarea id="hidden64ImageProfile" name="hidden64ImageProfile" hidden></textarea>
                                <img src="" id="profile-image" width="300px" hidden>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    <br>
                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right"><h4>{{ __('Biografía') }}</h4></label>
                            <div class="col-md-6">
                                <textarea id="biografia" type="text" class="form-control" name="biografia" required style="background-color:#20253d;color:white;border-color: #ff004c;height:10em;" autocomplete="new-password"></textarea>
                            </div>
                        </div>
                        <br>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn" style="background-color:#ff004c;color:white;">
                                    {{ __('Registrar') }}
                                </button>
                            </div>
                        </div>
                    </form>
                    Si ya tienes cuenta, puedes <a href="/login">
                          {{ __('entrar') }}
                      </a>
                </div>
            </div>
        </div>
    </div>
    <script>
        var uploadImage = document.getElementById('profile-image');
        var uploadImageInput = document.getElementById('imageProfile');
        uploadImageInput.addEventListener('change', () =>{
            var reader = new FileReader();
            reader.onload = function (e) {
                uploadImage.src = e.target.result;
                document.getElementById('hidden64ImageProfile').innerHTML=e.target.result;
                uploadImage.hidden=false;
            }
            reader.readAsDataURL(uploadImageInput.files[0]);
        });
    </script>
</div>
@endsection
