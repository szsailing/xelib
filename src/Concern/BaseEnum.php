<?php


namespace Xiaoetech\Xelib\Concern;


use App\Constants\XZXResponseCode;
use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;
use Hyperf\Constants\ConstantsCollector;
use Hyperf\Constants\Exception\ConstantsException;
use Hyperf\Utils\Str;
use ReflectionClass;

/**
 * Class BaseEnum
 * 数据库字段枚举基类
 *
 * @package App\Concern
 *
 * @method static getLabel($value)
 */
abstract class BaseEnum extends AbstractConstants {

    const __default = null;

    private $value;

    private static $constants = [];

    public function __construct ($value = null) {

        $class = get_class($this);

        if (!array_key_exists($class, self::$constants)) {
            self::populateConstants();
        }

        if ($value === null || $value === "") {
            $value = self::$constants[$class]["__default"];
        }

        $temp = self::$constants[$class];

        if (!in_array($value, $temp)) {
            throw new \UnexpectedValueException(sprintf("值 %s 不在枚举 %s 范围内", $value, $class));
        }

        $this->value = $value;
    }

    /**
     * 静态方法, 等效于 new Xxx(value)
     * @param null $value
     *
     * @return static
     */
    public static function born ($value = null) {
        return new static($value);
    }

    /**
     * 返回枚举范围
     *
     * @param bool $includeDefault
     *
     * @return mixed
     */
    public function getConstList ($includeDefault = false) {

        $class = get_class($this);

        if (!array_key_exists($class, self::$constants)) {
            self::populateConstants();
        }

        $list = self::$constants[$class];

        if (!$includeDefault) {
            unset($list['__default']);
        }

        return $list;
    }

    /**
     * 返回选项列表
     *
     * @return array
     */
    public function getOptions () {

        $list = $this->getConstList();

        $options = [];
        foreach ($list as $k => $v) {

            $opt = [
                'const' => $k,
                'value' => $v,
                'label' => self::getLabel($v),
            ];

            array_push($options, $opt);
        }

        return $options;
    }

    public function value () {
        return $this->value;
    }

    public function label () {
        return static::getLabel($this->value);
    }

    private function populateConstants () {

        $class = get_class($this);

        $r = new ReflectionClass($class);
        $constants = $r->getConstants();

        self::$constants = [
            $class => $constants,
        ];

    }

    public function __toString () {
        return (string) $this->value;
    }

}
