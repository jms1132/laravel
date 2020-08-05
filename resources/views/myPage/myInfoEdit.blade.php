@extends('layout')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.0/css/bulma.min.css"> @section('content')

<div id="wrapper">
    <div id="page" class="container">
        <form action="/myPage/{{$user->id}}" method="POST">
            @csrf @method('PUT')
            <div class="form-group row">
                <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                <div class="col-md-6">
                    <input id="email" type="email" class="form-control " name="email" value="{{$user->email}}" required autocomplete="email" readonly>
                    <!-- email을 아이디값으로 사용하기 때문에 email은 수정이 되지 않게끔 readonly로 설정해두었다. -->
                </div>
            </div>
            <div class="form-group row">
                <label for="nickname" class="col-md-4 col-form-label text-md-right">{{ __('Nickname') }}</label>
                <div class="col-md-6">
                    <input id="nickname" type="text" class="form-control" name="nickname" value="{{ $user->nickname }}" required autocomplete="nickname"> @error('nickname')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span> @enderror
                </div>
            </div>
            <div class="form-group row">
                <label for="pre_password" class="col-md-4 col-form-label text-md-right">{{ __('Original Password') }}</label>
                <!-- 원래의 비밀번호를 맞게끔 입력해야 정보수정이 가능하게끔 설정해두었다. -->
                <div class="col-md-6">
                    <input id="pre_password" type="password" class="form-control @error('pre_password') is-invalid @enderror" name="pre_password" value="{{ old('pre_password')}}" required autocomplete="password"> @error('pre_password')
                    <!-- 이부분은 단순히 인풋에 입력값이 아무것도 없을때 에러를 나타내기 위한 표현 -->
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span> @enderror
                </div>
            </div>
            @if ($message = Session::get('error'))
            <!-- 이전에 사용하던 비밀번호와 입력값이 일치하지 않을경우 컨트롤러에서 세션값을 error로 설정하게끔 만들어두었다. -->
            <div class="form-group row">
                <label class="col-md-4 col-form-label text-md-right"></label>
                <div class="col-md-6 alert alert-danger alert-block" style="width:350px;">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ $message }}</strong>
                </div>
            </div>
            @endif
            <div class="form-group row">
                <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('New Password') }}</label>
                <div class="col-md-6">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="password"> @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span> @enderror
                </div>
            </div>
            <div class="form-group row">
                <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>
                <div class="col-md-6">
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password">
                </div>
            </div>
            <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-4">
                    <button type="submit" class="top-right btn btn-primary">
                        {{ __('Register') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection