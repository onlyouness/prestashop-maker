<?php

declare(strict_types=1);

namespace Prestashop\PsPos\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'cat_lang')]
class CatLang
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Cat::class, inversedBy: 'translations')]
    #[ORM\JoinColumn(name: 'id_cat', referencedColumnName: 'id_cat', nullable: false, onDelete: 'CASCADE')]
    private $cat;

    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    private $id_lang;

    #[ORM\Column(type: 'string')]
    private $title;



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


}