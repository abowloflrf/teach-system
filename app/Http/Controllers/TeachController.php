<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TeachProject;
use Illuminate\Support\Facades\Auth;

class TeachController extends Controller
{
    //教师创建自己的教改项目
    public function create(Request $request)
    {
        $res = TeachProject::create([
            'title' => $request->title,
            'description' => $request->description,
            'teacher_id' => Auth::user()->id,
            'level' => $request->level,
            'year' => $request->year,
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

    //教师获取自己所有教改项目
    public function getMyAll(Request $request)
    {
        $list = $request->user()->getTeachProjects();
        return response()->json($list);
    }

    //教师获取自己的教改项目详细信息
    public function getMyOne(Request $request, $id)
    {
        $teach = TeachProject::find($id);
        if ($teach->owner->id != Auth::user()->id)
            return response("Operation Forbiddon", 403);
        if ($teach)
            return response()->json(array(
            'status' => 'OK',
            'teach' => $teach
        ));
        else
            return response()->json(array(
            'status' => 'ERROR',
            'msg' => 'Teach Project Not Found'
        ));

    }

    //教师更新自己的某条教改
    public function updateMyOne(Request $request, $id)
    {
        //获取请求操作码
        $op = $request->operation;
        $teach = TeachProject::find($id);
        if ($teach->owner->id != Auth::user()->id)
            return response("Operation Forbiddon", 403);
        if ($op == 101) {
            //提交中期材料
            $teach->middle_file = $request->middle_file;
            $teach->status = 3;
            if ($teach->save())
                return response()->json([
                'status' => 'OK',
                'msg' => '成功提交中期材料'
            ]);
        } elseif ($op == 102) {
            //提交结题材料
            $teach->end_file = $request->end_file;
            $teach->status = 5;
            if ($teach->save())
                return response()->json([
                'status' => 'OK',
                'msg' => '成功提交结题材料'
            ]);
        } elseif ($op == 103) {
            //提交终止材料
            $teach->abort_file = $request->end_file;
            $teach->status = 7;
            if ($teach->save())
                return response()->json([
                'status' => 'OK',
                'msg' => '成功终止'
            ]);
        } else {
            return response("Invaild Operation", 403);
        }
    }

    //秘书获取所有教改项目
    public function getAll()
    {
        $res = array();
        foreach (TeachProject::all() as $item) {
            $res[] = array(
                'id' => $item['id'],
                'title' => $item['title'],
                'description' => $item['description'],
                'year' => $item['year'],
                'level' => $item['level'],
                'status' => $item['status'],
                'teacher' => $item->owner->name,
                'apply_file' => $item['apply_file'],
                'middle_file' => $item['middle_file'],
                'end_file' => $item['end_file'],
                'abort_file' => $item['abort_file']
            );
        }
        return response()->json($res);
    }

    //秘书更新某条教改项目
    public function updateOne(Request $request, $id)
    {
        $op = $request->operation;
        $teach = TeachProject::find($id);
        if ($op == 201) {
            //通过创建申请
            $teach->level = $request->new_level;
            $teach->status = 2;
        } elseif ($op == 202) {
            //通过中期审核
            $teach->status = 4;
        } elseif ($op == 203) {
            //通过结题审核
            $teach->status = 6;
        } elseif ($op == 204) {
            //驳回创建申请/结题审核/结题审核 直接进入到终止状态
            $teach->status = 7;
        } else {
            return response("Invaild Operation", 403);
        }
        if ($teach->save())
            return response()->json([
            'status' => 'OK',
            'msg' => '成功审批状态'
        ]);
    }
}
