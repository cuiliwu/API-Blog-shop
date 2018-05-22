<?php
/**
 * Created by Engineer CuiLiwu.
 * Project: deal.
 * Date: 2018/5/22-11:40
 * 公共基类控制器
 */
    namespace App\Http\Controllers\Admin;
    use App\ConstDir\ErrorConst;
    use Illuminate\Routing\Controller;
    use Tymon\JWTAuth\Exceptions\JWTException;

    class BaseController extends Controller{

        protected $perPage = 15;//分页


        public function __construct()
        {
            $this->perPage = request()->input('perPage',15);
            static::formatErrorsUsing(function($validator){
                return [
                    'code' => ErrorConst::FAILED_CODE,
                    'message' => $validator->errors()->getMessages(),
                    'data' => null,
                ];
            });
        }

        /*
         * 错误提示
         */
        public function error($message, $code = ErrorConst::FAILED_CODE){
            return collect([
                    'code' => $code,
                    'message' => $message,
                    'data' => null,
                ]
            );
        }

        /*
         * 成功返回提示
         */
        public function success($message, $data = null, $page = false){
            if($page){
                $ret = [
                    'list' => $data['data'],
                    'page' => $data['meta']['pagination']
                ];
            }else{
                $ret = $data;
            }
            return collect([
                'code' => ErrorConst::SUCCESS_CODE,
                'message' => $message,
                'data' => $ret,
            ]);
        }

        /*
         * 无数据的返回
         */
        public function noDataReturn($status)
        {
            if ($status) {
                return $this->success(ErrorConst::SUCCESS_CODE_MSG);
            } else {
                return $this->error(ErrorConst::FAILED_CODE_MSG);
            }
        }

    }