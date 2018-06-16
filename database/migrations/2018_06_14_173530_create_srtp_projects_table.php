<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSrtpProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('srtp_projects', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("leader_id")->comment("项目所属负责人学生ID");
            $table->integer("teacher_id")->comment("项目指导教师ID");
            $table->tinyInteger('level')->default(1)->unsigned()->comment("项目级别 1-院级 2-市级 3-国家级");
            $table->tinyInteger("status")->default(1)->unsigned()->comment("项目状态");
            $table->year('year')->comment("项目年度");
            $table->string('title')->comment("项目标题");
            $table->text("description")->comment("项目简介");
            $table->string("members")->comment("项目所有成员");
            $table->string("apply_file")->default("")->comment("项目申请文档");
            $table->string("middle_file")->default("")->comment("项目中期检查报告");
            $table->string("end_file")->default("")->comment("结题报告");
            $table->string("postpond_file")->default("")->comment("延期申请材料");
            $table->string("abort_file")->default("")->comment("终止材料");
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
        Schema::dropIfExists('srtp_projects');
    }
}
