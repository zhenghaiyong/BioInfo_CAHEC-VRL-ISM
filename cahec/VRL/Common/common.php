<?php
// +----------------------------------------------------------------------
// | ThinkPHP
// +----------------------------------------------------------------------
// | Copyright (c) 2007 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id: common.php 2601 2012-01-15 04:59:14Z liu21st $

//公共函数
function toDate($time, $format = 'Y-m-d H:i:s') {
	if (empty ( $time )) {
		return '';
	}
	$format = str_replace ( '#', ':', $format );
	return date ($format, $time );
}

function getStatus($status, $imageShow = true) {
	switch ($status) {
		case 0 :
			$showText = L('_STATUS_LOCKED_');
			$showImg = '<IMG SRC="__PUBLIC__/Images/locked.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="'.L('_STATUS_LOCKED_').'">';
			break;
		case 2 :
			$showText = L('_STATUS_PENDING_');
			$showImg = '<IMG SRC="__PUBLIC__/Images/pending.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="'.L('_STATUS_PENDING_').'">';
			break;
		case - 1 :
			$showText = L('_STATUS_DELETE_');
			$showImg = '<IMG SRC="__PUBLIC__/Images/del.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="'.L('_STATUS_DELETE_').'">';
			break;
		case 1 :
		default :
			$showText = L('_STATUS_OK_');
			$showImg = '<IMG SRC="__PUBLIC__/Images/ok.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="'.L('_STATUS_OK_').'">';

	}
	return ($imageShow === true) ?  $showImg  : $showText;

}

function getNodeGroupName($id) {
	if (empty ( $id )) {
		return L('_NO_GROUP_');
	}
	if (isset ( $_SESSION ['nodeGroupList'] )) {
		return $_SESSION ['nodeGroupList'] [$id];
	}
	$Group = D ( "Group" );
	$list = $Group->getField ( 'id,title' );
	$_SESSION ['nodeGroupList'] = $list;
	$name = $list [$id];
	return $name;
}

function showStatus($status, $id) {
	switch ($status) {
		case 0 :
			$info = '<a href="javascript:resume(' . $id . ')">'.L('_STATUS_RESUME_').'</a>';
			break;
		case 2 :
			$info = '<a href="javascript:pass(' . $id . ')">'.L('_STATUS_PASS_').'</a>';
			break;
		case 1 :
			$info = '<a href="javascript:forbid(' . $id . ')">'.L('_STATUS_FORBID_').'</a>';
			break;
		case - 1 :
			$info = '<a href="javascript:recycle(' . $id . ')">'.L('_STATUS_RECYCLE_').'</a>';
			break;
	}
	return $info;
}


function getGroupName($id) {
	if ($id == 0) {
		return L('_NO_PARENT_GROUP_');
	}
	if ($list = F ( 'groupName' )) {
		return $list [$id];
	}
	$dao = D ( "Role" );
	$list = $dao->select( array ('field' => 'id,name' ) );
	foreach ( $list as $vo ) {
		$nameList [$vo ['id']] = $vo ['name'];
	}
	$name = $nameList [$id];
	F ( 'groupName', $nameList );
	return $name;
}

function pwdHash($password, $type = 'md5') {
	return hash ( $type, $password );
}

// ZHY 数组降维函数
function dimReduce($array) {
	static $tmp = array();
	for($i=0;$i<count($array);$i++){
		$tmp[] = $array[$i][0];
	}
	return $tmp;
}

// ZHY 查找邻接表数据叶节点
function findAdjacencyListLeaves($list, $id='id', $pid='pid', $node_id, &$leaves) {
	$is_leaf = 1;
	foreach($list as $key=>$value) {
		if($list[$key][$pid] == $node_id) {
			$is_leaf = 0;
			findAdjacencyListLeaves($list,$id,$pid,$list[$key][$id],$leaves);
		}
	}
	if($is_leaf) {
		$leaves[]=$node_id;
	}
	return $leaves;
}

function convertString($string){
	if(strpos($string,',') > -1){
		$string_1 = explode(",",$string);
		foreach($string_1 as $k=>$val){
			$string_2[$k] = "'".$val."'"; 
		}
		$string = implode(",",$string_2);
	}
	else{
		$string = "'".$string."'";
	}
	return $string;
}

//function delOthers($bioentry,$del){
function changeDisplayCountry($bioentry){
	//$bioentry_id = 0;
	foreach($bioentry as $k=>$v){
		/*if($bioentry[$k]['bioentry_id'] == $bioentry_id){
			$bioentry[$k]['gene'] = $bioentry[$k-1]['gene']. '-' .$bioentry[$k]['gene'];
			unset($bioentry[$k-1]);
			if(strpos($bioentry[$k]['gene'],$del) > -1){
				$gene_name = explode("-",$bioentry[$k]['gene']);
				foreach($gene_name as $m=>$val){
					if($val == $del){
						unset($gene_name[$m]);
					}
				}
				$bioentry[$k]['gene'] = implode('-',$gene_name);
			}
		}*/
		//$bioentry_id = $bioentry[$k]['bioentry_id'];
		if($bioentry[$k]['isolation_country'] == "Hong Kong")
			$bioentry[$k]['isolation_country'] = "China (Hong Kong)";
		if($bioentry[$k]['isolation_country'] == "Taiwan")
			$bioentry[$k]['isolation_country'] = "China (Taiwan)";
		if($bioentry[$k]['isolation_country'] == "Macao")
			$bioentry[$k]['isolation_country'] = "China (Macao)";
	}
	return $bioentry;
}

function download_file($file){
    if(is_file($file)){
        $length = filesize($file);
        $type = mime_content_type($file);
        $showname =  ltrim(strrchr($file,'/'),'/');
        header("Content-Description: File Transfer");
        header('Content-type: ' . $type);
        header('Content-Length:' . $length);
         if (preg_match('/MSIE/', $_SERVER['HTTP_USER_AGENT'])) { //for IE
             header('Content-Disposition: attachment; filename="' . rawurlencode($showname) . '"');
         } else {
             header('Content-Disposition: attachment; filename="' . $showname . '"');
         }
         readfile($file);
         exit;
     } else {
         exit('文件已被删除！');
     }
 }
function data_filter($data){
	foreach($data as $k=>$v){ $data_filter[$k] = array_filter($data[$k]); }
		$data = array_filter($data_filter);
		sort($data);
		return $data;
}
function analysis_gene($gene){
	$Gene = M('gene',null,'DB_VRL');
	$gene_new = array();
		$gene_array = explode(",",$gene);
		foreach($gene_array as $val){
			$gene_one = explode("@@",$val);
			array_push($gene_new,$gene_one[0]);
			if($gene_one[2] == 2){
				if(!strstr($gene,'@@id:'.$gene_one[1])){
					$condition['gene_id'] = $gene_one[1];
					$gene_name = $Gene->field('name')->where($condition)->select();
					if(!in_array($gene_name[0]['name'],$gene_new))
						array_push($gene_new,$gene_name[0]['name']);
				}
			}
			elseif($gene_one[2] == 3){
				if(!strstr($gene,'@@id:'.$gene_one[1])){
					$condition['gene_id'] = $gene_one[1];
					$gene_name = $Gene->field('name,parent_id')->where($condition)->select();
					if(!in_array($gene_name[0]['name'],$gene_new))
						array_push($gene_new,$gene_name[0]['name']);
					$condition['gene_id'] = $gene_name[0]['parent_id'];
					$gene_name = $Gene->field('name')->where($condition)->select();
					if(!in_array($gene_name[0]['name'],$gene_new))
						array_push($gene_new,$gene_name[0]['name']);
				}
			}
		}
		foreach($gene_new as $val){
			$gene_sql .= "bioentry.gene LIKE '%".$val."%' OR ";
		}
		$gene_sql = substr($gene_sql,0,strlen($gene_sql)-4);
	return $gene_sql;
}

// ZHY
// http://www.cnblogs.com/ybbqg/archive/2012/04/16/2452033.html
/**  According to the array paging
 *  @param array $array
 *  @param int $limit
 *  @return array
 */
/*
function arrayPage($array, $limit) {
    $count = count($array);
    if ($count) {
        import('ORG.Util.Page');//引入分页类
        $p = new Page($count, $limit);
        $data['list'] = array_slice($array, $p->firstRow, $p->listRows);
        $data['page'] = $p->show();
        return $data;
    }
}
*/

?>