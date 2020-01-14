<?php namespace check; // vim: se fdm=marker:

class 身份证{

  function __construct(string $id=''){

    if(!static::verify($id)) throw new \InvalidArgumentException('身份证号码不正确');

    $d = new \money\birthday(substr($id,6,8));
    $this->周岁 = $d->周岁();
    $this->生日 = $d->happy();
    $this->生肖 = $d->生肖();
    $this->星座 = $d->星座();

    $this->性别 = $id[-2]%2?'男':'女';

    //FIXME 凡是总有例外，尤其是2011年之前的历史数据
    $this->地址 = 行政区划::代码(substr($id,0,6), new \DateTime(substr($id,6,8)));
  }


  final static function verify(string $id):bool{

    if(strlen($id)!==18) return false;

    $arr = str_split($id);
    $x = array_pop($arr);

    if(array_filter($arr,'is_numeric')!==$arr) return false;

    $tmp = 0;
    foreach($arr as $k=>$v)
      $tmp += $v * [7,9,10,5,8,4,2,1,6,3,7,9,10,5,8,4,2][$k];
    if(['1','0','X','9','8','7','6','5','4','3','2'][$tmp%11] !== strtoupper($x)) return false;

    if(!checkdate(substr($id,10,2),substr($id,12,2),substr($id,6,4))) return false;

    if(substr($id,6,8) > date('Ymd')) return false;

    return true;
  }

}
