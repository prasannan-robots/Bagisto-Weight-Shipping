# Installation Guide - Bagisto Weight Based Shipping

## Quick Installation

### Method 1: Composer Install (Recommended)

1. **Navigate to your Bagisto root directory**:
   ```bash
   cd /path/to/your/bagisto
   ```

2. **Install the package**:
   ```bash
   composer require prasanna/bagisto-weight-shipping
   ```

3. **Run the installation command**:
   ```bash
   php artisan weight-shipping:install
   ```

4. **Clear cache**:
   ```bash
   php artisan cache:clear
   php artisan config:clear
   ```

5. **Configure in Admin Panel**:
   - Go to Admin → Configuration → Sales → Shipping Methods
   - Find "Weight Based Shipping"
   - Configure settings and enable

### Method 2: Manual Installation

1. **Download/Clone the package**:
   ```bash
   # Option A: Download from GitHub
   git clone https://github.com/prasanna/bagisto-weight-shipping.git packages/prasanna/bagisto-weight-shipping
   
   # Option B: Create directory and copy files
   mkdir -p packages/prasanna/bagisto-weight-shipping
   # Copy all files from the package
   ```

2. **Update composer.json** (in Bagisto root):
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

4. **Run installation command**:
   ```bash
   php artisan weight-shipping:install
   ```

## Configuration

### 1. Admin Panel Configuration

Navigate to: **Admin Panel → Configuration → Sales → Shipping Methods**

Find **"Weight Based Shipping"** section and configure:

- **Title**: `Weight Based Shipping`
- **Description**: `Shipping cost calculated based on total weight`
- **Weight Ranges**: `1:45,5:80,10:120` (example)
- **Status**: `Enable`

### 2. Weight Ranges Format

Use this format: `max_weight:price,max_weight:price`

**Examples:**

**Basic Setup:**
```
1:45,5:80,10:120
```
- 0-1kg = ₹45
- 1-5kg = ₹80
- 5-10kg = ₹120
- 10kg+ = ₹120

**Advanced Setup:**
```
0.5:25,1:45,2:60,5:100,10:150,20:250
```
- 0-0.5kg = ₹25
- 0.5-1kg = ₹45
- 1-2kg = ₹60
- 2-5kg = ₹100
- 5-10kg = ₹150
- 10-20kg = ₹250
- 20kg+ = ₹250

### 3. Product Weight Setup

Ensure all products have weight attributes set:

1. Go to **Catalog → Products**
2. Edit each product
3. Set **Weight** field (in kg)
4. Save product

## Testing

1. **Create test products** with different weights:
   - Light product: 0.5kg
   - Medium product: 2kg
   - Heavy product: 8kg

2. **Test cart combinations**:
   - Add light product → Should show first range price
   - Add medium product → Should show second range price
   - Add heavy product → Should show third range price

3. **Verify calculations**:
   - Check total cart weight
   - Verify correct shipping rate is applied
   - Test with different quantities

## Troubleshooting

### Issue: Package not found after installation

**Solution:**
```bash
composer dump-autoload
php artisan cache:clear
php artisan config:clear
```

### Issue: Shipping method not showing in checkout

**Possible causes:**
1. Method not enabled in configuration
2. Products don't have weight set
3. Cart is empty
4. Cache not cleared

**Solution:**
1. Check admin configuration
2. Verify product weights
3. Clear cache
4. Check Laravel logs

### Issue: Wrong shipping rates

**Possible causes:**
1. Incorrect weight range format
2. Product weights not set correctly
3. Configuration not saved properly

**Solution:**
1. Verify weight ranges format: `max_weight:price,max_weight:price`
2. Check product weights in admin
3. Save configuration again

### Issue: Installation command fails

**Solution:**
1. Check file permissions
2. Verify Bagisto version compatibility
3. Check if files exist:
   ```bash
   ls -la packages/prasanna/bagisto-weight-shipping/
   ```

## Manual Configuration (if installation command fails)

### 1. Add to carriers.php

Edit: `packages/Webkul/Shipping/src/Config/carriers.php`

Add before the closing `];`:
```php
'weightbased' => [
    'code'         => 'weightbased',
    'title'        => 'Weight Based Shipping',
    'description'  => 'Shipping cost calculation based on total weight',
    'active'       => true,
    'weight_ranges' => '1:45,5:80,10:120',
    'class'        => 'Prasanna\WeightShipping\Carriers\WeightBased',
],
```

### 2. Add to system.php

Edit: `packages/Webkul/Admin/src/Config/system.php`

Add after the flatrate shipping configuration:
```php
], [
    'key'    => 'sales.carriers.weightbased',
    'name'   => 'Weight Based Shipping',
    'info'   => 'Weight based shipping calculates shipping costs based on total weight.',
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
            'info'          => 'Enter weight ranges in format: max_weight:price,max_weight:price (e.g., 1:45,5:80,10:120)',
        ], [
            'name'          => 'active',
            'title'         => 'Status',
            'type'          => 'boolean',
            'channel_based' => true,
            'locale_based'  => false,
        ],
    ],
], [
```

### 3. Add language translations

Edit: `packages/Webkul/Admin/src/Resources/lang/en/app.php`

Add after flat-rate-shipping section:
```php
'weight-based-shipping' => [
    'description'          => 'Description',
    'page-title'           => 'Weight Based Shipping',
    'status'               => 'Status',
    'title'                => 'Title',
    'title-info'           => 'Weight based shipping calculates shipping costs based on total weight.',
    'weight-ranges'        => 'Weight Ranges',
    'weight-ranges-info'   => 'Enter weight ranges in format: max_weight:price (e.g., 1:45,5:80,10:120)',
],
```

## Verification

After installation, verify everything works:

1. **Check admin panel**: Weight Based Shipping should appear in shipping methods
2. **Check frontend**: Add products to cart and verify shipping option appears
3. **Check calculations**: Test different weight combinations
4. **Check logs**: No errors in Laravel logs

## Support

If you encounter any issues:

1. Check this troubleshooting guide
2. Verify Bagisto version compatibility
3. Check file permissions
4. Review Laravel logs
5. Create an issue on GitHub

## Next Steps

After successful installation:

1. Configure weight ranges for your needs
2. Set weights for all products
3. Test thoroughly with different scenarios
4. Monitor performance and adjust as needed
5. Consider creating backup of configuration