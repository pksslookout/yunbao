<?php

/**
 * 官方通知
 */
namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;

class OfficialController extends AdminbaseController {

    function log(){
        $data = $this->request->param();
        $map=[];

        $title=isset($data['title']) ? $data['title']: '';
        if($title!=''){
            $map[]=['title','like',"%".$title."%"];
        }	
			
    	$lists = Db::name("official")
                ->where($map)
                ->order("id DESC")
                ->paginate(20);

        
        $lists->appends($data);
        $page = $lists->render();

    	$this->assign('lists', $lists);

    	$this->assign("page", $page);
        

    	return $this->fetch();
    }
    
    function del(){
        
        $id = $this->request->param('id', 0, 'intval');
        
        $rs = DB::name('official')->where("id={$id}")->delete();
        if(!$rs){
            $this->error("删除失败！");
        }
        
        $action="删除官方通知：{$id}";
        setAdminLog($action);
        
        $this->success("删除成功！",url("official/log"));
    }


    function add(){
        return $this->fetch();
    }

    function addPost(){
        if ($this->request->isPost()) {

            $data      = $this->request->param();

            $data['content']=isset($data['content'])?$data['content']:'';
            $data['type']=trim($data['type']);
            $data['url']=isset($data['url'])?trim($data['url']):'';
            $data['title']=trim($data['title']);
            $data['introduction']=trim($data['introduction']);
            $data['push_user']=trim($data['push_user']);
            $data['push_ip']=trim(get_client_ip());
            $title=$data['title'];
            $category_title=$data['introduction'];

            if($title==""){
                $this->error("消息标题不能为空");
            }

            if($category_title==""){
                $this->error("简介不能为空");
            }

            $isexit=DB::name('official')->where(["title"=>$title])->find();
            if($isexit){
                $this->error('该消息标题已存在');
            }

            $data['addtime']=time();
            $data['pushtime']=time();

            $id = DB::name('official')->insertGetId($data);
            if(!$id){
                $this->error("添加失败！");
            }
            $data = [];
            $data['id'] = $id;
            $data['url'] ='/appapi/message/msginfo.html&id='.$id;
            $rs = DB::name('official')->update($data);
            if($rs===false){
                $this->error("修改失败！");
            }
            $action="添加消息：{$id}";
            setAdminLog($action);

            $this->success("添加成功！");

        }
    }

    function push(){
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
    
}
