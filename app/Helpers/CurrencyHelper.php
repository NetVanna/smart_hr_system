<?php

namespace App\Helpers;

use App\Models\Company;

class CurrencyHelper
{
    /**
     * Format currency based on company setting or provided currency.
     */
    public static function format($amount, $currency = 'USD')
    {
        if ($currency === 'KHR') {
            return number_format($amount, 0) . ' ៛';
        }
        return '$' . number_format($amount, 2);
    }

    /**
     * Convert USD to KHR based on specific company rate.
     */
    public static function usdToKhr($usdAmount, $companyId = null)
    {
        $rate = 4100; // Default
        if ($companyId) {
            $company = Company::find($companyId);
            if ($company) {
                $rate = $company->exchange_rate;
            }
        } elseif (auth()->check() && auth()->user()->company) {
            $rate = auth()->user()->company->exchange_rate;
        }

        return $usdAmount * $rate;
    }

    /**
     * Get dual display (USD and KHR)
     */
    public static function dualDisplay($usdAmount, $companyId = null)
    {
        $khrAmount = self::usdToKhr($usdAmount, $companyId);
        return self::format($usdAmount, 'USD') . ' (' . self::format($khrAmount, 'KHR') . ')';
    }
}
