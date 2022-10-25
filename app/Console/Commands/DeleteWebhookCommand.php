<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class DeleteWebhookCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trello-webhooks:delete {idModel}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'delete trello webhook';

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
        ])->delete('https://api.trello.com/1/webhooks/'.$this->argument('idModel'));

        if ($response->status() !== Response::HTTP_OK) {
            $this->error('Webhook not deleted!');

            return Command::FAILURE;
        }

        $this->info('Webhook deleted!');

        return Command::SUCCESS;
    }
}
