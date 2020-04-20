<?php
if(! defined( 'ABSPATH' )) exit;

function text_field_html($rowimages, $frontendformid)
{
    $placeholder = $rowimages->name;
    if( $rowimages->hc_required == 'on' && $rowimages->hc_input_show_default == 'formsInsideAlign') $placeholder .= ' *';
    ?>
    <div class="origincode-field-block" rel="origin-contact-field-<?php echo absint($rowimages->id); ?>">
        <label class="<?php if ($rowimages->hc_input_show_default != '1') echo esc_html($rowimages->hc_input_show_default); ?>"
               for="origincode_preview_textbox_<?php echo absint($rowimages->id); ?>"><?php echo esc_html($rowimages->hc_field_label);
            if ($rowimages->hc_required == 'on') {
                echo '<em class="required-star">*</em>';
            } ?> </label>
        <div class="field-block input-text-block <?php if ($rowimages->hc_input_show_default == 'formsAboveAlign' || $rowimages->hc_input_show_default == 'formsInsideAlign') echo $rowimages->hc_input_show_default; ?>">
            <input id="origincode_preview_textbox_<?php echo absint($rowimages->id); ?>"
                   name="origincode_<?php echo esc_html($frontendformid) . '_' . absint($rowimages->id); ?>"
                   type="<?php echo esc_html($rowimages->field_type); ?>"
                   placeholder="<?php if(!empty(trim($rowimages->mask_on))) echo $rowimages->mask_on; else echo $placeholder; ?>"
                   class="<?php echo ($rowimages->field_type=='number')?'forceNumeric':'';?><?php echo ($rowimages->hc_required == 'on')?'required':'';?>" <?php if ($rowimages->description != 'on') echo 'disabled="disabled"'; ?>
                    <?php if(trim($rowimages->def_value)!==""): ?>
         value="<?php echo $rowimages->def_value; ?>"
         <?php endif; ?>
        <?php if(trim($rowimages->mask_on)!==""): ?>
        data-origc-pattern="<?php echo $rowimages->mask_on; ?>"
        <?php endif; ?>
            />
            <span class="origincode-error-message"></span>
        </div>
    </div>
    <script>
        <?php $r= rand(1,9999); ?>
        var origincode_contact_<?php echo $r; ?>_form_view_text_field_interval = setInterval(function(){
            if(window.jQuery){
                origincode_contact_<?php echo $r; ?>_form_view_text_field_script();
                clearInterval(origincode_contact_<?php echo $r; ?>_form_view_text_field_interval);
            }
        },1000);

        function origincode_contact_<?php echo $r; ?>_form_view_text_field_script(){
            jQuery(document).ready(function () {
                jQuery('.forceNumeric').ForceNumericOnly();
            });
        }
    </script>
    <?php
}

function textarea_field_html($rowimages, $frontendformid)
{
    $placeholder = $rowimages->name;
    if( $rowimages->hc_required == 'on' && $rowimages->hc_input_show_default == 'formsInsideAlign') $placeholder .= ' *';
    ?>
    <div class="origincode-field-block" rel="origin-contact-field-<?php echo absint($rowimages->id); ?>">
        <label class="<?php if ($rowimages->hc_input_show_default != '1') echo esc_html($rowimages->hc_input_show_default); ?>"
               for="origincode_preview_textbox_<?php echo absint($rowimages->id); ?>"><?php echo esc_html($rowimages->hc_field_label);
            if ($rowimages->hc_required == 'on') {
                echo '<em class="required-star">*</em>';
            } ?></label>
        <div class="field-block textarea-block <?php if ($rowimages->hc_input_show_default == 'formsAboveAlign' || $rowimages->hc_input_show_default == 'formsInsideAlign') echo $rowimages->hc_input_show_default; ?>">
            <textarea style="height:<?php echo esc_html($rowimages->hc_other_field); ?>px;resize:<?php echo($rowimages->field_type == 'on')?'vertical':'none'; ?>;"
                      name="origincode_<?php echo esc_html($frontendformid) . '_' . absint($rowimages->id); ?>"
                      id="origincode_preview_textbox_<?php echo absint($rowimages->id); ?>"
                <?php if ($rowimages->description != 'on') { echo 'disabled="disabled"';} ?>
                      class="<?php echo($rowimages->hc_required == 'on')?'required':''; ?>"
                      placeholder="<?php echo esc_html($placeholder); ?>"><?php if(trim($rowimages->def_value)!=="") echo wp_unslash($rowimages->def_value);?></textarea>
            <span class="origincode-error-message"></span>
        </div>
    </div>
    <?php
}

function selectbox_field_html($rowimages, $frontendformid)
{
    ?>
    <div class="origincode-field-block" rel="origin-contact-field-<?php echo absint($rowimages->id); ?>">
        <label class="<?php if ($rowimages->hc_input_show_default != '1') echo esc_html($rowimages->hc_input_show_default); ?>"
               for="origincode_preview_textbox_<?php echo absint($rowimages->id); ?>"><?php echo esc_html($rowimages->hc_field_label);
            if ($rowimages->hc_required == 'on') {
                echo '<em class="required-star">*</em>';
            } ?></label>
        <div class="field-block selectbox-block <?php if ($rowimages->hc_input_show_default == 'formsAboveAlign' || $rowimages->hc_input_show_default == 'formsInsideAlign') echo $rowimages->hc_input_show_default; ?>">
            <?php
            $options = explode(';;', $rowimages->name);

            if($rowimages->def_value){
                $optionValue = $rowimages->def_value;
                if( $rowimages->hc_required && $rowimages->hc_input_show_default=='formsInsideAlign' ) $optionValue .= ' *';
            } else {
                $selectedOptionIndex = $rowimages->hc_other_field;
                if( is_numeric($selectedOptionIndex) && isset($options[$selectedOptionIndex]) )  $optionValue = $options[$selectedOptionIndex];
                else if(in_array($selectedOptionIndex,$options)) $optionValue = $selectedOptionIndex;
                else $optionValue = $options[0];

            } ?>


            <input type="text" disabled="disabled" class="textholder" value="<?php echo esc_html($optionValue); ?>"/>

            <select id="origincode_preview_textbox_<?php echo absint($rowimages->id); ?>" class="<?php echo ($rowimages->hc_required == 'on')?'required':''; ?>" name="origincode_<?php echo esc_html($frontendformid) . '_' . absint($rowimages->id); ?>">
                <?php if( $rowimages->def_value &&  $rowimages->def_value!=''){ ?>
                    <option selected="selected" disabled><?php echo $rowimages->def_value;?></option>
                <?php }
                foreach ($options as $opt_key => $option) {
                    ?>
                    <option <?php echo ($optionValue === $opt_key || $optionValue === $option) ?'selected="selected"':''; ?> ><?php echo esc_html($option); ?></option>
                    <?php
                } ?>
            </select>
            <i class="originicons-chevron-down"></i>
            <span class="origincode-error-message"></span>
        </div>
    </div>
    <?php
}

function customtext_field_html($rowimages)
{
    ?>
    <div class="origincode-field-block custom-text-block" rel="origin-contact-field-<?php echo absint($rowimages->id); ?>">
        <?php echo do_shortcode($rowimages->name); ?>
    </div>
    <?php
}

function checkbox_field_html($rowimages, $frontendformid, $style_values)
{
    ?>
    <div class="origincode-field-block origincode-check-field" rel="origin-contact-field-<?php echo absint($rowimages->id); ?>">
        <label class="<?php if ($rowimages->hc_input_show_default != '1') echo esc_html($rowimages->hc_input_show_default); ?>"
               for="origincode_preview_textbox_<?php echo absint($rowimages->id); ?>"><?php echo esc_html($rowimages->hc_field_label);
            if ($rowimages->hc_required == 'on') {
                echo '<em class="required-star">*</em>';
            } ?></label>
        <div class="field-block checkbox-field-block <?php if ($rowimages->hc_input_show_default == 'formsAboveAlign') echo esc_html($rowimages->hc_input_show_default); ?>">
            <ul id="origincode_preview_textbox_<?php echo absint($rowimages->id); ?>" class="origincode-checkbox-list">
                <?php
                $options = explode(';;', $rowimages->name);
                $actives = explode(';;', $rowimages->hc_other_field);
                $i = 0;
                $j = 0;
                foreach ($options as $keys => $option) {
                    ?>
                    <li style="width:<?php if ($rowimages->field_type != 0) {
                        echo 100 / intval($rowimages->field_type);
                    } ?>%;">
                        <label class="secondary-label" style="display: inline-block;">
                            <div class="checkbox-block big">
                                <input <?php if (isset($actives[$j]) && $actives[$j] == '' . $keys . '') {
                                    echo 'checked="checked"';
                                    $j++;
                                } ?> type="checkbox" value="<?php echo esc_attr($option); ?>"
                                     name="check_<?php echo esc_html($frontendformid) . '_' . absint($rowimages->id); ?>[origincode_<?php echo esc_html($frontendformid) . '_' . absint($rowimages->id) . '_' . esc_html($keys); ?>]" <?php if ($rowimages->description != 'on') {
                                    echo 'disabled="disabled"';
                                } ?>/>
                                <?php if ($style_values['form_checkbox_type'] == 'circle') { ?>
                                    <i class="originicons-dot-circle-o active"></i>
                                    <i class="originicons-circle-o passive"></i>
                                <?php } else { ?>
                                    <i class="originicons-check-square active"></i>
                                    <i class="originicons-square-o passive"></i>
                                <?php } ?>
                            </div>
                                <span class="sublable"><?php echo esc_html($option); ?></span>
                        </label>
                    </li>
                    <?php $i++;
                } ?>
            </ul>
            <span class="origincode-error-message"></span>
        </div>
    </div>
    <?php
}

function hidden_field_html($rowimages, $frontendformid)
{
    ?>
    <div class="origincode-field-block" rel="origin-contact-field-<?php echo esc_attr($rowimages->id); ?>" style="display: none;">
        <label class="<?php if ($rowimages->hc_input_show_default != '1') echo esc_attr($rowimages->hc_input_show_default); ?>"
               class="<?php if ($rowimages->hc_input_show_default != '1') echo esc_attr($rowimages->hc_input_show_default); ?>"
               for="origincode_preview_textbox_<?php echo esc_attr($rowimages->id); ?>">
        </label>

        <div class="field-block">
            <?php
            $current_user = wp_get_current_user();

            switch (esc_attr($rowimages->hc_other_field)) {
                case "user_id":
                    $hidden_value = "User ID is ".$current_user->ID;
                    break;
                case "user_login":
                    $hidden_value = "Username is ".$current_user->user_login;
                    break;
                case "user_email":
                    $hidden_value = "User email is ".$current_user->user_email;
                    break;
                case "ip_address":
                    function get_the_user_ip() {
                        if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
                            $ip = $_SERVER['HTTP_CLIENT_IP'];
                        } elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
                            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                        } else {
                            $ip = $_SERVER['REMOTE_ADDR'];
                        }
                        return $ip;
                    }

                    $hidden_value = "User IP Address is ".get_the_user_ip();
                    break;

            }

            ?>
            <input id="origincode_preview_textbox_<?php echo esc_attr($rowimages->id); ?>"
                   name="origincode_<?php echo esc_attr($frontendformid) . '_' . esc_attr($rowimages->id); ?>" type="hidden"
                   value="<?php echo $hidden_value; ?>" />
        </div>
    </div>
    <?php
}
function page_break_html($rowimages, $frontendformid)
{
    ?>
    <div class="page_break origincode-field-block" rel="origin-contact-field-<?php echo esc_attr($rowimages->id); ?>" style="display: none;" value="page_break">
        <label class="<?php if ($rowimages->hc_input_show_default != '1') echo esc_attr($rowimages->hc_input_show_default); ?>"
               class="<?php if ($rowimages->hc_input_show_default != '1') echo esc_attr($rowimages->hc_input_show_default); ?>"
               for="origincode_preview_textbox_<?php echo esc_attr($rowimages->id); ?>">
        </label>

        <div class="field-block">

            <input id="origincode_preview_textbox_<?php echo esc_attr($rowimages->id); ?>"
                   name="origincode_<?php echo esc_attr($frontendformid) . '_' . esc_attr($rowimages->id); ?>" type="hidden"
                   value="page_break<?php echo $rowimages->ordering; ?>" />
        </div>
    </div>
    <?php
}

function radiobox_field_html($rowimages, $frontendformid, $style_values)
{
    ?>
    <div class="origincode-field-block origincode-radio-field" rel="origin-contact-field-<?php echo absint($rowimages->id); ?>">
        <label class="<?php if ($rowimages->hc_input_show_default != '1') echo esc_html($rowimages->hc_input_show_default); ?>"
               for="origincode_preview_textbox_<?php echo absint($rowimages->id); ?>"><?php echo esc_html($rowimages->hc_field_label);
            if ($rowimages->hc_required == 'on') {
                echo '<em class="required-star">*</em>';
            } ?></label>
        <div class="field-block radio-field-block <?php if ($rowimages->hc_input_show_default == 'formsAboveAlign') echo esc_html($rowimages->hc_input_show_default); ?>">
            <ul id="origincode_preview_textbox_<?php echo absint($rowimages->id); ?>" class="origincode-radiobox-list">
                <?php
                $options = explode(';;', $rowimages->name);
                $i = 0;
                foreach ($options as $keys => $option) {
                    ?>
                    <li style="width:<?php if ($rowimages->description != 0) {
                        echo 100 / $rowimages->description;
                    } ?>%;">
                        <label class="secondary-label" style="display: inline-block;">
                            <div class="radio-block big">
                                <input <?php if (trim($rowimages->hc_other_field) == $i) {
                                    echo 'checked="checked"';
                                } ?> type="radio" value="<?php echo esc_attr($option); ?>"
                                     name="origincode_<?php echo esc_html($frontendformid) . '_' . absint($rowimages->id); ?>">
                                <?php if ($style_values['form_radio_type'] == 'circle') { ?>
                                    <i class="originicons-dot-circle-o active"></i>
                                    <i class="originicons-circle-o passive"></i>
                                <?php } else { ?>
                                    <i class="originicons-check-square active"></i>
                                    <i class="originicons-square-o passive"></i>
                                <?php } ?>
                            </div>
                            <span class="sublable"><?php echo esc_html($option); ?></span>
                        </label>
                    </li>
                    <?php $i++;
                } ?>
            </ul>
        </div>
    </div>
    <?php
}

function filebox_field_html($rowimages, $style_values)
{
    ?>
    <script>
        <?php $r= rand(1,9999); ?>
        var origincode_contact_<?php echo $r; ?>_form_view_filebox_interval = setInterval(function(){
            if(window.jQuery){
                origincode_contact_<?php echo $r; ?>_form_view_filebox_script();
                clearInterval(origincode_contact_<?php echo $r; ?>_form_view_filebox_interval);
            }
        },100);

        function origincode_contact_<?php echo $r; ?>_form_view_filebox_script(){
            jQuery(document).ready(function () {
                function mbToBytes(mb) {
                    var convertedByte = Math.round(mb * 1048576 * 100000) / 100000;
                    return convertedByte;
                }

                var byteRes = mbToBytes(<?php echo absint($rowimages->name);?>);
                jQuery(".origincode-contact-column-block div[rel='origin-contact-field-<?php echo absint($rowimages->id);?>']").find("input[name='MAX_FILE_SIZE']").attr('value', byteRes);
            });
        }

    </script>
    <div class="origincode-field-block" rel="origin-contact-field-<?php echo absint($rowimages->id); ?>">
        <label class="<?php if ($rowimages->hc_input_show_default != '1') echo esc_html($rowimages->hc_input_show_default); ?> origc_fl_box"
               for="origincode_preview_textbox_<?php echo htmlspecialchars($rowimages->id); ?>"><?php echo esc_html($rowimages->hc_field_label);
            if ($rowimages->hc_required == 'on') {
                echo '<em class="required-star">*</em>';
            } ?></label>
        <div class="field-block file-block <?php if ($rowimages->hc_input_show_default == 'formsAboveAlign' || $rowimages->hc_input_show_default == 'formsInsideAlign') echo $rowimages->hc_input_show_default; ?>">
            <input type="text" class="textholder"
                   placeholder="<?php if ($rowimages->hc_input_show_default == 'formsInsideAlign') echo esc_html($rowimages->hc_field_label); ?>"/>
            <span class="uploadbutton">
															<?php if ($style_values['form_file_has_icon'] == 'on'): ?>
                                                                <?php if ($style_values['form_file_icon_position'] == "left") { ?>
                                                                <i
                                                                        class="<?php echo esc_attr($style_values['form_file_icon_style']); ?>"></i><?php } ?>
                                                            <?php endif; ?>
                <?php echo esc_html($style_values['form_file_button_text']); ?>
                <?php if ($style_values['form_file_has_icon'] == 'on'): ?>
                    <?php if ($style_values['form_file_icon_position'] == "right") { ?><i
                        class="<?php echo esc_attr($style_values['form_file_icon_style']); ?>"></i><?php } ?>
                <?php endif; ?>
														</span>
            <input type="hidden" name="MAX_FILE_SIZE" value=""/>
            <input type="hidden" name="fileTypeArr" value="<?php echo esc_attr($rowimages->hc_other_field); ?>">
            <input id="origincode_preview_textbox_<?php echo absint($rowimages->id); ?>" type="file" multiple="multiple"
                   class="fileUploader <?php if ($rowimages->hc_required == 'on') {
                       echo 'required';
                   } ?>" name="userfile_<?php echo absint($rowimages->id); ?>"/>
            <span class="origincode-error-message"></span>
        </div>
    </div>
    <?php
}

function recaptcha_field_html($rowimages, $frontendformid, $paramssld)
{
    ?>

    <div class="origincode-field-block captcha-block" rel="origin-contact-field-<?php echo absint($rowimages->id); ?>"
         data-form_id="<?php echo esc_attr($frontendformid); ?>"
         data-sitekey="<?php echo esc_attr($paramssld['form_captcha_public_key']); ?>"
         data-theme="<?php echo esc_attr($rowimages->hc_required); ?>" data-cname="<?php echo esc_attr($rowimages->name); ?>">
        <?php $capPos = 'right';
        if ($rowimages->hc_input_show_default == '2') $capPos = "left"; ?>
        <div style="float:<?php echo $capPos; ?>;" id="origincode_captcha_<?php echo esc_html($frontendformid); ?>"></div>
        <span style="text-align:right;" class="origincode-error-message"></span>
    </div>
    <?php
}

function simplecaptcha_field_html($rowimages, $frontendformid,$paramssld)
{
    ?>
    <?php if ($rowimages->hc_input_show_default == 'formsRightAlign') {
    $origc_left_right_class = 'text-right';
} else {
    $origc_left_right_class = 'text-left';
} ?>

    <div class="origincode-field-block simple-captcha-block <?php echo esc_attr($origc_left_right_class); ?>"
         rel="origin-contact-field-<?php echo esc_attr($rowimages->id); ?>" data-form_id="<?php echo esc_attr($frontendformid); ?>"
         data-sitekey="<?php echo esc_attr($paramssld['form_captcha_public_key']); ?>"
         data-theme="<?php echo esc_attr($rowimages->hc_required); ?>" data-cname="<?php echo esc_attr($rowimages->name); ?>">

        <label class="formsAboveAlign">
            <?php $current_time = time(); ?>
            <img src="<?php echo esc_url(origincode_contact_create_new_captcha($rowimages->id, 'user', $current_time)); ?>">
            <span class="origincode_captcha_refresh_button" data-captcha-id="<?php echo esc_attr($rowimages->id); ?>" data-time="<?php echo esc_attr($current_time); ?>">
					<img src="<?php echo plugin_dir_url(__FILE__); ?>../images/refresh-icon.png" width="32px">
			</span>
        </label>

        <div class="field-block" rel="simple_captcha_<?php echo esc_attr($rowimages->id); ?>">
            <input type="text" name="simple_captcha_<?php echo esc_attr($frontendformid); ?>"
                   placeholder="<?php echo esc_attr($rowimages->name); ?>">
            <span style="display:block;" class="origincode-error-message"></span>
        </div>

    </div>
    <?php
}

function buttons_field_html($rowimages, $style_values)
{
    ?>
    <div class="origincode-field-block buttons-block" rel="origin-contact-field-<?php echo esc_attr($rowimages->id); ?>">
        <button type="submit" class="submit" id="origincode_preview_button__submit_<?php echo esc_attr($rowimages->id); ?>"
                value="Submit">
            <?php if ($style_values['form_button_submit_has_icon'] == "on" && $style_values['form_button_icons_position'] == "left" ) { ?>
                <i class="<?php echo esc_attr($style_values['form_button_submit_icon_style']); ?>"></i>
            <?php } ?>

            <?php echo esc_html($rowimages->description); ?>

            <?php if ($style_values['form_button_submit_has_icon'] == "on" && $style_values['form_button_icons_position'] == "right") { ?>
                <i class="<?php echo esc_attr($style_values['form_button_submit_icon_style']); ?>"></i>
            <?php } ?>
        </button>
        <?php if ($rowimages->hc_required == 'checked'): ?>
            <button type="reset" class="reset" id="origincode_preview_button_reset_<?php echo esc_attr($rowimages->id); ?>"
                    value="Reset">
                <?php if ($style_values['form_button_reset_has_icon'] == "on"){ ?>
                    <?php if ($style_values['form_button_icons_position'] == "left" ) { ?>
                        <i class="<?php echo esc_attr($style_values['form_button_reset_icon_style']); ?>"></i>
                    <?php } ?>

                    <?php echo esc_html($rowimages->hc_field_label); ?>

                    <?php if ($style_values['form_button_icons_position'] == "right") { ?>
                        <i class="<?php echo esc_attr($style_values['form_button_reset_icon_style']); ?>"></i>
                    <?php } ?>
                <?php } else { echo 'Reset';}?>
            </button>
        <?php endif; ?>
    </div>
    <?php
}

function email_field_html($rowimages, $frontendformid)
{
    $placeholder = $rowimages->name;
    if( $rowimages->hc_required == 'on' && $rowimages->hc_input_show_default == 'formsInsideAlign') $placeholder .= ' *';
    ?>
    <div class="origincode-field-block" rel="origin-contact-field-<?php echo esc_attr($rowimages->id); ?>">
        <label class="<?php if ($rowimages->hc_input_show_default != '1') echo esc_attr($rowimages->hc_input_show_default); ?>"
               class="<?php if ($rowimages->hc_input_show_default != '1') echo esc_attr($rowimages->hc_input_show_default); ?>"
               for="origincode_preview_textbox_<?php echo esc_attr($rowimages->id); ?>">
            <?php echo esc_html($rowimages->hc_field_label);
            if ($rowimages->hc_required == 'on') { echo '<em class="required-star">*</em>'; } ?>
        </label>
        <div class="field-block input-text-block email-block <?php if ($rowimages->hc_input_show_default == 'formsAboveAlign' || $rowimages->hc_input_show_default == 'formsInsideAlign') echo $rowimages->hc_input_show_default; ?>">
            <input id="origincode_preview_textbox_<?php echo esc_attr($rowimages->id); ?>"
                   name="origincode_<?php echo esc_attr($frontendformid) . '_' . esc_attr($rowimages->id); ?>" type="email"
                   placeholder="<?php echo esc_attr($placeholder); ?>" class="<?php if ($rowimages->hc_required == 'on') {
                echo 'required';
            } ?>" <?php if ($rowimages->description != 'on') {
                echo 'disabled="disabled"';
            } ?>
            <?php if(trim($rowimages->def_value)!==""): ?>
            value="<?php echo $rowimages->def_value; ?>"
            <?php endif; ?>
            />
            <span class="origincode-error-message"></span>
        </div>
    </div>
    <?php
}

function origincode_contact_front_end_origincode_contact($rowim, $paramssld, $origincode_contact, $frontendformid, $style_values, $origincode_gen_opt, $rowspar){
	ob_start();
	$frontendformid=esc_html($frontendformid);

	$gen_opt_assoc = array();
	foreach($origincode_gen_opt as $key=>$option){
        $gen_opt_assoc[$option->name] = $option->value;
    }
	?>
    <div class="origincode-contact-form-container">
    <?php ?>
<style>
    <?php $frontendformid=esc_html($frontendformid);?>
			#origincode-contact-wrapper_<?php echo esc_html($frontendformid); ?> {
				width:<?php echo esc_html($style_values['form_wrapper_width']); ?>%;

				<?php
					$color = explode(',', $style_values['form_wrapper_background_color']);
				 if($style_values['form_wrapper_background_type']=="color"){?>
						background:#<?php echo esc_html($color[0]); ?>;
				<?php }
					elseif($style_values['form_wrapper_background_type']=="gradient"){ ?>
						background: -webkit-linear-gradient(#<?php echo esc_html($color[0]); ?>, #<?php echo esc_html($color[1]); ?>); /* For Safari 5.1 to 6.0 */
						background: -o-linear-gradient(#<?php echo esc_html($color[0]); ?>, #<?php echo esc_html($color[1]); ?>); /* For Opera 11.1 to 12.0 */
						background: -moz-linear-gradient(#<?php echo esc_html($color[0]); ?>, #<?php echo esc_html($color[1]); ?>); /* For Firefox 3.6 to 15 */
						background: linear-gradient(#<?php echo esc_html($color[0]); ?>, #<?php echo esc_html($color[1]); ?>); /* Standard syntax */
				<?php
					}
				?>
			}

			#origincode-contact-wrapper_<?php echo $frontendformid; ?> > div {
				border:<?php echo esc_html($style_values['form_border_size']); ?>px solid #<?php echo esc_html($style_values['form_border_color']); ?>;
			}

			#origincode-contact-wrapper_<?php echo $frontendformid; ?> > div > h3 {
				font-size:<?php echo esc_html($style_values['form_title_size']); ?>px !important;
				line-height:<?php echo esc_html($style_values['form_title_size']); ?>px !important;
				color:#<?php echo esc_html($style_values['form_title_color']); ?> !important;
			}

			/*LABELS*/
			
			#origincode-contact-wrapper_<?php echo $frontendformid; ?> label {
				font-size:<?php echo esc_html($style_values['form_label_size']); ?>px !important;
				line-height:<?php echo esc_html($style_values['form_label_size']); ?>px !important;
				color:#<?php echo esc_html($style_values['form_label_color']); ?>;
				font-family:<?php echo esc_html($style_values['form_label_font_family']); ?>;
			}
			
			#origincode-contact-wrapper_<?php echo $frontendformid; ?> .origincode-field-block >label.error {
				color:#<?php echo esc_html($style_values['form_label_error_color']); ?> !important;
			}
			#origincode-contact-wrapper_<?php echo $frontendformid; ?> label em.required-star{
				color: #<?php echo esc_html($style_values['form_label_required_color']); ?>;
			}
			#origincode-contact-wrapper_<?php echo $frontendformid; ?> label em.error{
				color: #<?php echo esc_html($style_values['form_label_error_color']); ?>;
			}
			#origincode-contact-wrapper_<?php echo $frontendformid; ?> .origincode-field-block span.origincode-error-message{
				color: #<?php echo esc_html($style_values['form_label_error_color']); ?>;
				line-height:<?php echo esc_html($style_values['form_label_size']); ?>px !important;
				font-family:<?php echo esc_html($style_values['form_label_font_family']); ?>;
			}
			#origincode-contact-wrapper_<?php echo $frontendformid; ?> .origincode-field-block span.origincode_success_msg{
				font-size: 16px !important;
				display: block;
				text-align: center;
				vertical-align:super;
				font-family:<?php echo esc_html($style_values['form_label_font_family']); ?>;
				color:#<?php echo esc_html($style_values['form_label_success_message']); ?>;
			}
			#origincode-contact-wrapper_<?php echo $frontendformid; ?> .origincode-field-block span.origincode_spam_msg{
				font-family:<?php echo esc_html($style_values['form_label_font_family']); ?>;
				color:#<?php echo esc_html($style_values['form_label_error_color']); ?>;
			}
			/*FIELDS CUSTOM STYLES*/
			
				/*############INPUT TEXT############*/
			
				#origincode-contact-wrapper_<?php echo $frontendformid; ?> .input-text-block input,
				#origincode-contact-wrapper_<?php echo $frontendformid; ?> .input-text-block input:focus,
                #origincode-contact-wrapper_<?php echo $frontendformid; ?> .simple-captcha-block input[type=text],
                #origincode-contact-wrapper_<?php echo $frontendformid; ?> .simple-captcha-block input[type=text]:focus{
					height:<?php echo esc_html($style_values['form_input_text_font_size'])*2; ?>px;
					<?php if($style_values['form_input_text_has_background']=="on"){?>
					    background:#<?php echo esc_html($style_values['form_input_text_background_color']); ?>;
					<?php }else { ?>
					    background:none;
					<?php } ?>
					box-shadow:none  !important ;
					border-radius:<?php echo esc_html($style_values['form_input_text_border_radius']); ?>px;
					margin:0 !important;
					padding:0 0 0 5px !important;
					outline:none;
					vertical-align:top !important;
					box-sizing: border-box;
					-moz-box-sizing: border-box;
				}

            #origincode-contact-wrapper_<?php echo $frontendformid; ?> .input-text-block input,
            #origincode-contact-wrapper_<?php echo $frontendformid; ?> .input-text-block input:focus,
            #origincode-contact-wrapper_<?php echo $frontendformid; ?> .simple-captcha-block input[type=text],
            #origincode-contact-wrapper_<?php echo $frontendformid; ?> .simple-captcha-block input[type=text]:focus,
            #origincode-contact-wrapper_<?php echo $frontendformid; ?> .textarea-block textarea,
            #origincode-contact-wrapper_<?php echo $frontendformid; ?> .selectbox-block .textholder{
                border:1px solid #<?php echo esc_html($style_values['form_input_text_border_color']); ?> !important;
                color:#<?php echo esc_html($style_values['form_input_text_font_color']); ?>;
                margin:0 !important;
                padding:0 0 0 5px !important;
                box-sizing: border-box;
                -moz-box-sizing: border-box;
            }
            #origincode-contact-wrapper_<?php echo $frontendformid; ?> .field-block{
                font-size:<?php echo esc_html($style_values['form_input_text_font_size']); ?>px;
            }
				
				/*/////INPUT TEXT FullName//////*/

				#origincode-contact-wrapper_<?php echo $frontendformid; ?> .input-name-block input,
				#origincode-contact-wrapper_<?php echo $frontendformid; ?> .input-name-block input:focus {
					width: 49% !important;
					height:<?php echo esc_html($style_values['form_input_text_font_size'])*2; ?>px;
					<?php if($style_values['form_input_text_has_background']=="on"){?>
					    background:#<?php echo esc_html($style_values['form_input_text_background_color']); ?>;
					<?php }else { ?>
					    background:none;
					<?php } ?>
					border:1px solid #<?php echo esc_html($style_values['form_input_text_border_color']); ?> !important;
					box-shadow:none  !important ;
					border-radius:<?php echo esc_html($style_values['form_input_text_border_radius']); ?>px;
					font-size:<?php echo esc_html($style_values['form_input_text_font_size']); ?>px;
					color:#<?php echo esc_html($style_values['form_input_text_font_color']); ?>;
					margin:0 !important;
					padding:0 5px 0 5px !important;
					outline:none;
					box-sizing: border-box;
					-moz-box-sizing: border-box;
				}

				/*/////INPUT TEXT FullName//////*/
				/*/////////*/
				/*############ Phone Field############*/
				#origincode-contact-wrapper_<?php echo $frontendformid; ?> .ready-phone-block input.readyPhone,.ready-phone-block input.readyPhone:focus {
					width: 100%;
					box-sizing:border-box;
					height:<?php echo esc_html($style_values['form_input_text_font_size'])*2; ?>px;
					<?php if($style_values['form_input_text_has_background']=="on"){?>
					background:#<?php echo esc_html($style_values['form_input_text_background_color']); ?>;
					<?php }else { ?>
					background:none;
					<?php } ?>
					border:<?php echo $style_values['form_input_text_border_size']; ?>px solid #<?php echo esc_html($style_values['form_input_text_border_color']); ?> !important;
					box-shadow:none  !important ;
					border-radius:<?php echo esc_html($style_values['form_input_text_border_radius']); ?>px;
					font-size:<?php echo esc_html($style_values['form_input_text_font_size']); ?>px;
					color:#<?php echo esc_html($style_values['form_input_text_font_color']); ?>;
					margin:0 !important;
					outline:none;
				    padding-left: 48px;
				}

				/*############TEXTAREA############*/
				
				
				#origincode-contact-wrapper_<?php echo $frontendformid; ?> .textarea-block textarea {
					<?php if($style_values['form_textarea_has_background']=="on"){?>
					    background:#<?php echo esc_html($style_values['form_textarea_background_color']); ?>;
					<?php }else { ?>
					    background:none;
					<?php } ?>
					font-size:<?php echo esc_html($style_values['form_textarea_font_size']); ?>px;
					color:#<?php echo esc_html($style_values['form_textarea_font_color']); ?>;
				}
				
				/*############CHECKBOX RADIOBOX############ */

				
				#origincode-contact-wrapper_<?php echo $frontendformid; ?> .radio-block i {
					float:left;
					width:20px;
					color:#<?php echo esc_html($style_values['form_radio_color']); ?>;
					cursor:pointer;
				}
				
				#origincode-contact-wrapper_<?php echo $frontendformid; ?> .checkbox-block i {
					color:#<?php echo esc_html($style_values['form_checkbox_color']); ?>;
				 }
				
				#origincode-contact-wrapper_<?php echo $frontendformid; ?> .radio-block i:hover {
					color:#<?php echo esc_html($style_values['form_radio_hover_color']); ?>;
				}
				
				#origincode-contact-wrapper_<?php echo $frontendformid; ?> .checkbox-block i:hover {
					color:#<?php echo esc_html($style_values['form_checkbox_hover_color']); ?>;
				}

				
				#origincode-contact-wrapper_<?php echo $frontendformid; ?> .radio-block input:checked + i.active, 
				#origincode-contact-wrapper_<?php echo $frontendformid; ?> .radio-block input:checked + i.active:hover {
					color:#<?php echo esc_html($style_values['form_radio_active_color']); ?>;
				}
				
				#origincode-contact-wrapper_<?php echo $frontendformid; ?> .checkbox-block	input:checked + i.active, 
				#origincode-contact-wrapper_<?php echo $frontendformid; ?> .checkbox-block input:checked + i.active:hover {
					color:#<?php echo esc_html($style_values['form_checkbox_active_color']); ?>;
				}


				/*############SELECTBOX#############*/
				
				#origincode-contact-wrapper_<?php echo $frontendformid; ?> .selectbox-block {
					position:relative;
					height:<?php echo esc_html($style_values['form_selectbox_font_size'])*2+esc_html($style_values['form_selectbox_border_size']); ?>px;
				}
				
				#origincode-contact-wrapper_<?php echo $frontendformid; ?> .selectbox-block select {
					height:<?php echo esc_html($style_values['form_selectbox_font_size'])*2-esc_html($style_values['form_selectbox_border_size'])*2; ?>px;
					margin:<?php echo $style_values['form_selectbox_border_size']; ?>px 0 0 1px !important;
				}
				
				#origincode-contact-wrapper_<?php echo $frontendformid; ?> .selectbox-block .textholder {
					height:<?php echo esc_html($style_values['form_selectbox_font_size'])*2; ?>px;
					<?php if($style_values['form_selectbox_has_background']=="on"){?>
					    background:#<?php echo esc_html($style_values['form_selectbox_background_color']); ?>;
					<?php  }?>
				}
				
				#origincode-contact-wrapper_<?php echo $frontendformid; ?> .selectbox-block i {
					position:absolute;
					top:<?php echo esc_html($style_values['form_selectbox_font_size'])/2+esc_html($style_values['form_selectbox_border_size'])/4; ?>px;
					right:10px;
					z-index:0;
					color:#<?php echo esc_html($style_values['form_selectbox_arrow_color']); ?>;
					font-size:<?php echo esc_html($style_values['form_selectbox_font_size']); ?>px;
				}

				#origincode-contact-wrapper_<?php echo $frontendformid; ?> .file-block .textholder {
					width:calc(60% - <?php echo esc_html($style_values['form_file_border_size'])*2 + 5; ?>px) !important;
					height:<?php echo esc_html($style_values['form_file_font_size'])*2; ?>px;
					border:<?php echo esc_html($style_values['form_file_border_size']); ?>px solid #<?php echo esc_html($style_values['form_file_border_color']); ?> !important;
					border-radius:<?php echo esc_html($style_values['form_file_border_radius']); ?>px !important;
					color:#<?php echo esc_html($style_values['form_file_font_color']); ?>;
					<?php if($style_values['form_file_has_background']=="on"){?>
					background:#<?php echo esc_html($style_values['form_file_background']); ?>;
					<?php  }?>
				}
				
				#origincode-contact-wrapper_<?php echo $frontendformid; ?> .file-block .uploadbutton {
					border-top:<?php echo esc_html($style_values['form_file_border_size']); ?>px solid #<?php echo esc_html($style_values['form_file_border_color']); ?> !important;
					border-bottom:<?php echo esc_html($style_values['form_file_border_size']); ?>px solid #<?php echo esc_html($style_values['form_file_border_color']); ?> !important;
					border-right:<?php echo esc_html($style_values['form_file_border_size']); ?>px solid #<?php echo esc_html($style_values['form_file_border_color']); ?> !important;
					border-top-right-radius:<?php echo esc_html($style_values['form_file_border_radius']); ?>px !important;
					border-bottom-right-radius:<?php echo esc_html($style_values['form_file_border_radius']); ?>px !important;
					<?php $fileheight=$style_values['form_file_font_size']*2; ?>
					height:<?php echo esc_html($fileheight); ?>px;
					font-size:<?php echo esc_html($style_values['form_file_font_size']); ?>px;
					line-height:<?php echo esc_html($style_values['form_file_font_size'])*2; ?>px;
					color:#<?php echo esc_html($style_values['form_file_button_text_color']); ?>;
					background:#<?php echo esc_html($style_values['form_file_button_background_color']); ?>;
				}
				
				#origincode-contact-wrapper_<?php echo $frontendformid; ?> .file-block:hover .uploadbutton {	
					color:#<?php echo esc_html($style_values['form_file_button_text_color']); ?>;
					background:#<?php echo esc_html($style_values['form_file_button_background_color']); ?>;
					vertical-align: baseline;
				}

                #origincode-contact-wrapper_<?php echo $frontendformid; ?> .origc_fl_box:hover {
                    color:#<?php echo esc_html($style_values['form_file_button_background_hover_color']); ?>;
                }
				
				#origincode-contact-wrapper_<?php echo $frontendformid; ?> .file-block .uploadbutton i {
					color:#<?php echo esc_html($style_values['form_file_icon_color']); ?>;
					font-size:<?php echo esc_html($style_values['form_file_font_size']); ?>px;
				}
				
				#origincode-contact-wrapper_<?php echo $frontendformid; ?> .file-block:hover .uploadbutton {
					color:#<?php echo esc_html($style_values['form_file_button_text_hover_color']); ?>;
					background:#<?php echo esc_html($style_values['form_file_button_background_hover_color']); ?>;
				}
				
				#origincode-contact-wrapper_<?php echo $frontendformid; ?> .file-block:hover .uploadbutton i {
					color:#<?php echo esc_html($style_values['form_file_icon_hover_color']); ?>;
				}
				
				#origincode-contact-wrapper_<?php echo $frontendformid; ?> .buttons-block  {
					<?php
						if($style_values['form_button_position']=="left"){echo "text-align:left;";}
						else if ($style_values['form_button_position']=="right"){echo "text-align:right;";}
						else {echo "text-align:center;";}
					?>
				}

				#origincode-contact-wrapper_<?php echo $frontendformid; ?> .buttons-block button {
					padding:<?php echo esc_html($style_values['form_button_padding']); ?>px <?php echo esc_html($style_values['form_button_padding'])*2; ?>px <?php echo esc_html($style_values['form_button_padding']); ?>px <?php echo esc_html($style_values['form_button_padding'])*2; ?>px;
					<?php
						if($style_values['form_button_fullwidth']=="on") :
					?>
						clear:both;
						width:100%;
					<?php endif; ?>
					font-size:<?php echo esc_html($style_values['form_button_font_size']); ?>px;
				}
				
				#origincode-contact-wrapper_<?php echo $frontendformid; ?> .buttons-block button.submit {
					color:#<?php echo esc_html($style_values['form_button_submit_font_color']); ?> !important;
					background-color:#<?php echo esc_html($style_values['form_button_submit_background']); ?> !important;
					border:<?php echo esc_html($style_values['form_button_submit_border_size']); ?>px solid #<?php echo esc_html($style_values['form_button_submit_border_color']); ?> !important;
					border-radius:<?php echo esc_html($style_values['form_button_submit_border_radius']); ?>px !important;
				}				
				#origincode-contact-wrapper_<?php echo $frontendformid; ?> .buttons-block button.submit:hover {
					color:#<?php echo esc_html($style_values['form_button_submit_font_hover_color']); ?> !important;
					background:#<?php echo esc_html($style_values['form_button_submit_hover_background']); ?> !important;
				}				
				#origincode-contact-wrapper_<?php echo $frontendformid; ?> .buttons-block button.submit i {
					color:#<?php echo esc_html($style_values['form_button_submit_icon_color']); ?> !important;
					font-size:<?php echo esc_html($style_values['form_button_font_size']); ?>px !important;
				}				
				#origincode-contact-wrapper_<?php echo $frontendformid; ?> .buttons-block button.submit:hover i {
					color:#<?php echo esc_html($style_values['form_button_submit_icon_hover_color']); ?> !important;
				}	
				#origincode-contact-wrapper_<?php echo $frontendformid; ?> .buttons-block button.reset {
					color:#<?php echo esc_html($style_values['form_button_reset_font_color']); ?> !important;
					background-color:#<?php echo esc_html($style_values['form_button_reset_background']); ?> !important;
					border:<?php echo esc_html($style_values['form_button_reset_border_size']); ?>px solid #<?php echo esc_html($style_values['form_button_reset_border_color']); ?> !important;
					border-radius:<?php echo esc_html($style_values['form_button_reset_border_radius']); ?>px !important;
				}				
				#origincode-contact-wrapper_<?php echo $frontendformid; ?> .buttons-block button.reset:hover {
					color:#<?php echo esc_html($style_values['form_button_reset_font_hover_color']); ?> !important;
					background:#<?php echo esc_html($style_values['form_button_reset_hover_background']); ?> !important;
				}				
				#origincode-contact-wrapper_<?php echo $frontendformid; ?> .buttons-block button.reset i {
					color:#<?php echo esc_html($style_values['form_button_reset_icon_color']); ?> !important;
					font-size:<?php echo esc_html($style_values['form_button_font_size']); ?>px !important;
				}				
				#origincode-contact-wrapper_<?php echo $frontendformid; ?> .buttons-block button.reset:hover i {
					color:#<?php echo esc_html($style_values['form_button_reset_icon_hover_color']); ?> !important;
				}

			</style>
			<script>
                <?php $r= rand(1,9999); ?>
				var origincode_contact_<?php echo $r; ?>_form_view_interval = setInterval(function(){
				    if(window.jQuery){
                        origincode_contact_<?php echo $r; ?>_form_view_script();
                        clearInterval(origincode_contact_<?php echo $r; ?>_form_view_interval);
                    }
                },100);

                function origincode_contact_<?php echo $r; ?>_form_view_script(){
                    jQuery(document).ready(function () {
                        /*FRONT END PREVIEW FROM ADMIN JS*/
                        <?php if(isset($_SERVER['HTTP_USER_AGENT']))  $agent = $_SERVER['HTTP_USER_AGENT'];?>
                        <?php if (strlen(strstr($agent, 'Firefox')) > 0):?>
                        jQuery(".origincode-contact-column-block input[type='file']").on('change',function(){
                            var value=jQuery(this).val().substr(jQuery(this).val().indexOf('fakepath'));
                            jQuery(this).parent().find('input[type="text"]').val(jQuery(this).val());
                        });
                        <?php else: ?>
                        jQuery(".origincode-contact-column-block input[type='file']").on('change',function(){
                            var value=jQuery(this).val().substr(jQuery(this).val().indexOf('fakepath')+9);
                            jQuery(this).parent().find('input[type="text"]').val(value);
                        });
                        <?php endif; ?>

                        jQuery(".origincode-contact-column-block select").on('change',function(){
                            jQuery(this).prev('.textholder').val(jQuery(this).val());
                        });
                    });
                }
			</script>
        <form action="" method="post" enctype="multipart/form-data" verified="0" id="origincode_contact_form_<?php echo $frontendformid; ?>" class="origincode_form">
            <div id="origincode-contact-wrapper_<?php echo $frontendformid; ?>"
                 class="origincode-contact-wrapper <?php echo esc_html($style_values['form_radio_size']); ?>-radio <?php echo esc_html($style_values['form_checkbox_size']); ?>-checkbox">
                <?php $rowim = array_reverse($rowim); ?>
                <div <?php foreach ($rowim as $key => $rowimages) {
                    if ($rowimages->hc_left_right == 'right') {
                        echo 'class="multicolumn"';
                    }
                } ?>>
                    <?php
                    $show_title_custom_setting = get_option('origincode_contact_show_title_for_form_' . $frontendformid);
                    switch ($show_title_custom_setting) {
                        case 'yes' :
                            $show_title = true;
                            break;
                        case 'no' :
                            $show_title = false;
                            break;
                        default :
                            $show_title = $style_values['form_show_title'] === 'on' ? true : false;
                    }
                    if ($show_title) echo "<h3>" . $origincode_contact[0]->name . "</h3>";
                    ?>
                    <?php if (!origincode_contact_is_single_column($rowim)) {
                        $leftrightArray = array('left', 'right');
                    } else {
                        $leftrightArray = array('left');
                    }
                    //Pagination
                    $pagIndex="";
                    $total= array();
                    foreach ($rowim as $pagField => $pagValue){

                        $pagCheck = $pagValue->conttype;
                        if($pagCheck=="page_break"){
                            $pagIndex = $pagField;
                            break;
                        }
                    }
                    if($pagIndex===0 || $pagIndex > 0) {
                        $tempArr= array();
                        $total  = array();

                        while($pagIndex > 0 || $pagIndex===0){

                            array_splice($rowim,$pagIndex,1);
                            $tempArr = array_splice($rowim,0,$pagIndex);
                            if(count($tempArr)>0){array_push($total,$tempArr);}
                            foreach ($rowim as $pagField => $pagValue){

                                $pagCheck = $pagValue->conttype;
                                if($pagCheck=="page_break"){
                                    $pagIndex = $pagField;
                                    break;
                                }
                                else {
                                    $pagIndex=false;
                                }
                            }
                            if(count($rowim)===0){
                                break;
                            }

                        }
                        if(count($rowim)>0){
                            array_push($total,$rowim);
                        }
                    }
                    //Pagination
                    ?>

                    <?php foreach ($leftrightArray as $leftright) { ?>


                    <?php
                    /*Check pagination*/
                    if(count($total)>0){
                    for($x=0;$x<count($total);$x++){
                    ?>                        <div class="paj origincode-contact-column-block origincode-contact-block-<?php echo $leftright; ?>"
                                                   id="origincode-contact-block-<?php echo $leftright; ?> "><?php
                        foreach ($total[$x] as $key => $rowimages) {
                            if ($rowimages->hc_left_right == $leftright) {
                                $inputtype = $rowimages->conttype;
                                switch ($inputtype) {
                                    case 'text':
                                        text_field_html($rowimages, $frontendformid);
                                        break;
                                    case 'textarea':  //2
                                        textarea_field_html($rowimages, $frontendformid);
                                        break;
                                    case 'selectbox':  //3
                                        selectbox_field_html($rowimages, $frontendformid);
                                        break;
                                    case 'checkbox':  //4
                                        checkbox_field_html($rowimages, $frontendformid, $style_values);
                                        break;
                                    case 'radio_box':  //5
                                        radiobox_field_html($rowimages, $frontendformid, $style_values);
                                        break;
                                    case 'file_box':  //6
                                        filebox_field_html($rowimages, $style_values);
                                        break;
                                    case 'custom_text':  //7
                                        customtext_field_html($rowimages);
                                        break;
                                    case 'captcha': //8
                                        recaptcha_field_html($rowimages, $frontendformid, $paramssld);
                                        break;
                                    case 'simple_captcha_box': //8.1
                                        simplecaptcha_field_html($rowimages, $frontendformid,$paramssld);
                                        break;
                                    case 'buttons': //9
                                        buttons_field_html($rowimages, $style_values);
                                        break;
                                    case 'e_mail':  //10
                                        email_field_html($rowimages, $frontendformid);
                                        break;
                                    case 'nameSurname':
                                        fullname_field_html($rowimages, $frontendformid);
                                        break;
                                    case 'phone':
                                        phone_field_html($rowimages, $frontendformid);
                                        break;
                                    case 'license':
                                        license_field_html($rowimages, $frontendformid, $style_values);
                                        break;
                                    case 'address':
                                        address_field_html($rowimages, $frontendformid);
                                        break;
                                    case 'date':
                                        date_field_html($rowimages, $frontendformid);
                                        break;
                                    case 'paypal':
                                        paypal_field_html($rowimages, $frontendformid);
                                        $formhaspaypal=true;
                                        $paypaltype=$rowimages->field_type;
                                        break;
                                    case 'google_maps':
                                        google_map_field_html($rowimages);
                                        break;
                                    case 'hidden_field':
                                        hidden_field_html($rowimages, $frontendformid, $style_values);
                                        break;
                                    case 'page_break':
                                        page_break_html($rowimages, $frontendformid, $style_values);
                                        break;

                                } /*end switch case */
                            } /*endif */
                        }
                        echo "</div>";
                        }

                        }
                        /*Check pagination*/
                        else {
                        ?><div class="origincode-contact-column-block origincode-contact-block-<?php echo $leftright; ?>" id="origincode-contact-block-<?php echo $leftright; ?> "><?php
                            foreach ($rowim as $key => $rowimages) {
                                if ($rowimages->hc_left_right == $leftright) {
                                    $inputtype = $rowimages->conttype;
                                    switch ($inputtype) {
                                        case 'text':
                                            text_field_html($rowimages, $frontendformid);
                                            break;
                                        case 'textarea':  //2
                                            textarea_field_html($rowimages, $frontendformid);
                                            break;
                                        case 'selectbox':  //3
                                            selectbox_field_html($rowimages, $frontendformid);
                                            break;
                                        case 'checkbox':  //4
                                            checkbox_field_html($rowimages, $frontendformid, $style_values);
                                            break;
                                        case 'radio_box':  //5
                                            radiobox_field_html($rowimages, $frontendformid, $style_values);
                                            break;
                                        case 'file_box':  //6
                                            filebox_field_html($rowimages, $style_values);
                                            break;
                                        case 'custom_text':  //7
                                            customtext_field_html($rowimages);
                                            break;
                                        case 'captcha': //8
                                            recaptcha_field_html($rowimages, $frontendformid, $paramssld);
                                            break;
                                        case 'simple_captcha_box': //8.1
                                            simplecaptcha_field_html($rowimages, $frontendformid,$paramssld);
                                            break;
                                        case 'buttons': //9
                                            buttons_field_html($rowimages, $style_values);
                                            break;
                                        case 'e_mail':  //10
                                            email_field_html($rowimages, $frontendformid);
                                            break;
                                        case 'hidden_field':
                                            hidden_field_html($rowimages, $frontendformid, $style_values);
                                            break;
                                        case 'page_break':
                                            page_break_html($rowimages, $frontendformid, $style_values);
                                            break;

                                    } /*end switch case */
                                } /*endif */
                            }
                            }
                            ?>
                        </div>
                        <?php } ?> <!-- end foreach -->
                        <div class="clear"></div>
                    </div>
                </div>
                <input type="hidden" value="hc_email_r" name="hc_email_r">
                <input type="hidden" value="ok" name="submitok">
        </form>

        <!-- Check if page_break exists -->
        <?php if(count($total)>1){?>
            <ul class="paginationUl">
                <li id="prev">&lt;</li>
                <li id="next">&gt;</li>
            </ul><?php }
        ?>
        <!-- Check if page_break exists -->
        <script>
            <?php $r= rand(1,9999); ?>
            var origincode_contact_<?php echo $r; ?>_form_view_main_interval = setInterval(function(){
                if(window.jQuery){
                    origincode_contact_<?php echo $r; ?>_form_view_main_script();
                    clearInterval(origincode_contact_<?php echo $r; ?>_form_view_main_interval);
                }
            },100);

            function origincode_contact_<?php echo $r; ?>_form_view_main_script(){
                jQuery.fn.ForceNumericOnly =function(){
                    return this.each(function()		    {
                        jQuery(this).keydown(function(e){
                            var key = e.charCode || e.keyCode || 0;
                            // allow backspace, tab, delete, enter, arrows, numbers and keypad numbers ONLY
                            // home, end, period, and numpad decimal
                            return (
                                key == 8 ||
                                key == 9 ||
                                key == 13 ||
                                key == 46 ||
                                key == 110 ||
                                key == 107 ||
                                key == 190 ||
                                (key >= 35 && key <= 40) ||
                                (key >= 48 && key <= 57) ||
                                (key >= 96 && key <= 105));
                        });
                    });
                };
                jQuery(document).ready(function(){
                    var requiredError='<?php echo esc_html($gen_opt_assoc['required_empty_field']);?>';
                    var captchaError='<?php echo esc_html($gen_opt_assoc['msg_captcha_error']);?>';
                    var emailError='<?php echo esc_html($gen_opt_assoc['msg_invalid_email']);?>';
                    var uploadTypeError='<?php echo esc_html($gen_opt_assoc['msg_file_format']);?>';
                    var uploadSizeError='<?php echo esc_html($gen_opt_assoc['msg_large_file']);?>';
                    function isValidEmailAddress(emailAddress) {
                        var pattern = new RegExp(/^(("[\w-+\s]+")|([\w-+]+(?:\.[\w-+]+)*)|("[\w-+\s]+")([\w-+]+(?:\.[\w-+]+)*))(@((?:[\w-+]+\.)*\w[\w-+]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][\d]\.|1[\d]{2}\.|[\d]{1,2}\.))((25[0-5]|2[0-4][\d]|1[\d]{2}|[\d]{1,2})\.){2}(25[0-5]|2[0-4][\d]|1[\d]{2}|[\d]{1,2})\]?$)/i);
                        return pattern.test(emailAddress);
                    }
                    function Validate(oForm,_validFileExtensions) {
                        var arrInputs = document.getElementsByTagName("input");
                        for (var i = 0; i < arrInputs.length; i++) {
                            var oInput = arrInputs[i];
                            if (oInput.type == "file") {
                                var sFileName = oInput.value;
                                if (sFileName.length > 0) {
                                    var blnValid = false;
                                    for (var j = 0; j < _validFileExtensions.length; j++) {
                                        var sCurExtension = _validFileExtensions[j];
                                        if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
                                            blnValid = true;
                                            break;
                                        }
                                    }
                                    if (!blnValid) {
                                        return false;
                                    }
                                }
                            }
                        }
                        return true;
                    }

                    jQuery('#origincode-contact-wrapper_<?php echo $frontendformid; ?>').find('.origincode-field-block').not('.buttons-block').not('.captcha-block').each(function(){
                        if(jQuery(this).find('div.ready-phone-block input.readyPhone').attr('type')=='tel'){
                            phone_field=jQuery(this).find('div.ready-phone-block input.readyPhone');
                            if(phone_field.attr('data-required')=='required'){
                                phone_field.on('blur',function(){
                                    if(jQuery(this).val().trim()==''){
                                        jQuery(this).parents('.origincode-field-block').find('.origincode-error-message').text(requiredError);
                                        jQuery(this).parents('.origincode-field-block').find('label').addClass('error');
                                        jQuery(this).parents('.origincode-field-block').find('label>em.required-star').addClass('error');
                                    }else{
                                        jQuery(this).parents('.origincode-field-block').find('.origincode-error-message').text('');
                                        jQuery(this).parents('.origincode-field-block').find('label').removeClass('error');
                                        jQuery(this).parents('.origincode-field-block').find('label>em.required-star').removeClass('error');
                                    }
                                });
                            }

                            phone_field.on('keypress keyup change blur',function(){
                                var phoneVal=jQuery(this).val();
                                jQuery(this).parents('.field-block').find('input[type="hidden"]').val(phoneVal);
                            })
                        }
                        if(jQuery(this).find('div.input-text-block >input').hasClass('required')){
                            var text_emailField=jQuery(this).find('input');
                            text_emailField.on('blur',function(){
                                if(jQuery(this).val().trim()==''){
                                    jQuery(this).parent().find('.origincode-error-message').text(requiredError);
                                    jQuery(this).parent().parent().find('label').addClass('error');
                                    jQuery(this).parent().parent().find('label>em.required-star').addClass('error');
                                }else{
                                    jQuery(this).parent().find('.origincode-error-message').text('');
                                    jQuery(this).parent().parent().find('label').removeClass('error');
                                    jQuery(this).parent().parent().find('label>em.required-star').removeClass('error');
                                }
                            })
                        }
                        if(jQuery(this).find('div.selectbox-block >select').hasClass('required')){
                            var selectField=jQuery(this).find('div.selectbox-block >select');
                            selectField.on('blur change',function(){
                                if(selectField.val()==null){
                                    jQuery(this).parent().find('.origincode-error-message').text(requiredError);
                                    jQuery(this).parent().parent().find('label').addClass('error');
                                    jQuery(this).parent().parent().find('label>em.required-star').addClass('error');
                                    errorsAllow='no';
                                }else{
                                    jQuery(this).parent().find('.origincode-error-message').text('');
                                    jQuery(this).parent().parent().find('label').removeClass('error');
                                }
                            });
                        }
                        if(jQuery(this).find('div.input-name-block >input.pl_name').hasClass('required')){
                            var text_fullnameField=jQuery(this).find('input.pl_name');
                            var text_fullnameField2=jQuery(this).find('input.pl_surname');
                            text_fullnameField.on('blur',function(){
                                if(jQuery(this).val().trim()==''||text_fullnameField2.val().trim()==''){
                                    jQuery(this).parent().parent().find('.origincode-error-message').text(requiredError);
                                    jQuery(this).parent().parent().find('label').addClass('error');
                                    jQuery(this).parent().parent().find('label>em.required-star').addClass('error');
                                }else{
                                    jQuery(this).parent().parent().find('.origincode-error-message').text('');
                                    jQuery(this).parent().parent().find('label').removeClass('error');
                                    jQuery(this).parent().parent().find('label>em.required-star').removeClass('error');
                                }
                            });
                            text_fullnameField2.on('blur',function(){
                                if(jQuery(this).val().trim()==''||text_fullnameField.val().trim()==''){
                                    jQuery(this).parent().parent().find('.origincode-error-message').text(requiredError);
                                    jQuery(this).parent().parent().find('label').addClass('error');
                                    jQuery(this).parent().parent().find('label>em.required-star').addClass('error');
                                }else{
                                    jQuery(this).parent().parent().find('.origincode-error-message').text('');
                                    jQuery(this).parent().parent().find('label').removeClass('error');
                                    jQuery(this).parent().parent().find('label>em.required-star').removeClass('error');
                                }
                            });
                        }
                        if(jQuery(this).find('div.textarea-block >textarea').hasClass('required')){
                            var textarea_field=jQuery(this).find('textarea');
                            textarea_field.on('blur',function(){
                                if(jQuery(this).val().trim()==''){
                                    jQuery(this).parent().find('.origincode-error-message').text(requiredError);
                                    jQuery(this).parent().parent().find('label').addClass('error');
                                    jQuery(this).parent().parent().find('label>em.required-star').addClass('error');
                                }else{
                                    jQuery(this).parent().find('.origincode-error-message').text('');
                                    jQuery(this).parent().parent().find('label').removeClass('error');
                                    jQuery(this).parent().parent().find('label>em.required-star').removeClass('error');
                                }
                            })
                        }
                        if(jQuery(this).find('div.email-block >input').attr('type')=='email'){
                            var emailField=jQuery(this).find('input');
                            emailField.on('blur',function(){
                                if(jQuery(this).val()!=''){
                                    if(!isValidEmailAddress(jQuery(this).val())){
                                        jQuery(this).parent().find('.origincode-error-message').text(emailError);
                                        jQuery(this).parent().parent().find('label').addClass('error');
                                        jQuery(this).parent().parent().find('label>em.required-star').addClass('error');
                                    }else{
                                        jQuery(this).parent().find('.origincode-error-message').text('');
                                        jQuery(this).parent().parent().find('label').removeClass('error');
                                        jQuery(this).parent().parent().find('label>em.required-star').removeClass('error');
                                    }
                                }
                            })
                        }
                        if(jQuery(this).find('div.file-block >input[type="file"]').attr('type')=='file'){
                            var fileInput=jQuery(this).find('input[type="file"]');
                            var maxSize = fileInput.parent().find('input[name="MAX_FILE_SIZE"]').val();
                            fileInput.on('blur',function(){
                                var typeStr = jQuery(this).parent().find('input[name="fileTypeArr"]').val().trim();
                                typeStr = typeStr.replace(/\s+/g, '');
                                var _validFileExtensions = typeStr.split(",");
                                if(fileInput.val()!=''){
                                    var validREsult=Validate(jQuery('#origincode_contact_form_<?php echo $frontendformid; ?>'),_validFileExtensions);
                                    if(!validREsult){
                                        jQuery(this).parent().find('.origincode-error-message').text(uploadTypeError);
                                        jQuery(this).parent().parent().find('label').addClass('error');
                                        jQuery(this).parent().parent().find('label>em.required-star').addClass('error');
                                        jQuery(this).parent().parent().find('label>em.required-star').removeClass('error');
                                    }else{
                                        if(fileInput.val()!=''){
                                            var fileSize = fileInput.get(0).files[0].size;
                                        }
                                        if(jQuery(this).hasClass('required')&&jQuery(this).val().trim()==''){
                                            jQuery(this).parent().find('.origincode-error-message').text(requiredError);
                                            jQuery(this).parent().parent().find('label').addClass('error');
                                            jQuery(this).parent().parent().find('label>em.required-star').addClass('error');
                                        }else if(fileSize>maxSize){
                                            jQuery(this).parent().find('.origincode-error-message').text(uploadSizeError);
                                            jQuery(this).parent().parent().find('label').addClass('error');
                                            jQuery(this).parent().parent().find('label>em.required-star').addClass('error');
                                        }else{
                                            jQuery(this).parent().find('.origincode-error-message').text('');
                                            jQuery(this).parent().parent().find('label').removeClass('error');
                                            jQuery(this).parent().parent().find('label>em.required-star').removeClass('error');
                                        }
                                    }
                                }else{
                                    if(jQuery(this).hasClass('required')&&jQuery(this).val().trim()==''){
                                        jQuery(this).parent().find('.origincode-error-message').text(requiredError);
                                        jQuery(this).parent().parent().find('label').addClass('error');
                                        jQuery(this).parent().parent().find('label>em.required-star').addClass('error');
                                    }else{
                                        jQuery(this).parent().find('.origincode-error-message').text('');
                                        jQuery(this).parent().parent().find('label').removeClass('error');
                                        jQuery(this).parent().parent().find('label>em.required-star').removeClass('error');
                                    }
                                }
                            })
                        }
                    });
                    var captchaExists='no';
                    jQuery( "#origincode_contact_form_<?php echo $frontendformid; ?>" ).on( "submit", function(e){
                        e.preventDefault();
                        var errorsAllow='yes';
                        var nVer = navigator.appVersion;
                        var nAgt = navigator.userAgent;
                        var browserName  = navigator.appName;
                        var fullVersion  = ''+parseFloat(navigator.appVersion);
                        var majorVersion = parseInt(navigator.appVersion,10);
                        var nameOffset,verOffset,ix;

                        // In Opera 15+, the true version is after "OPR/"
                        if ((verOffset=nAgt.indexOf("OPR/"))!=-1) {
                            browserName = "Opera";
                            fullVersion = nAgt.substring(verOffset+4);
                        }
                        // In older Opera, the true version is after "Opera" or after "Version"
                        else if ((verOffset=nAgt.indexOf("Opera"))!=-1) {
                            browserName = "Opera";
                            fullVersion = nAgt.substring(verOffset+6);
                            if ((verOffset=nAgt.indexOf("Version"))!=-1)
                                fullVersion = nAgt.substring(verOffset+8);
                        }
                        // In MSIE, the true version is after "MSIE" in userAgent
                        else if ((verOffset=nAgt.indexOf("MSIE"))!=-1) {
                            browserName = "MSIE";
                            fullVersion = nAgt.substring(verOffset+5);
                        }
                        // In Chrome, the true version is after "Chrome"
                        else if ((verOffset=nAgt.indexOf("Chrome"))!=-1) {
                            browserName = "Chrome";
                            fullVersion = nAgt.substring(verOffset+7);
                        }
                        // In Safari, the true version is after "Safari" or after "Version"
                        else if ((verOffset=nAgt.indexOf("Safari"))!=-1) {
                            browserName = "Safari";
                            fullVersion = nAgt.substring(verOffset+7);
                            if ((verOffset=nAgt.indexOf("Version"))!=-1)
                                fullVersion = nAgt.substring(verOffset+8);
                        }
                        // In Firefox, the true version is after "Firefox"
                        else if ((verOffset=nAgt.indexOf("Firefox"))!=-1) {
                            browserName = "Firefox";
                            fullVersion = nAgt.substring(verOffset+8);
                        }
                        // In most other browsers, "name/version" is at the end of userAgent
                        else if ( (nameOffset=nAgt.lastIndexOf(' ')+1) <
                            (verOffset=nAgt.lastIndexOf('/')) )
                        {
                            browserName = nAgt.substring(nameOffset,verOffset);
                            fullVersion = nAgt.substring(verOffset+1);
                            if (browserName.toLowerCase()==browserName.toUpperCase()) {
                                browserName = navigator.appName;
                            }
                        }
                        // trim the fullVersion string at semicolon/space if present
                        if ((ix=fullVersion.indexOf(";"))!=-1)
                            fullVersion=fullVersion.substring(0,ix);
                        if ((ix=fullVersion.indexOf(" "))!=-1)
                            fullVersion=fullVersion.substring(0,ix);

                        majorVersion = parseInt(''+fullVersion,10);
                        if (isNaN(majorVersion)) {
                            fullVersion  = ''+parseFloat(navigator.appVersion);
                            majorVersion = parseInt(navigator.appVersion,10);
                        }

                        jQuery('#origincode-contact-wrapper_<?php echo $frontendformid; ?>').find('.origincode-field-block').not('.buttons-block').each(function(){
                            if(jQuery(this).find('div.input-text-block >input').hasClass('required')){
                                var text_emailField=jQuery(this).find('input');
                                if(text_emailField.val().trim()==''){
                                    text_emailField.parent().find('.origincode-error-message').text(requiredError);
                                    text_emailField.parent().parent().find('label').addClass('error');
                                    text_emailField.parent().parent().find('label>em.required-star').addClass('error');
                                    errorsAllow='no';
                                }else{
                                    text_emailField.parent().find('.origincode-error-message').text('');
                                    text_emailField.parent().parent().find('label').removeClass('error');
                                }
                            }
                            if(jQuery(this).find('div.ready-phone-block input.readyPhone').attr('type')=='tel'){
                                phone_field=jQuery(this).find('div.ready-phone-block input.readyPhone');
                                if(phone_field.attr('data-required')=='required'){
                                    if(phone_field.val().trim()==''){
                                        phone_field.parents('.origincode-field-block').find('.origincode-error-message').text(requiredError);
                                        phone_field.parents('.origincode-field-block').find('label').addClass('error');
                                        phone_field.parents('.origincode-field-block').find('label>em.required-star').addClass('error');
                                        errorsAllow='no';
                                    }else{
                                        phone_field.parents('.origincode-field-block').find('.origincode-error-message').text('');
                                        phone_field.parents('.origincode-field-block').find('label').removeClass('error');
                                        phone_field.parents('.origincode-field-block').find('label>em.required-star').removeClass('error');
                                    }
                                }
                            }
                            if(jQuery(this).find('div.license-block input#ifChecked').hasClass('required')){
                                license_field=jQuery(this).find('div.license-block input[type="checkbox"]');
                                if(!license_field.is(':checked')){
                                    license_field.parents('.origincode-field-block').find('.origincode-error-message').text('Please tick on checkbox');
                                    errorsAllow='no';
                                }else{
                                    license_field.parents('.origincode-field-block').find('.origincode-error-message').text('');
                                }
                            }
                            if(jQuery(this).find('div.input-name-block >input.pl_name').hasClass('required')){
                                var text_fullnameField=jQuery(this).find('input.pl_name');
                                var text_fullnameField2=jQuery(this).find('input.pl_surname');
                                if(text_fullnameField==''||text_fullnameField2.val().trim()==''){
                                    text_fullnameField.parent().parent().find('.origincode-error-message').text(requiredError);
                                    text_fullnameField.parent().parent().find('label').addClass('error');
                                    text_fullnameField.parent().parent().find('label>em.required-star').addClass('error');
                                    errorsAllow='no';
                                }else{
                                    text_fullnameField.parent().parent().find('.origincode-error-message').text('');
                                    text_fullnameField.parent().parent().find('label').removeClass('error');
                                    text_fullnameField.parent().parent().find('label>em.required-star').removeClass('error');
                                }
                                if(text_fullnameField2.val().trim()==''||text_fullnameField.val().trim()==''){
                                    text_fullnameField2.parent().parent().find('.origincode-error-message').text(requiredError);
                                    text_fullnameField2.parent().parent().find('label').addClass('error');
                                    text_fullnameField2.parent().parent().find('label>em.required-star').addClass('error');
                                    errorsAllow='no';
                                }else{
                                    text_fullnameField2.parent().parent().find('.origincode-error-message').text('');
                                    text_fullnameField2.parent().parent().find('label').removeClass('error');
                                    text_fullnameField2.parent().parent().find('label>em.required-star').removeClass('error');
                                }
                            }
                            if(jQuery(this).find('div.textarea-block >textarea').hasClass('required')){
                                var textarea_field=jQuery(this).find('textarea');
                                if(textarea_field.val().trim()==''){
                                    textarea_field.parent().find('.origincode-error-message').text(requiredError);
                                    textarea_field.parent().parent().find('label').addClass('error');
                                    textarea_field.parent().parent().find('label>em.required-star').addClass('error');
                                    errorsAllow='no';
                                }else{
                                    textarea_field.parent().find('.origincode-error-message').text('');
                                    textarea_field.parent().parent().find('label').removeClass('error');
                                }
                            }
                            if(jQuery(this).find('div.email-block >input').attr('type')=='email'){
                                var emailField=jQuery(this).find('input');
                                emailField.on('blur',function(){
                                    if(jQuery(this).val()!=''){
                                        if(!isValidEmailAddress(jQuery(this).val())){
                                            jQuery(this).parent().find('.origincode-error-message').text(emailError);
                                            jQuery(this).parent().parent().find('label>em.required-star').addClass('error');
                                            errorsAllow='no';
                                        }else{
                                            jQuery(this).parent().find('.origincode-error-message').text('');
                                        }
                                    }
                                })
                            }
                            if(jQuery(this).find('div.selectbox-block >select').hasClass('required')){
                                var selectField=jQuery(this).find('div.selectbox-block >select');
                                if(selectField.val()==null){
                                    selectField.parent().find('.origincode-error-message').text(requiredError);
                                    selectField.parent().parent().find('label').addClass('error');
                                    selectField.parent().parent().find('label>em.required-star').addClass('error');
                                    errorsAllow='no';
                                }else{
                                    selectField.parent().find('.origincode-error-message').text('');
                                    selectField.parent().parent().find('label').removeClass('error');
                                }
                            }
                            if(jQuery(this).find('div.file-block >input[type="file"]').attr('type')=='file'){
                                var fileInput=jQuery(this).find('input[type="file"]');
                                var maxSize = fileInput.parent().find('input[name="MAX_FILE_SIZE"]').val();
                                var typeStr = fileInput.parent().find('input[name="fileTypeArr"]').val().trim();
                                typeStr = typeStr.replace(/\s+/g, '');
                                var _validFileExtensions = typeStr.split(",");
                                if(fileInput.val()!=''){
                                    var validREsult=Validate(jQuery('#origincode_contact_form_<?php echo $frontendformid; ?>'),_validFileExtensions);
                                    if(!validREsult){
                                        fileInput.parent().find('.origincode-error-message').text(uploadTypeError);
                                        fileInput.parent().parent().find('label').addClass('error');
                                        fileInput.parent().parent().find('label>em.required-star').addClass('error');
                                        errorsAllow='no';
                                    }else{
                                        if(fileInput.val()!=''){
                                            var fileSize = fileInput.get(0).files[0].size;
                                        }
                                        if(fileInput.hasClass('required')&&fileInput.val().trim()==''){
                                            fileInput.parent().find('.origincode-error-message').text(requiredError);
                                            fileInput.parent().parent().find('label').addClass('error');
                                            fileInput.parent().parent().find('label>em.required-star').addClass('error');
                                            errorsAllow='no';
                                        }else if(fileSize>maxSize){
                                            fileInput.parent().find('.origincode-error-message').text(uploadSizeError);
                                            fileInput.parent().parent().find('label').addClass('error');
                                            fileInput.parent().parent().find('label>em.required-star').addClass('error');
                                            errorsAllow='no';
                                        }else{
                                            fileInput.parent().find('.origincode-error-message').text('');
                                            fileInput.parent().parent().find('label').removeClass('error');
                                        }
                                    }
                                }else{
                                    if(fileInput.hasClass('required')&&fileInput.val().trim()==''){
                                        fileInput.parent().find('.origincode-error-message').text(requiredError);
                                        fileInput.parent().parent().find('label').addClass('error');
                                        fileInput.parent().parent().find('label>em.required-star').addClass('error');
                                        errorsAllow='no';
                                    }else{
                                        fileInput.parent().find('.origincode-error-message').text('');
                                        fileInput.parent().parent().find('label').removeClass('error');
                                    }
                                }
                            }
                            if(jQuery(this).hasClass('captcha-block')){
                                captchaExists='yes';
                            }
                        });
                        if(captchaExists=='yes'){
                            if(jQuery('#origincode_contact_form_<?php echo $frontendformid; ?>').attr('verified')==0){
                                if(!jQuery(this).find('div.captcha-block #origincode_captcha_<?php echo $frontendformid; ?>').find('span').length){
                                    jQuery(this).find('div.captcha-block #origincode_captcha_<?php echo $frontendformid; ?>').append('<span style="text-align:right;" class="origincode-error-message">'+captchaError+'</span>')
                                }
                                errorsAllow='no';
                            }else{
                                jQuery(this).find('div.captcha-block').find('span.origincode-error-message').text('');
                            }
                        }
                        if(errorsAllow=='yes'){
                            var fd = new FormData();
                            var files_data = jQuery('.fileUploader');
                            var self=jQuery(this);
                            var postData=self.serialize();
                            jQuery.each(jQuery(files_data), function(i, obj) {
                                jQuery.each(obj.files,function(j,file){
                                    fd.append(obj.name, file);
                                })
                            });
                            var time=jQuery('.origincode_captcha_refresh_button').attr('data-time');


                            fd.append('action', 'origincode_validation_action');
                            fd.append('formId', '<?php echo $frontendformid; ?>');
                            fd.append('browser',browserName);
                            fd.append('nonce', origincode_forms_obj.nonce);
                            fd.append('postData', postData);
                            fd.append('time', time);
                            jQuery.ajax({
                                type: 'POST',
                                url: '<?php echo admin_url("admin-ajax.php"); ?>',
                                nonce:origincode_forms_obj.nonce,
                                data: fd,
                                contentType: false,
                                processData: false,
                                beforeSend: function(){
                                    var buttonsHeightBs=jQuery('#origincode-contact-wrapper_<?php echo $frontendformid; ?>').find('div.buttons-block').height();
                                    var buttonsWidthBs=jQuery('#origincode-contact-wrapper_<?php echo $frontendformid; ?>').find('div.buttons-block').width();
                                    jQuery('#origincode-contact-wrapper_<?php echo $frontendformid; ?>').find('div.buttons-block').append('<span class="origincode_button_overlay"style="line-height:'+buttonsHeightBs+'px;height:'+buttonsHeightBs+'px;width:'+buttonsWidthBs+'px;"><img id="buttLoad" src="<?php echo plugins_url( "../images/279.GIF", __FILE__ ); ?>"></span>');
                                },
                                success: function(response){
                                    var response = jQuery.parseJSON(response);
                                    if(response.markedAsSpam){
                                        jQuery('#origincode-contact-wrapper_<?php echo $frontendformid; ?>').find('.origincode_button_overlay').css('display','none');
                                        var buttonField=response.spamButton;
                                        jQuery('#origincode-contact-wrapper_<?php echo $frontendformid; ?> .origincode-error-message').empty();
                                        jQuery('#origincode-contact-wrapper_<?php echo $frontendformid; ?>').find('.origincode-field-block').find('label').removeClass('error');
                                        document.getElementById("origincode_contact_form_<?php echo $frontendformid; ?>").reset();
                                        var buttonsHeight=jQuery('#origincode-contact-wrapper_<?php echo $frontendformid; ?>').find('div[rel="'+buttonField+'"]').height()+3;
                                        var buttonsWidth=jQuery('#origincode-contact-wrapper_<?php echo $frontendformid; ?>').find('div[rel="'+buttonField+'"]').width()-2;
                                        var spamText=response.markedAsSpam;
                                        jQuery('#origincode-contact-wrapper_<?php echo $frontendformid; ?>').find('div[rel="'+buttonField+'"]').empty().append('<span class="origincode_spam_msg"style="line-height:'+buttonsHeight+'px;height:'+buttonsHeight+'px;width:'+buttonsWidth+'px;"><span>'+spamText+'</span></span>');
                                    }else if(response.errors){

                                        jQuery('#origincode-contact-wrapper_<?php echo $frontendformid; ?>').find('.origincode_button_overlay').css('display','none');
                                        if(captchaExists=='yes'){
                                            grecaptcha.reset(recaptchas[<?php echo $frontendformid; ?>]);
                                        }
                                        jQuery('#origincode-contact-wrapper_<?php echo $frontendformid; ?> .origincode-error-message').empty();
                                        jQuery('#origincode-contact-wrapper_<?php echo $frontendformid; ?>').find('.origincode-field-block').find('label').removeClass('error');
                                        jQuery('#origincode-contact-wrapper_<?php echo $frontendformid; ?>').find('.origincode-field-block').find('label>em.required-star').removeClass('error');

                                        jQuery.each( response.errors, function( key, value ) {
                                            jQuery('#origincode-contact-wrapper_<?php echo $frontendformid; ?>').find('div [rel="'+key+'"]').find('span.origincode-error-message').append(value);
                                            jQuery('#origincode-contact-wrapper_<?php echo $frontendformid; ?>').find('div [rel="'+key+'"]').find('label').addClass('error');
                                            jQuery('#origincode-contact-wrapper_<?php echo $frontendformid; ?>').find('div [rel="'+key+'"]').find('label>em.required-star').addClass('error');
                                        });
                                    }else if(response.success){
                                        if(response.afterSubmit=='print_success_message'){
                                            jQuery('#origincode-contact-wrapper_<?php echo $frontendformid; ?>').find('.origincode_button_overlay').css('display','none');
                                            var buttonField=response.buttons;
                                            var successText=response.success;
                                            var buttonsHeight=jQuery('#origincode-contact-wrapper_<?php echo $frontendformid; ?>').find('div[rel="'+buttonField+'"]').height()+3;
                                            var buttonsWidth=jQuery('#origincode-contact-wrapper_<?php echo $frontendformid; ?>').find('div[rel="'+buttonField+'"]').width()-2;
                                            jQuery('#origincode-contact-wrapper_<?php echo $frontendformid; ?>').find('div[rel="'+buttonField+'"]').empty().append('<span class="origincode_success_msg"style="line-height:'+buttonsHeight+'px;height:'+buttonsHeight+'px;width:'+buttonsWidth+'px;"><span>'+successText+'</span></span>');
                                            document.getElementById("origincode_contact_form_<?php echo $frontendformid; ?>").reset();
                                            jQuery('#origincode-contact-wrapper_<?php echo $frontendformid; ?> .origincode-error-message').empty();
                                            jQuery('#origincode-contact-wrapper_<?php echo $frontendformid; ?>').find('.origincode-field-block').find('label').removeClass('error');
                                        }else if(response.afterSubmit=='refresh_page'){
                                            jQuery('#origincode-contact-wrapper_<?php echo $frontendformid; ?>').find('.origincode_button_overlay').css('display','none');
                                            location.reload();
                                        }else if(response.afterSubmit=='go_to_url'){
                                            jQuery('#origincode-contact-wrapper_<?php echo $frontendformid; ?>').find('.origincode_button_overlay').css('display','none');
                                            document.getElementById("origincode_contact_form_<?php echo $frontendformid; ?>").reset();
                                            jQuery('#origincode-contact-wrapper_<?php echo $frontendformid; ?> .origincode-error-message').empty();
                                            jQuery('#origincode-contact-wrapper_<?php echo $frontendformid; ?>').find('.origincode-field-block').find('label').removeClass('error');
                                            var redirectUrl=response.afterSubmitUrl;
                                            window.location.href=redirectUrl;
                                        }

                                    }
                                }
                            });
                        }
                    });
                    jQuery( "#origincode_contact_form_<?php echo $frontendformid; ?>" ).on( "reset", function() {
                        if(captchaExists=='yes'){
                            grecaptcha.reset(recaptchas[<?php echo $frontendformid; ?>]);
                        }
                        jQuery('#origincode-contact-wrapper_<?php echo $frontendformid; ?> .origincode-error-message').empty();
                        jQuery('#origincode-contact-wrapper_<?php echo $frontendformid; ?>').find('.origincode-field-block').find('label').removeClass('error');
                        jQuery('#origincode-contact-wrapper_<?php echo $frontendformid; ?>').find('.origincode-field-block').find('label>em.required-star').removeClass('error');
                        jQuery('#origincode-contact-wrapper_<?php echo $frontendformid; ?>').find('.origincode-field-block').find('.stateWrap select option').remove();
                    });




                    function origincode_refresh_captcha() {
                        captchacontainer=jQuery(this).closest('.formsAboveAlign');
                        img=captchacontainer.find('img').eq(0);
                        captchaid=jQuery(this).attr('data-captcha-id');
                        var d = new Date();
                        time = d.getTime();
                        jQuery('.origincode_captcha_refresh_button').attr('data-time',time);
                        formid=jQuery(this).data('form-id');
                        digits=jQuery(this).data('digits');
                        user='user';

                        img.remove();

                        var url='<?php echo admin_url("admin-ajax.php"); ?>';

                        jQuery.ajax({
                            type: 'POST',
                            url: url,
                            data:{
                                captchaid: captchaid, action: "origincode_refresh_simple_captcha", time: time
                            },
                            beforeSend: function(){
                            },
                            success: function(response){

                                newimg='<img src="'+response+'">';

                                jQuery(newimg).prependTo(captchacontainer);
                            }
                        });



                    }
                    jQuery('#origincode_contact_form_<?php echo $frontendformid;?> .origincode_captcha_refresh_button').click(origincode_refresh_captcha);
                })
            }


</script>
    </div>

    <?php
	return ob_get_clean();
}
