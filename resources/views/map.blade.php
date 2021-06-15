@extends('layouts.master')

@section('title', 'Mapa')

@section('content')
    <div style="background-color:#ff004c;margin-top:5em;padding:0.4em"> 
        <div style="background-color:#20253d;margin:0.5em;color:white;">
        <div style="background-color:#cf004c;height:0.5em;">
        </div>
        <div style="margin-left:2em;padding-bottom:2em;margin-right:1em;">
            <h1>Mapa</h1>
            <div style="">
                <div style="">
                    <div id="mapid" style="width:97%;height:35em;margin-right:5em;" ></div>
                        <br>
                        <button id="crearPinButton" class="btn" style="background-color:#ff004c;color:white;" onclick="createPin()">
                                {{ __('Crear pin') }}
                        </button>
                        <div id="crearPin" hidden>
                            <h5>Crear pin</h5>
                            <form method="POST" action="{{ route('uploadPin') }}">
                            @csrf
                            <div style="display:inline-block">
                                <label for="fname">Latitud:</label>
                                <input type="text" id="latitud" name="latitud" style="border-color: #ff004c;color:white;background-color:#20253d;border-radius: 3px;width:100%;margin-top:1em;"><br><br>
                            </div>
                            <div style="display:inline-block">
                                <label for="fname">Longitud:</label>
                                <input type="text" id="longitud" name="longitud" style="border-color: #ff004c;color:white;background-color:#20253d;border-radius: 3px;width:100%;margin-top:1em;"><br><br>
                            </div>
                            <div class="" style="margin-right:auto;">
                                <p>Arrastra tu archivo aquí o pulsa para subir</p>
                                <input class="dropzone" id="fileUpload" name="file" type="file" single style="width: 90 %;vertical-align: middle">
                            </div>
                            <textarea id="hidden64file" name="hidden64file" hidden></textarea>
                            <br>
                            <label for="fname">Descripción</label>
                            <input type="text" id="desc" name="desc" style="border-color: #ff004c;color:white;background-color:#20253d;border-radius: 3px;width:97%;margin-top:1em;"><br><br>
                            <button type="submit" class="btn" style="background-color:#ff004c;color:white;">
                                {{ __('Crear pin') }}
                            </button>
                            </form>
                        </div>
                </div>
                <h5>Vista de pin</h5>
                <div id="emptySelectMap" style="flex-grow: 4;margin-left:1em;">Selecciona o crea un pin en el mapa.</div>
                <div id="selectedMap" style="flex-grow: 4;margin-left:1em;" hidden>
                    <div style="display:flex;justify-content:center;">
                        <img id="imagePin" width="500vw" src="" style="">
                    </div>
                    <div style="display:flex;justify-content:center;margin-top:1em;">
                        <i id="imageDesc"></i>
                    </div>
                    <div style="display:flex;justify-content:center;margin-top:0.4em;">
                        <a id="imageUser"><a>
                    </div>
                    <input type="text" id="hiddenPinIndex" name="hiddenPinIndex" hidden/>
                    <div style="display:flex;justify-content:center;margin-top:0.4em;">
                    <div class="rate">
                            <input type="radio" id="star5" onclick="rate(5)" name="rate" value="5" />
                            <label for="star5" title="text">5 stars</label>
                            <input type="radio" id="star4" onclick="rate(4)" name="rate" value="4" />
                            <label for="star4" title="text">4 stars</label>
                            <input type="radio" id="star3" onclick="rate(3)"name="rate" value="3" />
                            <label for="star3" title="text">3 stars</label>
                            <input type="radio" id="star2" onclick="rate(2)" name="rate" value="2" />
                            <label for="star2" title="text">2 stars</label>
                            <input type="radio" id="star1" onclick="rate(1)" name="rate" value="1" />
                            <label for="star1" title="text">1 star</label>
                        </div>
                    </div>
                </div>
            </div>
            <br>
        </div>
        </div>
        </div>
    </div>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.2/dropzone.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.2/min/dropzone.min.js"></script>
    <script>
    //LEAFTLET map
    var map = L.map('mapid').setView([51.505, -0.09], 13);
    var pins = {!! json_encode($pins) !!};
    console.log(pins);
    var onMarkerClick = function(e){
        var index=this.options.customId;
        document.getElementById("emptySelectMap").hidden=true;
        document.getElementById("imagePin").src="../uploads/"+pins[index]["file"];
        document.getElementById("imageDesc").innerHTML='"'+pins[index]["desc"]+'"';
        document.getElementById("imageUser").innerHTML=pins[index]["username"];
        document.getElementById("imageUser").href="user/"+pins[index]["username"];
        document.getElementById("hiddenPinIndex").value=index;
        axios.get("/checkRate",{params:{index:index}}).then(function(response) {
            var rate=Math.round(response.data);
            radiobtn = document.getElementById("star"+rate).checked = true;
        });
        document.getElementById("selectedMap").hidden=false;
    }
    for(var i=0;i<pins.length;i++){
        var index=i;
        console.log(i);
        L.marker([pins[i]["latitud"], pins[i]["longitud"]],{customId: i}).addTo(map).on('click', onMarkerClick);
    }
    L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://cloudmade.com">CloudMade</a>',
    }).addTo(map);
    map.panTo(new L.LatLng(40.4165, -3.70256));
    map.setZoom(6);
    map.addEventListener('click', function(ev) {
        lat = ev.latlng.lat;
        lng = ev.latlng.lng;
        console.log(lat+"  -  "+lng);
        document.getElementById('latitud').value=lat;
        document.getElementById('longitud').value=lng;
    });
    function rate(number){
        var index=document.getElementById("hiddenPinIndex").value;
        axios.post("/rate",{rate:number, index:index}).then(() => {
        });
    }
    function createPin(){
        if(document.getElementById("crearPin").hidden==false){
            document.getElementById("crearPin").hidden=true;
        }else{
            document.getElementById("crearPin").hidden=false;
        }
    }
    function updatePing(){
        for(var i=0;i<pins.length;i++){
            console.log(i);
            var index=i;
            L.marker([pins[i]["latitud"], pins[i]["longitud"]]).addTo(map).on('click', function(e) {
                document.getElementById("emptySelectMap").hidden=true;
                document.getElementById("imagePin").src="../uploads/"+pins[index]["file"];
                document.getElementById("selectedMap").hidden=false;
            });
        }
    }

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
    .rate {
    float: left;
    height: 46px;
    padding: 0 10px;
    }
    .rate:not(:checked) > input {
        position:absolute;
        top:-9999px;
    }
    .rate:not(:checked) > label {
        float:right;
        width:1em;
        overflow:hidden;
        white-space:nowrap;
        cursor:pointer;
        font-size:30px;
        color:#ccc;
    }
    .rate:not(:checked) > label:before {
        content: '★ ';
    }
    .rate > input:checked ~ label {
        color: #ffc700;    
    }
    .rate:not(:checked) > label:hover,
    .rate:not(:checked) > label:hover ~ label {
        color: #deb217;  
    }
    .rate > input:checked + label:hover,
    .rate > input:checked + label:hover ~ label,
    .rate > input:checked ~ label:hover,
    .rate > input:checked ~ label:hover ~ label,
    .rate > label:hover ~ input:checked ~ label {
        color: #c59b08;
    }
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