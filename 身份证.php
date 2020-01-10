<?php namespace check; // vim: se fdm=marker:

class 身份证{

  /**
   * @todo echo new 身份证； 生成随机身份证
   * @fixme 万一不小心传入empty等价变量，将导致误判
   */
  function __construct(string $id=''){

    if(!static::verify($id)) throw new \InvalidArgumentException('身份证号码不正确');

    $this->性别 = $id[-2]%2?'男':'女';

    $d = new \money\birthday(substr($id,6,8));

    $this->年龄 = $d->age();
    $this->生日 = $d->happy();
    $this->生肖 = $d->生肖();
    $this->星座 = $d->星座();

    $this->地址 = 行政区域代码::get(substr($id,0,6));

    $this->疑点[] = '出生时尚未启用该行政区号';
    $this->疑点[] = '百岁老人专用尾号999，但年龄不够，或行政区未听说推行该特殊政策';
    $this->疑点[] = '年龄太大(未必是假号）';
    $this->疑点[] = '出生序号太大，不符合当地人口密度';
    $this->疑点[] = '港澳台678开头的身份证';

  }

  final static function verify(string $id):bool{

    if(strlen(ltrim($id,'09'))!==18) return false;//行政区域代码不可能是前导零或九

    $id = strtr($id,'oOl|!zZbBg','0011122689');//TODO 一些ocr识别错误的号码要不要自动修复一下？？？

    $arr = str_split($id);
    $x = array_pop($arr);

    if(array_filter($arr,'is_numeric')!==$arr) return false;

    if(substr($id,6,8) > date('Ymd')) return false;

    if(!checkdate(substr($id,10,2),substr($id,12,2),substr($id,6,4))) return false;

    $tmp = 0;
    foreach($arr as $k=>$v)
      $tmp += $v * [7,9,10,5,8,4,2,1,6,3,7,9,10,5,8,4,2][$k];
    if(['1','0','X','9','8','7','6','5','4','3','2'][$tmp%11] !== strtoupper($x)) return false;

    //FIXME 好像不可能后两位零零，总之，不可能是一级二级，必须是完整地区
    //if(empty(行政区域::$代码[substr($id,0,6])) return false;

    return true;
  }

}
