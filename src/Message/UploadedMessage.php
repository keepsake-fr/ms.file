<?php

declare(strict_types=1);

namespace Storage\Message;

use Symfony\Component\Validator\Constraints as Assert;

class UploadedMessage
{
    /**
     * @Assert\NotBlank()
     */
    public string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }
}
