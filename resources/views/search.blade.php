@extends('layouts.master')

@section('title', 'Subida de archivos')

@section('content')
    <div style="background-color:#ff004c;margin-top:5em;padding:0.4em"> 
        <div style="background-color:#20253d;margin:0.5em;color:white;">
        <div style="background-color:#cf004c;height:0.5em;">
        </div>
        <div style="margin-left:2em;padding-bottom:2em;">
            <h1>Resultados de la busqueda</h1>
            <!-- search results -->
            @if (!empty($searchResult))
                 @for($i=0;$i<count($searchResult);$i++)
                 <a href="/user/.{{$searchResult[$i]['link']}}" style="color: inherit;">
                    <div class="resultBox">
                        <div style="display:inline-block;">
                            <img src="{{asset('uploads/'.$searchResult[$i]['fileName'])}}" width="100vw">
                        </div>
                        <div style="display:inline-block;vertical-align:top;margin-left:1em;">
                            <h3 style="color:#c261ff;display:inline-block;">{{$searchResult[$i]["name"]}}</h3> <e style="display:inline-block;"> - por {{$searchResult[$i]["username"]}}</e>
                            <br><i class="fa fa-heart" aria-hidden="true" style="color:#ff004c;"> {{$searchResult[$i]["likes"]}}</i>
                            <p>{{$searchResult[$i]["bio"]}}</p>
                        </div>
                    </div>
                @endfor
            @else
                <p>La busqueda no ha arrojado ningún resultado</p>
            @endif
        </div>
        </div>
    </div>
    <script>    
    var uploadFileInput = document.getElementById('fileUpload');
    var nameFile = document.getElementById('nameFile');
        function updateName(){
            document.getElementById("fileTitle2").innerHTML=nameFile.value;
        };
        function updateNameImage(){
            document.getElementById("imageTitle").innerHTML=nameFile.value;
        };
        uploadFileInput.addEventListener('change', () =>{
            var reader = new FileReader();
            if(isImage(uploadFileInput.files[0].name)){
                reader.onload = function (e) {
                document.getElementById('hidden64file').innerHTML=e.target.result;
                document.getElementById('imageUpload').hidden=false;
                document.getElementById('imagePreview').src=e.target.result;
                document.getElementById('imagePreview2').src=e.target.result;
                }
            reader.readAsDataURL(uploadFileInput.files[0]);
            }
            if(isAudio(uploadFileInput.files[0].name)){
                reader.onload = function (e) {
                document.getElementById('hidden64file').innerHTML=e.target.result;
                document.getElementById('musicUpload').hidden=false;
                document.getElementById('audioSource').src=e.target.result;
                document.getElementById('audioSource2').src=e.target.result;
                }
            reader.readAsDataURL(uploadFileInput.files[0]);
            }
        });
        function getExtension(filename) {
            var parts = filename.split('.');
            return parts[parts.length - 1];
        }
        function isImage(filename) {
            var ext = getExtension(filename);
            switch (ext.toLowerCase()) {
                case 'jpg':
                case 'gif':
                case 'bmp':
                case 'png':
                //etc
                return true;
            }
            return false;
            }
        function isAudio(filename) {
            var ext = getExtension(filename);
            switch (ext.toLowerCase()) {
                case 'wav':
                case 'mp3':
                case 'ogg':
                // etc
                return true;
            }
            return false;
            }

    </script>
    </div>
    <style>
    .resultBox{
        background-color:#20253d;
        width:90%;
    }
    .resultBox:hover{
        background-color: #00253d;
    }
    .column {
  width: 50%;
  padding: 10px;
  float: left;

}
* {
  box-sizing: border-box;
}
/* Clear floats after the columns */
.row:after {
  content: "";
  display: table;
  clear: both;
  float: right;
}
        .dropzone {
            background: #20253d;
            max-width: 850px;
            width: 100%;
            margin-left: 0%;
            margin-right: 0%;
            padding-top:6%;
            padding-left:10%;
            border: 7px dashed #ff004c;
            margin-top: 10px;
        }
        /* Start  styling the page */
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