<?php
// app/Services/CouponService.php

namespace App\Services;

use App\Models\Coupon;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CouponService
{
    /**
     * Apply coupon to cart.
     */
    public function applyCoupon($code, $cartTotal = null)
    {
        // Get cart if not provided
        if ($cartTotal === null) {
            $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
            $cartTotal = $cart->total;
        }

        $result = Coupon::validateAndApply($code, $cartTotal);

        if ($result['valid']) {
            // Store coupon in session
            session([
                'coupon_code' => $code,
                'coupon_discount' => $result['discount'],
                'coupon_data' => $result['coupon']
            ]);
        } else {
            // Remove coupon from session
            session()->forget(['coupon_code', 'coupon_discount', 'coupon_data']);
        }

        return $result;
    }

    /**
     * Remove coupon from session.
     */
    public function removeCoupon()
    {
        session()->forget(['coupon_code', 'coupon_discount', 'coupon_data']);

        return [
            'success' => true,
            'message' => 'Coupon removed successfully',
        ];
    }

    /**
     * Get current coupon from session.
     */
    public function getCurrentCoupon()
    {
        if (session()->has('coupon_code')) {
            $coupon = Coupon::findByCode(session('coupon_code'));
            if ($coupon && $coupon->isValid()) {
                return [
                    'code' => $coupon->code,
                    'coupon' => $coupon,
                ];
            }
            $this->removeCoupon();
        }

        return null;
    }

    /**
     * Get discounted total.
     */
    public function getDiscountedTotal($originalTotal)
    {
        $current = $this->getCurrentCoupon();
        if ($current && isset($current['coupon'])) {
            $discount = $current['coupon']->calculateDiscount($originalTotal);
            return max(0, $originalTotal - $discount);
        }

        return $originalTotal;
    }

    /**
     * Get discount amount.
     */
    public function getDiscountAmount($originalTotal)
    {
        $current = $this->getCurrentCoupon();
        if ($current && isset($current['coupon'])) {
            return $current['coupon']->calculateDiscount($originalTotal);
        }

        return 0;
    }
}
