<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( function_exists( 'current_user_can' ) ) {
	if ( ! current_user_can( 'manage_options' ) ) {
		die( 'Access Denied' );
	}
}
if ( ! function_exists( 'current_user_can' ) ) {
	die( 'Access Denied' );
}
require_once("head_banner.php");
function origincode_contact_html_styles($rows){
	global $wpdb;
	?>
    <script language="javascript">
		function ordering(name,as_or_desc) {
			document.getElementById('asc_or_desc').value=as_or_desc;		
			document.getElementById('order_by').value=name;
			document.getElementById('admin_form').submit();
		}
		function saveorder() {
			document.getElementById('saveorder').value="save";
			document.getElementById('admin_form').submit();
			
		}
		function listItemTask(this_id,replace_id) {
			document.getElementById('oreder_move').value=this_id+","+replace_id;
			document.getElementById('admin_form').submit();
		}
		function doNothing() {
			var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
			if( keyCode == 13 ) {

				if(!e) var e = window.event;

				e.cancelBubble = true;
				e.returnValue = false;

				if (e.stopPropagation) {
					e.stopPropagation();
					e.preventDefault();
				}
			}
		}
	</script>

<div class="wrap">
	<?php origincode_contact_drawFreeBanner('yes');?>
	<div id="poststuff">
		<div id="origincode_contacts-list-page">
			<form method="post"  onkeypress="doNothing()" action="admin.php?page=origincode_forms_main_page" id="admin_form" name="admin_form">
				<?php if(!isset($_GET["theme_id"]))$_GET["theme_id"]='';?>
			<h2>
                <?php _e('OriginCode Forms Themes', 'origincode_contact'); ?>
				<a onclick="alert('This option is disabled for free version. Please upgrade to pro license to be able to use it.');" class="add-new-h2" >
                    <?php _e('Add New Theme', 'origincode_contact'); ?> <i>(pro)</i>
                </a>
			</h2>
			<?php if ( isset( $_POST['serch_or_not'] ) ) { $serch_value = $_POST['serch_or_not'] == "search" ? esc_html( stripslashes( $_POST['search_events_by_title'] ) ) : "";}
            $serch_fields='<div class="alignleft actions"">				
			<div class="alignleft actions">
				<input type="button" value="Search" onclick="document.getElementById(\'page_number\').value=\'1\'; document.getElementById(\'serch_or_not\').value=\'search\';
				 document.getElementById(\'admin_form\').submit();" class="button-secondary action">
				 <input type="button" value="Reset" onclick="window.location.href=\'admin.php?page=origincode_forms_main_page\'" class="button-secondary action">
			</div>';
			?>
			<table class="wp-list-table widefat fixed pages">
				<thead>
				 <tr>
					<th scope="col" id="id" style="width:30px" ><span><?php _e('ID', 'origincode_contact'); ?></span><span class="sorting-indicator"></span></th>
					<th scope="col" id="name" style="width:85px" ><span><?php _e('Name', 'origincode_contact'); ?></span><span class="sorting-indicator"></span></th>
					<th scope="col" id="prod_count"  style="width:75px;" ><span><?php _e('Last Update', 'origincode_contact'); ?></span><span class="sorting-indicator"></span></th>
					<th style="width:40px"><?php _e('Delete', 'origincode_contact'); ?></th>
				 </tr>
				</thead>
				<tbody>
				 <?php
				 $trcount = 1;
				 for ( $i = 0; $i < count( $rows ); $i ++ ) :
					 $trcount ++;
					 $ka0 = 0;
					 $ka1 = 0;
					 $move_up = "";
					 if ( isset( $rows[ $i - 1 ]->id ) ) {
						 if ( isset( $rows[ $i ]->hc_width ) ) {
							 if ( $rows[ $i ]->hc_width == $rows[ $i - 1 ]->hc_width ) {
								 $x1  = $rows[ $i ]->id;
								 $x2  = $rows[ $i - 1 ]->id;
								 $ka0 = 1;
							 } else {
								 $jj = 2;
								 while ( isset( $rows[ $i - $jj ] ) ) {
									 if ( $rows[ $i ]->hc_width == $rows[ $i - $jj ]->hc_width ) {
										 $ka0 = 1;
										 $x1  = $rows[ $i ]->id;
										 $x2  = $rows[ $i - $jj ]->id;
										 break;
									 }
									 $jj ++;
								 }
							 }

							 if ( $ka0 ) {
								 $move_up = '<span><a href="#reorder" onclick="return listItemTask(\'' . $x1 . '\',\'' . $x2 . '\')" title="Move Up">   <img src="' . plugins_url( 'images/uparrow.png', __FILE__ ) . '" width="16" height="16" border="0" alt="Move Up"></a></span>';
							 }
						 }
					 }
					 if ( isset( $rows[ $i + 1 ]->id ) ) {
						 if ( isset( $rows[ $i ]->hc_width ) ) {
							 if ( $rows[ $i ]->hc_width == $rows[ $i + 1 ]->hc_width ) {
								 $x1  = $rows[ $i ]->id;
								 $x2  = $rows[ $i + 1 ]->id;
								 $ka1 = 1;
							 } else {
								 $jj = 2;
								 while ( isset( $rows[ $i + $jj ] ) ) {
									 if ( $rows[ $i ]->hc_width == $rows[ $i + $jj ]->hc_width ) {
										 $ka1 = 1;
										 $x1  = $rows[ $i ]->id;
										 $x2  = $rows[ $i + $jj ]->id;
										 break;
									 }
									 $jj ++;
								 }
							 }

							 $move_down = "";

							 if ( $ka1 ) {
								 $move_down = '<span><a href="#reorder" onclick="return listItemTask(\'' . $x1 . '\',\'' . $x2 . '\')" title="Move Down">  <img src="' . plugins_url( 'images/downarrow.png', __FILE__ ) . '" width="16" height="16" border="0" alt="Move Down"></a></span>';
							 }
						 }
					 }
					 if ( ! isset( $rows[ $i ]->par_name ) ) {
						 $rows[ $i ]->par_name = '';
					 }
					 $uncat = $rows[ $i ]->par_name;
					 if ( isset( $rows[ $i ]->last_update ) ) {
						 $pr_count = $rows[ $i ]->last_update;
					 } else {
						 $pr_count = 0;
					 }


					 ?>
					<tr <?php if($trcount%2==0) { echo 'class="has-background"';}?>>
						<td><?php echo $rows[$i]->id; ?></td>
						<td><a  href="admin.php?page=origincode_forms_theme_options&theme_id=<?php echo esc_html($rows[$i]->id)?>"><?php echo esc_html(stripslashes($rows[$i]->name)); ?></a></td>
						<td><?php if(!($pr_count)){echo '0';} else{ echo $rows[$i]->last_update;} ?></td>
						<td><?php if($rows[$i]->id!=1):?><a  href="#" onclick="alert('This option is disabled for free version. Please upgrade to pro license to be able to use it.');	" >Delete <i>(pro)</i></a><?php endif; ?></td>
					</tr> 
				 <?php endfor; ?>
				</tbody>
			</table>
			<input type="hidden" name="oreder_move" id="oreder_move" value="" />
			<input type="hidden" name="asc_or_desc" id="asc_or_desc" value="<?php if(isset($_POST['asc_or_desc'])) echo esc_attr($_POST['asc_or_desc']);?>"  />
			<input type="hidden" name="order_by" id="order_by" value="<?php if(isset($_POST['order_by'])) echo esc_attr($_POST['order_by']);?>"  />
			<input type="hidden" name="saveorder" id="saveorder" value="" />
			</form>
		</div>
	</div>
</div>
    <?php
}
function origincode_contact_html_editstyles($param_values, $op_type, $style_themes){?>
<!-- STYLES CUSTOMIZATION PAGE -->
<div class="wrap" id="origincode_theme_options_page">
<?php origincode_contact_drawFreeBanner('yes');?>
<div id="poststuff">
		<?php $path_site = plugins_url("Front_images", __FILE__); ?>
		<input type="hidden" id="type" name="type" value="<?php echo isset($_POST['type']) ? sanitize_text_field($_POST['type']) : '1'; ?>"/> 
          <script>
        	function origincode_contact_updateInput(ish){
			    document.getElementById("themeName").value = ish;
			}      
		</script>
		<div class="origincode_tabs_block">
			
			<ul id="" class="origincode_contact_top_tabs">
				<?php
				foreach($style_themes as $style_theme){
					if($style_theme->id != $_GET['theme_id']){
					?>
						<li>
							<a href="#" onclick="window.location.href='admin.php?page=origincode_forms_theme_options&theme_id=<?php echo esc_html($style_theme->id); ?>'" ><?php echo esc_html($style_theme->name); ?></a>
						</li>
					<?php
					}
					else{ ?>
						<li class="active fixed-tabs">
                            <div class="origc_cut_border">
                                <div class="origc_cut_inl_border"></div>
                            </div>
                            <span style="display: none"><?php echo esc_html(stripslashes($style_theme->name));?></span>
							<input onkeyup="origincode_contact_updateInput(this.value)" class="text_area" type="text" name="name" id="name" maxlength="250" value="<?php echo esc_html(stripslashes($style_theme->name));?>" style="background:url(<?php echo plugins_url('../images/edit.png', __FILE__) ;?>) no-repeat #f3f4f8;" />
						</li>
					<?php	
					}
				}
				?>
				<li class="add-new">
					<a onclick="alert('This option is disabled for free version. Please upgrade to pro license to be able to use it.');	"></a>
				</li>
			</ul>
		</div>
		<div id="post-body-content">
			<div id="post-body-heading">
				<h3>
                    <?php _e('Theme Options', 'origincode_contact'); ?>
                    <p class="origincode_contact_theme_pro_attention">
                        <?php _e('These options are disabled in Lite version. Please, upgrade to PRO license to be able to use.', 'origincode_contact'); ?>
                    </p>
                </h3>
				<a onclick="alert('This option is disabled for free version. Please upgrade to pro license to be able to use it.');" class="save-origincode_contact-options button-primary">Save  <i>(pro)</i></a>
			</div>
			<div class="origincode_contact_black_overlay">
				<div class="options-block">
					<form action="admin.php?page=origincode_forms_theme_options&theme_id=<?php echo esc_url($_GET["theme_id"]); ?>&task=save" method="post" id="adminForm" name="adminForm">
						<input type="hidden" id="themeName" name="themeName" value="">
						<div class="origincode-contact-general-options-column origincode-contact-general-options-left">
							<div class="origincode-contact-general-options-block">
								<h3><?php _e('Form Block Styles', 'origincode_contact'); ?></h3>
								<div class="has-background">
									<label for="form_wrapper_width">
                                        <?php _e('Form Width', 'origincode_contact'); ?>
                                    </label>
									<div class="slider-container">
										<input disabled id="form_wrapper_width"  data-slider-range="1,100"  type="text" data-slider="true"  data-slider-highlight="true" value="<?php echo $param_values['form_wrapper_width']; ?>" />
										<span><?php echo $param_values['form_wrapper_width']; ?>%</span>
									</div>
								</div>
								<div>
									<label for="form_wrapper_background_type">
                                        <?php _e('Form Background Type', 'origincode_contact'); ?>
                                    </label>
									<select id="form_wrapper_background_type" name="params[form_wrapper_background_type]">
										<option <?php if($param_values['form_wrapper_background_type'] == 'color'){ echo 'selected'; } ?> value="color">Color</option>
										<option <?php if($param_values['form_wrapper_background_type'] == 'transparent'){ echo 'selected'; } ?> value="transparent">Transparent</option>
										<option <?php if($param_values['form_wrapper_background_type'] == 'gradient'){ echo 'selected'; } ?> value="gradient">Gradient</option>
									</select>
								</div>
								<div class="has-background">
									<label for="form_wrapper_background_color">
                                        <?php _e('Form Background Color', 'origincode_contact'); ?>
                                    </label>
									<?php
									$bg=$param_values['form_wrapper_background_type'];
									$color = explode(',', $param_values['form_wrapper_background_color']);
									?>
									<input type="text" disabled class="color <?php if($bg == 'gradient'){echo "half";} ?> form_background_color form_first_background_color" value="#<?php echo $color[0]; ?>" size="10" />
									<input type="text" disabled class="color half <?php if($bg == 'color' or $bg == 'transparent' ){echo "none";} ?> form_background_color form_second_background_color" value="#<?php echo $color[1]; ?>" size="10" />

									<input id="form_wrapper_background_color" type="hidden" value="<?php echo esc_html($param_values['form_wrapper_background_color']) ; ?>" />
								</div>
								<div>
									<label for="form_border_size">
                                        <?php _e('Form Border Size', 'origincode_contact'); ?>
                                    </label>
									<input type="number" disabled id="form_border_size" value="<?php echo esc_html($param_values['form_border_size']); ?>" class="text" />
									<span>px</span>
								</div>
								<div>
									<label for="form_border_color">
                                        <?php _e('Form Border Color', 'origincode_contact'); ?>
                                    </label>
									<input type="text" disabled class="color" id="form_border_color" value="#<?php echo esc_html($param_values['form_border_color']); ?>" size="10" />
								</div>
								<div>
									<label for="form_show_title">
                                        <?php _e('Form Show Title', 'origincode_contact'); ?>
                                    </label>
									<input type="hidden" value="off" />
									<input type="checkbox" disabled id="form_show_title"  <?php if($param_values['form_show_title']  == 'on'){ echo 'checked="checked"'; } ?>  value="on" />
								</div>
								<div>
									<label for="form_title_size">
                                        <?php _e('Form Title Size', 'origincode_contact'); ?>
                                    </label>
									<input type="number" disabled id="form_title_size" value="<?php echo esc_html($param_values['form_title_size']); ?>" class="text" />
									<span>px</span>
								</div>
								<div>
									<label for="form_title_color">
                                        <?php _e('Form Title Color', 'origincode_contact'); ?>
                                    </label>
									<input type="text" disabled class="color" id="form_title_color" value="#<?php echo esc_html($param_values['form_title_color']); ?>" size="10" />
								</div>
							</div>
							<div class="origincode-contact-general-options-block">
								<h3><?php _e('Textarea Styles', 'origincode_contact'); ?></h3>
								<div>
									<label for="form_textarea_has_background">
                                        <?php _e('Textarea Has Background', 'origincode_contact'); ?>
                                    </label>
									<input type="hidden"  value="off"  />
									<input type="checkbox" disabled id="form_textarea_has_background"  <?php if($param_values['form_textarea_has_background']  == 'on'){ echo 'checked="checked"'; } ?>  value="on" />
								</div>
								<div>
									<label for="form_textarea_background_color">
                                        <?php _e('Textarea Background Color', 'origincode_contact'); ?>
                                    </label>
									<input type="text" disabled class="color" id="form_textarea_background_color" value="#<?php echo esc_html($param_values['form_textarea_background_color']) ; ?>" size="10" />
								</div>
								<div>
									<label for="form_textarea_border_size">
                                        <?php _e('Textarea Border Size', 'origincode_contact'); ?>
                                    </label>
									<input type="number" disabled id="form_textarea_border_size" value="<?php echo esc_html($param_values['form_textarea_border_size']); ?>" class="text" />
									<span>px</span>
								</div>
								<div>
									<label for="form_textarea_border_radius">
                                        <?php _e('Textarea Border Radius', 'origincode_contact'); ?>
                                    </label>
									<input type="number" disabled id="form_textarea_border_radius" value="<?php echo esc_html($param_values['form_textarea_border_radius']); ?>" class="text" />
									<span>px</span>
								</div>
								<div>
									<label for="form_textarea_border_color">
                                        <?php _e('Textarea Border Color', 'origincode_contact'); ?>
                                    </label>
									<input type="text" disabled class="color" id="form_textarea_border_color" value="#<?php echo esc_html($param_values['form_textarea_border_color']); ?>" size="10" />
								</div>
								<div>
									<label for="form_textarea_font_size">
                                        <?php _e('Textarea Font Size', 'origincode_contact'); ?>
                                    </label>
									<input type="number" disabled id="form_textarea_font_size" value="<?php echo esc_html($param_values['form_textarea_font_size']); ?>" class="text" />
									<span>px</span>
								</div>
								<div>
									<label for="form_textarea_font_color">
                                        <?php _e('Textarea Font Color', 'origincode_contact'); ?>
                                    </label>
									<input type="text" disabled class="color" id="form_textarea_font_color" value="#<?php echo esc_html($param_values['form_textarea_font_color']); ?>" size="10" />
								</div>
							</div>
							<div class="origincode-contact-general-options-block">
								<h3><?php _e('Checkbox Styles', 'origincode_contact'); ?></h3>
								<div>
									<label for="form_checkbox_size">
                                        <?php _e('Checkbox Size', 'origincode_contact'); ?>
                                    </label>
									<select id="form_checkbox_size" disabled>
										<option <?php if($param_values['form_checkbox_size'] == 'big'){ echo 'selected="selected"'; } ?> value="big">Big</option>
										<option <?php if($param_values['form_checkbox_size'] == 'medium'){ echo 'selected="selected"'; } ?> value="medium">Medium</option>
										<option <?php if($param_values['form_checkbox_size'] == 'small'){ echo 'selected="selected"'; } ?> value="small">Small</option>
									</select>
								</div>
								<div>
									<label for="form_checkbox_type">
                                        <?php _e('Checkbox Type', 'origincode_contact'); ?>
                                    </label>
									<select id="form_checkbox_type" disabled>
										<option <?php if($param_values['form_checkbox_type'] == 'circle'){ echo 'selected="selected"'; } ?> value="circle">Circle</option>
										<option <?php if($param_values['form_checkbox_type'] == 'square'){ echo 'selected="selected"'; } ?> value="square">Square</option>
									</select>
								</div>

								<div>
									<label for="form_checkbox_color">
                                        <?php _e('Checkbox Color', 'origincode_contact'); ?>
                                    </label>
									<input type="text" disabled class="color" id="form_checkbox_color" value="#<?php echo $param_values['form_checkbox_color']; ?>" size="10" />
								</div>
								<div>
									<label for="form_checkbox_hover_color">
                                        <?php _e('Checkbox Hover Color', 'origincode_contact'); ?>
                                    </label>
									<input type="text" disabled class="color" id="form_checkbox_hover_color" value="#<?php echo esc_html($param_values['form_checkbox_hover_color']); ?>" size="10" />
								</div>
								<div>
									<label for="form_checkbox_active_color">
                                        <?php _e('Checkbox Checked Color', 'origincode_contact'); ?>
                                    </label>
									<input type="text" disabled class="color" id="form_checkbox_active_color" value="#<?php echo esc_html($param_values['form_checkbox_active_color']); ?>" size="10" />
								</div>
							</div>

							<div class="origincode-contact-general-options-block">
								<h3><?php _e('Input-Radio Styles', 'origincode_contact'); ?></h3>
								<div>
									<label for="form_radio_size">
                                        <?php _e('Input-Radio Size', 'origincode_contact'); ?>
                                    </label>
									<select id="form_radio_size" disabled>
										<option <?php if($param_values['form_radio_size'] == 'big'){ echo 'selected="selected"'; } ?> value="big">Big</option>
										<option <?php if($param_values['form_radio_size'] == 'medium'){ echo 'selected="selected"'; } ?> value="medium">Medium</option>
										<option <?php if($param_values['form_radio_size'] == 'small'){ echo 'selected="selected"'; } ?> value="small">Small</option>
									</select>
								</div>
								<div>
									<label for="form_radio_type">
                                        <?php _e('Input-Radio Type', 'origincode_contact'); ?>
                                    </label>
									<select id="form_radio_type" disabled>
										<option <?php if($param_values['form_radio_type'] == 'circle'){ echo 'selected="selected"'; } ?> value="circle">Circle</option>
										<option <?php if($param_values['form_radio_type'] == 'square'){ echo 'selected="selected"'; } ?> value="square">Square</option>
									</select>
								</div>
								<div>
									<label for="form_radio_color">
                                        <?php _e('Input-Radio Color', 'origincode_contact'); ?>
                                    </label>
									<input type="text" disabled class="color" id="form_radio_color" value="#<?php echo esc_html($param_values['form_radio_color']); ?>" size="10" />
								</div>
								<div>
									<label for="form_radio_hover_color">
                                        <?php _e('Input-Radio Hover Color', 'origincode_contact'); ?>
                                    </label>
									<input type="text" disabled class="color" id="form_radio_hover_color" value="#<?php echo esc_html($param_values['form_radio_hover_color']); ?>" size="10" />
								</div>
								<div>
									<label for="form_radio_active_color">
                                        <?php _e('Input-Radio Checked Color', 'origincode_contact'); ?>
                                    </label>
									<input type="text" disabled class="color" id="form_radio_active_color" value="#<?php echo esc_html($param_values['form_radio_active_color']); ?>" size="10" />
								</div>
							</div>

                            <!-- file uploader styles -->
							<div class="origincode-contact-general-options-block">
								<h3><?php _e('File Uploader Styles', 'origincode_contact'); ?></h3>
								<div>
									<label for="form_file_has_background">
                                        <?php _e('FileBox Has Background', 'origincode_contact'); ?>
                                    </label>
									<input type="hidden" value="off"  />
									<input type="checkbox" disabled id="form_file_has_background"  <?php if($param_values['form_file_has_background']  == 'on'){ echo 'checked="checked"'; } ?>  value="on" />
								</div>
								<div>
									<label for="form_file_background">
                                        <?php _e('FileBox Background Color', 'origincode_contact'); ?>
                                    </label>
									<input type="text" disabled class="color" id="form_file_background" value="#<?php echo esc_html($param_values['form_file_background']); ?>" size="10" />
								</div>
								<div>
									<label for="form_file_border_size">
                                        <?php _e('FileBox Border Size', 'origincode_contact'); ?>
                                    </label>
									<input type="number" disabled id="form_file_border_size" value="<?php echo esc_html($param_values['form_file_border_size']); ?>" class="text" />
									<span>px</span>
								</div>
								<div>
									<label for="form_file_border_radius">
                                        <?php _e('FileBox Border Radius', 'origincode_contact'); ?>
                                    </label>
									<input type="number" disabled id="form_file_border_radius" value="<?php echo esc_html($param_values['form_file_border_radius']); ?>" class="text" />
									<span>px</span>
								</div>
								<div>
									<label for="form_file_border_color">
                                        <?php _e('FileBox Border Color', 'origincode_contact'); ?>
                                    </label>
									<input type="text" disabled class="color" id="form_file_border_color" value="#<?php echo esc_html($param_values['form_file_border_color']); ?>" size="10" />
								</div>
								<div>
									<label for="form_file_font_size">
                                        <?php _e('FileBox Font Size', 'origincode_contact'); ?>
                                    </label>
									<input type="number" disabled  id="form_file_font_size" value="<?php echo esc_html($param_values['form_file_font_size']); ?>" class="text" />
									<span>px</span>
								</div>
								<div>
									<label for="form_file_font_color">
                                        <?php _e('FileBox Font Color', 'origincode_contact'); ?>
                                    </label>
									<input type="text" disabled class="color" id="form_file_font_color" value="#<?php echo esc_html($param_values['form_file_font_color']); ?>" size="10" />
								</div>
								<div>
									<label for="form_file_button_text">
                                        <?php _e('Button Text', 'origincode_contact'); ?>
                                    </label>
									<input type="text" disabled id="form_file_button_text" value="<?php echo esc_html($param_values['form_file_button_text']); ?>"/>
								</div>
								<div>
									<label for="form_file_button_background_color">
                                        <?php _e('Button Background Color', 'origincode_contact'); ?>
                                    </label>
									<input type="text" disabled class="color" id="form_file_button_background_color" value="#<?php echo esc_html($param_values['form_file_button_background_color']); ?>" size="10" />
								</div>
								<div>
									<label for="form_file_button_background_hover_color">
                                        <?php _e('Button Background Hover Color', 'origincode_contact'); ?>
                                    </label>
									<input type="text" disabled class="color" id="form_file_button_background_hover_color" value="#<?php echo esc_html($param_values['form_file_button_background_hover_color']); ?>" size="10" />
								</div>
								<div>
									<label for="form_file_button_text_color">
                                        <?php _e('Button Text Color', 'origincode_contact'); ?>
                                    </label>
									<input type="text" disabled class="color" id="form_file_button_text_color" value="#<?php echo esc_html($param_values['form_file_button_text_color']); ?>" size="10" />
								</div>
								<div>
									<label for="form_file_button_text_hover_color">
                                        <?php _e('Button Text Hover Color', 'origincode_contact'); ?>
                                    </label>
									<input type="text" disabled class="color" id="form_file_button_text_hover_color" value="#<?php echo esc_html($param_values['form_file_button_text_hover_color']); ?>" size="10" />
								</div>

								<div>
									<label for="form_file_has_icon">
                                        <?php _e('FileBox Button Has Icon', 'origincode_contact'); ?>
                                    </label>
									<input type="hidden" value="off"  />
									<input type="checkbox" disabled id="form_file_has_icon"  <?php if($param_values['form_file_has_icon']  == 'on'){ echo 'checked="checked"'; } ?> value="on" />
								</div>

								<div class="has-height">
									<label for="form_file_icon_style">
                                        <?php _e('Button\'s Icon Style', 'origincode_contact'); ?>
                                    </label>
									<div class="icons-block">
										<ul>
											<li <?php if($param_values['form_file_icon_style']=="originicons-paperclip"){echo 'class="active"';} ?> title="Attachment Icon"><i class="originicons-paperclip"></i><input  type="radio" value="originicons-paperclip" /></li>
											<li <?php if($param_values['form_file_icon_style']=="originicons-camera"){echo 'class="active"';} ?> title="Photo Icon"><i class="originicons-camera"></i><input type="radio"  value="originicons-camera" /></li>
											<li <?php if($param_values['form_file_icon_style']=="originicons-picture-o"){echo 'class="active"';} ?> title="Picture Icon"><i class="originicons-picture-o"></i><input type="radio" disabled value="originicons-picture-o" /></li>
											<li <?php if($param_values['form_file_icon_style']=="originicons-file"){echo 'class="active"';} ?> title="File Icon"><i class="originicons-file"></i><input type="radio" disabled value="originicons-file" /></li>
											<li <?php if($param_values['form_file_icon_style']=="originicons-dropbox"){echo 'class="active"';} ?> title="Box Add Icon"><i class="originicons-dropbox"></i><input type="radio" disabled value="originicons-dropbox" /></li>
											<li <?php if($param_values['form_file_icon_style']=="originicons-cloud"){echo 'class="active"';} ?> title="Cloud Icon"><i class="originicons-cloud"></i><input type="radio" disabled value="originicons-cloud" /></li>
											<li <?php if($param_values['form_file_icon_style']=="originicons-cloud-upload"){echo 'class="active"';} ?> title="Upload Cloud Icon"><i class="originicons-cloud-upload"></i><input type="radio" disabled value="originicons-cloud-upload" /></li>
											<li <?php if($param_values['form_file_icon_style']=="originicons-download"){echo 'class="active"';} ?> title="Download Icon"><i class="originicons-download"></i><input type="radio" disabled value="originicons-download" /></li>
											<li <?php if($param_values['form_file_icon_style']=="originicons-cloud-download"){echo 'class="active"';} ?> title="Word Icon"><i class="originicons-cloud-download"></i><input type="radio" disabled value="originicons-cloud-download" /></li>
											<li <?php if($param_values['form_file_icon_style']=="originicons-file-pdf-o"){echo 'class="active"';} ?> title="PDF Icon"><i class="originicons-file-pdf-o"></i><input type="radio" disabled value="originicons-file-pdf-o" /></li>
											<li <?php if($param_values['form_file_icon_style']=="originicons-file-text"){echo 'class="active"';} ?> title="file-text Icon"><i class="originicons-file-text"></i><input type="radio" disabled value="originicons-file-text" /></li>
											<li <?php if($param_values['form_file_icon_style']=="originicons-file-excel-o"){echo 'class="active"';} ?> title="Excel Icon"><i class="originicons-file-excel-o"></i><input type="radio" disabled value="originicons-file-excel-o" /></li>
											<li <?php if($param_values['form_file_icon_style']=="originicons-file-powerpoint-o"){echo 'class="active"';} ?> title="powerpoint-o Icon"><i class="originicons-file-powerpoint-o"></i><input type="radio" disabled value="originicons-file-powerpoint-o" /></li>
											<li <?php if($param_values['form_file_icon_style']=="originicons-file-zip-o"){echo 'class="active"';} ?> title="Zip Icon"><i class="originicons-file-zip-o"></i><input type="radio" disabled value="originicons-file-zip-o" /></li>
											<li <?php if($param_values['form_file_icon_style']=="originicons-file-audio-o"){echo 'class="active"';} ?> title="CSS Icon"><i class="originicons-file-audio-o"></i><input type="radio" disabled value="originicons-file-audio-o" /></li>
											<li <?php if($param_values['form_file_icon_style']=="originicons-floppy-o"){echo 'class="active"';} ?> title="floppy-o Icon"><i class="originicons-floppy-o"></i><input type="radio" disabled value="originicons-floppy-o" /></li>
											<li <?php if($param_values['form_file_icon_style']=="originicons-music"){echo 'class="active"';} ?> title="Music Icon"><i class="originicons-music"></i><input type="radio" disabled value="originicons-music" /></li>
											<li <?php if($param_values['form_file_icon_style']=="originicons-film"){echo 'class="active"';} ?> title="Video Icon"><i class="originicons-film"></i><input type="radio" disabled value="originicons-film" /></li>
											<li <?php if($param_values['form_file_icon_style']=="originicons-camera-retro"){echo 'class="active"';} ?> title="Camera Icon"><i class="originicons-camera-retro"></i><input type="radio" disabled value="originicons-camera-retro" /></li>
											<li <?php if($param_values['form_file_icon_style']=="originicons-gift"){echo 'class="active"';} ?> title="Upload Gift"><i class="originicons-gift"></i><input type="radio" disabled value="originicons-gift" /></li>
										</ul>
									</div>
								</div>
								<div>
									<label for="form_file_icon_position">
                                        <?php _e('Button\'s Icon Position', 'origincode_contact'); ?>
                                    </label>
									<select id="form_file_icon_position" disabled>
										<option <?php if($param_values['form_file_icon_position'] == 'left'){ echo 'selected="selected"'; } ?> value="left">Before Text</option>
										<option <?php if($param_values['form_file_icon_position'] == 'right'){ echo 'selected="selected"'; } ?> value="right">After Text</option>
									</select>
								</div>
								<div>
									<label for="form_file_icon_color">
                                        <?php _e('Button\'s Icon Color', 'origincode_contact'); ?>
                                    </label>
									<input type="text" disabled class="color" id="form_file_icon_color" value="#<?php echo esc_html($param_values['form_file_icon_color']); ?>" size="10" />
								</div>
								<div>
									<label for="form_file_icon_hover_color">
                                        <?php _e("Button's Icon Hover Color", 'origincode_contact'); ?>
                                    </label>
									<input type="text" disabled class="color" id="form_file_icon_hover_color" value="#<?php echo esc_html($param_values['form_file_icon_hover_color']); ?>" size="10" />
								</div>
							</div>
                            <!-- end file uploader styles -->

                            <!-- custom css -->
                            <div class="origincode-contact-general-options-block">
                                <h3><?php _e('Custom Styles(CSS)', 'origincode_contact'); ?></h3>
                                <div>
                                    <textarea style="width: 100%;height: 150px;"  disabled >Write Your CSS Code Here</textarea>
                                </div>
                            </div>
                            <!-- end custom css -->


						</div>



						<div class="origincode-contact-general-options-column origincode-contact-general-options-right">
							<div class="origincode-contact-general-options-block">
								<h3><?php _e('Labels Styles', 'origincode_contact'); ?></h3>
								<div>
									<label for="form_label_size">
                                        <?php _e('Label Size', 'origincode_contact'); ?>
                                    </label>
									<input type="number" disabled id="form_label_size" value="<?php echo esc_html($param_values['form_label_size']); ?>" class="text" />
									<span>px</span>
								</div>
								<div>
									<label for="form_label_font_family">
                                        <?php _e('Label Font Family', 'origincode_contact'); ?>
                                    </label>
									<select id="form_label_font_family" disabled>
										<option <?php selected( '', $param_values['form_label_font_family'], true ); ?> value="">Default</option>
										<option <?php selected( 'Arial,Helvetica Neue,Helvetica,sans-serif', $param_values['form_label_font_family'], true ); ?> value="Arial,Helvetica Neue,Helvetica,sans-serif">Arial *</option>
										<option <?php selected( 'Arial Black,Arial Bold,Arial,sans-serif', $param_values['form_label_font_family'], true ); ?> value="Arial Black,Arial Bold,Arial,sans-serif">Arial Black *</option>
										<option <?php selected( 'Arial Nicon,Arial,Helvetica Neue,Helvetica,sans-serif', $param_values['form_label_font_family'], true ); ?> value="Arial Nicon,Arial,Helvetica Neue,Helvetica,sans-serif">Arial Nicon *</option>
										<option <?php selected( 'Courier,Verdana,sans-serif', $param_values['form_label_font_family'], true ); ?> value="Courier,Verdana,sans-serif">Courier *</option>
										<option <?php selected( 'Georgia,Times New Roman,Times,serif', $param_values['form_label_font_family'], true ); ?> value="Georgia,Times New Roman,Times,serif">Georgia *</option>
										<option <?php selected( 'Times New Roman,Times,Georgia,serif', $param_values['form_label_font_family'], true ); ?> value="Times New Roman,Times,Georgia,serif">Times New Roman *</option>
										<option <?php selected( 'Verdana,sans-serif', $param_values['form_label_font_family'], true ); ?> value="Verdana,sans-serif">Verdana *</option>
										<option <?php selected( 'American Typewriter,Georgia,serif', $param_values['form_label_font_family'], true ); ?> value="American Typewriter,Georgia,serif">American Typewriter</option>
										<option <?php selected( 'Bookman Old Style,Georgia,Times New Roman,Times,serif', $param_values['form_label_font_family'], true ); ?> value="Bookman Old Style,Georgia,Times New Roman,Times,serif">Bookman Old Style</option>
										<option <?php selected( 'Calibri,Helvetica Neue,Helvetica,Arial,Verdana,sans-serif', $param_values['form_label_font_family'], true ); ?> value="Calibri,Helvetica Neue,Helvetica,Arial,Verdana,sans-serif">Calibri</option>
										<option <?php selected( 'Cambria,Georgia,Times New Roman,Times,serif', $param_values['form_label_font_family'], true ); ?> value="Cambria,Georgia,Times New Roman,Times,serif">Cambria</option>
										<option <?php selected( 'Candara,Verdana,sans-serif', $param_values['form_label_font_family'], true ); ?> value="Candara,Verdana,sans-serif">Candara</option>
										<option <?php selected( 'Century Gothic,Apple Gothic,Verdana,sans-serif', $param_values['form_label_font_family'], true ); ?> value="Century Gothic,Apple Gothic,Verdana,sans-serif">Century Gothic</option>
										<option <?php selected( 'Century Schoolbook,Georgia,Times New Roman,Times,serif', $param_values['form_label_font_family'], true ); ?> value="Century Schoolbook,Georgia,Times New Roman,Times,serif">Century Schoolbook</option>
										<option <?php selected( 'Consolas,Andale Mono,Monaco,Courier,Courier New,Verdana,sans-serif', $param_values['form_label_font_family'], true ); ?> value="Consolas,Andale Mono,Monaco,Courier,Courier New,Verdana,sans-serif">Consolas</option>
										<option <?php selected( 'Constantia,Georgia,Times New Roman,Times,serif', $param_values['form_label_font_family'], true ); ?> value="Constantia,Georgia,Times New Roman,Times,serif">Constantia</option>
										<option <?php selected( 'Corbel,Lucida Grande,Lucida Sans Unicode,Arial,sans-serif', $param_values['form_label_font_family'], true ); ?> value="Corbel,Lucida Grande,Lucida Sans Unicode,Arial,sans-serif">Corbel</option>
										<option <?php selected( 'Tahoma,Geneva,Verdana,sans-serif', $param_values['form_label_font_family'], true ); ?> value="Tahoma,Geneva,Verdana,sans-serif">Tahoma</option>
										<option <?php selected( 'Rockwell, Arial Black, Arial Bold, Arial, sans-serif', $param_values['form_label_font_family'], true ); ?> value="Rockwell, Arial Black, Arial Bold, Arial, sans-serif">Rockwell</option>
									</select>
								</div>
								<div>
									<label for="form_label_color">
                                        <?php _e('Label Color', 'origincode_contact'); ?>
                                    </label>
									<input  type="text" disabled class="color" id="form_label_color" value="#<?php esc_html($param_values['form_label_color']); ?>" size="10" />
								</div>
								<div>
									<label for="form_label_error_color">
                                        <?php _e('Label Error Color', 'origincode_contact'); ?>
                                    </label>
									<input  type="text" disabled class="color" id="form_label_error_color" value="#<?php echo esc_html($param_values['form_label_error_color']); ?>" size="10" />
								</div>
								<div>
									<label for="form_label_required_color">
                                        <?php _e('Label * Color', 'origincode_contact'); ?>
                                    </label>
									<input  type="text" disabled class="color" id="form_label_required_color" value="#<?php echo esc_html($param_values['form_label_required_color']); ?>" size="10" />
								</div>
								<div>
									<label for="form_label_success_message">
                                        <?php _e('Label Success Message Color', 'origincode_contact'); ?>
                                    </label>
									<input  type="text" disabled class="color" id="form_label_success_message" value="#<?php echo esc_html($param_values['form_label_success_message']); ?>" size="10" />
								</div>
							</div>
							<div class="origincode-contact-general-options-block">
								<h3><?php _e('Input-Text Styles', 'origincode_contact'); ?></h3>
								<div>
									<label for="form_input_text_has_background">
                                        <?php _e('Input-Text Has Background', 'origincode_contact'); ?>
                                    </label>
									<input type="hidden" value="off" />
									<input type="checkbox" disabled id="form_input_text_has_background"  <?php if($param_values['form_input_text_has_background']  == 'on'){ echo 'checked="checked"'; } ?>  value="on" />
								</div>
								<div>
									<label for="form_input_text_background_color">
                                        <?php _e('Input-Text Background Color', 'origincode_contact'); ?>
                                    </label>
									<input type="text" disabled class="color" id="form_input_text_background_color" value="#<?php echo esc_html($param_values['form_input_text_background_color']); ?>" size="10" />
								</div>
								<div>
									<label for="form_input_text_border_size">
                                        <?php _e('Input-Text Border Size', 'origincode_contact'); ?>
                                    </label>
									<input type="number" disabled id="form_input_text_border_size" value="<?php echo esc_html($param_values['form_input_text_border_size']); ?>" class="text" />
									<span>px</span>
								</div>
								<div>
									<label for="form_input_text_border_radius">
                                        <?php _e('Input-Text Border Radius', 'origincode_contact'); ?>
                                    </label>
									<input type="number" disabled id="form_input_text_border_radius" value="<?php echo esc_html($param_values['form_input_text_border_radius']); ?>" class="text" />
									<span>px</span>
								</div>
								<div>
									<label for="form_input_text_border_color">
                                        <?php _e('Input-Text Border Color', 'origincode_contact'); ?>
                                    </label>
									<input type="text" disabled class="color" id="form_input_text_border_color" value="#<?php echo esc_html($param_values['form_input_text_border_color']); ?>" size="10" />
								</div>
								<div>
									<label for="form_input_text_font_size">
                                        <?php _e('Input-Text Font Size', 'origincode_contact'); ?>
                                    </label>
									<input type="number" disabled id="form_input_text_font_size" value="<?php echo esc_html($param_values['form_input_text_font_size']); ?>" class="text" />
									<span>px</span>
								</div>
								<div>
									<label for="form_input_text_font_color">
                                        <?php _e('Input-Text Font Color', 'origincode_contact'); ?>
                                    </label>
									<input type="text" disabled class="color" id="form_input_text_font_color" value="#<?php echo esc_html($param_values['form_input_text_font_color']); ?>" size="10" />
								</div>
							</div>
							<div class="origincode-contact-general-options-block">
								<h3><?php _e('Selectbox Styles', 'origincode_contact'); ?></h3>

								<div>
									<label for="form_selectbox_has_background">
                                        <?php _e('Selectbox Has Background', 'origincode_contact'); ?>
                                    </label>
									<input type="hidden" value="off" />
									<input type="checkbox" disabled id="form_selectbox_has_background"  <?php if($param_values['form_selectbox_has_background']  == 'on'){ echo 'checked="checked"'; } ?>  value="on" />
								</div>

								<div>
									<label for="form_selectbox_background_color">
                                        <?php _e('Selectbox Background Color', 'origincode_contact'); ?>
                                    </label>
									<input type="text" disabled class="color" id="form_selectbox_background_color" value="#<?php echo esc_html($param_values['form_selectbox_background_color']); ?>" size="10" />
								</div>
								<div>
									<label for="form_selectbox_border_size">
                                        <?php _e('Selectbox Border Size', 'origincode_contact'); ?>
                                    </label>
									<input type="number" disabled id="form_selectbox_border_size" value="<?php echo esc_html($param_values['form_selectbox_border_size']); ?>" class="text" />
									<span>px</span>
								</div>
								<div>
									<label for="form_selectbox_border_radius">
                                        <?php _e('Selectbox Border Radius', 'origincode_contact'); ?>
                                    </label>
									<input type="number" disabled id="form_selectbox_border_radius" value="<?php echo esc_html($param_values['form_selectbox_border_radius']); ?>" class="text" />
									<span>px</span>
								</div>
								<div>
									<label for="form_selectbox_border_color">
                                        <?php _e('Selectbox Border Color', 'origincode_contact'); ?>
                                    </label>
									<input type="text" disabled class="color" id="form_selectbox_border_color" value="#<?php echo esc_html($param_values['form_selectbox_border_color']); ?>" size="10" />
								</div>
								<div>
									<label for="form_selectbox_font_size">
                                        <?php _e('Selectbox Font Size', 'origincode_contact'); ?>
                                    </label>
									<input type="number" disabled id="form_selectbox_font_size" value="<?php echo esc_html($param_values['form_selectbox_font_size']); ?>" class="text" />
									<span>px</span>
								</div>
								<div>
									<label for="form_selectbox_font_color">
                                        <?php _e('Selectbox Font Color', 'origincode_contact'); ?>
                                    </label>
									<input type="text" disabled class="color" id="form_selectbox_font_color" value="#<?php echo esc_html($param_values['form_selectbox_font_color']); ?>" size="10" />
								</div>
								<div>
									<label for="form_selectbox_arrow_color">
                                        <?php _e('Selectbox Arrow Color', 'origincode_contact'); ?>
                                    </label>
									<input type="text" disabled class="color" id="form_selectbox_arrow_color" value="#<?php echo esc_html($param_values['form_selectbox_arrow_color']); ?>" size="10" />
								</div>
							</div>
                            <!-- Pagination -->
                            <div class="origincode-contact-general-options-block">
                                <h3><?php _e('Pagination Styles','origincode_contact');?></h3>

                                <div>
                                    <label for="form_pagination_has_background"><?php _e('Pagination Background','origincode_contact');?></label>
                                    <input type="hidden" value="off" />
                                    <input type="checkbox" disabled id="form_pagination_has_background" value="on" checked="checked" />
                                </div>

                                <div>
                                    <label for="form_pagination_background_color"><?php _e('Pagination Background Color','origincode_contact');?></label>
                                    <input type="text" disabled class="color" id="form_pagination_background_color" value="#F4514C" size="10" />
                                </div>
                                <div>
                                    <label for="form_pagination_background_size"><?php _e('Pagination Background Size','origincode_contact');?></label>
                                    <input type="number" disabled id="form_pagination_background_size" value="34" class="text" min="0" />
                                    <span>px</span>
                                </div>
                                <div>
                                    <label for="form_pagination_font_color"><?php _e('Pagination Font Color','origincode_contact');?></label>
                                    <input type="text" disabled class="color" id="form_pagination_font_color" value="#FFFFFF" size="10" />
                                </div>
                            </div>
                            <!-- End Pagination -->

							<div class="origincode-contact-general-options-block">
								<h3><?php _e('Button Styles', 'origincode_contact'); ?></h3>
								<div>
									<label for="form_button_position">
                                        <?php _e('Button Position', 'origincode_contact'); ?>
                                    </label>
									<select id="form_button_position" disabled>
										<option <?php if($param_values['form_button_position'] == 'left'){ echo 'selected="selected"'; } ?> value="left"><?php _e('Left', 'origincode_contact'); ?></option>
										<option <?php if($param_values['form_button_position'] == 'right'){ echo 'selected="selected"'; } ?> value="right"><?php _e('Right', 'origincode_contact'); ?></option>
										<option <?php if($param_values['form_button_position'] == 'center'){ echo 'selected="selected"'; } ?> value="center"><?php _e('Center', 'origincode_contact'); ?></option>
									</select>
								</div>
								<div>
									<label for="form_button_fullwidth">
                                        <?php _e('Make Buttons Full-width', 'origincode_contact'); ?>
                                    </label>
									<input type="hidden" value="off" />
									<input type="checkbox" disabled id="form_button_fullwidth"  <?php if($param_values['form_button_fullwidth']  == 'on'){ echo 'checked="checked"'; } ?>  value="on" />
								</div>
								<div>
									<label for="form_button_padding">
                                        <?php _e('Button Padding', 'origincode_contact'); ?>
                                    </label>
									<input type="number" disabled id="form_button_padding" value="<?php echo esc_html($param_values['form_button_padding']); ?>" class="text" />
									<span>px</span>
								</div>
								<div>
									<label for="form_button_font_size">
                                        <?php _e('Buttons Font Size', 'origincode_contact'); ?>
                                    </label>
									<input type="number" disabled id="form_button_font_size" value="<?php echo esc_html($param_values['form_button_font_size']); ?>" class="text" />
									<span>px</span>
								</div>
								<div>
									<label for="form_button_icons_position">
                                        <?php _e('Icons Position', 'origincode_contact'); ?>
                                    </label>
									<select id="form_button_icons_position" disabled>
										<option <?php if($param_values['form_button_icons_position'] == 'left'){ echo 'selected="selected"'; } ?> value="left">Before Text</option>
										<option <?php if($param_values['form_button_icons_position'] == 'right'){ echo 'selected="selected"'; } ?> value="right">After Text</option>
									</select>
								</div>

								<div>
									<label for="form_button_submit_has_icon">
                                        <?php _e('Submit Button Has Icon', 'origincode_contact'); ?>
                                    </label>
									<input type="hidden" value="off" />
									<input type="checkbox" disabled id="form_button_submit_has_icon"  <?php if($param_values['form_button_submit_has_icon']  == 'on'){ echo 'checked="checked"'; } ?>  value="on" />
								</div>
								<div class="has-height">
									<label for="form_button_submit_icon_style">
                                        <?php _e('Submit Icon Style', 'origincode_contact'); ?>
                                    </label>
									<div class="icons-block">
										<ul>
											<li <?php if($param_values['form_button_submit_icon_style']=="originicons-mail-forward"){echo 'class="active"';} ?> title="Mail Forward Icon"><i class="originicons-mail-forward"></i><input  type="radio" value="originicons-mail-forward" /></li>
											<li <?php if($param_values['form_button_submit_icon_style']=="originicons-mail-reply"){echo 'class="active"';} ?> title="Mail Replay Icon"><i class="originicons-mail-reply"></i><input type="radio" value="originicons-mail-reply" /></li>
											<li <?php if($param_values['form_button_submit_icon_style']=="originicons-clock"){echo 'class="active"';} ?> title="Clock Icon"><i class="originicons-clock"></i><input type="radio" value="originicons-clock" /></li>
											<li <?php if($param_values['form_button_submit_icon_style']=="originicons-bell"){echo 'class="active"';} ?> title="Bell Icon"><i class="originicons-bell"></i><input type="radio" value="originicons-bell" /></li>
											<li <?php if($param_values['form_button_submit_icon_style']=="originicons-paper-plane"){echo 'class="active"';} ?> title="Paper Plane Icon"><i class="originicons-paper-plane"></i><input  type="radio" value="originicons-paper-plane" /></li>
											<li <?php if($param_values['form_button_submit_icon_style']=="originicons-sign-in"){echo 'class="active"';} ?> title="Sign In Icon"><i class="originicons-sign-in"></i><input  type="radio" value="originicons-sign-in" /></li>
											<li <?php if($param_values['form_button_submit_icon_style']=="originicons-bars"){echo 'class="active"';} ?> title="Bars Icon"><i class="originicons-bars"></i><input type="radio" value="originicons-bars" /></li>
											<li <?php if($param_values['form_button_submit_icon_style']=="originicons-child"){echo 'class="active"';} ?> title="Child Icon"><i class="originicons-child"></i><input type="radio" value="originicons-child" /></li>
											<li <?php if($param_values['form_button_submit_icon_style']=="originicons-gift"){echo 'class="active"';} ?> title="Gift Icon"><i class="originicons-gift"></i><input type="radio" value="originicons-gift" /></li>
											<li <?php if($param_values['form_button_submit_icon_style']=="originicons-rocket"){echo 'class="active"';} ?> title="Rocket Icon"><i class="originicons-rocket"></i><input type="radio" value="originicons-rocket" /></li>
											<li <?php if($param_values['form_button_submit_icon_style']=="originicons-fire"){echo 'class="active"';} ?> title="Fire Icon"><i class="originicons-fire"></i><input type="radio" value="originicons-fire" /></li>
											<li <?php if($param_values['form_button_submit_icon_style']=="originicons-anchor"){echo 'class="active"';} ?> title="Anchor Icon"><i class="originicons-anchor"></i><input type="radio" value="originicons-anchor" /></li>
											<li <?php if($param_values['form_button_submit_icon_style']=="originicons-plus"){echo 'class="active"';} ?> title="Plus Icon"><i class="originicons-plus"></i><input type="radio" value="originicons-plus" /></li>
											<li <?php if($param_values['form_button_submit_icon_style']=="originicons-envelope-o"){echo 'class="active"';} ?> title="Envelope Icon"><i class="originicons-envelope-o"></i><input type="radio" value="originicons-envelope-o" /></li>
											<li <?php if($param_values['form_button_submit_icon_style']=="originicons-envelope"){echo 'class="active"';} ?> title="Envelope Icon"><i class="originicons-envelope"></i><input type="radio" value="originicons-envelope" /></li>
											<li <?php if($param_values['form_button_submit_icon_style']=="originicons-cart-plus"){echo 'class="active"';} ?> title="Cart Plus Icon"><i class="originicons-cart-plus"></i><input type="radio" value="originicons-cart-plus" /></li>
										</ul>
									</div>
								</div>
								<div>
									<label for="form_button_submit_icon_color">
                                        <?php _e('Submit Icon Color', 'origincode_contact'); ?>
                                    </label>
									<input type="text" disabled class="color" id="form_button_submit_icon_color" value="#<?php echo esc_html($param_values['form_button_submit_icon_color']); ?>" size="10" />
								</div>

								<div>
									<label for="form_button_submit_icon_hover_color">
                                        <?php _e('Submit Icon Hover Color', 'origincode_contact'); ?>
                                    </label>
									<input type="text" disabled class="color" id="form_button_submit_icon_hover_color" value="#<?php echo esc_html($param_values['form_button_submit_icon_hover_color']); ?>" size="10" />
								</div>
								<div>
									<label for="form_button_submit_font_color">
                                        <?php _e('Submit Button Font Color', 'origincode_contact'); ?>
                                    </label>
									<input type="text" disabled class="color" id="form_button_submit_font_color" value="#<?php echo esc_html($param_values['form_button_submit_font_color']); ?>" size="10" />
								</div>

								<div>
									<label for="form_button_submit_font_hover_color">
                                        <?php _e('Submit Button Font Hover Color', 'origincode_contact'); ?>
                                    </label>
									<input type="text" disabled class="color" id="form_button_submit_font_hover_color" value="#<?php echo $param_values['form_button_submit_font_hover_color']; ?>" size="10" />
								</div>

								<div>
									<label for="form_button_submit_background">
                                        <?php _e('Submit Button Background Color', 'origincode_contact'); ?>
                                    </label>
									<input type="text" disabled class="color" id="form_button_submit_background" value="#<?php echo esc_html($param_values['form_button_submit_background']); ?>" size="10" />
								</div>

								<div>
									<label for="form_button_submit_hover_background">
                                        <?php _e('Submit Button Background Hover Color', 'origincode_contact'); ?>
                                    </label>
									<input type="text" disabled class="color" id="form_button_submit_hover_background" value="#<?php echo esc_html($param_values['form_button_submit_hover_background']); ?>" size="10" />
								</div>

								<div>
									<label for="form_button_submit_border_size">
                                        <?php _e('Submit Button Border Size', 'origincode_contact'); ?>
                                    </label>
									<input type="number" disabled id="form_button_submit_border_size" value="<?php echo esc_html($param_values['form_button_submit_border_size']); ?>" class="text" />
									<span>px</span>
								</div>

								<div>
									<label for="form_button_submit_border_color">
                                        <?php _e('Submit Button Border Color', 'origincode_contact'); ?>
                                    </label>
									<input type="text" disabled class="color" id="form_button_submit_border_color" value="#<?php echo esc_html($param_values['form_button_submit_border_color']); ?>" size="10" />
								</div>

								<div>
									<label for="form_button_submit_border_radius">
                                        <?php _e('Submit Button Border Radius', 'origincode_contact'); ?>
                                    </label>
									<input type="number" disabled id="form_button_submit_border_radius" value="<?php echo esc_html($param_values['form_button_submit_border_radius']); ?>" class="text" />
									<span>px</span>
								</div>
								<div>
									<label for="form_button_reset_has_icon">
                                        <?php _e('Reset Button Has Icon', 'origincode_contact'); ?>
                                    </label>
									<input type="hidden" value="off" />
									<input type="checkbox" disabled id="form_button_reset_has_icon"  <?php if($param_values['form_button_reset_has_icon']  == 'on'){ echo 'checked="checked"'; } ?>  value="on" />
								</div>
								<div class="has-height">
									<label for="form_button_reset_icon_style">
                                        <?php _e('Reset Icon Style', 'origincode_contact'); ?>
                                    </label>
									<div class="icons-block reset-icons-block">
										<ul>
											<li <?php if($param_values['form_button_reset_icon_style']=="originicons-refresh"){echo 'class="active"';} ?> title="Refresh Icon"><i class="originicons-refresh"></i><input  type="radio" value="originicons-refresh" /></li>
											<li <?php if($param_values['form_button_reset_icon_style']=="originicons-power-off"){echo 'class="active"';} ?> title="Power Off Icon"><i class="originicons-power-off"></i><input type="radio" disabled value="originicons-power-off" /></li>
											<li <?php if($param_values['form_button_reset_icon_style']=="originicons-minus-circle"){echo 'class="active"';} ?> title="Minus Circle Icon"><i class="originicons-minus-circle"></i><input type="radio" disabled value="originicons-minus-circle" /></li>
											<li <?php if($param_values['form_button_reset_icon_style']=="originicons-times"){echo 'class="active"';} ?> title="Times Icon"><i class="originicons-times"></i><input type="radio" disabled value="originicons-times" /></li>
											<li <?php if($param_values['form_button_reset_icon_style']=="originicons-bell-slash"){echo 'class="active"';} ?> title="Bell Icon"><i class="originicons-bell-slash"></i><input type="radio" disabled value="originicons-bell-slash" /></li>
											<li <?php if($param_values['form_button_reset_icon_style']=="originicons-trash-o"){echo 'class="active"';} ?> title="Trash Icon"><i class="originicons-trash-o"></i><input type="radio"  disabled value="originicons-trash-o" /></li>
											<li <?php if($param_values['form_button_reset_icon_style']=="originicons-user-times"){echo 'class="active"';} ?> title="User Times Icon"><i class="originicons-user-times"></i><input type="radio" disabled value="originicons-user-times" /></li>
											<li <?php if($param_values['form_button_reset_icon_style']=="originicons-street-view"){echo 'class="active"';} ?> title="Street View Icon"><i class="originicons-street-view"></i><input type="radio" disabled value="originicons-street-view" /></li>
											<li <?php if($param_values['form_button_reset_icon_style']=="originicons-times-circle-o"){echo 'class="active"';} ?> title="Times Circle O Icon"><i class="originicons-times-circle-o"></i><input type="radio" disabled value="originicons-times-circle-o" /></li>
											<li <?php if($param_values['form_button_reset_icon_style']=="originicons-reply"){echo 'class="active"';} ?> title="Back Icon"><i class="originicons-reply"></i><input type="radio" disabled value="originicons-reply" /></li>
											<li <?php if($param_values['form_button_reset_icon_style']=="originicons-fire"){echo 'class="active"';} ?> title="Fire Icon"><i class="originicons-fire"></i><input type="radio" disabled value="originicons-fire" /></li>
											<li <?php if($param_values['form_button_reset_icon_style']=="originicons-retweet"){echo 'class="active"';} ?> title="Refrash Icon"><i class="originicons-retweet"></i><input  type="radio" disabled value="originicons-retweet" /></li>
										</ul>
									</div>
								</div>
								<div>
									<label for="form_button_reset_icon_color">
                                        <?php _e('Reset Icon Color', 'origincode_contact'); ?>
                                    </label>
									<input type="text" disabled class="color" id="form_button_reset_icon_color" value="#<?php echo esc_html($param_values['form_button_reset_icon_color']); ?>" size="10" />
								</div>
								<div>
									<label for="form_button_reset_icon_hover_color">
                                        <?php _e('Reset Icon Hover Color', 'origincode_contact'); ?>
                                    </label>
									<input type="text" disabled class="color" id="form_button_reset_icon_hover_color" value="#<?php echo esc_html($param_values['form_button_reset_icon_hover_color']); ?>" size="10" />
								</div>
								<div>
									<label for="form_button_reset_font_color">
                                        <?php _e('Reset Button Font Color', 'origincode_contact'); ?>
                                    </label>
									<input type="text" disabled class="color" id="form_button_reset_font_color" value="#<?php echo esc_html($param_values['form_button_reset_font_color']); ?>" size="10" />
								</div>

								<div>
									<label for="form_button_reset_font_hover_color">
                                        <?php _e('Reset Button Font Hover Color', 'origincode_contact'); ?>
                                    </label>
									<input type="text" disabled class="color" id="form_button_reset_font_hover_color" value="#<?php echo esc_html($param_values['form_button_reset_font_hover_color']); ?>" size="10" />
								</div>

								<div>
									<label for="form_button_reset_background">
                                        <?php _e('Reset Button Background Color', 'origincode_contact'); ?>
                                    </label>
									<input type="text" disabled class="color" id="form_button_reset_background" value="#<?php echo esc_html($param_values['form_button_reset_background']); ?>" size="10" />
								</div>

								<div>
									<label for="form_button_reset_hover_background">
                                        <?php _e('Reset Button Background Hover Color', 'origincode_contact'); ?>
                                    </label>
									<input type="text" disabled class="color" id="form_button_reset_hover_background" value="#<?php echo esc_html($param_values['form_button_reset_hover_background']); ?>" size="10" />
								</div>

								<div>
									<label for="form_button_reset_border_size">
                                        <?php _e('Reset Button Border Size', 'origincode_contact'); ?>
                                    </label>
									<input type="number" disabled id="form_button_reset_border_size" value="<?php echo esc_html($param_values['form_button_reset_border_size']); ?>" class="text" />
									<span>px</span>
								</div>


								<div>
									<label for="form_button_reset_border_color">
                                        <?php _e('Reset Button Border Color', 'origincode_contact'); ?>
                                    </label>
									<input type="text" disabled class="color" id="form_button_reset_border_color" value="#<?php echo esc_html($param_values['form_button_reset_border_color']); ?>" size="10" />
								</div>

								<div>
									<label for="form_button_reset_border_radius">
                                        <?php _e('Reset Button Border Radius', 'origincode_contact'); ?>
                                    </label>
									<input type="number" disabled id="form_button_reset_border_radius" value="<?php echo esc_html($param_values['form_button_reset_border_radius']); ?>" class="text" />
									<span>px</span>
								</div>
							</div>
						</div>
                        <style>
                            #poststuff {

                                border: 1px solid #d0d6dc;
                                background-color:#fff ;
                            }
                            #post-body-content {
                                background-color:#f3f4f8;
                            }
                            .origincode_tabs_block .origincode_contact_top_tabs li.add-new:before {
                                content: "Add New Theme";
                                position: absolute;
                                top: 26px;
                                left: -142px;
                                font-size: 17px;
                                font-family: 'Open Sans', sans-serif;
                            }
                        </style>
					</form>

				</div>
			</div>
		<div style="clear:both;"></div>
	</div>
</div>
</div>
<input type="hidden" value=""/>
<input type="hidden" value=""/>
<input type="hidden" value="options"/>
<input type="hidden" value="styles"/>
<input type="hidden" value="0"/>

<?php

}