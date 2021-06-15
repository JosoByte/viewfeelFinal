<html>
    <head>
        <title>@yield('title')</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body style="font-family: Calibri;background-image: url('{{ url('./img/background.jpg')}}');background-attachment: fixed;background-size:cover">
        <nav class="" style="background-color:#ff004c;height:0.5em;">
        </nav>
        <nav class="navbar navbar-expand-lg navbar-dark" style="background-color:#20253d;color: inherit;text-decoration: none;color:white;"> 
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon" style="color:white;"></span>
        </button>
        <img src="{{ url('./img/viewfeel.png')}}" height="25px" width="25px" style="display:inline-block; margin-left:2em;"><a class="navbar-brand" href="#" style="display:inline-block;">ViewFeel</a>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                <a class="nav-link" href="/" style="display:inline-block;text-decoration: none;color:white;margin-left:1em;font-size:1.5em;">Inicio</a>
                </li>
                @auth
                <li class="nav-item">
                <a class="nav-link" href="/map" style="display:inline-block;text-decoration: none;color:white;margin-left:1em;font-size:1.5em">Mapa</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" id="profileLink" href="/" style="display:inline-block;text-decoration: none;color:white;margin-left:1em;font-size:1.5em">Mi Perfil</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="/upload" style="display:inline-block;text-decoration: none;color:white;margin-left:1em;font-size:1.5em">Subir arte</a>
                </li>
                @endauth
                <li class="nav-item">
                <a class="nav-link" href="/contacto" style="display:inline-block;text-decoration: none;color:white;margin-left:1em;font-size:1.5em">Contacto</a>
                </li>
                </ul>&nbsp;
                <form method="POST" action="{{ route('search') }}">
                @csrf
                    <i class="fa fa-search" aria-hidden="true" style="color:#ff004c;"> </i> &nbsp;
                    <input type="text" name="search" class="" placeholder="Buscar..." aria-label="" aria-describedby="basic-addon1" style="border-color: #ff004c;color:white;background-color:#20253d;border-radius: 3px;width:25vw;margin-top:1em;">
                    &nbsp;&nbsp;<button type="submit" class="btn" style="background-color:#ff004c;color:white;">
                    {{ __('Buscar') }}
                    </button>
                </form>
                @auth
                    <div style="margin-left:2em;">
                        <h5 id="loginName"></h5>
                    </div>
                    <a href="{{ route('logoff') }}">
                        <button class="btn btn-danger" style="margin-left:1em;">Salir</button>
                    </a>
                    <!-- chat -->
                    <div style="position:fixed; bottom: 10; right:10;">
                        <div id="chatFrame" hidden>
                            <div style="background-color:#ff004c;margin-top:5em;padding:0.4em">
                            <div style="display:flex;justify-content:right;margin-bottom:0.2em;">
                                <e>ViewFeel Chat&nbsp;&nbsp;</e>
                                <button type="button" id="chatButtonClose"  href="#" onclick="hideChat()" class="btn" style="background-color:#20253d;border-radius:0px;color:white;font-size:0.9em;padding-right:1.5em;padding-left:1.5em;padding-top:0;height:1.5em;">X</button><br>
                            </div>
                                <div id="chatFrameI"  style="margin-right:10%;margin-bottom:1vh;">
                                    <iframe src="/chat"  height="400em" width="300vw" title="description"></iframe>
                                </div>
                                <input type="text" id="chatTextInput" name="chatTextInput" class="" placeholder="Escribe..." aria-label="" aria-describedby="basic-addon1" style="border-color: #ff004c;color:white;background-color:#20253d;border-radius: 3px;width:100%;margin-top:1em;">
                            </div>
                        </div>
                        <div id="chatButton">
                            <button type="button" id=""  href="#" onclick="openChat()" class="btn" style="background-color:#ff004c;border-radius:0px;color:white;font-size:1.3em;padding-right:2em;padding-left:2em;">Abrir chat</button><br>
                        </div>
                    </div>
                @endauth
            </div>
</nav>
        @show

        <div class="container">z
            @yield('content')
        </div>
    </body>
    <script>
        function hideChat(){
            document.getElementById('chatFrame').hidden=true;
            document.getElementById('chatButton').hidden=false;
        }
        function openChat(){
            document.getElementById('chatFrame').hidden=false;
            document.getElementById('chatButton').hidden=true;
        }
            document.getElementById('loginName').innerHTML="Eres "+document.getElementById('hiddenNameCurrent').innerHTML;
            document.getElementById('profileLink').href="/user/"+document.getElementById('hiddenNameCurrent').innerHTML;
        </script>
    <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"></script>
</html>