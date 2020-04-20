<?php

class Origincode_Contact_WP_Settings extends WPDEV_Settings_API_Form
{
    public $plugin_id = 'forms_contact';

    public $tablename = 'origincode_contact_general_options';

    public $ta_save = 'settings';

    public function __construct()
    {
        $config = array(
            'menu_slug' => 'origincode_forms_general_options',
            'parent_slug' => 'origincode_forms_main_page',
            'page_title' => __('General Options', 'origincode_contact'),
            'title' => __('Forms Contact General Options', 'origincode_contact'),
            'menu_title' => __('General Options', 'origincode_contact'),
        );
        $this->init();
        $this->init_panels();
        $this->init_sections();
        $this->init_controls();


        parent::__construct($config);

        $this->add_css('wpdev-custom-styles', plugins_url('../vendor/wpdev-settings/assets/css/wpdev-settings.css',__FILE__) );
        $this->add_js('wpdev-custom-js',  plugins_url('../vendor/wpdev-settings/assets/js/wpdev-settings.js',__FILE__));
        $this->add_css('fontawesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css?ver=4.9' );
        $this->add_css('origin-icons-styles', plugins_url('../assets/style/iconfonts/css/originicons.css',__FILE__) );
        $this->add_css('origincode-contact-freebanner', plugins_url('../assets/style/admin.style.css',__FILE__) );
    }

    /**
     * Initialize user defined variables
     */
    public function init()
    {
        $this->init_general_options();
    }

    /**
     *
     */
    public function init_panels()
    {
        $this->panels = array(
            'form_settings' => array(
                'title' => __('Edit Form Settings', 'origincode_contact'),
            ),
        );
    }

    public function init_sections()
    {
        $this->sections = array(
            'form_general_settings' => array(
                'panel' => 'form_settings',
                'title' => __('Form General Settings', 'origincode_contact'),
            ),

            'form_messages' => array(
                'panel' => 'form_settings',
                'title' => __('Error Messages', 'origincode_contact'),
            ),
            'email_admin' => array(
                'panel' => 'form_settings',
                'title' => __('Email To Administrator', 'origincode_contact'),
            ),
            'email_user' => array(
                'panel' => 'form_settings',
                'title' => __('Email To User', 'origincode_contact'),
            ),
            'paypal' => array(
                'panel' => 'form_settings',
                'title' => __('PayPal Settings', 'origincode_contact'),
                'disabled' => true,
                'disabled_link'=>'http://origincode.co/'
            ),
            'gmap' => array(
                'panel' => 'form_settings',
                'title' => __('Google Map Settings', 'origincode_contact'),
                'disabled' => true,
                'disabled_link'=>'http://origincode.co/'
            ),

        );
    }

    /**
     * Display the admin page
     */
    public function init_controls()
    {
        $this->controls = array();
        $controls_forms_general_options = $this->controls_general_options();

        foreach ($controls_forms_general_options as $control_id => $control) {
            $this->controls[$control_id] = $control;
        }


    }

    private function init_general_options()
    {
        $this->form_adminstrator_user_name = $this->get_option_from_table("form_adminstrator_user_name", '');
        $this->form_adminstrator_user_mail = $this->get_option_from_table("form_adminstrator_user_mail", '');
        $this->form_save_reply_to_user = $this->get_option_from_table("form_save_reply_to_user", 'off');
        $this->form_captcha_public_key = $this->get_option_from_table("form_captcha_public_key", '');
        $this->form_captcha_private_key = $this->get_option_from_table("form_captcha_private_key", '');
        $this->form_save_to_database = $this->get_option_from_table("form_save_to_database", '');

        $this->msg_send_success = $this->get_option_from_table("msg_send_success", 'Message is sent successfully');
        $this->msg_send_false = $this->get_option_from_table("msg_send_false", 'Message failed to be sent');
        $this->msg_refered_spam = $this->get_option_from_table("msg_refered_spam", 'Submission was referred to as Spam');
        $this->msg_captcha_error = $this->get_option_from_table("msg_captcha_error", 'Please tick on Captcha box');
        $this->required_empty_field = $this->get_option_from_table("required_empty_field", 'Please Fill This Field');
        $this->msg_invalid_email = $this->get_option_from_table("msg_invalid_email", 'Incorrect Email');
        $this->msg_fail_failed = $this->get_option_from_table("msg_fail_failed", 'Error on file upload');
        $this->msg_file_format = $this->get_option_from_table("msg_file_format", 'Unacceptable file type');
        $this->msg_large_file = $this->get_option_from_table("msg_large_file", 'Exceeds limits on uploaded file');
        $this->msg_simple_captcha_error = $this->get_option_from_table("msg_simple_captcha_error", 'Incorrect Input');

        $this->form_send_email_for_each_submition = $this->get_option_from_table("form_send_email_for_each_submition", 'on');
        $this->form_adminstrator_email = $this->get_option_from_table("form_adminstrator_email", '');
        $this->form_message_subject = $this->get_option_from_table("form_message_subject", 'Form Submitted');
        $this->form_adminstrator_message = $this->get_option_from_table("form_adminstrator_message", '');
        $this->form_send_to_email_user = $this->get_option_from_table("form_send_to_email_user", 'on');
        $this->form_user_message_subject = $this->get_option_from_table("form_user_message_subject", 'Form Submitted');
        $this->form_user_message = $this->get_option_from_table("form_user_message", '');


        $this->origincode_paypal_mode = $this->get_option_from_table("origincode_paypal_mode", 'sandbox');
        $this->origincode_paypal_client_email = $this->get_option_from_table("origincode_paypal_client_email", '');
        $this->origincode_sandbox_client_email = $this->get_option_from_table("origincode_sandbox_client_email", '');
        $this->origincode_paypal_currency = $this->get_option_from_table("origincode_paypal_currency", 'USD');
        $this->origincode_paypal_shopping_url = $this->get_option_from_table("origincode_paypal_shopping_url", '');
        $this->origincode_paypal_return_url = $this->get_option_from_table("origincode_paypal_return_url", '');

        $this->origincode_map_api = $this->get_option_from_table("origincode_map_api", '');
    }

    private function controls_general_options()
    {
        return array(
            'form_adminstrator_user_name' => array(
                'section' => 'form_general_settings',
                'type' => 'text',
                'default' => $this->form_adminstrator_user_name,
                'label' => __('Send Emails From Name', 'origincode_contact'),
            ),
            'form_adminstrator_user_mail' => array(
                'section' => 'form_general_settings',
                'type' => 'email',
                'default' => $this->form_adminstrator_user_mail,
                'label' => __('Send Emails From Email', 'origincode_contact'),
            ),
            'form_save_reply_to_user' => array(
                'section' => 'form_general_settings',
                'type' => 'checkbox',
                'checked_val'=>'on',
                'unchecked_val'=>'off',
                'default' => $this->form_save_reply_to_user,
                'label' => __('Reply To User', 'origincode_contact'),
                'help' => __('Choose whether to get the emails from the user email address', 'origincode_contact')
            ),
            'form_captcha_public_key' => array(
                'section' => 'form_general_settings',
                'type' => 'text',
                'default' => $this->form_captcha_public_key,
                'label' => __('Captcha Public Key', 'origincode_contact'),
            ),
            'form_captcha_private_key' => array(
                'section' => 'form_general_settings',
                'type' => 'text',
                'default' => $this->form_captcha_private_key,
                'label' => __('Captcha Private Key', 'origincode_contact'),
            ),
            'form_save_to_database' => array(
                'section' => 'form_general_settings',
                'type' => 'checkbox',
                'checked_val'=>'on',
                'unchecked_val'=>'off',
                'default' => $this->form_save_to_database,
                'label' => __('Save Submissions To Database', 'origincode_contact'),
                'help' => __('Uncheck this if you don\'t want submissions to be saved in database', 'origincode_contact')
            ),
            'msg_send_success' => array(
                'section' => 'form_messages',
                'type' => 'text',
                'default' => $this->msg_send_success,
                'label' => __('Sender\'s message was sent successfully', 'origincode_contact'),
            ),
            'msg_send_false' => array(
                'section' => 'form_messages',
                'type' => 'text',
                'default' => $this->msg_send_false,
                'label' => __('Sender\'s message was failed to send', 'origincode_contact'),
            ),
            'msg_refered_spam' => array(
                'section' => 'form_messages',
                'type' => 'text',
                'default' => $this->msg_refered_spam,
                'label' => __('Submission was referred to as spam', 'origincode_contact'),
            ),
            'msg_captcha_error' => array(
                'section' => 'form_messages',
                'type' => 'text',
                'default' => $this->msg_captcha_error,
                'label' => __('Captcha is Not Validated', 'origincode_contact'),
            ),
            'required_empty_field' => array(
                'section' => 'form_messages',
                'type' => 'text',
                'default' => $this->required_empty_field,
                'label' => __('Required Field Is Empty', 'origincode_contact'),
            ),
            'msg_invalid_email' => array(
                'section' => 'form_messages',
                'type' => 'text',
                'default' => $this->msg_invalid_email,
                'label' => __('Email address that the sender entered is invalid', 'origincode_contact'),
            ),
            'msg_fail_failed' => array(
                'section' => 'form_messages',
                'type' => 'text',
                'default' => $this->msg_fail_failed,
                'label' => __('Uploading a file fails for any reason', 'origincode_contact'),
            ),
            'msg_file_format' => array(
                'section' => 'form_messages',
                'type' => 'text',
                'default' => $this->msg_file_format,
                'label' => __('Uploaded file is not allowed file type', 'origincode_contact'),
            ),
            'msg_large_file' => array(
                'section' => 'form_messages',
                'type' => 'text',
                'default' => $this->msg_large_file,
                'label' => __('Uploaded file is too large', 'origincode_contact'),
            ),
            'msg_simple_captcha_error' => array(
                'section' => 'form_messages',
                'type' => 'text',
                'default' => $this->msg_simple_captcha_error,
                'label' => __('Simple Captcha Code Incorrect', 'origincode_contact'),
            ),
            'origincode_paypal_mode' => array(
                'section' => 'paypal',
                'type' => 'radio',
                'choices'=>array(
                    'sandbox'=>'Sandbox',
                    'live'=>'Live'
                ),
                'default' => $this->origincode_paypal_mode,
                'label' => __('PayPal Mode', 'origincode_contact'),
                'help' => __('Select Sandbox for Testing Purposes and Live for Live Payments', 'origincode_contact')
            ),
            'origincode_paypal_client_email' => array(
                'section' => 'paypal',
                'type' => 'email',
                'default' => $this->origincode_paypal_client_email,
                'label' => __('PayPal Acount Email Address', 'origincode_contact'),
            ),
            'origincode_sandbox_client_email' => array(
                'section' => 'paypal',
                'type' => 'email',
                'default' => $this->origincode_sandbox_client_email,
                'label' => __('Sandbox Acount Email Address', 'origincode_contact'),
            ),
            'origincode_paypal_currency' => array(
                'section' => 'paypal',
                'type' => 'select',
                'choices'=>array(
                    'USD'=>'USD (U.S. Dollar)',
                    'EUR'=>'EUR (Euro)',
                    'GBP'=>'GBP (Pound Sterling)',
                    'RUB'=>'RUB (Russian Ruble)',
                    'CAD'=>'CAD (Canadian Dollar)',
                    'AUD'=>'AUD (Australian Dollar)',
                    'BRL'=>'BRL (Brazilian Real)',
                    'CZK'=>'CZK (Czech Koruna)',
                    'CHF'=>'CHF (Swiss Franc)',
                    'DKK'=>'DKK (Danish Krone)',
                    'HKD'=>'HKD (Hong Kong Dollar)',
                    'HUF'=>'HUF (Hungarian Forint)',
                    'ILS'=>'ILS (Israeli New Sheqel)',
                    'JPY'=>'JPY (Japanese Yen)',
                    'MYR'=>'MYR (Malaysian Ringgit)',
                    'MXN'=>'MXN (Mexican Peso)',
                    'NOK'=>'NOK (Norwegian Krone)',
                    'NZD'=>'NZD (New Zealand Dollar)',
                    'PHP'=>'PHP (Philippine Peso)',
                    'PLN'=>'PLN (Polish Zloty)',
                    'SGD'=>'SGD (Singapore Dollar)',
                    'SEK'=>'SEK (Swedish Krona)',
                    'TWD'=>'TWD (Taiwan New Dollar)',
                    'THB'=>'THB (Thai Baht)',
                ),
                'default' => $this->origincode_paypal_currency,
                'label' => __('Payments Currency', 'origincode_contact'),
            ),
            'origincode_paypal_shopping_url' => array(
                'section' => 'paypal',
                'type' => 'url',
                'default' => $this->origincode_paypal_shopping_url,
                'label' => __('Continue Shopping URL', 'origincode_contact'),
            ),
            'origincode_paypal_return_url' => array(
                'section' => 'paypal',
                'type' => 'url',
                'default' => $this->origincode_paypal_return_url,
                'label' => __('Return URL', 'origincode_contact'),
            ),
            'origincode_map_api' => array(
                'section' => 'gmap',
                'type' => 'text',
                'default' => $this->origincode_map_api,
                'label' => __('Google Map Api Key', 'origincode_contact'),
            ),
            'form_send_email_for_each_submition' => array(
                'section' => 'email_admin',
                'type' => 'checkbox',
                'checked_val'=>'on',
                'unchecked_val'=>'off',
                'default' => $this->form_send_email_for_each_submition,
                'label' => __('Send Email For Each Submission', 'origincode_contact'),
                'help' => __('Whether to Send an Email to Admin for each Submission', 'origincode_contact')
            ),
            'form_adminstrator_email' => array(
                'section' => 'email_admin',
                'type' => 'textarea',
                'html_class' => array('short-textarea') ,
                'default' => $this->form_adminstrator_email,
                'label' => __('Administrator Email', 'origincode_contact'),
                'help' => __('Add multiple emails,separate them with commas', 'origincode_contact')
            ),
            'form_message_subject' => array(
                'section' => 'email_admin',
                'type' => 'text',
                'default' => $this->form_message_subject,
                'label' => __('Message Subject', 'origincode_contact'),
                'help' => __('If you leave this field empty, the name of the submitted form will be used as the subject of the email', 'origincode_contact')
            ),
            'form_adminstrator_message' => array(
                'section' => 'email_admin',
                'type' => 'editor',
                'editorId' => 'origincode_contact_adminmessage',
                'editorName' => 'form_adminstrator_message',
                'default' => $this->form_adminstrator_message,
                'label' => __('Message Content', 'origincode_contact'),
            ),
            'form_send_to_email_user' => array(
                'section' => 'email_user',
                'type' => 'checkbox',
                'checked_val'=>'on',
                'unchecked_val'=>'off',
                'default' => $this->form_send_to_email_user,
                'label' => __('Send Email For Each Submission', 'origincode_contact'),
                'help' => __('Whether to Send an Email to Admin for each Submission', 'origincode_contact')
            ),
            'form_user_message_subject' => array(
                'section' => 'email_user',
                'type' => 'text',
                'default' => $this->form_user_message_subject,
                'label' => __('Message Subject', 'origincode_contact'),
                'help' => __('If you leave this field empty, the name of the submitted form will be used as the subject of the email', 'origincode_contact')
            ),
            'form_user_message' => array(
                'section' => 'email_user',
                'type' => 'editor',
                'editorId' => 'origincode_contact_usermessage',
                'editorName' => 'form_user_message',
                'default' => $this->form_user_message,
                'label' => __('Message Content', 'origincode_contact'),
            ),
        );
    }




    /**
     * @param $id
     * @param $control
     */
    protected function control_editor( $id, $control ) {
        $default = ( isset( $control['default'] ) ? $control['default'] : "" );

        $html_class = isset( $control['html_class'] ) ? $control['html_class'] : array();

        if ( is_string( $html_class ) ) {
            explode( ' ', $html_class );
        }
        $html_class_str  = implode( ' ', $html_class );
        $label_str       = ( isset( $control['label'] ) ? '<label for="'.$id.'" > ' . $control['label'] : '' );
        $label_str      .= isset( $control['help'] ) ? '<div class="wpdev_settings_help">&#63;<div class="wpdev_settings_help_block"><span class="pnt"></span><p>'. $control['help'] .'</p></div></div></label>' : '</label>';
        $description     = isset( $control['description'] ) ? $control['description'] : "";
        $description_str = $description != "" ? '<p class="description">' . $description . '</p>' : '';

        $attrs = array();
        if ( isset( $control['attrs'] ) && ! empty( $control['attrs'] ) ) {
            foreach ( $control['attrs'] as $k => $attr ) {
                $attrs[] = $k . '=' . $attr;
            }
        }

        $editorId   = ( isset( $control['editorId'] )) ? $control['editorId'] : '';
        $editorName   = ( isset( $control['editorId'] )) ? $control['editorName'] : '';

        echo $label_str;
        ?>

        <?php wp_editor( html_entity_decode(stripslashes($default)), $editorId , array('textarea_name'=>'wpdev_options['.$editorName.']')); ?>
        <?php
        echo $description_str;
    }


    /**
     * @param $key
     * @param bool $default
     * @param bool $concat
     *
     * @return mixed|void
     */
    public function get_option_from_table( $key, $default = false, $concat = true  ) {
        global $wpdb;
        $value = $wpdb->get_var('SELECT `value` FROM '.$wpdb->prefix.$this->tablename.' WHERE `name`="'.$key.'"');

        if(!$value) $value = $default;

        return $value;
    }


    public function drawFreeBanner(){
        Origincode_Contact_Template_Loader::render();
    }

    /**
     * @param $key
     * @param $value
     *
     */
    public function update_option_in_table( $key, $value ) {
        global $wpdb;
        $query = "INSERT INTO ".$wpdb->prefix.$this->tablename." (name,value) VALUES ('".$key."','".$value."')  ON DUPLICATE KEY UPDATE value = '".$value."'";

        $wpdb->query($query);
    }
}

