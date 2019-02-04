@extends('layouts.app')
@section('content')
    <div class="container">
        @if (session('status'))
            <div class="alert {{session('status-class')}}" role="alert">
                {{session('status')}}
            </div>
        @endif
        <div class="list-group">
            <span class="list-group-item active">Name: Alexandra McGlynn</span>
            <span class="list-group-item ">ID: {{$user->id}}</span>
            <span class="list-group-item ">Баланс: {{$user->money}}</span>
        </div>
        <form method="POST">
            {{ csrf_field() }}
            <fieldset>
                <div class="form-group">
                    <label for="disabledSelect">Пользователи</label>
                    <select id="disabledSelect" class="form-control" name="user_transaction_id" required>
                        <option value="">Выберите пользователя</option>
                        @foreach($allUsers as $user)
                            <option value="{{$user->id}}">{{$user->name}} Баланс: {{$user->money}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="disabledTextInput">Сумма перевода</label>
                    <input type="number" min="1" step="any" id="disabledTextInput" class="form-control" placeholder="Сумма:" name="money"
                           required>
                </div>
                <button type="submit" class="btn btn-primary">Перевести</button>
            </fieldset>
        </form>
    </div>
@stop
