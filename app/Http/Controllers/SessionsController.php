<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use League\CommonMark\Block\Element\AbstractBlock;
use PhpParser\Node\Stmt\DeclareDeclare;

class SessionsController extends Controller
{


    //登录静态页面
    /**
     * SessionsController constructor.
     */
    public function __construct()
    {
        //游客只能访问登录夜，提交登录请求
        $this->middleware('guest', [
            'only' => ['create', 'store']
        ]);
        //退出登录只能是已经登录后的用户使用
        $this->middleware('auth', [
            'only' => ['destroy']
        ]);
    }

    public function create()
    {
        return view('sessions.create');
    }

    public function store(Request $request)
    {
        $credentials = $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if (Auth::attempt($credentials, $request->has('remember'))) {
            $fallback = route('users.show', [Auth::user()]);
            session()->flash('success', '登录成功，欢迎使用');
            //返回用户之前尝试过的页面，如果没有尝试过，则返回$fallback的默认页面
            return redirect()->intended($fallback);
        } else {
            session()->flash('danger', '邮箱或密码不正确');
            return redirect()->back()->withInput();
        }
    }

    public function destroy()
    {
        Auth::logout();
        session()->flash('success', '退出登录状态成功');
        return view('sessions.create');
    }
}
