<?php

declare(strict_types=1);

namespace Onliner\Materialized\Statement;

class DropIndex extends AbstractDrop
{
    /**
     * @return string
     */
    protected function type(): string
    {
        return 'INDEX';
    }
}
