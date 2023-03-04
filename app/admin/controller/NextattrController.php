<?php

// +----------------------------------------------------------------------
// | NextStack  
// +----------------------------------------------------------------------
// | Copyright (c) Lyove https://github.com/lyove All rights reserved.
// +----------------------------------------------------------------------
// | Author: lyove
// +----------------------------------------------------------------------


namespace app\admin\controller;

use framework\extend\Page;

class NextattrController extends CommonController
{
	
	public function index(){
		$molds = 'attr';
		$this->molds = M('Molds')->find(array('biaoshi'=>$molds));
		$data = $this->frparam();
		if($this->frparam('ajax')){
			
			$page = new Page($molds);
			$sql = null;
			$data = $page->where($sql)->orderby('id desc')->limit($this->frparam('limit',0,10))->page($this->frparam('page',0,1))->go();
			$ajaxdata = [];
			foreach($data as $k=>$v){
				
				$v['new_isshow'] = $v['isshow']==1 ? NEXTLANG('显示') : NEXTLANG('隐藏');
				
				$ajaxdata[]=$v;
				
			}
			
			$pages = $page->pageList();
			$this->pages = $pages;
			$this->lists = $data;
			$this->sum = $page->sum;
			JsonReturn(['code'=>0,'data'=>$ajaxdata,'count'=>$page->sum]);
			
			
		}
		
		
		$this->display('attr-list');
		
	}
	
	public function addAttr(){
		$w['name'] = $this->frparam('v',1);
		if(!$w['name']){
			JsonReturn(['code'=>1,'msg'=>NEXTLANG('请输入属性名称！')]);
		}
		if(M('attr')->find(['name'=>$w['name']])){
			JsonReturn(['code'=>1,'msg'=>NEXTLANG('属性已存在！')]);
			
		}
		$w['isshow'] = 1;
		M('attr')->add($w);
		JsonReturn(['code'=>0,'msg'=>NEXTLANG('添加成功！')]);
		
		
	}
	public function editAttr(){
		$id = $this->frparam('id');
		$w['name'] = $this->frparam('v',1);
		if(!$id){
			JsonReturn(['code'=>1,'msg'=>NEXTLANG('参数错误！')]);
		}
		if(!$w['name']){
			JsonReturn(['code'=>1,'msg'=>NEXTLANG('请输入属性名称！')]);
		}
		$sql = " name='".$w['name']."' and id!=".$id;
		if(M('attr')->find($sql)){
			JsonReturn(['code'=>1,'msg'=>NEXTLANG('属性已存在！')]);
			
		}
		
		M('attr')->update(['id'=>$id],$w);
		JsonReturn(['code'=>0,'msg'=>NEXTLANG('添加成功！')]);
		
		
	}
	
	
	//单一删除
	function delAttr(){
		$id = $this->frparam('id');
		if($id){
			if($id<=3){
				JsonReturn(array('code'=>1,'msg'=>NEXTLANG('系统属性只允许修改和隐藏，不允许删除！')));
			}
			if(M('attr')->delete(['id'=>$id])){
				
				JsonReturn(array('code'=>0,'msg'=>NEXTLANG('删除成功！')));
			}else{
				
				JsonReturn(array('code'=>1,'msg'=>NEXTLANG('删除失败！')));
			}
		}
	}
	
	function changeStatus(){

		$w['isshow'] = $this->frparam('value',0,0);
		$r = M('attr')->update(array('id'=>$this->frparam('id',0,0)),$w);
		if(!$r){
			JsonReturn(array('code'=>1,'info'=>NEXTLANG('修改失败！')));
		}
		JsonReturn(array('code'=>0,'info'=>NEXTLANG('修改成功！')));

	}
	



}