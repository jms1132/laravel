@extends('layout') @section('header')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.0/css/bulma.min.css"> @endsection @section('content')
<div id="wrapper">
    <div id="page" class="container">
        <h1 class="heading has-text-weight-bold is-size-4">New Article</h1>
        @if (Str::contains($_SERVER['REQUEST_URI'], '/notice'))
        <!-- 현재 url에 notice가 포함되어 있을 땐 notice 게시판에 글이 작성 될수 있게 조건 설정 -->
        <form method="POST" action="/notice" enctype="multipart/form-data">
            <!-- enctype 속성은 폼 데이터(form data)가 서버로 제출될 때 해당 데이터가 인코딩되는 방법을 명시한다 -->
            @endif @if (Str::contains($_SERVER['REQUEST_URI'], '/free'))
            <!-- 현재 url에 free가 포함되어 있을 땐 free 게시판에 글이 작성 될수 있게 조건 설정 -->
            <form method="POST" action="/free" enctype="multipart/form-data">
                @endif @csrf
                <div>
                    <label class="label" for="title">Title</label>
                    <div class="control">
                        <input class="input @error('title') is-danger @enderror" type="text" name="title" id="title" value="{{ old('title')}}">
                        <!-- error가 발생했을 때만 클래스명이 생성되도록 설정해놓는다.-->
                        <!-- value의 old title값은 타이틀만 입력하고 내용을 입력하지 않아서 submit 버튼을 눌렀을 때 미리 작성해둔 내용이 사라지지 않게끔 해주는 역할을 한다. -->
                        @error('title')
                        <!-- error가 발생되면 아래 p에 해당되는 내용이 나타난다. -->
                        <p class="help is-danger">{{$errors->first('title')}}</p>
                        @enderror
                    </div>
                </div>
                <div class="field">
                    <label for="contents" class="label">Contents</label>
                    <div class="form-group control">
                        <textarea class="textarea @error('contents') is-danger @enderror" name="contents" id="editor">{{ old('contents')}}</textarea>
                        <!-- 위의 error 조건과 동일하다 -->
                        @error('contents')
                        <p class="help is-danger">{{$errors->first('contents')}}</p>
                        @enderror
                        <script>
                            //ui에디터를 위한 스크립트코드
                            tinymce.init({
                                selector: 'textarea',
                                menubar: false,
                            });
                        </script>
                    </div>
                </div>
                <input type="file" name="file" id="file">
                <!-- 첨부파일을 넣을 수 있게 input의 타입을 file로 설정해둔다. -->
                @if (Str::contains($_SERVER['REQUEST_URI'], '/notice'))
                <!-- 같은 articles테이블을 사용하기 때문에 게시글을 입력할때 현재 어느 게시판의 위치에서 글을 작성하는지 판단하기 위해 url에 포함된 단어를 확인하고 
								그 후 작성하는 게시글의 article_type의 값을 알맞게 설정해준다. 이 조건을 이용해 각각의 게시판의 list에 출력되는 게시글이 정해진다. -->
                <input type="hidden" value="/notice" name="article_type" id="article_type">
                <input type="hidden" value="1" name="priority" id="priority"> @else
                <!-- notice게시판에 글을 쓸수있는 전제조건 자체가 로그인된 유저가 관리자일때만 공지게시판에 글쓰기 버튼이 나타나게끔 해두었기 때문에 
								notice게시판에 작성되는 글들은 모두 1이라는 priority를 가진다. -->
                <input type="hidden" value="/free" name="article_type" id="article_type">
                <!-- url에 free라는 단어가 포함되어 있으면(=else 현재 위치가 free게시판이라는 뜻) 게시글도 free라는 article_type을 가진다. -->
                @if ( Auth::user()->type ==='admin')
                <input type="hidden" value="1" name="priority" id="priority">
                <!-- 관리자는 자유게시판에 일반자유게시글을 작성할 수 없다. 
								항상 공지글만 작성할 수 있기 때문에 만약 위치가 free게시판이고 로그인유저가 관리자라면 그사람이 쓰는 글은 공지글이라는 뜻의 priority 1값을 가지게 된다. -->
                @else
                <!-- 로그인유저가 관리자가 아니라면 -->
                <input type="hidden" value="0" name="priority" id="priority"> @endif @endif
                <div class="field is-grouped" style="float: right;">
                    <div class="control ">
                        <button class=" button is-link " type="submit">Submit</button>
                    </div>
                </div>
            </form>
    </div>
</div>
@endsection