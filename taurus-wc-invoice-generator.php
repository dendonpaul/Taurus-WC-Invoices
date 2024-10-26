<?php

/**
 * Plugin Name: WooCommerce Invoice Generator
 * Description: Generates a PDF invoice for WooCommerce orders and emails it to a specified email address. It can also download invoice pdf from the order actions.
 * Version: 2.0
 * Author: Denny Paul
 */
// Exit if accessed directly
if (!defined('ABSPATH')) exit;

if (!class_exists('Taurus_WC_Invoice_Generator')) {
    class Taurus_WC_Invoice_Generator
    {
        function __construct()
        {
            $this->define_constants();

            require_once(WOO_INVOICE_PLUGIN_DIR_PATH . '/includes/class.Taurus_WC_IG_Main.php');
            require_once(WOO_INVOICE_PLUGIN_DIR_PATH . '/includes/class.Taurus_WC_IG_PDF_Invoice_Generator.php');
        }

        public function define_constants()
        {
            define('WOO_INVOICE_PLUGIN_DIR_PATH', plugin_dir_path(__FILE__));
            define('WOO_INVOICE_PLUGIN_DIR_URL', plugin_dir_url(__FILE__));
        }

        protected function taurus_wcig_main() {}

        //Activation function
        public static function activate()
        {
            //Flush rewrite rules
            update_option('rewrite_rules', '');
        }

        //Deactivation function
        public static function deactivate()
        {
            //Flush rewrite rule
            flush_rewrite_rules();
        }

        //Uninstall function
        public static function uninstall()
        {
            // remove data when uninstalling plugin
        }
    }
}

if (class_exists('Taurus_WC_Invoice_Generator')) {
    register_activation_hook(__FILE__, array('Taurus_Woo_Quick_Fixes', 'activate'));
    register_deactivation_hook(__FILE__, array('Taurus_Woo_Quick_Fixes', 'deactivate'));
    register_uninstall_hook(__FILE__, array('Taurus_Woo_Quick_Fixes', 'uninstall'));

    $invoiceGenerator = new Taurus_WC_Invoice_Generator();
}


//pdfGenerator
//toWordsConverter
//gstCalculator