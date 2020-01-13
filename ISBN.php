<?php namespace check; // vim: se fdm=marker:

class ISBN{

  //./978/7/101/05520-7.json 中华书局
  //./978/7/111/40192-6.json 机械工业出版社
  //./978/7/115/28148-7.json 人民邮电出版社
  //./978/7/121/10956-0.json 电子工业出版社
  //./978/7/302/45673-5.json 清华大学出版社
  //./978/7/313/06863-7.json 上海交通大学出版社

  //./978/7/5083/0730-5.json 中国电力出版社
  //./978/7/5124/0143-3.json 北京航空航天大学出版社
  //./978/7/5218/0383-9.json 经济科学出版社
  //./978/7/5609/2782-4.json 华中科技大学出版社
  //./978/7/5641/3372-6.json 东南大学出版社
  //./978/7/5714/0160-3.json 北京科学技术出版社

  //./978/7/80759/129-0.php 万卷出版公司

  const A = [//{{{
    0 => ['美国','英国和爱尔兰','澳大利亚'],
    2 => ['法国','瑞士'],
    3 => ['瑞士','奥地利','德国'],
    4 => ['日本'],
    5 => ['前苏联'],
    7 => ['中国大陆'],
    600 => ['伊朗'],
    601 => ['哈萨克斯坦'],
    602 => ['沙特阿拉伯'],
    604 => ['越南'],
    605 => ['土耳其'],
    606 => ['罗马尼亚'],
    607 => ['墨西哥'],
    608 => ['马其顿'],
    609 => ['立陶宛'],
    80 => ['捷克','斯洛伐克'],
    81 => ['印度'],
    82 => ['挪威'],
    90 => ['荷兰'],
    950 => ['阿根廷'],
    9297 => ['卡塔尔'],
    99901 => ['巴林'],
    99969 => ['阿曼'],
  ];//}}}
  
  final static function GET(int $code){
    //TODO 首先分隔978/7/101/xxx
    //TODO 如果没有找到，立即抓取豆瓣，解析并file_put_contents
    //FIXME 应该委托客户端抓取，并PUT上来
    return;
  }

  /**
   * @todo 委托客户端代理请求，PUT结果并解析
   */
  final static function parse(string $html){

  }

  final static function path(int $code){
    return './978/7/111/40192-6.json';
  }

  final static function PUT(int $isbn, array $arr):bool{
    return file_put_contents(static::path($isbn), json_encode($arr,JSON_NUMERIC_CHECK|JSON_PRETTY_PRINT| JSON_UNESCAPED_UNICODE),LOCK_EX);
  }

  final static function valid(string $isbn):bool{

  }
}
