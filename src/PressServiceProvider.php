<?php

declare(strict_types=1);

namespace Moox\Press;

use Illuminate\Support\Facades\Auth;
use Moox\Press\Commands\InstallCommand;
use Moox\Press\Providers\WordPressUserProvider;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class PressServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('press')
            ->hasConfigFile()
            ->hasViews()
            ->hasTranslations()
            ->hasRoute('web')
            ->hasMigration('create_press_table')
            ->hasCommand(InstallCommand::class);
    }

    public function boot()
    {
        parent::boot();

        Auth::provider('wpuser-provider', function ($app, array $config) {
            return new WordPressUserProvider($app['hash'], $config['model']);
        });
    }
}
