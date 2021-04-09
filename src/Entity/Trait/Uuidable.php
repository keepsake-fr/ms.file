<?php

declare(strict_types=1);

namespace Storage\Entity\Trait;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait Uuidable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=36)
     * @Assert\Uuid()
     */
    public string $id;

    public function __construct()
    {
        $this->id = (string) uuid_create(UUID_TYPE_RANDOM);
    }
}
