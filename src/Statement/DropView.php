<?php

declare(strict_types=1);

namespace Onliner\Materialized\Statement;

class DropView extends AbstractDrop
{
    /**
     * @var bool
     */
    private $materialized;

    /**
     * @param string $name
     * @param bool $materialized
     */
    public function __construct(string $name, bool $materialized)
    {
        parent::__construct($name);

        $this->materialized = $materialized;
    }

    /**
     * @return string
     */
    protected function type(): string
    {
        return $this->materialized ? 'MATERIALIZED VIEW' : 'VIEW';
    }
}
