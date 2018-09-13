### 插件类
    
 1. http
     
 	* Request 接受http参数
  
 2.   mixed 混合类

  	* ArrayList 二维数组处理类

  	* BinaryScene 二进制场景（如记录多选并且作为筛选）
             
  	* File 文件搜索匹配，复制，移动
  	           
  	* Pinyin 汉字转化为拼音（常用汉字，精切匹配可以使用overtrue/pinyin）
             
  	* Variable 驼峰、下划线、帕斯卡命名相互转化

#### Request

	 Request::instance()->param($name,$default,$filter); 
	
	 Request::instance()->post($name,$default,$filter); 
	      
	 Request::instance()->get($name,$default,$filter); 
     
     第一个参数：要接收的值，不传递返回所有，没有设置返回null或者默认值
     
     第二个参数：默认值，
     
     第三个参数：过滤，使用filter_var()过滤，所以参数需要是filter常量
     只过滤不处理，如果不满足则返回false，
     
     // file
     Request::instance()->file($name); 
     
     // php://input
     Request::instance()->input($name); 
     
     // xml,参数传递true为转化为数组，默认true
     Request::instance()->xml(false); 
     
#### File
    
    // 字符串是否满足通配符,$express为常用的文件搜索通配符
    File::instance()->match(string $string, string $express);
    
    // 搜索目录下的文件(递归搜索)，支持通配符
    File::instance()->each(string $dir, string $express);
    
    // 复制文件
    File::instance()->copy();
    
#### Variable

    下划线、驼峰、帕斯卡名称规则相互转化
     
    //转化成驼峰
    Variable::instance()->transToCamelCase();
     
    // 转化成帕斯卡
    Variable::instance()->transToPascal();
     
    // 转化成下划线
    Variable::instance()->transToUnderline();
    
#### Pinyin
    
    汉字转化为拼音，只能转常用的，使用的编码是GBK(汉字占用两个字节)，区位码的方式进行转化
    
    转化精确的可以参考、使用 git仓库中 overtrue/pinyin的插件
    
#### BinaryScene

    二进制场景，用于权限或者多选等业务场景，场景值需要遵循 1 2 4 8 16 32等规则
    
#### ArrayList

    请参考：array_list_demo.php