<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
/**
 * Responsible for managing General tab content
 *
 * @link       http://rextheme.com/
 * @since      8.0.0
 *
 * @package    Wpvr
 * @subpackage Wpvr/admin/classes
 */

class WPVR_General extends WPVR_Tour_setting {

    /**
     * Instance of WPVR_Advanced_control class
     * 
     * @var object
     * @since 8.0.0
     */
    private $advanced_control;

    /**
     * Instance of WPVR_Basic_Setting class
     * 
     * @var object
     * @since 8.0.0
     */
    private $basic_setting;

    /**
     * Instance of WPVR_Control_Button class
     * 
     * @var object
     * @since 8.0.0
     */
    private $control_button;

    function __construct()
    {
        $this->advanced_control = WPVR_Advanced_Control::get_instance();

        $this->control_button   = WPVR_Control_Button::get_instance();

        $this->basic_setting = new WPVR_Basic_Setting();

    }

    /**
     * Render General Tab Content 
     * @param mixed $preview
     * @param mixed $previewtext
     * @param mixed $autoload
     * @param mixed $control
     * @param mixed $postdata
     * @param mixed $autorotation
     * @param mixed $autorotationinactivedelay
     * @param mixed $autorotationstopdelay
     * 
     * @return void
     */
    public function render_setting($postdata)
    {
        ob_start();
        ?>

        <!-- start inner tab -->
        <div class="general-inner-tab">
            <!-- start inner nav -->
            <?php WPVR_Meta_Field::render_general_inner_navigation() ?>
            <!-- end inner nav -->

            <!-- start inner tab content -->
            <div class="inner-nav-content">

                <?php $this->basic_setting->render_basic_setting($postdata); ?>

                <?php WPVR_Advanced_Control::render($postdata); ?>

                <?php WPVR_Control_Button::render($postdata); ?>

            </div>
            <!-- end inner tab content -->

            <!-- Embed Iframe -->
            <?php if (apply_filters('is_wpvr_embed_addon_premium', false)) { $post = get_post(); $id = $post->ID;?>
                <div class="wpvr-use-shortcode">
                    <h4 class="area-title">Using this Tour</h4>
                    <div class="wpvr-shortcode-wrapper">
                        <div class="wpvr-single-shortcode gutenberg">
                            <span class="shortcode-title">To Embed on External Page:</span>
                            <div class="field-wapper">
                                <span>Use the iframe below to share this tour on an external page.</span><br>
                                <span style="color:red;">Note: WooCommerce &amp; Fluent Forms hotspots will not be supported on embedded tours.</span>
                                <div class="wpvr-shortcode-field">
                                <p class="copycode">&lt;iframe src="<?= home_url() ?>/?embed_page=<?= $id ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen width="100%" height="400">&lt;/iframe&gt;</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <!-- End Embed Iframe -->
        </div>
        <!-- end inner tab -->

        <?php
        ob_end_flush();
    }

}