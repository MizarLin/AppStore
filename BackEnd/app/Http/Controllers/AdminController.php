<?php

namespace App\Http\Controllers;

use App\Categories;
use Illuminate\Support\Facades\Storage;
use App\MemberImgs;
use Illuminate\Http\Request;
use App\Members;
use App\Apps;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    //修改密碼(待討論是否可以修改Name)
    public function pwdChange(Members $member, Request $request, $id)
    {

        $this->validate($request, [
            'name' => 'required|string',
            'oldPwd' => ['required', 'regex:/[0-9A-Za-z]/', 'min:8', 'max:12'],
            'newPwd' => ['required', 'regex:/[0-9A-Za-z]/', 'min:8', 'max:12'],
            'pwdCheck' => ['required', 'same:newPwd'],
        ]);
        // $id = session::get('member_id');
        $oldPwd = md5($request->oldPwd);
        $count = Members::where([
            ['id', '=', $id], ['password', '=', $oldPwd]
        ])->count();
        if ($count === 1) {
            Members::where('id', '=', $id)->update(['password' => md5($request->newPwd)]);
            return response()->json(["isSuccess" => "True"]);
        } else return response()->json(["isSuccess" => "False"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    //新增分類
    public function addCategory(Request $request)
    {
        $this->validate($request, [
            'category' => 'required|string|unique:categories',
        ]);
        if (isset($request->category)) {
            $category = $request->category;
            Categories::insert(['category' => $category]);
            return response()->json(["isSuccess" => "True"]);
        } else return response()->json(["isSuccess" => "False"]);
    }


    //管理員新增會員頭像
    public function newIcon(Request $request)
    {
        $icon = $request->file('imgs');
        $extension = strtolower($icon->getClientOriginalExtension()); //副檔名轉小寫
        if (
            $extension === 'png' || $extension === 'jpeg' ||
            $extension === 'jpg' || $extension === 'gif'
        ) {
            $file_name =  time() . rand(100000, 999999) . '.' . $extension;
            // $file_name = time(). '.' . $extension;
            $path = Storage::putFileAs('Member_icon', $icon, $file_name);
            if ($icon->isValid()) {
                MemberImgs::insert(
                    ['img' => $path,]
                );
            }
            return response()->json(["isSuccess" => "True"]);
        } else return response()->json(["isSuccess" => "False", "reason" => "file extension error"]);
    }

    //列出全部未審核的app
    public function appCheck()
    {
        return Apps::where('apps.verify', '=', 3)
            ->join('members', 'members.id', '=', 'apps.memberId')
            ->select('apps.id', 'apps.appName', 'apps.summary', 'members.name', 'apps.created_at')
            ->get();
    }

    //管理者首頁 - 計算未審app數、未審開發人員數 及 列出下載量前五名的app
    //前端接口為appCount、devCount、top5
    public function countAll()
    {
        $unCheck_app_count = Apps::where('verify', '=', 3)->count();
        $unCheck_dev_Count = Members::where('verify', '=', 0)->count();
        $top5dowload = Apps::orderBy('downloadTimes', 'desc')->take(5)->pluck('appName');
        return response(['appCount' => $unCheck_app_count, 'devCount' => $unCheck_dev_Count, 'top5' => $top5dowload]);
    }

    //App審核通過 (並return剩餘未審核) 
    //回傳欄位名有修改請告知前端
    public function appCheckOk($id)
    {
        $count = Apps::where('id', '=', $id)->count();
        if ($count === 1) {
            Apps::where('id', '=', $id)->update(['verify' => 1]);
            return Apps::where('apps.verify', '=', 3)
                ->join('members', 'members.id', '=', 'apps.memberId')
                ->select('apps.id', 'apps.appName', 'apps.summary', 'members.name', 'apps.created_at')
                ->get();
        } else return response()->json(["isSuccess" => "False", "reason" => "App not found"]);
    }
    //App審核失敗-退回
    public function appGoBack($id)
    {
        $count = Apps::where('id', '=', $id)->count();
        if ($count === 1) {
            Apps::where('id', '=', $id)->update(['verify' => 2]);
            return Apps::where('apps.verify', '=', 3)
                ->join('members', 'members.id', '=', 'apps.memberId')
                ->select('apps.id', 'apps.appName', 'apps.summary', 'members.name', 'apps.created_at')
                ->get();
        } else return response()->json(["isSuccess" => "False", "reason" => "App not found"]);
    }

    //列出未審核之開發者申請
    public function devCheck()
    {
        return Members::where('verify', '=', 0)
            ->select('id', 'name', 'updated_at')
            ->get();
    }

    //開發者審核通過 (並return剩餘未審核) 
    //回傳欄位名有修改請告知前端
    public function devCheckOk($id)
    {
        $count = Members::where('id', '=', $id)->count();
        if ($count === 1) {
            Members::where('id', '=', $id)->update(['verify' => 1, 'level' => 2]);
            return Members::where('verify', '=', 0)
                ->select('id', 'name', 'updated_at')
                ->get();
        } else return response()->json(["isSuccess" => "False", "reason" => "Member not found"]);
    }

    //開發者審核失敗-退回
    public function devGoBack($id)
    {
        $count = Members::where('id', '=', $id)->count();
        if ($count === 1) {
            Members::where('id', '=', $id)->update(['verify' => null]);
            return Members::where('verify', '=', 0)
                ->select('id', 'name', 'updated_at')
                ->get();
        } else return response()->json(["isSuccess" => "False", "reason" => "Member not found"]);
    }

    //會員管理
    public function memberManage()
    {
        $count = Members::count();
        $List = Members::where('level', '<', 3)->select('id', 'name', 'phone', 'email', 'level', 'stopRight')->get();
        for ($i = 0; $i < $count; $i++) {
            if ($List[$i]->level === 2) //開發者
                $List[$i]->level = '是';
            else if ($List[$i]->level === 1)
                $List[$i]->level = '否';
        }
        return $List;
    }

    //App管理
    public function appManage()
    {
        return Apps::where('verify', '=', 1)->select('id', 'appName', 'summary', 'device', 'stopRight')
            ->get();
    }

    //會員停權
    public function stopMember($id)
    {
        $count = Members::where([['id', '=', $id], ['stopRight', '=', 1]])->count();
        if ($count === 1) {
            Members::where('id', '=', $id)->update(['stopRight' => 0]);
            $count = Members::count();
            $List = Members::where('level', '<', 3)->select('id', 'name', 'phone', 'email', 'level', 'stopRight')->get();
            for ($i = 0; $i < $count; $i++) {
                if ($List[$i]->level === 2) //開發者
                    $List[$i]->level = '是';
                else if ($List[$i]->level === 1)
                    $List[$i]->level = '否';
            }
            return $List;
        } else return response()->json(["isSuccess" => "False", "reason" => "Member not found"]);
    }

    //會員停權恢復
    public function restoreMember($id)
    {
        $count = Members::where([['id', '=', $id], ['stopRight', '=', 0]])->count();
        if ($count === 1) {
            Members::where('id', '=', $id)->update(['stopRight' => 1]);
            $count = Members::count();
            $List = Members::where('level', '<', 3)->select('id', 'name', 'phone', 'email', 'level', 'stopRight')->get();
            for ($i = 0; $i < $count; $i++) {
                if ($List[$i]->level === 2) //開發者
                    $List[$i]->level = '是';
                else if ($List[$i]->level === 1)
                    $List[$i]->level = '否';
            }
            return $List;
        } else return response()->json(["isSuccess" => "False", "reason" => "Member not found"]);
    }

    //App停權
    public function stopApp($id)
    {
        $count = Apps::where([['id', '=', $id], ['stopRight', '=', 1]])->count();
        if ($count === 1) {
            Apps::where('id', '=', $id)->update(['stopRight' => 0]);
            return Apps::select('id', 'appName', 'summary', 'device')->get();
        } else return response()->json(["isSuccess" => "False", "reason" => "App not found"]);
    }

    //App停權恢復
    public function restoreApp($id)
    {
        $count = Apps::where([['id', '=', $id], ['stopRight', '=', 0]])->count();
        if ($count === 1) {
            Apps::where('id', '=', $id)->update(['stopRight' => 1]);
            return Apps::select('id', 'appName', 'summary', 'device')->get();
        } else return response()->json(["isSuccess" => "False", "reason" => "App not found"]);
    }

    //新增開發者  >> 待測試 <<
    public function newDeveloper(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'phone' => ['required', 'regex:/^09\d{8}$/', 'unique:members'],
            'email' => 'required|email|unique:members',
            'idNumber' => ['required', 'regex:/^[A-Z][1,2]\d{8}$/', 'unique:members'],
            'password' => ['required', 'regex:/[0-9A-Za-z]/', 'min:8', 'max:12'],
        ]);

        Members::insert([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'idNumber' => $request->idNumber,
            'password' => md5($request->password),
            'level' => 2
        ]);

        return response()->json(["isSuccess" => "True"]);
    }
}
