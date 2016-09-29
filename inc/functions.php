<?php

/**
 *	Instantiate a maker
 */
class KHPixelMaker {


	/**
	 *	Setup vars
	 */
	var $data = array();
	var $sprite_width = 64;
	var $sprite_height = 136;





	/**
	 *	Setup the base data and structures
	 */
	function __construct( $datasrc ) {

		$data = $this->get_json_data_from_file( $datasrc );
		$this->set_data( $data );

	}





	/**
	 *	Return a JSON Decoded array from a file
	 *
	 *	Retrieve the contents of a JSON file and return
	 *	a decoded version as a PHP array
	 */
	private function get_json_data_from_file( $datasrc ) {

		if ( file_exists( $datasrc ) ) {

			$data = file_get_contents( $datasrc );
			return json_decode( $data, true );

		}

		return false;

	}





	/**
	 *	Set the data var in the class
	 */
	private function set_data( $data ) {

		$this->data = $data;

	}





	/**
	 *	Return the main maker data
	 */
	public function get_data() {

		return $this->data;

	}





	/**
	 *	Render maker form
	 */
	public function render_form( $data = null ) {

		if ( empty( $data ) ) {

			$data = $this->get_data();

		}

		echo '<form method="post" action="index.php?save">';

			foreach( $data as $legend => $fieldset ) {

				$this->render_fieldset( $legend, $fieldset );

			}

			echo '<button id="btn-s" class="btn t-u t-tn" type="submit">Save Character</button>';

		echo '</form>';

	}





	/**
	 *	Render a fieldset
	 */
	public function render_fieldset( $legend, $fieldset ) {

		echo '<fieldset>';

			echo '<legend class="t-u t-tn t-s">' . $legend . '</legend>';

			$counter = 0;
			$rand = rand( 0, ( count( $fieldset ) - 1 ) );

			if ( $legend == 'FacialHair' ) {

				$rand = 0;

			}

			foreach( $fieldset as $itemnum => $item ) {

				$selected = '';

				if ( $counter == $rand ) {

					$selected = ' checked="checked"';

				}

				$this->render_item( $item, $itemnum, $legend, $selected );

				$counter++;

			}

		echo '</fieldset>';

	}




	/**
	 *	Shorten a classname to 2 characters
	 */
	public function shorten_css_class_name( $class ) {

		return substr( strtolower( $class ), 0, 2 );

	}





	/**
	 *	Render a fieldset
	 */
	public function render_item( $item, $itemnum, $legend, $selected ) {

		$type = $this->shorten_css_class_name( $legend );

		echo '<input type="radio" id="' . $type . '-' . $itemnum . '" name="' . $type . '" value="' . $itemnum . '"' . $selected . '>';
		echo '<label onclick for="' . $type . '-' . $itemnum . '">' . $item[ 'name' ] . '</label>';
	
	}




	/**
	 *	Render a fieldset
	 */
	public function get_item_choices() {

		$data = $this->data;
		$items = array();

		$skintone = $this->verify_item_choice( 'Skintones', $_POST[ 'sk' ], $data );
		if ( $skintone ) { $items[ 'sk' ] = $skintone; }

		$hair = $this->verify_item_choice( 'Hairstyles', $_POST[ 'ha' ], $data );
		if ( $hair ) { $items[ 'ha' ] = $hair; }

		$pants = $this->verify_item_choice( 'Pants', $_POST[ 'pa' ], $data );
		if ( $pants ) { $items[ 'pa' ] = $pants; }

		$bottoms = $this->verify_item_choice( 'Bottoms', $_POST[ 'bo' ], $data );
		if ( $bottoms ) { $items[ 'bo' ] = $bottoms; }

		$tops = $this->verify_item_choice( 'Tops', $_POST[ 'to' ], $data );
		if ( $tops ) { $items[ 'to' ] = $tops; }

		$face = $this->verify_item_choice( 'FacialHair', $_POST[ 'fa' ], $data );
		if ( $face ) { $items[ 'fa' ] = $face; }

		$accessories = $this->verify_item_choice( 'Accessories', $_POST[ 'ac' ], $data );
		if ( $accessories ) { $items[ 'ac' ] = $accessories; }

		$shoes = $this->verify_item_choice( 'Shoes', $_POST[ 'sh' ], $data );
		if ( $shoes ) { $items[ 'sh' ] = $shoes; }

		return $items;

	}
	




	/**
	 *	Return the sprite sheet image
	 */
	public function get_sprite_sheet_image() {

		return 'assets/img/sprite-sheet-01.png';

	}





	/**
	 *	Verify the item choice exists
	 */
	public function verify_item_choice( $type, $choice, $data ) {

		$key = $this->shorten_css_class_name( $type );
		$item = false;

		if ( isset( $_POST[ $key ] ) && isset( $data[ $type ][ $_POST[ $key ] ] ) ) {

			if ( $data[ $type ][ $_POST[ $key ] ][ 'x' ] > 0 ) {

				$item = array( $data[ $type ][ $_POST[ $key ] ][ 'x' ], $data[ $type ][ $_POST[ $key ] ][ 'y' ] );

			}

		}

		return $item;

	}




	/**
	 *	Create the image
	 */
	public function create_image( $items ) {

		// Create the base img
	    $img = imagecreatetruecolor( $this->sprite_width, $this->sprite_height );
	    $green = imagecolorallocate( $img, 0, 255, 0 );

		// Fill w/ obnoxious green, then make transparent
		imagefill( $img, 0, 0, $green );
		imagecolortransparent( $img, $green );

	    // Check for items
	    if ( is_array( $items ) ) {

	    	// Loop through items
	    	foreach( $items as $key => $item ) {

		    	// Create image instances
				$src = imagecreatefrompng( $this->get_sprite_sheet_image() );

				// Copy and merge
				imagecopyresampled( $img, $src, 0, 0, ( ( $item[ 0 ] - 1 ) * $this->sprite_width ), ( ( $item[ 1 ] - 1 ) * $this->sprite_height ), $this->sprite_width, $this->sprite_height, $this->sprite_width, $this->sprite_height );

			}

			ob_start(); 

			    imagepng( $img ); 
			    $imgdata = ob_get_contents();

			ob_end_clean();

			imagedestroy( $img );
			imagedestroy( $src );

			$dataUri = "data:image/png;base64," . base64_encode( $imgdata );

			return $dataUri;

	    }

	    return false;

	}


}