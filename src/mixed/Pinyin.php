<?php

namespace Magein\plugins\mixed;


/**
 * Class Pinyin
 * @package Tools
 */
class Pinyin
{
    /**
     * @var string
     */
    public $errorReplace = '';

    /**
     * @var string
     */
    public $charset = '';

    /**
     * @var bool
     */
    public $head = false;

    /**
     * @var string
     */
    public $delimiter = '';

    /**
     * @param $number
     * @return int|string
     */
    private function getKey($number)
    {
        static $data;

        if (empty($data)) {
            $data = $this->resource();
        }

        if ($number > 0 && $number < 160) {
            return chr($number);
        } elseif ($number < -20319 || $number > -10247) {
            return '';
        } else {
            foreach ($data as $k => $v) {
                if ($v <= $number) {
                    return $k;
                }
            }
        }
        return '';
    }

    /**
     * 字符串转化为拼音
     * @param string $string
     * @return mixed
     */
    public function trans($string)
    {
        /**
         * GBK 编码下汉字占用两个字节  UTF-8占用三个字节
         * GBK 编码下每个字节的ascii码为 161-254 (16 进制A1 - FE)，
         * 第一个字节 对应于 区码的1-94 区，第二个字节 对应于位码的1-94 位
         *
         * 获取汉字的区码:ord($string[0])-160
         * 获取汉字的位码:ord($string[1])-160
         * 汉字区位码对应表参考：http://www.fzrtvu.net/htm/qwm.htm#CHU
         *
         * 参考：http://blog.csdn.net/xcysuccess3/article/details/9145011
         */

        // 获取字符串编码
        if (!$this->charset) {
            $this->charset = mb_detect_encoding($string);
        }

        // 统一处理成GBK（向下兼容GB2312）格式
        if ($this->charset != 'GBK') {
            $string = iconv($this->charset, 'GBK//IGNORE', $string);
        }

        $result = '';
        $len = strlen($string);

        for ($i = 0; $i < $len; $i++) {

            // 第一个字节的ascii码（区码）
            $firstAscii = ord($string[$i]);

            if ($firstAscii > 160) {

                // 第二个字节的ascii码(位码)
                $secondAscii = ord($string[++$i]);

                $value = ($firstAscii << 8) + $secondAscii - 65536;

            } else {
                $value = $firstAscii;
            }

            $key = $this->getKey($value);

            if (empty($key)) {
                $key = $this->errorReplace;
            }

            if ($this->head) {
                $key = $key[0];
            }

            $result .= $this->delimiter . $key;
        }

        return trim($result);
    }

    /**
     * 打印拼音key
     * @return bool
     */
    public function printResource()
    {
        $dataKey = 'a|ai|an|ang|ao|ba|bai|ban|bang|bao|bei|ben|beng|bi|bian|biao|bie|bin|bing|bo|bu|ca|cai|can|cang|cao|ce|ceng|cha' .
            '|chai|chan|chang|chao|che|chen|cheng|chi|chong|chou|chu|chuai|chuan|chuang|chui|chun|chuo|ci|cong|cou|cu|' .
            'cuan|cui|cun|cuo|da|dai|dan|dang|dao|de|deng|di|dian|diao|die|ding|diu|dong|dou|du|duan|dui|dun|duo|e|en|er' .
            '|fa|fan|fang|fei|fen|feng|fo|fou|fu|ga|gai|gan|gang|gao|ge|gei|gen|geng|gong|gou|gu|gua|guai|guan|guang|gui' .
            '|gun|guo|ha|hai|han|hang|hao|he|hei|hen|heng|hong|hou|hu|hua|huai|huan|huang|hui|hun|huo|ji|jia|jian|jiang' .
            '|jiao|jie|jin|jing|jiong|jiu|ju|juan|jue|jun|ka|kai|kan|kang|kao|ke|ken|keng|kong|kou|ku|kua|kuai|kuan|kuang' .
            '|kui|kun|kuo|la|lai|lan|lang|lao|le|lei|leng|li|lia|lian|liang|liao|lie|lin|ling|liu|long|lou|lu|lv|luan|lue' .
            '|lun|luo|ma|mai|man|mang|mao|me|mei|men|meng|mi|mian|miao|mie|min|ming|miu|mo|mou|mu|na|nai|nan|nang|nao|ne' .
            '|nei|nen|neng|ni|nian|niang|niao|nie|nin|ning|niu|nong|nu|nv|nuan|nue|nuo|o|ou|pa|pai|pan|pang|pao|pei|pen' .
            '|peng|pi|pian|piao|pie|pin|ping|po|pu|qi|qia|qian|qiang|qiao|qie|qin|qing|qiong|qiu|qu|quan|que|qun|ran|rang' .
            '|rao|re|ren|reng|ri|rong|rou|ru|ruan|rui|run|ruo|sa|sai|san|sang|sao|se|sen|seng|sha|shai|shan|shang|shao|' .
            'she|shen|sheng|shi|shou|shu|shua|shuai|shuan|shuang|shui|shun|shuo|si|song|sou|su|suan|sui|sun|suo|ta|tai|' .
            'tan|tang|tao|te|teng|ti|tian|tiao|tie|ting|tong|tou|tu|tuan|tui|tun|tuo|wa|wai|wan|wang|wei|wen|weng|wo|wu' .
            '|xi|xia|xian|xiang|xiao|xie|xin|xing|xiong|xiu|xu|xuan|xue|xun|ya|yan|yang|yao|ye|yi|yin|ying|yo|yong|you' .
            '|yu|yuan|yue|yun|za|zai|zan|zang|zao|ze|zei|zen|zeng|zha|zhai|zhan|zhang|zhao|zhe|zhen|zheng|zhi|zhong|' .
            'zhou|zhu|zhua|zhuai|zhuan|zhuang|zhui|zhun|zhuo|zi|zong|zou|zu|zuan|zui|zun|zuo';

        $dataValue = '-20319|-20317|-20304|-20295|-20292|-20283|-20265|-20257|-20242|-20230|-20051|-20036|-20032|-20026|-20002|-19990' .
            '|-19986|-19982|-19976|-19805|-19784|-19775|-19774|-19763|-19756|-19751|-19746|-19741|-19739|-19728|-19725' .
            '|-19715|-19540|-19531|-19525|-19515|-19500|-19484|-19479|-19467|-19289|-19288|-19281|-19275|-19270|-19263' .
            '|-19261|-19249|-19243|-19242|-19238|-19235|-19227|-19224|-19218|-19212|-19038|-19023|-19018|-19006|-19003' .
            '|-18996|-18977|-18961|-18952|-18783|-18774|-18773|-18763|-18756|-18741|-18735|-18731|-18722|-18710|-18697' .
            '|-18696|-18526|-18518|-18501|-18490|-18478|-18463|-18448|-18447|-18446|-18239|-18237|-18231|-18220|-18211' .
            '|-18201|-18184|-18183|-18181|-18012|-17997|-17988|-17970|-17964|-17961|-17950|-17947|-17931|-17928|-17922' .
            '|-17759|-17752|-17733|-17730|-17721|-17703|-17701|-17697|-17692|-17683|-17676|-17496|-17487|-17482|-17468' .
            '|-17454|-17433|-17427|-17417|-17202|-17185|-16983|-16970|-16942|-16915|-16733|-16708|-16706|-16689|-16664' .
            '|-16657|-16647|-16474|-16470|-16465|-16459|-16452|-16448|-16433|-16429|-16427|-16423|-16419|-16412|-16407' .
            '|-16403|-16401|-16393|-16220|-16216|-16212|-16205|-16202|-16187|-16180|-16171|-16169|-16158|-16155|-15959' .
            '|-15958|-15944|-15933|-15920|-15915|-15903|-15889|-15878|-15707|-15701|-15681|-15667|-15661|-15659|-15652' .
            '|-15640|-15631|-15625|-15454|-15448|-15436|-15435|-15419|-15416|-15408|-15394|-15385|-15377|-15375|-15369' .
            '|-15363|-15362|-15183|-15180|-15165|-15158|-15153|-15150|-15149|-15144|-15143|-15141|-15140|-15139|-15128' .
            '|-15121|-15119|-15117|-15110|-15109|-14941|-14937|-14933|-14930|-14929|-14928|-14926|-14922|-14921|-14914' .
            '|-14908|-14902|-14894|-14889|-14882|-14873|-14871|-14857|-14678|-14674|-14670|-14668|-14663|-14654|-14645' .
            '|-14630|-14594|-14429|-14407|-14399|-14384|-14379|-14368|-14355|-14353|-14345|-14170|-14159|-14151|-14149' .
            '|-14145|-14140|-14137|-14135|-14125|-14123|-14122|-14112|-14109|-14099|-14097|-14094|-14092|-14090|-14087' .
            '|-14083|-13917|-13914|-13910|-13907|-13906|-13905|-13896|-13894|-13878|-13870|-13859|-13847|-13831|-13658' .
            '|-13611|-13601|-13406|-13404|-13400|-13398|-13395|-13391|-13387|-13383|-13367|-13359|-13356|-13343|-13340' .
            '|-13329|-13326|-13318|-13147|-13138|-13120|-13107|-13096|-13095|-13091|-13076|-13068|-13063|-13060|-12888' .
            '|-12875|-12871|-12860|-12858|-12852|-12849|-12838|-12831|-12829|-12812|-12802|-12607|-12597|-12594|-12585' .
            '|-12556|-12359|-12346|-12320|-12300|-12120|-12099|-12089|-12074|-12067|-12058|-12039|-11867|-11861|-11847' .
            '|-11831|-11798|-11781|-11604|-11589|-11536|-11358|-11340|-11339|-11324|-11303|-11097|-11077|-11067|-11055' .
            '|-11052|-11045|-11041|-11038|-11024|-11020|-11019|-11018|-11014|-10838|-10832|-10815|-10800|-10790|-10780' .
            '|-10764|-10587|-10544|-10533|-10519|-10331|-10329|-10328|-10322|-10315|-10309|-10307|-10296|-10281|-10274' .
            '|-10270|-10262|-10260|-10256|-10254';

        $dataKey = explode('|', $dataKey);

        $dataValue = explode('|', $dataValue);

        $data = array_combine($dataKey, $dataValue);
        arsort($data);
        reset($data);

        $records = [];
        foreach ($data as $key => $item) {

            $k = $key[0];

            $value = '\'' . $key . '\'=>' . $item . ',';

            $records[$k][] = $value;
        }

        if ($records) {
            foreach ($records as $item) {
                if ($item) {
                    foreach ($item as $key => $val) {
                        echo $val;
                    }
                }
                echo '<br/>';
            }
        }

        return true;
    }

    /**
     * 拼音对应的值
     * @return array
     */
    private function resource()
    {
        return [
            'zuo' => -10254, 'zun' => -10256, 'zui' => -10260, 'zuan' => -10262, 'zu' => -10270, 'zou' => -10274, 'zong' => -10281, 'zi' => -10296, 'zhuo' => -10307, 'zhun' => -10309, 'zhui' => -10315, 'zhuang' => -10322, 'zhuan' => -10328, 'zhuai' => -10329, 'zhua' => -10331, 'zhu' => -10519, 'zhou' => -10533, 'zhong' => -10544, 'zhi' => -10587, 'zheng' => -10764, 'zhen' => -10780, 'zhe' => -10790, 'zhao' => -10800, 'zhang' => -10815, 'zhan' => -10832, 'zhai' => -10838, 'zha' => -11014, 'zeng' => -11018, 'zen' => -11019, 'zei' => -11020, 'ze' => -11024, 'zao' => -11038, 'zang' => -11041, 'zan' => -11045, 'zai' => -11052, 'za' => -11055,
            'yun' => -11067, 'yue' => -11077, 'yuan' => -11097, 'yu' => -11303, 'you' => -11324, 'yong' => -11339, 'yo' => -11340, 'ying' => -11358, 'yin' => -11536, 'yi' => -11589, 'ye' => -11604, 'yao' => -11781, 'yang' => -11798, 'yan' => -11831, 'ya' => -11847,
            'xun' => -11861, 'xue' => -11867, 'xuan' => -12039, 'xu' => -12058, 'xiu' => -12067, 'xiong' => -12074, 'xing' => -12089, 'xin' => -12099, 'xie' => -12120, 'xiao' => -12300, 'xiang' => -12320, 'xian' => -12346, 'xia' => -12359, 'xi' => -12556,
            'wu' => -12585, 'wo' => -12594, 'weng' => -12597, 'wen' => -12607, 'wei' => -12802, 'wang' => -12812, 'wan' => -12829, 'wai' => -12831, 'wa' => -12838,
            'tuo' => -12849, 'tun' => -12852, 'tui' => -12858, 'tuan' => -12860, 'tu' => -12871, 'tou' => -12875, 'tong' => -12888, 'ting' => -13060, 'tie' => -13063, 'tiao' => -13068, 'tian' => -13076, 'ti' => -13091, 'teng' => -13095, 'te' => -13096, 'tao' => -13107, 'tang' => -13120, 'tan' => -13138, 'tai' => -13147, 'ta' => -13318,
            'suo' => -13326, 'sun' => -13329, 'sui' => -13340, 'suan' => -13343, 'su' => -13356, 'sou' => -13359, 'song' => -13367, 'si' => -13383, 'shuo' => -13387, 'shun' => -13391, 'shui' => -13395, 'shuang' => -13398, 'shuan' => -13400, 'shuai' => -13404, 'shua' => -13406, 'shu' => -13601, 'shou' => -13611, 'shi' => -13658, 'sheng' => -13831, 'shen' => -13847, 'she' => -13859, 'shao' => -13870, 'shang' => -13878, 'shan' => -13894, 'shai' => -13896, 'sha' => -13905, 'seng' => -13906, 'sen' => -13907, 'se' => -13910, 'sao' => -13914, 'sang' => -13917, 'san' => -14083, 'sai' => -14087, 'sa' => -14090,
            'ruo' => -14092, 'run' => -14094, 'rui' => -14097, 'ruan' => -14099, 'ru' => -14109, 'rou' => -14112, 'rong' => -14122, 'ri' => -14123, 'reng' => -14125, 'ren' => -14135, 're' => -14137, 'rao' => -14140, 'rang' => -14145, 'ran' => -14149,
            'qun' => -14151, 'que' => -14159, 'quan' => -14170, 'qu' => -14345, 'qiu' => -14353, 'qiong' => -14355, 'qing' => -14368, 'qin' => -14379, 'qie' => -14384, 'qiao' => -14399, 'qiang' => -14407, 'qian' => -14429, 'qia' => -14594, 'qi' => -14630,
            'pu' => -14645, 'po' => -14654, 'ping' => -14663, 'pin' => -14668, 'pie' => -14670, 'piao' => -14674, 'pian' => -14678, 'pi' => -14857, 'peng' => -14871, 'pen' => -14873, 'pei' => -14882, 'pao' => -14889, 'pang' => -14894, 'pan' => -14902, 'pai' => -14908, 'pa' => -14914,
            'ou' => -14921, 'o' => -14922,
            'nuo' => -14926, 'nue' => -14928, 'nuan' => -14929, 'nv' => -14930, 'nu' => -14933, 'nong' => -14937, 'niu' => -14941, 'ning' => -15109, 'nin' => -15110, 'nie' => -15117, 'niao' => -15119, 'niang' => -15121, 'nian' => -15128, 'ni' => -15139, 'neng' => -15140, 'nen' => -15141, 'nei' => -15143, 'ne' => -15144, 'nao' => -15149, 'nang' => -15150, 'nan' => -15153, 'nai' => -15158, 'na' => -15165,
            'mu' => -15180, 'mou' => -15183, 'mo' => -15362, 'miu' => -15363, 'ming' => -15369, 'min' => -15375, 'mie' => -15377, 'miao' => -15385, 'mian' => -15394, 'mi' => -15408, 'meng' => -15416, 'men' => -15419, 'mei' => -15435, 'me' => -15436, 'mao' => -15448, 'mang' => -15454, 'man' => -15625, 'mai' => -15631, 'ma' => -15640,
            'luo' => -15652, 'lun' => -15659, 'lue' => -15661, 'luan' => -15667, 'lv' => -15681, 'lu' => -15701, 'lou' => -15707, 'long' => -15878, 'liu' => -15889, 'ling' => -15903, 'lin' => -15915, 'lie' => -15920, 'liao' => -15933, 'liang' => -15944, 'lian' => -15958, 'lia' => -15959, 'li' => -16155, 'leng' => -16158, 'lei' => -16169, 'le' => -16171, 'lao' => -16180, 'lang' => -16187, 'lan' => -16202, 'lai' => -16205, 'la' => -16212,
            'kuo' => -16216, 'kun' => -16220, 'kui' => -16393, 'kuang' => -16401, 'kuan' => -16403, 'kuai' => -16407, 'kua' => -16412, 'ku' => -16419, 'kou' => -16423, 'kong' => -16427, 'keng' => -16429, 'ken' => -16433, 'ke' => -16448, 'kao' => -16452, 'kang' => -16459, 'kan' => -16465, 'kai' => -16470, 'ka' => -16474,
            'jun' => -16647, 'jue' => -16657, 'juan' => -16664, 'ju' => -16689, 'jiu' => -16706, 'jiong' => -16708, 'jing' => -16733, 'jin' => -16915, 'jie' => -16942, 'jiao' => -16970, 'jiang' => -16983, 'jian' => -17185, 'jia' => -17202, 'ji' => -17417,
            'huo' => -17427, 'hun' => -17433, 'hui' => -17454, 'huang' => -17468, 'huan' => -17482, 'huai' => -17487, 'hua' => -17496, 'hu' => -17676, 'hou' => -17683, 'hong' => -17692, 'heng' => -17697, 'hen' => -17701, 'hei' => -17703, 'he' => -17721, 'hao' => -17730, 'hang' => -17733, 'han' => -17752, 'hai' => -17759, 'ha' => -17922,
            'guo' => -17928, 'gun' => -17931, 'gui' => -17947, 'guang' => -17950, 'guan' => -17961, 'guai' => -17964, 'gua' => -17970, 'gu' => -17988, 'gou' => -17997, 'gong' => -18012, 'geng' => -18181, 'gen' => -18183, 'gei' => -18184, 'ge' => -18201, 'gao' => -18211, 'gang' => -18220, 'gan' => -18231, 'gai' => -18237, 'ga' => -18239,
            'fu' => -18446, 'fou' => -18447, 'fo' => -18448, 'feng' => -18463, 'fen' => -18478, 'fei' => -18490, 'fang' => -18501, 'fan' => -18518, 'fa' => -18526,
            'er' => -18696, 'en' => -18697, 'e' => -18710,
            'duo' => -18722, 'dun' => -18731, 'dui' => -18735, 'duan' => -18741, 'du' => -18756, 'dou' => -18763, 'dong' => -18773, 'diu' => -18774, 'ding' => -18783, 'die' => -18952, 'diao' => -18961, 'dian' => -18977, 'di' => -18996, 'deng' => -19003, 'de' => -19006, 'dao' => -19018, 'dang' => -19023, 'dan' => -19038, 'dai' => -19212, 'da' => -19218,
            'cuo' => -19224, 'cun' => -19227, 'cui' => -19235, 'cuan' => -19238, 'cu' => -19242, 'cou' => -19243, 'cong' => -19249, 'ci' => -19261, 'chuo' => -19263, 'chun' => -19270, 'chui' => -19275, 'chuang' => -19281, 'chuan' => -19288, 'chuai' => -19289, 'chu' => -19467, 'chou' => -19479, 'chong' => -19484, 'chi' => -19500, 'cheng' => -19515, 'chen' => -19525, 'che' => -19531, 'chao' => -19540, 'chang' => -19715, 'chan' => -19725, 'chai' => -19728, 'cha' => -19739, 'ceng' => -19741, 'ce' => -19746, 'cao' => -19751, 'cang' => -19756, 'can' => -19763, 'cai' => -19774, 'ca' => -19775,
            'bu' => -19784, 'bo' => -19805, 'bing' => -19976, 'bin' => -19982, 'bie' => -19986, 'biao' => -19990, 'bian' => -20002, 'bi' => -20026, 'beng' => -20032, 'ben' => -20036, 'bei' => -20051, 'bao' => -20230, 'bang' => -20242, 'ban' => -20257, 'bai' => -20265, 'ba' => -20283,
            'ao' => -20292, 'ang' => -20295, 'an' => -20304, 'ai' => -20317, 'a' => -20319,
        ];
    }
}