<?php

declare(strict_types=1);

namespace {{namespace}}\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: '{{table_name}}_lang')]
class {{entity_name}}Lang
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: {{entity_name}}::class, inversedBy: 'translations')]
    #[ORM\JoinColumn(name: 'id_{{entity_name_lower}}', referencedColumnName: 'id_{{entity_name_lower}}', nullable: false, onDelete: 'CASCADE')]
    private ${{entity_name_lower}};

    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    private $id_lang;

{{properties}}

    public function getLangId(): int
    {
        return $this->id_lang;
    }

    public function setLangId(int $id_lang): self
    {
        $this->id_lang = $id_lang;
        return $this;
    }

{{methods}}
}