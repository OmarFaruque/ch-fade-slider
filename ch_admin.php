<?php
// Admin Functions
// Admin Script
require_once('inc/ch_db.php');
function slider(){
	$sliderc = new SLIDER;
	return $sliderc;
}


function ch_admin_script() {
	if(isset($_GET['page'])){
		if($_GET['page'] == 'ch_fade_slider'){

			/*Color Picker */
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'wp-color-picker');

			/* Media Script */
			wp_enqueue_media();
			/* End Media Script */
			wp_enqueue_script('jquery-ui-sortable');
			wp_enqueue_script( 'admin_ch_script',  plugin_dir_url( __FILE__ ) . 'ch_files/admin/admin_ch_script.js');
			wp_localize_script( 'ajax_ch_slider', 'the_ajax_script', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
			wp_register_style( 'ch_wp_admin_css', plugin_dir_url(__FILE__) . 'ch_files/admin/admin_style.css', false, '1.0.0' );
			wp_enqueue_style( 'ch_wp_admin_css' );
		}
	}
	wp_enqueue_style('ch-global-css', plugin_dir_url(__FILE__ ) . 'ch_files/admin/ch_global.css');
}
add_action( 'admin_enqueue_scripts', 'ch_admin_script' );
// Admin Action
add_action( 'admin_menu', 'ch_slider_admin' );
function ch_slider_admin() {
$icon_url = plugin_dir_url( __FILE__) . 'ch_files/img/ch_icon_hover.png';
add_menu_page( 'CH Fade Slider', 'CH Fade Slider', 'manage_options', 'ch_fade_slider', 'ch_slider_option', $icon_url);
}



// Of slider Admin Option
function ch_slider_option(){

	$slider = slider();
	$of_sliders = (isset($_GET['slider']))?$slider->get_ch_slider_items($_GET['slider'], false):'';
	$icon_url_hover = plugin_dir_url( __FILE__) . 'ch_files/img/ch_icon_hover.png';
	$allItems = ($slider->get_all_slider())?$slider->get_all_slider():array();
	//	ob_start();
?>
<div id="loading" style="display: none;">
	<div class="loadingInner">
		<span class="thankYouMessage"></span>
	</div>
</div>
<div class="wrap">
	<h1 class="admintitle">Fade Slider</h1>
	<?php if(!isset($_GET['slider'])): ?> 
	<div class="one-two">
		<span id="base_url" style="display: none;" data-url="<?= admin_url('/'); ?>"></span>
		<span id="plugin_url" style="display: none;" data-url="<?= plugin_dir_url(__FILE__); ?>"></span>
		<table class="widefat fixed fadeSliderMain" cellspacing="0">
			<thead>
				<tr>
					<th>Image Sample</th>
					<th>Slider Name</th>
					<th>Slider Slug</th>
					<th>Shortcode</th>
					<th>Active</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($allItems as $slide): ?>
				<tr data-id="<?= $slide->id; ?>">
					<td><a style="background:none; padding:0px;" href="<?= admin_url( '/admin.php?page=ch_fade_slider&slider='.$slide->id, 'admin' );  ?>">
						<?php if(!empty($slider->get_slider_img_id($slide->id))): ?>
						<?= wp_get_attachment_image( $slider->get_slider_img_id($slide->id), 'thumbnail' );  ?>
						<?php else: ?>
						<img style="max-width: 100%;" src="<?= plugin_dir_url( __FILE__) . 'ch_files/img/dummy.jpg';  ?>">
						<?php endif; ?>
					</a></td>
					<td class="slider_name">
						<span class="input"><a style="background:none; padding:0px; color:#006799;" href="<?= admin_url( '/admin.php?page=ch_fade_slider&slider='.$slide->id, 'admin' );  ?>"><?= $slide->slider_name; ?></a></span>
						<span style="display: none;" class="text"><input type="text" name="slider_name" value="<?= $slide->slider_name; ?>"></span>	
					</td>
					<td class="slider_slug"><?= $slide->slider_slug; ?></td>
					<td><?= '[chfs id="'.$slide->slider_slug.'"]'; ?></td>
					<td class="slider_active">
						<span class="input"><?= ($slide->active==0)?'No':'Yes';  ?></span>
						<span class="text" style="display: none;">
							<div class="activeInputSection">
								<label><input type="radio" value="1"  <?= ($slide->active==1)?'checked="checked"':'';  ?> name="active_<?= $slide->id; ?>"> Yes</label>&nbsp;&nbsp;
								<label><input <?= ($slide->active==0)?'checked="checked"':'';  ?> type="radio" value="0" name="active_<?= $slide->id; ?>"> No</label>
							</div>
						</span>
					</td>
					<td data-name="<?= $slider->slider_name; ?>">
						<a class="delete_slide_main" href="javascript:void(0)" data-delete="<?= $slide->id; ?>">Delete</a>
						<a class="edit_slide_main" href="javascript:void(0)" 
						<?php foreach(json_decode($slide->settings) as $sk=>$stng): ?>
						data-<?= $sk; ?>="<?= $stng; ?>"	
						<?php endforeach; ?>
						data-edit="<?= $slide->id; ?>">Edit</a>
						<a style="display: none;" class="update_slide_main" href="javascript:void(0)" data-edit="<?= $slide->id; ?>">Update</a>
					</td>

				</tr>
				<?php endforeach; ?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="6" rowspan="" headers="">
						<i>Thank You for Using <b>Fade Slider</b>.&nbsp;&nbsp;&nbsp;  Mail me if you face any problem: <a class="mailme" href="mailto:ronymaha@gmail.com">ronymaha@gmail.com</a></i>
					</td>
				</tr>
			</tfoot>
		</table>
		<div class="addSlider">
				<a href="#" onClick="return false" alt="f502" class="dashicons dashicons-plus-alt pull-right"></a>
		</div>
		
		<div id="newSlider" class="newS">
				<div class="newSliderWrap">
					<form method="POST" action="" id="new_ch_fade_slider">
						<div class="title">
							<label for="s_name">Slider Name: </label>
							<input type="text" name="s_name" id="s_name" value="" />
						</div>
						<div class="buttonText">
							<label for="s_slug">Slider Slug:</label>
							<input type="text" readonly name="s_slug" id="s_slug" value="" />
						</div>
						<div class="activeStatus">
							<label>Active:</label>
							<div class="activeInputSection">
								<label><input type="radio" value="1" checked="checked" name="active"> Yes</label>&nbsp;&nbsp;
								<label><input type="radio" value="0" name="active"> No</label>
							</div>
						</div>
						<div class="submit_new_slider">
							<input onClick="return false;" type="submit" class="button right button-primary" value="Save" />
						</div>
						<div class="errorShow"></div>
					</form>
				</div>
		</div>
	</div>
	<div class="two-one instruciton landing" id="ch_main_sidebar">
	<!-- Add Dynamic From via jQuery -->
	<div class="editS" style="display: none;">
		
	</div>
	<div class="note">
	<div class="inst headerNotes">
		<h4>Note's</h4>
	</div>
		<div class="inst">
				<ul>
					<li><b>Shortcode: </b> If you insert code to your php file you can copy past this: <i>&lt;?php echo do_shortcode('[chfs id="id"]'); ?&gt;</i><br/><br/>If you like to add Slider from admin just copy past this code: "[chfs id="id"]" (don't copy block quote)</li>
				</ul>
			</div>
	</div>
	</div>
	<?php else: ?>
	<h2 class="slider_name">Slider Name: <?= $slider->get_slider_name($_GET['slider']);  ?></h2>
		<div class="one-two">
			<table class="widefat items fixed" cellspacing="0">
				<thead>
					<tr>
						<th>Slider Image</th>
						<th>Slider Title</th>
						<th>Slider Button Text</th>
						<th>Slider Button Link</th>
						<th>Visiable</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
					for($c = 0; $c < count($of_sliders); $c++): ?>
					<tr>
						<td>
							<span class="img_prev"><img class="sliderImg" src="<?= wp_get_attachment_url($of_sliders[$c]->item_img); ?>"/></span>
							<span class="hidden">
								<input required type="text" name="ch_slider_input_img" value="<?= wp_get_attachment_url($of_sliders[$c]->item_img); ?>">
								<input type="hidden" name="ch_slider_input_img_id" value="<?= $of_sliders[$c]->item_img; ?>" placeholder="">
							</span>
						</td>
						<td>
							<span class="text"><?= htmlentities($of_sliders[$c]->title); ?></span>
							<span class="input hidden">
								<input type="text" name="s_title" value="<?= htmlentities($of_sliders[$c]->title); ?>">
							</span>
						</td>
						<td>
							<span class="text"><?= $of_sliders[$c]->button_text; ?></span>
							<span class="input hidden">
								<input type="text" name="s_button_text" value="<?= $of_sliders[$c]->button_text; ?>">
							</span>
						</td>
						<td>
							<span class="text"><?= $of_sliders[$c]->button_link; ?></span>
							<span class="input hidden">
								<input type="text" name="s_button_link" value="<?= $of_sliders[$c]->button_link; ?>">
							</span>
						</td>
						<td>
							<a href="javascript:void(0)" data-endesable="<?= $c; ?>" class="readonly enableDesable"><?= ($of_sliders[$c]->active == 0)?'<span class="desable">Desable</span>':'<span class="enable">Enable</span>';  ?></a>
							<span class="input">
								<input type="hidden" name="s_visiable" value="<?= $of_sliders[$c]->active; ?>" >
							</span>
						</td>
						<td>
							<a class="edit_slide" data-slider_id="<?= $of_sliders[$c]->slider_id ?>" data-edit="<?= $of_sliders[$c]->id; ?>"  href="javascript:void(0)" >
								<div alt="f464" class="dashicons dashicons-edit"></div>
							</a>
							<a class="delete_slide" data-delete="<?= $of_sliders[$c]->id; ?>" href="javascript:void(0)">
								<div alt="f182" class="dashicons dashicons-trash"></div>
							</a>
						</td>
					</tr>
					<?php endfor;
					?>
					
				</tbody>
				<tfoot>
				<tr>
					<td colspan="6" rowspan="" headers="">
						<i>Thank You for Using <b>Fade Slider</b>.&nbsp;&nbsp;&nbsp;  Mail me if you face any problem: <a class="mailme" href="mailto:ronymaha@gmail.com">ronymaha@gmail.com</a></i>
					</td>
				</tr>
				</tfoot>
			</table>
			<div class="addSlider">
				<a href="#" onClick="return false" alt="f502" class="dashicons dashicons-plus-alt pull-right"></a>
			</div>
			<div class="backtoHome">
				<a href="<?= admin_url('/admin.php?page=ch_fade_slider');  ?>" type="button" class="button button-primary"><div alt="f341" class="dashicons dashicons-arrow-left-alt2"></div>Back to CH Slider</a>
			</div>
			<div class="message ch_Message" style="display: none;"></div>
			<div id="newSlider">
				<form method="POST" action="" id="ch_fade_slider">
				<div class="newSliderWrap">
					<input type="hidden" name="slider_id" value="<?= $_GET['slider']; ?>">
					<div class="title">
						<label for="s_title">Slider Title: </label>
						<input type="text" name="s_title" id="s_title" value="" />
					</div>
					<div class="buttonText">
						<label for="s_button_text">Button Label:</label>
						<input type="text" name="s_button_text" id="s_button_text" value="" />
					</div>
					<div class="button_link">
						<lable for="s_button_link">Button Link: </lable>
						<input type="text" name="s_button_link" id="s_button_link" value="" />
					</div>
					<div class="visiable">
						<p>Show Slider: </p>
						<lable><input type="radio" value="0" name="s_visiable" />No &nbsp;</lable>
						<lable><input type="radio" value="1" checked="checked" name="s_visiable" />Yes</lable>
					</div>
					<div class="uploader">
						<label for="ch_slider_input_img">Slider Image: </label>
						<input required id="ch_slider_input_img" name="ch_slider_input_img" type="text" value="" />
						<input type="hidden" id="ch_slider_input_img_id" name="ch_slider_input_img_id" value="" >
						<div class="previewch_slider"><img src="" /></div>
						<input id="ch_fade_slider_upload" class="button" name="ch_fade_slider_upload" type="button" value="Upload Image" />
					</div>
					<div class="submit_ch_sluder">
						<input onClick="return false;" type="submit" id="ch_slider_submit" name="ch_slider_submit" class="button" value="Save" />
					</div>
					<div class="errorShow"></div>
				</div>
				<!-- -->
				</form>
			</div>
		</div>
		<div class="two-one instruciton">
			<div class="inst">
				<ul>
					<li><b>Add New Slider Item: </b>Click ( <span class="dashicons dashicons-plus-alt"></span> ) from bottm right corner.</li>
					<li><b>Edit Slider Item: </b>Click ( <span class="dashicons dashicons-edit"></span> ) from each slider item.</li>
					<li><b>Delete Slider Item: </b> Click ( <span class="dashicons dashicons-trash"></span> ) from each slider item.</li>
					<li><b>Change Order: </b> Drag & Drop each item for re-order / change order.</li>
				</ul>
			</div>
		</div>

	<?php endif; ?>
</div>
<?php
//ob_end_clean();
}

/*****************************
END of_slider_option
***********************************/
require_once('inc/ch_ajax.php');
?>