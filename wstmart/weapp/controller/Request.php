<?php
namespace wstmart\weapp\controller;
/**
 * ============================================================================
 * WSTMart多用户商城
 * 版权所有 2016-2066 广州商淘信息科技有限公司，并保留所有权利。
 * 官网地址:http://www.wstmart.net
 * 交流社区:http://bbs.shangtao.net
 * 联系QQ:153289970
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！未经本公司授权您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * 请求控制器
 */
class Request extends Base{
	public function index(){
		$code = input('code');
		$appid = WSTConf('CONF.weAppId');
		$secret = WSTConf('CONF.weAppKey');
		$url = 'https://api.weixin.qq.com/sns/jscode2session?appid='.$appid.'&secret='.$secret.'&js_code='.$code.'&grant_type=authorization_code';
		$data = json_decode($this->http($url), true);
		if(isset($data['session_key'])){
			$session_key = base64_encode($data['session_key']);
			$openId = base64_encode($data['openid']);
			$sessionKey = base64_encode($openId.'_'.$session_key);	
			return jsonReturn('success',1,['sessionKey'=>$sessionKey,'openId'=>$data['openid']]);
		}else{
			return jsonReturn('',-1);
		}
	}
	public function bizdata(){
		$data = input('post.');
		$appid = WSTConf('CONF.weAppId');
		$sessionKey = $data['sessionKey'];
		$sessionKey = base64_decode($sessionKey);
		$sessionKey = explode('_',$sessionKey);
		$session_key = base64_decode($sessionKey[1]);
		$encryptedData = $data['encryptedData'];
		$iv = $data['iv'];
		$pc = new \wxbizdata\WXBizDataCrypt();
		$pc->WXBizDataCrypt($appid, $session_key);
		$errCode = $pc->decryptData($encryptedData, $iv, $data );
		if ($errCode == 0) {
			$unionKey = [];
			$data = json_decode($data,true);
			if(isset($data['unionId'])){
				$session_key = base64_encode($session_key);
				$unionId = base64_encode($data['unionId']);
				$unionKey = base64_encode($unionId.'_'.$session_key);
			}
			return jsonReturn('success',1,$unionKey);
		} else {
		    return jsonReturn('',-1,$errCode);
		}
	}
    /**
     * 解密getPhoneNumber
     */
    public function bizPhone(){
        $data = input('post.');
        $appid = WSTConf('CONF.weAppId');
        $sessionKey = $data['sessionKey'];
        if(empty($sessionKey)){
            return jsonReturn('获取微信绑定手机号必须先授权',-3);
        }
        $sessionKey = base64_decode($sessionKey);
        $sessionKey = explode('_',$sessionKey);
        $session_key = base64_decode($sessionKey[1]);
        $encryptedData = $data['encryptedData'];
        $iv = $data['iv'];
        $pc = new \wxbizdata\WXBizDataCrypt();
        $pc->WXBizDataCrypt($appid, $session_key);
        $errCode = $pc->decryptData($encryptedData, $iv, $datas );
        if ($errCode == 0) {
            $unionKey = [];
            $datas = json_decode($datas,true);
            $phone = $datas['purePhoneNumber'];
            if($phone){
                $rs = model('weapp/users')->wxBindPhone($phone);
                return $rs;
            }else{
                return jsonReturn('微信号没有绑定手机',-2);
            }
        } else {
            return jsonReturn('',-1,$errCode);
        }
    }
	/**
	 * http访问
	 * @param $url 访问网址
	 */
	public function http($url,$data = null){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_TIMEOUT, 500);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_URL, $url);
		if($data){
			curl_setopt($curl,CURLOPT_POST,1);
			curl_setopt($curl,CURLOPT_POSTFIELDS,$data);//如果要处理的数据，请在处理后再传进来 ，例如http_build_query这里不要加
		}
		$res = curl_exec($curl);
		if(!$res){
			$error = curl_errno($curl);
			echo $error;
		}
		curl_close($curl);
		return $res;
	}
}