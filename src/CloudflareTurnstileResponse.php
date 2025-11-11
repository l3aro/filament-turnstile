<?php

namespace l3aro\CloudflareTurnstile;

use Illuminate\Contracts\Support\Arrayable;

class CloudflareTurnstileResponse implements Arrayable
{
    public function __construct(
        public bool $success,
        public ?array $errorCodes,
    ) {}

    public static function make(bool $success, ?array $errorCodes): static
    {
        return new static($success, $errorCodes);
    }

    public function toArray(): array
    {
        return [
            'success' => $this->success,
            'errorCodes' => $this->errorCodes,
        ];
    }
}
