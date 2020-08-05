<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Article;

use Illuminate\Support\Facades\Validator;
use SebastianBergmann\Environment\Console;

class UserController extends Controller
{
	public function index()
	{
		//관리자가 회원관리에 들어갔을 때 list가 보여지는데 그 list에 담겨질 $users 데이터를 만드는 함수
		$users = User::latest()->paginate(10);
		//전체 회원들을 다 보아야하기 때문에 별다른 조건 없이 최근에 가입한 순으로 10명씩 페이지네이션해서 $users에 담아준다.
		return view('admin.userIndex', ['users' => $users]);
		//$users에 정보가 담겼다면 이어서 admin폴더에 있는 userIndex view화면을 불러온다. 물론 list에 출력될 데이터가 담긴 $users도 전달한다.
	}
	public function destroy(Request $request, User $user)
	{
		//checkbox를 이용해 다중선택 삭제를 진행할때 form을 이용한 방법에서 사용하는 함수이다.
		$array = request('delete');
		//userIndex화면에서 foreach를 통해 반복되는 user들을 checkbox를 통해 체크하면 체크 된 값들이 delete라는 이름의 배열에 담겨 submit버튼을 눌렀을 때 전송된다.
		User::whereIn('id', $array)->delete();
		//whereIn메소드는 주어진 컬럼의 값 중에 request를 통해 전달된 배열안의 값과 일치하는게 있는지 확인한다.
		//그리고 그 일치된 값들을 delete()해주면 선택된 유저들이 삭제된다.
		return redirect(route('user.index'));
	}
	public function multiDelete(Request $request)
	{
		//checkbox 다중삭제 기능 구현에서 자바스크립트를 사용했을 때 연결되는 함수이다.
		$array = $request->input('checkArr');
		//index화면에서 체크된 값들을 걸러 checkArr라는 배열에 담아 ajax를 통해 전송해주었다. 따라서 여기서 request를 통해 전송된 그 checkArr를 받아 $array에 담아준다.
		User::whereIn('id', $array)->delete();
		//이후 삭제하는 쿼리문의 과정은 위와 동일하다.
	}
	public function userSearch(Request $req)
	{
		$keyword = $req->keyword;
		$search_type = $req->search_type;
		//검색조건을 nickname과 email 중에 선택할 수 있게 해두었다.
		$users = User::where([
			$search_type,
			'like',
			'%' . $keyword . '%']
		)->latest()->paginate(10);
		$users->appends(['keyword' => $keyword, 'search_type' => $search_type]);
		//검색조건으로 선택한값을 사용할 필드값으로 정한다음, 그 필드의 데이터들 중에 keyword가 포함되어 있는 데이터들을 골라서 users에 담아준다.
		return view('admin.userIndex', ['users' => $users]);
		//userIndex 화면을 불러온다음 검색을 통해 나온 $users를 전달한다.
	}
	public function userDelete(User $user)
	{
		//회원 하나하나 직접 삭제 할때 버튼을 누르면 불러지는 함수이다.
		User::where('id', $user->id)->delete();
		return redirect(route('user.index'));
	}
	public function myInfoEdit(User $user)
	{
		//layout에서 정보수정을 눌렀을 때 불러지는 함수이다.
		//mypage 폴더에 있는 myinfoedit 화면을 불러오는데 그때 전달된 user에 대한 모든 정보를 전달한다.
		return view('myPage.myInfoEdit', ['user' => $user]);
	}
	public function myInfoUpdate(User $user)
	{
		request()->validate([
			'nickname' => 'required',
			'pre_password' => ['required', 'string', 'min:6'],
			'password' => ['required', 'string', 'min:6', 'confirmed'],
		]);
		if (Hash::check(request('pre_password'), $user->password)) {
			//회원가입 당시 입력한 사용자의 비밀번호는 저장될때 암호화되었기 때문에, 지금 사용자의 비밀번호 확인 입력값이 그 암호화된 db의 비밀번호 데이터와 비교하는 구문이다.
			$user->nickname = request('nickname');
			if (request('password')) {
				$user->password = Hash::make(request('password'));
			}
			$user->save();
			return view('myPage.myInfoEdit', compact('user'));
		} else {
			//입력값과 기존 비밀번호가 틀리다면 session을 error로 설정해서 메시지를 띄운다.
			return back()
				->with('error', '이전에 사용하던 비밀번호가 일치하지 않습니다.');
		}
	}
	public function myArticles()
	{
		$articles = Article::where('user_id', auth()->user()->id)->latest()->paginate(10);
		//articles테이블에서 로그인한 아이디 값과 user_id값이 같은 데이터만 골라서 가져온다.
		return view('myPage.myArticles', ['articles' => $articles]);
	}

	public function myArticlesSearch(Request $req)
	{
		$keyword = $req->keyword;
		$search_type = $req->search_type;

		$articles = Article::where([
			['user_id', auth()->user()->id],
			[$search_type, 'like', '%' . $keyword . '%'],
		])->latest()->paginate(10);

		return view('myPage.myArticles', ['articles' => $articles]);
	}
	public function articles(User $user)
	{
		$articles = Article::where(
			'user_id',
			$user->id
		)->latest()->paginate(10);

		return view('admin.userArticles', ['articles' => $articles]);
	}
}
