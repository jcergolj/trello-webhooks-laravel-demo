<?php

namespace App\DataObjects;

use Spatie\LaravelData\Data;

class WebhookData extends Data
{
    public function __construct(
        public string $id,
        public string $description,
        public string $idModel,
        public string $callbackURL,
        public bool $active,
        public int $consecutiveFailures,
        public string|null $firstConsecutiveFailDate,
    ) {
    }
}
