<?php namespace App\Providers;

use App\Port\Export\Services\CsvExportFileCreator\CsvExportFileCreator;
use App\Port\Export\Services\CsvExportFileCreator\ICsvExportFileCreatorService;
use App\Port\Export\Services\CsvExportDataProcessorService\CsvExportDataProcessorServiceImpl;
use App\Port\Export\Services\CsvExportDataProcessorService\ICsvExportDataProcessorService;
use App\Port\Export\Services\CsvExportService\CsvExportService;
use App\Port\Export\Services\CsvExportService\ICsvExportService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrapThree();

        $this->app->bind('request_id', static function () {
            return request()->attributes->get('request_id');
        });
    }

    /**
     * Register any application services.
     *
     * This service provider is a great spot to register your various container
     * bindings with the application.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ICsvExportDataProcessorService::class, CsvExportDataProcessorServiceImpl::class);
        $this->app->bind(ICsvExportService::class, CsvExportService::class);
    }
}
