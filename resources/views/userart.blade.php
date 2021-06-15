@extends('layouts.master')


@section('content')
@section('title', 'Página arte')
    <div style="background-color:#ff004c;margin-top:5em;padding:0.4em" onload="setTitle('{{$creatorUsername}} - {{$nameArt}}')">
        <div style="background-color:#20253d;margin-left:3em;margin-right:3em;margin-top:2em;color:white;">
        <div style="background-color:#cf004c;height:0.5em;">
        </div>
        @if ($username!='')
                <p id="hiddenName" hidden>{{ $username }}</p> <!-- user info for navbar -->
                <p id="hiddenNameCurrent" hidden>{{ $currentUsername}}</p> <!-- user info for navbar -->
            @endif
        <div style="margin-left:1em;margin-top:1em;color:#c261ff">
            <div style="margin: auto;width: 60%;height:50%;">
                <img src="{{asset('uploads/'.$filename)}}" width="100%" height="100%"style="">
            </div>
        </div>
        <div style="margin-left:1em;margin-top:1em;">
            <img src="{{$creatorImg}}" width="200vw" style="display:inline-block;">
            <div style="display:inline-block;vertical-align:middle;margin-left:1em;">
                <h2 style="color:#c261ff">{{$nameArt}}</h2>
                <h5>{{$creatorUsername}}</h5>
                <b>Nivel {{$creatorLevel}}</b>
                
            </div>
            <div style="display:inline-block;float:right;margin-top:5em;margin-right:1em;">
                <button class="btn btn-danger" id="likeButton" onclick="giveLike({{$likes}},'{{$creatorUsername}}',{{$artIndex}})" hidden><i class="fa fa-heart" id="likeButtonText" aria-hidden="true"></i> Me gusta</button>
                <button class="btn btn-danger" id="unlikeButton" onclick="removeLike({{$likes}},'{{$creatorUsername}}',{{$artIndex}})" hidden><i class="fa fa-heart" id="likeButtonText" aria-hidden="true"></i> No me gusta</button>
                <p id="likesCount">Me gusta: {{$likes}}</p>
            </div>
        </div>
        <hr>
        <div style="margin-left:1em;">
            <h1>Deja un comentario</h1>
            <textarea id="commentText" style="background-color:#20253d;color:white;border-color: #ff004c;width:38.5vw;"></textarea><br>
            <button class="btn" onclick="sendComment('{{$currentUsername}}','{{$creatorUsername}}',{{$artIndex}})" style="background-color:#ff004c;border-radius:0px;color:white;font-size:1.3em;padding-right:2.3em;padding-left:2.2em;margin-top:1em;">Enviar</button>
        </div>
        <hr>
        <div style="margin-left:1em;">
        @if ($comments=='')
            Parece que nadie a comentado todavía... ¡Se el primero en hacerlo!
        @else
            @for($i=0;$i<count($comments);$i++)
                <a class="comment" href="/user/{{$comments[$i]['commenterUser']}}">
                    <div class="comment" style="margin-bottom:2em;">
                        <img src="{{$comments[$i]["img"]}}" width="120vh" style="display:inline-block">
                        <div style="display:inline-block;margin-left:1em;"><b style="color:#c261ff">{{$comments[$i]["commenterUser"]}}</b><br>{{$comments[$i]["text"]}}</div>
                    </div>
                </a>
            @endfor
        @endif
            <br>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.js"></script>
    <script>
    function setTitle(title){
        document.title=title;
    }
    var art = {!! json_encode($filename) !!};
    checkLikes();
    function checkLikes(){
        console.log("a");
        axios.get("/checkIfLike",{params:{art:art}}).then(function(response) {
            console.log(response.data);
            if(response.data==0){
                document.getElementById("unlikeButton").hidden=true;
                document.getElementById("likeButton").hidden=false;
            }else{
                document.getElementById("likeButton").hidden=true;
                document.getElementById("unlikeButton").hidden=false;
            }
        });
    }
    function sendComment(commenter,username,artIndex){
        commentText=document.getElementById("commentText").value
        axios.post("/sendComment",{commenter:commenter, username:username, artIndex:artIndex, commentText:commentText}).then(() => {
            location.reload();
        });
    }
    function giveLike(likes,username,artIndex){
        console.log(likes);
        document.getElementById("likesCount").innerHTML="Me gusta: "+(likes+1);
        axios.post("/updateLikes/"+username+"/"+artIndex)
        checkLikes();
    }
    function removeLike(likes,username,artIndex){
        document.getElementById("likesCount").innerHTML="Me gusta: "+(likes-1);
        axios.post("/removeLike/"+username+"/"+artIndex)
        checkLikes();
    }
    var uploadImageInput = document.getElementById('imageProfile');
    uploadImageInput.addEventListener('change', () =>{
        var reader = new FileReader();
            reader.onload = function (e) {
                currentImageProfile.src = e.target.result;
                document.getElementById('hidden64ImageProfile').innerHTML=e.target.result;
            }
            reader.readAsDataURL(uploadImageInput.files[0]);
    });
    function cancelDel(){
        document.getElementById("delAccount").hidden=true;
    }
    function delAccountPopup(){
        document.getElementById("delAccount").hidden=false;
    }
    function showEditDetails(){
        document.getElementById("userShow").hidden=true;
        document.getElementById("userEdit").hidden=false;
    }
    function showMusicBars(bars){
        alert(bars);
        document.getElementById("musicBar"+bars).hidden=false;
    }
    </script>
    <style>
.grid-container {
 align-items: end;
  display: grid;
  grid-template-columns:repeat(auto-fit , 350px);
  padding-top: 40px;
}
.grid-item {
  font-size: 30px;
  text-align: center;
}
.comment {
    color:inherit;
    background-color: #20253d;
}
.comment:hover {
  background-color: #00253d;
  color:inherit;
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
