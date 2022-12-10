<?php

declare(strict_types=1);

namespace Onliner\Materialized;

interface Command
{
    /**
     * @return string
     */
    public function __toString(): string;
}
