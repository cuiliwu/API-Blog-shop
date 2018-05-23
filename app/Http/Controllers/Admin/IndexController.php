<?php
/**
 * Created by Engineer CuiLiwu.
 * Project: deal.
 * Date: 2018/5/18-9:41
 */

namespace App\Http\Controllers\Admin;

use App\ConstDir\ErrorConst;
use App\Http\Controllers\Admin\BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Repository\Repositories\Interfaces\AdminUserRepository;

class IndexController  extends BaseController
{
    /**
     * @var AdminUserRepository
     */
    protected $article_cate_repo;


    public function __construct(AdminUserRepository $article_cate_repo, Request $request)
    {
        $this->article_cate_repo = $article_cate_repo;
        $this->request = $request;
    }
    /**
     * 列表
     * 分页
     * */
    public function index(){
        if ($this->request->get('type')=='all'){
            $articleCate = $this->article_cate_repo->all();
        }else{
            $articleCate = $this->article_cate_repo->paginate($this->perPage);
        }
        return $this->success(ErrorConst::SUCCESS_CODE, $articleCate, true);
    }

    /**
     * 查询
     *
     * */
    public function show($id)
    {
        $change= $this->article_cate_repo->find($id);
        return $this->success(ErrorConst::SUCCESS_CODE, $change['data']);
    }
    /**
     * 添加
     *
     * */
    public function store(){

        try {
            $this->form();
            if ($action = $this->article_cate_repo->create($this->request->all())) {
                return $this->success('创建操作成功',$action['data']);
            }
        }
        catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
        return $this->error('创建失败');
    }
    /**
     * 修改
     *
     * */
    public function update($id){
        $this->form();
        $data = $this->request->all();

        $ret = $this->article_cate_repo->update($data, $id);
        if ($ret) {
            return $this->success(ErrorConst::SUCCESS_CODE, ErrorConst::SUCCESS_CODE_MSG);
        } else {
            return $this->error(ErrorConst::FAILED_CODE);
        }
    }
    /**
     * 删除
     *
     * */
    public function delete($id){
        $ret = $this->article_cate_repo->delete($id);

        return $this->success(ErrorConst::SUCCESS_CODE, ErrorConst::SUCCESS_CODE_MSG);
    }

    /**
     * 后台-验证分类保存
     * */
    public function form(){
        $this->validate($this->request,[
            'fid'   => 'required|integer',
            'title' => 'required',
            'is_show' => 'required',
            'sort' => 'integer',
        ], [
            'fid'   => '父级分类必选|父级分类必选',
            'title' => '分类名称必填',
            'is_show' => '是否前台展示必选',
            'integer' => '排序必须为数字',
        ]);

        return $this->request->all();
    }

    function test(){
        if ($this->request->get('type')=='all'){
            $articleCate = $this->article_cate_repo->all();
        }else{
            $articleCate = $this->article_cate_repo->paginate($this->perPage);
        }
        cui_log(var_export($this->request->session()->all(),1),'ecclub');
        return $this->success(ErrorConst::SUCCESS_CODE, $articleCate, true);
    }

}