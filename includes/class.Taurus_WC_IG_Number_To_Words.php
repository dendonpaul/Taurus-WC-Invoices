<?php
if (!defined('ABSPATH')) exit;

if (!class_exists('Taurus_WC_IG_Number_To_Words')) {
    class Taurus_WC_IG_Number_To_Words
    {
        public function  __construct()
        {
            //blank
        }

        public function currencyLabels(string $locale)
        {
            return [
                "en_US" => ["US Dollars", "cents"],
                "en_IN" => ["Indian Rupees", "paise"]
            ][$locale];
        }

        public function convertNumberToWords(float $amount, string $locale = "en_IN")
        {
            $hasDecimal = fmod($amount, 1) !== 0.0;
            $currencyLabels = $this->currencyLabels($locale);
            $words = "$currencyLabels[0] ";
            $formatter = new NumberFormatter($locale, NumberFormatter::SPELLOUT);

            if (!$hasDecimal) {
                $words .= ucwords(
                    str_replace(
                        "-",
                        " ",
                        $formatter->format($amount, NumberFormatter::CURRENCY)
                    )
                );
                return $words . " Only";
            }

            [$dollars, $cents] = explode(".", $amount);
            $words .= ucwords(
                str_replace(
                    "-",
                    " ",
                    $formatter->format($dollars, NumberFormatter::CURRENCY)
                )
            );
            $words .= " and ";
            $words .= ucwords($formatter->format($cents, NumberFormatter::CURRENCY));
            $words .= " $currencyLabels[1]";
            $words .= " Only";

            return $words;
        }
    }
}
