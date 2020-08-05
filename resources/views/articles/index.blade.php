@extends('layout') @section('content')
<div id="wrapper">
    <div id="page" class="container">
        <div class="content">
            <div class="row" style="margin-bottom: 10px;">
                <div class="col-10">
                    @if (Str::contains($_SERVER['REQUEST_URI'], '/notice'))
                    <!-- 현재 있는 게시판이 notice라면 -->
                    <form method="GET" action="/notice/search">
                        @else
                        <!-- 현재 있는 게시판이 free라면 -->
                        <form method="GET" action="/free/search">
                            @endif @csrf
                            <select name="search_type" id="search_type">
                                <option value="title">Title</option>
                                <option value="contents">Contents</option>
                            </select>
                            <input type="text" name="keyword">
                            <button type="submit">검색</button>
                        </form>
                </div>
                <div class="col-2" style="text-align: right;">
                    @if ( Auth::user()->type === 'admin')
                    <!-- 로그인된 유저가 관리자이고(=일반사용자는 안된다는 조건) -->
                    @if (Str::contains($_SERVER['REQUEST_URI'], '/notice'))
                    <!-- 현재 위치한 게시판이 notice라면 -->
                    <button style="margin-right: 10px;" onclick="location.href='/notice/create'">글쓰기</button>
                    <!-- 글쓰기 버튼이 나타나게 된다.  -->
                    @endif @endif @if (Str::contains($_SERVER['REQUEST_URI'], '/free'))
                    <!-- 현재 위치한 게시판이 자유게시판이라면 (관리자든 사용자든 상관없음) -->
                    <button style="margin-right: 10px;" onclick="location.href='/free/create'">글쓰기</button> @endif
                </div>
            </div>
            <table class="table table-striped ">
                <thead>
                    <tr>
                        <th>글번호</th>
                        <th>제목</th>
                        <th>작성자</th>
                        <th>첨부</th>
                        <th>등록일</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($articles as $article)
                    <tr>
                        <td>
                            {{ $article->id }}
                        </td>
                        <td>
                            @if (Str::contains($_SERVER['REQUEST_URI'], '/notice'))
                            <a href="/notice/{{$article->id}}">
                                @endif
                                @if (Str::contains($_SERVER['REQUEST_URI'], '/free'))
                                <a href="/free/{{$article->id}}">
                                    @endif
                                    @if ($article->priority == '1')
                                    <span class="badge badge-primary" style="margin-right: 10px;">공지</span>
                                    @endif
                                    <!-- 게시글의 priority가 1이라는 것은 그 글이 공지글이라는 뜻이기 때문에 글제목 앞에 뱃지를 붙여두고 차별성을 둔다. -->
                                    {{ $article->title }}
                                </a>
                        </td>
                        <td>
                            {{ $article->user->nickname }}
                        </td>
                        <td>
                            @if ($article->file_name) O @else X @endif
                        </td>
                        <td>
                            {{ $article->created_at }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="margin-top:20px; text-align: center;  display: table;margin-left: auto; margin-right: auto;">
                {{ $articles->links() }}
                <!-- articles에 연결된 페이지네이션 -->
            </div>
        </div>
    </div>
</div>
@endsection