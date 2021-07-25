<?php

namespace HafasClient\Models;

/**
 * @package HafasClient\Models
 * @todo    make readonly
 */
class Remark {

    public ?string $type;
    public ?string $code;
    public ?int    $prio;
    public ?string $message;

    public function __construct(
        string $type = null,
        string $code = null,
        int $prio = null,
        string $message = null
    ) {
        $this->type    = $type;
        $this->code    = $code;
        $this->prio    = $prio;
        $this->message = $message;
    }

    /**
     * @return string
     * @todo
     */
    public function __toString(): string {
        return json_encode($this);
    }
}