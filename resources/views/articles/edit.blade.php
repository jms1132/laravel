@extends('layout') @section('header')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.0/css/bulma.min.css"> @endsection @section('content')
<div id="wrapper">
    <div id="page" class="container">
        <h1 class="heading has-text-weight-bold is-size-4">Update Article</h1>
        @if (Str::contains($_SERVER['REQUEST_URI'], '/free'))
        <!-- 게시판의 위치가 free일 경우에는 -->
        <form method="POST" action="/free/{{$article->id}}" enctype="multipart/form-data">
            <!-- route값에 해당되는 article의 id값을 get방식으로 넘겨받아 해당글을 edit할 수 있게 설정한다. -->
            @endif @if (Str::contains($_SERVER['REQUEST_URI'], '/notice'))
            <!-- 게시판의 위치가 notice일 때 -->
            <form method="POST" action="/notice/{{$article->id}}" enctype="multipart/form-data">@endif @csrf @method('PUT')
                <div>
                    <label class="label" for="title">Title</label>
                    <div class="control">
                        <input class="input @error('title') is-danger @enderror" type="text" name="title" id="title" value="{{ $article->title}}">
                        <!-- title input에 값이 입력되지 않는 error가 발생할때만 아래의 p요소가 생성되게끔 input의 class명에 설정해둔다. -->
                        <!-- 위에서 article의 id를 get으로 넘겨받았기 때문에 이를 이용해서 해당 게시글의 title을 가져와 value값에 미리 넣어둔다. -->
                        @error('title')
                        <p class="help is-danger">
                            {{$errors->first('title')}}</p>
                        @enderror
                    </div>
                </div>
                <div class="field">
                    <label for="contents" class="label">Contents</label>
                    <div class="control">
                        <textarea class="textarea @error('contents') is-danger @enderror" name="contents" id="editor">{{$article->contents}}</textarea>
                        <!-- 수정하려는 게시글의 내용을 가져와 미리 input에 넣어둔다. -->
                        @error('contents')
                        <p class="help is-danger">{{$errors->first('contents')}}</p>
                        @enderror
                        <script>
                            tinymce.init({
                                selector: 'textarea',
                                menubar: false,
                            });
                        </script>
                    </div>
                </div>
                @if($article->file_name)
                <p> 기존파일 : {{$article->file_name}}</p>
                @endif
                <!-- 기존에 파일이 첨부되어 있는 상태라면 나타나고 아니면 나타나지 않는다. -->
                <input type="file" name="file" id="file">

                <div class="field is-grouped">
                    <div class="control">
                        <button class="button is-link" type="submit">Submit</button>
                    </div>
                </div>
            </form>
    </div>
</div>
@endsection