@extends('layouts.focus')

@section('css_for_page')
    <style type="text/css">
        .signin-box {
            max-width: 300px;
            padding: 19px 29px 29px;
            margin: 0 auto 20px;
            background-color: #fff;
        }

        @media screen and (min-width: 500px) {
            body {
                padding-top: 40px;
                padding-bottom: 40px;
            }

            html,body {
                background-color: #7D7D7D;
                background: url("{{ asset('assets/images/imperial-bg.png') }}") no-repeat center center fixed;
                -webkit-background-size: cover; /* For WebKit*/
                -moz-background-size: cover;    /* Mozilla*/
                -o-background-size: cover;      /* Opera*/
                background-size: cover;         /* Generic*/
            }


            .signin-box {
                border: 1px solid #e5e5e5;
                -webkit-border-radius: 5px;
                -moz-border-radius: 5px;
                border-radius: 5px;
                -webkit-box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
                -moz-box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
                box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
            }
        }


    </style>
@stop

@section('content')
    <div class="signin-box text-center">
        <div class="page-header">
            <h1><span class="glyphicon glyphicon-lock"></span></h1>

            <h2>Sign In</h2>
        </div>
        {{ Form::open(array('action' => 'SessionsController@postCreate')) }}

        @include('shared.flashes')

        <div class="form-group {{ $errors->first('username', 'has-error') }}">
            {{ Form::label('username', 'Imperial College Username', array('class' => 'control-label')) }}
            {{ Form::text('username', null, array('class' => 'form-control input-lg', 'placeholder' => 'e.g. abc123')) }}
            {{ $errors->first('username', '<span class="help-block">:message</span>') }}
        </div>

        <div class="form-group {{ $errors->first('password', 'has-error') }}">
            {{ Form::label('password', 'Password', array('class' => 'control-label')) }}
            {{ Form::password('password', array('class' => 'form-control input-lg')) }}
            {{ $errors->first('password', '<span class="help-block">:message</span>') }}
        </div>

        <div class="checkbox text-left">
            <label>
                {{ Form::checkbox('remember_me', 'true', true) }}
                Remember Me?
            </label>
        </div>

        {{ Form::submit('Sign In', array('class' => 'btn btn-primary btn-large btn-block', 'data-loading-text' => 'Signing in&hellip;')) }}

        {{ Form::close() }}
    </div>
@stop
