<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SrtpProject;
use Illuminate\Support\Facades\Auth;

class SrtpController extends Controller
{
    // 下面为学生使用的方法
    public function create(Request $request)
    {
        $srtp = SrtpProject::where('leader_id', Auth::user()->id)->get();
        if (count($srtp) > 0)
            return array(
            "status" => "ERROR",
            "msg" => "您已经有SRTP项目了，请勿重复创建"
        );
        $res = SrtpProject::create([
            'title' => $request->title,
            'leader_id' => Auth::user()->id,
            'teacher_id' => $request->teacher,
            'level' => $request->level,
            'year' => $request->year,
            'description' => $request->description,
            'members' => $request->members,
            'apply_file' => $request->apply_file
        ]);
        if ($res) {
            return array(
                "status" => "OK",
                "msg" => "创建成功"
            );
        } else {
            return array(
                "status" => "ERROR",
                "msg" => "创建失败"
            );
        }
    }

    public function getMySrtp()
    {
        return response()->json(
            Auth::user()->getSrtp()
        );
    }

    public function updateMySrtp(Request $request)
    {
        //获取请求操作码
        $op = intval($request->operation);
        //获取请求用户（学生）的所属srtp项目
        $srtp = Auth::user()->getSrtp();
        if ($op == 101) {
            //提交中期材料
            $srtp->middle_file = $request->middle_file;
            $srtp->status = 3;
            if ($srtp->save())
                return response()->json([
                'status' => 'OK',
                'msg' => '成功提交中期材料'
            ]);
        } elseif ($op == 102) {
            //提交终止申请，可能在中期或者后期提交终止报告
            $srtp->abort_file = $request->end_file ? $request->end_file : $request->middle_file;
            $srtp->status = 8;
            if ($srtp->save())
                return response()->json([
                'status' => 'OK',
                'msg' => '成功提交终止材料'
            ]);
        } elseif ($op == 103) {
            //提交延期申请，只可能在后期提交结题报告
            $srtp->postpond_file = $request->end_file;
            $srtp->status = 5;
            if ($srtp->save())
                return response()->json([
                'status' => 'OK',
                'msg' => '成功延期'
            ]);
        } elseif ($op == 104) {
            //提交结题材料
            $srtp->end_file = $request->end_file;
            $srtp->status = 6;
            if ($srtp->save())
                return response()->json([
                'status' => 'OK',
                'msg' => '成功提交结题材料'
            ]);
        } else {
            return response("Invaild Operation", 403);
        }
    }

    // 下面为秘书使用的方法
    public function all()
    {
        $res = array();
        foreach (SrtpProject::all() as $item) {
            $res[] = array(
                'id' => $item['id'],
                'title' => $item['title'],
                'year' => $item['year'],
                'level' => $item['level'],
                'status' => $item['status'],
                'leader' => $item->leader->name,
                'teacher' => $item->teacher->name,
                'description' => $item['description'],
                'members' => $item['members'],
                'apply_file' => $item['apply_file'],
                'middle_file' => $item['middle_file'],
                'end_file' => $item['end_file'],
                'postpond_file' => $item['postpond_file'],
                'abort_file' => $item['abort_file']
            );
        }
        return response()->json($res);
    }

    public function updateSrtp(Request $request, $id)
    {
        $op = intval($request->operation);
        $srtp = SrtpProject::find($id);
        if (!$srtp)
            return response("SRTP Not Found", 404);
        if ($op == 201) {
            //通过创建申请
            $srtp->level = $request->new_level;
            $srtp->status = 2;
        } elseif ($op == 202) {
            //通过中期审核
            $srtp->status = 4;
        } elseif ($op == 203) {
            //通过结题审核
            $srtp->status = 7;
        } elseif ($op == 204) {
            //驳回创建申请/结题审核/结题审核 直接进入到终止状态
            $srtp->status = 8;
        } else {
            return response("Invaild Operation", 403);
        }
        if ($srtp->save())
            return response()->json([
            'status' => 'OK',
            'msg' => '成功更新状态'
        ]);
    }
}
