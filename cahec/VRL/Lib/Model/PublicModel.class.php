<?php 
class PublicModel extends CommonModel {
	function getNodeList($userId) {
		$rs = $this->db->query('select b.node_id from '.$this->tablePrefix.'role_user as a ,'.$this->tablePrefix.'access as b where b.role_id=a.role_id and a.user_id='.$userId.' ');
		return $rs;
	}

	function getGroupList($nodeId) {
		$rs = $this->db->query('select b.id,b.title from '.$this->tablePrefix.'node as a ,'.$this->tablePrefix.'group as b where b.id=a.group_id and b.status=1 and a.id='.$nodeId.' ');
		return $rs;
	}
}
