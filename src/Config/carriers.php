<?php

return [
    'weightbased' => [
        'code'         => 'weightbased',
        'title'        => 'Weight Based Shipping',
        'description'  => 'Shipping cost calculation based on total weight',
        'active'       => true,
        'weight_ranges' => '1:45,5:80,10:120',
        'class'        => 'Prasanna\WeightShipping\Carriers\WeightBased',
    ],
];