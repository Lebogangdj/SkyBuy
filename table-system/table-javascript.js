/**
	Modular Table system for HTML and PHP
	//todo add more documentation here
	
	@author Dylan Vorster
*/

TM = function(){};

//!------- CONFIGURATION ---------

TM.SETTING_enginePath = 'http://'+window.location.host+'/table-engine.php';

TM.SETTING_panelClass = '.ETablePanel';

TM.processResponse = function(response){
	console.log(response);
	return response;
}

//!------------ METHODS ---------

/**
	Standard method for sending data to the table engine, and receving data
*/
TM.action = function(context,extras){
	$model = $(context).closest(TM.SETTING_panelClass);
	$.post(TM.SETTING_enginePath,$.extend({},{'TABLE_MODEL_ID':$model.data('model-id')},extras),function(data){
		var data = TM.processResponse(data);
		if(data !== false){
			$model.replaceWith(data);	
		}
	});
};

/**
	Dont call directly, is called on each checkbox on the 'onchage' event
*/
TM.select = function(context){
	if(context.checked){
		TM.action(context,{'SELECT':[context.value],'CHECKED':'YES'});
	}else{
		TM.action(context,{'SELECT':[context.value],'CHECKED':'NO'});
	}
}

/**
	Call this to check all visible checkboxes
	@param context - just pass 'this' into the function
	@param selectall - bool true to select, false to deselect
*/
TM.selectAll = function(context,selectall){
	$model = $(context).closest(TM.SETTING_panelClass);
	var checks = [];
	$model.find('input[type=checkbox]').each(function(index,vari){
		if(selectall){
			vari.checked = true;
		}else{
			vari.checked = false;	
		}
		checks[checks.length] = vari.value;
	});
	TM.action(context,{'SELECT':checks,'CHECKED':(selectall?'YES':'NO')});
}

/**
	Call this to perform a search with the data that is in the search bar
*/
TM.search = function(context){
	$ob = $(context);
	//we need to give it the listener
	if($ob.data('has-focus') === undefined){
		$ob.keypress(function(event) {
			if ( event.which == 13 ) {
				event.preventDefault();
				TM.action(context,{SEARCH:$ob.val()});
			}
			$ob.data('has-focus','yes');
		});
	}
};

/**
	Call this to print the table passed in the context
*/
TM.print = function(context){
	$model = $(context).closest(TM.SETTING_panelClass);
	var id = $model.data('model-id');
	window.open(TM.SETTING_enginePath+'?TABLE_MODEL_ID='+id,'_blank');
};

/**
	Call this to export to CSV
*/
TM.csv = function(context){
	$model = $(context).closest(TM.SETTING_panelClass);
	var id = $model.data('model-id');
	window.location =TM.SETTING_enginePath+'?TABLE_MODEL_ID='+id+"&csv=csv",'_blank';
};
