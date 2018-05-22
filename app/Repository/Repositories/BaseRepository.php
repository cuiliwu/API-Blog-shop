<?php

namespace App\Repository\Repositories;

use App\Repository\Criteria\CustomRequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository as Repository;

abstract class BaseRepository extends Repository
{
    /**
     * 搜索
     * @var array
     */
    protected $fieldSearchable = [];

    /**
     * 将前端字段名替换为关联查询模式
     * @var array
     */
    public $fieldSearchReplace = [];

    /**
     * 多字段联合模糊查询,前端查询字段名keyword
     * @var array
     */
    public $fieldFuzzySearch = [];

    /**
     * 可关联查询的字段
     */
    public $withAllow = [];

    /**
     *
     * @return string
     */
    public function presenter()
    {
        return "Prettus\\Repository\\Presenter\\ModelFractalPresenter";
    }

    public function query()
    {
        return $this->model->newQuery();
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(CustomRequestCriteria::class));
    }

    /**
     * 查找单条数据
     * @param array $where
     * @param array $columns
     * @return mixed
     */
    public function whereFirst(array $where, $columns = ['*'])
    {
        $this->applyCriteria();
        $this->applyScope();
        $model = $this->model->where($where)->firstOrFail($columns);
        $this->resetModel();

        return $this->parserResult($model);
    }

    public function sum($column, $where = [])
    {
        $this->applyCriteria();
        $this->applyScope();
        if($where){
            $query = $this->model->where($where);
        }
        $sum = $query->sum($column);

        return $sum;
    }

    public function increment($column, $where = [])
    {
        $this->applyCriteria();
        $this->applyScope();
        if($where){
            $query = $this->model->where($where);
        }
        $ret = $query->increment($column);

        return $ret;
    }

    public function updateWhere($attributes, $where = []){
        $this->applyCriteria();
        $this->applyScope();

        $ret = $this->model->where($where)->update($attributes);
        return $ret;
    }
}
