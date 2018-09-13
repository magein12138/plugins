<?php

namespace Magein\plugins\mixed;

/**
 * Class BinaryScene
 * @package Tools
 */
class BinaryScene
{
    /**
     * 可执行
     */
    const SCENE_EXECUTE = 1;

    /**
     * 可写
     */
    const SCENE_WRITE = 2;

    /**
     * 可读
     */
    const SCENE_READ = 4;

    /**
     * 获取场景值常量
     * @return array
     */
    protected function getSceneConstant(): array
    {
        $ref = new \ReflectionClass($this);

        $constants = $ref->getConstants();

        $constant = [];

        foreach ($constants as $item) {
            $constant[] = $item;
        }

        return $constant;
    }

    /**
     * 根据已选场景值返回一个场景
     * @param array $selected
     * @return int
     */
    public function merge(array $selected): int
    {
        if (empty($selected)) {
            return null;
        }

        $scene = $this->getSceneConstant();

        $trans = function ($selected, $value) use ($scene) {
            if (in_array($value, $scene)) {
                return $selected | $value;
            }
            return '';
        };

        return array_reduce($selected, $trans);
    }

    /**
     * 转化为具体的场景
     * @param int $selected 已选场景值
     * @return array
     */
    public function transScene(int $selected): array
    {
        $result = [];

        $scene = $this->getSceneConstant();

        foreach ($scene as $value) {

            if ($selected & $value) {
                $result[] = $value;
            }
        }

        return $result;
    }

    /**
     * 检测二进制场景值是否包含验证的场景
     * @param int $selected 已选场景值
     * @param int $scene 当前类的SCENE为前缀的常量
     * @return bool
     */
    public function checkScene(int $selected, int $scene): bool
    {
        if ($selected & $scene) {
            return true;
        }

        return false;
    }
}