<?php

declare(strict_types=1);

namespace Onliner\Materialized;

class Envelope
{
    public const
        NONE = 'none',
        UPSERT = 'upsert',
        DEBEZIUM = 'debezium';

    /**
     * @var string
     */
    private $type;

    /**
     * @var Format
     */
    private $format;

    /**
     * @param string $type
     * @param Format $format
     */
    private function __construct(string $type, Format $format)
    {
        $this->type = $type;
        $this->format = $format;
    }

    /**
     * @param Format $format
     *
     * @return self
     */
    public static function none(Format $format): self
    {
        return new self(self::NONE, $format);
    }

    /**
     * @param Format $format
     *
     * @return self
     */
    public static function upsert(Format $format): self
    {
        return new self(self::UPSERT, $format);
    }

    /**
     * @param Format $format
     *
     * @return self
     */
    public static function debezium(Format $format): self
    {
        return new self(self::DEBEZIUM, $format);
    }

    /**
     * @return self
     */
    public static function default(): self
    {
        return self::none(new Format\Json());
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return sprintf('%s ENVELOPE %s', $this->format, strtoupper($this->type));
    }
}
