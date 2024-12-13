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

            if (isset($_POST["taurus_wcig_order_id"])) {
                echo "Dello-Henny";
                $invoice_generator = new Taurus_WC_Invoice_Generator();
                $pdf_file = $invoice_generator->pdf_generator($order);
                echo $pdf_file;
            }
?>
            <div class="invoice-download-button">
                <form method="post" action="">
                    <input type='hidden' name='taurus_wcig_order_id' id='taurus_wcig_order_id' />
                    <input type='submit' name='taurus_wcig_download_invoice' value='Download Invoice' class="button button-primary" />
                </form>
            </div>
            <style>
                .invoice-download-button input[type=submit] {
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
