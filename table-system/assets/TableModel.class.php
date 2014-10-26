<?php namespace tm;
/**
	Table model
	
	Any table model used on the system needs to extend this one by default.
	You will most likely need to overide:
	
	-> getRows()
	
	@author Dylan Vorster
	@date 2014
*/
abstract class TableModel{

	//variables
	var $headers;
	var $rows;
	var $orderByArray;
	var $offsetRow;
	var $rowLimit;
	var $modelID;
	var $totalPages;
	var $currentPage;
	var $totalRows;
	var $customHeaders;
	var $searchkeywords;
	var $search;
	var $checkBoxes;
	var $checkBoxKey;
	var $searchColumns;
	
	//global ID to identify the model
	public static $globalModelID = 0;

	public function __construct(){
		$this->modelID = self::$globalModelID;
		self::$globalModelID++;
		
		//defaults that must not be overided
		$this->headers = array();
		$this->customHeaders = false;
		$this->reset();
		$this->checkBoxKey = false;
		$this->searchColumns = array();
	}
	
	//!------------------- SEARCH FUNCTIONS ------------------
	
	public function getSearchColumns(){
		return $this->searchColumns;
	}
	
	public function getMetaData(){
		return array();
	}
	
	public function enableSearch($columns = array()){
		$args = func_get_args();
		if(count($args) > 0){
			$this->searchColumns = $args[0];
		}else{
			$this->searchColumns = func_get_args();
		}
	}
	
	public function getSearchKeywords(){
		return $this->searchKeywords;
	}
	
	public function doSearch($search){
		$search = trim($search);
		$this->searchKeywords = explode(' ', $search);
		for($i = 0;$i < count($this->searchKeywords);$i++){
			$this->searchKeywords[$i] = trim($this->searchKeywords[$i]);
			if($this->searchKeywords[$i] == ''){
				unset($this->searchKeywords[$i]);
			}
		}
		$this->setPage(1);
	}
	
	//!------------------- CHECK BOXES ------------------
	
	public function getCheckBoxKey(){
		return $this->checkBoxKey;
	}
	
	public function getCheckBoxes(){
		return $this->checkBoxes;
	}
	
	public function addChecks($checks = array()){
		foreach($checks as $check){
			if(!isset($this->checkBoxes[''.$check])){
				$this->checkBoxes[''.$check] = true;
			}
		}
	}
	
	public function removeChecks($checks = array()){
		foreach($checks as $check){
			unset($this->checkBoxes[''.$check]);			
		}
	}
	
	public function enableCheckboxes($keyName){
		$this->checkBoxKey = $keyName;
	}
	
	public function isChecked($value){
		return isset($this->checkBoxes[''.$value]);
	}
	
	public function getCheckedValues(){
		return array_keys($this->checkBoxes);
	}
	
	//!---------------------- MISC ---------------------------

	public function reset(){
		$this->orderByArray = array();
		$this->rows = array();
		$this->limit(50);
		$this->currentPage = 1;
		$this->offsetRow = 0;
		$this->searchKeywords = array();
		$this->checkBoxes = array();
	}
	
	public function orderBy($column,$ascending = NULL){

		//remove the order by
		if($ascending === NULL){
			for($i = 0; $i < count($this->orderByArray);$i++){
				if($this->orderByArray[$i]['COLUMN'] == $column){
					array_splice($this->orderByArray, $i,1);
					break;
				}
			}
		}else{
			$found = false;
			for($i = 0; $i < count($this->orderByArray);$i++){
				if($this->orderByArray[$i]['COLUMN'] == $column){
					$this->orderByArray[$i] = array('COLUMN'=>$column,'ASC'=>$ascending);
					$found = true;
				}
			}
			if(!$found){
				$this->orderByArray[] = array('COLUMN'=>$column,'ASC'=>$ascending);
			}
		}
	}

	public function getColumnState($columnID){
		for($i = 0; $i < count($this->orderByArray);$i++){
			if($this->orderByArray[$i]['COLUMN'] == $columnID){
				return $this->orderByArray[$i]['ASC']?'ASC':'DESC';
			}
		}
		return false;
	}

	public function addHeader($name,$currency = false){
		$this->headers[] = array("name"=>$name,"currency"=>$currency);
		$this->customHeaders = true;
	}
	
	public function addRow(Array $values){
		$this->rows[] = $values;
	}
	
	//----------------- PAGE METHODS ---------------------
	
	public function setPage($pageID){
		$this->currentPage = $pageID;
	}

	public function getCurrentPage(){
		return $this->currentPage;
	}

	public function getTotalPages(){
		return $this->totalPages;
	}
	
	public function limit($limit){
		$this->limit = $limit;
	}

	public function getLimit(){
		return $this->limit;
	}
	
	public function getTotalRecords(){
		return $this->totalRows;
	}
	
	//!----------------------------------------------------

	public function getHeaders(){
		return $this->headers;
	}

	public function getRows(){
		return $this->rows;
	}

	public function getModelID(){
		return $this->modelID;
	}
}

?>