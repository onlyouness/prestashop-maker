<?php

declare(strict_types=1);

namespace Prestashop\PsToto\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'ps_toto__lang')]
class Lang
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity:::class, inversedBy: 'translations')]
    #[ORM\JoinColumn(name: 'id_', referencedColumnName: 'id_', nullable: false, onDelete: 'CASCADE')]
    private $;

    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    private $id_lang;

    #[ORM\Column(type: 'string')]
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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $value): self
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