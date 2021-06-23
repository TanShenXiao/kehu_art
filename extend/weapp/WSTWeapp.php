<?php 
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
 *  小程序接口类
 */
namespace weapp;
use think\Db;

class WSTWeapp{
	public $appId;
	public $secret;
	private $tokenId;
	private $error;
	
	
	/**
	 * 初始微信配置信息
	 */
    public function __construct($appId, $secret) {
        $this->appId = $appId;
        $this->secret = $secret;
        $this->getToken();
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
	
	/**
	 * 获取访问令牌
	 */
	public function getToken(){
		$access_token = cache('we_access_token');
		if($access_token!=false) { //已缓存，直接使用
			$url = 'https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token='.$access_token;
			$data = $this->http($url);
			$data = json_decode($data, true);
			if(isset($data['errcode'])){
				cache('we_access_token',null);
				return $this->getToken();
			}else{
				$this->tokenId = $access_token;
				return $this->tokenId;
			}
		} else { //获取access_token
			$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->appId.'&secret='.$this->secret;
			$data = $this->http($url);
			$data = json_decode($data, true);
			if($data['access_token']!=''){
				cache('we_access_token',$data['access_token'],600);
				$this->tokenId = $data['access_token'];
				return $this->tokenId;
			}else{
				$this->error = $data;
			}
			return false;
		}
	}
	
}