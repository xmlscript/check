<?php namespace check; // vim: se fdm=marker:

/**
 * 免数据库即可查询区域代码
 * 一定要配套采集！！！直接运行本文件即可生成/更新对应类常量
 * @todo 区号变更不可能重复，但名称很可能与旧称一字之差，但仍沿用旧称，或并入括弧
 * @todo 输出前端需要的json树结构，以便客户端缓存。配合etag，分段输出下辖代码
 * @todo 前端很可能根据ip分析完整默认所在地
 */
class 行政区划 extends update{

  //TODO 继承\自更新类，预设常量，然后手动命令行静态化，类型必须符合指明的常量名和类型
  //TODO php配合参数先include该文件，然后
  const latest = '2019-11';
  const A = [];
  const B = [];
  const C = [];


  /**
   * @return 传入日期，则在include数组中查找C类地址，成功str，失败null
   * @return 没传日期，查A类地址返回B/C类数组，查B类返回C类数组，查C类返回str；失败一律null
   */
  final static function 代码(int $q, \DateTime $birthday=null){

    if(strlen($q)!==6) return;

    $aa0000 = substr($q,0,2)*10000;
    $aabb00 = substr($q,0,4)*100;

    if($birthday){

      if($q===$aa0000 || $q===$aabb00 || $birthday >= new \DateTime(static::latest))
        return;

      $file = max(new \DateTime('1985-01'),$birthday);
      if(!($arr = include $file->format('Y').'.php'))
      $arr = include $file->format('Ym').'.php';

      if(isset($arr[$q]))
      return join(array_unique([$arr[$aa0000],$arr[$aabb00],$arr[$q]]));
      return;
    }


    switch($q){
    case $aa0000:
      return static::B[$aa0000];
    case $aabb00:
      return static::C[$aabb00];
    default:
      if(isset(static::C[$aabb00][$q]))
        return static::A[$aa0000].static::B[$aa0000][$aabb00].static::C[$aabb00][$q];
    }

  }


  final static function ip(string $ip){

  }

}

/**
 * 要求必须文件名的必须符合DateTime要求的格式，1980不行，必须1980-1
 */
if(PHP_SAPI==='cli' && $_SERVER['argv'][0]===basename(__FILE__)){//{{{

  $a = $b = $c = [];

  ob_start();
  $arr = @include $_SERVER['argv'][1];
  ob_clean();
  is_array($arr) or die('Failed to open file.'.PHP_EOL);

  foreach($arr as $k=>$v){

    if($k===substr($k,0,2)*10000)
      $a[$k] = $v;

    $aa0000 = substr($k,0,2)*10000;
    $aabb00 = substr($k,0,4)*100;

    if($aa0000!==$k)//排除北京/天津/重庆
      if($aabb00===$k)//排除二级结构的北京市东城区
        $b[$aa0000][$k]= $v;

    if($aabb00!==$k)//排除混杂在三级区当中出现的二级市自身
      $c[isset($arr[$aabb00])?$aabb00:$aa0000][$k]= $v; //北京重庆有虚拟二级，由一级替换

  }

  //die(var_dump(token_get_all(file_get_contents(__FILE__))));

  //TODO token_get_all检测对应常量名和类型，由自更新类方法final实现
  $a&&$b&&$c or die('Failed to parse file.');

  //TODO token_get_all分析，替换或插入，并报告结果

    die(str_replace([
      "\n  array (",//先替换特征明显的二级数组
      'array (',//最后替换外围数组，顺便缩进
      ');',
      "),",
      ' => ',
    ],[
      '[',
      '[//{{{',
        '  ];//}}}',
  '],',
    '=>',
    ],
    '  // '.date(DATE_W3C).' '.hash_file('md5',$_SERVER['argv'][1]).PHP_EOL.PHP_EOL.
    '  const A = '.var_export($a,true).';'.PHP_EOL.PHP_EOL.
    '  private const B = '.var_export($b,true).';'.PHP_EOL.PHP_EOL.
    '  private const C = '.var_export($c,true).';'.PHP_EOL
    ));

}//}}}

