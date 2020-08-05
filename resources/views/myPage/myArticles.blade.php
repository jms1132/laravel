@extends('layout') @section('content')

<div id="wrapper">
    <div id="page" class="container">
        <div class="content">
            <div style="margin-bottom: 10px;">
                <form method="GET" action="/myPage/myArticles/search">
                    @csrf
                    <select name="search_type" id="search_type">
                    <option value="title">Title</option>
                    <option value="contents">Contents</option>
                </select>
                    <input type="text" name="keyword">
                    <button type="submit">검색</button>
                </form>
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
                            <!-- 일반사용자가 쓴글은 무조건 free게시판에 있다고 생각하면 되기 때문에 제목을 클릭하면 free게시판의 글보기 루트로 연결된다. -->
                            <a href="/free/{{$article->id}}">
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