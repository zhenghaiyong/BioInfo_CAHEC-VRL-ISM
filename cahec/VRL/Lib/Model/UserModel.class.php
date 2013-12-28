<?php
// 用户模型
class UserModel extends CommonModel {
    public $_validate	=	array(
        array('account','/^[a-z]\w{3,}$/i','Username format error'), // ZHY 多语言 帐号格式错误
        array('password','require','Please input Password'), // ZHY 多语言 密码必须
        array('nickname','require','Please input Nickname'), // ZHY 多语言 昵称必须
        array('repassword','require','Please reinput Password'), // ZHY 多语言 确认密码必须
        array('repassword','password','These Passwords don\'t match',self::EXISTS_VALIDATE,'confirm'), // ZHY 多语言 确认密码不一致
        array('account','','This Username is exist',self::EXISTS_VALIDATE,'unique',self::MODEL_INSERT), // ZHY 多语言 帐号已经存在
        );

    public $_auto		=	array(
        array('password','pwdHash',self::MODEL_BOTH,'callback'),
        array('create_time','time',self::MODEL_INSERT,'function'),
        array('update_time','time',self::MODEL_UPDATE,'function'),
        );

    protected function pwdHash() {
        if(isset($_POST['password'])) {
            return pwdHash($_POST['password']);
        }else{
            return false;
        }
    }
}