<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGraProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gra_projects', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("student_id")->comment("毕设学生ID");
            $table->integer("teacher_id")->comment("指导教师ID");
            $table->tinyInteger("status")->default(1)->unsigned()->comment("项目状态");
            $table->year('year')->comment("毕业年度");
            $table->string('title')->comment("毕设标题");
            $table->text("description")->comment("项目简介");
            $table->string("task_file")->default("")->comment("任务书");
            $table->string("start_file")->default("")->comment("开题报告");
            $table->string("middle_file")->default("")->comment("项目中期检查报告");
            $table->string("end_file")->default("")->comment("结题材料");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gra_projects');
    }
}
