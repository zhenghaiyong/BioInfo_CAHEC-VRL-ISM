<?php
class PublicAction extends Action {
    // 检查用户是否登录
    protected function checkUser() {
        if(!isset($_SESSION[C('USER_AUTH_KEY')])) {
            $this->error(L('_NOT_LOGIN_'),'login');
        }
    }

    // 顶部页面
    public function top() {
        C('SHOW_RUN_TIME',false);			// 运行时间显示
        C('SHOW_PAGE_TRACE',false);
		// ZHY 根据用户（user）角色（role）权限（node）读取组名（group）
		if(isset($_SESSION[C('USER_AUTH_KEY')])) {
			$user_id = $_SESSION[C('USER_AUTH_KEY')];
			$public = D('Public');
			$node_id = $public->getNodeList($user_id); // role_user access
			for($i=0;$i<count($node_id);$i++) {
				$results[] = $public->getGroupList($node_id[$i]['node_id']); // node group
      		}
			$result = array_filter($results);
			sort($result);
			$result=dimReduce($result);
			for($i=0;$i<count($result);$i++) {
				$list[$result[$i]['id']]=$result[$i]['title'];
			}
	    }
		// ZHY
        //$model	=	M("Group");
        //$list	=	$model->where('status=1')->getField('id,title');
        $this->assign('nodeGroupList',$list);
        $this->display();
    }

    public function drag(){
        C('SHOW_PAGE_TRACE',false);
        C('SHOW_RUN_TIME',false);			// 运行时间显示
        $this->display();
    }

    // 尾部页面
    public function footer() {
        C('SHOW_RUN_TIME',false);			// 运行时间显示
        C('SHOW_PAGE_TRACE',false);
        $this->display();
    }

    // 菜单页面
    public function menu() {
        $this->checkUser();
        if(isset($_SESSION[C('USER_AUTH_KEY')])) {
            //显示菜单项
            $menu  = array();
            if(isset($_SESSION['menu'.$_SESSION[C('USER_AUTH_KEY')]])) {
                //如果已经缓存，直接读取缓存
                $menu   =   $_SESSION['menu'.$_SESSION[C('USER_AUTH_KEY')]];
            }else {
                //读取数据库模块列表生成菜单项
                $node    =   M("Node");
                $id	=	$node->getField("id");
                $where['level']=2;
                $where['status']=1;
                $where['pid']=$id;
                $list	=	$node->where($where)->field('id,name,group_id,title')->order('sort asc')->select();
                if(isset($_SESSION['_ACCESS_LIST'])) {
                    $accessList = $_SESSION['_ACCESS_LIST'];
                }else{
                    import('@.ORG.Util.RBAC');
                    $accessList =   RBAC::getAccessList($_SESSION[C('USER_AUTH_KEY')]);
                }
                foreach($list as $key=>$module) {
                     if(isset($accessList[strtoupper(APP_NAME)][strtoupper($module['name'])]) || $_SESSION['administrator']) {
                        //设置模块访问权限
                        $module['access'] =   1;
                        $menu[$key]  = $module;
                    }
                }
                //缓存菜单访问
                $_SESSION['menu'.$_SESSION[C('USER_AUTH_KEY')]]	=	$menu;
            }
            if(!empty($_GET['tag'])){
                $this->assign('menuTag',$_GET['tag']);
            }
            //dump($menu);
            $this->assign('menu',$menu);
        }
        C('SHOW_RUN_TIME',false);			// 运行时间显示
        C('SHOW_PAGE_TRACE',false);
        $this->display();
    }

    // 后台首页 查看系统信息
    public function main() {
        $info = array(
            L('_PHP_OS_')=>PHP_OS,
            L('_SERVER_SOFTWARE_')=>$_SERVER["SERVER_SOFTWARE"],
            L('_PHP_SERVER_API_')=>php_sapi_name(),
            L('_THINKPHP_VERSION_')=>THINK_VERSION.' [ <a href="http://thinkphp.cn" target="_blank">'.L('_CHECK_LATEST_VERSION_').'</a> ]',
            L('_UPLOAD_MAX_FILESIZE_')=>ini_get('upload_max_filesize'),
            L('_MAX_EXECUTION_TIME_')=>ini_get('max_execution_time').L('_SECOND_'),
            L('_SERVER_DATETIME_')=>date("Y-n-j H:i:s"),
            L('_BEIJING_DATETIME_')=>gmdate("Y-n-j H:i:s",time()+8*3600),
            L('_SERVER_NAME_') . '[ IP ]'=>$_SERVER['SERVER_NAME'].' [ '.gethostbyname($_SERVER['SERVER_NAME']).' ]',
            L('_DISK_FREE_SPACE_')=>round((@disk_free_space(".")/(1024*1024)),2).'M',
            'register_globals'=>get_cfg_var("register_globals")=="1" ? "ON" : "OFF",
            'magic_quotes_gpc'=>(1===get_magic_quotes_gpc())?'YES':'NO',
            'magic_quotes_runtime'=>(1===get_magic_quotes_runtime())?'YES':'NO',
            );
        $this->assign('info',$info);
        $this->display();
    }

    // 用户登录页面
    public function login() {
        if(!isset($_SESSION[C('USER_AUTH_KEY')])) {
            $this->display();
        }else{
            $this->redirect('Index/index');
        }
    }

    public function index() {
        //如果通过认证跳转到首页
        redirect(__APP__);
    }

    // 用户登出
    public function logout() {
        if(isset($_SESSION[C('USER_AUTH_KEY')])) {
            unset($_SESSION[C('USER_AUTH_KEY')]);
            unset($_SESSION);
            session_destroy();
            $this->success(L('_SIGN_OUT_SUCCESS_'),__URL__.'/login/');
        }else {
            $this->error(L('_ALREADY_SIGN_OUT_'));
        }
    }

    // 登录检测
    public function checkLogin() {
        if(empty($_POST['account'])) {
            $this->error(L('_ACCOUNT_EMPTY_'));
        }elseif (empty($_POST['password'])){
            $this->error(L('_PASSWORD_EMPTY_'));
        }elseif (empty($_POST['verify'])){
            $this->error(L('_VERIFICATION_EMPTY_'));
        }
        //生成认证条件
        $map            =   array();
        // 支持使用绑定帐号登录
        $map['account']	= $_POST['account'];
        $map["status"]	=	array('gt',0);
        if(session('verify') != md5($_POST['verify'])) {
            $this->error(L('_VERIFICATION_ERROR_'));
        }
        import ( '@.ORG.Util.RBAC' );
        $authInfo = RBAC::authenticate($map);
        //使用用户名、密码和状态的方式进行认证
        if(false === $authInfo) {
            $this->error(L('_ACCOUNT_NOT_EXIST_OR_DISABLE_'));
        }else {
            if($authInfo['password'] != md5($_POST['password'])) {
                $this->error(L('_PASSWORD_ERROR_'));
            }
            $_SESSION[C('USER_AUTH_KEY')]	=	$authInfo['id'];
            $_SESSION['email']	=	$authInfo['email'];
            $_SESSION['loginUserName']		=	$authInfo['nickname'];
            $_SESSION['lastLoginTime']		=	$authInfo['last_login_time'];
            $_SESSION['login_count']	=	$authInfo['login_count'];
            if($authInfo['account']=='admin') {
                $_SESSION['administrator']		=	true;
            }
            //保存登录信息
            $User	=	M('User');
            $ip		=	get_client_ip();
            $time	=	time();
            $data = array();
            $data['id']	=	$authInfo['id'];
            $data['last_login_time']	=	$time;
            $data['login_count']	=	array('exp','login_count+1');
            $data['last_login_ip']	=	$ip;
            $User->save($data);

            // 缓存访问权限
            RBAC::saveAccessList();
            $this->success(L('_SIGN_IN_SUCCESS_'),__APP__.'/Index/index');

        }
    }

    // 更换密码
    public function changePwd() {
        $this->checkUser();
        //对表单提交处理进行处理或者增加非表单数据
        if(md5($_POST['verify'])	!= $_SESSION['verify']) {
            $this->error(L('_VERIFICATION_ERROR_'));
        }
        $map	=	array();
        $map['password']= pwdHash($_POST['oldpassword']);
        if(isset($_POST['account'])) {
            $map['account']	 =	 $_POST['account'];
        }elseif(isset($_SESSION[C('USER_AUTH_KEY')])) {
            $map['id']		=	$_SESSION[C('USER_AUTH_KEY')];
        }
        //检查用户
        $User    =   M("User");
        if(!$User->where($map)->field('id')->find()) {
            $this->error(L('_OLD_PASSWORD_ERROR_OR_ACCOUNT_ERROR_'));
        }else {
            $User->password	=	pwdHash($_POST['password']);
            $User->save();
            $this->success(L('_CHANGE_PASSWORD_SUCCESS_'));
         }
    }

    public function profile() {
        $this->checkUser();
        $User	 =	 M("User");
        $vo	=	$User->getById($_SESSION[C('USER_AUTH_KEY')]);
        $this->assign('vo',$vo);
        $this->display();
    }

    public function verify() {
        $type	 =	 isset($_GET['type'])?$_GET['type']:'gif';
        import("@.ORG.Util.Image");
        Image::buildImageVerify(4,1,$type);
    }

    // 修改资料
    public function change() {
        $this->checkUser();
        $User	 =	 D("User");
        if(!$User->create()) {
            $this->error($User->getError());
        }
        $result	=	$User->save();
        if(false !== $result) {
            $this->success(L('_CHANGE_PROFILE_SUCCESS_'));
        }else{
            $this->error(L('_CHANGE_PROFILE_FAILURE_'));
        }
    }
}