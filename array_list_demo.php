<?php

require_once './src/mixed/ArrayList.php';


$data = [];

$length = 5;

for ($i = 1; $i <= $length; $i++) {
    $data[] = [
        'id' => $i,
        'gid' => rand(1, 5),
        'name' => 'name' . $i,
        'age' => rand(18, 30),
        'sex' => rand(0, 2),
        'head' => 'http://images.com/' . md5($i)
    ];
}

$class = new \Magein\plugins\mixed\ArrayList();

/**
 * 生成一个以id的值为键的数组
 */
$result = $class
    ->setData($data)
    ->setKey('id')
    ->getData();

echo '<hr/>';
echo '生成一个以id的值为键的数组:';
echo '<br/>';
var_dump($result);

/**
 * 生成一个已id的值为键的数组，并且收集 id,gid 的值组成一个新的数组
 */
$result = $class
    ->setData($data)
    ->setKey('id')
    ->setCollectFields(['id', 'gid'])
    ->getData();

echo '<hr/>';
echo '生成一个已id的值为键的数组，并且收集 id,gid 的值组成一个新的数组:';
echo '<br/>';
var_dump($result);

echo '<br/>';
echo '<br/>';
echo '收集到的id，gid的数组';
var_dump($class->getCollectFields());


/**
 * 生一个以id的值为键的数组，并且收集 id gid的值，同时只保留数组中的 id、name、head信息
 */
$result = $class
    ->setData($data)
    ->setKey('id')
    ->setCollectFields(['id', 'gid'])
    ->setKeepFields(['id', 'name', 'head'])
    ->getData();

echo '<hr/>';
echo '生一个以id的值为键的数组，并且收集 id gid的值，同时只保留数组中的 id、name、head信息:';
echo '<br/>';
var_dump($result);
echo '<br/>';
echo '<br/>';
echo '收集到的id，gid的数组';
var_dump($class->getCollectFields());