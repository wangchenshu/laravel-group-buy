<?php

namespace App\Api\Helpers;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class ExceptionReport
{
    use ApiResponse;

    /**
     * @var Exception
     */
    public $exception;
    /**
     * @var Request
     */
    public $request;

    /**
     * @var
     */
    protected $report;

    /**
     * ExceptionReport constructor.
     * @param Request $request
     * @param Exception $exception
     */
    function __construct(Request $request, Exception $exception)
    {
        $this->request = $request;
        $this->exception = $exception;
    }

    /**
     * @var array
     */
    //当抛出这些异常时，可以使用我们定义的错误信息与HTTP状态码
    //可以把常见异常放在这里
    public $doReport = [
        AuthenticationException::class => ['未授權', 401],
        ModelNotFoundException::class => ['找不到資料', 404],
        AuthorizationException::class => ['沒有權限', 403],
        ValidationException::class => [],
        UnauthorizedHttpException::class => ['未登錄或登入狀態失效', 422],
        TokenInvalidException::class => ['token 不正確', 401],
        NotFoundHttpException::class => ['找不到頁面', 404],
        MethodNotAllowedHttpException::class => ['訪問方式不正確', 405],
        QueryException::class => ['參數錯誤', 400],
    ];

    public function register($className, callable $callback)
    {

        $this->doReport[$className] = $callback;
    }

    /**
     * @return bool
     */
    public function shouldReturn()
    {
        //只有请求包含是json或者ajax请求时才有效
        //        if (! ($this->request->wantsJson() || $this->request->ajax())){
        //
        //            return false;
        //        }
        foreach (array_keys($this->doReport) as $report) {
            if ($this->exception instanceof $report) {
                $this->report = $report;
                return true;
            }
        }

        return false;
    }

    /**
     * @param Exception $e
     * @return static
     */
    public static function make(Exception $e)
    {

        return new static(\request(), $e);
    }

    /**
     * @return mixed
     */
    public function report()
    {
        if ($this->exception instanceof ValidationException) {
            $error = array_first($this->exception->errors());
            return $this->failed(array_first($error), $this->exception->status);
        }
        $message = $this->doReport[$this->report];
        return $this->failed($message[0], $message[1]);
    }

    public function prodReport()
    {
        return $this->failed('伺服器錯誤', '500');
    }
}
