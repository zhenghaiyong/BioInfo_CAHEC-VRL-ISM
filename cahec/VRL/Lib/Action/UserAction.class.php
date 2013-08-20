<?php
// 后台用户模块
class UserAction extends CommonAction {
    function _filter(&$map){
        $map['id'] = array('egt',2);
        if(!empty($_POST['account'])) {
            $map['account'] = array('like',"%".$_POST['account']."%");
        }
    }

    // 检查帐号
    public function checkAccount() {
        if(!preg_match('/^[a-z]\w{4,}$/i',$_POST['account'])) {
            $this->error(L('_USERNAME_MUST_BE_LETTERS_ERROR_'));
        }
        $User = M("User");
        // 检测用户名是否冲突
        $name  =  $_REQUEST['account'];
        $result  =  $User->getByAccount($name);
        if($result) {
            $this->error(L('_USERNAME_EXIST_ERROR_'));
        }else {
            $this->success(L('_USERNAME_OK_'));
        }
    }

    // 插入数据
    public function insert() {
        // 创建数据对象
        $User	 =	 D("User");
        if(!$User->create()) {
            $this->error($User->getError());
        }else{
            // 写入帐号数据
            if($result	 =	 $User->add()) {
                $this->addRole($result);
                $this->success(L('_USER_ADD_SUCCESS_'));
            }else{
                $this->error(L('_USER_ADD_FAILURE_'));
            }
        }
    }

    protected function addRole($userId) {
        //新增用户自动加入相应权限组
        $RoleUser = M("RoleUser");
        $RoleUser->user_id	=	$userId;
        // 默认加入网站编辑组
        $RoleUser->role_id	=	3;
        $RoleUser->add();
    }

    //重置密码
    public function resetPwd() {
        $id  =  $_POST['id'];
        $password = $_POST['password'];
        if(''== trim($password)) {
            $this->error(L('_PASSWORD_NOT_EMPTY_'));
        }
        $User = M('User');
        $User->password	=	md5($password);
        $User->id			=	$id;
        $result	=	$User->save();
        if(false !== $result) {
            $this->success(L('_PASSWORD_RESET_TO_')."$password");
        }else {
            $this->error(L('_PASSWORD_RESET_FAILURE_'));
        }
    }
}