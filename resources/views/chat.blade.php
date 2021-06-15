<body id="body" style="background-color:#20253d;color:white;height:100%;">
    <div id="messages">
        <p></p>
    </div>
</body>
<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"></script>
<script>
    var currentUser="";
    axios.get("/checkuser").then(function(response) {
        currentUser=response.data;
    });
    function scrollDown(){
        var messageBody = document.querySelector('#body');
        messageBody.scrollTop = messageBody.scrollHeight - messageBody.clientHeight;
    }
    function checkMessage(){
        axios.get("/checkMessage").then(function(response) {
            var messageBody = document.querySelector('#body');
            messageBody.scrollTop = messageBody.scrollHeight - messageBody.clientHeight;
            var messageBox=document.getElementById("messages")
            messageBox.innerHTML="";
                for(var i=0;i<response.data.length;i++){
                    if(currentUser!=response.data[i]["username"]){
                        var div=document.createElement("div");
                        div.style= "display:flex;justify-content:left;flex-flow: wrap;border-style: dashed dashed dashed solid;border-color: #ff004c;border-radius: 0px 10px 10px 10px;padding-left:0.5em;margin-bottom:1em;";
                        var p=document.createElement("p");
                        p.innerHTML=response.data[i]["username"];
                        p.style="width: 100%;text-align:left;margin:1;color:#c261ff;";
                        div.append(p);
                        var p=document.createElement("p");
                        p.innerHTML=response.data[i]["message"];
                        p.style="width: 100%;text-align:left;margin:1;color:white;";
                        div.append(p);
                        var p=document.createElement("p");
                        p.innerHTML=response.data[i]["time"];
                        p.style="width: 100%;text-align:left;margin:1;color:white;";
                        div.append(p);
                        messages.append(div);
                        var br=document.createElement("br");
                        div.append(br);
                    }else{
                        var div=document.createElement("div");
                        div.style= "display:flex;justify-content:right;flex-flow: wrap;border-style: dashed solid dashed dashed;border-color: #ff004c;border-radius: 10px 0px 10px 10px;padding-right:0.5em;margin-bottom:1em;";
                        var p=document.createElement("p");
                        p.innerHTML=response.data[i]["username"];
                        p.style="width: 100%;text-align:right;margin:1;color:#c261ff;";
                        div.append(p);
                        var p=document.createElement("p");
                        p.innerHTML=response.data[i]["message"];
                        p.style="width: 100%;text-align:right;margin:1;color:white;";
                        div.append(p);
                        var p=document.createElement("p");
                        p.innerHTML=response.data[i]["time"];
                        p.style="width: 100%;text-align:right;margin:1;color:white;";
                        div.append(p);
                        messages.append(div);
                    }
            }
        });
    }
</script>