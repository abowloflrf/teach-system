<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\GraProject;
use App\User;

class GraduationController extends Controller
{
    //学生创建毕业项目
    public function create(Request $request)
    {
        $gra = GraProject::where('student_id', Auth::user()->id)->get();
        if (count($gra) > 0)
            return array(
            "status" => "ERROR",
            "msg" => "您已经有毕业项目了，请勿重复创建"
        );
        $res = GraProject::create([
            'title' => $request->title,
            'description' => $request->description,
            'student_id' => Auth::user()->id,
            'teacher_id' => $request->teacher,
            'year' => $request->year
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

    //学生获取自己的毕业项目详细信息
    public function getMine()
    {
        $graduation = Auth::user()->getGraduation();
        if (!$graduation)
            return '{}';
        return response()->json(
            array(
                'title' => $graduation['title'],
                'description' => $graduation['description'],
                'year' => $graduation['year'],
                'status' => $graduation['status'],
                'teacher' => $graduation->teacher->name,
                'task_file' => $graduation['task_file'],
                'start_file' => $graduation['start_file'],
                'middle_file' => $graduation['middle_file'],
                'end_file' => $graduation['end_file']
            )
        );
    }

    //学生更新自己的毕业项目
    public function updateMine(Request $request)
    {
        $op = intval($request->operation);
        $graduation = Auth::user()->getGraduation();
        if ($op == 101) {
            //提交选题报告
            $graduation->start_file = $request->start_file;
            $graduation->status = 3;
            if ($graduation->save())
                return response()->json([
                'status' => 'OK',
                'msg' => '成功提交开题报告'
            ]);
        } elseif ($op == 102) {
            //提交中期材料
            $graduation->middle_file = $request->middle_file;
            $graduation->status = 4;
            if ($graduation->save())
                return response()->json([
                'status' => 'OK',
                'msg' => '成功提交中期材料'
            ]);
        } elseif ($op == 103) {
            //提交结题材料
            $graduation->end_file = $request->end_file;
            $graduation->status = 5;
            if ($graduation->save())
                return response()->json([
                'status' => 'OK',
                'msg' => '成功提交结题材料'
            ]);
        } else {
            return response("Invaild Operation", 403);
        }
    }

    //教师获取自己所有的所有毕业项目
    public function getMyAll()
    {
        $list = Auth::user()->getGraduations();
        $res = array();
        foreach ($list as $item) {
            $res[] = array(
                'id' => $item['id'],
                'title' => $item['title'],
                'description' => $item['description'],
                'year' => $item['year'],
                'status' => $item['status'],
                'student' => $item->student->name,
                'task_file' => $item['task_file'],
                'start_file' => $item['start_file'],
                'middle_file' => $item['middle_file'],
                'end_file' => $item['end_file']
            );
        }
        return response()->json($res);
    }

    //教师更新单条毕业项目
    public function updateOne(Request $request, $id)
    {
        $op = intval($request->operation);
        $graduation = GraProject::find($id);
        if ($op == 201) {
            //通过创建申请，并发布任务书
            $graduation->task_file = $request->task_file;
            $graduation->status = 2;
        } else {
            return response("Invaild Operation", 403);
        }
        if ($graduation->save())
            return response()->json([
            'status' => 'OK',
            'msg' => '成功发布任务书'
        ]);
    }
}
