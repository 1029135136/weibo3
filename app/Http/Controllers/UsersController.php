<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    //

    public function create()
    {
        return view('users.create');
    }

    //显示用户个人信息的页面
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * 用户注册处理器
     * @param Request $request
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:users|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        //注册后自动登录
        Auth::login($user);
        session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');
        //注册成功后，跳转到用户个人中心
        //return redirect()->route('users.show', $user->id);
        return redirect()->route('users.show', $user);
    }

    public function edit(User $user)
    {
        return view('user.edit', compact('user'));
    }
}
