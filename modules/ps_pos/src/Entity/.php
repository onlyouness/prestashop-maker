<?php

declare(strict_types=1);

namespace Prestashop\PsPos\Entity;

use Doctrine\ORM\Mapping as ORM;
use Prestashop\PsPos\Repository\Repository;

#[ORM\Entity(repositoryClass: Repository::class)]
#[ORM\Table(name: 'ps_pos_')]
class 
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'integer')]
    private $active;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getActive(): ?int
    {
        return $this->active;
    }

    public function setActive(int $value): self
    {
        $this->active = $value;
        return $this;
    }


}