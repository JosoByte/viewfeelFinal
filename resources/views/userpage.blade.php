@extends('layouts.master')

@section('title', 'Page Title')

@section('content')
    <div style="background-color:#ff004c;margin-top:5em;padding:0.4em">
        <div style="background-color:#20253d;margin-left:3em;margin-right:3em;margin-top:2em;color:white;">
        <div style="background-color:#cf004c;height:0.5em;">
        </div>
        <div>
        @if ($username!='')
            <p id="hiddenName" hidden>{{ $username }}</p> <!-- user info for navbar -->
            <p id="hiddenNameCurrent" hidden>{{ $currentUsername}}</p> <!-- user info for navbar -->
        @endif
        <div style="padding-left:1.5em;">
            <img src="{{$image}}" height="200px" width="200px" style="padding-top:1em;display:inline-block">
            <div style="display:inline-block;vertical-align: top; margin-top:1em;">
                <p style="font-size:1.6em;">Logros</p>
                <p>placeholder logros</p>
            </div>
        </div>
        <img src="{{ url('./img/divPerfil.png')}}" style="margin-top:0.5em;">
        <div style="margin-left:1.5em;">
            <div>
                <h1 style="display:inline-block">{{$username}} - Artista de nivel {{$level}}</h1>
            </div>
            <h2>Biografía</h2>
            <p>{{$bio}}</p>
        </div>
        </div>
        </div>
        <div style="background-color:#ff004c;margin-top:0em;padding:0.4em">
            <div style="background-color:#20253d;margin-left:1em;margin-right:1em;margin-top:2em;color:white;">
                <div style="background-color:#cf004c;height:0.5em;">
            </div>
            @if (count($gallery)==0)
            <h3>Esta galería está vacía</h3>
            @endif
            <div style="justify-items:center;">
                <div class="grid-container" style="padding-left:4em;">
                    @for ($i = 0; $i < count($gallery); $i++)
                        <div class="grid-item" style="width:85%;">
                        @if($gallery[$i]["type"]=="jpg" || $gallery[$i]["type"]=="gif" || $gallery[$i]["type"]=="png")
                            <img src="{{asset('uploads/'.$gallery[$i]['fileData'])}}" width="25%">
                            <p style="text-align:center;color:#c261ff;">{{$gallery[$i]['name']}}</p>
                        @endif
                        @if($gallery[$i]["type"]=="wav" || $gallery[$i]["type"]=="mp3" || $gallery[$i]["type"]=="ogg")
                            <div class="container-audio" style="width:100%">
                                <audio controls loop id="audioSource" src="{{asset($gallery[$i]['fileData'])}}">
                                        <source src="" type="audio/ogg">
                                        Your browser dose not Support the audio Tag
                                    </audio>
                            </div>
                            <div style="height:5em;overflow: hidden;width:100%;background-image:url('https://lh3.googleusercontent.com/proxy/6RuIpjPeuCQpHf4TQF4Hle25mj6G38bpu8_Jk3As5p8eqQV7jZrGhnlkOWPxuSYJPUOr0YoMYXU84vRml5VZkeKW6gz8TyudbI1TEFDAsC7EFaUovb3WDWNsScQNiz72ITJ3gatPDVk');background-size:100% 100%;">
                                <div id="musicBar{{$i}}" class="container-audio" style="margin-left:9%;padding-right:7%;;widht:100%">
                                    <div class="colum1">
                                        <div class="row"></div>
                                    </div>
                                    <div class="colum1">
                                        <div class="row"></div>
                                    </div>
                                    <div class="colum1">
                                        <div class="row"></div>
                                    </div>
                                    <div class="colum1">
                                        <div class="row"></div>
                                    </div>
                                    <div class="colum1">
                                        <div class="row"></div>
                                    </div>
                                    <div class="colum1">
                                        <div class="row"></div>
                                    </div>
                                    <div class="colum1">
                                        <div class="row"></div>
                                    </div>
                                    <div class="colum1">
                                        <div class="row"></div>
                                    </div>
                                    <div class="colum1">
                                        <div class="row"></div>
                                    </div>
                                    <div class="colum1">
                                        <div class="row"></div>
                                    </div>
                                    <div class="colum1">
                                        <div class="row"></div>
                                    </div>
                                    <div class="colum1">
                                        <div class="row"></div>
                                    </div>
                                    <div class="colum1">
                                        <div class="row"></div>
                                    </div>
                                    <div class="colum1">
                                        <div class="row"></div>
                                    </div>
                                    <div class="colum1">
                                        <div class="row"></div>
                                    </div>
                                    <div class="colum1">
                                        <div class="row"></div>
                                    </div>
                                    <div class="colum1">
                                        <div class="row"></div>
                                    </div>
                                    <div class="colum1">
                                        <div class="row"></div>
                                    </div>
                                    <div class="colum1">
                                        <div class="row"></div>
                                    </div>
                                    <div class="colum1">
                                        <div class="row"></div>
                                    </div>
                                    <div class="colum1">
                                        <div class="row"></div>
                                    </div>
                                    <div class="colum1">
                                        <div class="row"></div>
                                    </div>
                                    <div class="colum1">
                                        <div class="row"></div>
                                    </div>
                                    <div class="colum1">
                                        <div class="row"></div>
                                    </div>
                                    <div class="colum1">
                                        <div class="row"></div>
                                    </div>
                                    <div class="colum1">
                                        <div class="row"></div>
                                    </div>
                                </div>
                            </div>
                            <p style="text-align:center;color:#c261ff;">{{$gallery[$i]['name']}}</p>
                        @endif
                        </div>
                    @endfor
                </div>
            </div>
            <div>
        <div>
    </div>
    <script>
    function showMusicBars(bars){
        alert(bars);
        documennt.getElementById("musicBar"+bars).hidden=false;
    }
    </script>
    <style>
.grid-container {
 align-items: end;
  display: grid;
  grid-template-columns:repeat(auto-fit , 380px);
  padding-top: 40px;
}
.grid-item {
  font-size: 30px;
  text-align: center;
}
.container-audio {
    width: 100%;
    border-radius: 5px;
    background-color:;
    color: #444;
    margin-top: 20px;
    overflow: hidden;
}
audio {
  width:100%;
}
audio:nth-child(2), audio:nth-child(4), audio:nth-child(6) {
    margin: 5;
}
.container-audio .colum1 {
    width: 2.6%;
    height: 5em;
    display: inline-block;
    position: relative;
}
.container-audio .colum1:last-child {
    margin: 0;
}
.container-audio .colum1 .row {
    width: 100%;
    height: 100%;
    border-radius: 5px;
    background: linear-gradient(to up, #7700aa, #8800ff);
    position: absolute;
    -webkit-animation: Rofa 10s infinite ease-in-out;
    animation: Rofa 10s infinite ease-in-out;
    bottom: 0;
}
.container-audio .colum1:nth-of-type(1) .row {
    -webkit-animation-delay: 3.99s;
    animation-delay: 3.99s;
}
.container-audio .colum1:nth-of-type(2) .row {
    -webkit-animation-delay: 3.80s;
    animation-delay: 3.80s;
}
.container-audio .colum1:nth-of-type(3) .row {
    -webkit-animation-delay: 3.70s;
    animation-delay: 3.70s;
}
.container-audio .colum1:nth-of-type(4) .row {
    -webkit-animation-delay: 3.60s;
    animation-delay: 3.60s;
}
.container-audio .colum1:nth-of-type(5) .row {
    -webkit-animation-delay: 3.50s;
    animation-delay: 3.50s;
}
.container-audio .colum1:nth-of-type(6) .row {
    -webkit-animation-delay: 3.40s;
    animation-delay: 3.40s;
}
.container-audio .colum1:nth-of-type(7) .row {
    -webkit-animation-delay: 3.30s;
    animation-delay: 3.30s;
}
.container-audio .colum1:nth-of-type(8) .row {
    -webkit-animation-delay: 3.20s;
    animation-delay: 3.20s;
}
.container-audio .colum1:nth-of-type(9) .row {
    -webkit-animation-delay: 3.10s;
    animation-delay: 3.10s;
}
.container-audio .colum1:nth-of-type(10) .row {
    -webkit-animation-delay: 2.1s;
    animation-delay: 2.1s;
}
.container-audio .colum1:nth-of-type(11) .row {
    -webkit-animation-delay: 2.1s;
    animation-delay: 2.1s;
}
.container-audio .colum1:nth-of-type(12) .row {
    -webkit-animation-delay: 2.10s;
    animation-delay: 2.10s;
}
.container-audio .colum1:nth-of-type(13) .row {
    -webkit-animation-delay: 2.20s;
    animation-delay: 2.20s;
}
.container-audio .colum1:nth-of-type(14) .row {
    -webkit-animation-delay: 1.30s;
    animation-delay: 1.30s;
}
.container-audio .colum1:nth-of-type(15) .row {
    -webkit-animation-delay: 1.40s;
    animation-delay: 1.40s;
}
.container-audio .colum1:nth-of-type(16) .row {
    -webkit-animation-delay: 1.50s;
    animation-delay: 1.50s;
}
.container-audio .colum1:nth-of-type(17) .row {
    -webkit-animation-delay: 1.60s;
    animation-delay: 1.60s;
}
.container-audio .colum1:nth-of-type(18) .row {
    -webkit-animation-delay: 1.70s;
    animation-delay: 1.70s;
}
.container-audio .colum1:nth-of-type(19) .row {
    -webkit-animation-delay: 1.80s;
    animation-delay: 1.80s;
}
.container-audio .colum1:nth-of-type(20) .row {
    -webkit-animation-delay: 2s;
    animation-delay: 2s;
}

@-webkit-keyframes Rofa {
    0% {
        height: 0%;
        -webkit-transform: translatey(0);
        transform: translatey(0);
        background-color: yellow;
    }

    5% {
        height: 100%;
        -webkit-transform: translatey(15px);
        transform: translatey(15px);
        background-color: fuchsia;
    }
    10% {
        height: 90%;
        transform: translatey(0);
        -webkit-transform: translatey(0);
        background-color: bisque;
    }

    15% {
        height: 80%;
        -webkit-transform: translatey(0);
        transform: translatey(0);

        background-color: cornflowerblue;
    }
    20% {
        height: 70%;
        -webkit-transform: translatey(0);
        transform: translatey(0);
        background-color: cornflowerblue;
    }
    25% {
        height: 0%;
        -webkit-transform: translatey(0);
        transform: translatey(0);
        background-color: cornflowerblue;
    }
    30% {
        height: 70%;
        -webkit-transform: translatey(0);
        transform: translatey(0);
        background-color: cornflowerblue;
    }
    35% {
        height: 0%;
        -webkit-transform: translatey(0);
        transform: translatey(0);

        background-color: cornflowerblue;
    }
    40% {
        height: 60%;
        -webkit-transform: translatey(0);
        transform: translatey(0);

        background-color: cornflowerblue;
    }
    45% {
        height: 0%;
        -webkit-transform: translatey(0);
        transform: translatey(0);

        background-color: cornflowerblue;
    }
    50% {
        height: 50%;
        -webkit-transform: translatey(0);
        transform: translatey(0);

        background-color: cornflowerblue;
    }
    55% {
        height: 0%;
        -webkit-transform: translatey(0);
        transform: translatey(0);

        background-color: cornflowerblue;
    }
    60% {
        height: 40%;
        -webkit-transform: translatey(0);
        transform: translatey(0);

        background-color: cornflowerblue;
    }
    65% {
        height: 0%;
        -webkit-transform: translatey(0);
        transform: translatey(0);

        background-color: cornflowerblue;
    }
    70% {
        height: 30%;
        -webkit-transform: translatey(0);
        transform: translatey(0);

        background-color: cornflowerblue;
    }
    75% {
        height: 0%;
        -webkit-transform: translatey(0);
        transform: translatey(0);

        background-color: cornflowerblue;
    }
    80% {
        height: 20%;
        -webkit-transform: translatey(0);
        transform: translatey(0);

        background-color: cornflowerblue;
    }
    85% {
        height: 0%;
        -webkit-transform: translatey(0);
        transform: translatey(0);

        background-color: cornflowerblue;
    }
    90% {
        height: 10%;
        -webkit-transform: translatey(0);
        transform: translatey(0);

        background-color: cornflowerblue;
    }
    95% {
        height: 5%;
        -webkit-transform: translatey(0);
        transform: translatey(0);

        background-color: cornflowerblue;
    }
    100% {
        height: 0;
        -webkit-transform: translatey(0);
        transform: translatey(0);

        background-color: cornflowerblue;
    }
}
    </style>
@stop
