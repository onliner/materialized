<?php

declare(strict_types=1);

namespace Onliner\Materialized\Statement;

use Onliner\Materialized\Command;

class DropSink implements Command
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var bool
     */
    private $strict = false;

    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return self
     */
    public function strict(): self
    {
        $this->strict = true;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $query = 'DROP SINK';

        if (!$this->strict) {
            $query .= ' IF EXISTS';
        }

        $query .= ' %s';

        return sprintf($query, $this->name);
    }
}
