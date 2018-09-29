<?php

class IdExtract
{
    /**
     * 城市编码：身份证 1、2 位
     * @var string
     */
    private $provinceCode = '';

    /**
     * 城市编码: 身份证 3、4 位
     * @var string
     */
    private $cityCode = '';

    /**
     * 县区编码：身份证 5、6 为
     * @var string
     */
    private $areaCode = '';

    /**
     * 性别：身份证最后一位 奇数 男 偶数 女
     * @var string
     */
    private $sex = '';

    /**
     * 出生年月：身份证 7~14 位
     * @var string
     */
    private $birth = '';

    /**
     * 年龄: 计算得到
     * @var string
     */
    private $age = '';

    /**
     * IdExtract constructor.
     * @param $idNumber
     */
    public function __construct($idNumber)
    {
        $this->init($idNumber);
    }

    /**
     * @param string $idNumber
     */
    private function init($idNumber)
    {
        preg_match('/([\d]{2})([\d]{2})([\d]{2})([\d]{8})([\d]{4})x?/', $idNumber, $matches);

        if ($matches) {

            $sex = function ($value) {
                return ($value & 1) == 1 ? '男' : '女';
            };

            $age = function ($value) {
                return date('Y') - substr($value, 0, 4);
            };

            $this->provinceCode = $matches[1];
            $this->cityCode = $matches[2];
            $this->areaCode = $matches[3];
            $this->birth = $matches[4];
            $this->sex = $sex($matches[5]);
            $this->age = $age($matches[4]);
        }
    }

    /**
     * @return string
     */
    public function getProvinceCode(): string
    {
        return $this->provinceCode;
    }

    /**
     * @return string
     */
    public function getCityCode(): string
    {
        return $this->cityCode;
    }

    /**
     * @return string
     */
    public function getAreaCode(): string
    {
        return $this->areaCode;
    }

    /**
     * @return string
     */
    public function getSex(): string
    {
        return $this->sex;
    }

    /**
     * @return string
     */
    public function getBirth(): string
    {
        return $this->birth;
    }

    /**
     * @return string
     */
    public function getAge(): string
    {
        return $this->age;
    }
}