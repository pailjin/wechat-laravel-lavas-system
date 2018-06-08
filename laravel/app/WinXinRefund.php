<?php

namespace App;
use Config;

use App\WeiXinPay as WeiXinPay;

class WinXinRefund extends WeiXinPay
{
  protected $SSLCERT_PATH = 'wxpaycert/apiclient_cert.pem';//证书路径
  protected $SSLKEY_PATH = 'wxpaycert/apiclient_key.pem';//证书路径
  protected $opUserId = Config::get('weixinpay.xcx.merchant_id');//商户号
  function __construct($openid,$outTradeNo,$totalFee,$outRefundNo,$refundFee){
    //初始化退款类需要的变量
    $this->openid = $openid;
    $this->outTradeNo = $outTradeNo;
    $this->totalFee = $totalFee;
    $this->outRefundNo = $outRefundNo;
    $this->refundFee = $refundFee;
  }
  public function refund(){
    //对外暴露的退款接口
    $result = $this->wxrefundapi();
    return $result;
  }
  private function wxrefundapi(){
    //通过微信api进行退款流程

    $parma = array(
      'appid'=> $this->APPID,
      'mch_id'=> $this->MCHID,
      'nonce_str'=> $this->createNoncestr(),
      'out_refund_no'=> $this->outRefundNo,
      'out_trade_no'=> $this->outTradeNo,
      'total_fee'=> $this->totalFee,
      'refund_fee'=> $this->refundFee,
      'op_user_id' => $this->opUserId,
    );
    $parma['sign'] = $this->getSign($parma);

    $xmldata = $this->arrayToXml($parma);
    $xmlresult = $this->postXmlSSLCurl($xmldata,'https://api.mch.weixin.qq.com/secapi/pay/refund');

    $result = $this->xmlToArray($xmlresult);
    return $result;
  }
  //需要使用证书的请求
  function postXmlSSLCurl($xml,$url,$second=30)
  {
    $ch = curl_init();
    //超时时间
    curl_setopt($ch,CURLOPT_TIMEOUT,$second);
    //这里设置代理，如果有的话
    //curl_setopt($ch,CURLOPT_PROXY, '8.8.8.8');
    //curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
    curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
    //设置header
    curl_setopt($ch,CURLOPT_HEADER,FALSE);
    //要求结果为字符串且输出到屏幕上
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
    //设置证书
    //使用证书：cert 与 key 分别属于两个.pem文件
    //默认格式为PEM，可以注释
    curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
    curl_setopt($ch,CURLOPT_SSLCERT, $this->SSLCERT_PATH);
    //默认格式为PEM，可以注释
    curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
    curl_setopt($ch,CURLOPT_SSLKEY, $this->SSLKEY_PATH);
    //post提交方式
    curl_setopt($ch,CURLOPT_POST, true);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$xml);
    $data = curl_exec($ch);
    //返回结果
    if($data){
      curl_close($ch);
      return $data;
    }
    else {
      $error = curl_errno($ch);
      echo "curl出错，错误码:$error"."<br>";
      curl_close($ch);
      return false;
    }
  }

}
