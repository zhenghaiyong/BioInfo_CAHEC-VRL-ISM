<?php  
class QueryModel extends CommonModel {
	function getGeneName($bioentryId) {
		$rs = $this->db(2,"DB_VRL")->query('select b.name from bioentry_gene as a, gene as b where b.gene_id=a.gene_id and a.bioentry_id='.$bioentryId.' ');
		return $rs;
	}

	function getVirusName($bioentryId) {
		$rs = $this->db(2,"DB_VRL")->query('select b.value from term as a, bioentry_qualifier_value as b where b.term_id=a.term_id and a.name="source" and b.bioentry_id='.$bioentryId.' ');
		return $rs;
	}

	function getSequenceVersion($bioentryId) {
		$rs = $this->db(2,"DB_VRL")->query('select b.value from term as a, bioentry_qualifier_value as b where b.term_id=a.term_id and a.name="sequence_version" and b.bioentry_id='.$bioentryId.' ');
		return $rs;
	}
}
