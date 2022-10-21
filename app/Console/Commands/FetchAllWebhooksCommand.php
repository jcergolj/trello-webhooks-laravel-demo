<?php

namespace App\Console\Commands;

use Illuminate\Http\Response;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FetchAllWebhooksCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trello-webhooks:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'fetch all trello webhooks';

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
        ])->get('https://api.trello.com/1/tokens/'.config('services.trello.token').'/webhooks');

        if ($response->status() !== Response::HTTP_OK) {
            $this->error('Failed to fetch webhooks.');

            return Command::FAILURE;
        }

        $webhooksRows = [];
        foreach ($response->json() as $webhook) {
            unset($webhook['description'], $webhook['firstConsecutiveFailDate'], $webhook['callbackURL']);
            $webhooksRows[] = $webhook;
        }

        $this->table(
            [
                'id',
                'idModel',
                'active',
                'consecutiveFailures',
            ],
            $webhooksRows,
        );

        $this->line('Total number of webhooks: '.count($response->json()));

        return Command::SUCCESS;
    }
}
