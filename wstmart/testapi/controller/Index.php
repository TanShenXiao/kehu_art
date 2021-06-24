<?php
namespace wstmart\testapi\controller;
use wstmart\common\model\Index as M;
use think\Controller;

use think\Db;

class Index extends Controller{
	public function index(){
		$params = input();
		$rtn = verifySinature($params);
		foreach($params as $k=>$v){
			$params[$k] = htmlspecialchars_decode($v,ENT_QUOTES);
		}
		$params['finalSign'] = $rtn['data'][0];
		if($rtn['status'] == -1)
			return jsonReturn($rtn['msg'],-1,$params);
		return jsonReturn($rtn['msg'],$rtn['status'],$params);

		$params = input();
		$rtn = verifySinature($params);
		if($rtn['status'] == -1)
			return jsonReturn($rtn['msg'],-1);
		if(!empty($params['data'])) {    //data参数经过了base64
			$params['data'] = str_replace(' ', '+', $params['data']);    //data经过了base64，有时post过来的base64字符串的加号会被替换成空格，这里把它恢复
			$json = base64_decode($params['data'], true);
			//var_dump($json);die;
			if(!$json)
				return jsonReturn('参数值不是有效的base64数据！',-1,[$params['data']]);
			$data = json_decode($json,true);
			//var_dump($data);die;
			if(empty($data))
				return jsonReturn('不是有效的json字符串',-1,[$json]);
		}
		if(empty($data['shopId']))
			return jsonReturn('参数不完整',-1);
		$m = model('common/Goods');
		$r = $m->add($data['shopId']);
		if($r['status'] == 1)
			echo jsonReturn('商品创建成功',1,$r['data']);
		else echo jsonReturn($r['msg'],$r['status']);

		$input = input();
		if(!empty($input['data']))
			$input['data'] = str_replace(' ', '+', $input['data']);	//有时post过来的base64字符串的加号会被替换成空格，这里把它恢复
		$json = base64_decode($input['data'],true);
		//var_dump($data);die;
		if(!$json)
			return jsonReturn('参数值不是有效的base64数据！',-1);
		$data = json_decode($json,true);
		//var_dump($data);die;
		if(empty($data))
			return jsonReturn('不是有效的json字符串',-1,$data);
		$params = $data;
		$ok = -1;
		$msg = '';

		$sign = empty($input['sign'])?'':$input['sign'];
		unset($input['sign']);
		ksort($input);
		$str = '';
		$i = 0;
//		if(!empty($params['goodsDesc'])) {
//			$params['goodsDesc'] = str_replace(' ', '+', $params['goodsDesc']);	//有时post过来的base64字符串的加号会被替换成空格，这里把它恢复
//		}
		foreach($input as $k=>$v){
			if($i==0)	$c = '';
			else		$c = '&';
			$str .= $c.$k.'='.$v;
			//$params[$k] = htmlspecialchars_decode($v);
			$i++;
		}
		$apiSecretKey = WSTConf('CONF.apiSecretKey');
		//var_dump($str.'&key='.$apiSecretKey);die;
		$sign1 = md5($str.'&key='.$apiSecretKey);
		//var_dump($sign1);var_dump(strtolower($sign));die;
		if($sign1 != strtolower($sign))
			$msg = '签名失败！';
		else {$msg = '签名成功！';$ok=1;}

//		if(!empty($params['goodsDesc'])){
//			$d = base64_decode($params['goodsDesc'],true);
//			if($d)
//				$params['goodsDesc_decode'] = $d;
//			else $msg = 'goodsDesc不是有效的base64字符串！';
//		}
		$params['finalSign'] = $sign1;
		//var_dump($params['goodsDesc_decode']);die;
		//$params['goodsDesc'] = htmlspecialchars_decode($params['goodsDesc']);

		return jsonReturn($msg,$ok,$params);
	}

	public function post(){
		$data['a'] = '荣耀9i+4GB+64GB 手机&<黑色>';
		$data['b'] = '<src>http://ww.dd?rr=**--++^&dd=u</src>';
		$data['c'] = 'fff';
		$r = curl_request("http://localhost/testapi",$data,'POST');
		echo $r;
	}


}
