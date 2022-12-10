<?php

declare(strict_types=1);

namespace Onliner\Materialized\Format;

use Onliner\Materialized\Format;

class Bytes implements Format
{
    /**
     * @return string
     */
    public function __toString(): string
    {
        return 'FORMAT BYTES';
    }
}
