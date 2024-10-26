<?php
if (!defined('ABSPATH')) exit;

if (!class_exists('Taurus_WC_IG_Main')) {
    class Taurus_WC_IG_Main
    {
        private $email_options;
        function __construct()
        {
            $this->email_options = $this->taurus_wc_ig_check_options();
            // Register the function to generate and send the PDF invoice after an order is placed
            add_action('woocommerce_thankyou', array($this, 'taurus_wc_ig_generate_send_invoice'), 10, 1);
        }

        //Generate Invoice function
        public function taurus_wc_ig_generate_send_invoice($order_id)
        {
            $order = wc_get_order($order_id);
            if (!$order) return;

            // Generate the PDF invoice
            $invoiceGeneratorClass = new Taurus_WC_IG_PDF_Invoice_Generator();
            $pdf_file_path = $invoiceGeneratorClass->pdf_generator($order);

            // Get the recipient email from settings
            $recipient_email = get_option('woo_invoice_email', get_option('admin_email'));

            // Send the email with the invoice attached
            //check if email option is enabled
            // $email_enabled = $this->taurus_wc_ig_check_options();
            if ($this->email_options['email_enabled'] === true) {
                $this->taurus_wc_ig_email_invoice($order, $pdf_file_path, $recipient_email);
            }
        }


        //Send Email function
        public function taurus_wc_ig_email_invoice($order, $pdf_file_path, $recipient_email)
        {
            $to = $recipient_email;
            $subject = 'Invoice for Order #' . $order->get_order_number();
            $message = 'Please find the invoice attached for your recent order.';

            // Prepare headers
            $headers[] = 'Content-Type: text/html; charset=UTF-8';
            if ($this->email_options['email_customer'] == true && null !== $order->get_billing_email()) {
                $headers[] = 'Cc:' . $order->get_billing_email();
            }


            // Attach the PDF file
            $attachments = array($pdf_file_path);

            // Send the email
            // add_filter('wp_mail_from', 'custom_wp_mail_from');
            // function custom_wp_mail_from($original_email_address)
            // {
            //     //Make sure the email is from the same domain 
            //     //as your website to avoid being marked as spam.
            //     return 'orders@hovet.in';
            // }
            wp_mail($to, $subject, $message, $headers, $attachments);

            // Optionally, delete the file after sending to avoid clutter
            unlink($pdf_file_path);
        }

        //Check options
        public function taurus_wc_ig_check_options()
        {
            $taurus_wc_ig_email_enabled = get_option('taurus_wc_ig_enable_thankyou_email', true);
            $taurus_wc_ig_email_customer_enabled = true;
            $taurus_wc_ig_email_cc = true;
            $taurus_wc_ig_email_bcc = true;
            return ['email_enabled' => $taurus_wc_ig_email_enabled, 'email_customer' => $taurus_wc_ig_email_customer_enabled, 'email_cc' => $taurus_wc_ig_email_cc, 'email_bcc' => $taurus_wc_ig_email_bcc];
        }
    }
}

if (class_exists('Taurus_WC_IG_Main')) {
    $invoiceGeneratorMain = new Taurus_WC_IG_Main();
}
