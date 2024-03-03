<?php

namespace App\Services\Parser;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

abstract class ParserAbstractClass implements ParserInterface
{
    protected string $radio;

    public function setRadio(string $radio): self
    {
        $this->radio = $radio;

        return $this;
    }

    protected function getClient(): PendingRequest
    {
        return Http::withOptions([
            'verify' => false,
        ]);
    }
}
