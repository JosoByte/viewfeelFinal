@extends('layouts.master')

@section('content')
<?php
// El mensaje
$mensaje = "No se lo digas a nadie pero flanders tiene piojos";

// Enviarlo
mail('mahelini0@gmail.com', 'Mi título', $mensaje);?>
<div class="container" style="background-color:#ff004c;padding:2em;width:45em;">
<div style="background-color:#cf004c;height:0.5em;">
    </div>
    <div class="row justify-content-center">
      <div class="">
      <div class="card" style="background-color:#20253d;color:white;">
          <div class="card-header"  style="text-align:center;background-color:#20253d;color:white;"><h1>Recuperar contraseña</h1>   </div>
          <div class="card-body">
            @if(Session::has('message'))
              <p class=" pb-3 alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
            @endif
            <form method="POST" action="{{ route('storeReset') }}">
            @csrf
            <div class="form-group">
            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus style="background-color:#20253d;color:white;border-color: #ff004c;">
            </div>
            <br>

            <div class="form-group">
            <button type="submit" class="btn" style="background-color:#ff004c;color:white;rigth:0;width:30%;">
                                    {{ __('Reset Password') }}
                                </button>
            </div>

            </from>

          </div>

        </div>
      </div>
    </div>
  </div>
@endsection