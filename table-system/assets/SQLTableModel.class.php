<?php namespace tm;
/**
	SQL Table Model
	
	@author Dylan Vorster
	@date 2014
*/
class SQLTableModel extends TableModel{
	
	//variables specific to the SQL model
	var $query;
	var $database;
	var $values;
	var $string_orderby;
	var $string_limit;
	var $string_query;
	var $string_totalRowsQuery;
	var $string_search;
	var $total;
	var $executionTime;
		
		
	/**
		@overide
	*/
	public function __construct($query,$database ,$values = array()){
		parent::__construct();
	
		//store the standard Query values
		$this->query = $query;
		$this->database = $database;
		$this->values = $values;
	}
	
	//!----------------------- CALL THESE TO CREATE A TABLE -------------------
	
	public static function create($query,$db,$data = array(),$searchCols = array(),$limit = 50,$controls = true){
		$model = new SQLTableModel($query,$db,$data);
		$model->enableSearch($searchCols);
		$model->limit($limit);
		$table = new Table($model);
		$table->showExtras($controls);
		$table->drawTable();
	}
	
	public static function createChecked($keyColumn,$query,$db,$data = array(),$searchCols = array(),$limit = 50,$controls = true){
		$model = new SQLTableModel($query,$db,$data);
		$model->enableSearch($searchCols);
		$model->enableCheckboxes($keyColumn);
		$model->limit($limit);
		$table = new Table($model);
		$table->showExtras($controls);
		$table->drawTable();
	}
	
	//!------------------------------------------------------------------------
	
	/**
		@overide
	*/
	public function reset(){
		parent::reset();
		$this->string_search = NULL;
		$this->string_limit = NULL;
	}
	
	private function calculateLimit(){
		//get the total number of rows that match this criteria regardless of limit
		$var = TM_PSQL_NAMESPACE;
		$rowData = $var::query($this->string_totalRowsQuery,$this->database,$this->values);
		if($row = $rowData->fetch()){
			$this->totalRows = $row['total'];
			$this->totalPages = ceil($row['total']/$this->limit);
		}
		
		$this->offsetRow = ($this->currentPage-1)*$this->limit;
		$this->string_limit = 'LIMIT '.$this->offsetRow.', '.$this->limit;
	}
	
	public function buildQuery(){	
		
		//construct the orderby array
		if(count($this->orderByArray) > 0){
			$string = array();
			
			foreach($this->orderByArray as $condition){
				$string[] = ($condition['COLUMN']+1).' '.($condition['ASC']?'ASC':'DESC');
			}
			
			$this->string_orderby = 'ORDER BY '.implode(',', $string);
		}else{
			$this->string_orderby = '';
		}
		
		//construct the new query
		$searchString = array();
		foreach($this->searchKeywords as $keyword){
			$string = array();
			foreach($this->searchColumns as $search){
				$string[] = $search.=" LIKE '".$keyword."%'";
			}
			$searchString[] = '('.implode(' OR ', $string).')';
		}
		
		
		//found a 'having' need to inject our code rather than append
		$pos = strpos($this->query, 'HAVING');
		if($pos !== false){
			$pos+=6;
			if(count($searchString) > 0){
				$newQuery = substr($this->query, 0, $pos).' '.implode(' AND ', $searchString).' AND '.substr($this->query, $pos);
			}else{
				$newQuery = $this->query;
			}		
		}
		
		//otherwise standard query so no need to inject
		else{
			//only add this conditon if we ARE searching
			if(count($searchString) > 0){
				$this->string_search = 'HAVING '.implode(' AND ', $searchString);	
			}
								
			$newQuery = $this->query.' '.$this->string_search;
		}
		
		//query for calculating the total rows counted
		$this->string_totalRowsQuery = "SELECT COUNT(1) AS 'total' FROM (".$newQuery.") as t";
		
		//get the limits
		$this->calculateLimit();
		
		//create the final query
		$this->string_query = $newQuery.' '.$this->string_orderby.' '.$this->string_limit;
	}

	/**
		@overide
	*/
	public function getRows(){
		
		$this->buildQuery();
		$var = TM_PSQL_NAMESPACE;
		//get the rows we are interested in
	  	$milliseconds = microtime(true) * 1000;
	  	$rowData = $var::query($this->string_query,$this->database,$this->values);
	  	$this->executionTime = round((microtime(true) * 1000) - $milliseconds);
		$this->rows = $rowData->fetchAll(\PDO::FETCH_ASSOC);
		if(!$this->customHeaders){
			if(count($this->rows) > 0){
				$row = $this->rows[0];
				foreach($row as $c => $v){
					$this->addHeader($c);
				}
			}
		}
		return $this->rows;
	}
	
	/**
		@overide
	*/
	public function getMetaData(){
		return array('Speed'=>$this->executionTime.' ms');
	}
}
?>