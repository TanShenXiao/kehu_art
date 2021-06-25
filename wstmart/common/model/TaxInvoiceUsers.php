<?php
namespace wstmart\common\model;
use wstmart\supplier\model\SupplierConfigs;
use think\Db;
use think\facade\Cache;
class TaxInvoiceUsers extends Base{

    /**
     * 渠道登陆
     * 获取token
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Session\SessionManager|\Illuminate\Session\Store|mixed|string
     */
    public function getTaxToken(){
        $session_token = session('tax_message');
        $token='';
        if(empty($session_token) && $session_token['end_time']>=time()){

            $data = [
                'id'=>'kkk',
                'password'=>'kkk',
            ];

            $url = 'Ip:port/authorization';
            //把信息写入发票专门表，方便以后调用

            $res = $this->posturl($url,'',$data);
            if(isset($token['code'])){
                return $token;
            }else{
                $token = $token['token'];
                $token['end_time'] = time()+$token['expire'];
                $token['code']=200;
                session('tax_message',$token);
                return $token;
            }
        }
        return $session_token;

    }
    /**
     * 获取业务token（代开）
     * zrrjbrtoken
     */
    public function dkTaxBusinessToken($data){
        $token = $this->getTaxToken();

        if($token['code']!=200){
            return $token['message'];
        }else{
            $token = $token['token'];
        }
        $url = '/hlwptjj/zrrjbrtoken';

        $headerData = [
            'Authorization'=>'token',
        ];
        $data = [
            'zjhm'=>$data['cardNumber'],//开票人号码
            'jbrzjhm'=>$data['dkrsfz'],//代开人号码
        ];

        $json_data = json_encode($data);

        $res = $this->posturl($url,$json_data,$headerData);

            return $res;
    }
    /**
     * 获取业务token
     * @return mixed|string
     */
    public function taxBusinessToken($data){
        //hlwptjj/zrrtoken
        $token = $this->getTaxToken();

        if($token['code']!=200){
            return $token['message'];
        }else{
           $token = $token['token'];
        }
        $url = '/hlwptjj/zrrtoken';

        $headerData = [
        'Authorization'=>'token',
        ];
        $data = [
            'zjhm'=>$data['cardNumber'],
        ];

        $json_data = json_encode($data);

        $res = $this->posturl($url,$json_data,$headerData);
        return $res;

    }

    /**
     * 回传协议
     */
    public function returnTreaty($data){
//        hlwptjj/yhxy
        $url = '/hlwptjj/yhxy';
       /* $data = [
            'nsrsbh'=>'',
            'nsrmc'=>'',
            'yhxybh'=>'',
            'xylx'=>'',
            'yxqq'=>'',
            'yxqz'=>'',
            'xynr'=>'',
        ];*/
        $json_data = json_encode($data);

        $res = $this->posturl($url,$json_data);
        if($res['code']!='000'){

            return $res['message'];

        }else{

            return '成功';

        }
    }

    //提交税务注册账号信息
    //作废

    public function register($sId=0,$data=[]){
        //authorization
        //hlwptjj/dzswj-zrr
        $data = [
            'sfzhm'=>'身份证号码',
            'xm'=>'姓名',
            'mobile'=>'手机号码',
            'ssjAddress'=>'省市',
            'qxjAddress'=>'区县',
            'address'=>'详细地址',
            'gjdqdm'=>'156',
            'rxcjfs'=>'tx',
            'rxcjTx'=>[
                'bizToken'=>'腾讯慧眼唯一标识',
                'secretId'=>'慧眼ID',
                'secretKey'=>'慧眼key',
                'ruleId'=>'慧眼序号',
            ],
        ];
        $url = 'Ip:port/hlwptjj/dzswj-zrr';
        //把信息写入发票专门表，方便以后调用

       $json_data = json_encode($data);
       $res = $this->httpRequest($url,$json_data);
       if($res['code']!='000'){
        return $res['message'];
       }else{

           return '成功';
       }
    }

    public function posturl($url,$data,$header=[]){
        $headerArray =  [
            "Content-type:application/json;charset='utf-8'",
            "Accept:application/json"
        ];
        if(!empty($header)){
            $headerArray = array_merge($headerArray,$header);
        }
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,FALSE);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl,CURLOPT_HTTPHEADER,$headerArray);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return json_decode($output，true);
    }
    /**
    *AES加密
    **/
    public function encrypt($str,$screct_key){
    	//AES, 128 模式加密数据 CBC
    	$screct_key = base64_decode($screct_key);
    	$str = trim($str);
    	$str = addPKCS7Padding($str);
    	$iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128,MCRYPT_MODE_CBC),1);
    	$encrypt_str =  mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $screct_key, $str, MCRYPT_MODE_CBC);
    	return base64_encode($encrypt_str);
    }
    /**
     * 填充算法
     * @param string $source
     * @return string
     */
    function addPKCS7Padding($source){
    	$source = trim($source);
    	$block = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);

    	$pad = $block - (strlen($source) % $block);
    	if ($pad <= $block) {
    		$char = chr($pad);
    		$source .= str_repeat($char, $pad);
    	}
    	return $source;
    }
}