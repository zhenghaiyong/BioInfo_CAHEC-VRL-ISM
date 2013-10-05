<?php
class QueryAction extends CommonAction {
	public function index() {
		// family
		$BioDB = M('biodatabase',null,'DB_VRL');
		$family = $BioDB->field('name')->select();
		$this->assign("family",$family);

		$BioEntry = M('bioentry',null,'DB_VRL');
		$condition['is_usable'] = 'Y';
		// host
		$host = $BioEntry->Distinct(true)->where($condition)->field('vrl_host')->select();
		foreach($host as $k=>$v){ $host_filter[$k] = array_filter($host[$k]);}
		$host = array_filter($host_filter);
		sort($host);
		$this->assign("host",$host);
		// country
		$country = $BioEntry->Distinct(true)->where($condition)->field('isolation_country')->select();
		foreach($country as $k=>$v){ $country_filter[$k] = array_filter($country[$k]);}
		$country = array_filter($country_filter);
		sort($country);
		$this->assign("country",$country);
		//
		unset($condition);

		$this->display();
	}

	public function result() {
// 1、根据输入查询条件得到数据库查询字段。
// 2、根据数据库查询字段得到bioentry_ids。
// 3、根据bioentry_ids查询输出显示字段。

// 1 2:
// 输入查询条件全any或空时 => bioseqdbvrl->bioentry => query_bioentry_ids
// 输入查询条件除family外全any或空时 => bioseqdbvrl->bioentry|biodatabase_id => query_bioentry_ids
// 其它情况下需要判断某一个查询条件（condition）是否为any（结合family条件：非any和any）查询得到$query_bioentry_ids_by_condition（该数组count是否大于0判断非any和any）再将所有查询条件非any的查询结果根据键值求交集
// 输入查询条件family为非any和any情况通过count($query_biodatabase_ids)是否大于0来判断

		//***** 1 *****//
		$query_fields = I('post.');
		//dump($query_fields,1,'<pre>',0);

// 判断输入查询条件全any或空
		if(($query_fields['family'] == 'any') && ($query_fields['gene'] == 'any') && ($query_fields['host'] == 'any') && ($query_fields['country'] == 'any') && ($query_fields['type'] == 'any') && ($query_fields['subtype'] == 'any') && ($query_fields['subsubtype'] == 'any') && ($query_fields['subsubsubtype'] == 'any') && ($query_fields['fyear'] == '') && ($query_fields['fmonth'] == '') && ($query_fields['fday'] == '') && ($query_fields['tyear'] == '') && ($query_fields['tmonth'] == '') && ($query_fields['tday'] == '') && ($query_fields['flen'] == '') && ($query_fields['tlen'] == '')) {
			$BioEntry = M('bioentry',null,'DB_VRL');
			$condition['is_usable'] = 'Y';
			$query_bioentry_id = $BioEntry->where($condition)->field('bioentry_id')->select();
			unset($condition);
			foreach($query_bioentry_id as $k=>$v) {
				$query_bioentry_ids[] = $query_bioentry_id[$k]['bioentry_id'];
			}
			//echo 'ALL any or empty | query bioentry_ids:<br />';
			//dump($query_bioentry_ids,1,'<pre>',0);
		}
// 判断输入查询条件除family外全any或空
		elseif(($query_fields['family'] != 'any') && ($query_fields['gene'] == 'any') && ($query_fields['host'] == 'any') && ($query_fields['country'] == 'any') && ($query_fields['type'] == 'any') && ($query_fields['subtype'] == 'any') && ($query_fields['subsubtype'] == 'any') && ($query_fields['subsubsubtype'] == 'any') && ($query_fields['fyear'] == '') && ($query_fields['fmonth'] == '') && ($query_fields['fday'] == '') && ($query_fields['tyear'] == '') && ($query_fields['tmonth'] == '') && ($query_fields['tday'] == '') && ($query_fields['flen'] == '') && ($query_fields['tlen'] == '')) {
			$BioDB = M('biodatabase',null,'DB_VRL');
			$condition['name'] = $query_fields['family'];
			$query_biodatabase_id = $BioDB->where($condition)->field('biodatabase_id')->select();
			unset($condition);
			$query_biodatabase_ids[] = $query_biodatabase_id[0]['biodatabase_id'];
			$BioEntry = M('bioentry',null,'DB_VRL');
			$condition['is_usable'] = 'Y';
			$condition['biodatabase_id'] = $query_biodatabase_ids[0];
			$query_bioentry_id = $BioEntry->where($condition)->field('bioentry_id')->select();
			unset($condition);
			foreach($query_bioentry_id as $k=>$v) {
				$query_bioentry_ids[] = $query_bioentry_id[$k]['bioentry_id'];
			}
			//echo 'ALL any or empty except family | query bioentry_ids:<br />';
			//dump($query_bioentry_ids,1,'<pre>',0);
		}
// 其它情况
		else {
		// family => biodatabase->biodatabase_id
		if($query_fields['family'] != 'any') {
			$BioDB = M('biodatabase',null,'DB_VRL');
			$condition['name'] = $query_fields['family'];
			$query_biodatabase_id = $BioDB->where($condition)->field('biodatabase_id')->select();
			unset($condition);
			$query_biodatabase_ids[] = $query_biodatabase_id[0]['biodatabase_id'];
		}
		//echo 'query bioentry_biodatabase_ids:<br />';
		//dump($query_biodatabase_ids,1,'<pre>',0);
		//-----//
		// gene => gene->gene_id => bioentry_gene->gene_id
		if($query_fields['gene'] != 'any') {
			$Gene = M('gene',null,'DB_VRL');
			$condition['name'] = $query_fields['gene'];
			if(count($query_biodatabase_ids)>0) {
				$condition['biodatabase_id'] = $query_biodatabase_ids[0];
			}
			$query_gene_id = $Gene->where($condition)->field('gene_id')->select();
			unset($condition);
			//echo 'query gene_id:<br />';
			//dump($query_gene_id,1,'<pre>',0);
			$gene = $Gene->field('gene_id,name,parent_id')->select();
			//echo 'gene:<br />';
			//dump($gene,1,'<pre>',0);
			findAdjacencyListLeaves($gene,'gene_id','parent_id',$query_gene_id[0]['gene_id'],$query_gene_ids);
			//echo 'query gene_ids (all leaves of query gene_id):<br />';
			//dump($query_gene_ids,1,'<pre>',0);
			//***** 2 *****//
			$BioentryGene = M('bioentry_gene',null,'DB_VRL');
			foreach($query_gene_ids as $k=>$v) {
				$condition['gene_id'] = $v;
				$query_bioentry_ids = $BioentryGene->where($condition)->field('bioentry_id')->select();
				foreach($query_bioentry_ids as $kk=>$vv) {
					$query_bioentry_ids_by_gene[] = $query_bioentry_ids[$kk]['bioentry_id'];
				}
			}
			unset($condition);
			$query_bioentry_ids_by_gene = array_unique($query_bioentry_ids_by_gene);
			sort($query_bioentry_ids_by_gene);
			//$query_bioentry_ids_by_gene = array_flip(array_flip($query_bioentry_ids_by_gene));
		}
		//echo 'query bioentry_ids by gene:<br />';
		//dump($query_bioentry_ids_by_gene,1,'<pre>',0);

		// bioentry
		$BioEntry = M('bioentry',null,'DB_VRL');
		// host => bioentry->vrl_host
		if($query_fields['host'] != 'any') {
			$query_hosts[] = $query_fields['host'];
			//echo 'query hosts:<br />';
			//dump($query_hosts,1,'<pre>',0);
			//***** 2 *****//
			if(count($query_biodatabase_ids)>0) {
				$condition['biodatabase_id'] = $query_biodatabase_ids[0];
			}
			foreach($query_hosts as $k=>$v) {
				$condition['vrl_host'] = $v;
				$condition['is_usable'] = 'Y';
				$query_bioentry_ids = $BioEntry->where($condition)->field('bioentry_id')->select();
				foreach($query_bioentry_ids as $kk=>$vv) {
					$query_bioentry_ids_by_host[] = $query_bioentry_ids[$kk]['bioentry_id'];
				}
			}
			unset($condition);
			$query_bioentry_ids_by_host = array_unique($query_bioentry_ids_by_host);
			sort($query_bioentry_ids_by_host);
		}
		//echo 'query bioentry_ids by host:<br />';
		//dump($query_bioentry_ids_by_host,1,'<pre>',0);
		//-----//
		// country => bioentry->isolation_country
		if($query_fields['country'] != 'any') {
			$query_countries[] = $query_fields['country'];
			//echo 'query countries:<br />';
			//dump($query_countries,1,'<pre>',0);
			//***** 2 *****//
			if(count($query_biodatabase_ids)>0) {
				$condition['biodatabase_id'] = $query_biodatabase_ids[0];
			}
			foreach($query_countries as $k=>$v) {
				$condition['isolation_country'] = $v;
				$condition['is_usable'] = 'Y';
				$query_bioentry_ids = $BioEntry->where($condition)->field('bioentry_id')->select();
				foreach($query_bioentry_ids as $kk=>$vv) {
					$query_bioentry_ids_by_country[] = $query_bioentry_ids[$kk]['bioentry_id'];
				}
			}
			unset($condition);
			$query_bioentry_ids_by_country = array_unique($query_bioentry_ids_by_country);
			sort($query_bioentry_ids_by_country);
		}
		//echo 'query bioentry_ids by country:<br />';
		//dump($query_bioentry_ids_by_country,1,'<pre>',0);
		//-----//
		// biosequence->length
		if(($query_fields['flen'] != '') || ($query_fields['tlen'] != '')) {
			$flen = $query_fields['flen'];
    		$tlen = $query_fields['tlen'];
			$BioSequence = M('biosequence',null,'DB_VRL');
			if(($flen != '') && ($tlen == '')) { $map['length'] = array('egt',$flen); }
			elseif(($flen == '') && ($tlen != '')) { $map['length'] = array('elt',$tlen); }
			elseif(($flen != '') && ($tlen != '')) { $map['length'] = array(array('egt',$flen),array('elt',$tlen)); }
			$query_bioentry_ids = $BioSequence->where($map)->field('bioentry_id')->select();
			foreach($query_bioentry_ids as $kk=>$vv) {
				$query_bioentry_ids_by_length[] = $query_bioentry_ids[$kk]['bioentry_id'];
			}
			unset($map);
			$query_bioentry_ids_by_length = array_unique($query_bioentry_ids_by_length);
			sort($query_bioentry_ids_by_length);
		}
		//echo 'query bioentry_ids by length:<br />';
		//dump($query_bioentry_ids_by_length,1,'<pre>',0);
		//-----//

		// 交集
		//unset($query_bioentry_ids);
		//$query_bioentry_ids = array();
		if(count($query_bioentry_ids_by_gene)>0) {echo 'gene';
			$query_bioentry_ids = $query_bioentry_ids_by_gene;
		} elseif(count($query_bioentry_ids_by_host)>0) {
			$query_bioentry_ids = $query_bioentry_ids_by_host;
		} elseif(count($query_bioentry_ids_by_country)>0) {
			$query_bioentry_ids = $query_bioentry_ids_by_country;
		} elseif(count($query_bioentry_ids_by_length)>0) {
			$query_bioentry_ids = $query_bioentry_ids_by_length;
		}

		if(count($query_bioentry_ids_by_gene)>0) {
			$query_bioentry_ids = array_intersect($query_bioentry_ids_by_gene,$query_bioentry_ids);
		}
		if(count($query_bioentry_ids_by_host)>0) {
			$query_bioentry_ids = array_intersect($query_bioentry_ids_by_host,$query_bioentry_ids);
		}
		if(count($query_bioentry_ids_by_country)>0) {
			$query_bioentry_ids = array_intersect($query_bioentry_ids_by_country,$query_bioentry_ids);
		}
		if(count($query_bioentry_ids_by_length)>0) {
			$query_bioentry_ids = array_intersect($query_bioentry_ids_by_length,$query_bioentry_ids);
		}
		sort($query_bioentry_ids);

		//echo 'query bioentry_ids:<br />';
		//dump($query_bioentry_ids,1,'<pre>',0);
		//echo 'THE END<br />';
		} // 判断结束

		//***** 3 *****//
		$query_bioentry_count = count($query_bioentry_ids);
		if($query_bioentry_count>0) {
			$BioEntry = M('bioentry',null,'DB_VRL');
			$condition['is_usable'] = 'Y';
			$BioSequence = M('biosequence',null,'DB_VRL');
			$query = D('query');
			foreach($query_bioentry_ids as $k=>$v) {
				$condition['bioentry_id'] = $v;
				// bioentry
				$query_info_bioentry = $BioEntry->where($condition)->field('accession,isolation_year,isolation_country,vrl_host,vrl_type,vrl_subtype,vrl_subsubtype,vrl_subsubsubtype')->select();
				$show_bioentry_info[$k] = $query_info_bioentry[0];
				//echo 'show info from bioentry:<br />';
				//dump($show_bioentry_info,1,'<pre>',0);
				//-----//
				// biosequence
				$query_info_biosequence = $BioSequence->where($condition)->field('length')->select();
				$show_bioentry_info[$k] = $show_bioentry_info[$k] + $query_info_biosequence[0];
 				//echo 'show info from biosequence:<br />';
				//dump($show_bioentry_info,1,'<pre>',0);
				//-----//
				// bioentry_gene => gene
				$query_info_gene = $query->getGeneName($v);//$v 3
				unset($query_info_gene_str);
				if(count($query_info_gene)>0) {
					foreach($query_info_gene as $kk=>$vv) {
						if($query_info_gene[$kk]['name'] == 'Others') { unset($query_info_gene[$kk]); }
						else { $query_info_gene_str = $query_info_gene_str.'-'.$query_info_gene[$kk]['name']; }
					}
				$query_info_gene_str = trim($query_info_gene_str,'-');
				//echo $query_info_gene_str; echo '<br />';
				}
				//echo 'query info gene:<br />';
				//dump($query_info_gene,1,'<pre>',0);
				$show_bioentry_info[$k]['gene'] = $query_info_gene_str;
 				//echo 'show info from gene:<br />';
				//dump($show_bioentry_info,1,'<pre>',0);
				//-----//
				// bioentry_qualifier_value->source<-term
				$query_info_source = $query->getVirusName($v);
				if(count($query_info_source)>0) {
					$show_bioentry_info[$k]['name'] = $query_info_source[0]['value'];
				}
				//echo 'query info source:<br />';
				//dump($query_info_source,1,'<pre>',0);
 				//echo 'show info from source:<br />';
				//dump($show_bioentry_info,1,'<pre>',0);
				//-----//
				// bioentry_qualifier_value->sequence_version<-term
				$query_info_version = $query->getSequenceVersion($v);
				if(count($query_info_version)>0) {
					$show_bioentry_info[$k]['dotversion'] = '.'.$query_info_version[0]['value'];
				}
				//echo 'query info version:<br />';
				//dump($query_info_version,1,'<pre>',0);
 				//echo 'show info from version:<br />';
				//dump($show_bioentry_info,1,'<pre>',0);
			}
			//echo 'show bioentry info:<br />';
			//dump($show_bioentry_info,1,'<pre>',0);
			//
			unset($condition);
		} //查询结果不为空的情况结束

		$show_bioentry_count = count($show_bioentry_info);
		$this->assign("show_bioentry_count",$show_bioentry_count);
		$this->assign("show_bioentry_info",$show_bioentry_info);

		// 分页显示
		// ajax分页：http://www.thinkphp.cn/extend/246.html
/*
		import("@.ORG.Util.Page");//导入分页类
		$Page = new Page($show_bioentry_count,4);//实例化分页类 传入总记录数和每页显示的记录数
		$list = array_slice($show_bioentry_info,$Page->firstRow,$Page->listRows);//在数组中根据条件取出一段值
		$show = $Page->show();//分页显示输出
		$this->assign('page',$show);//赋值分页输出

		$this->assign("show_bioentry_info",$list);//赋值数据集
*/
		//die;
		$this->display();//输出模板
	}

}