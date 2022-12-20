<?php

namespace App\Providers;

use App\Services\Contracts\DocumentHandlerContract;
use App\Services\GoogleCloud\DocumentAiService;
use Illuminate\Support\ServiceProvider;

class DocumentHandlerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(DocumentHandlerContract::class, DocumentAiService::class);
    }
}
