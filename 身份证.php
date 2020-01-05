<?php namespace check; // vim: se fdm=marker:

class 身份证{

  //public $户籍;

  function __construct(string $id){

    $arr = str_split($id);
    $x = array_pop($arr);


    if(count($arr)!==17)
      throw new \InvalidArgumentException('18位');


    if(array_filter($arr,'is_numeric')!==$arr)
      throw new \InvalidArgumentException('非法字符');


    $d = new \money\birthday(substr($id,6,4),substr($id,10,2),substr($id,12,2));

    if($d>new \DateTime('today'))
      throw new \InvalidArgumentException('出生在未来');



    $a = [7,9,10,5,8,4,2,1,6,3,7,9,10,5,8,4,2];
    $b = ['1','0','X','9','8','7','6','5','4','3','2'];

    $tmp = 0;

    foreach($arr as $k=>$v)
      $tmp += $v * $a[$k];

    if($b[$tmp%11] !== strtoupper($x))
      throw new \InvalidArgumentException('校验码未通过检测');


    $this->年龄 = $d->age();
    $this->生日 = $d->happy();
    $this->生肖 = $d->生肖();
    $this->星座 = $d->星座();

    $this->性别 = $id[-2]%2?'男':'女';



  }

}
