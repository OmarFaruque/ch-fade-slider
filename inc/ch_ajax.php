<?php
/*
* CH All Ajax Function's
*/


/******************************************************************
G L O B A L  V A R I A B L E   F O R  A L L  F U N C T I O N  U S E
********************************************************************/
$slider = new SLIDER;
//$slider = add_slider()


/*******************************************************************
N E W  S L I D E R  
*********************************************************************/

/*
* New Slider Add Function 
*/
if(!function_exists('ch_new_slider')) {
function ch_new_slider(){
	global $slider;
	// jQuery Post Data
	$_POST['shortcode'] = (!isset($_POST['shortcode']))?'[chfs id="'.$_POST['slider_slug'].'"]':$_POST['shortcode'];
	unset($_POST['action']);
	$settingsArray = array();
	parse_str($_POST['settings'], $settingsArray);
	$_POST['settings'] = json_encode($settingsArray);

	$formValues = $_POST;

	/* Insert Slider */
	$insert = $slider->add_slider($formValues);
	($formValues);
	if($insert){
		echo 'success';
	}else{
		echo 'fail';
	}
die();
}
// Add the ajax hooks for admin
add_action( 'wp_ajax_ch_new_slider', 'ch_new_slider' );
// Add the ajax hooks for front end
add_action( 'wp_ajax_nopriv_ch_new_slider', 'ch_new_slider' );
}


/*******************************************************************
E N D  N E W  S L I D E R  
*********************************************************************/




/*****************************************************************
S T A R T   A J A X  C  A L L  B A C K   F U N C T I O N
*******************************************************************/
if(!function_exists('ch_slider_item_insert')) {
function ch_slider_item_insert(){
	global $slider;
	// jQuery Post Data
	$formValues = array();
	parse_str($_POST['formVar'], $formValues);
	
	$insertItem = $slider->add_slider_item($formValues);

	if($insertItem){
		echo 'success'; 
	}else{
		echo 'Duplicate';
	}	
die();
}
// Add the ajax hooks for admin
add_action( 'wp_ajax_ch_slider_item_insert', 'ch_slider_item_insert' );
// Add the ajax hooks for front end
add_action( 'wp_ajax_nopriv_ch_slider_item_insert', 'ch_slider_item_insert' );
}
/*
* Delete Ajax Function
*/
if(!function_exists('ch_slider_delete_item')){
	function ch_slider_delete_item(){
		global $slider;
		$delete = $_POST['delete'];
		$action = $slider->delete_ch_slider_item($delete);
		if($action){
			echo 'success';
		}else{
			echo 'failed';
		}
		die();
	}
	add_action( 'wp_ajax_ch_slider_delete_item', 'ch_slider_delete_item' );
	
	// Add the ajax hooks for front end
	add_action( 'wp_ajax_nopriv_ch_slider_delete_item', 'ch_slider_delete_item' );
}
/*
* Edit Ajax Function
*/
if(!function_exists('ch_slider_edit')){
	function ch_slider_edit(){
		global $slider;
		unset($_POST['action']);

		$update = $slider->update_ch_slider_item($_POST);

		if($update){
			echo 'success'; 
		}else{
			echo 'failed';
		}	
		die();
	}
	add_action( 'wp_ajax_ch_slider_edit', 'ch_slider_edit' );
	
	// Add the ajax hooks for front end
	add_action( 'wp_ajax_nopriv_ch_slider_edit', 'ch_slider_edit' );
}
/*
* Shortable Update Ajax Function
*/
if(!function_exists('ch_update_shortable')) {
function ch_update_shortable(){
	
	// jQuery Post Data
	$formValues = array();
	parse_str($_POST['formVar'], $formValues);
	foreach($formValues as $k => $val){
		unset( $formValues[$k][count($val)-1] );
	}
	// Update Shorting Data
	$ex_slider_db = get_option('ch_fade_slider');
	if(!is_array($ex_slider_db)){
		add_option( 'ch_fade_slider', $formValues, '', 'yes');
	}else{
		update_option( 'ch_fade_slider', $formValues );
	}
	$afterInsert = get_option('ch_fade_slider');
	//echo json_encode($afterInsert);
	print_r($afterInsert);
	
die();
}
// Add the ajax hooks for admin
add_action( 'wp_ajax_ch_update_shortable', 'ch_update_shortable' );
// Add the ajax hooks for front end
add_action( 'wp_ajax_nopriv_ch_update_shortable', 'ch_update_shortable' );
}


/*****************************************************************
U P D A T E  M A I N  S L I D E R
********************************************************************/
if(!function_exists('ch_main_slider_update')){
	function ch_main_slider_update(){
		global $slider;
		$settingsArray = array();
		parse_str($_POST['settings'], $settingsArray);

		$update = $slider->update_main_slider($_POST['slider_name'], $_POST['slider_slug'], $_POST['slider_active'], $_POST['slider_id'], json_encode($settingsArray));
		
		if($update){
			echo 'success';
		}else{
			echo 'Update Failed.';
		}
		die();
	}
	// Add the ajax hooks for admin
	add_action( 'wp_ajax_ch_main_slider_update', 'ch_main_slider_update' );
	// Add the ajax hooks for front end
	add_action( 'wp_ajax_nopriv_ch_main_slider_update', 'ch_main_slider_update' );
}


/*****************************************************************************
D E L E T E  C H  S L I D E R  F R O M  M A I N
*****************************************************************************/

if(!function_exists('delete_ch_slider')){
	function delete_ch_slider(){
		global $slider;
		$deleteM = $slider->delete_main_slider_item($_POST['id']);

		if($deleteM){
			echo 'success';
		}else{
			echo 'failed';
		}
		
	die();
	}
	// Add the ajax hooks for admin
	add_action( 'wp_ajax_delete_ch_slider', 'delete_ch_slider' );
	// Add the ajax hooks for front end
	add_action( 'wp_ajax_nopriv_delete_ch_slider', 'delete_ch_slider' );
}



/*****************************************************************
E N D   A J A X  C  A L L  B A C K   F U N C T I O N
********************************************************************/