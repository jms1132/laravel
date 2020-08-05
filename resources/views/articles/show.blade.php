@extends('layout') @section('header')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.0/css/bulma.min.css"> @endsection @section('content')

<div id="wrapper">
    <div id="page" class="container" style="display: table;margin-left: auto; margin-right: auto;">
        <div id="content">
            <div class="title">
                <h4>{{ $article->title }}</h4>
            </div>
            <p><img src="/images/banner.jpg" alt="" class="image image-full" /> </p>
            {!! $article->contents !!}
            <!-- ui편집기를 이용해서 작성한 글이라 데이터를 escape 처리를 하지 않기 위해 !!를 붙여주었다. 
						htmlspecialchars 함수를 통과x-->
            @if($article->file_name)
            <!-- 첨부된 파일이 있을때만 보여준다. -->
            <p>
                @if (Str::contains($_SERVER['REQUEST_URI'], '/free')) 첨부파일 : <a href="/free/{{$article->id}}/download">{{$article->file_name}}</a>
                <!-- 사용자에게 보여주는 이름은 첨부된 파일의 실제 파일명을 사용한다. 클릭하면 파일을 다운로드 할 수 있는 루트로 연결된다. -->
            </p>
            @elseif (Str::contains($_SERVER['REQUEST_URI'], '/notice')) 첨부파일 : <a href="/notice/{{$article->id}}/download">{{$article->file_name}}</a> @endif
            </p>
            @endif
        </div>
        <div>
            @if (Str::contains($_SERVER['REQUEST_URI'], '/free'))
            <!-- 자유게시판일때는  -->
            @if ( Auth::user()->id === $article->user_id )
            <!--게시글의 작성자와 로그인된 사용자가 같을때 -->
            <div style="float: right; ">
                <a href="/free/{{ $article->id }}/edit"><button>수정</button></a>
            </div>
            <div style="float: right; margin-right: 10px;">
                <form name="delete" method="POST" action="/free/{{ $article->id }}">
                    @method('DELETE') @csrf
                    <button>삭제</button>
                </form>
            </div>
            @elseif ( Auth::user()->type === 'admin' )
            <!--또는 로그인아이디와 작성자아이디가 다르더라도 로그인된 유저가 관리자 일때는 수정버튼 없이 삭제버튼만 노출한다. -->
            <div style="float: right;">
                <form name="delete" method="POST" action="/free/{{ $article->id }}">
                    @method('DELETE') @csrf
                    <button>삭제</button>
                </form>
            </div>
            @endif @elseif (Str::contains($_SERVER['REQUEST_URI'], '/notice'))
            <!-- 공지게시판일때는 -->
            @if ( Auth::user()->type === 'admin')
            <!-- 관리자에게만, 일반사용자는 그냥 글보기만 가능하고 삭제수정은 안된다.-->
            <div style="float: right; ">
                <a href="/notice/{{ $article->id }}/edit"><button>수정</button></a>
            </div>
            <div style="float: right;margin-right: 10px;">
                <form name="delete" method="POST" action="/notice/{{ $article->id }}">
                    @method('DELETE') @csrf
                    <button>삭제</button>
                </form>
            </div>
            @endif @endif
        </div>
    </div>
</div>
@endsection