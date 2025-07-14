# Bagisto Weight Based Shipping Package

A comprehensive weight-based shipping package for Bagisto that calculates shipping costs based on the total weight of cart items.

## Features

- **Weight-based Calculation**: Calculates shipping cost based on total cart weight
- **Flexible Rate Configuration**: Support for multiple weight ranges with different rates
- **Admin Configuration**: Easy-to-use admin interface for configuring weight ranges
- **Multi-language Support**: Supports localization for different languages
- **Channel-based Configuration**: Different rates for different sales channels
- **Easy Installation**: Install via Composer with automated setup

## Requirements

- Bagisto 2.x
- PHP 8.2+
- Laravel 11.x
- Products must have weight attributes set

## Installation

### Method 1: Via Composer (Recommended)

```bash
composer require prasanna/bagisto-weight-shipping
```

### Method 2: Manual Installation

1. **Download the package** to your Bagisto installation:
   ```bash
   git clone https://github.com/prasanna/bagisto-weight-shipping.git packages/prasanna/bagisto-weight-shipping
   ```

2. **Add to composer.json** (in your Bagisto root):
   ```json
   {
       "autoload": {
           "psr-4": {
               "Prasanna\\WeightShipping\\": "packages/prasanna/bagisto-weight-shipping/src/"
           }
       }
   }
   ```

3. **Update autoloader**:
   ```bash
   composer dump-autoload
   ```

## Setup

After installation, run the setup command:

```bash
php artisan weight-shipping:install
```

This command will:
- Add weight-based shipping to the carriers configuration
- Add admin configuration fields
- Add language translations
- Clear cache

## Configuration

1. **Go to Admin Panel** → Configuration → Sales → Shipping Methods
2. **Find "Weight Based Shipping"** section
3. **Configure the following settings**:

### Settings

- **Title**: Display name for the shipping method
- **Description**: Description shown to customers
- **Weight Ranges**: Configure weight ranges and their corresponding rates
- **Status**: Enable/disable the shipping method

### Weight Ranges Format

Enter weight ranges in the following format:
```
1:45,5:80,10:120
```

This means:
- **0-1kg**: ₹45
- **1-5kg**: ₹80
- **5-10kg**: ₹120
- **10kg+**: ₹120 (uses last range price)

### Example Configurations

**Basic Configuration:**
```
1:45,5:80,10:120
```

**Complex Configuration:**
```
0.5:25,1:45,2:60,5:100,10:150,20:250
```

## Usage

### For Customers

1. **Add products to cart** (products must have weight set)
2. **Go to checkout**
3. **Select shipping address**
4. **Choose "Weight Based Shipping"** from available shipping methods
5. **Complete checkout**

### For Admins

1. **Set product weights** in product management
2. **Configure weight ranges** in shipping settings
3. **Enable the shipping method**
4. **Monitor and adjust rates** as needed

## How It Works

### Weight Calculation

1. System calculates total weight of all cart items
2. For each item: `item_weight × quantity`
3. Total weight = Sum of all item weights

### Rate Calculation

1. System parses the weight ranges configuration
2. Finds the appropriate range for the total cart weight
3. Returns the corresponding shipping rate

### Example

**Weight Ranges**: `1:45,5:80,10:120`

- Cart with 0.5kg total → ₹45 (0-1kg range)
- Cart with 3kg total → ₹80 (1-5kg range)
- Cart with 8kg total → ₹120 (5-10kg range)
- Cart with 15kg total → ₹120 (uses last range)

## Troubleshooting

### Common Issues

1. **Shipping method not showing**
   - Check if the method is enabled in configuration
   - Verify products have weight attributes set
   - Clear cache: `php artisan cache:clear`

2. **Incorrect rate calculations**
   - Verify weight range format is correct
   - Check product weights are set properly
   - Test with different cart combinations

3. **Configuration not appearing**
   - Run installation command: `php artisan weight-shipping:install`
   - Check if service providers are registered
   - Clear config cache: `php artisan config:clear`

### Debug Steps

1. **Check product weights**:
   ```php
   // In tinker
   $product = Product::find(1);
   dd($product->weight);
   ```

2. **Check cart total weight**:
   ```php
   // In tinker
   $cart = Cart::getCart();
   // Check individual item weights
   foreach ($cart->items as $item) {
       echo $item->product->name . ': ' . $item->product->weight . 'kg' . PHP_EOL;
   }
   ```

3. **Check configuration**:
   ```php
   // In tinker
   dd(core()->getConfigData('sales.carriers.weightbased'));
   ```

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## License

This package is open-source software licensed under the [MIT License](LICENSE).

## Support

For issues and questions:
- Check the troubleshooting section above
- Review Bagisto documentation
- Check Laravel logs for errors
- Create an issue on GitHub

## Changelog

### v1.0.0
- Initial release
- Basic weight-based shipping calculation
- Admin configuration interface
- Multi-language support
- Automated installation command

## Credits

- **Author**: Prasanna
- **Based on**: Bagisto eCommerce framework
- **Inspired by**: WooCommerce weight-based shipping plugins