<?php

namespace App\Http\Controllers;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Database;
class FirebaseController extends Controller
{
    protected $database;

    public function __construct()
    {
        $this->database = app('firebase.database');
    }
    public function testFirebase(){
        $reference = $this->database->getReference('/');
        $snapshot = $reference->getSnapshot();
        $this->database->getReference('test2/test3')->set([
            'jose' => 'mi'
        ]);
        dd($snapshot);
    }
}