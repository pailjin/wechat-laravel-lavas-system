<?php

namespace App;
use Config;
//微信支付类
class WeiXinPay
{
  //=======【基本信息设置】=====================================
  //微信公众号身份的唯一标识
  protected $APPID = Config::get('weixinpay.xcx.app_id');//填写您的appid。
  protected $APPSECRET = Config::get('weixinpay.xcx.app_secret');
  //受理商ID，身份标识
  protected $MCHID = Config::get('weixinpay.xcx.merchant_id');//商户id
  //商户支付密钥Key
  protected $KEY = Config::get('weixinpay.xcx.pay_key');
  //回调通知接口
  protected $APPURL =   Config::get('weixinpay.xcx.notify_url');
  //交易类型
  protected $TRADETYPE = 'JSAPI';
  //商品类型信息
  protected $BODY = 'wx/order';
  //微信支付类的构造函数
  function __construct($openid,$outTradeNo,$totalFee){
    $this->openid = $openid; //用户唯一标识
    $this->outTradeNo = $outTradeNo; //商品编号
    $this->totalFee = $totalFee; //总价
  }
  //微信支付类向外暴露的支付接口
  public function pay(){
    $result = $this->weixinapp();
    return $result;
  }
   //对微信统一下单接口返回的支付相关数据进行处理
   private function weixinapp(){
     $unifiedorder=$this->unifiedorder();
     if($unifiedorder==null || $unifiedorder['return_code'] == 'FAIL'){
       return null;
     }

     $parameters=array(
     'appId'=>$this->APPID,//小程序ID
     'timeStamp'=>''.time().'',//时间戳
     'nonceStr'=>$this->createNoncestr(),//随机串
     'package'=>'prepay_id='.$unifiedorder['prepay_id'],//数据包
     'signType'=>'MD5'//签名方式
       );
     $parameters['paySign']=$this->getSign($parameters);
     return $parameters;
   }
  /*
   *请求微信统一下单接口
   */
  private function unifiedorder(){
    $parameters = array(
      'appid' => $this->APPID,//小程序id
      'mch_id'=> $this->MCHID,//商户id
      'spbill_create_ip'=>$_SERVER['REMOTE_ADDR'],//终端ip
      'notify_url'=>$this->APPURL, //通知地址
      'nonce_str'=> $this->createNoncestr(),//随机字符串
      'out_trade_no'=>$this->outTradeNo,//商户订单编号
      'total_fee'=>floatval($this->totalFee), //总金额
      'open_id'=>$this->openid,//用户openid
      'trade_type'=>$this->TRADETYPE,//交易类型
      'body' =>$this->BODY, //商品信息
    );
    $parameters['sign'] = $this->getSign($parameters);
    $xmlData = $this->arrayToXml($parameters);
    $xml_result = $this->postXmlCurl($xmlData,'https://api.mch.weixin.qq.com/pay/unifiedorder',60);
    $result = $this->xmlToArray($xml_result);
    return $result;
  }
  //数组转字符串方法
  protected function arrayToXml($arr){
    $xml = "<xml>";
    foreach ($arr as $key=>$val)
    {
      if (is_numeric($val)){
        $xml.="<".$key.">".$val."</".$key.">";
      }else{
         $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
      }
    }
    $xml.="</xml>";
    return $xml;
  }
  protected function xmlToArray($xml){
    $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    return $array_data;
  }
  //发送xml请求方法
  private static function postXmlCurl($xml, $url, $second = 30)
  {
    $ch = curl_init();
    //设置超时
    curl_setopt($ch, CURLOPT_TIMEOUT, $second);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); //严格校验
    //设置header
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    //要求结果为字符串且输出到屏幕上
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    //post提交方式
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
    curl_setopt($ch, CURLOPT_TIMEOUT, 40);
    set_time_limit(0);
    //运行curl
    $data = curl_exec($ch);
    //返回结果
    if ($data) {
      curl_close($ch);
      return $data;
    } else {
      $error = curl_errno($ch);
      curl_close($ch);
      return null;
    }
  }
  /*
   * 对要发送到微信统一下单接口的数据进行签名
   */
  protected function getSign($Obj){
     foreach ($Obj as $k => $v){
     $Parameters[$k] = $v;
     }
     //签名步骤一：按字典序排序参数
     ksort($Parameters);
     $String = $this->formatBizQueryParaMap($Parameters, false);
     //签名步骤二：在string后加入KEY
     $String = $String."&key=".$this->KEY;
     //签名步骤三：MD5加密
     $String = md5($String);
     //签名步骤四：所有字符转为大写
     $result_ = strtoupper($String);
     return $result_;
   }
  /*
   *排序并格式化参数方法，签名时需要使用
   */
  protected function formatBizQueryParaMap($paraMap, $urlencode)
  {
    $buff = "";
    ksort($paraMap);
    foreach ($paraMap as $k => $v)
    {
      if($urlencode)
      {
        $v = urlencode($v);
      }
      //$buff .= strtolower($k) . "=" . $v . "&";
      $buff .= $k . "=" . $v . "&";
    }
    $reqPar;
    if (strlen($buff) > 0)
    {
      $reqPar = substr($buff, 0, strlen($buff)-1);
    }
    return $reqPar;
  }
  /*
   * 生成随机字符串方法
   */
  protected function createNoncestr($length = 32 ){
     $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
     $str ="";
     for ( $i = 0; $i < $length; $i++ ) {
     $str.= substr($chars, mt_rand(0, strlen($chars)-1), 1);
     }
     return $str;
     }
}
