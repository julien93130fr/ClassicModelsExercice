<?php

/**
 * Format un entier ou un float en un chaîne représentant des euros
 */
function euro(int|float $number): string {
    $fmt = numfmt_create( 'fr_FR', NumberFormatter::CURRENCY );
    return numfmt_format_currency($fmt, $number, "EUR");
}