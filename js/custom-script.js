var selected_db;
selected_db='';
jQuery(document).ready(function(){

	jQuery('.custombutton .single_add_to_cart_button').click(function(event){
		if(selected_db=='' || selected_db==undefined){															  			alert('Please select delivery date');
			event.preventDefault();
		}
	})
	
	jQuery('#loop_1 input[type="radio"]').change(function(){
			jQuery('.included').hide();
			jQuery('#custom_row_'+jQuery(this).val()).show();
			
			jQuery('#loop_2').not(":animated").slideDown("slow",function(){
				var aTag = jQuery("div[id='loop_2']");
				
				var h=0//jQuery('#custom_footer').height();			
				var t=eval(aTag.offset().top)-(eval(h));
				
				jQuery('html,body').animate({scrollTop: t},'slow');
			});
	});
	
	jQuery('#loop_2 input[type="radio"]').change(function(){
		jQuery('#loop_3').not(":animated").slideDown("slow",function(){
			jQuery('#single_variation1').show();
			var aTag = jQuery("div[id='loop_3']");
				var h=0//jQuery('#custom_footer').height();			
				var t=eval(aTag.offset().top);			
			jQuery('html,body').animate({scrollTop: t},'slow');
		});
	});
	
	//var $j = jQuery.noConflict();
	
							/**/
							
jQuery('#loop_1 input[type="radio"],#loop_2 input[type="radio"],#loop_3 input[type="checkbox"],#loop_3 select').click(function(){
  
	  update_cart_summary();
  });
  
  
  
  jQuery(".started").click(function(e){	
	
	
			var Height=realWidth(jQuery('#loop_1'));
	
	
	
	
			jQuery("#loop_1").slideDown("slow");
			var aTag = jQuery("a[id='bm_1']");
			var h=0;//jQuery('#custom_footer').height();
			var t=eval(aTag.offset().top)-eval(h);
			foter=jQuery('#custom_footer');
			foter.css('padding-top',(eval(jQuery(window).height())-eval(Height))+'px');

			
//			alert(eval(jQuery(window).height())-(eval(foter_ofset)-eval(t)));
			//
			jQuery('html,body').animate({scrollTop: t},'slow');
		});

	
	
})

function realWidth(obj){
    var clone = obj.clone();
    clone.css("visibility","hidden");
    jQuery('body').append(clone);
    var width = clone.outerHeight();
    clone.remove();
    return width;
}	

function update_cart_summary(dateText){	
  					//console.log("clicked");
					var selected=jQuery('#loop_1 input[type="radio"]:checked');
					var week_day=jQuery('#loop_2 input[type="radio"]:checked');			
					var price=meal_plan[selected.val()];
					

					var price=(eval(price)*(eval(week_day.val()))*selected.val());
					
					

					
					var breakfast_radio=jQuery('#loop_3 input[name="meal_plan_breakfast_radio"]:checked');			
					var cus;	
					cus='';
					if(breakfast_radio.val()=='1'){

						price +=eval(jQuery('#loop_3 select[name="meal_plan_breakfast"]').val())*(eval(breakfast_amount));
						//console.log("Brakfast amount Price ;"+price);
						cus ='<p>Extra Brakfast: <span>'+jQuery('#loop_3 select[name="meal_plan_breakfast"]').val()+' Breakfast</span></p>';
					}
					
					
					var breakfast_radio=jQuery('#loop_3 input[name="meal_plan_lunch_radio"]:checked');			

					if(breakfast_radio.val()==1){
						price +=eval(jQuery('#loop_3 select[name="meal_plan_lunch"]').val())*(eval(lunch_amount));
						//console.log("Brakfast amount Price ;"+price);
						cus +='<p>Extra Lunch: <span>'+jQuery('#loop_3 select[name="meal_plan_lunch"]').val()+' Lunch</span></p>';						
					}
					
					var breakfast_radio=jQuery('#loop_3 input[name="meal_plan_dinner_radio"]:checked');			
					if(breakfast_radio.val()=='1'){
						price +=eval(jQuery('#loop_3 select[name="meal_plan_dinner"]').val())*(eval(dinner_amount));
						//console.log("Brakfast amount Price ;"+price);
						cus +='<p>Extra Dinner: <span>'+jQuery('#loop_3 select[name="meal_plan_dinner"]').val()+' Dinner</span></p>';						
					}
					var breakfast_radio=jQuery('#loop_3 input[name="meal_plan_dessert_radio"]:checked');			
					if(breakfast_radio.val()=='1'){
						price +=eval(jQuery('#loop_3 select[name="meal_plan_dessert"]').val())*(eval(dessert_amount));
						//console.log("Brakfast amount Price ;"+price);
						cus +='<p>Extra Dessert: <span>'+jQuery('#loop_3 select[name="meal_plan_dessert"]').val()+' Dessert</span></p>';						
					}
					
					var breakfast_radio=jQuery('#loop_3 input[name="meal_plan_snack_radio"]:checked');			
					if(breakfast_radio.val()=='1'){
						price +=eval(jQuery('#loop_3 select[name="meal_plan_snack"]').val())*(eval(snack_amount));
						//console.log("Brakfast amount Price ;"+price);
						cus +='<p>Extra Snack: <span>'+jQuery('#loop_3 select[name="meal_plan_snack"]').val()+' Snack</span></p>';												
					}
					
					price=price.toFixed(2);
					
				jQuery(".single_variation").show();
				jQuery(".single_variation").html("$"+price);
			
				var ord_list = '<p >Delivery Date: <span id="d_date" ><span class="date-picker" id="deliveryselect"><input type="text" id="deliverydate" name="deliverydate"  placeholder="Select Delivery Date First" value="'+selected_db+'"></span ></span></p>';
				//var ord_list ='<p class="ddate">Delivery Date: </p><span>''</span>';
				ord_list +=	'<div class="clearfix"></div><p>Meal Plan: <span>'+jQuery('#loop_1 input[type="radio"]:checked').data('select')+'</span></p>';
				ord_list += '<p>Meal Day: <span>'+jQuery('#loop_2 input[type="radio"]:checked').data('select')+'</span></p>';
				ord_list +=cus;
				jQuery("#product-detail-list").html(ord_list);
				
				jQuery('#deliverydate').datepicker({
								minDate: 0,
								dateFormat:'mm/dd/yy',
								changeMonth: true,
								changeYear: true,
								beforeShowDay: function(date) {
									var day = date.getDay();
									newdate=addDays(1);
									if(daydiff( newdate,date)<=0){
										return [false];
									}else{
										if(day==1 || day==4){
											return [true];
										}	
										else
											return [false];
									}		
								},
								onSelect: function(dateText) {
									selected_db=dateText;
									update_cart_summary();								
								}
								
							});
				
				
			}
			function daydiff(first, second) {
			    return (second-first)/(1000*60*60*24);
			}
			function addDays(days) {
			    var result = new Date();
			    result.setDate(result.getDate() + days);
			    return result;
			}
						

			
			
			

		
									