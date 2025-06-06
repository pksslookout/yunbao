<?php

/**
 * 直播举报
 */
namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;

class ReportController extends AdminbaseController {
    protected function getStatus($k=''){
        $status=[
            '0'=>'待处理',
            '1'=>'已处理',
        ];
        
        if($k==''){
            return $status;
        }
        return $status[$k];
    }
    function index(){
        $data = $this->request->param();
        $map=[];
        
        $start_time=isset($data['start_time']) ? $data['start_time']: '';
        $end_time=isset($data['end_time']) ? $data['end_time']: '';
        
        if($start_time!=""){
           $map[]=['addtime','>=',strtotime($start_time)];
        }

        if($end_time!=""){
           $map[]=['addtime','<=',strtotime($end_time) + 60*60*24];
        }
        
        $status=isset($data['status']) ? $data['status']: '';
        if($status!=''){
            $map[]=['status','=',$status];
        }
        
        $uid=isset($data['uid']) ? $data['uid']: '';
        if($uid!=''){
            $lianguid=getLianguser($uid);
            if($lianguid){
                $map[]=['uid',['=',$uid],['in',$lianguid],'or'];
            }else{
                $map[]=['uid','=',$uid];
            }
        }	
			

    	$lists = Db::name("report")
                ->where($map)
                ->order("id DESC")
                ->paginate(20);
        
        $lists->each(function($v,$k){
			$v['userinfo']=getUserInfo($v['uid']);
            $user_status=Db::name("user")->where("id={$v['touid']}")->value('user_status');
            $touserinfo=getUserInfo($v['touid']);
            $touserinfo['user_status']=$user_status;
			$v['touserinfo']=$touserinfo;
            return $v;           
        });
        
        $lists->appends($data);
        $page = $lists->render();

    	$this->assign('lists', $lists);

    	$this->assign("page", $page);
        
    	$this->assign("status", $this->getStatus());
    	
    	return $this->fetch();
    }
		
    function setstatus(){
        $id = $this->request->param('id', 0, 'intval');
        $data['status']=1;
        $data['uptime']=time();
        $data=[
            'status'=>1,
            'uptime'=>time(),
        ];
        $rs = DB::name('report')->where("id={$id}")->update($data);
        if($rs===false){
            $this->error("标记失败！");
        }
        
        $action="直播举报标记处理：{$id}";
        setAdminLog($action);
        
        $this->success("标记成功！");
        							  		
    }		
    
    function del(){
        
        $id = $this->request->param('id', 0, 'intval');
        
        $rs = DB::name('report')->where("id={$id}")->delete();
        if(!$rs){
            $this->error("删除失败！");
        }
        
        $action="删除直播举报：{$id}";
        setAdminLog($action);
        
        $this->success("删除成功！",url("report/index"));								  
    }

    function userIndex(){
        $data = $this->request->param();
        $map=[];

        $start_time=isset($data['start_time']) ? $data['start_time']: '';
        $end_time=isset($data['end_time']) ? $data['end_time']: '';

        if($start_time!=""){
            $map[]=['addtime','>=',strtotime($start_time)];
        }

        if($end_time!=""){
            $map[]=['addtime','<=',strtotime($end_time) + 60*60*24];
        }

        $status=isset($data['status']) ? $data['status']: '';
        if($status!=''){
            $map[]=['status','=',$status];
        }

        $uid=isset($data['uid']) ? $data['uid']: '';
        if($uid!=''){
            $lianguid=getLianguser($uid);
            if($lianguid){
                $map[]=['uid',['=',$uid],['in',$lianguid],'or'];
            }else{
                $map[]=['uid','=',$uid];
            }
        }


        $lists = Db::name("report_user")
            ->where($map)
            ->order("id DESC")
            ->paginate(20);

        $lists->each(function($v,$k){
            $v['userinfo']=getUserInfo($v['uid']);
            $user_status=Db::name("user")->where("id={$v['touid']}")->value('user_status');
            $touserinfo=getUserInfo($v['touid']);
            $touserinfo['user_status']=$user_status;
            $v['touserinfo']=$touserinfo;
            return $v;
        });

        $lists->appends($data);
        $page = $lists->render();

        $this->assign('lists', $lists);

        $this->assign("page", $page);

        $this->assign("status", $this->getStatus());

        return $this->fetch();
    }

    // 用户举报分类
    function classify(){

        $data = $this->request->param();
        $map=[];
        $keyword=isset($data['keyword']) ? $data['keyword']: '';
        if($keyword!=''){
            $map[]=['name','like',"%".$keyword."%"];
        }

        $lists = Db::name("report_user_classify")
            ->where($map)
            ->order("list_order asc")
            ->paginate(20);

        $lists->appends($data);
        $page = $lists->render();

        $this->assign('lists', $lists);

        $this->assign("page", $page);

        return $this->fetch();
    }

    //排序
    public function listOrder() {

        $model = DB::name('report_user_classify');
        parent::listOrders($model);


        $action="更新用户举报类型排序";
        setAdminLog($action);

        $this->success("排序更新成功！");

    }

    function catDel(){

        $id = $this->request->param('id', 0, 'intval');

        $rs = DB::name('report_user_classify')->where("id={$id}")->delete();
        if(!$rs){
            $this->error("删除失败！");
        }

        $action="删除直播举报类型：{$id}";
        setAdminLog($action);

        $this->success("删除成功！",url("report/classify"));

    }


    function cat_add(){
        return $this->fetch();
    }

    function catAddPost(){
        if ($this->request->isPost()) {

            $data      = $this->request->param();

            $data['name']=trim($data['name']);
            $data['category_title']=trim($data['category_title']);
            $name=$data['name'];
            $category_title=$data['category_title'];

            if($name==""){
                $this->error("名称不能为空");
            }

            if($category_title==""){
                $this->error("CATEGORY TITLE不能为空");
            }

            $isexit=DB::name('report_user_classify')->where(["name"=>$name])->find();
            if($isexit){
                $this->error('该名称已存在');
            }

            $data['edittime']=time();
            $data['addtime']=time();

            $id = DB::name('report_user_classify')->insertGetId($data);
            if(!$id){
                $this->error("添加失败！");
            }

            $action="添加用户举报类型：{$id}";
            setAdminLog($action);

            $this->success("添加成功！");

        }
    }

    function cat_edit(){
        $id   = $this->request->param('id', 0, 'intval');

        $data=Db::name('report_user_classify')
            ->where("id={$id}")
            ->find();
        if(!$data){
            $this->error("信息错误");
        }

        $this->assign('data', $data);
        return $this->fetch();
    }

    function catEditPost(){
        if ($this->request->isPost()) {

            $data      = $this->request->param();

            $data['name']=trim($data['name']);
            $data['category_title']=trim($data['category_title']);
            $name=$data['name'];
            $category_title=$data['category_title'];
            $id=$data['id'];

            if($name==""){
                $this->error("名称不能为空");
            }
            if($category_title==""){
                $this->error("CATEGORY TITLE不能为空");
            }

            $isexit=DB::name('report_user_classify')->where([['id','<>',$id],['name','=',$name],['category_title','=',$category_title]])->find();
            if($isexit){
                $this->error('该CATEGORY TITLE已存在');
            }

            $rs = DB::name('report_user_classify')->update($data);
            if($rs===false){
                $this->error("修改失败！");
            }

            $action="修改用户举报类型：{$id}";
            setAdminLog($action);

            $this->success("修改成功！");
        }
    }
    
}
