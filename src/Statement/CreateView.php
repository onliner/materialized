<?php

declare(strict_types=1);

namespace Onliner\Materialized\Statement;

use Onliner\Materialized\Command;

class CreateView implements Command
{
    private const
        TYPE_VIEW = 'VIEW',
        TYPE_TEMPORARY = 'TEMPORARY VIEW',
        TYPE_MATERIALIZED = 'MATERIALIZED VIEW';

    private const
        MODE_NONE = 0,
        MODE_STRICT = 1,
        MODE_REPLACE = 2;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $query;

    /**
     * @var int
     */
    private $mode = self::MODE_NONE;

    /**
     * @param string $name
     * @param string $type
     * @param string $query
     */
    private function __construct(string $name, string $type, string $query)
    {
        $this->name = $name;
        $this->type = $type;
        $this->query = $query;
    }

    /**
     * @param string $name
     * @param string $query
     * @param bool $materialized
     *
     * @return self
     */
    public static function from(string $name, string $query, bool $materialized = false): self
    {
        return new self($name, $materialized ? self::TYPE_MATERIALIZED : self::TYPE_VIEW, $query);
    }

    /**
     * @param string $name
     * @param string $query
     *
     * @return self
     */
    public static function temp(string $name, string $query): self
    {
        return new self($name, self::TYPE_TEMPORARY, $query);
    }

    /**
     * @return self
     */
    public function strict(): self
    {
        $this->mode = self::MODE_STRICT;

        return $this;
    }

    /**
     * @return self
     */
    public function replace(): self
    {
        $this->mode = self::MODE_REPLACE;

        return $this;
    }

    /**
     * @link https://materialize.com/docs/sql/create-view
     * @link https://materialize.com/docs/sql/create-materialized-view
     *
     * @return string
     */
    public function __toString(): string
    {
        $query = 'CREATE ';

        switch ($this->mode) {
            case self::MODE_STRICT:
                $query .= $this->type;

                break;
            case self::MODE_REPLACE:
                $query .= 'OR REPLACE ' . $this->type;

                break;
            default:
                $query .= $this->type . ' IF NOT EXISTS';
        }

        $query .= ' %s AS %s';

        return sprintf($query, $this->name, $this->query);
    }
}
