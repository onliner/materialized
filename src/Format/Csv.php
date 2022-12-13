<?php

declare(strict_types=1);

namespace Onliner\Materialized\Format;

use Onliner\Materialized\Format;

class Csv implements Format
{
    /**
     * @var int
     */
    private $columns;

    /**
     * @var string
     */
    private $delimiter;

    /**
     * @param int $columns
     * @param string $delimiter
     */
    public function __construct(int $columns, string $delimiter)
    {
        $this->columns = $columns;
        $this->delimiter = $delimiter;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return sprintf('FORMAT CSV WITH %d COLUMNS DELIMITED BY %s', $this->columns, $this->delimiter);
    }
}
