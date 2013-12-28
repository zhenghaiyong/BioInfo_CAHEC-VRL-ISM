<?php
class QueryAction extends CommonAction {
	public function index() {
		// family
		$BioDB = M('biodatabase',null,'DB_VRL');
		$family = $BioDB->field('biodatabase_id,name')->select();
		$this->assign("family",$family);
		unset($condition);
		$this->display();
	}
	public function displaySearch(){
		$condition['biodatabase_id'] = $_GET['family_id'];
		$field = $_GET['id'];
		$BioEntry = M('bioentry',null,'DB_VRL');
		$condition['is_usable'] = 'Y';
		$list = data_filter($BioEntry->Distinct(true)->where($condition)->field($field)->select());
		$this->ajaxReturn($list,"success",'1');
	}
	public function geneSelect(){
		$condition['biodatabase_id'] = $_GET['family_id'];
		$Gene = M('gene',null,'DB_VRL');
		$gene = $Gene->field('gene_id,name,parent_id,level,left_value,right_value')->where($condition)->order('level')->select();
		$this->ajaxReturn($gene,"success",'1');
	}	
	public function result(){
		$query_fields = explode("||",$_POST['post_data']);
	//0=>family 1=>subfamily 2=>genus 3=>species 4=>virus_name 5=>host 6=>typeA 7=>typeB 8=>subType 9=>subsubType 10=>subsubsubType 11=>gene 12=>country 13=>from 14=>to 15=>flen 16=>tlen
		$BioEntry = M('bioentry',null,'DB_VRL');
		$sql = "SELECT count(*) FROM bioentry LEFT JOIN biosequence ON bioentry.bioentry_id = biosequence.bioentry_id WHERE is_usable = 'Y'";
		if($query_fields[0] !='0'){
			$sql .= " AND biodatabase_id IN (".$query_fields[0].")";
		}
		if($query_fields[1] != '0'){
			$subfamily = convertString($query_fields[1]);
			$sql .= " AND bioentry.subfamily IN (".$subfamily.")";
		}
		if($query_fields[2] != '0'){
			$genus = convertString($query_fields[2]);
			$sql .= " AND bioentry.genus IN (".$genus.")";
		}
		if($query_fields[3] != '0'){
			$species = convertString($query_fields[3]);
			$sql .= " AND bioentry.species IN (".$species.")";
		}
		if($query_fields[4] != '0'){
			$virus_name = convertString($query_fields[4]);
			$sql .= " AND bioentry.virus_name IN (".$virus_name.")";
		}
		if($query_fields[5] != '0'){
			$host = convertString($query_fields[5]);
			$sql .= " AND bioentry.host IN (".$host.")";
		}
		if($query_fields[6] != '0'){
			$type = convertString($query_fields[6]);
			$sql .= " AND bioentry.typeA = ".$type;
		}
		if($query_fields[7] != '0'){
			$type = convertString($query_fields[7]);
			$sql .= " AND bioentry.typeB = ".$type;
		}
		if($query_fields[8] != '0'){
			$subtype = convertString($query_fields[8]);
			$sql .= " AND bioentry.subtype = ".$subtype;
		}
		if($query_fields[9] != '0'){
			$subsubtype = convertString($query_fields[9]);
			$sql .= " AND bioentry.subsubtype = ".$subsubtype;
		}
		if($query_fields[10] != '0'){
			$subsubsubtype = convertString($query_fields[10]);
			$sql .= " AND bioentry.subsubsubtype = ".$subsubsubtype;
		}
		if($query_fields[11] != '0'){
			$sql .= " AND (".analysis_gene($query_fields[11]).")";
		}
		if($query_fields[12] != '0'){
			$country = convertString($query_fields[12]);
			$sql .= " AND bioentry.isolation_country IN (".$country.")";
		}
		if($query_fields[13] != '0'){
			$isolation_year = substr($query_fields[13],0,4);
			$sql .= " AND bioentry.isolation_year >= ".$isolation_year;
		}
		if($query_fields[14] != '0'){
			$isolation_year = substr($query_fields[14],0,4);
			$sql .= " AND bioentry.isolation_year <= ".$isolation_year;
		}
		if($query_fields[15] != 0){
			$sql .=  " AND biosequence.length >= ".$query_fields[15];
		}
		if($query_fields[16] != 0){
			$sql .=  " AND biosequence.length <= ".$query_fields[16];
		}
		$show_bioentry_count = $BioEntry->query($sql);
		$this->assign('post_data',$_POST['post_data']);
		$this->assign('show_bioentry_count',$show_bioentry_count[0]['count(*)']);
		$this->display();
	}

	public function result_ajax() {
		$query_fields = explode("||",$_POST['post_data']);
		$from = 0;
		$limit = 0;
		$orderby = $_POST['orderby'];
		$order_line = $_POST['order_line'];
		$BioEntry = M('bioentry',null,'DB_VRL');
		$sql = "SELECT bioentry.bioentry_id,B.value AS sequence_version,bioentry.accession,bioentry.subfamily,bioentry.genus,bioentry.species,bioentry.virus_name,bioentry.isolation_year,bioentry.isolation_country,bioentry.host,bioentry.typeA,bioentry.typeB,bioentry.subtype,bioentry.subsubtype,bioentry.subsubsubtype,biosequence.length,bioentry.gene FROM bioentry LEFT JOIN biosequence ON bioentry.bioentry_id = biosequence.bioentry_id LEFT JOIN bioentry_qualifier_value as B ON bioentry.bioentry_id =  B.bioentry_id WHERE bioentry.is_usable = 'Y' AND  B.term_id IN (SELECT term_id FROM term WHERE term.name='sequence_version')";
		if($query_fields[0] !='0'){
			$sql .= " AND biodatabase_id IN (".$query_fields[0].")";
		}
		if($query_fields[1] != '0'){
			$subfamily = convertString($query_fields[1]);
			$sql .= " AND bioentry.subfamily IN (".$subfamily.")";
		}
		if($query_fields[2] != '0'){
			$genus = convertString($query_fields[2]);
			$sql .= " AND bioentry.genus IN (".$genus.")";
		}
		if($query_fields[3] != '0'){
			$species = convertString($query_fields[3]);
			$sql .= " AND bioentry.species IN (".$species.")";
		}
		if($query_fields[4] != '0'){
			$virus_name = convertString($query_fields[4]);
			$sql .= " AND bioentry.virus_name IN (".$virus_name.")";
		}
		if($query_fields[5] != '0'){
			$host = convertString($query_fields[5]);
			$sql .= " AND bioentry.host IN (".$host.")";
		}
		if($query_fields[6] != '0'){
			$type = convertString($query_fields[6]);
			$sql .= " AND bioentry.typeA = ".$type;
		}
		if($query_fields[7] != '0'){
			$type = convertString($query_fields[7]);
			$sql .= " AND bioentry.typeB = ".$type;
		}
		if($query_fields[8] != '0'){
			$subtype = convertString($query_fields[8]);
			$sql .= " AND bioentry.subtype = ".$subtype;
		}
		if($query_fields[9] != '0'){
			$subsubtype = convertString($query_fields[9]);
			$sql .= " AND bioentry.subsubtype = ".$subsubtype;
		}
		if($query_fields[10] != '0'){
			$subsubsubtype = convertString($query_fields[10]);
			$sql .= " AND bioentry.subsubsubtype = ".$subsubsubtype;
		}
		if($query_fields[11] != '0'){
			$sql .= " AND (".analysis_gene($query_fields[11]).")";
		}
		if($query_fields[12] != '0'){
			$country = convertString($query_fields[12]);
			$sql .= " AND bioentry.isolation_country IN (".$country.")";
		}
		if($query_fields[13] != '0'){
			$isolation_year = substr($query_fields[13],0,4);
			$sql .= " AND bioentry.isolation_year >= ".$isolation_year;
		}
		if($query_fields[14] != '0'){
			$isolation_year = substr($query_fields[14],0,4);
			$sql .= " AND bioentry.isolation_year <= ".$isolation_year;
		}
		if($query_fields[15] != 0){
			$sql .=  " AND biosequence.length >= ".$query_fields[15];
		}
		if($query_fields[16] != 0){
			$sql .=  " AND biosequence.length <= ".$query_fields[16];
		}
		switch($orderby){
			case "length":
				$sql .= " ORDER BY biosequence.".$orderby." ".$order_line;
				break;
			case "gene":
				$sql .= " ORDER BY ".$orderby." ".$order_line;
				break;
			case "name":
				$sql .= " ORDER BY ".$orderby." ".$order_line;
				break;
			default:
				$sql .= " ORDER BY bioentry.".$orderby." ".$order_line;
				break;
		}
		$show_bioentry_info = $BioEntry->query($sql);
		$show_bioentry_info = changeDisplayCountry($show_bioentry_info);
		$this->ajaxReturn($show_bioentry_info,"success",'1');
	}

	public function downloadQuery(){
		$str = '';
		$query_field = '';  //store download field
		$line_feed = "\r\n";
		$check_list = $_POST['check_list'];
		$format = $_POST['format'];
		$orderby = $_POST['order'];
		$order_line = $_POST['order_line'];
		$field_all = $_POST['defline'];
		$field_separate_array = explode('}',$field_all);
		$i = 0;
		for($m=0;$m<count($field_separate_array)-1;$m++){
			$field_separate = explode('{',$field_separate_array[$m]);
			$field[$i] = isset($field_separate[1])?$field_separate[1]:0;
			$separate[$i] = isset($field_separate[0])?$field_separate[0]:0;
			$i++;
		}
		$separate[$i] = $field_separate_array[count($field_separate_array)-1];
		foreach($field as $val){
			switch ($val){
				case 'gi':
					$query_field .= "bioentry.bioentry_id,";
					break;
				case 'accession':
					$query_field .= "bioentry.accession,";
					break;
				case 'subfamily':
					$query_field .= "bioentry.subfamily,";
					break;
				case 'genus':
					$query_field .= "bioentry.genus,";
					break;
				case 'species':
					$query_field .= "bioentry.species,";
					break;	
				case 'virus_name':
					$query_field .= "bioentry.virus_name,";
					break;	
				case 'year':
					$query_field .= "bioentry.isolation_year,";
					break;
				case 'country':
					$query_field .= "bioentry.isolation_country,";
					break;	
				case 'host':
					$query_field .= "bioentry.host,";
					break;	
				case 'typeA':
					$query_field .= "bioentry.typeA,";
					break;	
				case 'typeB':
					$query_field .= "bioentry.host,";
					break;	
				case 'subtype':
					$query_field .= "bioentry.subtype,";
					break;	
				case 'subsubtype':
					$query_field .= "bioentry.subsubtype,";
					break;	
				case 'subsubsubtype':
					$query_field .= "bioentry.subsubsubtype,";
					break;	
				case 'gene':
					$query_field .= "bioentry.gene,";
					break;
				case 'length':
					$query_field .= "biosequence.length,";
					break;
				default:
					break;
			}
		}
		$query_field .= "biosequence.seq";
		$filename = date("y-m-d-H-i-s").rand();
		$filepath = "./Public/Download/";
		$BioEntry = M('bioentry',null,'DB_VRL');
		$sql = "SELECT ".$query_field." FROM bioentry LEFT JOIN biosequence ON bioentry.bioentry_id = biosequence.bioentry_id WHERE bioentry.bioentry_id IN (" .$check_list. ") AND bioentry.is_usable = 'Y'";
		switch($orderby){
			case "length":
				$sql .= " ORDER BY biosequence.".$orderby." ".$order_line;
				break;
			case "gene":
				$sql .= " ORDER BY ".$orderby." ".$order_line;
				break;
			case "name":
				$sql .= " ORDER BY ".$orderby." ".$order_line;
				break;
			default:
				$sql .= " ORDER BY bioentry.".$orderby." ".$order_line;
				break;
		}
		$show_bioentry_info = $BioEntry->query($sql);
		$show_bioentry_info = changeDisplayCountry($show_bioentry_info);
		switch ($format){
			case 'fP':
				$filename = $filename.".fa";
				$fullpath = $filepath.$filename;
				$fp=fopen($fullpath,"w+");
				foreach($show_bioentry_info as $k=>$v){
					$i = 0;
					foreach($v as $n=>$val){ 
						if($n == "seq"){
							$str .= $line_feed;
							for($j=70; $j<=strlen($val); $j+=70){
								$str_val .= substr($val,$j-70,70).$line_feed;
							}
							$val = $str_val.substr($val,$j-70);
						}
						$str .= $separate[$i].$val;
						$i++;
					}
					fputs($fp,$str);
					$str = "\r\n";
				}
				
				fclose($fp);
				break;
			case 'fR':
				
				break;
			case 'fN':
				
				break;
			case 'aP':
				
				break;
			case 'aN':
				
				break;
			case 'xml':
				
				break;
			case 'csv':
				
				break;
			case 'tab':
				
				break;
			default:
				break;
		}
		download_file($fullpath);
	}

}