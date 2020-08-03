@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">

                    <div class="panel-heading">{{ trans('users.forgetPassword') }}</div>

                    <div class="panel-body">
                        @if($errors->any())
                            <ul class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <li >{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                            @if (session()->has('success'))
                                <div class="alert alert-success text-center animated fadeIn">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <strong>
                                        @if(gettype(session()->get('success')) == "array")
                                            @foreach(session()->get('success') as $error)
                                                {!! $error !!}<br>
                                            @endforeach
                                        @else
                                            {{ session()->get('success') }}
                                        @endif
                                    </strong>
                                </div>
                            @endif
                        <form action="{{ url('save-user-forget-password') }}" method="POST">
                            {{ csrf_field() }}
                            {{ method_field('post') }}
                            <input type="hidden" name="memberEmail" value="{{$member->email}}">
                            <input type="hidden" name="memberUUID" value="{{$member->uuid}}">
                            <div class="form-group">
                                <label for="memberNewPassword">{{ trans('users.newPassword') }}</label>
                                <input type="password" class="form-control" name="memberNewPassword" id="memberNewPassword" placeholder="{{ trans('users.enterNewPassword') }}">
                            </div>
                            <div class="form-group">
                                <label for="memberConfirmNewPassword">{{ trans('users.confirmNewPassword') }}</label>
                                <input type="password" class="form-control" name="memberConfirmNewPassword" id="memberConfirmNewPassword" placeholder="{{ trans('users.enterConfirmNewPassword') }}">
                            </div>
                            <div class="row">
                                <div class="col-xs-12 text-right">
                                    <button type="submit" class="btn btn-default">{{ trans('users.save') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')


@endsection