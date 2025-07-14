<?php

namespace Prasanna\WeightShipping\Carriers;

use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Models\CartShippingRate;
use Webkul\Shipping\Carriers\AbstractShipping;

class WeightBased extends AbstractShipping
{
    /**
     * Shipping method carrier code.
     *
     * @var string
     */
    protected $code = 'weightbased';

    /**
     * Shipping method code.
     *
     * @var string
     */
    protected $method = 'weightbased_weightbased';

    /**
     * Calculate rate for weight-based shipping.
     *
     * @return \Webkul\Checkout\Models\CartShippingRate|false
     */
    public function calculate()
    {
        if (! $this->isAvailable()) {
            return false;
        }

        return $this->getRate();
    }

    /**
     * Get rate based on cart weight.
     */
    public function getRate(): CartShippingRate
    {
        $cart = Cart::getCart();
        $totalWeight = $this->calculateTotalWeight($cart);
        $shippingCost = $this->calculateShippingCost($totalWeight);

        $cartShippingRate = new CartShippingRate;

        $cartShippingRate->carrier = $this->getCode();
        $cartShippingRate->carrier_title = $this->getConfigData('title');
        $cartShippingRate->method = $this->getMethod();
        $cartShippingRate->method_title = $this->getConfigData('title');
        $cartShippingRate->method_description = $this->getConfigData('description');
        $cartShippingRate->price = core()->convertPrice($shippingCost);
        $cartShippingRate->base_price = $shippingCost;

        return $cartShippingRate;
    }

    /**
     * Calculate total weight of cart items.
     *
     * @param  \Webkul\Checkout\Models\Cart  $cart
     * @return float
     */
    private function calculateTotalWeight($cart): float
    {
        $totalWeight = 0;

        foreach ($cart->items as $item) {
            if ($item->getTypeInstance()->isStockable()) {
                $productWeight = $item->product->weight ?? 0;
                $totalWeight += $productWeight * $item->quantity;
            }
        }

        return $totalWeight;
    }

    /**
     * Calculate shipping cost based on weight ranges.
     *
     * @param  float  $weight
     * @return float
     */
    private function calculateShippingCost(float $weight): float
    {
        $weightRanges = $this->parseWeightRanges();

        if (empty($weightRanges)) {
            return 0;
        }

        $shippingCost = 0;
        $lastRange = end($weightRanges);

        foreach ($weightRanges as $range) {
            if ($weight > $range['min'] && $weight <= $range['max']) {
                $shippingCost = $range['price'];
                break;
            }
        }

        // If weight exceeds all ranges, use the last range price
        if ($shippingCost === 0 && $weight > $lastRange['max']) {
            $shippingCost = $lastRange['price'];
        }

        return $shippingCost;
    }

    /**
     * Parse weight ranges from configuration.
     *
     * @return array
     */
    private function parseWeightRanges(): array
    {
        $weightRangesConfig = $this->getConfigData('weight_ranges');
        
        if (empty($weightRangesConfig)) {
            return [];
        }

        $ranges = [];
        $rangeItems = explode(',', $weightRangesConfig);
        
        $previousMax = 0;
        
        foreach ($rangeItems as $item) {
            $item = trim($item);
            if (strpos($item, ':') !== false) {
                list($max, $price) = explode(':', $item);
                $max = (float) trim($max);
                $price = (float) trim($price);
                
                $ranges[] = [
                    'min' => $previousMax,
                    'max' => $max,
                    'price' => $price
                ];
                
                $previousMax = $max;
            }
        }

        return $ranges;
    }
}