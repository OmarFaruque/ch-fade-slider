<?php
/*
* Plugin Name: CH Fade Slider
* Author: Omar Faruque
* Author URL: http://aboutdhaka.com/
* Description: This plugin for symple admin panel like logo, social links, custom css, custom js etc.
* Version: 1.0
* Licence: CH_FADE_SLIDER
* Text Domain: ch_fade_slider
*/
// Admin Theme Function
// ‍Admin file include
require_once('ch_admin.php');
if(!function_exists('ch_fade_slider_front')){
function ch_fade_slider_front($atts){
global $slider;
$ch_sliders = $slider->get_ch_slider_items($atts['id'], true);
?>
<div class="bannercontainer">
	<div class="banner">
		<ul>
			<!-- Slide 1 -->
			<?php
				for($ch_c = 0; count($ch_sliders) > $ch_c; $ch_c++):
					$alt = get_post_meta($ch_sliders[$ch_c]->item_img, '_wp_attachment_image_alt', true);
			?>
			<li data-transition="fade" data-slotamount="7" data-masterspeed="1500" >
				<!-- Main Image -->
				<img src="<?= wp_get_attachment_url( $ch_sliders[$ch_c]->item_img ); ?>" style="opacity:0;" alt="<?= $alt; ?>"  data-bgfit="cover" data-bgposition="left bottom" data-bgrepeat="no-repeat">
				<!-- Layers -->
				<!-- Layer 1 -->
				<div class="caption sft revolution-ch-fade-slider bigtext"
					data-x="205"
					data-y="50"
					data-speed="700"
					data-start="1700"
					data-easing="easeOutBack">
					&nbsp;<?= $ch_sliders[$ch_c]->title; ?>&nbsp;
				</div>
				<div class="caption sft"
					data-x="575"
					data-y="110"
					data-speed="1000"
					data-start="1900"
					data-easing="easeOutBack">
					<a href="<?= ( $ch_sliders[$ch_c]->button_link != '' )? $ch_sliders[$ch_c]->button_link : get_home_url( '/' ); ?>" class="button btn btn-olive ch-btn btn-lg"><?= $ch_sliders[$ch_c]->button_text; ?></a>
				</div>
			</li>
			<?php endfor; ?>
			<!-- Slide 2 -->
		</ul>
	</div>
</div>
<?php }
add_shortcode( 'chfs', 'ch_fade_slider_front' );
}
// FrontEnd Script
function of_script() {
wp_enqueue_script( 'revolution-tool',  plugin_dir_url( __FILE__ ) . 'ch_files/jquery.themepunch.tools.min.js', array(), '10.0.0', true); // revolution-took
wp_enqueue_script( 'revolution',  plugin_dir_url( __FILE__ ) . 'ch_files/jquery.themepunch.revolution.min.js', array(), '10.0.1', true); // revolution
wp_enqueue_script( 'ch-custm-script', plugin_dir_url( __FILE__ ) . 'ch_files/ch_custom_script.js', array(), '10.0.2', true); // custom script
wp_enqueue_style( 'of_style', plugin_dir_url(__FILE__) . 'ch_files/ch_style.css' ); // default style;
wp_enqueue_style( 'fontawesome', plugin_dir_url(__FILE__) . 'ch_files/font-awesome.min.css' ); // FontAwesome min Stylesheet
wp_enqueue_style( 'ch_settings', plugin_dir_url( __FILE__ ) . 'ch_files/slider-settings.css' ); // slider settings file
}
add_action('wp_enqueue_scripts', 'of_script', 20 );