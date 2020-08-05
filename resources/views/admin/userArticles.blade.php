@extends('layout') @section('content')
<div id="wrapper">
    <div id="page" class="container">
        <div class="content">
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