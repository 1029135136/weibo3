<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    //


    /**
     * 权限控制模块
     * UsersController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth', [
            'except'=>['show','create','store']
        ]);
        $this->middleware('guest', [
            'only'=>['create','store']
        ]);
    }

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
        return view('users.edit', compact('user'));
    }

    public function update(User $user,Request $request)
    {
        //权限校验
        $this->authorize('update', $user);

        $this->validate($request, [
            'name' => 'required|max:50',
            'password' => 'nullable|confirmed|min:6'
        ]);
        $data = [];
        $data['name'] = $request->input('name');
        //如果请求中包含密码则更新密码
        if ($request->input('password')) {
            $data['password'] = bcrypt($request->input('password'));
        }
        $user->newQuery()->update($data);
        session()->flash('success','用户资料更新成功');
        return redirect()->route('users.show', $user->fillable(['id']));
    }
}
