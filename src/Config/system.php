<?php

return [
    [
        'key'    => 'sales.carriers.weightbased',
        'name'   => 'Weight Based Shipping',
        'info'   => 'Weight based shipping calculates shipping costs based on the total weight of items in the cart. Different weight ranges can have different shipping rates.',
        'sort'   => 3,
        'fields' => [
            [
                'name'          => 'title',
                'title'         => 'Title',
                'type'          => 'text',
                'depends'       => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'description',
                'title'         => 'Description',
                'type'          => 'textarea',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'weight_ranges',
                'title'         => 'Weight Ranges',
                'type'          => 'textarea',
                'depends'       => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => true,
                'locale_based'  => false,
                'info'          => 'Enter weight ranges in format: max_weight:price,max_weight:price (e.g., 1:45,5:80,10:120). Each range represents: "up to X kg costs Y"',
            ], [
                'name'          => 'active',
                'title'         => 'Status',
                'type'          => 'boolean',
                'channel_based' => true,
                'locale_based'  => false,
            ],
        ],
    ]
];