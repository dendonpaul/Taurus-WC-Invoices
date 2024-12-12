<?php
if (!defined('ABSPATH')) exit;

if (!class_exists('Taurus_WC_IG_Settings')) {
    class Taurus_WC_IG_Settings
    {
        function __construct()
        {
            add_action('admin_menu', array($this, 'taurus_wcig_add_settings_menu'));
            add_action('admin_init', [$this, 'admin_init']);

            // Save settings
            // add_action('admin_init', [$this, 'woo_save_invoice_settings']);
        }

        public function taurus_wcig_add_settings_menu()
        {
            add_menu_page('Invoice Settings', 'Invoice Settings', 'manage_options', 'taurus-wcig-settings', [$this, 'taurus_wc_ig_invoice_settings_form']);
        }

        public function admin_init()
        {


            //1.Enable Invoice, 2.add custom email for invoice, 3.enable email to customer

            //Register Settings fields
            register_setting(
                'taurus_wcig_group',
                'taurus_wcig_enable_email'
            );
            register_setting(
                'taurus_wcig_group',
                'taurus_wcig_custom_email'
            );
            register_setting(
                'taurus_wcig_group',
                'taurus_wcig_enable_invoice_to_customer'
            );

            //Add settings fields.
            //enable email
            add_settings_field(
                'taurus_wcig_enable_email',
                'Enable Invoice Email',
                [$this, 'taurus_wcig_enable_email_html'],
                'taurus_wcig_group',
                'taurus_wcig_section1'
            );

            //set custom email for invoice sending
            add_settings_field(
                'taurus_wcig_custom_email',
                'Add custom email to send the invoices. Default admin email used',
                [$this, 'taurus_wcig_custom_email_html'],
                'taurus_wcig_group',
                'taurus_wcig_section1'
            );

            //Enable email to customer
            add_settings_field(
                'taurus_wcig_enable_invoice_to_customer',
                'Send invoice email to customer',
                [$this, 'taurus_wcig_enable_invoice_to_customer_html'],
                'taurus_wcig_group',
                'taurus_wcig_section1'
            );

            //Add sections
            add_settings_section(
                'taurus_wcig_section1',
                'Invoice Email Settings',
                null,
                'taurus_wcig_group'
            );
        }

        //Enable Invoice email feature HTML
        public function taurus_wcig_enable_email_html()
        {
            $email_enabled = get_option('taurus_wcig_enable_email', '0'); ?>
            <select name='taurus_wcig_enable_email'>
                <option value='0' <?php echo ($email_enabled == '0') ? 'selected' : ''; ?>>Disable</option>
                <option value='1' <?php echo ($email_enabled == '1') ? 'selected' : ''; ?>>Enable</option>
            </select>
        <?php
        }

        //Set custom email HTML
        public function taurus_wcig_custom_email_html()
        {
            $custom_email = get_option('taurus_wcig_custom_email', get_option('admin_email')); ?>
            <input type="email" name='taurus_wcig_custom_email' value='<?php echo (!empty($custom_email)) ? $custom_email : get_option('admin_email') ?>' />
        <?php

        }

        //Enable invoice email to customer
        public function taurus_wcig_enable_invoice_to_customer_html()
        {
            $enable_customer_email = get_option('taurus_wcig_enable_invoice_to_customer', '0') ?>
            <select name='taurus_wcig_enable_invoice_to_customer'>
                <option value='0' <?php echo ($enable_customer_email == '0') ? 'selected' : '' ?>>Disable</option>
                <option value='1' <?php echo ($enable_customer_email == '1') ? 'selected' : '' ?>>Enable</option>
            </select>
        <?php
        }

        public function taurus_wc_ig_invoice_settings_form()
        { ?>

            <div class="wrap">
                <form method='post' action='options.php'>
                    <?php
                    settings_fields('taurus_wcig_group');
                    do_settings_sections('taurus_wcig_group');
                    submit_button('Save Settings');
                    ?>

                </form>
            </div>

<?php }
    }
}
