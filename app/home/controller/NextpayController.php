<?php

// +----------------------------------------------------------------------
// | NextStack  
// +----------------------------------------------------------------------
// | Copyright (c) Lyove https://github.com/lyove All rights reserved.
// +----------------------------------------------------------------------
// | Author: lyove
// +----------------------------------------------------------------------


namespace app\home\controller;


use framework\extend\Page;

class NextpayController extends CommonController
{
	/**
	 * 支付接口
	 **/
	
	public function _init(){
		parent::_init();
	}
	
	
	//检查是否支付
	private function checke_order($orderno=null){
		$w['orderno'] = $orderno;
		$w['mchid'] = $this->webconf['next_mchid'];
		$api = $this->webconf['next_pay_url'].'/Pay/query_order';
		$data = curl_http($api,$w);
		$res = json_decode($data,true);
		if($res['code']==0){
			if($res['data']['ispay']==1){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	
	}
	
	//同步跳转
	function return_pay(){
		//记录一下支付信息
		//register_log($_REQUEST,'return_pay_log');
		$orderno = $this->frparam('orderno',1);
		$ispay = $this->frparam('ispay');
		$checkpay = $this->checke_order($orderno);
		if($ispay==1 && $checkpay){
			$paytime = $this->frparam('paytime');
			$order = M('orders')->find(['orderno'=>$orderno]);
			if(!$order){
				Error(NEXTLANG('支付成功，但是系统内没有找到相应的订单！').$orderno,get_domain());
			}
			if($order['ispay']==1){
				//跳转对应查询详情
				//Success('支付成功！',U('User/details',['id'=>$order['id']]));
				$this->overpay($order['orderno']);
				exit;
			}
			
			$r = M('orders')->update(['orderno'=>$orderno],['ispay'=>1,'isshow'=>2,'paytime'=>$paytime]);
		
			//支付成功后处理...
			$this->overpay($order['orderno']);
			exit;
				
		}else{
			Error(NEXTLANG('支付失败'),get_domain());
		}
	}
	//异步跳转--只处理状态
	function notify_pay(){
		//记录一下支付信息
		//register_log($_REQUEST,'notify_pay_log');
		$orderno = $this->frparam('orderno',1);
		$ispay = $this->frparam('ispay');
		$checkpay = $this->checke_order($orderno);
		if($ispay==1 && $checkpay){
			$paytime = $this->frparam('paytime');
			$order = M('orders')->find(['orderno'=>$orderno]);
			if(!$order){
				//Error('支付成功，但是系统内没有找到相应的订单！No.'.$orderno);
				
				exit;
			}
			if($order['ispay']==1){
				//跳转对应查询详情
				//Error('已支付成功，请勿重复操作！',U('User/details',['id'=>$order['id']]));
				
				exit;
			}
			
			$r = M('orders')->update(['orderno'=>$orderno],['ispay'=>1,'isshow'=>2,'paytime'=>$paytime]);
			if($r){
				//同步跳转进行查询
				//Success('支付成功！',U('User/Verify',['id'=>$id]));
				exit;
			}else{
				//Error('支付成功，更新系统订单状态失败！');
				
				exit;
			}
		}
	}
	
	
	private function overpay($orderno){
		$orderno = $this->frparam('orderno',1);
		$order = M('orders')->find(['orderno'=>$orderno]);
		if($orderno && $order){
			
			$this->order = $order;
			$this->display($this->template.'/paytpl/overpay');
		}else{
			exit(NEXTLANG('订单号错误！'));
		}
		
	}

	
}