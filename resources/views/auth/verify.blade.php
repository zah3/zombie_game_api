@extends('layouts.app')
@section('style')
<style>
    @import url('https://fonts.googleapis.com/css?family=Oswald:200,300,400,500,600,700&amp;subset=latin-ext');
    html,
    body {
    padding: 0;
    margin: 0;
    font-family: 'Oswald', sans-serif;
    font-size: 13px;
    line-height: 1.4;
    background: -moz-linear-gradient(-90deg, #f0c27b 0%, #4b1248 100%);
    background: -webkit-linear-gradient(-90deg, #f0c27b 0%, #4b1248 100%);
    background: linear-gradient(90deg, #f0c27b 0%, #4b1248 100%);
    min-height: 100%;
    height: 100%;
    display: table;
    font-weight: 400;
    text-transform: uppercase;
    width: 100%;
    }

    #content {
    position: relative;
    min-height: 100%;
    height: 100%;
    float: left;
    width: 100%;
    padding: 15px 0;
    }

    #content .desc {
    display: table;
    height: 100%;
    width: 100%;
    }

    #content .desc .inner {
    display: table-cell;
    vertical-align: middle;
    }

    .app {
    border: 1px solid rgba(170, 208, 233, .2);
    box-shadow: 0 16px 28px 0 rgba(0, 0, 0, .22), 0 25px 55px 0 rgba(0, 0, 0, .21);
    float: left;
    width: 100%;
    background: #272727;
    color: #959AA3;
    position: relative;
    }

    .app .heading,
    .app .footer {
    text-align: center;
    width: 100%;
    }

    .app .footer span {
    color: rgb(239, 194, 89)
    }

    .app .heading,
    .app .main,
    .app .footer {
    padding: 15px;
    }

    .app .main {
    text-align: center;
    font-size: 25px;
    font-weight: 300;
    }

    .app .main .temp {
    padding-bottom: 30px;
    margin-bottom: 30px;
    }

    .app .main .fa {
    font-size: 86px;
    margin-bottom: 15px;
    display: block;
    }

    .app .main .temp .fa {
    font-size: 86px;
    }

    .app .main .sunrise .fa {
    font-size: 46px;
    }

    .app .main .fa,
    .app .footer span {
    color: rgb(239, 194, 89)
    }
    </style>
@endsection

@section('content')
    <div id="content">
        <div class="desc">
            <div class="inner">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3">
                            <div class="app">
                                <div class="heading">
                                    {{ __('Verify Your Email Address') }}
                                </div>
                                <div class="main">
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-md-12 temp" style="color: {{$color}};">
                                                <i class="fa fa-thermometer-full" aria-hidden="true"></i>
                                                {{ $message }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
