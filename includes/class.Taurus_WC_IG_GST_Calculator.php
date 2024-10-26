<?php
if (!defined('ABSPATH')) exit;

if (!class_exists('Taurus_WC_IG_GST_Calculator')) {
    class Taurus_WC_IG_GST_Calculator
    {
        function __construct()
        {
            //blank
        }
        public function gst_calculator($inclusivePrice)
        {
            // GST rate
            $gstRate = 5;

            // Calculate the base price (exclusive of GST)
            $basePrice = round($inclusivePrice / (1 + $gstRate / 100), 2);

            // Calculate the total GST amount
            $totalGST = round($inclusivePrice - $basePrice, 2);

            // Split the total GST into CGST and IGST (50% each)
            $cgst = round($totalGST / 2, 2);
            $igst = round($totalGST / 2, 2);

            // Outputting the values
            // echo "Inclusive Price: ₹" . number_format($inclusivePrice, 2) . "<br>";
            // echo "Base Price (exclusive of GST): ₹" . number_format($basePrice, 2) . "<br>";
            // echo "Total GST (5%): ₹" . number_format($totalGST, 2) . "<br>";
            // echo "CGST (2.5%): ₹" . number_format($cgst, 2) . "<br>";
            // echo "IGST (2.5%): ₹" . number_format($igst, 2) . "<br>";

            // Rounding off field
            $roundOff = $inclusivePrice - floor($inclusivePrice);
            // echo "Roundoff: ₹" . number_format($roundOff, 2) . "<br>";
            return [$inclusivePrice, $basePrice, $totalGST, $cgst, $igst, $roundOff];
        }
    }
}
