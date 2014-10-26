<?php
namespace tm;
/**
	Array Table Model
	
*/
class ArrayTableModel extends TableModel{
	
	/*
		Pass 2d key value array and the header values
	*/
	public function __construct(Array $headers, Array $array = array()){
		parent::__construct();
		
		$offset = 0;
		
		if (count($array) > 0) {
			// Finds longest row (most elements) and checks if data is array
			foreach($array as $row) {
				if (!is_array($row)) {
					throw new Exception("Data must contain nested values of type array");
				}
				if (count($row) > $offset) {
					$offset = count($row);
				}
			}
			
			// get offset of table
			$offset = $offset - count($headers);
			if ($offset > 0) {
				// Add placeholders for the missing headers
				for($i=0;$i<$offset;$i++) {
					$headers[] = "&nbsp;";
				}
			} else if ($offset < 0) {
				// Removes unused headers
				array_splice($headers, $offset);
			}
			
			//adds all the rows to the model
			foreach($array as $row) {
				$newRow = $row;
				if (count($row) < count($headers)) {
					for($i=0;$i<(count($headers)-count($row));$i++) {
						$newRow[] = "&nbsp;";
					}
				}
				$this -> addRow($newRow);
			}
		}
		
		//finally adds each header
		foreach ($headers as $header) {
			$this -> addHeader($header);
		}
	}
	
	public function reset(){
		parent::reset();
		
	}
	
	public function doSearch($search){
		parent::doSearch($search);
		
		
	}
	
	public static function create(Array $headers, Array $array = array()){
		$model = new ArrayTableModel($headers, $array);
		
		$table = new Table($model);
		$table->drawTable();
	}
}
?>