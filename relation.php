<?php
require_once("htmldata.php");
require_once("scraping.php");

class Relation{

	function CQuery($cypherStatement){
		require('neo4jphp/api_login.php');

		$cypherQuery = new Everyman\Neo4j\Cypher\Query($client, $cypherStatement);
		return $cypherQuery->getResultSet();

	}

	function relationdata($bibliography){
		$relationArray = array();
		$cypherStatement = 'match (d:Data{id:"'.$bibliography.'"}) return d';
		$resultset = Relation::CQuery($cypherStatement);

		if(count($resultset[0]['d']) > 0){
			$cypherStatement = 'match (d1:Data{id:"'.$bibliography.'"})-[]-(d2:Data) return d2';
			$Rresult = Relation::CQuery($cypherStatement);

			foreach($Rresult as $Node){
				$RNode = $Node['d2']->getProperty('id');
				$cypherStatement = 'match (d:Data{id:"'.$RNode.'"}) return d.title';
				$Tresult = Relation::CQuery($cypherStatement);

				if(count($Tresult[0]['d']) == 0){
					$set_data = array(
						//"AC" => "1",
						"FR" => "1",
						"CD" => "1",
						//"CO10" => "0",
						"RI1" => "SI",
						"SW1" => $RNode
					);
					$html = HtmlData::htmldata_get($set_data);
					$Sresult = Scraping::scraping_title($html);
					$relationArray[] = array(
						"id" => $RNode,
						"title" => $Sresult
					);
					$cypherStatement = 'match (d:Data{id:"'.$RNode.'"}) set d.title="'.$Sresult.'"';
					Relation::CQuery($cypherStatement);

				}else{

					foreach ($Tresult as $title){
						$relationArray[] = array(
							"id" => $RNode,
							"title" => $title['d1']
						);
					}
				}
			
			}
	
		}else{
			$relationArray[] = null;
		}

		return $relationArray;

	}
}

?>