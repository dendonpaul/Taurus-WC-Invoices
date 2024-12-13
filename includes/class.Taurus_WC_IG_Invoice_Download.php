<?php
if (!class_exists('Taurus_WC_IG_Invoice_Download')) {
    class Taurus_WC_IG_Invoice_Download
    {
        function __construct()
        {
            add_action('woocommerce_admin_order_data_after_order_details', [$this, 'taurus_wcig_invoice_download_button']);
        }

        public function taurus_wcig_invoice_download_button($order)
        {
            $order_id = $order->get_id();
?>
            <div class="invoice-download-button">
                <a href="<?php echo esc_url(admin_url('admin.php?page=custom_page&order_id=' . $order_id)); ?>"
                    class="button button-primary"
                    target="_blank">
                    Download Invoice
                </a>
            </div>
            <style>
                .invoice-download-button a {
                    margin-top: 20px !important;
                }
            </style>
<?php
        }
    }
}

if (class_exists('Taurus_WC_IG_Invoice_Download')) {
    $invoice_downloader = new Taurus_WC_IG_Invoice_Download();
}
