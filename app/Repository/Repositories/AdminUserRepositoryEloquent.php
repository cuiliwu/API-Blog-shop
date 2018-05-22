<?php

namespace App\Repository\Repositories;

use App\Repository\Repositories\Interfaces\AdminUserRepository;
use App\Repository\Models\AdminUser;

/**
 * Class AdminUserRepositoryEloquent.
 *
 * @package namespace App\Repository\Repositories;
 */
class AdminUserRepositoryEloquent extends BaseRepository implements AdminUserRepository
{
    /**
     * 搜索
     * @var array
     */
    protected $fieldSearchable = [];

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return AdminUser::class;
    }
}
