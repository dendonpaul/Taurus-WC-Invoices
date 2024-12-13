<?php
if (!class_exists('Taurus_WC_IG_Invoice_Download')) {
    class Taurus_WC_IG_Invoice_Download
    {
        function __construct()
        {
            add_action('woocommerce_admin_order_data_after_order_details', [$this, 'taurus_wcig_invoice_download_button']);
            add_action('template_redirect', [$this, 'formHandling']);
        }

        public function taurus_wcig_invoice_download_button($order)
        {
            $order_id = $order->get_id($order);

?>
            <div class="invoice-download-button">
                <form method="post" action="">
                </form>
            </div>

            <div class="invoice-download-button">
                <form method="post" action="">
                    <?php wp_nonce_field('reload_with_post_data', 'custom_nonce'); ?>
                    <input type="hidden" name="order_id" value="<?php echo esc_attr($order_id); ?>">
                    <button type="submit" name="taurus_wcig_download_invoice" class="button button-primary">Download Invoice</button>
                </form>
            </div>
            <style>
                .invoice-download-button form button {
                    margin-top: 20px !important;
                }
            </style>

            <?php

            // Display submitted POST data (if any)
            if (isset($_POST['taurus_wcig_download_invoice']) && isset($_POST['order_id'])) {
                $invoice_generator = new Taurus_WC_IG_PDF_Invoice_Generator();
                $pdf_file = $invoice_generator->pdf_generator($order);


                header("Content-type:application/pdf");
                header('Content-Disposition: attachment; filename=Invoice for Order: ' . $order->get_id());
                readfile($pdf_file);
            } ?>
<?php
        }
    }
}

if (class_exists('Taurus_WC_IG_Invoice_Download')) {
    $invoice_downloader = new Taurus_WC_IG_Invoice_Download();
}
