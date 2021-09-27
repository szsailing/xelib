<?php

declare(strict_types=1);

namespace Xiaoetech\Xelib\Constants;

use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

/**
 * @Constants
 */
class ResponseCode extends AbstractConstants
{
    /**
     * @Message("服务器暂时开小差了，请稍后再试！")
     */
    const INTERNAL_CODE = 500;

    /**
     * @Message("Success")
     */
    const SUCCESS_CODE = 0;

    /**
     * @Message("Failed")
     */
    const FAILED_CODE = 1;

    /**
     * @Message("请求成功。")
     */
    const REQUEST_SUCCESS_CODE = 200;

    /**
     * @Message("Token 解密失败，请重新登录！")
     */
    const TOKEN_ERROR_CODE = 1000;

    /**
     * @Message("Token 已过期，请重新登录！")
     */
    const TOKEN_EXPIRE_CODE = 1001;   
    
    /**
     * @Message("没有访问此node的权限！")
     */
    const NO_ACCESS_NODE_MSG = 1002;    

    /**
     * @Message("未找到记录。")
     */
    const DATA_NOT_FOUND = 1003;
    
    /**
     * @Message("添加失败。")
     */
    const ADD_FAIL = 1004;    

    /**
     * @Message("编辑失败。")
     */
    const EDIT_FAIL = 1005; 

    /**
     * @Message("删除失败。")
     */
    const DEL_FAIL = 1006;     

    /**
     * @Message("message.welcome %s")
     */
    const TEST = 1007;    

}
