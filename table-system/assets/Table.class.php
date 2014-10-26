<?php namespace tm;

/**
	Class designed to draw tables from models
	
	@author Dylan Vorster
	@date 2014
*/
class Table{

	var $model;
	var $showControls;
	var $showExtras;
	
	public function __construct(TableModel $model){
		$this->model = $model;
		$this->showControls = true;
		$this->showExtras = true;
		self::storeModel($model);
	}
	
	public function showControls($show = true){
		$this->showControls = $show;
	}
		
	public static function storeModel(TableModel $model){
		if(!isset($_SESSION[TM_SESSION_NAME])){
			$_SESSION[TM_SESSION_NAME] = array();
		}
		$_SESSION[TM_SESSION_NAME][$model->getModelID()] = $model;
	}
	
	public static function getPostModel(){
		if(isset($_POST['TABLE_MODEL_ID'])){
			return self::getModel($_POST['TABLE_MODEL_ID']);
		}
		return false;
	}
	
	public static function getModel($modelID){
		if(!isset($_SESSION[TM_SESSION_NAME])){
			return false;
		}
		if(!is_array($_SESSION[TM_SESSION_NAME])){
			return false;
		}
		if(!isset($_SESSION[TM_SESSION_NAME][$modelID])){
			return false;
		}
		return $_SESSION[TM_SESSION_NAME][$modelID];
	}
	
	public function showExtras($show = true){
		$this->showExtras = $show;
	}
	
	/*
		Draws the controls on the table models
	*/
	public function drawControls(){
	
		//get the style		
		$records = $this->model->getTotalRecords();
		if($this->showControls){
			echo '<div class="controls">';
			
			//only show this if there are checkboxes involved
			if($this->model->getCheckBoxKey() !== false){
				echo '<div class="'.TM_BUTTON_CLASS.'" onclick="'.TM_JS_NAMESPACE.'selectAll(this,true)">Tick Visible</div>';
				echo '<div class="'.TM_BUTTON_CLASS.'" onclick="'.TM_JS_NAMESPACE.'selectAll(this,false)">Untick Visible</div>';
				
				//output the hidden variable
				echo '<input type="hidden" name="TABLE_MODEL_ID" value="'.$this->model->getModelID().'">';
			}
			
			if(count($this->model->getSearchColumns()) != 0){
				if(count($this->model->getSearchKeywords()) == 0){
					$placeholder = 'search';
				}else{
					$placeholder = implode(' ', $this->model->getSearchKeywords());
				}
				echo '<input placeholder="'.$placeholder.'" type="text" onfocus="'.TM_JS_NAMESPACE.'search(this)" class="'.TM_FIELD_CLASS.'">';
			}
							
			//display the previous button
			if($this->model->getCurrentPage() > 2){
				echo '<div class="'.TM_BUTTON_CLASS.'" onclick="'.TM_JS_NAMESPACE.'action(this,{PAGE_ID:\'1\'})"><<</div>';
			}
			if($this->model->getCurrentPage() > 1){
				echo '<div class="'.TM_BUTTON_CLASS.'" onclick="'.TM_JS_NAMESPACE.'action(this,{PAGE_ID:\''.($this->model->getCurrentPage()-1).'\'})">'.($this->model->getCurrentPage()-1).'</div>';
			}
			echo '<div class="'.TM_BUTTON_CLASS.' hover">'.$this->model->getCurrentPage().'</div>';
			
			//display the next button
			if($this->model->getCurrentPage() < $this->model->getTotalPages()){
				echo '<div class="'.TM_BUTTON_CLASS.'" onclick="'.TM_JS_NAMESPACE.'action(this,{PAGE_ID:\''.($this->model->getCurrentPage()+1).'\'})">'.($this->model->getCurrentPage()+1).'</div>';
			}
			if(($this->model->getCurrentPage()+1) < $this->model->getTotalPages()){
				echo '<div class="'.TM_BUTTON_CLASS.'" onclick="'.TM_JS_NAMESPACE.'action(this,{PAGE_ID:\''.($this->model->getTotalPages()).'\'})">>></div>';
			}
			$options = array(10,50,100,200,500);
			echo '<select onchange="'.TM_JS_NAMESPACE.'action(this,{LIMIT:this.value})">';
			foreach($options as $op){
				echo '<option value="'.$op.'" '.($op==$this->model->getLimit()?'selected="selected"':'').'>'.$op.'</option>';
			}
			echo '</select>';
			
			echo '<div class="'.TM_BUTTON_CLASS.'" onclick="'.TM_JS_NAMESPACE.'action(this,{\'RESET\':\'RESET\'})">Reset</div>';
			
			if($this->showExtras){
				echo '<div class="'.TM_BUTTON_CLASS.'" onclick="'.TM_JS_NAMESPACE.'print(this)">Print</div>
					<div class="'.TM_BUTTON_CLASS.'" onclick="'.TM_JS_NAMESPACE.'csv(this)">CSV</div>
					<div class="'.TM_BUTTON_CLASS.'" onclick="'.TM_JS_NAMESPACE.'action(this,{HELP:\'HELP\'})">?</div>
					<span><b>'.$this->model->getTotalRecords().'</b> records</span>';
					
				$extraMetas = $this->model->getMetaData();
				foreach($extraMetas as $name => $extra){
					echo '<span>'.$name.' <b>'.$extra.'</b></span>';
				}
			}
			
			echo'</div>';
		}
	}

	/**
		Draws a complete table
	*/
	public function drawTable($encode = false){
			
		//output the values
		$rows = $this->model->getRows();
		
		echo '<div class="'.TM_CONTAINER_CLASS.'" data-model-id="'.$this->model->getModelID().'">';
		$this->drawControls();
		echo '<table class="'.TM_TABLE_CLASS.'">';
		
		//output the headers
		$headers = $this->model->getHeaders();
		echo '<thead>';
		$i = 0;
		if($this->model->getCheckBoxKey() !== false){
			echo '<th>[-]</th>';
		}
		foreach($headers as $header){
			$state = $this->model->getColumnState($i);
			$class = '';
			if($state !== false){
				$class= ' class="'.strtolower($state).'"';
			}
			echo '<th onclick="'.TM_JS_NAMESPACE.'action(this,{\'SORT_COLUMN\':\''.$i.'\'})" '.$class.' >'.$header['name'].'</th>';
			$i++;
		}
		echo '</thead>';
		
		if(count($rows) == 0){
			echo '
			<tr>
				<td colspan="'.count($headers).'">No Rows found</td>
			</tr>';
		}else{
			foreach($rows as $row){
				echo '<tr>';
				if($this->model->getCheckBoxKey() !== false){
					echo '<td><input onchange="'.TM_JS_NAMESPACE.'select(this)" name="checkboxes[]" value="'.$row[$this->model->getCheckBoxKey()].'" type="checkbox" '.
						($this->model->isChecked((int)$row[$this->model->getCheckBoxKey()])?'checked':'').'></td>';
				}
				foreach($row as $column){
					if(is_null($column)){
						$column = '<i>NULL</i>';
					}else if(trim($column) == ''){
						$column = '<i>NA</i>';
					}else{
						if($encode){
							$column = utf8_encode($column);
						}
					}
					echo '<td>'.$column.'</td>';
				}              
				echo '</tr>';
			}
		}
		echo '	</table>';
		//$this->drawControls();
		echo '</div>';
		
	}
	
	public function &getTableModel(){
		return $this->model;
	}
}	
?>