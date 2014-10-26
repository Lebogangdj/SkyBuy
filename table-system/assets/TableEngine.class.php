<?php namespace tm;


/**
 * Description of TableEngine
 *
 * @author dylanvorster
 */
class TableEngine {
	
    //put your code here
	public static function process(){
		if(isset($_GET['TABLE_MODEL_ID']) && isset($_GET['csv'])){
			$output  = '';
			$model = Table::getModel($_GET['TABLE_MODEL_ID']);
			if($model == false){
				die('could not find model');
			}
			$rows = $model->getRows();

			//headers
			$headers = $model->getHeaders();
			$tempHeaders = array();
			foreach($headers as $h){
				$tempHeaders[] = $h['name'];
			}
			$output.=implode(',',$tempHeaders).PHP_EOL;

			//normal output
			foreach($rows as $row){
				$output.='"'.implode('","', $row).'"'.PHP_EOL;
			}

			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename="CSV-Export-'.date('Y-M-d').'.csv"'); //<<< Note the " " surrounding the file name
			header('Content-Transfer-Encoding: binary');
			header('Connection: Keep-Alive');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			header('Content-Length: ' . sizeof($output));
			die($output);
		}
		
		else if(isset($_GET['TABLE_MODEL_ID'])){
			echo 
				'<style>
					.ETable{
						font-family: Arial;
						font-size: 12px;
						width: 100%;
					}
				</style>';
			$model = Table::getModel($_GET['TABLE_MODEL_ID']);
			$t = new Table($model);
			$t->showControls(false);
			$t->drawTable(true);
		}
		//!--------------------------- OUTPUT THE TABLE ----------------------

		else{
			
			$model = Table::getModel($_POST['TABLE_MODEL_ID']);

			//do a check for the table (table might not exist for some reason)
			if($model == false){
				die('Could not find table with ID: '.$_POST['TABLE_MODEL_ID']);
			}

			//load a specific page
			if(isset($_POST['PAGE_ID'])){
				$model->setPage($_POST['PAGE_ID']);
			}

			//reset the table
			if(isset($_POST['RESET'])){
				$model->reset();
			}

			if(isset($_POST['SEARCH'])){
				$model->doSearch($_POST['SEARCH']);
			}

			if(isset($_POST['LIMIT'])){
				$model->limit($_POST['LIMIT']);
				$model->setPage(1);
			}

			if(isset($_POST['SELECT'],$_POST['CHECKED'])){
				if($_POST['CHECKED'] == 'YES'){
					$model->addChecks($_POST['SELECT']);	
				}else{
					$model->removeChecks($_POST['SELECT']);
				}
			}

			if(isset($_POST['SORT_COLUMN'])){

				$current = $model->getColumnState($_POST['SORT_COLUMN']);

				//perform an automatic switch
				if($current === 'ASC'){
					$current = false;
				}else if($current === 'DESC'){
					$current = NULL;
				}else{
					$current = true;
				}
				$model->orderBy($_POST['SORT_COLUMN'],$current);
			}

			ob_start();
			$t = new Table($model);
			if(isset($_POST['HELP'])){
				echo '<div class="'.TM_CONTAINER_CLASS.'" data-model-id="'.$model->getModelID().'">';
				$t->drawControls();
				echo '<h3>Mission Control Table Help</h3>
						<p>The table system on Mission Control is a universal system that makes it possible to treat all tables with a set of common features.</p>';
				echo '</div>';
			}else{
				$t->drawTable();
			}
			$response = utf8_encode(ob_get_contents());
			ob_end_clean();
			return $response;
		}
	}
}
?>
