<?php

namespace l3aro\FilamentTurnstile;

use Illuminate\Contracts\Support\Arrayable;

class FilamentTurnstileResponse implements Arrayable
{
    public function __construct(
        public bool $success,
        public ?array $errorCodes,
    ) {}

    public static function make(bool $success, ?array $errorCodes): self
    {
        return new self($success, $errorCodes);
    }

    public function toArray(): array
    {
        return [
            'success' => $this->success,
            'errorCodes' => $this->errorCodes,
        ];
    }
}
