@extends('layout') @section('header')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.0/css/bulma.min.css"> @endsection @section('content')
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">

<div id="wrapper">
    <div id="page" class="container">
        <div class="content">


            <table class="table table-striped ">
                <thead>
                    <tr>
                        <th>
                        </th>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Nickname</th>
                        <th>가입일</th>
                        <th></th>
                    </tr>
                </thead>

                <!-- checkbox 기능 자체로 form으로 전송해서 쓰는 방식 -->
                <!-- <form name="delete" method="POST" action="/user/destroy"> @method('DELETE') @csrf @foreach($users as $user)
                    <tbody>
                        <tr>
                            <td>
                                <input type="checkbox" name="delete[]" value="{{$user->id}}">
                            </td>
                            <td>
                                {{ $user->id }}
                            </td>
                            <td>
                                {{ $user->email }}
                            </a>
                            </td>
                            <td>
                                {{ $user->nickname }}
                            </td>
                            <td>
                                {{ $user->created_at }}
                                </a>
                            </td>
                            <td>
                                <a href="/user/{{ $user->id }}/delete"><input type="button" value="삭제"></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <button type="submit">선택 삭제</button>
                </form> -->

                <!-- 자바스크립트 쓰는 방식 -->
                @foreach($users as $user)
                <tbody>
                    <tr>
                        <td>
                            <input type="checkbox" name="delete" value="{{$user->id}}">
                        </td>
                        <td>
                            {{ $user->id }}
                        </td>
                        <td>
                            <a href="/user/{{$user->id}}">
                                {{ $user->email }}
                            </a>

                        </td>
                        <td>
                            {{ $user->nickname }}
                        </td>
                        <td>
                            {{ $user->created_at }}

                        </td>
                        <td>
                            <a href="/user/{{ $user->id }}/delete"><input type="button" value="삭제"></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <button onclick="sendCheckedUser()">선택 삭제</button>

            </table>
            <div style="margin-top:20px; text-align: center;  display: table;margin-left: auto; margin-right: auto;">
                {{ $users->links() }}
                <!-- users에 연결된 페이지네이션 -->
            </div>
        </div>
    </div>
</div>
@endsection
<script>
    function sendCheckedUser() {
        var checkArr = [];
        $("input[name='delete']:checked").each(function() {
            checkArr.push($(this).val());
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "/user/multi_delete",
            type: 'POST',
            data: {
                checkArr: checkArr
            },
            success: function(data) {

                location.href = "/user"
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert("에러 발생~~ \n" + textStatus + " : " + errorThrown);
                self.close();
            }
        });

    }

    // function makeArray(target) {
    //     var userVal = target.value;
    //     var confirmCheck = target.checked;

    //     if (confirmCheck == true) {
    //         checkArr.push(userVal); // check value filter 배열에 입력 // check false 
    //     } else {
    //         checkArr.splice(checkArr.indexOf(userVal), 1); // check value filter 배열내용 삭제 
    //     }
    //     console.log("필터입력값 출력 : " + checkArr);


    // }
</script>