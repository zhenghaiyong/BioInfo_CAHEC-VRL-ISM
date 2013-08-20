<?php
return array(
    'URL_MODEL'                 =>  2, // 如果你的环境不支持PATHINFO 请设置为3
    'DB_TYPE'                   =>  'mysql',
    'DB_HOST'                   =>  'localhost',
    'DB_NAME'                   =>  'cahecvrl',
    'DB_USER'                   =>  'root',
    'DB_PWD'                    =>  'pwd',
    'DB_PORT'                   =>  '3306',
    'DB_PREFIX'                 =>  'vrl_',
    'APP_AUTOLOAD_PATH'         =>  '@.TagLib',
    'SESSION_AUTO_START'        =>  true,
    'TMPL_ACTION_ERROR'         =>  'Public:success', // 默认错误跳转对应的模板文件
    'TMPL_ACTION_SUCCESS'       =>  'Public:success', // 默认成功跳转对应的模板文件
    'USER_AUTH_ON'              =>  true,
    'USER_AUTH_TYPE'			=>  2,		// 默认认证类型 1 登录认证 2 实时认证
    'USER_AUTH_KEY'             =>  'authId',	// 用户认证SESSION标记
    'ADMIN_AUTH_KEY'			=>  'administrator',
    'USER_AUTH_MODEL'           =>  'User',	// 默认验证数据表模型
    'AUTH_PWD_ENCODER'          =>  'md5',	// 用户认证密码加密方式
    'USER_AUTH_GATEWAY'         =>  '/Public/login',// 默认认证网关
    'NOT_AUTH_MODULE'           =>  'Public',	// 默认无需认证模块
    'REQUIRE_AUTH_MODULE'       =>  '',		// 默认需要认证模块
    'NOT_AUTH_ACTION'           =>  '',		// 默认无需认证操作
    'REQUIRE_AUTH_ACTION'       =>  '',		// 默认需要认证操作
    'GUEST_AUTH_ON'             =>  false,    // 是否开启游客授权访问
    'GUEST_AUTH_ID'             =>  0,        // 游客的用户ID
    'DB_LIKE_FIELDS'            =>  'title|remark',
    'RBAC_ROLE_TABLE'           =>  'vrl_role',
    'RBAC_USER_TABLE'           =>  'vrl_role_user',
    'RBAC_ACCESS_TABLE'         =>  'vrl_access',
    'RBAC_NODE_TABLE'           =>  'vrl_node',
    'SHOW_PAGE_TRACE'           =>  1,  //显示调试信息
    // 多语言
    'LANG_SWITCH_ON'            =>  true,
    'DEFAULT_LANG'              =>  'en-us',  //默认语言
    'LANG_AUTO_DETECT'          =>  false,  //自动侦测语言
    'LANG_LIST'                 =>  'en-us,zh-cn',  //必须写可允许的语言列表
);
