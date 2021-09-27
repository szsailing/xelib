<?php


namespace App\Concern;


use Hyperf\Database\Model\Model;
use Hyperf\Database\Query\Builder;
use Hyperf\DbConnection\Db;
use Hyperf\Utils\Arr;

/**
 * 通用Dao函数封装, 包含单表增删改查和联表列表查询, 联表函数实现在子类种覆写
 * Class BaseDao
 *
 * @package App\Concern
 */
abstract class BaseDao {

    /**
     * 获取表名
     *
     * @return string
     */
    abstract public static function table (): string;

    /**
     * 单表增删改查
     */
    use BaseDaoSingleTrait;

    /**
     * 通用列表查询
     */
    use BaseDaoCommonTrait;

    /**
     * 主键字段名
     */
    protected $pk = "id";

    /**
     * 创建/更新时间, 如果设为null, 则不会自动更新指定字段
     */
    protected $createTime = "createTime";
    protected $updateTime = "updateTime";

    /**
     * 软删除标记字段, 如果设置为null, 则不支持软删除
     */
    protected $softDelete = "delFlag";

    /**
     * 分页逻辑
     *
     * @param Builder $builder
     * @param         $page
     * @param         $limit
     *
     * @return Builder
     */
    protected function _buildPageLimit (Builder $builder, $page, $limit) {

        if (!is_null($page) && !is_null($limit)) {
            $builder->offset(($page - 1) * $limit)->limit($limit);
        }

        return $builder;
    }

    /**
     * 排序逻辑
     *
     * @param Builder $builder
     * @param array   $params
     *
     * @return mixed
     */
    protected function _buildOrder (Builder $builder, $params = []) {

        $builder->orderBy("main." . $this->pk, 'asc');

        return $builder;
    }

    /**
     * 查询逻辑
     *
     * @param $params
     *
     * @return Builder
     */
    protected function _buildQueryString ($params) {

        $main = sprintf("%s AS main", $this->table());

        $builder = DB::table($main);

        // like
        // Arr::exists($params, "name") && $builder->where('main.name', 'like', '%' . $param['name'] . '%');

        // equal || in
        // Arr::exists($params, "status") && $builder->where('main.status',$param['status']);

        // between 闭区间
        // Arr::exists($params, "createTime") && $builder->whereBetween('main.createTime', $param['createTime']);

        return $builder;
    }

    /**
     * 补充创建时间
     *
     * @param $data
     *
     * @return mixed
     */
    protected function _fillCreateTime ($data) {

        if (!is_null($this->createTime) && !Arr::exists($data, $this->createTime)) {
            // 创建时间自动 && 未传入创建时间
            // $data[$this->createTime] = date("Y-m-d H:i:s");
            $data[$this->createTime] = time();
        }

        return $data;
    }

    /**
     * 补充更新时间
     *
     * @param $data
     *
     * @return mixed
     */
    protected function _fillUpdateTime ($data) {

        if (!is_null($this->updateTime) && !Arr::exists($data, $this->updateTime)) {
            // 更新时间自动 && 未传入更新时间
            $data[$this->updateTime] = date("Y-m-d H:i:s");
        }

        return $data;
    }

}
