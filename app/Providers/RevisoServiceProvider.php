<?php 

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use InvalidArgumentException;
use Webleit\RevisoApi\Reviso;

class RevisoServiceProvider extends ServiceProvider
{

    /**
    * Register bindings in the container.
    *
    * @return void
    */
    public function register()
    {
        $this->app->singleton(Reviso::class, function ($app) {
            
            // Load the configuration and instantiate the API
            $config = $app['config']['services.reviso'];
            if (!$appSecretToken = array_get($config, 'secret')) {
                throw new InvalidArgumentException('Reviso App Secret Key not configured');
            }
            
            if (!$agreementGrantToken = array_get($config, 'grant')) {
                throw new InvalidArgumentException('Reviso Agreement Grant Token not configured');
            }

            return new Reviso($appSecretToken, $agreementGrantToken);
        });
        
    }
    
}
