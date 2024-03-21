<?php

use Illuminate\Support\Carbon;


if (!defined('formatAmount')) {
    function formatAmount($number): string
    {
        $amount = doubleval(str_replace('&#36; ', '', $number));
        return number_format($amount, 2);
    }
}
