@extends('layouts.master')

@section('title', 'Inicio')

@section('content')
    <div style="background-color:#ff004c;margin-top:5em;padding:0.4em"> 
        <div style="background-color:#20253d;margin:0.5em;color:white;">
        <div style="background-color:#cf004c;height:0.5em;">
        </div>
        <div style="padding-left:0.5em;">
        @if ($username!='')
            <p id="hiddenName" hidden>{{ $username }}</p> <!-- user info for navbar -->
            <p id="hiddenNameCurrent" hidden>{{ $currentUsername}}</p> <!-- user info for navbar -->

            <h1>¡Bienvenido, {{ $username }}!</h1>
            <h5>Siéntete como en casa y comparte tu arte con otros usuarios</h5>
        @else
            <h1>¡Bienvenido a Viewfeel!</h1>
        <h5>Siéntete como en casa y comparte tu arte con otros usuarios</h5>
        <a href="{{ route('register') }}">
            <button type="button" class="btn" style="background-color:#ff004c;border-radius:0px;color:white;font-size:1.3em;padding-right:2em;padding-left:2em;margin-right:0.5em;">Registro</button>
        </a>
        <a href="{{ route('login') }}">
            <button type="button"  href="{{ route('login') }}" class="btn" style="background-color:#ff004c;border-radius:0px;color:white;font-size:1.3em;padding-right:2em;padding-left:2em;">login</button><br>
        </a>
        @endif
        <i class="fa fa-search" aria-hidden="true" style="color:#ff004c;margin-right:0.5em;margin-top:1em;"> </i> 
        <input type="text" class="" placeholder="¡Busca arte!" aria-label="" aria-describedby="basic-addon1" style="border-color: #ff004c;color:white;background-color:#20253d;border-radius: 3px;width:15em;">
        <h5>Me gusta recientes:</h5>
        <p>Placeholder me gusta recientes</p>
        <h5>Compartidos:</h5>
        <p>Placeholder compartidos</p>
        <h5>Mas me gusta:</h5>
        <p>Placeholder mas me gusta</p>
        </div>
        </div>
        
    </div>
@stop