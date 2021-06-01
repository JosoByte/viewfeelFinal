<?php

namespace App\Http\Controllers;

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
    public function getUploadIndex(){
        return view('upload', ["currentUsername"=> $this->getCurrentUserDisplay(),"username"=>$this->getCurrentUserDisplay()]);
    }
    public function uploadFile(Request $request){
        $gallerySize=$this->database->getReference('users/'.$this->getCurrentUserDisplay().'/gallery')->getSnapshot()->getValue();
        $data = substr($request->hidden64file, strpos($request->hidden64file, ',') + 1);
        $file = base64_decode($data); //converts to base64
        $fileDetails = pathinfo($request->file);
        $length = 10;
        $name = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1,$length); //creates a random name for the file so its unique
        Storage::disk('publicImg')->put($name.".".$fileDetails['extension'], $file);
        if($gallerySize==null){
            $this->database->getReference('users/'.$this->getCurrentUserDisplay().'/gallery/0')
            ->set([
                'name' => $request->nameFile,
                'likes' => '',
                'shares' => '',
                'fileData' => $name.".".$fileDetails['extension'],
                'type' => $fileDetails['extension'],
                ]);
        }else{
            $galerySize=count($gallerySize);
            $this->database->getReference('users/'.$this->getCurrentUserDisplay().'/gallery/'.$galerySize)
            ->set([
                'name' => $request->nameFile,
                'likes' => '',
                'shares' => '',
                'fileData' => $name.".".$fileDetails['extension'],
                'type' => $fileDetails['extension'],
                ]);
        }
        return redirect('/user/'.$this->getCurrentUserDisplay());
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
