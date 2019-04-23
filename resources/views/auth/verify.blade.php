@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Verify Your Email Address') }}</div>

                    <div class="card-body">
                        <div class="alert alert-{{$type}}" role="alert">
                            {{ $message }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection