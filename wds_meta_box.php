<?php
function mng_sanitize_array(&$item,$key){
      $item=sanitize_text_field($item);
}

function mng_call_someClass() {
    new MngGenerateMetabox();
}
if ( is_admin() ) {
    add_action( 'load-post.php', 'mng_call_someClass' );
    add_action( 'load-post-new.php', 'mng_call_someClass' );
}

class MngGenerateMetabox {

	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'save_post', array( $this, 'save' ) );
	}

	public function add_meta_box( $post_type ) {
            $post_types = array('product');     //limit meta box to certain post types
            if ( in_array( $post_type, $post_types )) {
			add_meta_box(
			'some_meta_box_name'
			,__( 'Woocommer Custom Product Attribute', 'mng_myplugin_textdomain' )
			,array( $this, 'render_meta_box_content' )
			,$post_type
			,'advanced'
			,'high'
		);
            }
	}

	/**
	 * Save the meta when the post is saved.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	public function save( $post_id ) {
	
		/*
		 * We need to verify this came from the our screen and with proper authorization,
		 * because save_post can be triggered at other times.
		 */

		// Check if our nonce is set.
		if ( ! isset( $_POST['myplugin_inner_custom_box_nonce'] ) )
			return $post_id;

		$nonce = $_POST['myplugin_inner_custom_box_nonce'];
		
		
		
		

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, 'myplugin_inner_custom_box' ) )
			return $post_id;

		// If this is an autosave, our form has not been submitted,
                //     so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
			return $post_id;

		// Check the user's permissions.
		if ( 'page' == $_POST['post_type'] ) {

			if ( ! current_user_can( 'edit_page', $post_id ) )
				return $post_id;
	
		} else {

			if ( ! current_user_can( 'edit_post', $post_id ) )
				return $post_id;
		}
		
		if(isset($_POST['custom'])){
			$mydata = $this->mng_filter_post_data($_POST['custom']);
			update_post_meta( $post_id, 'mng_custom_data', $mydata );
		}
	}
	
	
	public function mng_filter_post_data($data){
		array_walk_recursive($data, 'mng_sanitize_array');
		return $data;
	}

	
	public function render_meta_box_content( $post ) {
	
		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'myplugin_inner_custom_box', 'myplugin_inner_custom_box_nonce' );

		// Use get_post_meta to retrieve an existing value from the database.
		$value = get_post_meta( $post->ID, 'mng_custom_data', true );
		if(empty($value)){
			$value['meal']=array();
		}
	
		echo '<h2><input type="checkbox" name="custom[meal][active]" value="1" '.(($value['meal']['active']==1)?'checked="checked"':'').' > Active this product for meal plan</h2>';
		
	echo '<h3>General Popup Message : <input type="text" name="custom[meal][general_message]" value="'.esc_attr( $value['meal']['general_message']).'"  /> </h3>';
		
		//print "<pre>";
		//print_r($value);
		//print "</pre>";		
		// Display the form, using the current value.
		echo '<h2>Meal Plan</h2>';
		$meanPlanArray = array(1,4,6,8,12);
		foreach($meanPlanArray as $i){
/*		for($i=0;$i<count($meanPlanArray);$i++){*/
			echo '<table width="100%" class="wp-list-table widefat fixed posts" border="1" style="border-collapse:collapse">
			
			  <tr>
				<td colspan="3"><strong>'.$i.' Week Meal Package</strong></td>
				<td colspan="3" >Package Price: <input type="text" name="custom[meal][PR]['.$i.']" value="'.esc_attr( $value['meal']['PR'][$i] ).'" /></td>
			  </tr>
			  <tr>
				<td><strong>Items</strong></td>
				<td align="center">Breakfast</td>
				<td align="center">Lunch</td>
				<td align="center">Dinner</td>
				<td align="center">Dessert</td>
				<td align="center">Snack</td>
			  </tr>
			  <tr>
				<td><strong>Included</strong></td>
				<td align="center"><input type="checkbox" name="custom[meal][BF][IN]['.$i.']" value="1" '.(($value['meal']['BF']['IN'][$i]==1)?'checked="checked"':'').' /></td>
				<td align="center"><input type="checkbox" name="custom[meal][LN][IN]['.$i.']" value="1" '.(($value['meal']['LN']['IN'][$i]==1)?'checked="checked"':'').' /></td>
				<td align="center"><input type="checkbox" name="custom[meal][DN][IN]['.$i.']" value="1" '.(($value['meal']['DN']['IN'][$i]==1)?'checked="checked"':'').' /></td>
				<td align="center"><input type="checkbox" name="custom[meal][DR][IN]['.$i.']" value="1" '.(($value['meal']['DR']['IN'][$i]==1)?'checked="checked"':'').' /></td>
				<td align="center"><input type="checkbox" name="custom[meal][SN][IN]['.$i.']" value="1" '.(($value['meal']['SN']['IN'][$i]==1)?'checked="checked"':'').' /></td>
			  </tr>
			  <tr>
				<td><strong>Price</strong></td>
				<td align="center"><input type="text" name="custom[meal][BF][PR]['.$i.']" value="'.esc_attr( $value['meal']['BF']['PR'][$i] ).'" style="width:100px" /></td>
				<td align="center"><input type="text" name="custom[meal][LN][PR]['.$i.']" value="'.esc_attr( $value['meal']['LN']['PR'][$i] ).'" style="width:100px" /></td>
				<td align="center"><input type="text" name="custom[meal][DN][PR]['.$i.']" value="'.esc_attr( $value['meal']['DN']['PR'][$i] ).'" style="width:100px" /></td>
				<td align="center"><input type="text" name="custom[meal][DR][PR]['.$i.']" value="'.esc_attr( $value['meal']['DR']['PR'][$i] ).'" style="width:100px" /></td>
				<td align="center"><input type="text" name="custom[meal][SN][PR]['.$i.']" value="'.esc_attr( $value['meal']['SN']['PR'][$i] ).'" style="width:100px" /></td>
			  </tr>
			  
			    <tr>
				<td><strong>Quantity</strong></td>
				<td align="center">
				<select  name="custom[meal][BF][QT]['.$i.']">';
				
				
				for($j=1;$j<=5;$j++){
				
					if($value['meal']['BF']['QT'][$i]==$j)
						echo '<option value="'.$j.'" selected="selected">'.$j.'</option>';
					else		 
					     echo '<option value="'.$j.'">'.$j.'</option>';
				}
				
				echo '</select>
				</td>
				<td align="center">
				<select  name="custom[meal][LN][QT]['.$i.']">';
				
				for($j=1;$j<=5;$j++){
					if($value['meal']['LN']['QT'][$i]==$j)
						echo '<option value="'.$j.'" selected="selected">'.$j.'</option>';
					else		 		 
						echo '<option value="'.$j.'">'.$j.'</option>';
				}
				
				echo '</select>
				</td>
				<td align="center">
				<select  name="custom[meal][DN][QT]['.$i.']">';
				
				for($j=1;$j<=5;$j++){
					if($value['meal']['DN']['QT'][$i]==$j)
						echo '<option value="'.$j.'" selected="selected">'.$j.'</option>';
					else		 
						echo '<option value="'.$j.'">'.$j.'</option>';
				}
				
				echo '</select>
				</td>
				<td align="center">
				<select  name="custom[meal][DR][QT]['.$i.']">';
				
				for($j=1;$j<=5;$j++){
					if($value['meal']['DR']['QT'][$i]==$j)
						echo '<option value="'.$j.'" selected="selected">'.$j.'</option>';
					else		 
						echo '<option value="'.$j.'">'.$j.'</option>';
				}
				
				echo '</select>
				</td>
				<td align="center">
				<select  name="custom[meal][SN][QT]['.$i.']">';
				
				for($j=1;$j<=5;$j++){
					if($value['meal']['SN']['QT'][$i]==$j)
						echo '<option value="'.$j.'" selected="selected">'.$j.'</option>';
					else		 
						echo '<option value="'.$j.'">'.$j.'</option>';
				}
				
				echo '</select>
				</td>
			  </tr>
			  
			  <tr>
				<td><strong>SKU</strong></td>
				<td align="center"><input type="text" name="custom[meal][BF][SK]['.$i.']" value="'.esc_attr( $value['meal']['BF']['SK'][$i] ).'" style="width:100px" /></td>
				<td align="center"><input type="text" name="custom[meal][LN][SK]['.$i.']" value="'.esc_attr( $value['meal']['LN']['SK'][$i] ).'" style="width:100px" /></td>
				<td align="center"><input type="text" name="custom[meal][DN][SK]['.$i.']" value="'.esc_attr( $value['meal']['DN']['SK'][$i] ).'" style="width:100px" /></td>
				<td align="center"><input type="text" name="custom[meal][DR][SK]['.$i.']" value="'.esc_attr( $value['meal']['DR']['SK'][$i] ).'" style="width:100px" /></td>
				<td align="center"><input type="text" name="custom[meal][SN][SK]['.$i.']" value="'.esc_attr( $value['meal']['SN']['SK'][$i] ).'" style="width:100px" /></td>
			  </tr>
			</table><br />';
		}
		
		echo '<table width="100%" class="wp-list-table widefat fixed posts">
		<tr>
			<td>Display Message</td>
			<td><textarea  name="custom[meal][plan_tag_line]" style="height:100px;width:100%">'.esc_attr( $value['meal']['plan_tag_line'] ).'</textarea></td>
		</tr>
		</table>';
		
		echo '<h2>Meals Per Day</h2>
		<table width="100%" class="wp-list-table widefat fixed posts">
		<tr>
			<td>Display Message</td>
			<td><textarea name="custom[meal][plan_per_day_tag_line]" style="height:100px;width:100%">'.esc_attr( $value['meal']['plan_per_day_tag_line'] ).'</textarea></td>
		</tr>
		</table>';
		
		
		echo '<h2>Extra</h2>';
		echo '<table width="100%" class="wp-list-table widefat fixed posts" border="1" style="border-collapse:collapse">
			  <tr>
				<td><strong>Items</strong></td>
				<td align="center">Breakfast</td>
				<td align="center">Lunch</td>
				<td align="center">Dinner</td>
				<td align="center">Dessert</td>
				<td align="center">Snack</td>
			  </tr>
			  <tr>
				<td><strong>Included</strong></td>
				<td align="center"><input type="checkbox" name="custom[extra][BF][IN]" value="1" '.(($value['extra']['BF']['IN']==1)?'checked="checked"':'').' /></td>
				<td align="center"><input type="checkbox" name="custom[extra][LN][IN]" value="1" '.(($value['extra']['LN']['IN']==1)?'checked="checked"':'').' /></td>
				<td align="center"><input type="checkbox" name="custom[extra][DN][IN]" value="1" '.(($value['extra']['DN']['IN']==1)?'checked="checked"':'').' /></td>
				<td align="center"><input type="checkbox" name="custom[extra][DR][IN]" value="1" '.(($value['extra']['DR']['IN']==1)?'checked="checked"':'').' /></td>
				<td align="center"><input type="checkbox" name="custom[extra][SN][IN]" value="1" '.(($value['extra']['SN']['IN']==1)?'checked="checked"':'').' /></td>
			  </tr>
			  <tr>
				<td><strong>Price</strong></td>
				<td align="center"><input type="text" name="custom[extra][BF][PR]" value="'.esc_attr( $value['extra']['BF']['PR'] ).'" style="width:100px" /></td>
				<td align="center"><input type="text" name="custom[extra][LN][PR]" value="'.esc_attr( $value['extra']['LN']['PR'] ).'" style="width:100px" /></td>
				<td align="center"><input type="text" name="custom[extra][DN][PR]" value="'.esc_attr( $value['extra']['DN']['PR'] ).'" style="width:100px" /></td>
				<td align="center"><input type="text" name="custom[extra][DR][PR]" value="'.esc_attr( $value['extra']['DR']['PR'] ).'" style="width:100px" /></td>
				<td align="center"><input type="text" name="custom[extra][SN][PR]" value="'.esc_attr( $value['extra']['SN']['PR'] ).'" style="width:100px" /></td>
			  </tr>
			  <tr>
				<td><strong>SKU</strong></td>
				<td align="center"><input type="text" name="custom[extra][BF][SK]" value="'.esc_attr( $value['extra']['BF']['SK'] ).'" style="width:100px" /></td>
				<td align="center"><input type="text" name="custom[extra][LN][SK]" value="'.esc_attr( $value['extra']['LN']['SK'] ).'" style="width:100px" /></td>
				<td align="center"><input type="text" name="custom[extra][DN][SK]" value="'.esc_attr( $value['extra']['DN']['SK'] ).'" style="width:100px" /></td>
				<td align="center"><input type="text" name="custom[extra][DR][SK]" value="'.esc_attr( $value['extra']['DR']['SK'] ).'" style="width:100px" /></td>
				<td align="center"><input type="text" name="custom[extra][SN][SK]" value="'.esc_attr( $value['extra']['SN']['SK'] ).'" style="width:100px" /></td>
			  </tr>
			</table>
			<table width="100%" class="wp-list-table widefat fixed posts">
			<tr>
				<td>Display Message</td>
				<td><textarea name="custom[meal][plan_extra]" style="height:100px;width:100%">'.esc_attr( $value['meal']['plan_extra'] ).'</textarea></td>
			</tr>
			</table>';
	}
}
