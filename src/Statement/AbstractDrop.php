<?php

namespace Onliner\Materialized\Statement;

use Onliner\Materialized\Command;

abstract class AbstractDrop implements Command
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
     * @var bool
     */
    private $cascade = false;

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
     * @return self
     */
    public function cascade(): self
    {
        $this->cascade = true;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $query = 'DROP %s';

        if (!$this->strict) {
            $query .= ' IF EXISTS';
        }

        $query .= ' %s';
        $query .= $this->cascade ? ' CASCADE' : ' RESTRICT';

        return sprintf($query, $this->type(), $this->name);
    }

    /**
     * @return string
     */
    abstract protected function type(): string;
}
