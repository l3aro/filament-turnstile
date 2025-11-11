<?php

namespace l3aro\CloudflareTurnstile\Commands;

use Illuminate\Console\Command;

class CloudflareTurnstileCommand extends Command
{
    public $signature = 'cloudflare-turnstile';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
