<?php

namespace Prasanna\WeightShipping\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'weight-shipping:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Weight Based Shipping package';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Installing Weight Based Shipping Package...');

        // Step 1: Add to carriers config
        $this->addToCarriersConfig();

        // Step 2: Add to system config
        $this->addToSystemConfig();

        // Step 3: Add language translations
        $this->addLanguageTranslations();

        // Step 4: Clear cache
        $this->call('cache:clear');
        $this->call('config:clear');

        $this->info('✅ Weight Based Shipping Package installed successfully!');
        $this->info('');
        $this->info('Next steps:');
        $this->info('1. Go to Admin Panel → Configuration → Sales → Shipping Methods');
        $this->info('2. Configure "Weight Based Shipping" settings');
        $this->info('3. Set weight ranges (e.g., 1:45,5:80,10:120)');
        $this->info('4. Enable the shipping method');
        $this->info('5. Save configuration');

        return 0;
    }

    /**
     * Add weight-based shipping to carriers config.
     */
    private function addToCarriersConfig()
    {
        $carriersFile = base_path('packages/Webkul/Shipping/src/Config/carriers.php');
        
        if (! File::exists($carriersFile)) {
            $this->error('Carriers config file not found!');
            return;
        }

        $carriersContent = File::get($carriersFile);
        
        $weightBasedConfig = "
    'weightbased' => [
        'code'         => 'weightbased',
        'title'        => 'Weight Based Shipping',
        'description'  => 'Shipping cost calculation based on total weight',
        'active'       => true,
        'weight_ranges' => '1:45,5:80,10:120',
        'class'        => 'Prasanna\\WeightShipping\\Carriers\\WeightBased',
    ],";

        if (! str_contains($carriersContent, 'weightbased')) {
            $carriersContent = str_replace(
                '];',
                $weightBasedConfig . "\n];",
                $carriersContent
            );
            
            File::put($carriersFile, $carriersContent);
            $this->info('✅ Added to carriers config');
        } else {
            $this->info('⚠️  Already exists in carriers config');
        }
    }

    /**
     * Add weight-based shipping to system config.
     */
    private function addToSystemConfig()
    {
        $systemFile = base_path('packages/Webkul/Admin/src/Config/system.php');
        
        if (! File::exists($systemFile)) {
            $this->error('System config file not found!');
            return;
        }

        $systemContent = File::get($systemFile);
        
        $weightBasedSystemConfig = "    ], [
        'key'    => 'sales.carriers.weightbased',
        'name'   => 'Weight Based Shipping',
        'info'   => 'Weight based shipping calculates shipping costs based on the total weight of items in the cart.',
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
        'key'  => 'sales.payment_methods',";

        if (! str_contains($systemContent, 'sales.carriers.weightbased')) {
            $systemContent = str_replace(
                "    ], [\n        'key'  => 'sales.payment_methods',",
                $weightBasedSystemConfig,
                $systemContent
            );
            
            File::put($systemFile, $systemContent);
            $this->info('✅ Added to system config');
        } else {
            $this->info('⚠️  Already exists in system config');
        }
    }

    /**
     * Add language translations.
     */
    private function addLanguageTranslations()
    {
        $langFile = base_path('packages/Webkul/Admin/src/Resources/lang/en/app.php');
        
        if (! File::exists($langFile)) {
            $this->error('Language file not found!');
            return;
        }

        $langContent = File::get($langFile);
        
        $weightBasedLang = "
                    'weight-based-shipping' => [
                        'description'          => 'Description',
                        'page-title'           => 'Weight Based Shipping',
                        'status'               => 'Status',
                        'title'                => 'Title',
                        'title-info'           => 'Weight based shipping calculates shipping costs based on the total weight of items in the cart. Different weight ranges can have different shipping rates.',
                        'weight-ranges'        => 'Weight Ranges',
                        'weight-ranges-info'   => 'Enter weight ranges in format: max_weight:price,max_weight:price (e.g., 1:45,5:80,10:120). Each range represents: \"up to X kg costs Y\"',
                    ],";

        if (! str_contains($langContent, 'weight-based-shipping')) {
            $langContent = str_replace(
                "                    'flat-rate-shipping' => [",
                "                    'weight-based-shipping' => [
                        'description'          => 'Description',
                        'page-title'           => 'Weight Based Shipping',
                        'status'               => 'Status',
                        'title'                => 'Title',
                        'title-info'           => 'Weight based shipping calculates shipping costs based on the total weight of items in the cart. Different weight ranges can have different shipping rates.',
                        'weight-ranges'        => 'Weight Ranges',
                        'weight-ranges-info'   => 'Enter weight ranges in format: max_weight:price,max_weight:price (e.g., 1:45,5:80,10:120). Each range represents: \"up to X kg costs Y\"',
                    ],

                    'flat-rate-shipping' => [",
                $langContent
            );
            
            File::put($langFile, $langContent);
            $this->info('✅ Added language translations');
        } else {
            $this->info('⚠️  Language translations already exist');
        }
    }
}