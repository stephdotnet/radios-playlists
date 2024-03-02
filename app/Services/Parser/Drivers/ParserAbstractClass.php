<?php

namespace App\Services\Parser\Drivers;

abstract class ParserAbstractClass implements ParserInterface
{
    protected string $radio;

    public function setRadio(string $radio): self
    {
        $this->radio = $radio;

        return $this;
    }
}
