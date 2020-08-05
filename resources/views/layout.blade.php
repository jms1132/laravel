<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title></title>
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,600,700,900" rel="stylesheet" />
    <link href="/css/default.css" rel="stylesheet" />
    <link href="/css/fonts.css" rel="stylesheet" />
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <style>
        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }
        
        ul>li {
            list-style: none;
        }
        
        td>a {
            text-decoration: none;
            color: black;
        }
        
        .nav-item>a {
            color: white;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }
    </style>
    <script src="https://cdn.tiny.cloud/1/va03179m22yv1zhnjppdmu0ub17tl6ew0g0wmhpyuwlgmiwk/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
</head>


<body>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous">
    </script>

    <div id="header-wrapper">
        <div id="header" class="container" style="height: 160px;">
            <div class="flex-center position-ref full-height">
                <div class="top-right links">
                    <ul class="navbar-nav ml-auto">
                        @guest
                        <!-- 현재 접속자가 인증되지 않은 사용자라는 뜻-->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                        @endif @else
                        <!-- 인증이 된 사용자라면 -->
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->nickname }} 님
                                <!-- 로그인한 유저의 정보에서 nickname을 사용해 로그인되었음을 표현해준다. -->
                                <span class="caret"></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                            @if((Auth::user()->type) !== 'admin')
                            <!-- 만약 로그인된 유저의 타입이 관리자가 아니라면 마이페이지를 보여준다. -->
                            <a href="/myPage/{{Auth::user()->id}}/myInfoEdit">
                                <div class="mypage">마이페이지</div>
                            </a>
                            @else
                            <!-- 관리자가 로그인했다면 회원관리 기능을 보여준다. -->
                            <a href="/user">
                                <div class="mypage">회원관리</div>
                            </a>

                            @endif
                        </li> @endguest

                    </ul>
                </div>
                <div id="logo">
                    <h1><a href="/ ">Hello Laravel</a></h1>
                </div>
                <div id="menu">
                    @if (Str::contains($_SERVER['REQUEST_URI'], '/myPage'))
                    <!-- 만약 일반사용자가 로그인해서 마이페이지를 클릭하면 url에는 mypage라는 루트가 설정되게 되서 이것을 통해 사용자가 마이페이지를 눌렀는지 확인한다. -->
                    <ul>
                        <li>
                            <a href="/myPage/{{Auth::user()->id}}/myInfoEdit">내정보수정</a>
                        </li>
                        <li>
                            <a href="/myPage/myArticles">내게시물</a>
                        </li>
                    </ul>
                    @else
                    <!-- 마이페이지를 누르지 않은 상태라면 그대로 홈페이지, 공지게시판, 자유게시판 메뉴를 보여준다. -->
                    <ul>
                        <li class="{{ Request:: path()==='/' ? 'current_page_item' : ''}} "><a href="/ " accesskey="1 " title=" ">Homepage</a></li>
                        <li class="{{ Request:: path()==='notice' ? 'current_page_item' : ''}} "><a href="/notice" accesskey="2 " title=" ">Notice</a></li>
                        <li class="{{ Request:: path()==='free' ? 'current_page_item' : ''}} "><a href="/free" accesskey="3 " title=" ">Free board</a></li>
                    </ul>
                    @endif
                </div>
            </div>
        </div>
        @yield('header')
    </div>
    @yield('content')
</body>

</html>