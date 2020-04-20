<?php

/*
Plugin Name: Contact Forms - Form Builder
Plugin URI: https://origincode.co/forms
Description: Contact Form Builder plugin is created to simplify the process of creating any kind of forms on your website.
Version: 1.0.3
Author: OriginCode
Author URI: https://origincode.co/
Domain Path: /languages/
License: GNU/GPLv3 https://www.gnu.org/licenses/gpl-3.0.html
*/

define('ORIGC_CONTACT_VERSION', '1.0.3');
define('ORIGC_CONTACT_URL', plugins_url('', __FILE__));
define('ORIGC_CONTACT_PATH', plugin_dir_path(__FILE__));
define('ORIGC_IMAGES_BASE_URL', plugins_url('images/',__FILE__));

require_once "includes/class-template-loader.php";
if (!defined('ABSPATH')) {
    exit;
}

/*INCLUDING ORIGINCODE AJAX FILE*/
require_once("admin/form_ajax.php");

add_filter('tiny_mce_before_init', 'origincode_contact_tinymce_readonly');

function origincode_contact_tinymce_readonly($args)
{
    if ($args['selector'] == '#origincode_contact_admin_message' || $args['selector'] == '#origincode_contact_user_message') {
        $args['readonly'] = 1;
    }
    return $args;
}

add_filter('mce_buttons', 'origc_form_mce_button');

function origc_form_mce_button($buttons) {
    array_push($buttons, 'origc_forms_shortcode');
    return $buttons;
}

add_filter('mce_external_plugins', 'origincode_contact_tinymce_shortcode_placeholder');

function origincode_contact_tinymce_shortcode_placeholder($plugins)
{
    $plugins['origc_forms_shortcode'] = plugins_url("assets/js/shortcode-placeholder.js", __FILE__);
    return $plugins;
}

/*INCLUDING ORIGINCODE FORM BUILDER AJAX FILE*/
function origincode_contact_formBuilder_ajax_action_callback()
{
    require("admin/builder_ajax.php");
    die();
}


// Include simple captcha generation file
require_once("admin/captcha.php");

add_action('wp_ajax_origincode_refresh_simple_captcha', 'origincode_contact_create_new_captcha');
add_action('wp_ajax_nopriv_origincode_refresh_simple_captcha', 'origincode_contact_create_new_captcha');


/*INCLUDING ORIGINCODE EMAIL MANAGER SCHEDULE FILE*/
require_once("functions/email_schedule.php");
// Including Contact Form Validation File
require_once("functions/form_validation.php");
add_action('wp_ajax_origincode_validation_action', 'origincode_contact_contact_form_validation_callback');
add_action('wp_ajax_nopriv_origincode_validation_action', 'origincode_contact_contact_form_validation_callback');
add_action('wp_ajax_origincode_contact_action', 'origincode_contact_ajax_action_callback');
add_action('wp_ajax_origincode_contact_formBuilder_action', 'origincode_contact_formBuilder_ajax_action_callback');
add_action('wp_ajax_origincode_email_action', 'origincode_contact_email_ajax_action_callback');
/*ADDING to HEADER of FRONT END */
function origincode_contact_frontend_scripts_and_styles($id)
{
    wp_enqueue_style("font_awesome_frontend", plugins_url("assets/style/iconfonts/css/originicons.css", __FILE__), false);
    wp_enqueue_style("origincode_contact_front_css", plugins_url("assets/style/form-front.css", __FILE__), false);
    wp_enqueue_script("origincode_forms_front_main_js", plugins_url("assets/js/front.js", __FILE__), array('jquery'));
    wp_enqueue_script( 'ORIGCjQueryMask',plugins_url('assets/js/masked-inputs.js',__FILE__));
    global $wpdb;
    $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "origincode_contact_contacts_fields where origincode_contact_id = %d order by ordering DESC", $id);
    $rowim = $wpdb->get_results($query);
    foreach ($rowim as $key => $rowimages) {
        if ($rowimages->conttype == 'captcha') {
            $recaptcha = 'https://www.google.com/recaptcha/api.js?onload=origincode_forms_onloadCallback&render=explicit';
            wp_enqueue_script('origincode_contact_recaptcha', $recaptcha, array('jquery'), '1.0.0', true);
        }
    }
    wp_enqueue_script("origincode_forms_front_end_js", plugins_url("assets/js/recaptcha_front.js", __FILE__), false);
    $origincode_contact_nonce = array(
        'nonce' => wp_create_nonce('origincode_contact_front_nonce')
    );
    wp_localize_script('origincode_forms_front_end_js', 'origincode_forms_obj', $origincode_contact_nonce);
}

function origincode_contact_scripts_async($tag, $handle)
{
    if ('recaptcha' !== $handle) :
        return $tag;
    endif;

    return str_replace('<script', '<script defer async', $tag);
}

add_filter('script_loader_tag', 'origincode_contact_scripts_async', 10, 2);

//Add Form Button in editor tools
add_action('media_buttons_context', 'origincode_contact_add_contact_button');
function origincode_contact_add_contact_button($context)
{
    $img = plugins_url('/images/origincode_contactLogoHover-for_menu.png', __FILE__);
    $container_id = 'origincode_contact';
    $context .= '<a class="button thickbox origincode-forms-add" title="Select OriginCode Contact Form to Insert Into Post"    href="#TB_inline?width=400&inlineId=' . $container_id . '">
        <span class="wp-media-buttons-icon" style="background: url(' . $img . '); background-repeat: no-repeat; background-position: left bottom;"></span>
    Add Form
    </a>';

    return $context;
}


add_action('wp_ajax_origincode_contact_duplicate_form', 'wp_ajax_origincode_contact_duplicate_form_callback');
function wp_ajax_origincode_contact_duplicate_form_callback()
{
    if (!isset($_POST['nonce'], $_POST['id']) || !wp_verify_nonce($_POST['nonce'], 'duplicate_form_' . $_POST['id'])) {
        return false;
    }
    $id = absint($_POST['id']);

    global $wpdb;

    $form = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "origincode_contact_contacts WHERE id = " . $id, ARRAY_A);
    unset($form['id']);

    $inserted = $wpdb->insert(
        $wpdb->prefix . 'origincode_contact_contacts',
        $form
    );

    if ($inserted) {
        $inserted_form_id = $wpdb->insert_id;

        $fields = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "origincode_contact_contacts_fields WHERE origincode_contact_id = " . $id, ARRAY_A);

        foreach ($fields as $field) {
            unset($field['id']);
            $field['origincode_contact_id'] = $inserted_form_id;

            $fields_result[] = $wpdb->insert(
                $wpdb->prefix . 'origincode_contact_contacts_fields',
                $field
            );
        }

        $options['origincode_contact_show_title_for_form'] = get_option('origincode_contact_show_title_for_form_' . $id);

        foreach ($options as $name => $value) {
            if ($value !== false) {
                update_option($name . '_' . $inserted_form_id, $value);
            }
        }
    }

    echo json_encode(array(
        'success' => $inserted && !in_array(false, $fields_result, true)
    ));
    wp_die();
}

//Add Form Popup Div
add_action('admin_footer', 'origincode_contact_add_inline_contact_popup_content');
function origincode_contact_add_inline_contact_popup_content()
{

    ?>
    <script type="text/javascript">
        jQuery(document).ready(function () {
            jQuery('#origincodeorigincode_contactinsert').on('click', function () {
                var id = jQuery('#origincode_contact-select option:selected').val();
                window.send_to_editor('[origincode_forms id="' + id + '"]');
                tb_remove();
            })
        });
    </script>
    <div id="origincode_contact" style="display:none;">
        <h3>Select OriginCode Form to Insert Into Post</h3>
        <?php
        global $wpdb;
        $tablename = $wpdb->prefix . "origincode_contact_contacts";
        $query = $wpdb->prepare('SELECT * FROM %s order by id ASC', $tablename);
        $query = str_replace("'", "", $query);
        $shortcodeorigincode_contacts = $wpdb->get_results($query);

        if (count($shortcodeorigincode_contacts)) {
            echo "<select id='origincode_contact-select'>";
            foreach ($shortcodeorigincode_contacts as $shortcodeorigincode_contact) {
                echo "<option value='" . $shortcodeorigincode_contact->id . "'>" . $shortcodeorigincode_contact->name . "</option>";
            }
            echo "</select>";
            echo "<button class='button primary' id='origincodeorigincode_contactinsert'>Insert Form</button>";
        } else {
            echo "No Form Found", "origincode_forms";
        }
        ?>
    </div>

    <?php
}

add_action('admin_head', 'origincode_contact_ajax_func');
function origincode_contact_ajax_func()
{
    ?>
    <script>
        var origincode_ajax = '<?php echo admin_url("admin-ajax.php"); ?>';
    </script>
    <?php
}

add_action('admin_print_scripts','origincode_forms_shortcode_placeholder_template');

function origincode_forms_shortcode_placeholder_template(){
    ?>
    <script type="text/underscore-template" id="origincode-shortcode-placeholder">
        <div class="mceItem mceNonEditable origcformsPlaceholder" id="<%- ref %>" data-shortcode="<%- shortcode %>" data-mce-resize="false" data-mce-placeholder="1" contenteditable="false">
            <span class="plugin-name">OriginCode Forms</span>
            <span title="<%- edit %>" class="origcformsPlaceholderButton origcformsPlaceholderEdit">
            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>&nbsp;
        </span>
            <span title="<%- remove %>" class="origcformsPlaceholderButton origcformsPlaceholderRemove">
            <i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;
        </span>
        </div>
    </script>

    <style>
        .origcformsPlaceholder{
            width: 120px;
            background: #2d8ac7;
            color: #fff;
            padding: 5px 10px;
        }
        .origcformsPlaceholder span.plugin-name{
            font-size: 14px;
        }
    </style>
    <?php
}

function origincode_contact_images_list_shotrcode($atts)
{
    extract(shortcode_atts(array(
        'id' => 'no origincode origincode_contact',
    ), $atts));
    if (!(is_numeric($atts['id']) || $atts['id'] == 'ALL_CAT')) {
        return 'insert numerical or `ALL_CAT` shortcode in `id`';
    }
    origincode_contact_frontend_scripts_and_styles($atts['id']);
    return origincode_contact_cat_images_list($atts['id']);
}

/////////////// Filter origincode_contact
function origincode_contact_after_search_results($query)
{
    global $wpdb;
    if (isset($_REQUEST['s']) && $_REQUEST['s']) {
        $serch_word = htmlspecialchars(($_REQUEST['s']));
        $query = str_replace($wpdb->prefix . "posts.post_content", gen_string_origincode_contact_search($serch_word, $wpdb->prefix . 'posts.post_content') . " " . $wpdb->prefix . "posts.post_content", $query);
    }

    return $query;
}

add_shortcode('origincode_forms', 'origincode_contact_images_list_shotrcode');
function origincode_contact_cat_images_list($id)
{
    require_once("front/form_view.php");
    require_once("front/form_view_function.php");

    return origincode_contact_show_published_contact_1($id);
}


add_filter('admin_head', 'origincode_contact_ShowTinyMCE');
function origincode_contact_ShowTinyMCE()
{
    // conditions here
    wp_enqueue_script('common');
    wp_enqueue_script('jquery-color');
    wp_print_scripts('editor');
    if (function_exists('add_thickbox')) {
        add_thickbox();
    }
    wp_print_scripts('media-upload');
    if (version_compare(get_bloginfo('version'), 3.3) < 0) {
        if (function_exists('wp_tiny_mce')) {
            wp_tiny_mce();
        }
    }
    wp_admin_css();
    wp_enqueue_script('utils');
    do_action("admin_print_styles-post-php");
    do_action('admin_print_styles');

    wp_localize_script(
        'media-editor',
        'origcform_attach_to_post_url',
        admin_url('/?origcform_attach_to_post=1')
    );
}

add_action('admin_menu', 'origincode_contact_options_panel');
function origincode_contact_options_panel()
{
    $page_main            = add_menu_page('OriginCode Forms', 'OriginCode Forms', 'manage_options', 'origincode_forms_main_page', 'origincode_contacts_origincode_contact', plugins_url('images/origincode_contactLogoHover-for_menu.png', __FILE__));
    $page_manageforms     = add_submenu_page('origincode_forms_main_page', 'Manage Forms', 'Manage Forms', 'manage_options', 'origincode_forms_main_page', 'origincode_contacts_origincode_contact');
    $page_allsubmissions  = add_submenu_page('origincode_forms_main_page', 'All Submissions', 'All Submissions', 'manage_options', 'origincode_forms_submissions', 'origincode_contact_submissions');
    $page_emailmanager    = add_submenu_page('origincode_forms_main_page', 'Newsletter Manager', 'Newsletter Manager', 'manage_options', 'origincode_forms_email_manager', 'origincode_contact_email_manager');
    $page_import_export   = add_submenu_page("origincode_forms_main_page", "Import/Export", "Import/Export", "manage_options", "import_export", "origincode_forms_import_export");

    add_submenu_page("origincode_forms_main_page", "Upgrade to PRO", "<strong id=\"wfMenuCallout\" style=\"color: #2587e2;\">Upgrade to PRO</strong>", "manage_options", "upgradeLink", "upgradeLink");
    add_action('admin_print_styles-' . $page_main, 'origincode_contact_less_options');
    add_action('admin_print_styles-' . $page_main, 'origincode_contact_formBuilder_options');
    add_action('admin_print_styles-' . $page_allsubmissions, 'origincode_contact_less_options');
    add_action('admin_print_styles-' . $page_emailmanager, 'origincode_contact_less_options');
    add_action('admin_print_styles-' . $page_emailmanager, 'origincode_contact_email_options');

    add_action('admin_print_styles-' . $page_import_export, 'origincode_contact_less_options');

    $GLOBALS['origincode_contact_admin_pages'] = array($page_main, $page_allsubmissions, $page_emailmanager, $page_import_export);
}

//Captcha
function origincode_contact_admin_captcha()
{
    wp_enqueue_script('origincode_recaptcha', 'https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit');
}

function upgradeLink()
{
    if (!headers_sent()) {
        header('Location: http://origincode.co/forms/');
        exit;
    } else {
        echo '<script type="text/javascript">';
        echo 'window.location.href="http://origincode.co/forms/";';
        echo '</script>';
        echo '<noscript>';
        echo '<meta http-equiv="refresh" content="0;url=http://origincode.co/forms/" />';
        echo '</noscript>';
        exit;
    }
}

function origincode_contact_less_options()
{
    wp_enqueue_media();
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-sortable');


    wp_enqueue_script("jquery-ui-core");
    wp_enqueue_style("wp-jquery-ui-core");
    wp_enqueue_style("origincode_contact_originicons", plugins_url("assets/style/iconfonts/css/originicons.css", __FILE__), false);
    wp_enqueue_style("font-awesome", 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', true);
    add_action('admin_footer', 'origincode_contact_admin_captcha');
    wp_enqueue_style("origincode_contact_admin_css", plugins_url("assets/style/admin.style.css", __FILE__), false);
    wp_enqueue_script("origincode_contact_admin_js", plugins_url("assets/js/admin.js", __FILE__), false);
    wp_enqueue_script("origincode_fonts", 'https://fonts.googleapis.com/css?family=Roboto:500|Open+Sans');
    $translation_array = array(
        'nonce' => wp_create_nonce('admin_nonce')
    );
    wp_enqueue_script('param_block3', plugins_url("assets/elements/jscolor/jscolor.js", __FILE__));
    wp_localize_script('origincode_contact_admin_js', 'origincode_forms_obj', $translation_array);

    wp_enqueue_script(
        'origincode-forms-igw', plugins_url("assets/js/edit-popup.js", __FILE__), array('jquery'), '1.0.0'
    );
    wp_localize_script('origincode-forms-igw', 'origcform_igw_i18n', array(
        'origcform'	=>	'OriginCode Forms',
        'edit'				=>	__('Click to edit', 'forms_contact'),
        'remove'			=>	__('Click to remove', 'forms_contact'),
    ));


}

function origincode_contact_email_options()
{
    wp_enqueue_script('origincode_contact_email_script', plugins_url('assets/js/email_manager.js', __FILE__), array('jquery'));
    global $wpdb;
    $genOptions = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "origincode_contact_general_options order by id");
    $mailing_progress = $genOptions[33]->value;
    $translation_array = array(
        'mail_status' => $mailing_progress,
        'nonce' => wp_create_nonce('email_nonce')
    );
    wp_localize_script('origincode_contact_email_script', 'origincode_forms_obj', $translation_array);
}

function origincode_contact_formBuilder_options()
{
    wp_enqueue_script('origincode_contact_formBuilder_script', plugins_url('assets/js/form-builder.js', __FILE__), array('jquery'));
    $translation_array = array(
        'nonce' => wp_create_nonce('builder_nonce')
    );
    wp_localize_script('origincode_contact_formBuilder_script', 'origincode_forms_obj', $translation_array);
}


function origincode_contact_with_options()
{
    wp_enqueue_media();
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script("origincode_simple_slider_js", plugins_url("assets/js/simple-slider.js", __FILE__), false);
    wp_enqueue_style("origincode_simple_slider_css", plugins_url("assets/style/simple-slider.css", __FILE__), false);
    wp_enqueue_script('origincode_param_block2', plugins_url("assets/elements/jscolor/jscolor.js", __FILE__));
    wp_enqueue_style("originicons", plugins_url("assets/style/iconfonts/css/originicons.css", __FILE__), false);
    wp_enqueue_style("origincode_admin_css", plugins_url("assets/style/admin.style.css", __FILE__), false);
    wp_enqueue_script("origincode_admin_js", plugins_url("assets/js/admin.js", __FILE__), false);
    wp_enqueue_style("font-awesome", 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', true);
}


function origincode_contact_option_admin_script()
{
    wp_enqueue_script('param_block2', plugins_url("assets/elements/jscolor/jscolor.js", __FILE__));
}

function origincode_contact_my_mce_buttons_2($buttons)
{
    array_unshift($buttons, 'styleselect');

    return $buttons;
}

// Register our callback to the appropriate filter
add_filter('mce_buttons_2', 'origincode_contact_my_mce_buttons_2');
function origincode_contacts_origincode_contact()
{
    require_once("admin/builder_ajax.php");
    require_once("admin/forms_function.php");
    require_once("admin/forms_view.php");
    if (!function_exists('origincode_contact_print_html_nav')) {
        require_once("functions/function.php");
    }
    if (isset($_GET["task"])) {
        $task = sanitize_text_field($_GET["task"]);
    } else {
        $task = '';
    }
    if (isset($_GET["id"]) && is_numeric($_GET['id'])) {
        $id = absint($_GET["id"]);
    } else {
        $id = 0;
    }
    global $wpdb;
    switch ($task) {
        case 'add_cat':
            if (!isset($_REQUEST['origincode_contact_add_form_nonce']) || !wp_verify_nonce($_REQUEST['origincode_contact_add_form_nonce'], 'add_form')) {
                wp_die('Security check failure');
            }

            origincode_contact_add_origincode_contact();
            break;
        case 'captcha_keys':
            if ($id) {
                origincode_contact_captcha_keys($id);
            } else {
                $id = $wpdb->get_var("SELECT MAX( id ) FROM " . $wpdb->prefix . "origincode_contact_contacts");
                origincode_contact_captcha_keys($id);
            }
            break;
        case 'edit_cat':
            if ($id) {
                if (!isset($_REQUEST['origincode_contact_edit_form_nonce']) || !wp_verify_nonce($_REQUEST['origincode_contact_edit_form_nonce'], 'edit_form_' . $id)) {
                    wp_die('Security check failure');
                }
                origincode_contact_edit_origincode_contact($id);
            } else {
                $id = $wpdb->get_var("SELECT MAX( id ) FROM " . $wpdb->prefix . "origincode_contact_contacts");
                if (isset($_GET['origincode_forms_nonce']) && wp_verify_nonce($_GET['origincode_forms_nonce'], 'origincode_edit_cat_' . $id)) {
                    origincode_contact_edit_origincode_contact($id);
                }
            }
            break;
        case 'save':
            if ($id) {
                origincode_contact_apply_cat($id);
            }
        case 'apply':
            if (!isset($_REQUEST['origincode_contact_apply_form_nonce']) || !wp_verify_nonce($_REQUEST['origincode_contact_apply_form_nonce'], 'apply_form_' . $id)) {
                wp_die('Security check failure');
            }
            if ($id) {
                origincode_contact_apply_cat($id);
                origincode_contact_edit_origincode_contact($id);
            }
            break;
        case 'remove_cat':
            if (!isset($_REQUEST['origincode_forms_remove_form_nonce']) || !wp_verify_nonce($_REQUEST['origincode_forms_remove_form_nonce'], 'remove_form_' . $id)) {
                wp_die('Security check failure');
            }
            if (isset($id) && $id) {
                origincode_contact_remove_contact($id);
                origincode_contact_show_contact();
            }
            break;
        case 'remove_submissions':
            origincode_contact_remove_submissions($id);
            origincode_contact_show_submissions();
            break;
        default:
            origincode_contact_show_contact();
            break;
    }
}

/* Theme Options Page */
function origincode_contact_contact_style_options()
{
    require_once("admin/style_settings_function.php");
    require_once("admin/style_settings_view.php");
    if (isset($_GET['task'])) {
        $task = sanitize_text_field($_GET['task']);
        if ($task == 'save') {
            origincode_contact_save_styles_options();
        }
    }
    if (isset($_GET['theme_id'])) {
        origincode_contact_editstyles();
    } else {
        origincode_contact_styles();
    }
}

/* All Submissions Page */
function origincode_contact_submissions()
{
    require_once("admin/submissions_function.php");
    require_once("admin/submissions_view.php");
    if (isset($_GET['task'])) {
        $task = sanitize_text_field($_GET['task']);
        if ($task == 'save') {
            origincode_contact_save_styles_options();
        }
    }
    $task = isset($_GET["task"]) ? sanitize_text_field($_GET['task']) : '';
    $id = isset($_GET["id"]) ? sanitize_text_field($_GET["id"]) : 0;
    $subId = isset($_GET["subId"]) ? sanitize_text_field($_GET["subId"]) : 0;
    $submissionsId = isset($_GET["submissionsId"]) ? sanitize_text_field($_GET["submissionsId"]) : 0;

    switch ($task) {
        case 'remove_submissions':
            origincode_contact_remove_submissions($id, $subId);
            origincode_contact_view_submissions($subId);
            $actual_link = "//$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            $pattern = '/\?(.*)/';
            $actual_link = preg_replace($pattern, '?page=origincode_forms_submissions&task=view_submissions&id=' . $subId . '', $actual_link);
            break;
        case 'view_submissions':
            origincode_contact_view_submissions($id);
            break;
        case 'show_submissions':
            origincode_contact_show_submissions_2($id, $submissionsId);
            break;
        default:
            origincode_contact_show_submissions();
            break;
    }
}

function origincode_contact_email_manager()
{
    require_once("admin/emails_function.php");
    require_once("admin/emails_view.php");
    if (isset($_GET['task'])) {
        $task = sanitize_text_field($_GET['task']);
        if ($task == 'save') {
            origincode_contact_save_global_options();
            origincode_contact_show_emails();
        }
    } else {
        origincode_contact_show_emails();
    }
}

/* General Options Page */
function origincode_contact_general_options()
{
    require_once("admin/general_settings_function.php");
    require_once("admin/general_settings_view.php");

    if (isset($_GET['task'])) {
        $task = sanitize_text_field($_GET['task']);
        if ($task == 'save') {
            origincode_contact_save_styles_options();
        }
    }
}


/* Import Export Forms Page */
function origincode_forms_import_export()
{
    if (isset($_POST['import-form'])) {
        origincode_contact_import_form();
    }
    wp_enqueue_script('origincode_import_export', plugin_dir_url(__FILE__) . 'assets/js/import_export.js', true);
    wp_localize_script('origincode_import_export', 'origincode_forms_exportForm', array(
        'nonce' => wp_create_nonce('origincode_contact_export_form'),
    ));
    require_once("admin/import_export.php");
}

/* Form is exported via ajax */
add_action('wp_ajax_origincode_contact_export_form', 'origincode_contact_export_form');
add_action('wp_ajax_nopriv_origincode_contact_export_form', 'origincode_contact_export_form');
function origincode_contact_export_form()
{
    if (!isset($_REQUEST['nonce']) || !wp_verify_nonce($_REQUEST['nonce'], 'origincode_contact_export_form')) {
        wp_die(__('Security check failed', 'origincode_contact'));
    }
    if (!isset($_REQUEST['form'])) {
        wp_die(__('missing "form" parameter', 'origincode_contact'));
    }
    $form = absint($_REQUEST['form']);
    $date_format = 'm/d/Y';
    global $wpdb;
    $export = array(
        'form' => array(),
        'fields' => array(),
    );
    $formRow = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "origincode_contact_contacts WHERE id={$form}", 'ARRAY_A');
    $fields = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "origincode_contact_contacts_fields WHERE origincode_contact_id={$form}", 'ARRAY_A');
    $export['form'] = $formRow;
    foreach ($fields as $field) {
        $export['fields'][] = $field;
    }
    echo json_encode(array('success' => true, 'data' => $export));
    die();
}

function origincode_contact_import_form()
{
    if (!isset($_FILES['import-file']) || !$_FILES['import-file']) return;
    $file = $_FILES['import-file'];
    if (substr($file['name'], 0, 15) !== 'origincode_contact_' || substr($file['name'], -3) !== 'origcf') return;
    $data = file_get_contents($_FILES['import-file']['tmp_name']);
    if (!is_array($data)) {
        $data = json_decode(html_entity_decode($data), true);
        if (!is_array($data)) {
            $data = json_decode($data, true);
        }
        if (!is_array($data)) {
            if (!is_array($data)) {
                return false;
            }
        }
        $import = $data;
        $form = $import['form'];
        global $wpdb;
        $wpdb->insert(
            $wpdb->prefix . 'origincode_contact_contacts',
            array(
                'name' => $form['name'],
                'hc_acceptms' => $form['hc_acceptms'],
                'hc_width' => $form['hc_width'],
                'hc_userms' => $form['hc_userms'],
                'hc_yourstyle' => $form['hc_yourstyle'],
                'description' => $form['description'],
                'param' => $form['param'],
                'ordering' => $form['ordering'],
                'def_value' => $form['def_value'],
                'mask_on' => $form['mask_on'],
                'published' => $form['published']
            ),
            array(
                '%s',
                '%s',
                '%d',
                '%s',
                '%s',
                '%s',
                '%s',
                '%d',
                '%s'
            )
        );
        $form_id = $wpdb->insert_id;
        $fields = $import['fields'];
        foreach ($fields as $field) {
            $wpdb->insert(
                $wpdb->prefix . 'origincode_contact_contacts_fields',
                array(
                    'name' => $field['name'],
                    'origincode_contact_id' => $form_id,
                    'description' => $field['description'],
                    'conttype' => $field['conttype'],
                    'hc_field_label' => $field['hc_field_label'],
                    'hc_other_field' => $field['hc_other_field'],
                    'field_type' => $field['field_type'],
                    'hc_required' => $field['hc_required'],
                    'ordering' => $field['ordering'],
                    'published' => $field['published'],
                    'hc_input_show_default' => $field['hc_input_show_default'],
                    'hc_left_right' => $field['hc_left_right']
                ),
                array(
                    '%s',
                    '%d',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%d',
                    '%d',
                    '%s',
                    '%s'
                )
            );
        }
        wp_redirect(admin_url() . '?page=origincode_forms_main_page');
        //exit;
    }
    if (!$import) {
        wp_die(
            __('There uploaded file is not a valid format.', 'origincode_contact') . ' ' . (function_exists('json_last_error')) ? json_last_error_msg() : '',
            __('Invalid Form Upload.', 'origincode_contact')
        );
    }
}


function origincode_contact_subscriber_deactivate()
{
    global $wpdb;
    $wpdb->query("UPDATE " . $wpdb->prefix . "origincode_contact_general_options SET value = 'finish' WHERE name = 'mailing_progress'");
    $wpdb->query("UPDATE " . $wpdb->prefix . "origincode_contact_subscribers SET send = '0' WHERE send != '0'");
    wp_clear_scheduled_hook('origincode_cron_action');
}

/**
 * OriginCode Contact FormWidget
 */
class Origincode_Contact_Form_Widget extends WP_Widget
{
    public function __construct()
    {
        parent::__construct('Origincode_Contact_Form_Widget', 'OriginCode Forms', array(
            'description' => 'OriginCode Forms', 'origincode_forms',
        ));
    }

    public function widget($args, $instance)
    {
        /**
         * @var string $before_widget ;
         * @var string $before_title ;
         * @var string $after_title ;
         * @var string $after_widget ;
         */
        extract($args);
        if (isset($instance['contact_id'])) {
            $contact_id = $instance['contact_id'];
            $title = apply_filters('widget_title', $instance['title']);
            echo $before_widget;
            if (!empty($title)) {
                echo $before_title . $title . $after_title;
            }
            echo do_shortcode("[origincode_forms id={$contact_id}]");
            echo $after_widget;
        }
    }

    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['contact_id'] = strip_tags($new_instance['contact_id']);
        $instance['title'] = strip_tags($new_instance['title']);

        return $instance;
    }

    public function form($instance)
    {
        if (!isset($instance['contact_id'])) {
            $instance['contact_id'] = '';
        }
        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = 'Form';
        }

        global $wpdb;
        $query = "SELECT * FROM " . $wpdb->prefix . "origincode_contact_contacts ";
        $row_widgets = $wpdb->get_results($query);

        ?>
        <p>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>" type="text"
                   value="<?php echo esc_attr($title); ?>"/>
        </p>
        <label for="<?php echo $this->get_field_id('contact_id'); ?>"><?php _e('Select Form:', 'origincode_forms'); ?></label>
        <select id="<?php echo $this->get_field_id('contact_id'); ?>"
                name="<?php echo $this->get_field_name('contact_id'); ?>">
            <?php foreach ($row_widgets as $row_widget) : ?>
                <option <?php if (isset($row_widget->id) && $row_widget->id == $instance['contact_id']) {
                    echo 'selected';
                } ?> value="<?php echo $row_widget->id; ?>"><?php echo $row_widget->name; ?></option>
            <?php endforeach; ?>
        </select>
        </p>
        <?php
    }
}

add_action('widgets_init', 'origincode_contact_register_Origin_it_contact_Widget');
function origincode_contact_register_Origin_it_contact_Widget()
{
    register_widget('Origincode_Contact_Form_Widget');
}

//////////////////////////////////////////////////////                                             ///////////////////////////////////////////////////////
//////////////////////////////////////////////////////               Activate OriginCode Forms        ///////////////////////////////////////////////////////
//////////////////////////////////////////////////////                                             ///////////////////////////////////////////////////////
//////////////////////////////////////////////////////                                             ///////////////////////////////////////////////////////
function origincode_contact_activate()
{
    global $wpdb;

    $collate = '';

    if ($wpdb->has_cap('collation')) {
        $collate = $wpdb->get_charset_collate();
    }

/// create database tables
    $sql_origincode_contact_style_fields = "
CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "origincode_contact_style_fields`(
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 NOT NULL,
  `title` varchar(200) CHARACTER SET utf8 NOT NULL,
  `description` text CHARACTER SET utf8 NOT NULL,
  `options_name` text NOT NULL,
  `value` varchar(200) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) " . $collate . " AUTO_INCREMENT=1";
// DON'T EDIT HERE NOTHING!!!!!!!!!!!!!
    $sql_origincode_contact_general_options = "
CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "origincode_contact_general_options`(
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 NOT NULL UNIQUE,
  `title` varchar(200) CHARACTER SET utf8 NOT NULL,
  `description` text CHARACTER SET utf8 NOT NULL,
  `value` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) " . $collate . " AUTO_INCREMENT=1";
// DON'T EDIT HERE NOTHING!!!!!!!!!!!!!
    $sql_origincode_contact_styles = "
CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "origincode_contact_styles`(
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 NOT NULL,
  `last_update` varchar(50) CHARACTER SET utf8 NOT NULL,
  `ordering` int(11) NOT NULL,
  `published` text,
  PRIMARY KEY (`id`)
) " . $collate . " AUTO_INCREMENT=0";
    $sql_origincode_contact_submission = "
CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "origincode_contact_submission`(
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_id` int(11) NOT NULL,
  `sub_labels` text NOT NULL,
  `submission` text NOT NULL,
  `submission_date` text NOT NULL,
  `submission_ip` text NOT NULL,
  `customer_country` text NOT NULL,
  `customer_spam` text NOT NULL,
  `customer_read_or_not` text NOT NULL,
  `files_url` text NULL,
  `files_type` text NULL,
  PRIMARY KEY (`id`)
) " . $collate . " AUTO_INCREMENT=0";
    $sql_origincode_contact_contacts_fields = "
CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "origincode_contact_contacts_fields` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` text DEFAULT NULL,
  `origincode_contact_id` varchar(200) DEFAULT NULL,
  `description` text,
  `conttype` text NOT NULL,
  `hc_field_label` text,
  `hc_other_field` varchar(128) DEFAULT NULL,
  `field_type` text NOT NULL,
  `hc_required` text NOT NULL,
  `ordering` int(11) NOT NULL,
  `published` tinyint(4) unsigned DEFAULT NULL,
  `hc_input_show_default` text NOT NULL,
  `hc_left_right` text NOT NULL,
  `def_value` text NOT NULL,
  `mask_on` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) " . $collate . "  AUTO_INCREMENT=1";
    $sql_origincode_contact_contacts = "
CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "origincode_contact_contacts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `hc_acceptms` text,
  `hc_width` int(11) unsigned DEFAULT NULL,
  `hc_userms` text,
  `hc_yourstyle` text,
  `description` text,
  `param` text,
  `ordering` int(11) NOT NULL,
  `published` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) " . $collate . " AUTO_INCREMENT=8 ";
    $sql_origincode_contact_subscribers = "
CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "origincode_contact_subscribers` (
    `subscriber_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `subscriber_form_id` int(10) NOT NULL,
    `subscriber_email` varchar(50) NOT NULL,
    `text` text NOT NULL,
    `send` enum('0','1','2','3') NOT NULL DEFAULT '0',
    PRIMARY KEY (`subscriber_id`)
    ) " . $collate . ";";
    /**
     *DANGER!!!DON'T EDIT THIS TABLE!!!
     **/
    $email = get_bloginfo('admin_email');
    $table_name = $wpdb->prefix . "origincode_contact_general_options";
    $sql_4 = <<<query1
INSERT INTO `$table_name` (`name`, `title`, `description`, `value`) VALUES
('form_action_after_submition', 'Form Action after submition', 'Form Action after submition', 'light'),
('form_save_to_database', 'Form Save to Database', 'Form Save to Database', 'on'),
('form_send_email_for_each_submition', 'Send email for each submition', 'Send email for each submition', 'on'),
('form_adminstrator_email', 'Adminstrator email', 'Adminstrator email', '$email'),
('form_message_subject', 'Form Message Subject', 'Form Message Subject', 'Message Subject'),
('form_adminstrator_message', 'Form Administrator Message', 'Form Administrator Message', '{formContent}<br>This Email Is For Administrator!'),
('form_send_to_email_user', 'Send to email user', 'Send to email user', 'on'),
('form_user_message_subject', 'Message Subject', 'Message Subject', 'Message Subject'),
('form_user_message', 'Message', 'Message', 'This Email Goes To User!'),
('form_captcha_public_key', 'Captcha Public Key', 'Captcha Public Key', ''),
('form_captcha_private_key', 'Captcha Private Key', 'Captcha Private Key', ''),
('msg_send_success', 'Sender''s message was sent successfully', 'Sender''s message was sent successfully', 'Message is sent successfully'),
('msg_send_false', 'Sender''s message was failed to send', 'Sender''s message was failed to send', 'Message failed to be send'),
('msg_vld_error', 'Validation errors occurred', 'Validation errors occurred', 'error'),
('msg_refered_spam', 'Submission was referred to as spam', 'Submission was referred to as spam', 'Submission was referred to as Spam'),
('msg_accept_terms', 'There are terms that the sender must accept', 'There are terms that the sender must accept', 'accept'),
('msg_fill_field', 'There is a field that the sender must fill in', 'There is a field that the sender must fill in', 'fill'),
('msg_invalid_number', 'Number format that the sender entered is invalid', 'Number format that the sender entered is invalid', 'invalid'),
('msg_number_smaller', 'Number is smaller than minimum limit', 'Number is smaller than minimum limit', 'limit'),
('msg_number_large', 'Number is larger than maximum limit', 'Number is larger than maximum limit', 'maximum'),
('msg_invalid_email', 'Email address that the sender entered is invalid', 'Email address that the sender entered is invalid', 'Incorrect Email'),
('msg_invalid_url', 'URL that the sender entered is invalid', 'URL that the sender entered is invalid', 'sender'),
('msg_invalid_tel', 'Telephone number that the sender entered is invalid', 'Telephone number that the sender entered is invalid', 'Telephone'),
('msg_invalid_date', 'Date format that the sender entered is invalid', 'Date format that the sender entered is invalid', 'format'),
('msg_early_date', 'Date is earlier than minimum limit', 'Date is earlier than minimum limit', 'earlier'),
('msg_late_date', 'Date is later than maximum limit', 'Date is later than maximum limit', 'later'),
('msg_fail_failed', 'Uploading a file fails for any reason', 'Uploading a file fails for any reason', 'Error on file upload'),
('msg_file_format', 'Uploaded file is not allowed file type', 'Uploaded file is not allowed file type', 'Unacceptable file type'),
('msg_large_file', 'Uploaded file is too large', 'Uploaded file is too large', 'Exceeds limits on uploaded file'),
('sub_choose_form','Subscribers To Send','Subscribers To Send','all'),
('sub_count_by_parts','Subscribers Count In Part','Subscribers Count In Part',50),
('sub_interval','Email Manager Interval','Email Manager Interval',60),
('email_subject','Email Subject','Email Subject','Mailings From Forms'),
('mailing_progress','Mailing Progress','Mailing Progress','finish'),
('form_adminstrator_user_mail','Form Administrator User Email','Form Administrator User Email','example@123.com'),
('form_adminstrator_user_name','Form Adminstrator User Name','Form Adminstrator User Name','John'),
('required_empty_field','Required Field Is Empty','Required Field Is Empty','Please Fill This Field'),
('msg_captcha_error','Captcha Validation Error','Captcha Validation Error','Please tick on Captcha box');
query1;
    /**
     *DANGER!!!DON'T EDIT THIS TABLE!!!
     **/
    $table_name = $wpdb->prefix . "origincode_contact_style_fields";
    $sql_1 = <<<query1
INSERT INTO `$table_name` (`name`, `title`, `description`, `options_name`, `value`) VALUES
('form_selectbox_font_color', 'Form Selectbox Font Color', 'Form Selectbox Font Color', '1', '393939'),
('form_label_success_message', 'Form Label Success Color', 'Form Label Success Color', '1', '3DAD48'),
('form_button_reset_icon_hover_color', 'Form Button Reset Icon Hover Color', 'Form Button Reset Icon Hover Color', '1', 'FFFFFF'),
('form_label_required_color', 'Form Label Required Color', 'Form Label Required Color', '1', 'FE5858'),
('form_button_reset_icon_color', 'Form Button Reset Icon Color', 'Form Button Reset Icon Color', '1', 'FFFFFF'),
('form_button_reset_icon_style', 'Form Button Reset Icon Style', 'Form Button Reset Icon Style', '1', 'originicons-retweet'),
('form_button_reset_has_icon', 'Form Reset Button Has Icon', 'Form Reset Button Has Icon', '1', 'off'),
('form_button_reset_border_radius', 'Form Button Reset Border Radius', 'Form Button Reset Border Radius', '1', '1'),
('form_button_reset_border_size', 'Form Button Reset Border Size', 'Form Button Reset Border Size', '1', '1'),
('form_button_reset_border_color', 'Form Button Reset Border Color', 'Form Button Reset Border Color', '1', 'FE5858'),
('form_button_reset_background', 'Form Button Reset Background', 'Form Button Reset Background', '1', 'FFFFFF'),
('form_button_reset_hover_background', 'Form Button Reset Hover Background', 'Form Button Reset Hover Background', '1', 'FFFFFF'),
('form_button_reset_font_color', 'Form Button Reset Font Color', 'Form Button Reset Font Color', '1', 'FE5858'),
('form_button_reset_font_hover_color', 'Form Button Reset Font Hover Color', 'Form Button Reset Font Hover Color', '1', 'FE473A'),
('form_custom_css', 'Form Custom CSS', 'Form Custom CSS', '1', '/*Write Your Custom CSS Code Here*/'),
('form_button_submit_icon_color', 'Form Button Submit Icon Color', 'Form Button Submit Icon Color', '1', 'FFFFFF'),
('form_button_submit_icon_hover_color', 'Form Button Submit Icon Hover Color', 'Form Button Submit Icon Hover Color', '1', 'FFFFFF'),
('form_button_submit_icon_style', 'Form Button Submit Icon Style', 'Form Button Submit Icon Style', '1', 'originicons-rocket'),
('form_button_submit_border_radius', 'Form Button Border Submit Radius', 'Form Button Submit Border Radius', '1', '2'),
('form_button_submit_has_icon', 'Form Submit Button Has Icon', 'Form Submit Button Has Icon', '1', 'off'),
('form_button_submit_border_color', 'Form Button Submit Border Color', 'Form Button Submit Border Color', '1', 'FE5858'),
('form_button_submit_border_size', 'Form Button Submit Border Size', 'Form Button Submit Border Size', '1', '1'),
('form_button_submit_hover_background', 'Form Button Submit Hover Background', 'Form Button Submit Hover Background', '1', 'FE473A'),
('form_button_submit_font_hover_color', 'Form Button Submit Font Hover Color', 'Form Button Submit Font Hover Color', '1', 'FFFFFF'),
('form_button_submit_background', 'Form Button Submit Background', 'Form Button Submit Background', '1', 'FE5858'),
('form_button_icons_position', 'Form Button Icons Position', 'Form Button Icons Position', '1', 'left'),
('form_button_submit_font_color', 'Form Button Submit Font Color', 'Form Button Submit Font Color', '1', 'FFFFFF'),
('form_button_font_size', 'Form Button Font Size', 'Form Button Font Size', '1', '14'),
('form_button_padding', 'Form Button Padding', 'Form Button Padding', '1', '8'),
('form_file_icon_hover_color', 'Form File Icon Hover Color', 'Form File Icon Hover Color', '1', 'FFFFFF'),
('form_file_icon_position', 'Form File Icon Position', 'Form File Icon Position', '1', 'left'),
('form_button_position', 'Form Button Position', 'Form Button Position', '1', 'left'),
('form_button_fullwidth', 'Form Button Fullwidth', 'Form Button Fullwidth', '1', 'off'),
('form_file_icon_color', 'Form File Icon Color', 'Form File Icon Color', '1', 'DFDFDF'),
('form_file_has_icon', 'Form File Button Has Icon', 'Form File Button Has Icon', '1', 'on'),
('form_file_icon_style', 'Form File Icon Style', 'Form File Icon Style', '1', 'originicons-cloud-upload'),
('form_file_button_text_color', 'Form File Button Text Color', 'Form File Button Text Color', '1', 'F7F4F4'),
('form_file_button_text_hover_color', 'Form File Button Text Hover Color', 'Form File Button Text Hover Color', '1', 'FFFFFF'),
('form_file_button_background_color', 'Form File Button Background Color', 'Form File Button Background Color', '1', '393939'),
('form_file_button_background_hover_color', 'Form File Button Background Hover Color', 'Form File Button Background Hover Color', '1', 'FE5858'),
('form_file_button_text', 'Form File Button Text', 'Form File Button Text', '1', 'Upload'),
('form_file_font_size', 'Form File Font Size', 'Form File Font Size', '1', '14'),
('form_file_font_color', 'Form File Font Color', 'Form File Font Color', '1', '393939'),
('form_file_border_color', 'Form File Border Color', 'Form File Border Color', '1', 'DEDFE0'),
('form_file_border_radius', 'Form File Border Radius', 'Form File Border Radius', '1', '2'),
('form_file_background', 'Form File Background', 'Form File Background', '1', 'FFFFFF'),
('form_file_border_size', 'Form File Border Size', 'Form File Border Size', '1', '1'),
('form_file_has_background', 'Form File Has Background', 'Form File Has Background', '1', 'on'),
('form_radio_active_color', 'Form Radio Active Color', 'Form Radio Active Color', '1', 'FE5858'),
('form_radio_hover_color', 'Form Radio Hover Color', 'Form Radio Hover Color', '1', 'A9A6A6'),
('form_radio_type', 'Form Radio Type', 'Form Radio Type', '1', 'circle'),
('form_radio_color', 'Form Radio Color', 'Form Radio Color', '1', 'C6C3C3'),
('form_radio_size', 'Form Radio Size', 'Form Radio Size', '1', 'medium'),
('form_checkbox_active_color', 'Form Checkbox Active Color', 'Form Checkbox Active Color', '1', 'FE5858'),
('form_checkbox_hover_color', 'Form Checkbox Hover Color', 'Form Checkbox Hover Color', '1', 'A9A6A6'),
('form_checkbox_type', 'Form Checkbox Type', 'Form Checkbox Type', '1', 'square'),
('form_checkbox_color', 'Form Checkbox Color', 'Form Checkbox Color', '1', 'C6C3C3'),
('form_checkbox_size', 'Form Checkbox Size', 'Form Checkbox Size', '1', 'medium'),
('form_input_text_has_background', 'Form Input Text Has Background', 'Form Input Text Has Background', '1', 'on'),
('form_input_text_background_color', 'Form Input Text Background Color', 'Form Input Text Background Color', '1', 'FFFFFF'),
('form_input_text_border_color', 'Form Input Text Border Color', 'Form Input Text Border Color', '1', 'DEDFE0'),
('form_input_text_border_size', 'Form Input Text Border Size', 'Form Input Text Border Size', '1', '2'),
('form_input_text_border_radius', 'Form Input Text Border Radius', 'Form Input Text Border Radius', '1', '3'),
('form_input_text_font_size', 'Font Input Text Font Size', 'Font Input Text Font Size', '1', '12'),
('form_input_text_font_color', 'Form Input Text Font Color', 'Form Input Text Font Color', '1', '393939'),
('form_textarea_has_background', 'Form Textarea Has Background', 'Form Textarea Has Background', '1', 'on'),
('form_textarea_background_color', 'Form Textarea Background Color', 'Form Textarea Background Color', '1', 'FFFFFF'),
('form_textarea_border_size', 'Form Textarea Border Size', 'Form Textarea Border Size', '1', '1'),
('form_textarea_border_color', 'Form Textarea Border Color', 'Form Textarea Border Color', '1', 'C7C5C5'),
('form_textarea_border_radius', 'Form Textarea Border Radius', 'Form Textarea Border Radius', '1', '1'),
('form_textarea_font_size', 'Form Textarea Font Size', 'Form Textarea Font Size', '1', '12'),
('form_textarea_font_color', 'Form Textarea Font Color', 'Form Textarea Font Color', '1', '393939'),
('form_selectbox_arrow_color', 'Form Selectbox Arrow Color', 'Form Selectbox Arrow Color', '1', 'FE5858'),
('form_selectbox_has_background', 'Form Selectbox Has Background', 'Form Selectbox Has Background', '1', 'on'),
('form_selectbox_background_color', 'Form Selectbox Background Color', 'Form Selectbox Background Color', '1', 'FFFFFF'),
('form_selectbox_font_size', 'Form Selectbox Font Size', 'Form Selectbox Font Size', '1', '14'),
('form_selectbox_border_size', 'Form Selectbox Border Size', 'Form Selectbox Border Size', '1', '1'),
('form_selectbox_border_color', 'Form Selectbox Border Color', 'Form Selectbox Border Color', '1', 'C7C5C5'),
('form_selectbox_border_radius', 'Form Selectbox Border Radius', 'Form Selectbox Border Radius', '1', '2'),
('form_label_error_color', 'Form Label Error Color', 'Form Label Error Color', '1', 'C2171D'),
('form_label_color', 'Form Label Color', 'Form Label Color', '1', '3B3B3B'),
('form_label_font_family', 'Form Label Font Family', 'Form Label Font Family', '1', 'Calibri,Helvetica Neue,Helvetica,Arial,Verdana,sans-serif'),
('form_label_size', 'Form Label Size', 'Form Label Size', '1', '16'),
('form_title_color', 'Form Title Color', 'Form Title Color', '1', 'FE5858'),
('form_title_size', 'Form Title Size', 'Form Title Size', '1', '22'),
('form_show_title', 'Form Show Title', 'Form Show Title', '1', 'on'),
('form_border_size', 'Form Border Size', 'Form Border Size', '1', '0'),
('form_border_color', 'Form Border Color', 'Form Border Color', '1', 'DEDFE0'),
('form_wrapper_width', 'Form Wrapper Width', 'Form Wrapper Width', '1', '100'),
('form_wrapper_background_type', 'Form Wrapper Background Type', 'Form Wrapper Background Type', '1', 'color'),
('form_wrapper_background_color', 'Form Background Color', 'Form Background Color', '1', 'fcfcfc,E6E6E6'),
('form_wrapper_width', 'Form Wrapper Width', 'Form Wrapper Width', '2', '100'),
('form_wrapper_background_type', 'Form Wrapper Background Type', 'Form Wrapper Background Type', '2', 'color'),
('form_wrapper_background_color', 'Form Background Color', 'Form Background Color', '2', 'f8f8f8,000000'),
('form_border_size', 'Form Border Size', 'Form Border Size', '2', '0'),
('form_border_color', 'Form Border Color', 'Form Border Color', '2', 'EAF1F0'),
('form_show_title', 'Form Show Title', 'Form Show Title', '2', 'on'),
('form_title_size', 'Form Title Size', 'Form Title Size', '2', '24'),
('form_title_color', 'Form Title Color', 'Form Title Color', '2', '0DC4C6'),
('form_label_size', 'Form Label Size', 'Form Label Size', '2', '16'),
('form_label_font_family', 'Form Label Font Family', 'Form Label Font Family', '2', 'Consolas,Andale Mono,Monaco,Courier,Courier New,Verdana,sans-serif'),
('form_label_color', 'Form Label Color', 'Form Label Color', '2', '323432'),
('form_label_error_color', 'Form Label Error Color', 'Form Label Error Color', '2', 'D42424'),
('form_selectbox_border_color', 'Form Selectbox Border Color', 'Form Selectbox Border Color', '2', '21A8AA'),
('form_selectbox_border_radius', 'Form Selectbox Border Radius', 'Form Selectbox Border Radius', '2', '2'),
('form_selectbox_border_size', 'Form Selectbox Border Size', 'Form Selectbox Border Size', '2', '1'),
('form_selectbox_font_size', 'Form Selectbox Font Size', 'Form Selectbox Font Size', '2', '14'),
('form_selectbox_background_color', 'Form Selectbox Background Color', 'Form Selectbox Background Color', '2', 'FFFFFF'),
('form_selectbox_has_background', 'Form Selectbox Has Background', 'Form Selectbox Has Background', '2', 'on'),
('form_selectbox_arrow_color', 'Form Selectbox Arrow Color', 'Form Selectbox Arrow Color', '2', '21A8AA'),
('form_textarea_font_color', 'Form Textarea Font Color', 'Form Textarea Font Color', '2', '323432'),
('form_textarea_font_size', 'Form Textarea Font Size', 'Form Textarea Font Size', '2', '14'),
('form_textarea_border_color', 'Form Textarea Border Color', 'Form Textarea Border Color', '2', '0DC4C6'),
('form_textarea_border_radius', 'Form Textarea Border Radius', 'Form Textarea Border Radius', '2', '2'),
('form_textarea_border_size', 'Form Textarea Border Size', 'Form Textarea Border Size', '2', '1'),
('form_textarea_background_color', 'Form Textarea Background Color', 'Form Textarea Background Color', '2', 'FFFFFF'),
('form_textarea_has_background', 'Form Textarea Has Background', 'Form Textarea Has Background', '2', 'on'),
('form_input_text_font_color', 'Form Input Text Font Color', 'Form Input Text Font Color', '2', '323432'),
('form_input_text_font_size', 'Font Input Text Font Size', 'Font Input Text Font Size', '2', '14'),
('form_input_text_border_color', 'Form Input Text Border Color', 'Form Input Text Border Color', '2', '0DC4C6'),
('form_input_text_border_radius', 'Form Input Text Border Radius', 'Form Input Text Border Radius', '2', '2'),
('form_input_text_border_size', 'Form Input Text Border Size', 'Form Input Text Border Size', '2', '1'),
('form_input_text_background_color', 'Form Input Text Background Color', 'Form Input Text Background Color', '2', 'FFFFFF'),
('form_input_text_has_background', 'Form Input Text Has Background', 'Form Input Text Has Background', '2', 'on'),
('form_checkbox_size', 'Form Checkbox Size', 'Form Checkbox Size', '2', 'medium'),
('form_checkbox_type', 'Form Checkbox Type', 'Form Checkbox Type', '2', 'square'),
('form_checkbox_color', 'Form Checkbox Color', 'Form Checkbox Color', '2', '0DC4C6'),
('form_checkbox_hover_color', 'Form Checkbox Hover Color', 'Form Checkbox Hover Color', '2', '21A8AA'),
('form_checkbox_active_color', 'Form Checkbox Active Color', 'Form Checkbox Active Color', '2', '0DC4C6'),
('form_radio_size', 'Form Radio Size', 'Form Radio Size', '2', 'medium'),
('form_radio_type', 'Form Radio Type', 'Form Radio Type', '2', 'circle'),
('form_radio_color', 'Form Radio Color', 'Form Radio Color', '2', '0DC4C6'),
('form_radio_hover_color', 'Form Radio Hover Color', 'Form Radio Hover Color', '2', '21A8AA'),
('form_radio_active_color', 'Form Radio Active Color', 'Form Radio Active Color', '2', '0DC4C6'),
('form_file_has_background', 'Form File Has Background', 'Form File Has Background', '2', 'on'),
('form_file_background', 'Form File Background', 'Form File Background', '2', 'FFFFFF'),
('form_file_border_size', 'Form File Border Size', 'Form File Border Size', '2', '1'),
('form_file_border_radius', 'Form File Border Radius', 'Form File Border Radius', '2', '2'),
('form_file_border_color', 'Form File Border Color', 'Form File Border Color', '2', '0DC4C6'),
('form_file_font_size', 'Form File Font Size', 'Form File Font Size', '2', '14'),
('form_file_font_color', 'Form File Font Color', 'Form File Font Color', '2', '323432'),
('form_file_button_text', 'Form File Button Text', 'Form File Button Text', '2', 'Upload'),
('form_file_button_background_color', 'Form File Button Background Color', 'Form File Button Background Color', '2', '0DC4C6'),
('form_file_button_background_hover_color', 'Form File Button Background Hover Color', 'Form File Button Background Hover Color', '2', '21A8AA'),
('form_file_button_text_color', 'Form File Button Text Color', 'Form File Button Text Color', '2', 'FFFFFF'),
('form_file_button_text_hover_color', 'Form File Button Text Hover Color', 'Form File Button Text Hover Color', '2', 'FFFFFF'),
('form_file_has_icon', 'Form File Button Has Icon', 'Form File Button Has Icon', '2', 'on'),
('form_file_icon_style', 'Form File Icon Style', 'Form File Icon Style', '2', 'originicons-paperclip'),
('form_file_icon_color', 'Form File Icon Color', 'Form File Icon Color', '2', 'FFFFFF'),
('form_file_icon_hover_color', 'Form File Icon Hover Color', 'Form File Icon Hover Color', '2', 'E6F2F2'),
('form_file_icon_position', 'Form File Icon Position', 'Form File Icon Position', '2', 'left'),
('form_button_position', 'Form Button Position', 'Form Button Position', '2', 'right'),
('form_button_fullwidth', 'Form Button Fullwidth', 'Form Button Fullwidth', '2', 'on'),
('form_button_padding', 'Form Button Padding', 'Form Button Padding', '2', '7'),
('form_button_font_size', 'Form Button Font Size', 'Form Button Font Size', '2', '14'),
('form_button_icons_position', 'Form Button Icons Position', 'Form Button Icons Position', '2', 'right'),
('form_button_submit_font_color', 'Form Button Submit Font Color', 'Form Button Submit Font Color', '2', 'FFFFFF'),
('form_button_submit_font_hover_color', 'Form Button Submit Font Hover Color', 'Form Button Submit Font Hover Color', '2', 'E6F2F2'),
('form_button_submit_background', 'Form Button Submit Background', 'Form Button Submit Background', '2', '0DC4C6'),
('form_button_submit_hover_background', 'Form Button Submit Hover Background', 'Form Button Submit Hover Background', '2', '21A8AA'),
('form_button_submit_border_size', 'Form Button Submit Border Size', 'Form Button Submit Border Size', '2', '1'),
('form_button_submit_border_color', 'Form Button Submit Border Color', 'Form Button Submit Border Color', '2', '0DC4C6'),
('form_button_submit_border_radius', 'Form Button Border Submit Radius', 'Form Button Submit Border Radius', '2', '2'),
('form_button_submit_has_icon', 'Form Submit Button Has Icon', 'Form Submit Button Has Icon', '2', 'on'),
('form_button_submit_icon_style', 'Form Button Submit Icon Style', 'Form Button Submit Icon Style', '2', 'originicons-rocket'),
('form_button_submit_icon_color', 'Form Button Submit Icon Color', 'Form Button Submit Icon Color', '2', 'FFFFFF'),
('form_button_submit_icon_hover_color', 'Form Button Submit Icon Hover Color', 'Form Button Submit Icon Hover Color', '2', 'E6F2F2'),
('form_button_reset_font_color', 'Form Button Reset Font Color', 'Form Button Reset Font Color', '2', 'FFFFFF'),
('form_button_reset_font_hover_color', 'Form Button Reset Font Hover Color', 'Form Button Reset Font Hover Color', '2', 'E6F2F2'),
('form_button_reset_background', 'Form Button Reset Background', 'Form Button Reset Background', '2', '0DC4C6'),
('form_button_reset_hover_background', 'Form Button Reset Hover Background', 'Form Button Reset Hover Background', '2', '21A8AA'),
('form_button_reset_border_size', 'Form Button Reset Border Size', 'Form Button Reset Border Size', '2', '1'),
('form_button_reset_border_color', 'Form Button Reset Border Color', 'Form Button Reset Border Color', '2', '0DC4C6'),
('form_button_reset_border_radius', 'Form Button Reset Border Radius', 'Form Button Reset Border Radius', '2', '2'),
('form_button_reset_has_icon', 'Form Reset Button Has Icon', 'Form Reset Button Has Icon', '2', 'on'),
('form_button_reset_icon_style', 'Form Button Reset Icon Style', 'Form Button Reset Icon Style', '2', 'originicons-refresh'),
('form_button_reset_icon_color', 'Form Button Reset Icon Color', 'Form Button Reset Icon Color', '2', 'FFFFFF'),
('form_button_reset_icon_hover_color', 'Form Button Reset Icon Hover Color', 'Form Button Reset Icon Hover Color', '2', 'E6F2F2'),
('form_selectbox_font_color', 'Form Selectbox Font Color', 'Form Selectbox Font Color', '2', '323432'),
('form_label_required_color', 'Form Label REquired Color', 'Form Label REquired Color', '2', '0DC4C6'),
('form_label_success_message', 'Form Label Success Color', 'Form Label Success Color', '2', '30B038'),
('form_custom_css', 'Form Custom CSS', 'Form Custom CSS', '2', '/*Write Your Custom CSS Code Here*/'),
('form_selectbox_font_color', 'Form Selectbox Font Color', 'Form Selectbox Font Color', '3', '333333'),
('form_button_submit_font_hover_color', 'Form Button Submit Font Hover Color', 'Form Button Submit Font Hover Color', '3', 'FFFFFF'),
('form_button_submit_font_color', 'Form Button Submit Font Color', 'Form Button Submit Font Color', '3', 'FFFFFF'),
('form_button_icons_position', 'Form Button Icons Position', 'Form Button Icons Position', '3', 'right'),
('form_button_font_size', 'Form Button Font Size', 'Form Button Font Size', '3', '16'),
('form_button_padding', 'Form Button Padding', 'Form Button Padding', '3', '6'),
('form_button_fullwidth', 'Form Button Fullwidth', 'Form Button Fullwidth', '3', 'on'),
('form_button_position', 'Form Button Position', 'Form Button Position', '3', 'center'),
('form_file_icon_position', 'Form File Icon Position', 'Form File Icon Position', '3', 'right'),
('form_file_icon_hover_color', 'Form File Icon Hover Color', 'Form File Icon Hover Color', '3', 'FFFFFF'),
('form_file_icon_color', 'Form File Icon Color', 'Form File Icon Color', '3', 'FFFFFF'),
('form_file_icon_style', 'Form File Icon Style', 'Form File Icon Style', '3', 'originicons-file-text'),
('form_file_has_icon', 'Form File Button Has Icon', 'Form File Button Has Icon', '3', 'on'),
('form_file_button_text_hover_color', 'Form File Button Text Hover Color', 'Form File Button Text Hover Color', '3', 'FFFFFF'),
('form_file_button_text_color', 'Form File Button Text Color', 'Form File Button Text Color', '3', 'FFFFFF'),
('form_file_button_background_hover_color', 'Form File Button Background Hover Color', 'Form File Button Background Hover Color', '3', '333333'),
('form_file_button_background_color', 'Form File Button Background Color', 'Form File Button Background Color', '3', '333333'),
('form_file_button_text', 'Form File Button Text', 'Form File Button Text', '3', 'Upload'),
('form_file_font_color', 'Form File Font Color', 'Form File Font Color', '3', '333333'),
('form_file_font_size', 'Form File Font Size', 'Form File Font Size', '3', '14'),
('form_file_border_color', 'Form File Border Color', 'Form File Border Color', '3', 'CACDD1'),
('form_file_border_radius', 'Form File Border Radius', 'Form File Border Radius', '3', '3'),
('form_file_border_size', 'Form File Border Size', 'Form File Border Size', '3', '1'),
('form_file_background', 'Form File Background', 'Form File Background', '3', 'EDF0F5'),
('form_file_has_background', 'Form File Has Background', 'Form File Has Background', '3', 'on'),
('form_radio_active_color', 'Form Radio Active Color', 'Form Radio Active Color', '3', '333333'),
('form_radio_hover_color', 'Form Radio Hover Color', 'Form Radio Hover Color', '3', '333333'),
('form_radio_color', 'Form Radio Color', 'Form Radio Color', '3', 'CACDD1'),
('form_radio_type', 'Form Radio Type', 'Form Radio Type', '3', 'circle'),
('form_radio_size', 'Form Radio Size', 'Form Radio Size', '3', 'medium'),
('form_checkbox_active_color', 'Form Checkbox Active Color', 'Form Checkbox Active Color', '3', '333333'),
('form_checkbox_hover_color', 'Form Checkbox Hover Color', 'Form Checkbox Hover Color', '3', '333333'),
('form_checkbox_color', 'Form Checkbox Color', 'Form Checkbox Color', '3', 'CACDD1'),
('form_checkbox_type', 'Form Checkbox Type', 'Form Checkbox Type', '3', 'square'),
('form_checkbox_size', 'Form Checkbox Size', 'Form Checkbox Size', '3', 'medium'),
('form_input_text_has_background', 'Form Input Text Has Background', 'Form Input Text Has Background', '3', 'on'),
('form_input_text_background_color', 'Form Input Text Background Color', 'Form Input Text Background Color', '3', 'EDF0F5'),
('form_input_text_border_size', 'Form Input Text Border Size', 'Form Input Text Border Size', '3', '1'),
('form_input_text_border_radius', 'Form Input Text Border Radius', 'Form Input Text Border Radius', '3', '3'),
('form_input_text_border_color', 'Form Input Text Border Color', 'Form Input Text Border Color', '3', 'CACDD1'),
('form_input_text_font_size', 'Font Input Text Font Size', 'Font Input Text Font Size', '3', '14'),
('form_input_text_font_color', 'Form Input Text Font Color', 'Form Input Text Font Color', '3', '333333'),
('form_textarea_has_background', 'Form Textarea Has Background', 'Form Textarea Has Background', '3', 'on'),
('form_textarea_background_color', 'Form Textarea Background Color', 'Form Textarea Background Color', '3', 'EDF0F5'),
('form_textarea_border_size', 'Form Textarea Border Size', 'Form Textarea Border Size', '3', '1'),
('form_textarea_border_radius', 'Form Textarea Border Radius', 'Form Textarea Border Radius', '3', '3'),
('form_textarea_border_color', 'Form Textarea Border Color', 'Form Textarea Border Color', '3', 'CACDD1'),
('form_textarea_font_size', 'Form Textarea Font Size', 'Form Textarea Font Size', '3', '14'),
('form_textarea_font_color', 'Form Textarea Font Color', 'Form Textarea Font Color', '3', '333333'),
('form_selectbox_arrow_color', 'Form Selectbox Arrow Color', 'Form Selectbox Arrow Color', '3', '333333'),
('form_selectbox_has_background', 'Form Selectbox Has Background', 'Form Selectbox Has Background', '3', 'on'),
('form_selectbox_background_color', 'Form Selectbox Background Color', 'Form Selectbox Background Color', '3', 'EDF0F5'),
('form_selectbox_font_size', 'Form Selectbox Font Size', 'Form Selectbox Font Size', '3', '14'),
('form_selectbox_border_size', 'Form Selectbox Border Size', 'Form Selectbox Border Size', '3', '1'),
('form_selectbox_border_radius', 'Form Selectbox Border Radius', 'Form Selectbox Border Radius', '3', '3'),
('form_selectbox_border_color', 'Form Selectbox Border Color', 'Form Selectbox Border Color', '3', 'CACDD1'),
('form_label_error_color', 'Form Label Error Color', 'Form Label Error Color', '3', 'F01C24'),
('form_label_color', 'Form Label Color', 'Form Label Color', '3', '000000'),
('form_label_font_family', 'Form Label Font Family', 'Form Label Font Family', '3', 'Verdana,sans-serif'),
('form_label_size', 'Form Label Size', 'Form Label Size', '3', '14'),
('form_title_size', 'Form Title Size', 'Form Title Size', '3', '20'),
('form_title_color', 'Form Title Color', 'Form Title Color', '3', '000000'),
('form_show_title', 'Form Show Title', 'Form Show Title', '3', 'off'),
('form_border_color', 'Form Border Color', 'Form Border Color', '3', 'FFFFFF'),
('form_border_size', 'Form Border Size', 'Form Border Size', '3', '0'),
('form_wrapper_background_color', 'Form Background Color', 'Form Background Color', '3', 'FFFFFF,E6E6E6'),
('form_wrapper_background_type', 'Form Wrapper Background Type', 'Form Wrapper Background Type', '3', 'color'),
('form_wrapper_width', 'Form Wrapper Width', 'Form Wrapper Width', '3', '100'),
('form_label_success_message', 'Form Label Success Color', 'Form Label Success Color', '3', '03A60E'),
('form_label_required_color', 'Form Label Required Color', 'Form Label Required Color', '3', '941116'),
('form_button_reset_icon_hover_color', 'Form Button Reset Icon Hover Color', 'Form Button Reset Icon Hover Color', '3', 'FFFFFF'),
('form_button_reset_icon_color', 'Form Button Reset Icon Color', 'Form Button Reset Icon Color', '3', 'FFFFFF'),
('form_button_reset_icon_style', 'Form Button Reset Icon Style', 'Form Button Reset Icon Style', '3', 'originicons-refresh'),
('form_button_reset_has_icon', 'Form Reset Button Has Icon', 'Form Reset Button Has Icon', '3', 'on'),
('form_button_reset_border_radius', 'Form Button Reset Border Radius', 'Form Button Reset Border Radius', '3', '3'),
('form_button_reset_border_color', 'Form Button Reset Border Color', 'Form Button Reset Border Color', '3', '000000'),
('form_button_reset_border_size', 'Form Button Reset Border Size', 'Form Button Reset Border Size', '3', '1'),
('form_button_reset_hover_background', 'Form Button Reset Hover Background', 'Form Button Reset Hover Background', '3', '000000'),
('form_button_reset_background', 'Form Button Reset Background', 'Form Button Reset Background', '3', '333333'),
('form_button_reset_font_hover_color', 'Form Button Reset Font Hover Color', 'Form Button Reset Font Hover Color', '3', 'FFFFFF'),
('form_button_reset_font_color', 'Form Button Reset Font Color', 'Form Button Reset Font Color', '3', 'FFFFFF'),
('form_button_submit_icon_hover_color', 'Form Button Submit Icon Hover Color', 'Form Button Submit Icon Hover Color', '3', 'FFFFFF'),
('form_button_submit_icon_color', 'Form Button Submit Icon Color', 'Form Button Submit Icon Color', '3', 'FFFFFF'),
('form_button_submit_icon_style', 'Form Button Submit Icon Style', 'Form Button Submit Icon Style', '3', 'originicons-paper-plane'),
('form_button_submit_has_icon', 'Form Submit Button Has Icon', 'Form Submit Button Has Icon', '3', 'on'),
('form_button_submit_border_radius', 'Form Button Border Submit Radius', 'Form Button Submit Border Radius', '3', '3'),
('form_button_submit_background', 'Form Button Submit Background', 'Form Button Submit Background', '3', '333333'),
('form_button_submit_hover_background', 'Form Button Submit Hover Background', 'Form Button Submit Hover Background', '3', '000000'),
('form_button_submit_border_size', 'Form Button Submit Border Size', 'Form Button Submit Border Size', '3', '1'),
('form_button_submit_border_color', 'Form Button Submit Border Color', 'Form Button Submit Border Color', '3', '000000'),
('form_custom_css', 'Form Custom CSS', 'Form Custom CSS', '3', '/*Write Your Custom CSS Code Here*/'),
('form_file_font_size', 'Form File Font Size', 'Form File Font Size', '4', '14'),
('form_file_border_color', 'Form File Border Color', 'Form File Border Color', '4', '24A33F'),
('form_file_border_radius', 'Form File Border Radius', 'Form File Border Radius', '4', '2'),
('form_file_border_size', 'Form File Border Size', 'Form File Border Size', '4', '1'),
('form_file_background', 'Form File Background', 'Form File Background', '4', 'FFFFFF'),
('form_file_has_background', 'Form File Has Background', 'Form File Has Background', '4', 'on'),
('form_radio_active_color', 'Form Radio Active Color', 'Form Radio Active Color', '4', '29BA48'),
('form_radio_hover_color', 'Form Radio Hover Color', 'Form Radio Hover Color', '4', '24A33F'),
('form_radio_color', 'Form Radio Color', 'Form Radio Color', '4', 'E9ECEA'),
('form_radio_type', 'Form Radio Type', 'Form Radio Type', '4', 'circle'),
('form_radio_size', 'Form Radio Size', 'Form Radio Size', '4', 'medium'),
('form_checkbox_active_color', 'Form Checkbox Active Color', 'Form Checkbox Active Color', '4', '29BA48'),
('form_checkbox_hover_color', 'Form Checkbox Hover Color', 'Form Checkbox Hover Color', '4', '24A33F'),
('form_checkbox_color', 'Form Checkbox Color', 'Form Checkbox Color', '4', 'E9ECEA'),
('form_checkbox_type', 'Form Checkbox Type', 'Form Checkbox Type', '4', 'square'),
('form_checkbox_size', 'Form Checkbox Size', 'Form Checkbox Size', '4', 'medium'),
('form_input_text_has_background', 'Form Input Text Has Background', 'Form Input Text Has Background', '4', 'on'),
('form_input_text_background_color', 'Form Input Text Background Color', 'Form Input Text Background Color', '4', 'FFFFFF'),
('form_input_text_border_size', 'Form Input Text Border Size', 'Form Input Text Border Size', '4', '1'),
('form_input_text_border_radius', 'Form Input Text Border Radius', 'Form Input Text Border Radius', '4', '2'),
('form_input_text_border_color', 'Form Input Text Border Color', 'Form Input Text Border Color', '4', '24A33F'),
('form_input_text_font_size', 'Font Input Text Font Size', 'Font Input Text Font Size', '4', '14'),
('form_input_text_font_color', 'Form Input Text Font Color', 'Form Input Text Font Color', '4', '434744'),
('form_textarea_has_background', 'Form Textarea Has Background', 'Form Textarea Has Background', '4', 'on'),
('form_textarea_background_color', 'Form Textarea Background Color', 'Form Textarea Background Color', '4', 'FFFFFF'),
('form_textarea_border_size', 'Form Textarea Border Size', 'Form Textarea Border Size', '4', '1'),
('form_textarea_border_radius', 'Form Textarea Border Radius', 'Form Textarea Border Radius', '4', '2'),
('form_textarea_border_color', 'Form Textarea Border Color', 'Form Textarea Border Color', '4', '24A33F'),
('form_textarea_font_size', 'Form Textarea Font Size', 'Form Textarea Font Size', '4', '14'),
('form_textarea_font_color', 'Form Textarea Font Color', 'Form Textarea Font Color', '4', '434744'),
('form_selectbox_arrow_color', 'Form Selectbox Arrow Color', 'Form Selectbox Arrow Color', '4', '434744'),
('form_selectbox_has_background', 'Form Selectbox Has Background', 'Form Selectbox Has Background', '4', 'on'),
('form_selectbox_background_color', 'Form Selectbox Background Color', 'Form Selectbox Background Color', '4', 'FFFFFF'),
('form_selectbox_font_size', 'Form Selectbox Font Size', 'Form Selectbox Font Size', '4', '14'),
('form_selectbox_border_size', 'Form Selectbox Border Size', 'Form Selectbox Border Size', '4', '1'),
('form_selectbox_border_radius', 'Form Selectbox Border Radius', 'Form Selectbox Border Radius', '4', '2'),
('form_selectbox_border_color', 'Form Selectbox Border Color', 'Form Selectbox Border Color', '4', '24A33F'),
('form_label_color', 'Form Label Color', 'Form Label Color', '4', '444444'),
('form_label_error_color', 'Form Label Error Color', 'Form Label Error Color', '4', 'C2171D'),
('form_label_font_family', 'Form Label Font Family', 'Form Label Font Family', '4', 'Arial,Helvetica Neue,Helvetica,sans-serif'),
('form_label_size', 'Form Label Size', 'Form Label Size', '4', '16'),
('form_title_color', 'Form Title Color', 'Form Title Color', '4', '24A33F'),
('form_title_size', 'Form Title Size', 'Form Title Size', '4', '20'),
('form_show_title', 'Form Show Title', 'Form Show Title', '4', 'on'),
('form_border_color', 'Form Border Color', 'Form Border Color', '4', 'E9ECEA'),
('form_border_size', 'Form Border Size', 'Form Border Size', '4', '0'),
('form_wrapper_background_color', 'Form Background Color', 'Form Background Color', '4', 'FFFFFF,E6E6E6'),
('form_wrapper_background_type', 'Form Wrapper Background Type', 'Form Wrapper Background Type', '4', 'transparent'),
('form_wrapper_width', 'Form Wrapper Width', 'Form Wrapper Width', '4', '100'),
('form_selectbox_font_color', 'Form Selectbox Font Color', 'Form Selectbox Font Color', '4', '434744'),
('form_label_success_message', 'Form Label Success Color', 'Form Label Success Color', '4', '000000'),
('form_label_required_color', 'Form Label Required Color', 'Form Label Required Color', '4', '24A33F'),
('form_button_reset_icon_hover_color', 'Form Button Reset Icon Hover Color', 'Form Button Reset Icon Hover Color', '4', '24A33F'),
('form_button_reset_icon_color', 'Form Button Reset Icon Color', 'Form Button Reset Icon Color', '4', '29BA48'),
('form_button_reset_icon_style', 'Form Button Reset Icon Style', 'Form Button Reset Icon Style', '4', 'originicons-bars'),
('form_button_reset_has_icon', 'Form Reset Button Has Icon', 'Form Reset Button Has Icon', '4', 'off'),
('form_button_reset_border_radius', 'Form Button Reset Border Radius', 'Form Button Reset Border Radius', '4', '2'),
('form_button_reset_border_color', 'Form Button Reset Border Color', 'Form Button Reset Border Color', '4', '29BA48'),
('form_button_reset_border_size', 'Form Button Reset Border Size', 'Form Button Reset Border Size', '4', '1'),
('form_button_reset_hover_background', 'Form Button Reset Hover Background', 'Form Button Reset Hover Background', '4', 'F1F1F1'),
('form_button_reset_background', 'Form Button Reset Background', 'Form Button Reset Background', '4', 'FFFFFF'),
('form_button_reset_font_hover_color', 'Form Button Reset Font Hover Color', 'Form Button Reset Font Hover Color', '4', '24A33F'),
('form_button_reset_font_color', 'Form Button Reset Font Color', 'Form Button Reset Font Color', '4', '29BA48'),
('form_button_submit_icon_hover_color', 'Form Button Submit Icon Hover Color', 'Form Button Submit Icon Hover Color', '4', 'FFFFFF'),
('form_button_submit_icon_color', 'Form Button Submit Icon Color', 'Form Button Submit Icon Color', '4', 'FFFFFF'),
('form_button_submit_icon_style', 'Form Button Submit Icon Style', 'Form Button Submit Icon Style', '4', 'originicons-paper-plane'),
('form_button_submit_has_icon', 'Form Submit Button Has Icon', 'Form Submit Button Has Icon', '4', 'on'),
('form_button_submit_border_radius', 'Form Button Border Submit Radius', 'Form Button Submit Border Radius', '4', '2'),
('form_button_submit_border_color', 'Form Button Submit Border Color', 'Form Button Submit Border Color', '4', '29BA48'),
('form_button_submit_border_size', 'Form Button Submit Border Size', 'Form Button Submit Border Size', '4', '1'),
('form_button_submit_hover_background', 'Form Button Submit Hover Background', 'Form Button Submit Hover Background', '4', '24A33F'),
('form_button_submit_background', 'Form Button Submit Background', 'Form Button Submit Background', '4', '29BA48'),
('form_button_submit_font_hover_color', 'Form Button Submit Font Hover Color', 'Form Button Submit Font Hover Color', '4', 'FFFFFF'),
('form_button_submit_font_color', 'Form Button Submit Font Color', 'Form Button Submit Font Color', '4', 'F1F1F1'),
('form_button_icons_position', 'Form Button Icons Position', 'Form Button Icons Position', '4', 'left'),
('form_button_font_size', 'Form Button Font Size', 'Form Button Font Size', '4', '14'),
('form_button_padding', 'Form Button Padding', 'Form Button Padding', '4', '6'),
('form_button_fullwidth', 'Form Button Fullwidth', 'Form Button Fullwidth', '4', 'off'),
('form_button_position', 'Form Button Position', 'Form Button Position', '4', 'right'),
('form_file_icon_position', 'Form File Icon Position', 'Form File Icon Position', '4', 'right'),
('form_file_icon_hover_color', 'Form File Icon Hover Color', 'Form File Icon Hover Color', '4', 'F1F1F1'),
('form_file_icon_color', 'Form File Icon Color', 'Form File Icon Color', '4', 'FFFFFF'),
('form_file_icon_style', 'Form File Icon Style', 'Form File Icon Style', '4', 'originicons-paperclip'),
('form_file_has_icon', 'Form File Button Has Icon', 'Form File Button Has Icon', '4', 'on'),
('form_file_button_text_hover_color', 'Form File Button Text Hover Color', 'Form File Button Text Hover Color', '4', 'FFFFFF'),
('form_file_button_text_color', 'Form File Button Text Color', 'Form File Button Text Color', '4', 'FFFFFF'),
('form_file_button_background_hover_color', 'Form File Button Background Hover Color', 'Form File Button Background Hover Color', '4', '24A33F'),
('form_file_button_background_color', 'Form File Button Background Color', 'Form File Button Background Color', '4', '29BA48'),
('form_file_button_text', 'Form File Button Text', 'Form File Button Text', '4', 'Upload'),
('form_file_font_color', 'Form File Font Color', 'Form File Font Color', '4', '444444'),
('form_custom_css', 'Form Custom CSS', 'Form Custom CSS', '4', '/*Write Your Custom CSS Code Here*/'),
('form_textarea_border_color', 'Form Textarea Border Color', 'Form Textarea Border Color', '5', 'ABABAB'),
('form_textarea_font_size', 'Form Textarea Font Size', 'Form Textarea Font Size', '5', '12'),
('form_textarea_font_color', 'Form Textarea Font Color', 'Form Textarea Font Color', '5', '444444'),
('form_selectbox_arrow_color', 'Form Selectbox Arrow Color', 'Form Selectbox Arrow Color', '5', 'ABABAB'),
('form_selectbox_has_background', 'Form Selectbox Has Background', 'Form Selectbox Has Background', '5', 'on'),
('form_selectbox_background_color', 'Form Selectbox Background Color', 'Form Selectbox Background Color', '5', 'FFFFFF'),
('form_selectbox_font_size', 'Form Selectbox Font Size', 'Form Selectbox Font Size', '5', '12'),
('form_selectbox_border_size', 'Form Selectbox Border Size', 'Form Selectbox Border Size', '5', '1'),
('form_selectbox_border_radius', 'Form Selectbox Border Radius', 'Form Selectbox Border Radius', '5', '1'),
('form_selectbox_border_color', 'Form Selectbox Border Color', 'Form Selectbox Border Color', '5', 'ABABAB'),
('form_label_error_color', 'Form Label Error Color', 'Form Label Error Color', '5', 'C2171D'),
('form_label_color', 'Form Label Color', 'Form Label Color', '5', '444444'),
('form_label_font_family', 'Form Label Font Family', 'Form Label Font Family', '5', 'Arial,Helvetica Neue,Helvetica,sans-serif'),
('form_label_size', 'Form Label Size', 'Form Label Size', '5', '16'),
('form_title_color', 'Form Title Color', 'Form Title Color', '5', '328FE6'),
('form_title_size', 'Form Title Size', 'Form Title Size', '5', '24'),
('form_show_title', 'Form Show Title', 'Form Show Title', '5', 'on'),
('form_border_color', 'Form Border Color', 'Form Border Color', '5', 'EBECEC'),
('form_border_size', 'Form Border Size', 'Form Border Size', '5', '0'),
('form_wrapper_background_color', 'Form Background Color', 'Form Background Color', '5', 'F9F9F9,E6E6E6'),
('form_wrapper_width', 'Form Wrapper Width', 'Form Wrapper Width', '5', '100'),
('form_wrapper_background_type', 'Form Wrapper Background Type', 'Form Wrapper Background Type', '5', 'color'),
('form_textarea_border_radius', 'Form Textarea Border Radius', 'Form Textarea Border Radius', '5', '2'),
('form_textarea_border_size', 'Form Textarea Border Size', 'Form Textarea Border Size', '5', '1'),
('form_textarea_background_color', 'Form Textarea Background Color', 'Form Textarea Background Color', '5', 'FFFFFF'),
('form_textarea_has_background', 'Form Textarea Has Background', 'Form Textarea Has Background', '5', 'on'),
('form_input_text_font_color', 'Form Input Text Font Color', 'Form Input Text Font Color', '5', '4F4F4F'),
('form_input_text_font_size', 'Font Input Text Font Size', 'Font Input Text Font Size', '5', '12'),
('form_input_text_border_color', 'Form Input Text Border Color', 'Form Input Text Border Color', '5', 'ABABAB'),
('form_input_text_border_radius', 'Form Input Text Border Radius', 'Form Input Text Border Radius', '5', '1'),
('form_input_text_border_size', 'Form Input Text Border Size', 'Form Input Text Border Size', '5', '1'),
('form_input_text_background_color', 'Form Input Text Background Color', 'Form Input Text Background Color', '5', 'FFFFFF'),
('form_input_text_has_background', 'Form Input Text Has Background', 'Form Input Text Has Background', '5', 'on'),
('form_checkbox_size', 'Form Checkbox Size', 'Form Checkbox Size', '5', 'medium'),
('form_checkbox_type', 'Form Checkbox Type', 'Form Checkbox Type', '5', 'square'),
('form_checkbox_color', 'Form Checkbox Color', 'Form Checkbox Color', '5', 'ABABAB'),
('form_checkbox_hover_color', 'Form Checkbox Hover Color', 'Form Checkbox Hover Color', '5', '949292'),
('form_checkbox_active_color', 'Form Checkbox Active Color', 'Form Checkbox Active Color', '5', '328FE6'),
('form_radio_size', 'Form Radio Size', 'Form Radio Size', '5', 'medium'),
('form_radio_type', 'Form Radio Type', 'Form Radio Type', '5', 'circle'),
('form_radio_color', 'Form Radio Color', 'Form Radio Color', '5', 'ABABAB'),
('form_radio_hover_color', 'Form Radio Hover Color', 'Form Radio Hover Color', '5', '949292'),
('form_radio_active_color', 'Form Radio Active Color', 'Form Radio Active Color', '5', '328FE6'),
('form_file_has_background', 'Form File Has Background', 'Form File Has Background', '5', 'on'),
('form_file_background', 'Form File Background', 'Form File Background', '5', 'FFFFFF'),
('form_file_border_size', 'Form File Border Size', 'Form File Border Size', '5', '1'),
('form_file_border_radius', 'Form File Border Radius', 'Form File Border Radius', '5', '1'),
('form_file_border_color', 'Form File Border Color', 'Form File Border Color', '5', '328FE6'),
('form_file_font_size', 'Form File Font Size', 'Form File Font Size', '5', '14'),
('form_file_font_color', 'Form File Font Color', 'Form File Font Color', '5', '4F4F4F'),
('form_file_button_text', 'Form File Button Text', 'Form File Button Text', '5', 'Upload'),
('form_file_button_background_color', 'Form File Button Background Color', 'Form File Button Background Color', '5', '328FE6'),
('form_file_button_background_hover_color', 'Form File Button Background Hover Color', 'Form File Button Background Hover Color', '5', '137ADB'),
('form_file_button_text_color', 'Form File Button Text Color', 'Form File Button Text Color', '5', 'FFFFFF'),
('form_file_button_text_hover_color', 'Form File Button Text Hover Color', 'Form File Button Text Hover Color', '5', 'F9F9F9'),
('form_file_has_icon', 'Form File Button Has Icon', 'Form File Button Has Icon', '5', 'on'),
('form_file_icon_style', 'Form File Icon Style', 'Form File Icon Style', '5', 'originicons-file-text'),
('form_file_icon_color', 'Form File Icon Color', 'Form File Icon Color', '5', 'FFFFFF'),
('form_file_icon_hover_color', 'Form File Icon Hover Color', 'Form File Icon Hover Color', '5', 'F9F9F9'),
('form_file_icon_position', 'Form File Icon Position', 'Form File Icon Position', '5', 'left'),
('form_button_position', 'Form Button Position', 'Form Button Position', '5', 'right'),
('form_button_fullwidth', 'Form Button Fullwidth', 'Form Button Fullwidth', '5', 'off'),
('form_button_padding', 'Form Button Padding', 'Form Button Padding', '5', '6'),
('form_button_font_size', 'Form Button Font Size', 'Form Button Font Size', '5', '14'),
('form_button_icons_position', 'Form Button Icons Position', 'Form Button Icons Position', '5', 'left'),
('form_button_submit_font_color', 'Form Button Submit Font Color', 'Form Button Submit Font Color', '5', 'FFFFFF'),
('form_button_submit_font_hover_color', 'Form Button Submit Font Hover Color', 'Form Button Submit Font Hover Color', '5', 'F0F0F0'),
('form_button_submit_background', 'Form Button Submit Background', 'Form Button Submit Background', '5', '328FE6'),
('form_button_submit_hover_background', 'Form Button Submit Hover Background', 'Form Button Submit Hover Background', '5', '137ADB'),
('form_button_submit_border_size', 'Form Button Submit Border Size', 'Form Button Submit Border Size', '5', '1'),
('form_button_submit_border_color', 'Form Button Submit Border Color', 'Form Button Submit Border Color', '5', '328FE6'),
('form_button_submit_border_radius', 'Form Button Border Submit Radius', 'Form Button Submit Border Radius', '5', '1'),
('form_button_submit_has_icon', 'Form Submit Button Has Icon', 'Form Submit Button Has Icon', '5', 'on'),
('form_button_submit_icon_style', 'Form Button Submit Icon Style', 'Form Button Submit Icon Style', '5', 'originicons-envelope-o'),
('form_button_submit_icon_color', 'Form Button Submit Icon Color', 'Form Button Submit Icon Color', '5', 'FFFFFF'),
('form_button_submit_icon_hover_color', 'Form Button Submit Icon Hover Color', 'Form Button Submit Icon Hover Color', '5', 'FFFFFF'),
('form_button_reset_font_color', 'Form Button Reset Font Color', 'Form Button Reset Font Color', '5', 'FFFFFF'),
('form_button_reset_font_hover_color', 'Form Button Reset Font Hover Color', 'Form Button Reset Font Hover Color', '5', 'FFFFFF'),
('form_button_reset_background', 'Form Button Reset Background', 'Form Button Reset Background', '5', '328FE6'),
('form_button_reset_hover_background', 'Form Button Reset Hover Background', 'Form Button Reset Hover Background', '5', '137ADB'),
('form_button_reset_border_size', 'Form Button Reset Border Size', 'Form Button Reset Border Size', '5', '1'),
('form_button_reset_border_color', 'Form Button Reset Border Color', 'Form Button Reset Border Color', '5', '328FE6'),
('form_button_reset_border_radius', 'Form Button Reset Border Radius', 'Form Button Reset Border Radius', '5', '1'),
('form_button_reset_has_icon', 'Form Reset Button Has Icon', 'Form Reset Button Has Icon', '5', 'on'),
('form_button_reset_icon_style', 'Form Button Reset Icon Style', 'Form Button Reset Icon Style', '5', 'originicons-reply'),
('form_button_reset_icon_color', 'Form Button Reset Icon Color', 'Form Button Reset Icon Color', '5', 'FFFFFF'),
('form_button_reset_icon_hover_color', 'Form Button Reset Icon Hover Color', 'Form Button Reset Icon Hover Color', '5', 'F9F9F9'),
('form_label_required_color', 'Form Label Required Color', 'Form Label Required Color', '5', '328FE6'),
('form_label_success_message', 'Form Label Success Color', 'Form Label Success Color', '5', '00C60E'),
('form_selectbox_font_color', 'Form Selectbox Font Color', 'Form Selectbox Font Color', '5', '4F4F4F'),
('form_custom_css', 'Form Custom CSS', 'Form Custom CSS', '5', '/*Write Your Custom CSS Code Here*/'),
('form_textarea_border_color', 'Form Textarea Border Color', 'Form Textarea Border Color', '6', '2FCCA6'),
('form_textarea_font_size', 'Form Textarea Font Size', 'Form Textarea Font Size', '6', '12'),
('form_textarea_font_color', 'Form Textarea Font Color', 'Form Textarea Font Color', '6', '3B3B3B'),
('form_selectbox_arrow_color', 'Form Selectbox Arrow Color', 'Form Selectbox Arrow Color', '6', '2AB795'),
('form_selectbox_has_background', 'Form Selectbox Has Background', 'Form Selectbox Has Background', '6', 'on'),
('form_selectbox_background_color', 'Form Selectbox Background Color', 'Form Selectbox Background Color', '6', 'FFFFFF'),
('form_selectbox_font_size', 'Form Selectbox Font Size', 'Form Selectbox Font Size', '6', '12'),
('form_selectbox_border_size', 'Form Selectbox Border Size', 'Form Selectbox Border Size', '6', '1'),
('form_selectbox_border_radius', 'Form Selectbox Border Radius', 'Form Selectbox Border Radius', '6', '5'),
('form_selectbox_border_color', 'Form Selectbox Border Color', 'Form Selectbox Border Color', '6', '2AB795'),
('form_label_error_color', 'Form Label Error Color', 'Form Label Error Color', '6', 'C2171D'),
('form_label_color', 'Form Label Color', 'Form Label Color', '6', '444444'),
('form_label_font_family', 'Form Label Font Family', 'Form Label Font Family', '6', 'Arial,Helvetica Neue,Helvetica,sans-serif'),
('form_label_size', 'Form Label Size', 'Form Label Size', '6', '16'),
('form_title_color', 'Form Title Color', 'Form Title Color', '6', '2FCCA6'),
('form_title_size', 'Form Title Size', 'Form Title Size', '6', '22'),
('form_show_title', 'Form Show Title', 'Form Show Title', '6', 'on'),
('form_border_color', 'Form Border Color', 'Form Border Color', '6', 'FFFFFF'),
('form_border_size', 'Form Border Size', 'Form Border Size', '6', '0'),
('form_wrapper_background_color', 'Form Background Color', 'Form Background Color', '6', 'ffffff,ffffff'),
('form_wrapper_width', 'Form Wrapper Width', 'Form Wrapper Width', '6', '100'),
('form_wrapper_background_type', 'Form Wrapper Background Type', 'Form Wrapper Background Type', '6', 'color'),
('form_textarea_border_radius', 'Form Textarea Border Radius', 'Form Textarea Border Radius', '6', '5'),
('form_textarea_border_size', 'Form Textarea Border Size', 'Form Textarea Border Size', '6', '1'),
('form_textarea_background_color', 'Form Textarea Background Color', 'Form Textarea Background Color', '6', 'FFFFFF'),
('form_textarea_has_background', 'Form Textarea Has Background', 'Form Textarea Has Background', '6', 'on'),
('form_input_text_font_color', 'Form Input Text Font Color', 'Form Input Text Font Color', '6', '4F4F4F'),
('form_input_text_font_size', 'Font Input Text Font Size', 'Font Input Text Font Size', '6', '12'),
('form_input_text_border_color', 'Form Input Text Border Color', 'Form Input Text Border Color', '6', '2AB795'),
('form_input_text_border_radius', 'Form Input Text Border Radius', 'Form Input Text Border Radius', '6', '5'),
('form_input_text_border_size', 'Form Input Text Border Size', 'Form Input Text Border Size', '6', '1'),
('form_input_text_background_color', 'Form Input Text Background Color', 'Form Input Text Background Color', '6', 'FFFFFF'),
('form_input_text_has_background', 'Form Input Text Has Background', 'Form Input Text Has Background', '6', 'on'),
('form_checkbox_size', 'Form Checkbox Size', 'Form Checkbox Size', '6', 'medium'),
('form_checkbox_type', 'Form Checkbox Type', 'Form Checkbox Type', '6', 'circle'),
('form_checkbox_color', 'Form Checkbox Color', 'Form Checkbox Color', '6', '2FCCA6'),
('form_checkbox_hover_color', 'Form Checkbox Hover Color', 'Form Checkbox Hover Color', '6', '249E81'),
('form_checkbox_active_color', 'Form Checkbox Active Color', 'Form Checkbox Active Color', '6', '3ED6B3'),
('form_radio_size', 'Form Radio Size', 'Form Radio Size', '6', 'medium'),
('form_radio_type', 'Form Radio Type', 'Form Radio Type', '6', 'circle'),
('form_radio_color', 'Form Radio Color', 'Form Radio Color', '6', '2FCCA6'),
('form_radio_hover_color', 'Form Radio Hover Color', 'Form Radio Hover Color', '6', '249E81'),
('form_radio_active_color', 'Form Radio Active Color', 'Form Radio Active Color', '6', '3ED6B3'),
('form_file_has_background', 'Form File Has Background', 'Form File Has Background', '6', 'on'),
('form_file_background', 'Form File Background', 'Form File Background', '6', 'FFFFFF'),
('form_file_border_size', 'Form File Border Size', 'Form File Border Size', '6', '1'),
('form_file_border_radius', 'Form File Border Radius', 'Form File Border Radius', '6', '5'),
('form_file_border_color', 'Form File Border Color', 'Form File Border Color', '6', '2FCCA6'),
('form_file_font_size', 'Form File Font Size', 'Form File Font Size', '6', '14'),
('form_file_font_color', 'Form File Font Color', 'Form File Font Color', '6', '393939'),
('form_file_button_text', 'Form File Button Text', 'Form File Button Text', '6', 'Upload'),
('form_file_button_background_color', 'Form File Button Background Color', 'Form File Button Background Color', '6', '2AB795'),
('form_file_button_background_hover_color', 'Form File Button Background Hover Color', 'Form File Button Background Hover Color', '6', '249E81'),
('form_file_button_text_color', 'Form File Button Text Color', 'Form File Button Text Color', '6', 'F7F4F4'),
('form_file_button_text_hover_color', 'Form File Button Text Hover Color', 'Form File Button Text Hover Color', '6', 'FFFFFF'),
('form_file_has_icon', 'Form File Button Has Icon', 'Form File Button Has Icon', '6', 'on'),
('form_file_icon_style', 'Form File Icon Style', 'Form File Icon Style', '6', 'originicons-file-text'),
('form_file_icon_color', 'Form File Icon Color', 'Form File Icon Color', '6', 'FFFFFF'),
('form_file_icon_hover_color', 'Form File Icon Hover Color', 'Form File Icon Hover Color', '6', 'FFFFFF'),
('form_file_icon_position', 'Form File Icon Position', 'Form File Icon Position', '6', 'left'),
('form_button_position', 'Form Button Position', 'Form Button Position', '6', 'center'),
('form_button_fullwidth', 'Form Button Fullwidth', 'Form Button Fullwidth', '6', 'on'),
('form_button_padding', 'Form Button Padding', 'Form Button Padding', '6', '8'),
('form_button_font_size', 'Form Button Font Size', 'Form Button Font Size', '6', '14'),
('form_button_icons_position', 'Form Button Icons Position', 'Form Button Icons Position', '6', 'right'),
('form_button_submit_font_color', 'Form Button Submit Font Color', 'Form Button Submit Font Color', '6', 'FFFFFF'),
('form_button_submit_font_hover_color', 'Form Button Submit Font Hover Color', 'Form Button Submit Font Hover Color', '6', 'FFFFFF'),
('form_button_submit_background', 'Form Button Submit Background', 'Form Button Submit Background', '6', '2AB795'),
('form_button_submit_hover_background', 'Form Button Submit Hover Background', 'Form Button Submit Hover Background', '6', '249E81'),
('form_button_submit_border_size', 'Form Button Submit Border Size', 'Form Button Submit Border Size', '6', '1'),
('form_button_submit_border_color', 'Form Button Submit Border Color', 'Form Button Submit Border Color', '6', 'FEFEFE'),
('form_button_submit_border_radius', 'Form Button Border Submit Radius', 'Form Button Submit Border Radius', '6', '30'),
('form_button_submit_has_icon', 'Form Submit Button Has Icon', 'Form Submit Button Has Icon', '6', 'on'),
('form_button_submit_icon_style', 'Form Button Submit Icon Style', 'Form Button Submit Icon Style', '6', 'originicons-rocket'),
('form_button_submit_icon_color', 'Form Button Submit Icon Color', 'Form Button Submit Icon Color', '6', 'FFFFFF'),
('form_button_submit_icon_hover_color', 'Form Button Submit Icon Hover Color', 'Form Button Submit Icon Hover Color', '6', 'FFFFFF'),
('form_button_reset_font_color', 'Form Button Reset Font Color', 'Form Button Reset Font Color', '6', '2AB795'),
('form_button_reset_font_hover_color', 'Form Button Reset Font Hover Color', 'Form Button Reset Font Hover Color', '6', '249E81'),
('form_button_reset_background', 'Form Button Reset Background', 'Form Button Reset Background', '6', 'FFFFFF'),
('form_button_reset_hover_background', 'Form Button Reset Hover Background', 'Form Button Reset Hover Background', '6', 'FFFFFF'),
('form_button_reset_border_size', 'Form Button Reset Border Size', 'Form Button Reset Border Size', '6', '1'),
('form_button_reset_border_color', 'Form Button Reset Border Color', 'Form Button Reset Border Color', '6', '2EC9A4'),
('form_button_reset_border_radius', 'Form Button Reset Border Radius', 'Form Button Reset Border Radius', '6', '30'),
('form_button_reset_has_icon', 'Form Reset Button Has Icon', 'Form Reset Button Has Icon', '6', 'on'),
('form_button_reset_icon_style', 'Form Button Reset Icon Style', 'Form Button Reset Icon Style', '6', 'originicons-reply'),
('form_button_reset_icon_color', 'Form Button Reset Icon Color', 'Form Button Reset Icon Color', '6', '2AB795'),
('form_button_reset_icon_hover_color', 'Form Button Reset Icon Hover Color', 'Form Button Reset Icon Hover Color', '6', '249E81'),
('form_custom_css', 'Form Custom CSS', 'Form Custom CSS', '6', '/*Write Your Custom CSS Code Here*/'),
('form_label_required_color', 'Form Label Required Color', 'Form Label Required Color', '6', '2AB795'),
('form_label_success_message', 'Form Label Success Color', 'Form Label Success Color', '6', '3DAD48'),
('form_selectbox_font_color', 'Form Selectbox Font Color', 'Form Selectbox Font Color', '6', '4F4F4F');
query1;
    $table_name = $wpdb->prefix . "origincode_contact_contacts_fields";
    $sql_2 = "INSERT INTO 
`" . $table_name . "` (`name`, `origincode_contact_id`, `description`, `conttype`, `hc_field_label`, `hc_other_field`, `field_type`, `hc_required`, `ordering`, `published`, `hc_input_show_default`, `hc_left_right`) VALUES
('', '4', 'on', 'text', 'Phone', '', 'number', 'on', 7, 2, '1', 'left'),
('11:00 AM;;11:30 AM;;12:00 PM;;12:30 PM;;1:00 PM;;1:30 PM;;2:00 PM;;2:30 PM;;3:00 PM;;3:30 PM;;4:00 PM;;4:30 PM;;5:30 PM;;6:00 PM;;6:30 PM;;7:00 PM;;7:30 PM;;8:30 PM;;9:00 PM;;9:30 PM;;10:00 PM;;10:30 PM', '4', '', 'selectbox', 'Selectbox', 'Option 2', '', '', 1, 2, '1', 'left'),
('Birthday;;Anniversary;;Business Lunch;;Surprise;;Pre-Theater Dinner;;Retirement;;Farewell', '4', '', 'selectbox', 'Event type', '5', '', '', 3, 2, '1', 'left'),
('1 person;;2 person;;3 person;;4 person;;5 person;;6 person;;7 person;;8 person;;please call us for 9 and more people', '4', '', 'selectbox', 'Party Size', '0', '', '', 2, 2, '1', 'left'),
('', '4', 'on', 'text', 'Surname', '', 'text', '', 5, 2, '1', 'left'),
('', '4', 'on', 'text', 'Name', '', 'text', 'on', 4, 2, '1', 'left'),
('', '4', 'on', 'e_mail', 'E-mail', '', 'name', 'on', 6, 2, '1', 'left'),
('YY/MM/DD', '4', 'on', 'text', 'Date', '', 'text', 'on', 0, 2, '1', 'left'),
('Please let us know if you have any special needs', '4', 'on', 'textarea', 'Special requests:', '80', 'on', '', 8, 2, '1', 'left'),
('text', '4', 'Submit', 'buttons', 'Reset', 'go_to_url', '', '', 9, 2, '1', 'left'),
('Type Your Name', '1', 'on', 'text', 'Name', '', 'text', 'on', 0, 2, '1', 'left'),
('text', '1', 'Subscribe!', 'buttons', 'Reset', 'print_success_message', '', '', 2, 2, '1', 'left'),
('Type Your Email', '1', 'on', 'e_mail', 'E-mail', '', 'name', 'on', 1, 2, '1', 'left'),
('', '3', 'on', 'text', 'Last Name', '', 'text', 'on', 1, 2, '1', 'left'),
('', '3', 'on', 'text', 'First Name', '', 'text', 'on', 0, 2, '1', 'left'),
('Address : 1600 Pennsylvania Ave NW<br />Washington, DC 20500, United States<br />Phone: <a href=\"tel:+1 202-456-4444\">+1 202-456-4444</a></br>Email:  <a href=\"mailto:schedulingrequest@ostp.gov\">schedulingrequest@ostp.gov</a>', '3', 'on', 'custom_text', 'Label', '80', 'on', 'on', 0, 2, '1', 'right'),
('Type Your message here ...', '3', 'on', 'textarea', 'Message', '80', '', 'on', 5, 2, '1', 'left'),
('', '3', 'on', 'text', 'Subject', '', 'text', '', 4, 2, '1', 'left'),
('', '3', 'on', 'text', 'Phone', '', 'number', '', 3, 2, '1', 'left'),
('', '3', 'on', 'e_mail', 'E-mail', '', 'name', 'on', 2, 2, '1', 'left'),
('text', '3', 'Submit', 'buttons', 'Reset', 'go_to_url', '', '', 6, 2, '1', 'left'),
('Type your address', '2', 'on', 'text', 'Address Line 1', '', 'text', 'on', 2, 2, '1', 'right'),
('Tel. number', '2', 'on', 'text', 'Phone Number', '', 'number', 'on', 3, 2, '1', 'left'),
('Type your last name', '2', 'on', 'text', 'Last Name', '', 'text', 'on', 1, 2, '1', 'left'),
('Type your first name', '2', 'on', 'text', 'First Name', '', 'text', 'on', 0, 2, '1', 'left'),
('Type Your Email', '2', 'on', 'e_mail', 'E-mail', '', 'name', 'on', 2, 2, '1', 'left'),
('Type your address', '2', 'on', 'text', 'Address Line 2', '', 'text', '', 3, 2, '1', 'right'),
('California;;New York;;Nevada;;Georgia;;Florida', '2', '', 'selectbox', 'State', 'Option 2', '', '', 0, 2, '1', 'right'),
('Type Your City', '2', 'on', 'text', 'City', '', 'text', 'on', 1, 2, '1', 'right'),
('Credit Card;;Cash on Delivery', '2', '0', 'radio_box', 'Payment Method', 'option 1', 'text', '', 4, 2, '1', 'left'),
('Type your zip code', '2', 'on', 'text', 'Zip Code', '', 'number', 'on', 4, 2, '1', 'right'),
('text', '2', 'Order', 'buttons', 'Reset', 'print_success_message', '', '', 5, 2, '1', 'right')";
    $table_name = $wpdb->prefix . "origincode_contact_contacts";
    $sql_3 = "INSERT INTO `$table_name` (`id`, `name`, `hc_acceptms`, `hc_width`, `hc_userms`, `hc_yourstyle`, `description`, `param`, `ordering`, `published`) VALUES
            (1, 'Subscribe Form', '500', 300, 'true', '3', '2900', '1000', 2, ''),
            (2, 'Delivery Form', '500', 300, 'true', '1', '2900', '1000', 1, ''),
            (3, 'Contact US Form', '500', 300, 'true', '5', '2900', '1000', 1, ''),
            (4, 'Reservation Form', '500', 300, 'true', '4', '2900', '1000', 1, '');";
    $table_name = $wpdb->prefix . "origincode_contact_styles";
    $sql_5 = "
    INSERT INTO `$table_name` (`id`, `name`, `last_update`,`ordering`, `published`) VALUES
    (1, 'Victory ', '12/12/2015', 1, ''),
    (2, 'Fresh Mint', '12/12/2015', 1, ''),
    (3, 'Black&White', '12/12/2015', 1, ''),
    (4, 'Wild Green', '12/12/2015', 1, ''),
    (5, 'Navy ', '12/12/2015', 1, ''),
    (6, 'Ocean Green', '06/16/2017', 1, '');";

    $wpdb->query($sql_origincode_contact_style_fields);
    $wpdb->query($sql_origincode_contact_general_options);
    $wpdb->query($sql_origincode_contact_styles);
    $wpdb->query($sql_origincode_contact_submission);
    $wpdb->query($sql_origincode_contact_contacts_fields);
    $wpdb->query($sql_origincode_contact_contacts);
    $wpdb->query($sql_origincode_contact_subscribers);
    if (!$wpdb->get_var("select count(*) from " . $wpdb->prefix . "origincode_contact_style_fields")) {
        $wpdb->query($sql_1);
    }
    if (!$wpdb->get_var("select count(*) from " . $wpdb->prefix . "origincode_contact_styles")) {
        $wpdb->query($sql_5);
    }
    if (!$wpdb->get_var("select count(*) from " . $wpdb->prefix . "origincode_contact_general_options")) {
        $wpdb->query($sql_4);
    }
    if (!$wpdb->get_var("select count(*) from " . $wpdb->prefix . "origincode_contact_contacts_fields")) {
        $wpdb->query($sql_2);
    }
    if (!$wpdb->get_var("select count(*) from " . $wpdb->prefix . "origincode_contact_contacts")) {
        $wpdb->query($sql_3);
    }

    if (!$wpdb->get_var("select count(*) from " . $wpdb->prefix . "origincode_contact_styles where `id` = 6 ")) {
        $table_name = $wpdb->prefix . "origincode_contact_styles";
        $oc_sql = "INSERT INTO `$table_name` (`id`, `name`, `last_update`,`ordering`, `published`) VALUES (6, 'Ocean Green', '06/16/2017', 1, '')";
        $wpdb->query($oc_sql);
    }

    if (!$wpdb->get_var("select count(*) from " . $wpdb->prefix . "origincode_contact_style_fields WHERE  `options_name` = '6' ")) {
        $table_name = $wpdb->prefix . "origincode_contact_style_fields";
        $n_theme_sql = <<<n_theme_Query
INSERT INTO `$table_name` (`name`, `title`, `description`, `options_name`, `value`) VALUES
('form_textarea_border_color', 'Form Textarea Border Color', 'Form Textarea Border Color', '6', '2FCCA6'),
('form_textarea_font_size', 'Form Textarea Font Size', 'Form Textarea Font Size', '6', '12'),
('form_textarea_font_color', 'Form Textarea Font Color', 'Form Textarea Font Color', '6', '3B3B3B'),
('form_selectbox_arrow_color', 'Form Selectbox Arrow Color', 'Form Selectbox Arrow Color', '6', '2AB795'),
('form_selectbox_has_background', 'Form Selectbox Has Background', 'Form Selectbox Has Background', '6', 'on'),
('form_selectbox_background_color', 'Form Selectbox Background Color', 'Form Selectbox Background Color', '6', 'FFFFFF'),
('form_selectbox_font_size', 'Form Selectbox Font Size', 'Form Selectbox Font Size', '6', '12'),
('form_selectbox_border_size', 'Form Selectbox Border Size', 'Form Selectbox Border Size', '6', '1'),
('form_selectbox_border_radius', 'Form Selectbox Border Radius', 'Form Selectbox Border Radius', '6', '5'),
('form_selectbox_border_color', 'Form Selectbox Border Color', 'Form Selectbox Border Color', '6', '2AB795'),
('form_label_error_color', 'Form Label Error Color', 'Form Label Error Color', '6', 'C2171D'),
('form_label_color', 'Form Label Color', 'Form Label Color', '6', '444444'),
('form_label_font_family', 'Form Label Font Family', 'Form Label Font Family', '6', 'Arial,Helvetica Neue,Helvetica,sans-serif'),
('form_label_size', 'Form Label Size', 'Form Label Size', '6', '16'),
('form_title_color', 'Form Title Color', 'Form Title Color', '6', '2FCCA6'),
('form_title_size', 'Form Title Size', 'Form Title Size', '6', '22'),
('form_show_title', 'Form Show Title', 'Form Show Title', '6', 'on'),
('form_border_color', 'Form Border Color', 'Form Border Color', '6', 'FFFFFF'),
('form_border_size', 'Form Border Size', 'Form Border Size', '6', '0'),
('form_wrapper_background_color', 'Form Background Color', 'Form Background Color', '6', 'ffffff,ffffff'),
('form_wrapper_width', 'Form Wrapper Width', 'Form Wrapper Width', '6', '100'),
('form_wrapper_background_type', 'Form Wrapper Background Type', 'Form Wrapper Background Type', '6', 'color'),
('form_textarea_border_radius', 'Form Textarea Border Radius', 'Form Textarea Border Radius', '6', '5'),
('form_textarea_border_size', 'Form Textarea Border Size', 'Form Textarea Border Size', '6', '1'),
('form_textarea_background_color', 'Form Textarea Background Color', 'Form Textarea Background Color', '6', 'FFFFFF'),
('form_textarea_has_background', 'Form Textarea Has Background', 'Form Textarea Has Background', '6', 'on'),
('form_input_text_font_color', 'Form Input Text Font Color', 'Form Input Text Font Color', '6', '4F4F4F'),
('form_input_text_font_size', 'Font Input Text Font Size', 'Font Input Text Font Size', '6', '12'),
('form_input_text_border_color', 'Form Input Text Border Color', 'Form Input Text Border Color', '6', '2AB795'),
('form_input_text_border_radius', 'Form Input Text Border Radius', 'Form Input Text Border Radius', '6', '5'),
('form_input_text_border_size', 'Form Input Text Border Size', 'Form Input Text Border Size', '6', '1'),
('form_input_text_background_color', 'Form Input Text Background Color', 'Form Input Text Background Color', '6', 'FFFFFF'),
('form_input_text_has_background', 'Form Input Text Has Background', 'Form Input Text Has Background', '6', 'on'),
('form_checkbox_size', 'Form Checkbox Size', 'Form Checkbox Size', '6', 'medium'),
('form_checkbox_type', 'Form Checkbox Type', 'Form Checkbox Type', '6', 'circle'),
('form_checkbox_color', 'Form Checkbox Color', 'Form Checkbox Color', '6', '2FCCA6'),
('form_checkbox_hover_color', 'Form Checkbox Hover Color', 'Form Checkbox Hover Color', '6', '249E81'),
('form_checkbox_active_color', 'Form Checkbox Active Color', 'Form Checkbox Active Color', '6', '3ED6B3'),
('form_radio_size', 'Form Radio Size', 'Form Radio Size', '6', 'medium'),
('form_radio_type', 'Form Radio Type', 'Form Radio Type', '6', 'circle'),
('form_radio_color', 'Form Radio Color', 'Form Radio Color', '6', '2FCCA6'),
('form_radio_hover_color', 'Form Radio Hover Color', 'Form Radio Hover Color', '6', '249E81'),
('form_radio_active_color', 'Form Radio Active Color', 'Form Radio Active Color', '6', '3ED6B3'),
('form_file_has_background', 'Form File Has Background', 'Form File Has Background', '6', 'on'),
('form_file_background', 'Form File Background', 'Form File Background', '6', 'FFFFFF'),
('form_file_border_size', 'Form File Border Size', 'Form File Border Size', '6', '1'),
('form_file_border_radius', 'Form File Border Radius', 'Form File Border Radius', '6', '5'),
('form_file_border_color', 'Form File Border Color', 'Form File Border Color', '6', '2FCCA6'),
('form_file_font_size', 'Form File Font Size', 'Form File Font Size', '6', '14'),
('form_file_font_color', 'Form File Font Color', 'Form File Font Color', '6', '393939'),
('form_file_button_text', 'Form File Button Text', 'Form File Button Text', '6', 'Upload'),
('form_file_button_background_color', 'Form File Button Background Color', 'Form File Button Background Color', '6', '2AB795'),
('form_file_button_background_hover_color', 'Form File Button Background Hover Color', 'Form File Button Background Hover Color', '6', '249E81'),
('form_file_button_text_color', 'Form File Button Text Color', 'Form File Button Text Color', '6', 'F7F4F4'),
('form_file_button_text_hover_color', 'Form File Button Text Hover Color', 'Form File Button Text Hover Color', '6', 'FFFFFF'),
('form_file_has_icon', 'Form File Button Has Icon', 'Form File Button Has Icon', '6', 'on'),
('form_file_icon_style', 'Form File Icon Style', 'Form File Icon Style', '6', 'originicons-file-text'),
('form_file_icon_color', 'Form File Icon Color', 'Form File Icon Color', '6', 'FFFFFF'),
('form_file_icon_hover_color', 'Form File Icon Hover Color', 'Form File Icon Hover Color', '6', 'FFFFFF'),
('form_file_icon_position', 'Form File Icon Position', 'Form File Icon Position', '6', 'left'),
('form_button_position', 'Form Button Position', 'Form Button Position', '6', 'center'),
('form_button_fullwidth', 'Form Button Fullwidth', 'Form Button Fullwidth', '6', 'on'),
('form_button_padding', 'Form Button Padding', 'Form Button Padding', '6', '8'),
('form_button_font_size', 'Form Button Font Size', 'Form Button Font Size', '6', '14'),
('form_button_icons_position', 'Form Button Icons Position', 'Form Button Icons Position', '6', 'right'),
('form_button_submit_font_color', 'Form Button Submit Font Color', 'Form Button Submit Font Color', '6', 'FFFFFF'),
('form_button_submit_font_hover_color', 'Form Button Submit Font Hover Color', 'Form Button Submit Font Hover Color', '6', 'FFFFFF'),
('form_button_submit_background', 'Form Button Submit Background', 'Form Button Submit Background', '6', '2AB795'),
('form_button_submit_hover_background', 'Form Button Submit Hover Background', 'Form Button Submit Hover Background', '6', '249E81'),
('form_button_submit_border_size', 'Form Button Submit Border Size', 'Form Button Submit Border Size', '6', '1'),
('form_button_submit_border_color', 'Form Button Submit Border Color', 'Form Button Submit Border Color', '6', 'FEFEFE'),
('form_button_submit_border_radius', 'Form Button Border Submit Radius', 'Form Button Submit Border Radius', '6', '30'),
('form_button_submit_has_icon', 'Form Submit Button Has Icon', 'Form Submit Button Has Icon', '6', 'on'),
('form_button_submit_icon_style', 'Form Button Submit Icon Style', 'Form Button Submit Icon Style', '6', 'originicons-rocket'),
('form_button_submit_icon_color', 'Form Button Submit Icon Color', 'Form Button Submit Icon Color', '6', 'FFFFFF'),
('form_button_submit_icon_hover_color', 'Form Button Submit Icon Hover Color', 'Form Button Submit Icon Hover Color', '6', 'FFFFFF'),
('form_button_reset_font_color', 'Form Button Reset Font Color', 'Form Button Reset Font Color', '6', '2AB795'),
('form_button_reset_font_hover_color', 'Form Button Reset Font Hover Color', 'Form Button Reset Font Hover Color', '6', '249E81'),
('form_button_reset_background', 'Form Button Reset Background', 'Form Button Reset Background', '6', 'FFFFFF'),
('form_button_reset_hover_background', 'Form Button Reset Hover Background', 'Form Button Reset Hover Background', '6', 'FFFFFF'),
('form_button_reset_border_size', 'Form Button Reset Border Size', 'Form Button Reset Border Size', '6', '1'),
('form_button_reset_border_color', 'Form Button Reset Border Color', 'Form Button Reset Border Color', '6', '2EC9A4'),
('form_button_reset_border_radius', 'Form Button Reset Border Radius', 'Form Button Reset Border Radius', '6', '30'),
('form_button_reset_has_icon', 'Form Reset Button Has Icon', 'Form Reset Button Has Icon', '6', 'on'),
('form_button_reset_icon_style', 'Form Button Reset Icon Style', 'Form Button Reset Icon Style', '6', 'originicons-reply'),
('form_button_reset_icon_color', 'Form Button Reset Icon Color', 'Form Button Reset Icon Color', '6', '2AB795'),
('form_button_reset_icon_hover_color', 'Form Button Reset Icon Hover Color', 'Form Button Reset Icon Hover Color', '6', '249E81'),
('form_label_required_color', 'Form Label Required Color', 'Form Label Required Color', '6', '2AB795'),
('form_label_success_message', 'Form Label Success Color', 'Form Label Success Color', '6', '3DAD48'),
('form_selectbox_font_color', 'Form Selectbox Font Color', 'Form Selectbox Font Color', '6', '4F4F4F');
n_theme_Query;

        $wpdb->query($n_theme_sql);
    }


    changeSubmissionDateColumnType();

    refactorSelectboxPlaceholders();

    addConditionalLogicMaskColumns();

    refactorGeneralOptionsTable();

}

/* change submission_date column type if required */
function changeSubmissionDateColumnType(){
    global $wpdb;
    $type = $wpdb->get_var("SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS 
  WHERE table_name = '" . $wpdb->prefix . "origincode_contact_submission' AND COLUMN_NAME = 'submission_date'");

    if ($type == 'text') {
        $submissions = $wpdb->get_results("SELECT id,submission_date FROM " . $wpdb->prefix . "origincode_contact_submission ");

        /* change submission_date column type from text to datetime */
        $column_type_changed = $wpdb->query("ALTER TABLE " . $wpdb->prefix . "origincode_contact_submission MODIFY submission_date datetime");

        /* change submission_date column type from text to datetime */
        if ($column_type_changed) {
            foreach ($submissions as $key => $submission) {
                $submission_date = date('Y-m-d H:i:s', strtotime($submission->submission_date));
                $id = $submission->id;
                $wpdb->query("UPDATE " . $wpdb->prefix . "origincode_contact_submission
            SET submission_date ='" . $submission_date . "'
            WHERE id = " . $id . " ");
            }
        }

    }
}


/* move selectbox placeholder value from options to separate field */
function refactorSelectboxPlaceholders(){
    global $wpdb;

    $fields = $wpdb->get_results('SELECT id,name,hc_other_field FROM '.$wpdb->prefix.'origincode_contact_contacts_fields  WHERE conttype="selectbox" AND hc_input_show_default="formsInsideAlign" AND def_value = "" AND description = ""' );

    if(count($fields)) {

        foreach ( $fields as $field ){
            $fieldID = $field->id;

            $options = explode(';;', $field->name);

            $defValue = $options[0];

            unset($options[0]);

            $newOptions = implode(';;',$options);

            $wpdb->update($wpdb->prefix.'origincode_contact_contacts_fields',
                array(
                    'def_value'=>$defValue,
                    'name' =>$newOptions,
                    'description'=>'refactored'
                ),
                array(
                    'id'=>$fieldID
                )
            );
        }
    }

}

function addConditionalLogicMaskColumns(){
    global $wpdb;
    /* Add column for Default Value if not exists  */
    $conditionalLogicColumn = $wpdb->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '".$wpdb->dbname."' AND TABLE_NAME = '".$wpdb->prefix."origincode_contact_contacts_fields' AND COLUMN_NAME = 'def_value'");

    if($conditionalLogicColumn==0) {
        $wpdb->query("ALTER TABLE " . $wpdb->prefix . "origincode_contact_contacts_fields ADD def_value text NOT NULL");
    }
    /* Add column for Default Value if not exists  */

    /* Add column for Mask On if not exists  */
    $conditionalLogicColumn = $wpdb->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '".$wpdb->dbname."' AND TABLE_NAME = '".$wpdb->prefix."origincode_contact_contacts_fields' AND COLUMN_NAME = 'mask_on'");

    if($conditionalLogicColumn==0) {
        $wpdb->query("ALTER TABLE " . $wpdb->prefix . "origincode_contact_contacts_fields ADD mask_on text NOT NULL");
    }
    /* Add column for Mask On if not exists  */
}


function refactorGeneralOptionsTable(){
    global $wpdb;


    $uniqueColumns = $wpdb->get_results(
        "SHOW INDEXES FROM ".$wpdb->prefix."origincode_contact_general_options WHERE column_name='name'"
    );

    if(empty($uniqueColumns) ){
        $wpdb->query('ALTER TABLE '.$wpdb->prefix.'origincode_contact_general_options ADD UNIQUE (name)');
    }

}

register_activation_hook(__FILE__, 'origincode_contact_activate');
register_deactivation_hook(__FILE__, 'origincode_contact_subscriber_deactivate');

add_action('init', 'origincode_contact_new_form_callback');
function origincode_contact_new_form_callback()
{
    $wp_upload_dir = wp_upload_dir();

    $condition1 = isset($_GET['page'], $_GET['task'], $_GET['origincode_contact_add_form_nonce']) && $_GET['page'] === 'origincode_forms_main_page' && $_GET['task'] === 'add_cat';
    $condition2 = isset($_GET['page'], $_GET['task'], $_GET['file']) && file_exists($wp_upload_dir['basedir'] . DIRECTORY_SEPARATOR . $_GET['file']);
    $condition3 = isset($_GET['page'], $_GET['task'], $_GET['inputtype']) && $_GET['task'] == 'apply' && $_GET['inputtype'] == 'custom_text';

    if ($condition1 || $condition2 || $condition3) {
        ob_start();
    }

    require_once 'vendor/wpdev-settings/class-wpdev-settings-api.php';
    require_once 'includes/settings.php';
    require_once 'includes/theme-settings.php';
    new Origincode_Contact_WP_Settings();
    new Origincode_Contact_Theme_Options();
}



function origincode_contact_custom_cron_job_recurrence($schedules)
{
    $schedules['origincode-contact-weekly'] = array(
        'display' => __('Once per week', 'origincode-contact'),
        'interval' => 604800
    );
    return $schedules;
}

add_filter('cron_schedules', 'origincode_contact_custom_cron_job_recurrence');
