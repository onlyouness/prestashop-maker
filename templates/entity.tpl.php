<?php

declare(strict_types=1);

namespace {{namespace}}\Entity;

use Doctrine\ORM\Mapping as ORM;
use {{namespace}}\Repository\{{entity_name}}Repository;

#[ORM\Entity(repositoryClass: {{entity_name}}Repository::class)]
#[ORM\Table(name: '{{table_name}}')]
class {{entity_name}}
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

{{properties}}

    public function getId(): ?int
    {
        return $this->id;
    }

{{methods}}
}