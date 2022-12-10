<?php

declare(strict_types=1);

namespace Onliner\Materialized\Statement;

class DropConnection extends AbstractDrop
{
    /**
     * @return string
     */
    protected function type(): string
    {
        return 'CONNECTION';
    }
}
