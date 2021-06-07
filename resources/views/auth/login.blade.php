@extends('layouts.master')
@section('title', 'Login')
@section('content')
  <div class="container" style="background-color:#ff004c;padding:2em;width:45em;">
    <div style="background-color:#cf004c;height:0.5em;">
    </div>
    <div class="row justify-content-center">
      <div class="">
        <div class="card" style="background-color:#20253d;color:white;">
          <div class="card-header" style="text-align:center;background-color:#20253d;color:white;"><h1> {{ __('Login') }}</h1></div>
          <div style="margin-left:1em;">
            {{ session()->get( 'uncomfirmed' ) }}
          </div>
          <div class="card-body">
            <form method="POST" action="{{ route('login') }}">
              @csrf

              <div class="form-group row">
                <label for="email" class="col-md-4 col-form-label" style="text-aling:right;"><h4>{{ __('E-Mail') }}</h4></label>

                <div class="col-md-6">
                  <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" style="background-color:#20253d;color:white;border-color: #ff004c;" autofocus>

                    @error('email')
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>
                </div>

                <div class="form-group row">
                  <label for="password" class="col-md-4 col-form-label"><h4>{{ __('Contraseña') }}</h4></label>

                  <div class="col-md-6">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" style="background-color:#20253d;color:white;border-color: #ff004c;">

                      @error('password')
                        <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                    </div>
                     <a class="btn btn-link" href="/password/reset" style="text-decoration: none;">
                          Forgot Your Password?
                        </a>
                      <div class="form-group row">
                    <div class="col-md-6 offset-md-4">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                        <label class="form-check-label" for="remember">
                          {{ __('Recordar login') }}
                        </label>
                      </div>
                    </div>
                  </div>
                  </div>
                <br>
                  <div class="form-group row mb-0">
                    <div class="col-md-12 offset-md-4">
                      <button type="submit" class="btn" style="background-color:#ff004c;color:white;rigth:0;width:48%;">
                        {{ __('Login') }}
                      </button>
                      </form>
                    </div>
                  </div>
                  Si no tienes cuenta, puedes <a href="/register">
                          {{ __('Registrarte') }}
                      </a>
              </div>
            </div>
          </div>
        </div>
      </div>



      <script src="https://www.gstatic.com/firebasejs/7.14.0/firebase-app.js"></script>
      <script src="https://www.gstatic.com/firebasejs/7.14.0/firebase-auth.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
      <script>
      // Initialize Firebase
      var firebaseConfig = {
        apiKey: "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX",
        authDomain: "XXXXXXXXX.firebaseapp.com",
        projectId: "XXXXXXXX-XXXX",
        storageBucket: "XXXXXXXXX.appspot.com",
        messagingSenderId: "XXXXXXXXXXXX",
        appId: "XXXXXXXXXXXXXXXXXXXXX",
        measurementId: "G-XXXXXXXX"
      };
      firebase.initializeApp(config);
      var facebookProvider = new firebase.auth.FacebookAuthProvider();
      var googleProvider = new firebase.auth.GoogleAuthProvider();
      var facebookCallbackLink = '/login/facebook/callback';
      var googleCallbackLink = '/login/google/callback';
      async function socialSignin(provider) {
        var socialProvider = null;
        if (provider == "facebook") {
          socialProvider = facebookProvider;
          document.getElementById('social-login-form').action = facebookCallbackLink;
        } else if (provider == "google") {
          socialProvider = googleProvider;
          document.getElementById('social-login-form').action = googleCallbackLink;
        } else {
          return;
        }
        firebase.auth().signInWithPopup(socialProvider).then(function(result) {
          result.user.getIdToken().then(function(result) {
            document.getElementById('social-login-tokenId').value = result;
            document.getElementById('social-login-form').submit();
          });
        }).catch(function(error) {
          // do error handling
          console.log(error);
        });
      }
      </script>

    @endsection
