<?php
declare(strict_types=1);

namespace Xiaoetech\Xelib\Utils;

use Hyperf\Utils\Context;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
// use Hyperf\HttpServer\Contract\ResponseInterface;
use Throwable;

/**
 * 自定义函数类
 *
 * Class CustomUtils
 * @package Xiaoetech\Xelib\Utils
 */
class CustomUtils
{

    public static function xeServiceReturnData(int $code, string $message = '', $data = []): array
    {
        $result = [
            'code' => $code,
            'message' => $message,
            'data' => $data
        ];
        
        return $result;
    }

    /**
     * 操作成功返回值包装
     *
     * @param array $data
     * @param string $msg
     * @return array
     */
    public static function xeResponseData(int $code, string $message = '', $data = []): array
    {
        return [
            "code"  => $code,
            "msg"   => $message == "" ? "操作成功" : $message,
            "data"  => $data
        ];
    }

    public static function xeDebugLogContext()
    {
        $debug_backtrace = debug_backtrace();

        $trace = current($debug_backtrace);

        // 获取函数名
        $func = next($debug_backtrace);
        $func = !$func ? null : $func['function'];

        $context = [
            'file' => $trace['file'],
            'func' => $func,
            'line' => $trace['line']
        ];        
        return $context;
    }

    // /**
    //  * 自定义打印调试函数
    //  */
    // public static function xzxDebug()
    // {
    //     // 检查环境参数
    //     if (!env('APP_DEBUG')) {
    //         return;
    //     }

    //     $args = func_get_args();

    //     if (PHP_SAPI !== 'cli') echo '<pre>';

    //     $datetime = self::xzxGetDateTime();

    //     echo PHP_EOL . '---------- debug print begin ----------' . PHP_EOL . PHP_EOL;

    //     foreach ($args as $v) {
    //         if (!$v) {
    //             var_dump($v);
    //         } else {
    //             print_r($v);
    //         }
    //         echo PHP_EOL;
    //     }

    //     echo PHP_EOL . '---------------------------------------' . PHP_EOL;

    //     $debug_backtrace = debug_backtrace();

    //     $trace = current($debug_backtrace);

    //     // 获取函数名
    //     $func = next($debug_backtrace);
    //     $func = !$func ? null : $func['function'];

    //     echo "FILE  : {$trace['file']}" . PHP_EOL;
    //     echo "FUNC  : {$func}" . PHP_EOL;
    //     echo "LINE  : {$trace['line']}" . PHP_EOL;
    //     echo "TIME  : {$datetime}" . PHP_EOL;

    //     echo '---------- debug print end   ----------' . PHP_EOL . PHP_EOL;

    //     if (PHP_SAPI !== 'cli') echo '</pre>';
    // }

    /**
     * 时间戳转换
     *
     * @param null $time
     * @return false|string
     */
    public static function xeGetDateTime($time = null)
    {
        if(is_null($time)){
            $time = time();
        }
        return date('Y-m-d H:i:s', $time);
    }

    /**
     * @param $pwd
     * @param $salt
     * @return string
     */
    public static function xeGenPassWord($pwd, $salt = "")
    {
        if ($salt = "") {
            $salt = "xzxkkkkkkk";
        }
        return  md5($pwd . '*' . $salt);
    }

    /**
     * @return mixed|string
     */
    public static function xeGetClientIp()
    {
        try {
            /**
             * @var ServerRequestInterface $request
             */
            $request = Context::get(ServerRequestInterface::class);
            $ip_addr = $request->getHeaderLine('x-forwarded-for');
            if (self::xeVerifyIp($ip_addr)) {
                return $ip_addr;
            }
            $ip_addr = $request->getHeaderLine('remote-host');
            if (self::xeVerifyIp($ip_addr)) {
                return $ip_addr;
            }
            $ip_addr = $request->getHeaderLine('x-real-ip');
            if (self::xeVerifyIp($ip_addr)) {
                return $ip_addr;
            }
            $ip_addr = $request->getServerParams()['remote_addr'] ?? '0.0.0.0';
            if (self::xeVerifyIp($ip_addr)) {
                return $ip_addr;
            }
        } catch (Throwable $e) {
            return '0.0.0.0';
        }
        return '0.0.0.0';
    }

    public static function xeVerifyIp($realip)
    {
        return filter_var($realip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
    }

    /**
     * 根据IP获取地区
     */

    public static function getCity($ip)
    {

    }
    /**
     * 判断是否存在并且不为空
     *
     * @param $param
     *
     * @return bool
     */
    public static function xeIsEmptyParam($param)
    {
        return (isset($param) && !empty($param));
    }

    /**
     * 当存在且不为空字符串的时候增加参数
     */
    public static function xeAddSearch(&$param, $search, $key)
    {
        if (isset($search[$key]) && $search[$key] !== '') {

            $param[$key] = $search[$key];
        }
        return $param;
    }

    /**
     * 当存在且不为空字符串的时候增加参数，将search对象里面的所有字段都复制到param
     */
    public static function xeAddAllSearch(&$param, $search)
    {
        foreach($search as $k => $v){
            if (isset($search[$k]) && $search[$k] !== '') {
                $param[$k] = $search[$k];
                if(preg_match('/Range$/', $k, $matches)){
                    try {
                        $param[$k][0] = DateUtils::dateToTimeStamp($param[$k][0]);
                        $param[$k][1] = DateUtils::dateToTimeStamp($param[$k][1]);
                    } catch (\Throwable $th) {
                        unset($param[$k]);
                    }                
                }
            } 
        }
        return $param;
    }

    /**
     * 空对象
     */
    public static function xeEmptyObject()
    {
        return (object)[];
    }
}
