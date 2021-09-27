<?php


namespace Xiaoetech\Xelib\Concern;

use Hyperf\Database\Model\Model;
use Hyperf\Database\Query\Builder;
use Hyperf\DbConnection\Db;
use Hyperf\Utils\Arr;

trait BaseDaoSingleTrait {

    /**
     * 主键获取
     *
     * @param $id
     *
     * @return Model|Builder|object|null
     */
    public function get ($id) {
        return $this->getWhere([$this->pk => $id]);
    }

    /**
     * 条件获取
     *
     * @param $where
     *
     * @return Model|Builder|object|null
     */
    public function getWhere ($where) {
        return DB::table($this->table())->where($where)->first();
    }

    /**
     * 条件数量
     *
     * @param $where
     *
     * @return int
     */
    public function getWhereCount ($where) {
        return DB::table($this->table())->where($where)->count();
    }

    /**
     * 条件累加数值
     *
     * @param $where
     * @param $sumField
     *
     * @return int
     */
    public function getWhereSum ($where,$sumField) {
        return DB::table($this->table())->where($where)->sum($sumField);
    }

    /**
     * 条件列表
     *
     * @param        $where
     * @param null   $orderBy
     * @param null[] $paginate
     *
     * @return array
     */
    public function getWhereList ($where, $orderBy = null, $paginate = [null, null]) {

        $builder = DB::table($this->table())->where($where);
        $builder = $this->_buildPageLimit($builder, $paginate[0], $paginate[1]);

        if (is_string($orderBy)) {
            $builder->orderBy($orderBy);
        }
        else if (is_array($orderBy)) {
            foreach ($orderBy as $k => $v) {
                $builder->orderBy($k, $v);
            }
        }

        return $builder->get()->toArray();
    }

    /**
     * 插入
     *
     * @param $data
     *
     * @return bool|int
     */
    public function add ($data) {

        if (!Arr::isAssoc($data)) {
            // 非关联数组, 批量插入

            foreach ($data as $k => $item) {
                $item = $this->_fillCreateTime($item);
                $item = $this->_fillUpdateTime($item);

                $data[$k] = $item;
            }

            return DB::table($this->table())->insert($data);
        }
        else {
            // 关联数组, 单条插入

            $data = $this->_fillCreateTime($data);
            $data = $this->_fillUpdateTime($data);

            return DB::table($this->table())->insertGetId($data);
        }
    }

    public function insert ($data) {
        return $this->add($data);
    }

    /**
     * 更新
     *
     * @param $id
     * @param $data
     *
     * @return int
     */
    public function set ($id, $data) {
        return $this->setWhere(['id' => $id], $data);
    }

    public function update ($id, $data) {
        return $this->set($id, $data);
    }

    /**
     * 条件更新
     *
     * @param $where
     * @param $data
     *
     * @return int
     */
    public function setWhere ($where, $data) {

        $data = $this->_fillUpdateTime($data);

        return DB::table($this->table())->where($where)->update($data);
    }

    public function updateWhere ($where, $data) {
        return $this->setWhere($where, $data);
    }


    /**
     * 删除
     *
     * @param      $id
     * @param bool $soft
     *
     * @return int
     */
    public function del ($id, $soft = true) {
        return $this->delWhere(['id' => $id], $soft);
    }

    /**
     * 条件删除
     *
     * @param      $where
     * @param bool $soft
     *
     * @return int
     */
    public function delWhere ($where, $soft = true) {


        if ($soft && !is_null($this->softDelete)) {
            // 要求软删除, 并且支持软删除
            $this->updateWhere($where, [$this->softDelete => 1]);
        }
        else {
            return DB::table($this->table())->where($where)->delete();
        }
    }
}
