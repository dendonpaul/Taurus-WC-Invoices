<?php
if (!defined('ABSPATH')) exit;

if (!class_exists('Taurus_WC_IG_PDF_Invoice_Generator')) {
    class Taurus_WC_IG_PDF_Invoice_Generator
    {
        function __construct()
        {
            require_once(WOO_INVOICE_PLUGIN_DIR_PATH . 'apps/fpdf/fpdf.php');
            require_once(WOO_INVOICE_PLUGIN_DIR_PATH . 'includes/class.Taurus_WC_IG_GST_Calculator.php');
            require_once(WOO_INVOICE_PLUGIN_DIR_PATH . 'includes/class.Taurus_WC_IG_Number_To_Words.php');
        }

        public function pdf_generator($order)
        {

            //Billing Details
            $billing_name = $order->get_billing_first_name() . ' ' . $order->get_billing_last_name();
            $billing_address = $order->get_billing_address_1() . ', ' . $order->get_billing_city() . ', ' . $order->get_billing_state() . ' - ' . $order->get_billing_postcode();
            $billing_email = $order->get_billing_email();
            $billing_phone = $order->get_billing_phone();


            $pdf = new FPDF();
            $pdf->AddPage();

            // Set document title and metadata
            $pdf->SetTitle('Invoice for Order #' . $order->get_order_number());
            $pdf->SetAuthor(bloginfo('name'));

            // Set font to Arial, bold, 14pt
            $pdf->SetFont('Arial', 'B', 14);

            //Cell(width,height,text,border,end line,[align])
            $pdf->Cell(189, 20, '', 0, 1);
            $pdf->Cell(120, 5, 'Curewell Homoeo Pharmacy', 0, 0);
            $logo_path = get_template_directory() . '/images/logo.png';
            if (file_exists($logo_path)) {
                $pdf->Image($logo_path, 10, 10, 15); // X = 10, Y = 10, Width = 30
            }
            $pdf->Cell(50, 5, 'Invoice', 0, 1);

            //Set font to arial, regular, 12pt
            $pdf->SetFont('Arial', '', 12);

            //header Details - company address

            // $pdf->Cell(30);
            // $pdf->Cell(120, 5, "Althara GCDA Road,", 0, 0);
            $pdf->Cell(120);
            $pdf->Cell(40, 5, "Invoice #:" . $order->get_order_number(), 0, 1);

            // // $pdf->Cell(30);
            // $pdf->Cell(120, 5, "Thottakkattukara P.O, Aluva,", 0, 0);
            $pdf->Cell(120);
            $pdf->Cell(40, 5, "Date/Time: " . $order->get_date_created()->date('Y-m-d H:i:s'), 0, 1);

            // // $pdf->Cell(30);
            // $pdf->Cell(120, 5, "Kerala - 683108", 0, 0);
            $pdf->Cell(120);
            $pdf->Cell(40, 5, "Cust. Name: " . $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(), 0, 1);

            $pdf->SetY(35);
            $pdf->MultiCell(100, 5, get_option('taurus_wcig_company_address'), 0, 1);

            // $pdf->Cell(30);
            $address = get_option('taurus_wcig_custom_email', get_option('admin_email'));
            $pdf->Cell(120, 5, "Email:" . $address, 0, 0);
            $pdf->Cell(40, 5, "", 0, 1);

            // $pdf->Cell(30);
            $mobile = get_option('taurus_wcig_comp_mobile');
            $pdf->Cell(120, 5, "Phone: " . $mobile, 0, 0);
            $pdf->Cell(40, 5, "", 0, 1);

            //Blank Cell for white space
            $pdf->Cell(189, 10, "", 0, 1);

            // $pdf->Cell(30);
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->Cell(100, 10, "Bill To:", 0, 1);

            //Billing Details Section

            $pdf->SetFont('Arial', '', 12);
            // $pdf->Cell(30);
            $pdf->Cell(20, 5, 'Name: ', 0, 0);
            $pdf->Cell(50, 5, $billing_name, 0, 1);

            // $pdf->Cell(30);
            $pdf->Cell(20, 5, 'Address: ', 0, 0);
            $pdf->MultiCell(100, 5, $billing_address, 0, 1);

            // $pdf->Cell(30);
            $pdf->Cell(20, 5, 'Email: ', 0, 0);
            $pdf->Cell(50, 5,  $billing_email, 0, 1);

            // $pdf->Cell(30);
            $pdf->Cell(20, 5, 'Phone: ', 0, 0);
            $pdf->Cell(50, 5, $billing_phone, 0, 1);

            //Blank Cell for white space
            $pdf->Cell(189, 10, "", 0, 1);

            //Order Details


            // Product details
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(100, 10, 'Order Details:', 0, 1);
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(80, 10, 'Product', 1);
            $pdf->Cell(30, 10, 'Qty', 1);
            $pdf->Cell(40, 10, 'Price', 1);
            // $pdf->Cell(20, 10, 'IGST', 1);
            // $pdf->Cell(20, 10, 'CGST', 1);
            // $pdf->Cell(20, 10, 'Round off', 1);
            $pdf->Cell(40, 10, 'Total', 1);
            $pdf->Ln();



            //Copied


            // Loop through the order items
            $pdf->SetFont('Arial', '', 12);
            foreach ($order->get_items() as $item) {
                $product_name = wp_strip_all_tags($item->get_name());
                $quantity = $item->get_quantity();
                $total = $item->get_total();
                $price = $item->get_subtotal() / $quantity; // Per item price


                $pdf->Cell(80, 10, $product_name, 1);
                $pdf->Cell(30, 10, $quantity, 1, 0, 'C');
                $pdf->Cell(40, 10, $price, 1);
                $pdf->Cell(40, 10, $total, 1);
                $pdf->Ln();
            }

            $pdf->Ln(10); // Line break

            //Tax Calculation
            //0: inclusivePrice 1:basePrice 2:totalGST 3:CGST 4:IGST 5:roundOff
            $gst_calculator = new Taurus_WC_IG_GST_Calculator();
            $gstData = $gst_calculator->gst_calculator($order->get_subtotal());
            $gst = number_format($gstData[2], 2);
            $basePrice = number_format($gstData[1], 2);

            // Order totals
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(190, 10, 'Order Summary:', 0, 1);

            //Product Price without tax
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(30, 8, 'Price:', 0, 0);
            $pdf->Cell(90, 8, 'Rs.' . $basePrice, 0, 1);

            //Product Tax
            $pdf->Cell(30, 8, 'GST (5%):', 0, 0);
            $pdf->Cell(90, 8, 'Rs.' . $gst, 0, 1);

            //Shippping price or free shipping
            if ($order->get_shipping_total() > 0) {
                $pdf->Cell(30, 8, 'Shipping:', 0, 0);
                $pdf->Cell(90, 8, 'Rs.' . $order->get_shipping_total(), 0, 1);
            }
            if ($order->get_shipping_total() == 0) {
                $pdf->Cell(30, 8, 'Shipping:', 0, 0);
                $pdf->Cell(90, 8, 'Free Shipping', 0, 1);
            }

            //Discount price
            if ($order->get_discount_total() > 0) {
                $pdf->Cell(30, 8, 'Discount:', 0, 0);
                $pdf->Cell(90, 8, $order->get_discount_total(), 0, 1, 'R');
            }

            //Product Subtotal baseprice+gst
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(30, 5, 'Sub Total:', 0, 0);
            $pdf->Cell(90, 5, /*$total_price_wo_gst*/ 'Rs.' . $order->get_subtotal(), 0, 1);

            // Check if payment was done and display paid or pending amount
            if ($order->get_transaction_id() !== '') {
                $pdf->Cell(30, 5, 'Total Paid:', 0, 0);
                $pdf->Cell(90, 5, 'Rs.' . $order->get_total(), 0, 1);
            } else {
                $pdf->Cell(30, 5, 'Total Paid:', 0, 0);
                $pdf->Cell(90, 5, 'Rs. 0', 0, 1);

                $pdf->Cell(30, 5, 'Amount Due:', 0, 0);
                $pdf->Cell(90, 5, 'Rs.' . $order->get_total(), 0, 1);
            }

            //In words
            $numberToWords = new Taurus_WC_IG_Number_To_Words();
            $pdf->Cell(189, 10, 'In Words: ' . $numberToWords->convertNumberToWords($order->get_total()), 0, 0);

            // Line break
            $pdf->Ln(20);

            // Footer with thanks message
            $pdf->SetFont('Arial', 'I', 12);
            $pdf->Cell(190, 5, 'Thank you for shopping with us!', 0, 1, 'C');
            $pdf->Cell(190, 5, 'This is an e-invoice and no signature is required.', 0, 1, 'C');

            //Copied end

            // Output PDF to file
            $upload_dir = wp_upload_dir();
            $pdf_file_path = $upload_dir['basedir'] . '/invoice.pdf';
            // echo $pdf_file_path;
            $pdf->Output('F', $pdf_file_path);
            // $pdf->Output();

            return $pdf_file_path;
            //echo $pdf_file_path;
        }
    }
}
