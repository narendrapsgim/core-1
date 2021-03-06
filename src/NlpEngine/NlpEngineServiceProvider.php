<?php

namespace OpenDialogAi\NlpEngine;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use OpenDialogAi\NlpEngine\MicrosoftRepository\MsClient;
use OpenDialogAi\NlpEngine\Service\NlpService;
use OpenDialogAi\NlpEngine\Service\NlpServiceInterface;

class NlpEngineServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes(
            [
                __DIR__.'/config/opendialog-nlpengine-custom.php' => config_path('opendialog/nlp_engine.php'),
            ],
            'opendialog-config'
        );
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/opendialog-nlpengine.php', 'opendialog.nlp_engine');

        $this->app->singleton(MsClient::class, function () {
            $client = new Client(
                [
                    'base_uri' => config('opendialog.nlp_engine.ms_api_url'),
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Ocp-Apim-Subscription-Key' => config('opendialog.nlp_engine.ms_api_key'),
                    ],
                ]
            );

            return new MsClient($client);
        });

        $this->app->singleton(NlpServiceInterface::class, function () {
            $nlpService = new NlpService();
            $nlpService->registerAvailableProviders(config('opendialog.nlp_engine.available_nlp_providers'));

            // Register custom NLP providers if they are available
            if (is_array(config('opendialog.nlp_engine.custom_nlp_providers'))) {
                $nlpService->registerAvailableProviders(config('opendialog.nlp_engine.custom_nlp_providers'));
            }

            return $nlpService;
        });
    }
}
