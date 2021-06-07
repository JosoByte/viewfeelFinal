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


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function __construct()
    {
        $this->database = app('firebase.database');
    }
    public function getWelcomeIndex(){
        return view('welcome', ["currentUsername"=> $this->getCurrentUserDisplay(), "username"=>$this->getCurrentUserDisplay()]);
    }
    public function checkConfirmEmail(){

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
