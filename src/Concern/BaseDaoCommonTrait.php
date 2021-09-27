<?php


namespace App\Concern;


trait BaseDaoCommonTrait {

    /**
     * 通用条件列表
     *
     * @param      $param
     * @param null $page
     * @param null $limit
     *
     * @return mixed
     */
    public function getList ($param, $page = null, $limit = null) {

        $builder = $this->_buildQueryString($param);
        $builder = $this->_buildOrder($builder, $param);
        $builder = $this->_buildPageLimit($builder, $page, $limit);

        return $builder->get()->toArray();
    }

    /**
     * 通用条件数量
     *
     * @param $param
     *
     * @return int
     */
    public function getCount ($param) {
        $builder = $this->_buildQueryString($param);
        return $builder->count();
    }
}
