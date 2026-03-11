<?php

declare(strict_types=1);

namespace Prestashop\PsPos\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'ps_pos__lang')]
class Lang
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: ::class, inversedBy: 'translations')]
    #[ORM\JoinColumn(name: 'id_{{entity_name_lower}}', referencedColumnName: 'id_{{entity_name_lower}}', nullable: false, onDelete: 'CASCADE')]
    private ${{entity_name_lower}};

    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    private $id_lang;

    #[ORM\Column(type: 'integer')]
    private $title;

    #[ORM\Column(type: 'text')]
    private $description;



    public function getLangId(): int
    {
        return $this->id_lang;
    }

    public function setLangId(int $id_lang): self
    {
        $this->id_lang = $id_lang;
        return $this;
    }

    public function getTitle(): ?int
    {
        return $this->title;
    }

    public function setTitle(int $value): self
    {
        $this->title = $value;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $value): self
    {
        $this->description = $value;
        return $this;
    }


}