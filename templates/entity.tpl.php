<?php

declare(strict_types=1);

namespace {{namespace}}\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use {{namespace}}\Repository\{{entity_name}}Repository;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="{{namespace}}\Repository\{{entity_name}}Repository")
 * @ORM\HasLifecycleCallbacks
 */
class {{entity_name}}
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private ?int $id = null;

{{properties}}

{{translation_logic}}

    public function getId(): ?int
    {
        return $this->id;
    }

{{methods}}
}