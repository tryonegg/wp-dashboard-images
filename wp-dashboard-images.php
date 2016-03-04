<?php
/*
Plugin Name: Dashboard Featured Images
Version: 1.0
Plugin URI: 
Description: Shows featured images on the dashboard
Author: Tryon Eggleston
*/

class wpDashboardImages {

	function __construct(){
		add_action('init', array( &$this, 'init' ), 20);
		add_action('admin_head', array( &$this, 'css' ) );
	}

	function init(){
		$args = array(
			'public'   => true,
		);

		$post_types = get_post_types( $args ); 

		foreach($post_types as $type){
			add_filter( 'manage_' . $type . 's_columns', array( &$this, 'AddThumbColumn' ) );
			add_action( 'manage_' . $type . 's_custom_column', array( &$this, 'AddThumbValue' ), 10, 2 );
		}
	}

	function css() {
		echo '
			<style type="text/css">
				.column-thumbnail{
					width:80px;
				}
				
				.column-thumbnail img{
					width:60px;
					height:auto;
				}
			</style>
		';
	}

	function AddThumbColumn($cols) {
		$cols['thumbnail'] = __('Featured Image');

		return $cols;
	}

	function AddThumbValue($column_name, $post_id) {
		$width = (int) 60;
		$height = (int) 60;
		
		if ( 'thumbnail' == $column_name ) {
			$thumbnail_id = get_post_meta( $post_id, '_thumbnail_id', true );
			
			// image from gallery
			$attachments = get_children( array('post_parent' => $post_id, 'post_type' => 'attachment', 'post_mime_type' => 'image') );
			if ($thumbnail_id){
				$img =  wp_prepare_attachment_for_js( $thumbnail_id );
				echo  "<a href='" . get_edit_post_link($thumbnail_id) . "'>" . wp_get_attachment_image( $thumbnail_id, 'medium', true ) . "</a>";
				echo "<br/> ".$img['sizes']['full']['width'] . "x" . $img['sizes']['full']['height'];
			} else {
				echo __('');
			}
		}
	}

}
new wpDashboardImages();
