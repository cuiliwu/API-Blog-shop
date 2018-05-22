<?php

namespace App\Repository\Criteria;

use Illuminate\Support\Facades\Auth;
use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Contracts\CriteriaInterface;

/**
 * 用于控制数据所有者
 * Class OwnerCriteria
 * @package App\Repository\Criteria
 */
class OwnerCriteria implements CriteriaInterface
{

    public function apply($model, RepositoryInterface $repository)
    {
        //@todo 暂无登录模块，暂时写死
        //$model = $model->where('user_id','=', Auth::user()->id );
        $model = $model->where('user_id', '=', 2);

        return $model;
    }
}