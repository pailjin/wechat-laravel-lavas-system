<?php

namespace App;

class myUtil
{
  function __construct(){
  }
  //随机生成长度为10位数字的字符串。
  public function getRandString(){
    $str="QWERTYUIOPASDFGHJKLZXCVBNM1234567890qwertyuiopasdfghjklzxcvbnm";
    str_shuffle($str);
    $result=substr(str_shuffle($str),26,10);
    return $result;
  }
}
