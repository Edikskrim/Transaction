@extends('layouts.app')
@section('title','Юзеры')
@section('content')
    <div class="container">
        <span class="list-group-item active">Список пользователей</span>
        @foreach($users as $user)
            <a href="{{route('user.transaction',$user->id)}}" class="list-group-item list-group-item-action">
                <span>ID: {{$user->id}}</span><br>
                <span>Name: {{$user->name}}</span><br>
                <span>Баланс: {{$user->money}}</span><br></a>
        @endforeach
    </div>
@endsection
