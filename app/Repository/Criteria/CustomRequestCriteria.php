<?php

namespace App\Repository\Criteria;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class RequestCriteria
 * @package Prettus\Repository\Criteria
 */
class CustomRequestCriteria implements CriteriaInterface
{
    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    public $fieldSearchReplace = [];

    public $fieldFuzzySearch = [];

    public $withAllow = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }


    /**
     * Apply criteria in query repository
     *
     * @param         Builder|Model $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     * @throws \Exception
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $fieldsSearchable = $repository->getFieldsSearchable();
        $this->fieldSearchReplace = isset($repository->fieldSearchReplace) ? $repository->fieldSearchReplace : [];
        $this->fieldFuzzySearch = isset($repository->fieldFuzzySearch) ? $repository->fieldFuzzySearch : [];
        $this->withAllow = isset($repository->withAllow) ? $repository->withAllow : [];
        $search = $this->request->get(config('repository.criteria.params.search', 'search'), null);
        $searchFields = $this->request->get(config('repository.criteria.params.searchFields', 'searchFields'), null);
        $filter = $this->request->get(config('repository.criteria.params.filter', 'filter'), null);
        $orderBy = $this->request->get(config('repository.criteria.params.orderBy', 'orderBy'), null);
        $sortedBy = $this->request->get(config('repository.criteria.params.sortedBy', 'sortedBy'), 'asc');
        $with = $this->request->get(config('repository.criteria.params.with', 'with'), null);
        $searchJoin = $this->request->get(config('repository.criteria.params.searchJoin', 'searchJoin'), null);
        $sortedBy = !empty($sortedBy) ? $sortedBy : 'asc';

        if ($search && is_array($fieldsSearchable) && count($fieldsSearchable)) {

            $searchFields = is_array($searchFields) || is_null($searchFields) ? $searchFields : explode(';', $searchFields);
            $fields = $this->parserFieldsSearch($fieldsSearchable, $searchFields);
            $isFirstField = true;
            $searchData = $this->parserSearchData($search);
            $search = $this->parserSearchValue($search);
            $modelForceAndWhere = strtolower($searchJoin) === 'and';

            $model = $model->where(function ($query) use ($fields, $search, $searchData, $isFirstField, $modelForceAndWhere) {
                /** @var Builder $query */
                $modelTableName = $query->getModel()->getTable();

                //多字段模糊查询
                if (array_get($searchData, 'keyword')) {
                    $keyword = '%' . array_get($searchData, 'keyword') . '%';
                    $query->where(function ($query) use ($keyword, $modelTableName) {
                        foreach ($this->fieldFuzzySearch as $column) {
                            $relation = null;
                            if (stripos($column, '.')) {
                                $explode = explode('.', $column);
                                $column = array_pop($explode);
                                $relation = implode('.', $explode);
                            }
                            if (!is_null($relation)) {
                                $query->orWhereHas($relation, function ($query) use ($column, $keyword) {
                                    $query->where($column, 'like', $keyword);
                                });
                            } else {
                                $query->orWhere($modelTableName . '.' . $column, 'like', $keyword);
                            }
                        }
                    });
                }

                foreach ($fields as $field => $condition) {

                    if (is_numeric($field)) {
                        $field = $condition;
                        $condition = "=";
                    }

                    $value = null;

                    $condition = trim(strtolower($condition));

                    if (isset($searchData[$field])) {
                        switch ($condition) {
                            case 'like':
                                $value = "%{$searchData[$field]}%";
                                break;
                            case 'ilike':
                                $value = "%{$searchData[$field]}%";
                                break;
                            case 'between':
                                $value = explode(',', $searchData[$field]);
                                break;
                            case 'in':
                                $value = explode(',', $searchData[$field]);
                                break;
                            case 'notin':
                                $value = explode(',', $searchData[$field]);
                                break;
                            default:
                                $value = $searchData[$field];
                                break;
                        }
                    } else {
                        if (!is_null($search)) {
                            $value = ($condition == "like" || $condition == "ilike") ? "%{$search}%" : $search;
                        }
                    }

                    $relation = null;
                    if (stripos($field, '.')) {
                        $explode = explode('.', $field);
                        $field = array_pop($explode);
                        $relation = implode('.', $explode);
                    }

                    if ($isFirstField || $modelForceAndWhere) {
                        if (!is_null($value)) {
                            if (!is_null($relation)) {
                                $query->whereHas($relation, function ($query) use ($field, $condition, $value) {
                                    switch ($condition) {
                                        case 'between':
                                            $this->betweenFilter($query, $field, $value);
                                            break;
                                        case 'in':
                                            $query->whereIn($field, $value);
                                            break;
                                        case 'notin':
                                            $query->whereNotIn($field, $value);
                                            break;
                                        default:
                                            $query->where($field, $condition, $value);
                                            break;
                                    }
                                });
                            } else {
                                switch ($condition) {
                                    case 'between':
                                        $this->betweenFilter($query, $field, $value, $modelTableName);
                                        break;
                                    case 'in':
                                        $query->whereIn($modelTableName . '.' . $field, $value);
                                        break;
                                    case 'notin':
                                        $query->whereNotIn($modelTableName . '.' . $field, $value);
                                        break;
                                    default:
                                        $query->where($modelTableName . '.' . $field, $condition, $value);
                                        break;
                                }
                            }
                            $isFirstField = false;
                        }
                    } else {
                        if (!is_null($value)) {
                            if (!is_null($relation)) {
                                $query->orWhereHas($relation, function ($query) use ($field, $condition, $value) {
                                    switch ($condition) {
                                        case 'between':
                                            $this->betweenFilter($query, $field, $value);
                                            break;
                                        case 'in':
                                            $query->whereIn($field, $value);
                                            break;
                                        case 'notin':
                                            $query->whereNotIn($field, $value);
                                            break;
                                        default:
                                            $query->where($field, $condition, $value);
                                            break;
                                    }
                                });
                            } else {
                                switch ($condition) {
                                    case 'between':
                                        $this->betweenFilter($query, $field, $value);
                                        break;
                                    case 'in':
                                        $query->orWhereIn($field, $value);
                                        break;
                                    case 'notin':
                                        $query->orWhereNotIn($field, $value);
                                        break;
                                    default:
                                        $query->orWhere($modelTableName . '.' . $field, $condition, $value);
                                        break;
                                }
                            }
                        }
                    }
                }
            });
        }

        if (isset($orderBy) && !empty($orderBy)) {
            $split = explode('|', $orderBy);
            if (count($split) > 1) {
                /*
                 * ex.
                 * products|description -> join products on current_table.product_id = products.id order by description
                 *
                 * products:custom_id|products.description -> join products on current_table.custom_id = products.id order
                 * by products.description (in case both tables have same column name)
                 */
                $table = $model->getModel()->getTable();
                $sortTable = $split[0];
                $sortColumn = $split[1];

                $split = explode(':', $sortTable);
                if (count($split) > 1) {
                    $sortTable = $split[0];
                    $keyName = $table . '.' . $split[1];
                } else {
                    /*
                     * If you do not define which column to use as a joining column on current table, it will
                     * use a singular of a join table appended with _id
                     *
                     * ex.
                     * products -> product_id
                     */
                    $prefix = str_singular($sortTable);
                    $keyName = $table . '.' . $prefix . '_id';
                }

                $model = $model
                    ->leftJoin($sortTable, $keyName, '=', $sortTable . '.id')
                    ->orderBy($sortColumn, $sortedBy)
                    ->addSelect($table . '.*');
            } else {
                $model = $model->orderBy($orderBy, $sortedBy);
            }
        }

        if (isset($filter) && !empty($filter)) {
            if (is_string($filter)) {
                $filter = explode(';', $filter);
            }

            $model = $model->select($filter);
        }

        /*
         * 关联数据
         */
        if ($with) {
            $with = explode(';', $with);

            $withAllowArr = [];
            foreach ($this->withAllow as $relation => $fields) {
                if (is_numeric($relation)) {    //不限制字段
                    $relation = $fields;
                    if(in_array($relation, $with)){
                        $withAllowArr[] = $relation;
                    }
                }else{  //限制指定字段
                    if(in_array($relation, $with)){
                        $withAllowArr[$relation] = function($query) use ($fields){
                            $query->select($fields);
                        };
                    }
                }
            }

            if(!empty($with)){
                $model = $model->with($withAllowArr);
            }
        }

        return $model;
    }

    /**
     * @param $search
     *
     * @return array
     */
    protected function parserSearchData($search)
    {
        $searchData = [];

        if (stripos($search, ':')) {
            $fields = explode(';', $search);

            foreach ($fields as $row) {
                try {
                    //避免时间被分隔
                    $explode_arr = explode(':', $row);
                    $field_name = $explode_arr[0];
                    array_shift($explode_arr);
                    $field_value = trim(implode(':', $explode_arr));
                    list($field, $value) = [$field_name, $field_value];
                    $field = array_get($this->fieldSearchReplace, $field) ?: $field;
                    $searchData[$field] = $value;
                } catch (\Exception $e) {
                    //Surround offset error
                }
            }
        }

        return $searchData;
    }

    /**
     * @param $search
     *
     * @return null
     */
    protected function parserSearchValue($search)
    {

        if (stripos($search, ';') || stripos($search, ':')) {
            $values = explode(';', $search);
            foreach ($values as $value) {
                $s = explode(':', $value);
                if (count($s) == 1) {
                    return $s[0];
                }
            }

            return null;
        }

        return $search;
    }


    protected function parserFieldsSearch(array $fields = [], array $searchFields = null)
    {
        if (!is_null($searchFields) && count($searchFields)) {
            $acceptedConditions = config('repository.criteria.acceptedConditions', [
                '=',
                'like'
            ]);
            $originalFields = $fields;
            $fields = [];

            foreach ($searchFields as $index => $field) {
                $field_parts = explode(':', $field);
                $temporaryIndex = array_search($field_parts[0], $originalFields);

                if (count($field_parts) == 2) {
                    if (in_array($field_parts[1], $acceptedConditions)) {
                        unset($originalFields[$temporaryIndex]);
                        $field = $field_parts[0];
                        $condition = $field_parts[1];
                        $originalFields[$field] = $condition;
                        $searchFields[$index] = $field;
                    }
                }
            }

            foreach ($originalFields as $field => $condition) {
                if (is_numeric($field)) {
                    $field = $condition;
                    $condition = "=";
                }
                if (in_array($field, $searchFields)) {
                    $fields[$field] = $condition;
                }
            }

            if (count($fields) == 0) {
                throw new \Exception(trans('repository::criteria.fields_not_accepted', ['field' => implode(',', $searchFields)]));
            }

        }

        return $fields;
    }

    protected function betweenFilter($query, $field, $value, $model_table_name = '')
    {
        if (array_get($value, 0) && array_get($value, 1)) {
            $query->whereBetween($model_table_name . '.' . $field, $value);
        } elseif (array_get($value, 0)) {
            $query->where($model_table_name . '.' . $field, '>', array_get($value, 0));
        } elseif (array_get($value, 1)) {
            $query->where($model_table_name . '.' . $field, '<', array_get($value, 1));
        }
    }
}
