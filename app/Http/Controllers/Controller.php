<?php

namespace App\Http\Controllers;

use Mail;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function __construct()
    {
        $this->database = app('firebase.database');
    }
    public function mapIndex(){
        $referenceMap=$this->database->getReference('/mapPins/')->getSnapshot()->getValue();
        return view('map',["pins" => $referenceMap]);
    }
    function checkRate(Request $request){
        $referenceMapPin=$this->database->getReference('/mapPins/'.$request->index.'/rate')->getSnapshot()->getValue();
        if($referenceMapPin!=0){
            $extractedValues=[];
            for($i=0;$i<count($referenceMapPin);$i++){
                $extractedValues[$i]=$referenceMapPin[$i]["points"];
            }
            return $this->getMedian($extractedValues);
        }else{
            return 0;
        }
    }
    function getMedian($arr) {
        if(!is_array($arr)){
            throw new Exception('$arr must be an array!');
        }
        if(empty($arr)){
            return false;
        }
        $num = count($arr);
        $middleVal = floor(($num - 1) / 2);
        if($num % 2) { 
            return $arr[$middleVal];
        } 
        else {
            $lowMid = $arr[$middleVal];
            $highMid = $arr[$middleVal + 1];
            return (($lowMid + $highMid) / 2);
        }
    }
    function rate(Request $request){
        $referenceMapPin=$this->database->getReference('/mapPins/'.$request->index.'/rate')->getSnapshot()->getValue();
        if($referenceMapPin==0){
            $referenceMapPin=$this->database->getReference('/mapPins/'.$request->index.'/rate/0')->set([
                "points"=>$request->rate,
                ]);
        }else{
            $referenceMapPin2=$this->database->getReference('/mapPins/'.$request->index.'/rate/'.count($referenceMapPin))->set([
                "points"=>$request->rate,
                ]);
        }
    }
    public function uploadPin(Request $request){
        $referenceMap=$this->database->getReference('/mapPins/')->getSnapshot()->getValue();
        if($referenceMap==""){
            $data = substr($request->hidden64file, strpos($request->hidden64file, ',') + 1);
            $file = base64_decode($data); //converts to base64
            $fileDetails = pathinfo($request->file);
            $length = 10;
            $name = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1,$length); //creates a random name for the file so its unique
            Storage::disk('publicImg')->put($name.".".$fileDetails['extension'], $file);
            $referenceUploadPin=$this->database->getReference('/mapPins/0')->set([
                "latitud"=>$request->latitud,
                "longitud"=>$request->longitud,
                "file"=>$name,
                "desc"=>$request->desc,
                "rate"=>0,
            ]);
        }
        if($referenceMap!=""){
            $data = substr($request->hidden64file, strpos($request->hidden64file, ',') + 1);
            $file = base64_decode($data); //converts to base64
            $fileDetails = pathinfo($request->file);
            $length = 10;
            $name = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1,$length); //creates a random name for the file so its unique
            Storage::disk('publicImg')->put($name.".".$fileDetails['extension'], $file);
            $referenceUploadPin=$this->database->getReference('/mapPins/'.count($referenceMap))->set([
                "latitud"=>$request->latitud,
                "longitud"=>$request->longitud,
                "file"=>$name.".".$fileDetails['extension'],
                "desc"=>$request->desc,
                "username"=>$this->getCurrentUserDisplay(),
            ]);
        }
    }
    public function getWelcomeIndex(){
        $referenceUsers =  array_values($this->database->getReference('/users/')->getSnapshot()->getValue());
        $usersWithGallery=[];
        $mostLikes=[];
        for($i=0;$i<count($referenceUsers);$i++){
            if(array_key_exists("gallery",$referenceUsers[$i])){
                array_push($usersWithGallery,$referenceUsers[$i]);
            }
        }
        for($i=0;$i<count($usersWithGallery);$i++){
            usort($usersWithGallery[$i]["gallery"], function($a, $b) {
                return strcmp($b['likes'] , $a['likes']);
            });
        }
        $photosMostLikes=[];
        for($i=0;$i<count($usersWithGallery);$i++){
            array_push($photosMostLikes,$usersWithGallery[$i]["gallery"][0]);
        }
        usort($photosMostLikes, function($a, $b) {
            return strcmp($b['likes'] , $a['likes']);
        });
        $recentLikes= array_values($this->database->getReference('/recentLikes/')->getSnapshot()->getValue());
        return view('welcome', ["currentUsername"=> $this->getCurrentUserDisplay(), "username"=>$this->getCurrentUserDisplay(),"mostLikes"=> $photosMostLikes,"recentLikes" => $recentLikes]);
    }
    public function checkConfirmEmail(){

    }
    public function searchIndex(Request $request){
        $searchText=$request->search;
        $referenceUsers =  array_values($this->database->getReference('/users/')->getSnapshot()->getValue());
        $searchResult=[];
        for($i=0;$i<count($referenceUsers);$i++){
            if(array_key_exists("gallery",$referenceUsers[$i])){
                for($j=0;$j<count($referenceUsers[$i]["gallery"]);$j++){
                    if(stripos($referenceUsers[$i]["gallery"][$j]["name"],$searchText) || str_contains($referenceUsers[$i]["username"],$searchText)) {
                        $username=$referenceUsers[$i]["username"];
                        $filename=$referenceUsers[$i]["gallery"][$j]["fileData"];
                        $searchResult[$j]["link"]='/'.$username.'/'.$filename;
                        $searchResult[$j]["fileName"]=$filename;
                        $searchResult[$j]["username"]=$username;
                        $searchResult[$j]["bio"]=$referenceUsers[$i]["bio"];
                        $searchResult[$j]["likes"]=$referenceUsers[$i]["gallery"][$j]["likes"];
                        $searchResult[$j]["name"]=$referenceUsers[$i]["gallery"][$j]["name"];
                    }
                }
            }
        }
        return view('search', ["currentUsername"=> $this->getCurrentUserDisplay(), "username"=>$this->getCurrentUserDisplay(), "searchResult" => $searchResult]);
    }
    public function contactoIndex(){
        return view("contacto");
    }
    public function chatIndex(){
        return view("chat");
    }
    public function sendMessage(Request $request){
        $referenceMessages =  $this->database->getReference('/chat/messages')->getSnapshot()->getValue();
        if($referenceMessages==""){
            $referenceNewMessage =  $this->database->getReference('/chat/messages/0')->set([
                "message" => $request->message,
                "time" => Carbon::now()->format('g:ia'),
                "username" => $this->getCurrentUserDisplay(),
            ]);
            return [$request->message,Carbon::now()->format('g:ia'),$this->getCurrentUserDisplay()];
        }
        else{
            $referenceMessages =  $this->database->getReference('/chat/messages')->getSnapshot()->getValue();
            $referenceNewMessage =  $this->database->getReference('/chat/messages/'.count($referenceMessages))->set([
                "message" => $request->message,
                "time" => Carbon::now()->format('g:ia'),
                "username" => $this->getCurrentUserDisplay(),
            ]);
            return [$request->message,Carbon::now()->format('g:ia'),$this->getCurrentUserDisplay()];
        }
    }
    public function checkMessage(){
        $referenceMessages=$this->database->getReference('/chat/messages')->getSnapshot()->getValue();
        return $referenceMessages;
    }
    public function contactEmail(Request $request){
        $to= explode("@", $request->email, 2);
        $to_name = $to[0];
        $to_email = $request->email;
        $data = array('name'=>$to[0], "body" => "¡Tu consulta ha llegado a nosotros!, por favor, espere a una respuesta.\n Su consulta:\n ".$request->consulta);
        Mail::send('emails.mailContacto', $data, function($message) use ($to_name, $to_email) {
            $message->to($to_email, $to_name)->subject('Su consulta de Viewfeel');
            $message->from('viewfeel0@gmail.com','Su consulta de Viewfeel');
        });
        $to_name = "viewfeel";
        $to_email = "viewfeel0@gmail.com";
        $data = array('name'=>$to_name, "body" => "Hay una nueva consulta de ".$request->email.". La consulta:\n ".$request->consulta);
        Mail::send('emails.mailContacto', $data, function($message) use ($to_name, $to_email) {
            $message->to($to_email, $to_name)->subject('Nueva consulta en Viewfeel');
            $message->from('viewfeel0@gmail.com','Nueva consulta en Viewfeel');
        });
        return view("welcome");
    }
    public function checkIfLike(Request $request){
        $referenceGivenLikes = $this->database->getReference('/users/'.$this->getCurrentUserDisplay().'/givenLikes')->getSnapshot()->getValue();
        if($referenceGivenLikes!=""){
            if (in_array($request->art, $referenceGivenLikes)) {
                return 1;
            }
            return 0;
        }else{
            return 0;
        }
    }
    public function confirmMail($user,$token){
        $referenceConfirm = $this->database->getReference('/users/'.$user);
        $snapshotConfirm = $referenceConfirm->getSnapshot()->getValue();
        if($snapshotConfirm["tokenEmail"]==$token){
            $referenceConfirm->update([
                "confirmed"=>true,
            ]);
        }
        return redirect()->route('login')->with(['uncomfirmed' => "¡Listo! Ya puedes entrar a tu cuenta."]);
    }
    public function sendEmail(){
        $to_name = "Belinda";
        $to_email = "bely_nl_91@hotmail.com";
        $data = array('name'=>"Flanders", "body" => "Temo advertirle de que sufre de piojos, atentamente: El señor X");
        Mail::send('emails.mail', $data, function($message) use ($to_name, $to_email) {
            $message->to($to_email, $to_name)->subject('Laravel ');
            $message->from('viewfeel0@gmail.com','Test Mail');
        });
    }
    public function sendComment(Request $request){
        $referenceCommenter = $this->database->getReference('/users/'.$request->commenter);
        $snapshotCommenter = $referenceCommenter->getSnapshot()->getValue();
        $commenterImg=$snapshotCommenter["img"];
        $commentNumber= $this->database->getReference('/users/'.$request->username.'/gallery/'.$request->artIndex.'/comments')->getSnapshot()->getValue();
        if($commentNumber==""){
            $referenceUser = $this->database->getReference('/users/'.$request->username.'/gallery/'.$request->artIndex.'/comments/0')
            ->update([
                "img" => $commenterImg,
                "commenterUser" => $request->commenter,
                "text" => $request->commentText,
            ]);
        }
        else{
            $commentNumber=count($this->database->getReference('/users/'.$request->username.'/gallery/'.$request->artIndex.'/comments')->getSnapshot()->getValue());
            $referenceUser = $this->database->getReference('/users/'.$request->username.'/gallery/'.$request->artIndex.'/comments/'.$commentNumber)
            ->update([
                "img" => $commenterImg,
                "commenterUser" => $request->commenter,
                "text" => $request->commentText,
            ]);
        }
    }
    public function testLine(){
        dd(Carbon::now()->format('g:ia'));
    }
    public function removeLike($username, $artIndex){
        $referenceUser = $this->database->getReference('/users/'.$username."/gallery/".$artIndex);
        $snapshotArt = $referenceUser->getSnapshot()->getValue();
        $referenceLikeUser = $this->database->getReference('/users/'.$this->getCurrentUserDisplay()."/givenLikes/");
        $snapshotLikes=$referenceLikeUser->getSnapshot()->getValue();
        $artName=$snapshotArt["fileData"];
        $arrayIndexLikes=array_search($artName, $snapshotLikes);
        unset($snapshotLikes[$arrayIndexLikes]);
        $referenceNewLikeUser = $this->database->getReference('/users/'.$this->getCurrentUserDisplay())->update([
            "givenLikes" => [$snapshotLikes],
        ]);
        $snapshotArtLikes=$snapshotArt["likes"]-1;
        $referenceNewLikeArt = $this->database->getReference('/users/'.$username."/gallery/".$artIndex)->update([
            "likes" => $snapshotArtLikes,
        ]);
    }
    public function updateLikes($username,$artIndex){
        $referenceUser = $this->database->getReference('/users/'.$username."/gallery/".$artIndex);
        $snapshotArt = $referenceUser->getSnapshot()->getValue();
        $referenceLikeUser = $this->database->getReference('/users/'.$this->getCurrentUserDisplay()."/givenLikes/");
        $snapshotLikes=$referenceLikeUser->getSnapshot()->getValue();
        if($snapshotLikes==null){
            $referenceLikeUser = $this->database->getReference('/users/'.$this->getCurrentUserDisplay())->update([
                "givenLikes" => [$snapshotArt["fileData"]],
            ]);
            $referenceUser = $this->database->getReference('/users/'.$username."/gallery/".$artIndex);
            $snapshotLikes = $referenceUser->getSnapshot()->getValue()["likes"];
            $referenceUser = $this->database->getReference('/users/'.$username."/gallery/".$artIndex)
            ->update([
                "likes" => $snapshotLikes+1,
            ]);
            $referenceMostLikes = $this->database->getReference('/recentLikes')->getSnapshot()->getValue();
            $hasArtOnLikes=false;
            if($referenceMostLikes!=""){
                for($i=0;$i<count($referenceMostLikes);$i++){
                    if($referenceMostLikes[$i]["fileName"]==$snapshotArt["fileData"]){
                        $hasArtOnLikes=true;
                    }
                }
            }
            if($hasArtOnLikes==false){
                if($referenceMostLikes==""){
                    $referenceMostLikes = $this->database->getReference('/recentLikes/0')->set([
                        "fileName" => $snapshotArt["fileData"],
                        "name" => $snapshotArt["name"],
                        "likes" => $snapshotArt["likes"],
                        "link" => $username."/".$snapshotArt["fileData"],
                    ]);
                }else{
                    if(count($referenceMostLikes)<5){
                        $referenceMostLikes = $this->database->getReference('/recentLikes/'.count($referenceMostLikes))->set([
                            "fileName" => $snapshotArt["fileData"],
                            "name" => $snapshotArt["name"],
                            "likes" => $snapshotArt["likes"],
                            "link" => $username."/".$snapshotArt["fileData"],
                        ]);
                    }elseif(count($referenceMostLikes)==5){
                        $referenceMostLikes = $this->database->getReference('/recentLikes/')->getSnapshot()->getValue();
                        $referenceMostLikes=array_pop($referenceMostLikes);
                        $arrayToInsert= array(
                            "fileName" => $snapshotArt["fileData"],
                            "name" => $snapshotArt["name"],
                            "likes" => $snapshotArt["likes"],
                            "link" => $username."/".$snapshotArt["fileData"],
                        );
                        array_unshift($referenceMostLikes,$arrayToInsert);
                        $referenceMostLikesInsert= $this->database->getReference('/recentLikes/')->set([
                            $referenceMostLikes,
                        ]);
                    }
                }
            }
        }else{
            if (in_array($snapshotArt["fileData"], $snapshotLikes)!=true){
                array_push($snapshotLikes, $snapshotArt["fileData"]);
                $referenceLikeUser = $this->database->getReference('/users/'.$this->getCurrentUserDisplay())->update([
                    "givenLikes" => $snapshotLikes,
                ]);
                $referenceUser = $this->database->getReference('/users/'.$username."/gallery/".$artIndex);
                $snapshotLikes = $referenceUser->getSnapshot()->getValue()["likes"];
                $referenceUser = $this->database->getReference('/users/'.$username."/gallery/".$artIndex)
                ->update([
                    "likes" => $snapshotLikes+1,
                ]);
                $referenceMostLikes = $this->database->getReference('/recentLikes')->getSnapshot()->getValue();
                $hasArtOnLikes=false;
                if($referenceMostLikes!=""){
                    for($i=0;$i<count($referenceMostLikes);$i++){
                        if($referenceMostLikes[$i]["fileName"]==$snapshotArt["fileData"]){
                            $hasArtOnLikes=true;
                        }
                    }
                }
                if($hasArtOnLikes==false){
                    if($referenceMostLikes==""){
                        $referenceMostLikes = $this->database->getReference('/recentLikes/0')->set([
                            "fileName" => $snapshotArt["fileData"],
                            "name" => $snapshotArt["name"],
                            "likes" => $snapshotArt["likes"],
                            "link" => $username."/".$snapshotArt["fileData"],
                        ]);
                    }elseif(count($referenceMostLikes)<5){
                        $referenceMostLikes = $this->database->getReference('/recentLikes/'.count($referenceMostLikes))->set([
                            "fileName" => $snapshotArt["fileData"],
                            "name" => $snapshotArt["name"],
                            "likes" => $snapshotArt["likes"],
                            "link" => $username."/".$snapshotArt["fileData"],
                        ]);
                    }elseif(count($referenceMostLikes)==5){
                        $referenceMostLikes = $this->database->getReference('/recentLikes/')->getSnapshot()->getValue();
                        array_pop($referenceMostLikes);
                        $arrayToInsert= array(
                            "fileName" => $snapshotArt["fileData"],
                            "name" => $snapshotArt["name"],
                            "likes" => $snapshotArt["likes"],
                            "link" => $username."/".$snapshotArt["fileData"],
                        );
                        array_unshift($referenceMostLikes,$arrayToInsert);
                        for($i=0;$i<count($referenceMostLikes);$i++){
                            $referenceMostLikesInsert= $this->database->getReference('/recentLikes/'.$i)->set([
                                "fileName" => $referenceMostLikes[$i]["fileName"],
                                "name" => $referenceMostLikes[$i]["name"],
                                "likes" => $referenceMostLikes[$i]["likes"],
                                "link" => $referenceMostLikes[$i]["link"],
                            ]);
                        }
                    }
                }
            }
        }
    }
    public function getArtIndex($username,$artname){
        $referenceUser = $this->database->getReference('/users/'.$username."/gallery/");
        $snapshotUser = $referenceUser->getSnapshot()->getValue();
        for ($i=0;$i<=count($snapshotUser);$i++){
            if ($snapshotUser[$i]["fileData"]==$artname){
                $referenceCreator=$this->database->getReference('/users/'.$username);
                $snapshotCreator = $referenceCreator->getSnapshot()->getValue();
                return view('userart', ["currentUsername"=> $this->getCurrentUserDisplay(), "username"=>$this->getCurrentUserDisplay(),"comments"=>$snapshotUser[$i]["comments"], "likes"=>$snapshotUser[$i]["likes"], "shares"=>$snapshotUser[$i]["shares"], "nameArt"=>$snapshotUser[$i]["name"], "filename"=>$snapshotUser[$i]["fileData"], 'creatorImg' => $snapshotCreator["img"], 'creatorLevel' => $snapshotCreator["level"], 'creatorUsername' => $snapshotCreator["username"], 'artIndex' => $i]);
            }
        }
        //return view('userart', ["currentUsername"=> $this->getCurrentUserDisplay(), "username"=>$this->getCurrentUserDisplay()]);
    }
    public function getUploadIndex(){
        return view('upload', ["currentUsername"=> $this->getCurrentUserDisplay(),"username"=>$this->getCurrentUserDisplay()]);
    }
    public function delProfile(){
        $referenceUser = $this->database->getReference('/users/'.$this->getCurrentUserDisplay())->remove();
        $auth = app('firebase.auth');
        $auth->deleteUser(Auth::id());
        Auth::logout();
        return redirect("/");
    }
    public function updateProfile(Request $request){
            $referenceUser = $this->database->getReference('/users/'.$request->name);
            $snapshotUser = $referenceUser->getSnapshot();
            if($request->hidden64ImageProfile!=null){
                $this->database->getReference('users/'.$this->getCurrentUserDisplay())
                ->update([
                    "img" => $request->hidden64ImageProfile,
                ]);
            }
            $this->database->getReference('users/'.$this->getCurrentUserDisplay())
                ->update([
                    "bio" => $request->bio,
                    "usernameDisplay" => $request->name,
                ]);
            return redirect('/user/'.$this->getCurrentUserDisplay());
        }
    public function uploadFile(Request $request){
        $gallerySize=$this->database->getReference('users/'.$this->getCurrentUserDisplay().'/gallery')->getSnapshot()->getValue();
        $data = substr($request->hidden64file, strpos($request->hidden64file, ',') + 1);
        $file = base64_decode($data); //converts to base64
        $fileDetails = pathinfo($request->file);
        $length = 10;
        $name = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1,$length); //creates a random name for the file so its unique
        Storage::disk('publicImg')->put($name.".".$fileDetails['extension'], $file);
        if($request->nameFile!=null){
            if($gallerySize==null){
                $this->database->getReference('users/'.$this->getCurrentUserDisplay().'/gallery/0')
                ->set([
                    'name' => $request->nameFile,
                    'likes' => 0,
                    'shares' => 0,
                    'fileData' => $name.".".$fileDetails['extension'],
                    'type' => $fileDetails['extension'],
                    'comments' => '',
                    ]);
                return  redirect('/upload');
            }else{
                $galerySize=count($gallerySize);
                $this->database->getReference('users/'.$this->getCurrentUserDisplay().'/gallery/'.$galerySize)
                ->set([
                    'name' => $request->nameFile,
                    'likes' => 0,
                    'shares' => 0,
                    'fileData' => $name.".".$fileDetails['extension'],
                    'type' => $fileDetails['extension'],
                    'comments' => '',
                    ]);
                return  redirect('/upload');
            }
        }
        elseif($request->nameFile2!=null){
            if($gallerySize==null){
                $this->database->getReference('users/'.$this->getCurrentUserDisplay().'/gallery/0')
                ->set([
                    'name' => $request->nameFile2,
                    'likes' => 0,
                    'shares' => 0,
                    'fileData' => $name.".".$fileDetails['extension'],
                    'type' => $fileDetails['extension'],
                    ]);
                return  redirect('/upload');
            }else{
                $galerySize=count($gallerySize);
                $this->database->getReference('users/'.$this->getCurrentUserDisplay().'/gallery/'.$galerySize)
                ->set([
                    'name' => $request->nameFile2,
                    'likes' => 0,
                    'shares' => 0,
                    'fileData' => $name.".".$fileDetails['extension'],
                    'type' => $fileDetails['extension'],
                    ]);
                return redirect('/upload');
            }
        }
        else{
            if($gallerySize==null){
                $this->database->getReference('users/'.$this->getCurrentUserDisplay().'/gallery/0')
                ->set([
                    'name' => "Unnamed",
                    'likes' => 0,
                    'shares' => 0,
                    'fileData' => $name.".".$fileDetails['extension'],
                    'type' => $fileDetails['extension'],
                    ]);
                return  redirect('/upload');
            }else{
                $galerySize=count($gallerySize);
                $this->database->getReference('users/'.$this->getCurrentUserDisplay().'/gallery/'.$galerySize)
                ->set([
                    'name' => "Unnamed",
                    'likes' => 0,
                    'shares' => 0,
                    'fileData' => $name.".".$fileDetails['extension'],
                    'type' => $fileDetails['extension'],
                    ]);
                return redirect('/upload');
            }
        }
    }
    public function getUserPage($username){
        $referenceUser = $this->database->getReference('/users/'.$username);
        $snapshotUser = $referenceUser->getSnapshot();
        $results=$snapshotUser->getValue();
        $valueGallery = $this->database->getReference('users/'.$username.'/gallery')->getSnapshot()->getValue();
        if($valueGallery==null){
            $valueGallery=[];
        }
        return view('userpage', ["currentUsername"=> $this->getCurrentUserDisplay(), "username"=>$results["username"],"image"=>$results["img"],"bio"=>$results["bio"],"level"=>$results["level"],"gallery"=>$valueGallery]);
    }
    public function getCurrentUserDisplay(){
        $auth = app('firebase.auth');
        if(Auth::id()!=null){
            return $auth->getUser(Auth::id())->displayName;
        }
        return null;
    }
}
