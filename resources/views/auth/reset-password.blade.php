@extends('layouts.app')

@section('content')
    <style>
        .uper {
            margin-top: 40px;
        }
    </style>
    <div class="card uper">
        <div class="card-header">
            Add Share
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div><br />
            @endif
            <form method="post" action="{{ route('password.reset') }}">
                <div class="form-group">
                    @csrf
                    <input type="hidden" class="form-control" name="email" value="{{$email}}"/>
                    <input type="hidden" class="form-control" name="email" value="{{$token}}"/>
                </div>
                <div class="form-group">
                    <label for="price">Nowe hasło</label>
                    <input type="password" class="form-control" name="password"/>
                </div>
                <button type="submit" class="btn btn-primary">Zmień hasło</button>
            </form>
        </div>
    </div>
@endsection