<?php

namespace ResponsiveTable\Admin\Customizer;


class CustomizeDonate extends \WP_Customize_Control
{
    public function render_content()
    {
        ?>
        <label>
            <h4 class="customize-control-title"><?php echo esc_html($this->label); ?></h4>
        </label>
        <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
            <input type="hidden" name="cmd" value="_s-xclick" />
            <input type="hidden" name="hosted_button_id" value="JPVFEH3C6WC4Y" />
            <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button" />
            <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
        </form>


        <span class="customize-control-title"><?php  _e('Development plan:', 'wp-responsive-table'); ?></span>
        </label>

        <ul>
            <li>
                <?php  _e('More style options', 'wp-responsive-table'); ?>
            </li>
            <li>
                <?php  _e('Hover effect for rows, columns', 'wp-responsive-table'); ?>
            </li>
            <li>
                <?php  _e('Vertical scroll on small screens', 'wp-responsive-table'); ?>
            </li>
            <li>
                <?php  _e(' Selection of the work area of the plugin. Post content or the whole site.', 'wp-responsive-table'); ?>
            </li>
        </ul>

        <?php
    }

}