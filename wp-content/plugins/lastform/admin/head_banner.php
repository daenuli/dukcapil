<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
function origincode_contact_drawFreeBanner( $freeText = 'no' ) {
    $path_site2 = plugins_url( "../images", __FILE__ );
    ?>
    <div class="free_version_banner" >

        <img class="manual_icon" src="<?php echo $path_site2; ?>/forms_icon.png" alt="user manual" />
        <h1 class="plugin_heading">Origin Forms</h1>
        <ul class="submenu">
            <li>
                <a target="_blank"  href="http://origincode.co/">
				    <?php _e('Demo','origincode_contact');?>
                </a>
            </li>
            <li>
                <a target="_blank"  href="https://wordpress.org/support/plugin/">
				    <?php _e('Support','origincode_contact');?>
                </a>
            </li>
            <li>
                <a target="_blank"  href="http://origincode.co/contact-us/">
			        <?php _e('Contact','origincode_contact');?>
                </a>
            </li>
            <li>
                <a class="get_full_version" href="http://origincode.co/" target="_blank">
                    <?php _e('Go Pro','origincode_contact');?>
                </a>
            </li>
        </ul>
    </div>
    <?php
}