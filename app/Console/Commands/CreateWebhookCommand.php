<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class CreateWebhookCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trello-webhooks:create {idModel}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create trello webhook';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $authHeader = 'OAuth oauth_consumer_key="';
        $authHeader .= config('services.trello.key');
        $authHeader .= '",oauth_token="'.config('services.trello.token').'"';

        $response = Http::withHeaders([
            'Authorization' => $authHeader,
        ])->post('https://api.trello.com/1/webhooks', [
            'idModel' => $this->argument('idModel'),
            'description' => 'webhook description',
            'callbackURL' => config('app.url').'/webhooks/trello',
        ]);

        if ($response->status() !== Response::HTTP_OK) {
            $this->error('Webhook not created!');

            return Command::FAILURE;
        }

        $this->info('Webhook created!');

        return Command::SUCCESS;
    }
}
