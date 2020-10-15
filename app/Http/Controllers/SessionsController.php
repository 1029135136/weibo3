<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use League\CommonMark\Block\Element\AbstractBlock;
use PhpParser\Node\Stmt\DeclareDeclare;

class SessionsController extends Controller
{
    //登录静态页面
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
        if (Auth::attempt($credentials)) {
            session()->flash('success','登录成功，欢迎使用');
            return redirect()->route('users.show', [Auth::user()]);
        } else {
            session()->flash('danger','邮箱或密码不正确');
            return redirect()->back()->withInput();
        }
    }
}
