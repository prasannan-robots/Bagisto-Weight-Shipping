# Bagisto Weight Based Shipping - Composer Package

## Package Overview

**Package Name**: `prasanna/bagisto-weight-shipping`  
**Namespace**: `Prasanna\WeightShipping`  
**Version**: 1.0.0  
**Type**: bagisto-package  

## Installation Methods

### Method 1: Composer Install (Recommended)

```bash
# Navigate to your Bagisto installation
cd /path/to/your/bagisto

# Install the package
composer require prasanna/bagisto-weight-shipping

# Run installation setup
php artisan weight-shipping:install

# Clear cache
php artisan cache:clear
php artisan config:clear
```

### Method 2: Local Development

```bash
# Copy the package to your Bagisto installation
cp -r prasanna-weight-shipping /path/to/your/bagisto/packages/prasanna/bagisto-weight-shipping

# Add to composer.json
{
    "autoload": {
        "psr-4": {
            "Prasanna\\WeightShipping\\": "packages/prasanna/bagisto-weight-shipping/src/"
        }
    }
}

# Update autoloader
composer dump-autoload

# Run installation
php artisan weight-shipping:install
```

## Package Structure

```
prasanna-weight-shipping/
├── composer.json                           # Package definition
├── LICENSE                                 # MIT License
├── README.md                              # Main documentation
├── INSTALLATION.md                        # Installation guide
├── .gitignore                             # Git ignore file
└── src/
    ├── Carriers/
    │   └── WeightBased.php                # Main shipping carrier class
    ├── Config/
    │   ├── carriers.php                   # Carrier configuration
    │   └── system.php                     # Admin system configuration
    ├── Console/
    │   └── InstallCommand.php             # Automated installation command
    ├── Providers/
    │   ├── WeightShippingServiceProvider.php  # Main service provider
    │   └── ModuleServiceProvider.php      # Bagisto module service provider
    └── Resources/
        └── manifest.php                   # Package manifest
```

## Key Features

✅ **Composer Installation**: Install via `composer require prasanna/bagisto-weight-shipping`  
✅ **Automated Setup**: One-command installation with `php artisan weight-shipping:install`  
✅ **Weight-based Calculation**: Shipping costs based on total cart weight  
✅ **Flexible Configuration**: Multiple weight ranges with different rates  
✅ **Admin Interface**: Easy configuration through Bagisto admin panel  
✅ **Multi-language Support**: Localized admin interface  
✅ **Channel Support**: Different rates for different sales channels  
✅ **Proper Namespacing**: Clean `Prasanna\WeightShipping` namespace  

## Installation Command Features

The `php artisan weight-shipping:install` command automatically:

1. **Adds to carriers config** - Registers the shipping method
2. **Adds to system config** - Creates admin configuration fields
3. **Adds language translations** - Adds English translations
4. **Clears cache** - Clears Laravel cache automatically
5. **Provides feedback** - Shows success/failure messages
6. **Gives next steps** - Tells user what to do next

## Configuration

### Weight Ranges Format

Use this format: `max_weight:price,max_weight:price`

**Examples:**

```
1:45,5:80,10:120
```
- 0-1kg = ₹45
- 1-5kg = ₹80  
- 5-10kg = ₹120
- 10kg+ = ₹120

```
0.5:25,2:60,5:100,10:150
```
- 0-0.5kg = ₹25
- 0.5-2kg = ₹60
- 2-5kg = ₹100
- 5-10kg = ₹150
- 10kg+ = ₹150

### Admin Configuration

Navigate to: **Admin Panel → Configuration → Sales → Shipping Methods**

Configure:
- **Title**: Display name for customers
- **Description**: Shipping method description
- **Weight Ranges**: Weight ranges in specified format
- **Status**: Enable/disable the shipping method

## Publishing to Packagist

To publish this package to Packagist:

1. **Create GitHub repository**:
   ```bash
   git init
   git add .
   git commit -m "Initial commit"
   git remote add origin https://github.com/prasanna/bagisto-weight-shipping.git
   git push -u origin main
   ```

2. **Submit to Packagist**:
   - Go to https://packagist.org/packages/submit
   - Submit your GitHub repository URL
   - Package will be available as `prasanna/bagisto-weight-shipping`

3. **Tag releases**:
   ```bash
   git tag 1.0.0
   git push origin 1.0.0
   ```

## Usage After Installation

### For Store Owners

1. **Configure shipping rates** in admin panel
2. **Set product weights** for all products
3. **Test with different cart combinations**
4. **Monitor and adjust rates** as needed

### For Customers

1. **Add products to cart** (products must have weight)
2. **Go to checkout**
3. **Select shipping address**
4. **Choose "Weight Based Shipping"**
5. **See calculated shipping cost**

## Support and Maintenance

### Troubleshooting

- **Package not found**: Check autoloader with `composer dump-autoload`
- **Shipping not showing**: Verify products have weights set
- **Wrong calculations**: Check weight ranges format
- **Config not showing**: Run `php artisan weight-shipping:install`

### Updates

To update the package:

```bash
composer update prasanna/bagisto-weight-shipping
php artisan cache:clear
php artisan config:clear
```

## Advantages of This Approach

1. **Professional Distribution**: Proper Composer package
2. **Easy Installation**: One-command setup
3. **Automated Configuration**: No manual file editing
4. **Version Control**: Proper semantic versioning
5. **Documentation**: Complete installation and usage guides
6. **Maintainability**: Clean code structure and namespacing
7. **Compatibility**: Follows Bagisto package conventions

## Next Steps

1. **Test the package** thoroughly in different Bagisto installations
2. **Create GitHub repository** for version control
3. **Submit to Packagist** for public distribution
4. **Add unit tests** for better reliability
5. **Consider additional features** like:
   - Dimensional weight calculation
   - Free shipping thresholds
   - Multiple shipping zones
   - Shipping discounts

This package is now ready for distribution and can be easily installed by any Bagisto store owner using standard Composer commands!