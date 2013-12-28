<?php
// 节点模型
class NodeModel extends CommonModel {
    protected $_validate	=	array(
        array('name','checkNode','This Node is exist',0,'callback'), // ZHY 多语言 节点已经存在
        );

    public function checkNode() {
        $map['name']	 =	 $_POST['name'];
        $map['pid']	=	isset($_POST['pid'])?$_POST['pid']:0;
        $map['status'] = 1;
        if(!empty($_POST['id'])) {
            $map['id']	=	array('neq',$_POST['id']);
        }
        $result	=	$this->where($map)->field('id')->find();
        if($result) {
            return false;
        }else{
            return true;
        }
    }
}