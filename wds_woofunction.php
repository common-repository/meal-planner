<?php
// Add Woofunction.php 08-06-2016
add_filter ('woocommerce_before_shop_loop_item', 'mng_check_is_active');
add_filter ('woocommerce_before_main_content', 'mng_check_is_active');

function mng_check_is_active() {
		$value = get_post_meta( get_the_ID(), 'mng_custom_data', true );
		$ddd=mnd_wds_get_mel_plan_price($value);
		if(! is_single()){
			remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
			remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
			remove_action( 'woocommerce_after_shop_loop_item_title', 'mng_generate_from_button', 12 );
			remove_action( 'woocommerce_after_shop_loop_item', 'mng_generate_from_button', 12 );
			remove_action( 'woocommerce_after_single_product_summary', 'mng_generate_from', 0 );			
		}	
		
		if($value['meal']['active']==1){ 
		
		
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
		
			add_filter( 'wc_product_sku_enabled', '__return_false' );
			add_action( 'woocommerce_single_product_summary', 'mng_generate_from_button', 12 );
			add_action( 'woocommerce_after_single_product_summary', 'mng_generate_from', 0 );
			add_action( 'woocommerce_after_shop_loop_item', 'mng_generate_from_button', 12 );
			
		}else{
		
			if(! is_single()){
				add_filter( 'wc_product_sku_enabled', '__return_true' );
				add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
				add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
				add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
				add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
				add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
			}
		}
}



add_filter ('add_to_cart_redirect', 'mng_redirect_to_checkout');
function mng_redirect_to_checkout() {
    global $woocommerce;
    $checkout_url = $woocommerce->cart->get_checkout_url();
    return $checkout_url;
}
function mng_generate_from_button(){ 
		if(is_single()){ ?>
		 <div class="inner-page-right-btn wds_meal_customizer_button"> 
    	  	<a class="started" href="javascript:void(0);">
			<button class="single_add_to_cart_button button alt" type="submit"><?php echo __('Choose your Meal plan') ?></button>
			
			</a>
    	   <a class="followbookmark" href="#bm_1"></a> 
    	  </div>
	
<?php }else{ ?>

		 <div class="inner-page-right-btn wds_meal_customizer_button"> 
         	<a class="button product_type_simple add_to_cart_button ajax_add_to_cart" href="<?php echo get_permalink() ?>"><?php echo __('Customize Meal') ?></a>
     	 </div>
	
	 <?php  }
}

function mng_generate_from(){
	global $product;
	$value = get_post_meta( get_the_ID(), 'mng_custom_data', true );
	$ddd=mnd_wds_get_mel_plan_price($value);
	if($value['meal']['active']==1){ 
	?>
	<div class="wds_meal_customizer_div">
	<form class="cart" method="post" enctype='multipart/form-data'>
	<div class="variations inner-page-dedetail row1 attr_list" id="loop_1"> <a id="bm_1"></a>
    	<h4 class="inner-page-dedetail-title">Select A Meal Plan</h4>
        
        <div class="included" id="general_message" style="display:block">
       <?php  echo 	$value['meal']['general_message'] ?>
        </div>
        
        
        <?php if(is_array($ddd['package_data'])){
				foreach($ddd['package_data'] as $key=>$index){
					echo '<div id="custom_row_'.$key.'" class="included">';
					$tot=count($index);
					$totrow=1;
					$str='';
					foreach($index as $ttt){
						$title=$ttt['title'];
						$title=str_replace('BF','breakfast',$title);
						$title=str_replace('LN','lunch',$title);						
						$title=str_replace('DN','dinner',$title);												
						$title=str_replace('DR','dessert',$title);																		
						$title=str_replace('SN','snack',$title);																								
						if($totrow==$tot && $tot>1){
							
							if($ttt['price']<=0)
							$title =' FREE '.$title;	
							$str =trim($str,', ');
							$str .=' and '.$ttt['qty'].' '.$title.'';
						}	
						else
							$str .=$ttt['qty'].' '.$title.', ';
							
						$totrow++;
					}
					if(strlen($str)>0)
						echo 'Includes ';
					echo $str;
					echo '</div>';
				}	
			}	
		?>
		<div class="inner-page-dedetail-left"><h2><?php echo $value['meal']['plan_tag_line'];?></h2></div>
		<div class="inner-page-dedetail-right">
      	<?php 
		//$meanPlanArray = array(1,4,6,8,12);
		foreach($value['meal']['PR'] as $key=>$d){ 
			if($d){?>
				<div class="dedetail-box">
					<div class="redio-button">
						  <input type="radio" value="<?php echo $key ?>" id="meal_panl_<?php echo $key ?>" name="meal_plan" data-select="<?php echo $key ?> Week Meal Package">
					</div>

					<div class="dedetail-box-icon"><?php echo '<img src="'.plugin_dir_url( __FILE__ ).'/images/inner-icon.png">'; ?></div>
					<div class="dedetail-box-text"><?php echo $key ?> Week Meal Package<br>
					  <span>($<?php echo $d ?> per meal)</span></div>
				 </div>
      		<?php 
			} 
	  	} ?>
	    </div>
	</div>
    
    
    
    <div class="variations inner-page-dedetail row1 attr_list" id="loop_2"> <a id="bm_2"></a>
    	<h4 class="inner-page-dedetail-title"><?php echo __('How Many Days Per Week would you like to have Meals for?') ?></h4>
		<div class="inner-page-dedetail-left"><h2><?php echo $value['meal']['plan_per_day_tag_line'];?></h2></div>
		<div class="inner-page-dedetail-right">
		  <?php 
		  $da=array('3'=>3,'5'=>5,'7'=>7);
		  foreach($da as $key=>$d){ 
			if($d){?>
				<div class="dedetail-box">
					<div class="redio-button">
						  <input type="radio" value="<?php echo $key ?>" id="plan_per_day<?php echo $key ?>" name="meal_plan_per_day" data-select="<?php echo $key ?> Days Per Week">
					</div>
					<div class="dedetail-box-text"><?php echo $key ?> Days Per Week</div>
				 </div>
			<?php } 
		  } ?>
	    </div>
	</div>


    <div class="variations inner-page-dedetail row1 attr_list" id="loop_3"> <a id="bm_3"></a>
    	<h4 class="inner-page-dedetail-title"><?php echo __(' Would You Like to Add an Extra Breakfast, Lunch, Dinner Or Snack?') ?></h4>
		<div class="inner-page-dedetail-left"><h2><?php echo $value['meal']['plan_extra'];?></h2></div>
		<div class="inner-page-dedetail-right">
     		<?php if($value['extra']['BF']['IN']): ?>
				<div class="dedetail-box">
					<div class="dedetail-box-text">
						<input type="checkbox" name="meal_plan_breakfast_radio" id="meal_plan_breakfast_radio" value="1"><br>Breakfast<br>
						<span style="color:#86ac13">(+$<?php echo $value['extra']['BF']['PR'] ?> Per Breakfast)</span>
					</div>
					<div class="redio-button">
						<select name="meal_plan_breakfast">
							  <option value="1">1</option>
							  <option value="2">2</option>
							  <option value="3">3</option>
							  <option value="4">4</option>
							  <option value="5">5</option>
							  <option value="6">6</option>                                                                                          
						</select>
					 </div>
				</div>
			<?php endif; ?>
			
			<?php if($value['extra']['LN']['IN']): ?>
      			<div class="dedetail-box">
                    <div class="dedetail-box-text">
                        <input type="checkbox" name="meal_plan_lunch_radio" id="meal_plan_lunch_radio" value="1"><br>Lunch<br>
                        <span style="color:#86ac13">(+$<?php echo $value['extra']['LN']['PR'] ?> Per Lunch)</span>
                    </div>
                    <div class="redio-button">
                        <select name="meal_plan_lunch">
                              <option value="1">1</option>
                              <option value="2">2</option>
                              <option value="3">3</option>
                              <option value="4">4</option>
                              <option value="5">5</option>
                              <option value="6">6</option>                                                                                          
                        </select>
                     </div>
      			</div>
			<?php endif; ?>
				
      		<?php if($value['extra']['DN']['IN']): ?>
            	<div class="dedetail-box">
                    <div class="dedetail-box-text">
                        <input type="checkbox" name="meal_plan_dinner_radio" id="meal_plan_dinner_radio" value="1"><br>Dinner<br>
                           <span style="color:#86ac13">(+$<?php echo $value['extra']['DN']['PR'] ?> Per Dinner)</span>
                    </div>
                    <div class="redio-button">
                        <select name="meal_plan_dinner">
                              <option value="1">1</option>
                              <option value="2">2</option>
                              <option value="3">3</option>
                              <option value="4">4</option>
                              <option value="5">5</option>
                              <option value="6">6</option>
						</select>
                     </div>
		      </div>
            <?php endif; ?>
			<?php if($value['extra']['DR']['IN']): ?>
            	<div class="dedetail-box">
                    <div class="dedetail-box-text">
                        <input type="checkbox" name="meal_plan_dessert_radio" id="meal_plan_dessert_radio" value="1"><br>Dessert<br>
                           <span style="color:#86ac13">(+$<?php echo $value['extra']['DR']['PR'] ?> Per Dessert)</span>
                    </div>
                    <div class="redio-button">
                        <select name="meal_plan_dessert">
                              <option value="1">1</option>
                              <option value="2">2</option>
                              <option value="3">3</option>
                              <option value="4">4</option>
                              <option value="5">5</option>
                              <option value="6">6</option>
						</select>
                     </div>
		      </div>
            <?php endif; ?>
            
            
            <?php if($value['extra']['SN']['IN']): ?>              
              <div class="dedetail-box">
                <div class="dedetail-box-text">
                    <input type="checkbox" name="meal_plan_snack_radio" id="meal_plan_snack_radio" value="1"><br>Snack<br>
                       <span style="color:#86ac13">(+$<?php echo $value['extra']['SN']['PR'] ?> Per Snack)</span>
                </div>
                <div class="redio-button">
                    <select name="meal_plan_snack">
                          <option value="1">1</option>
                          <option value="2">2</option>
                          <option value="3">3</option>
                          <option value="4">4</option>
                          <option value="5">5</option>
                          <option value="6">6</option>                                                                                          
                    </select>
                 </div>
     		 </div>
                 <?php endif; ?>
	   </div>
	</div>
    
    
    
    <div id="single_variation1" class="single_variation_wrap inner-page-dedetail row3"  style="display:none;">
    
    
    <div class="inner-page-dedetail row3 attr_list" style="display:block">
			<h4 class="inner-page-dedetail-title"><?php echo __('Please advise of any food allergies') ?></h4>
			<div class="inner-page-dedetail-left">
				<p>
				<?php echo __('Please specify upto 3 foods dislike (i.e fish, chicken, broccoli, etc include food alergies.)') ?>
				</p>
			</div>
			<div class="inner-page-dedetail-right">
				<div class="dedetail-box-input"><textarea rows="" name="food_allergies_or_any_specific_requirements" cols="" ></textarea></div>
			</div>
		</div>
    
    
			<h4 class="inner-page-dedetail-title"><?php echo __('Product Details') ?></h4>
			<div class="inner-page-dedetail-product">
				<div class="product-dedetail-box" id="product-detail-list">
				<?php 
				if(isset($_POST) && count($_POST)>0){
				    echo '<p><p class="ddate">Delivery Date : </p><span>'.sanitize_text_field($_POST['deliverydate']).'</span></p>';
					echo '<p>Meal Plan: <span>'.sanitize_text_field($_POST['attribute_select-a-meal-plan_radio']).'</span></p>';
					echo '<p>Duration: <span>'.sanitize_text_field($_POST['attribute_how-many-meals-per-day-you-would-prefer-to-have_radio']).'</span></p>';
					echo '<p>Meals/Day: <span>'.sanitize_text_field($_POST['attribute_how-many-days-per-week-would-you-like-us-to-serve-you-for_radio']).'</span></p>';
					echo '<p>Double Protein: <span>'.sanitize_text_field($_POST['attribute_would-you-like-to-have-extra-protein-in-your-meals_radio']).'</span></p>';
					//echo '<br>Total Cost : '.$_POST['attribute_select-a-meal-plan_radio'];
				}
				?>
				</div>				
				
				<h4 class="product-price">Total Cost: <span class="single_variation" style="display: inline-block;text-align: left;width: 17%; padding-left:5px"></span></h4>
	
				<div class="add-to-cart-row">
					<div class="add-to-cart-box">
						<div class="add-to-cart-btn">
							<div class="variations_button custombutton" style="text-align:right">
								<?php //  woocommerce_quantity_input(); ?>
								<button type="submit" class="single_add_to_cart_button button alt"><?php echo $product->single_add_to_cart_text(); ?></button>
                                <input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->id ); ?>" />
							</div>
						</div>
						<!--div class="date-picker" id="deliveryselect">
							<input type="text" id="deliverydate" name="deliverydate" required placeholder="Select Delivery Date First">
						</div-->
					</div>
				</div>	
			</div>
		</div>
    
  <script language="javascript">
//	document.getElementById("custom_cart").reset();
	var meal_plan= new Object;
	var lunch_amount='<?php echo $ddd['package_extra']['LN']['price'] ?>';
	var dinner_amount='<?php echo $ddd['package_extra']['DN']['price'] ?>';
	var dessert_amount='<?php echo $ddd['package_extra']['DR']['price'] ?>';
	var snack_amount='<?php echo $ddd['package_extra']['SN']['price'] ?>';
	var breakfast_amount='<?php echo $ddd['package_extra']['BF']['price'] ?>';
 
 <?php  
	
	if(is_array($ddd['package_price'])){
		foreach($ddd['package_price'] as $key=>$val){
			echo 'meal_plan['.$key.']='.$val.';';	
		}
	}
  ?> 
  </script>
  <input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->id ); ?>" />
  </form>
  	</div>
  <?php }
	
	
	
}

add_action( 'wp_ajax_cancel_subscription', 'mng_cancel_subscription' );
add_action( 'wp_ajax_nopriv_cancel_subscription', 'mng_cancel_subscription' );
function mng_cancel_subscription(){
	$order_id=$_GET['order_id'];
	$sub_id=get_post_meta($order_id,'subscription_id',true);
	$order_up = new WC_Order($order_id);
	$d=get_option('woocommerce_mbj_auth_gateway_settings');
	if($d['environment']=='yes'){
		$host = "apitest.authorize.net";
		$path = "/xml/v1/request.api";
	}else{
		$host = "api.authorize.net";
		$path = "/xml/v1/request.api";
	}
	
	$api_login = $d['api_login'];
	$trans_key = $d['trans_key'];
	
	 $content =
				        "<?xml version=\"1.0\" encoding=\"utf-8\"?>".
				        "<ARBCancelSubscriptionRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">".
				        "<merchantAuthentication>".
				        "<name>" . $api_login. "</name>".
				        "<transactionKey>" . $trans_key . "</transactionKey>".
				        "</merchantAuthentication>" .
				        "<subscriptionId>" . $sub_id . "</subscriptionId>".
				       "</ARBCancelSubscriptionRequest>";
					   
			$response = send_request_via_curl($host,$path,$content);

			
		    $status = substring_between($response,'<messages>','</messages>');	
			$status ='<?xml version="1.0" encoding="utf-8"?><data>'.$status.'</data>';
			$response_data=@simplexml_load_string($status);
			if($response_data->resultCode=='Ok'){
				$order_up->add_order_note('Your Subscription has been cancelled successfully');
				$order_up->update_status( 'wc-cancelled', 'Error : Your Sucscribtion has been cancelled');
				wc_add_notice( 'Your Subscription has been cancelled successfully', 'success' );
				wp_redirect($order_up->get_view_order_url());
			}else{
				wc_add_notice( 'Try after some time', 'error' );			
				wp_redirect($order_up->get_view_order_url());
				exit;
			}

	exit;
}

function mnd_wds_get_mel_plan_price($value,$is_js='no'){
	$arrray_extra=$package=$extra_array=array();
	
	if(@$value['meal']['active']==1){
		$t_array=array('BF','LN','DN','DR','SN');
		
		foreach($value['meal']['PR'] as $k=>$val){
			$price=0;

			
			
			$arrray_extra=array();
			foreach($t_array as $j){
				$is_active=$value['meal'][$j]['IN'][$k];
				if($is_active){
					$sku=$value['meal'][$j]['SK'][$k];			
					$e_price=$value['meal'][$j]['PR'][$k];
					
					$e_qty=$value['meal'][$j]['QT'][$k];
					if(empty($e_qty))
						$e_qty=1;
						
					$price+=($e_price*$e_qty);	
					/*$e_qty=$value['meal'][$j]['QT'][$k];
						if(empty($e_qty))
							$e_qty=1;*/
					$arrray_extra[]=array('title'=>$j,'price'=>$e_price,'sku'=>$sku,'qty'=>$e_qty);
				}	
			}	
			if(empty($price))
				$price=0;
							
			$package['package_price'][$k]=$price;
			$package['package_data'][$k]=$arrray_extra;
			
			
		}
		
		
		foreach($t_array as $j){
			$is_active=$value['extra'][$j]['IN'];
			if($is_active){
				$sku=$value['extra'][$j]['SK'];			
				$e_price=$value['extra'][$j]['PR'];
				$extra_array[$j]=array('title'=>$j,'price'=>$e_price,'sku'=>$sku);
			}	
		}
		$package['package_extra']=$extra_array;
		
	}
	return $package;
}












add_action( 'woocommerce_before_calculate_totals', 'mng_add_custom_price' );
// Add to cart
add_filter( 'woocommerce_add_cart_item', 'mng_woocommerce_add_cart_item_custom' );

// Add item data to the cart
add_filter( 'woocommerce_add_cart_item_data', 'mng_woocommerce_add_cart_item_data_custom', 10, 2 );
add_action( 'woocommerce_add_order_item_meta', 'mng_woocommerce_add_order_item_meta_custom', 10, 2 );

// Load cart data per page load
add_filter( 'woocommerce_get_cart_item_from_session', 'mng_woocommerce_get_cart_item_from_session_custom', 20, 2 );

add_filter( 'woocommerce_get_item_data', 'mng_wc_checkout_description_so_15127954', 10, 2 );
// Get item data to display
add_filter( 'woocommerce_order_again_cart_item_data', 'mng_woocommerce_order_again_cart_item_data', 10, 3 );

// Add meta to order

add_filter( 'default_checkout_country', 'mng_woocommerce_default_country' );
add_action( 'woocommerce_add_to_cart_validation', 'mng_custom_error', 1, 5 );
function mng_add_custom_price( $cart_object ) {
	foreach ( $cart_object->cart_contents as $key => $value ) {
		$cvalue = get_post_meta( $value['product_id'] , 'mng_custom_data', true );
		if(is_array($cvalue)){
			if(@$cvalue['meal']['active']==1){
				$ddd=mnd_wds_get_mel_plan_price($cvalue);
				
				/*echo '<pre>';
				//echo $value['meal_plan_dessert'];
				echo '</pre>';	
				*/
							
				
				
				if($value['meal_plan_breakfast']>0)	
					$breakfast_amount =$ddd['package_extra']['BF']['price']*$value['meal_plan_breakfast'];	
				if($value['meal_plan_lunch']>0)	
					$lunch_amount =$ddd['package_extra']['LN']['price']*$value['meal_plan_breakfast'];	
				if($value['meal_plan_dinner']>0)	
					$dinner_amount =$ddd['package_extra']['DN']['price']*$value['meal_plan_breakfast'];	
				if($value['meal_plan_dessert']>0)	
					$dessert_amount =$ddd['package_extra']['DR']['price']*$value['meal_plan_dessert'];	
				if($value['meal_plan_snack']>0)	
					$snack_amount =$ddd['package_extra']['SN']['price']*$value['meal_plan_breakfast'];	
				
			//	echo $breakfast_amount,	$lunch_amount,$dinner_amount,$dessert_amount,$snack_amount;
				$plan=$value['meal_plan'];
				$qry=$value['meal_plan_per_day'];
				$price=$ddd['package_price'][$value['meal_plan']];
				$price=(($price*$qry)*$plan);
				
				$price=$price+$lunch_amount+$dinner_amount+$snack_amount+$breakfast_amount+$dessert_amount;

				$value['data']->price = $price;
			}
		}
    }
}

function mng_wc_checkout_description_so_15127954( $other_data, $cart_item ){

	$extra_field_array=mng_get_meal_array();
    $post_data = get_post( $cart_item['product_id'] );
	foreach($extra_field_array as $key=>$val) {
		if($cart_item[$key]){
			$vvv=$cart_item[$key];
			
			if($key=='meal_plan')
				$vvv =	$vvv.' Week Meal Package';
			else if($key=='meal_plan_per_day')
				$vvv =	$vvv.' Days Per Week';
			else if($key=='meal_plan_breakfast')
				$vvv =	$vvv.' Breakfast';
			else if($key=='meal_plan_lunch')
				$vvv =	$vvv.' Lunch';
			else if($key=='meal_plan_dinner')
				$vvv =	$vvv.' Dinner';
			else if($key=='meal_plan_dessert')
				$vvv =	$vvv.' Dessert';
			else if($key=='meal_plan_snack')
				$vvv =	$vvv.' Snack';
				
			$other_data[] = array( 'name' =>  $val,'value'=>$vvv);
		}	
	}	
    return $other_data;
	
}

//  add to cart 
function mng_woocommerce_add_cart_item_custom( $cart_item ) {

	return $cart_item;
}

//  get cart from session
function mng_woocommerce_get_cart_item_from_session_custom( $cart_item, $values ) {

$extra_field_array=mng_get_meal_array();
foreach($extra_field_array as $key=>$val) {
	if (!empty($values[$key])) :
		$cart_item[$key] = $values[$key];
		$cart_item = mng_woocommerce_add_cart_item_custom( $cart_item );
	endif;
}
return $cart_item;

}

//  get item data
function other_options_get_item_data( $other_data, $cart_item ) {
	$extra_field_array=mng_get_meal_array();
	foreach($extra_field_array as $key=>$val) {
		if (!empty($cart_item[$key])) :
			$other_data = array(
				'name' => $custom,
				'value' => $val,
				'display' => $custom .' : '.$val
			);
		endif;
		
	}
	return $other_data;
}

function mng_woocommerce_add_cart_item_data_custom($cart_item_meta, $product_id){
 	global $woocommerce;
	$name_a='';
	
	foreach($woocommerce->cart->cart_contents as $d){
		$name_a .=$d['data']->post->post_title.' Removed<br>';
	}
	
    $woocommerce->cart->empty_cart();
	if($name_a)
		wc_add_notice( $name_a, 'success' );
	

	if(! isset($_POST['meal_plan_breakfast_radio'])){
		unset($_POST['meal_plan_breakfast']);
	}
	if(! isset($_POST['meal_plan_lunch_radio'])){
		unset($_POST['meal_plan_lunch']);
	}
	if(! isset($_POST['meal_plan_dinner_radio'])){
		unset($_POST['meal_plan_dinner']);
	}
	if(! isset($_POST['meal_plan_dessert_radio'])){
		unset($_POST['meal_plan_dessert']);
	}
	if(! isset($_POST['meal_plan_snack_radio'])){
		unset($_POST['meal_plan_snack']);
	}

$extra_field_array=mng_get_meal_array();
	foreach($extra_field_array as $key=>$val) {
		if(empty($cart_item_meta[$key]))
			$cart_item_meta[$key] = array();

		$cart_item_meta[$key] = esc_attr($_POST[sanitize_title($key)]);            
	}
return $cart_item_meta;

}


//  add to order meta

function mng_woocommerce_add_order_item_meta_custom( $item_id, $values ) {
$cvalue = get_post_meta(  $values['data']->id, 'mng_custom_data', true );
	if(is_array($cvalue)){
		if(@$cvalue['meal']['active']==1){
			$extra_field_array=mng_get_meal_array();
			$subdada=array();
			
			
			
			$planData=$values['meal_plan'];
			$deliverydate=$values['deliverydate'];
			$deliverydate_array=explode('/',$deliverydate);
			$new_d_date=$deliverydate_array[1].'-'.$deliverydate_array[0].'-'.$deliverydate_array[2];
			$sum = strtotime(date("Y-m-d", strtotime($new_d_date)) . " +".$planData." week");
			$subscription=date('m/d/Y',$sum);
			
			
			
			
			foreach($extra_field_array as $key=>$val) {
				if ( ! empty( $values[$key] ) ) {
					$option_name=$val;
					$option_value=$values[$key];
					
					$subdada[$key]=$option_value;
					if($key=='meal_plan')
						$option_value =	$option_value.' Week Meal Package';
					else if($key=='meal_plan_per_day')
						$option_value =	$option_value.' Days Per Week';
					else if($key=='meal_plan_breakfast')
						$option_value =	$option_value.' Breakfast';
					else if($key=='meal_plan_lunch')
						$option_value =	$option_value.' Lunch';
					else if($key=='meal_plan_dinner')
						$option_value =	$option_value.' Dinner';
					else if($key=='meal_plan_dessert')
						$option_value =	$option_value.' Dessert';
					else if($key=='meal_plan_snack')
						$option_value =	$option_value.' Snack';
					woocommerce_add_order_item_meta( $item_id, $option_name, $option_value );           
					
					
				}
			}
			$subdada['active']=1;
			
			$org_data=mnd_wds_get_mel_plan_price($cvalue);
			
			woocommerce_add_order_item_meta($item_id,'meal_subscription_data', $subdada );           
			woocommerce_add_order_item_meta($item_id,'meal_subscription_org_data', $org_data );           
			
			//woocommerce_add_order_item_meta($item_id,'meal_subscription_next_date', $subscription );           
		}
	}
}




function mng_woocommerce_order_again_cart_item_data($cart_item_meta, $product, $order){
global $woocommerce;
/*
print_r($cart_item_meta['_other_options']);
exit;*/


$extra_field_array=mng_get_meal_array();
foreach($extra_field_array as $key=>$val) {

if(empty($cart_item_meta[$key]))
	$cart_item_meta[$key] = array();
	$cart_item_meta['_other_options']= esc_attr($_POST[sanitize_title($key)]);
}

return $cart_item_meta;
}



	

function mng_woocommerce_default_country() {
  return 'US'; // country code
}


function mng_custom_error(){
	global $woocommerce;
	if(isset($_POST['deliverydate'])){
		if($_POST['deliverydate']=='')
			wc_add_notice( sprintf( __( "Please select delivery date", "your-theme-language" ) ) ,'error' );
	}
	return true;
}


add_action( 'woocommerce_admin_order_data_after_billing_address', 'wds_mng_my_custom_checkout_field_display_admin_order_meta', 10, 1 ); 
function wds_mng_my_custom_checkout_field_display_admin_order_meta($order){
	//echo '<p><strong>'.__('Subscription Id').':</strong> ' . get_post_meta($order->id,'subscription_id',true) . '</p>';
	//echo '<p><strong>'.__('Last Payment Received Date').':</strong> ' . get_post_meta($order->id,'meal_subscription_data',true) . '</p>';	
}

add_action('woocommerce_before_order_itemmeta','wds_add_order_meta_after_order_place',1,2);
function wds_add_order_meta_after_order_place($a,$b){
	if(strlen($b['meal_subscription_data'])>10){
	$org= unserialize($b['meal_subscription_org_data']);
	$org2= unserialize($b['meal_subscription_data']);
//	$f=mnd_wds_get_mel_plan_price($org);


	
	

	
	
	$d=$org['package_data'][$org2['meal_plan']];
	$data_F=array();
	foreach($d as $final_data){
		$final_data['qry']=$final_data['qty'];
		$data_F[]=$final_data;
	}
	
	$w=$org['package_extra'];

	foreach($w as $final_data){
		$qry=0;
		if($final_data['title']=='BF' && $org2['meal_plan_breakfast']>0)
			$qry=$org2['meal_plan_breakfast'];			
		if($final_data['title']=='LN' && $org2['meal_plan_lunch']>0)
			$qry=$org2['meal_plan_lunch'];			
		if($final_data['title']=='DN' && $org2['meal_plan_dinner']>0)
			$qry=$org2['meal_plan_dinner'];	
		if($final_data['title']=='DR' && $org2['meal_plan_dessert']>0)
			$qry=$org2['meal_plan_dessert'];				
		if($final_data['title']=='SN' && $org2['meal_plan_snack']>0)
			$qry=$org2['meal_plan_snack'];			
			
		if($qry>0){	
			$final_data['qry']=$qry;
			$final_data['extra']='yes';
			$data_F[]=$final_data;
		}
	}
	
	
	
//	print_r($data_F);
	
	$array=array('Delivery date','Meal Plan','Days Per Week');
	echo '<table cellspacing="0" class="display_meta  custommeta">
				<tbody>';
					foreach($array as $d){		
								if($b[$d]){
									echo '<tr>
												<th style="width:150px">'.$d.'</th>
												<td>'.$b[$d].'</td>
										 </tr>';
								}		
					}					
	echo  '</tbody></table>';
	
	
	echo '<table cellspacing="0" class="display_meta  custommeta" style="width:100%">
				<thead>
				
				<tr>
					<th style="width:300px" >SKU</th>
					<th style="width:40px;text-align:right">QTY</th>
					<th style="width:40px;text-align:right">Cost</th>										
				</tr></thead><tbody>';
	$fianl_qry=$fianl_price=0;
	foreach($data_F as $k){
		$t=$k['sku'];
		if($k['extra']=='yes'){
			$t =$t.'&nbsp;&nbsp;(Extra)';
			$tot_qty= $k['qry'];	
			$tot_qty_price=$k['price']*$tot_qty;			
		}else{
			$t =$t.'&nbsp;&nbsp;(Includes in package)';
			$tot_qty= $k['qry'];	
			$tot_qty=($org2['meal_plan_per_day']*$tot_qty)*$org2['meal_plan'];
			$tot_qty_price=$k['price']*$tot_qty;
		}
		$fianl_qry +=$tot_qty;		
		$fianl_price +=$tot_qty_price;
		
		echo  '<tr>
					<td>'.$t.'</td>
					<td style="text-align:right" >'.$tot_qty.'</td>
					<td style="text-align:right">$'.$tot_qty_price.'</td>										
				</tr>';
	}			
	echo '<tfoot><tr>
		<th>Total </th>
		<th style="text-align:right" >'.$fianl_qry.'</th>
		<th style="text-align:right" >$'.$fianl_price.'</th>				
		</tr></tfoot>';
	echo  '</tbody></table>';
	echo  '<style>#order_line_items .display_meta{display:none} .custommeta{ display:block !important} .wc-order-data-row .refund-items, .wc-order-data-row .add-items .description{display:none}</style>';		
	}
}