@extends('layouts.app')

@section('title','编辑资料')

@section('content')
    <div class="container">
        <div class="offset-md-2 col-md-8">
            <div class="card">

                <div class="card-header">
                    <h4 style="font-size: 22px;">
                        <i class="fa fa-address-card mr-4"></i>编辑个人资料
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('users.update',$user->id) }}" method="POST" accept-charset="UTF-8">
                        {{ method_field('PUT') }}
                        {{ csrf_field() }}

                        @include('shared._error')

                        <div class="form-group">
                            <label for="name-field">用户名</label>
                            <input type="text" class="form-control" name="name" id="name-field" value="{{ old('name',$user->name) }}">
                        </div>

                        <div class="form-group">
                            <label for="email-field">邮箱</label>
                            <input type="text" class="form-control" name="email" id="email-field" value="{{ old('email',$user->email) }}">
                        </div>

                        <div class="form-group">
                            <label for="introduction-field">个人简介</label>
                            <textarea name="introduction" id="introduction-field" class="form-control" rows="3">{{ old('introduction',$user->introduction) }}</textarea>
                        </div>

                        <div class="well weill-sm">
                            <button type="submit" class="btn btn-primary float-right">保存</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection