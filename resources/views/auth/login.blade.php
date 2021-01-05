@extends('layouts.start')

@section('title', 'Entrar')

@section('content')


    <div class="col-md-6">
        <h2 class="font-bold">Bem-vindo ao Portal Sweet</h2>

        <p>
            Perfectly designed and precisely prepared admin theme with over 50 pages with extra new web app views.
        </p>

        <p>
            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.
        </p>

        <p>
            When an unknown printer took a galley of type and scrambled it to make a type specimen book.
        </p>

        <p>
            <small>It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</small>
        </p>

    </div>
    <div class="col-md-6">
        <div class="ibox-content">
            <form class="form-horizontal" role="form" method="POST" action="{{ route('login.api') }}">
                {{ csrf_field() }}
                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <input id="email" type="email" class="form-control" placeholder="E-mail" name="email" value="{{ old('email') }}" required autofocus>

                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    <input id="password" type="password" class="form-control" placeholder="Password" name="password" required>

                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-md-offset-4">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                            </label>
                        </div>
                    </div>
                </div>
                <button type="button" id="login" class="btn btn-primary block full-width m-b">Login</button>

                {{--<a href="#">
                    <small>Forgot password?</small>
                </a>--}}

            </form>
        </div>
    </div>

@endsection

@section('script')

<script type="text/javascript" src="<?php echo asset('assets/js/app/login.js')?>?{{ date('dmYhis') }}"></script>


@endsection