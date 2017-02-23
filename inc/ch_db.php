<?php
/*
* alldb function
*/

class SLIDER {
	function __construct(){
        global $wpdb;
        $this->db 				= $wpdb;
        $this->charset 			= $wpdb->get_charset_collate();
        $this->ch_table 		= $wpdb->prefix . "ch_slider"; 
        $this->ch_item_table 	= $wpdb->prefix . "ch_slider_items";
    }     

	protected function create_table(){
		$sql = "CREATE TABLE $this->ch_table (
		  id bigint(9) NOT NULL AUTO_INCREMENT,
		  slider_name varchar(191) NOT NULL,
		  slider_slug varchar(191) NOT NULL,
		  shortcode varchar(191) NOT NULL,
		  settings text NOT NULL,
		  active int(5) DEFAULT 1 NOT NULL,
		  UNIQUE KEY id (id)
		) $this->charset;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}


	protected function create_item_table(){
		if($this->db->get_var("SHOW TABLES LIKE '$this->ch_item_table'") != $this->ch_item_table) {
			$sqlI = "CREATE TABLE $this->ch_item_table (
			  id bigint(9) NOT NULL AUTO_INCREMENT,
			  slider_id bigint(9) NOT NULL,
			  item_img int(9) NOT NULL,
			  title	 varchar(291) NOT NULL,
			  button_text varchar(191) NOT NULL,
			  button_link varchar(191) NOT NULL,
			  active int(5) DEFAULT 1 NOT NULL,
			  UNIQUE KEY id (id)
			) $this->charset;";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sqlI );
		}
	}

	public function get_ch_slider($sliderSlug){  
		$gSliderDB = $this->db->get_row( "SELECT * FROM $this->ch_table WHERE slider_slug = '".$sliderSlug."'", OBJECT );
		return $gSliderDB->slider_slug;
	}

	public function get_all_slider(){
		$this->create_table();
		$gallSliderDB = $this->db->get_results( "SELECT * FROM $this->ch_table", OBJECT );
		return $gallSliderDB;
	}


	/*
	* Add New Slider 
	*/
	public function add_slider($posts){
			$this->create_table();
			// Insert Process
			$insertNewS = $this->db->insert(
				$this->ch_table,
				array(
					'slider_name' 	=> $posts['slider_name'],
					'slider_slug'	=> $posts['slider_slug'],
					'shortcode'		=> $posts['shortcode'],
					'settings'		=> $posts['settings'],
					'active'		=> $posts['active']

				),
				array(
					'%s',
					'%s',
					'%s',
					'%s',
					'%d'
				)
			);

			if($insertNewS){
				return true;
			}else{
				return false;
			}
	}

	/*
	* Update Main Slider Item
	*/
	public function update_main_slider($slider_name, $slider_slug, $slider_active, $id, $settings){
		$update_main = $this->db->query($this->db->prepare("UPDATE $this->ch_table SET slider_name='".$slider_name."', slider_slug='".$slider_slug."', active='".$slider_active."', settings='".$settings."' WHERE id='".$id."'"));
		if($update_main){
			return true;
		}else{
			return false;
		}
	}


	/* G E T   S L I D E R   N A M E */
	public function get_slider_name($id){
		$gSliderDB = $this->db->get_row( "SELECT slider_name FROM $this->ch_table WHERE id = '".$id."'", OBJECT );
		return $gSliderDB->slider_name;
	}




	/****************************************************
	Add New Slider individual Items
	*******************************************************/
	public function add_slider_item($posts){
		$this->create_item_table();
			/* 
			* Insert Process for slider item using single slider id
			*/

			$existingData = $this->db->get_row( "SELECT item_img FROM $this->ch_item_table WHERE item_img = '".$posts['ch_slider_input_img_id']."' AND slider_id='".$posts['slider_id']."'", OBJECT );
			if(empty($existingData->item_img)){
				$insertNewI = $this->db->insert(
					$this->ch_item_table,
					array(
						'slider_id'		=> $posts['slider_id'],
						'item_img' 		=> $posts['ch_slider_input_img_id'],
						'title'			=> $posts['s_title'],
						'button_text'	=> $posts['s_button_text'],
						'button_link'	=> $posts['s_button_link'],
						'active'		=> $posts['s_visiable']

					),
					array(
						'%d',
						'%d',
						'%s',
						'%s',
						'%s',
						'%d'
					)
				);
				return true;
			}else{
				return false;
			}
	}


	/******************************************************
	 G E T  A L L  S L I D E R  I T E M   U S I N G  I D 
	********************************************************/

	public function get_ch_slider_items($slider_id, $desable){
		$getID = $this->db->get_row("SELECT id FROM $this->ch_table WHERE slider_slug='".$slider_id."'");
		$id = (is_numeric($slider_id))?$slider_id:$getID->id;
		
		if(!$desable):
		$gallSliderDB = $this->db->get_results( "SELECT * FROM $this->ch_item_table WHERE slider_id='".$id."'", OBJECT );
		else:
		$gallSliderDB = $this->db->get_results( "SELECT * FROM $this->ch_item_table WHERE slider_id='".$id."' AND active=1", OBJECT );
		endif;
		return $gallSliderDB;
	}


	/**********************************************************
	U P D A T E  S L I D E R  I T E M S
	***********************************************************/
	public function update_ch_slider_item($posts){
		$update = $this->db->query($this->db->prepare("UPDATE $this->ch_item_table SET item_img='".$posts['img_id']."', title='".$posts['title']."', button_text='".$posts['button_text']."', button_link='".$posts['button_link']."', active='".$posts['visiable']."' WHERE id='".$posts['id']."'"));

		if($update){
			return true;
		}else{
			return false;
		}
	}


	/*************************************************************
	D E L E T E  S L I D E R  I T E M
	***************************************************************/
	public function delete_ch_slider_item($id){
		$delete = $this->db->query("DELETE FROM $this->ch_item_table WHERE id='".$id."'");
		if($delete){
			return true;
		}else{
			return false;
		}
	}


	/***************************************************************************
	G E T  S L I D E R  I T E M  I M A G E  I D
	****************************************************************************/
	public function get_slider_img_id($s_id){
		$this->create_item_table();
		$imgid = $this->db->get_row( "SELECT item_img FROM $this->ch_item_table WHERE slider_id = '".$s_id."'", OBJECT );

		if($imgid){
			return $imgid->item_img;
		}
	}


	/*******************************************************************************
	D E L E T  S L I D E R
	***********************************************************************************/
	public function delete_main_slider_item($id){
		$ch_delete = $this->db->query("DELETE `abm_ch_slider`, `abm_ch_slider_items` FROM `abm_ch_slider` LEFT JOIN `abm_ch_slider_items` ON abm_ch_slider.id=abm_ch_slider_items.slider_id WHERE abm_ch_slider.id='".$id."'");

		if($ch_delete){
			return true;
		}else{
			return false;
		}
	}
} /*End Class*/