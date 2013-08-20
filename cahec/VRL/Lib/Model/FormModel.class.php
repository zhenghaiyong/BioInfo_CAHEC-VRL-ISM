<?php
class FormModel extends CommonModel {
    // 自动验证设置
    protected $_validate	 =	 array(
        array('title','require','Please input Title',1), // ZHY 多语言 标题必须
        array('content','require','Please input Content'), // ZHY 多语言 内容必须
        array('title','','This Title is exist',0,'unique',self::MODEL_INSERT), // ZHY 多语言 标题已经存在
        );
    // 自动填充设置
    protected $_auto	 =	 array(
        array('status','1',self::MODEL_INSERT),
        array('create_time','time',self::MODEL_INSERT,'function'),
        );

}