<?php

namespace App\Http\Controllers;


use App\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Response;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Support\Facades\Storage;

class ArticlesController extends Controller
{

	public function index() //list collection of articles 		
	{
		//layout에서 게시판을 선택했을때 연결되는 함수이다.
		//게시판의 종류에 따라서 보여지는 결과가 달라야한다.
		$request_uri = $_SERVER['REQUEST_URI'];
		//board.test 뒤에 붙는 url
		if (Str::contains($request_uri, 'notice')) {
			$articles = Article::where('article_type', '/notice')->latest()->paginate(10);
			//만약 현재 위치가 공지게시판에 진입했다면 $articles에는 Article테이블에서 article_type이 notice인 게시글들만 조회해서 가장 최신순으로 담기게 된다. 그 게시글들은 10개를 기준으로 페이지네이션 된다.
		} else {
			$articles = Article::where('article_type', '/free')->orderBy('priority', 'desc')->latest()->paginate(10);
			//free게시판에서는 priority에 따라 list에 보여지는 게시글의 순서가 달라진다. 따라서 먼저 Article 테이블에서 article_type이 free인 게시글들을 가져온다음, priority를 내림차순으로 가장 최근 작성된 순으로 $articles에 담아준다.
		}
		//이 모든 과정이 끝나면 articles폴더에 있는 index.blade.php view를 불러오는데 그때 위에서 쿼리를 실행시킨 결과가 담겨져 있는 $articles를 사용할 수 있게 넘겨준다.
		return view('articles.index', ['articles' => $articles]);
	}

	public function search(Request $req)
	{
		//게시판의 리스트에서 게시글을 검색할 수 있게 해주는 함수
		$keyword = $req->keyword;
		$search_type = $req->search_type;
		//index.php에서 form을 통해 검색어와 검색조건의 정보를 보내주는데 그걸 함수에서 매개변수로 받아서 $req로 사용한다.
		if (Str::contains($_SERVER['REQUEST_URI'], 'notice')) {

			//현재 url의 위치가 공지게시판일 경우 articles테이블에서 article type이 notice로 설정되어있는 데이터들 가운데서만 검색을 진행한다.
			$articles = Article::where([
				['article_type', '/notice'],
				[$search_type, 'like', '%' . $keyword . '%'],
			])->orderBy('priority', 'desc')->latest()->paginate(10);
			$articles->appends(['keyword' => $keyword, 'search_type' => $search_type]);
			//쿼리파라미터에 페이지의 쪽수를 넣는게 아니라, 페이지위치는 그대로 남아있기 때문에 거기에 keyword와 search_type값을 아예 링크에 추가시키기 위해 appends를 이용했다.
		} else {
			$articles = Article::where([
				['article_type', '/free'],
				[$search_type, 'like', '%' . $keyword . '%'],
			])->orderBy('priority', 'desc')->latest()->paginate(10);
			$articles->appends(['keyword' => $keyword, 'search_type' => $search_type]);
		}
		return view('articles.index', ['articles' => $articles]);
	}
	public function show(Article $article)		//show single resoure
	{

		//index.php에서 리스트에 있는 아이템을 클릭했을 경우, articles폴더에 있는 show.php 라는 뷰 화면으로 이동하게 되며 리스트에 있던 article 정보를 그대로 show에서도 이용할 수 있게 넘겨준다.
		return view('articles.show', ['article' => $article]);
	}
	public function create()		//shows a view to create a new resource
	{
		//게시글을 작성 기능을 가진 함수. create.php 화면으로 이동시킨다.
		return view('articles.create');
	}
	public function store(Request $request)	//persist the new resource
	{
		//create 화면에서 form submit 버튼을 누르면 작동되는 함수이다. create.php 에서 form을 통해 넘어오는 정보를 사용해야 하기 때문에 매개변수로 request를 이용한다.
		request()->validate([
			'title' => 'required',
			'contents' => 'required'
		]);
		//반드시 제목과 내용의 정보가 있어야한다.
		if ($request->file('file')) {
			//만약 첨부파일이 있을 경우
			$file_name = $request->file('file')->getClientOriginalName();
			//사용자에게 보여지고 인식되는 파일의 명시적인 이름을 가져와 $file_name에 담는다.
			$file_path = $request->file('file')->store('files');
			//첨부파일이 files라는 폴더에 저장이 되는데 그 경로를 가져와 $file_path에 담아준다.
			Article::create([
				'user_id' => auth()->user()->id,
				'title' => request('title'),
				'contents' => request('contents'),
				'file_name' => $file_name,
				'file_path' => $file_path,
				'article_type' => request('article_type'),
				'priority' => request('priority'),
			]);
		} else {
			//첨부파일이 없을 경우 파일은 모델 생성에 넣지 않고 진행한다.
			Article::create([
				'user_id' => auth()->user()->id,
				'title' => request('title'),
				'contents' => request('contents'),
				'article_type' => request('article_type'),
				'priority' => request('priority'),
			]);
		}
		if (Str::contains($_SERVER['REQUEST_URI'], 'free')) {
			return redirect(route('free.index'));
		} else {
			return redirect(route('notice.index'));
		}
	}
	public function download(Article $article)
	{
		return Storage::download($article->file_path);
		//show.php에서 첨부된 파일명을 클릭하면 파일을 다운로드할 수 있게끔 작동하는 함수인데,
		//이때 사용하는 데이터는 파일의 경로인 file_name을 사용한다.
	}
	public function edit(Article $article)		//show a view to edit an resource
	{
		//find the article associated with the id
		return view('articles.edit', ['article' => $article]);
		//show.php에서 수정하기 버튼을 누르면 edit.php라는 view로 연결된다.
	}
	public function update(Request $request, Article $article)		//persist the edited resource
	{
		//edit.php에서 submit버튼을 누르면 작동되는 함수
		//기존의 article에 대한 정보도 필요하고 새로 입력받는 데이터에 대한 요청도 필요하기 때문에 2가지의 매개변수를 받았다.
		request()->validate([
		//request의 validate 메소드를 사용하여 유효성검사 생성
			'title' => 'required',
			'contents' => 'required'
		]);
		//title과 contents 값이 있는지 확인한다.
		if ($request->file('file')) {
			//만약 수정화면에서 파일을 새로 첨부했을 경우
			Storage::delete($article->file_path);
			//기존에 있던 파일의 경로를 가져와 삭제한후,
			$file_path = $request->file('file')->store('files');
			//새로 파일을 저장한 후 다시 그 경로를 file_path에 담아준다.
			$file_name = $request->file('file')->getClientOriginalName();
			//새로 첨부된 파일의 기존이름도 file_name에 설정해둔다.
			$article->file_name = $file_name;
			$article->file_path = $file_path;
			//위 두가지 데이터들을 새로 업데이트한다.
		}
		//새롭게 파일을 첨부하지 않을 경우, 제목과 내용만 업데이트한다.
		$article->title = request('title');
		$article->contents = request('contents');
		$article->save();
		return view('articles.show', ['article' => $article]);
		//다시 show.php로 돌아가 업데이트된 article의 정보를 전달한다.
	}
	public function destroy(Article $article)		//delete the resource
	{
		//show.php에서 파일 삭제를 눌렀을 때 작동되는 함수이다.
		//게시글이 삭제되면 저장소에 저장되어 있는 첨부파일도 삭제 되어야햐기 때문에 파일경로를 이용해 파일도 삭제해준다.
		Storage::delete($article->file_path);
		//그리고 해당 게시글을 삭제해준다.
		$article->delete();
		if (Str::contains($_SERVER['REQUEST_URI'], '/free')) {
			return redirect(route('free.index'));
		} else {
			return redirect(route('notice.index'));
		}
	}
}
