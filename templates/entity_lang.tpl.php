<?php

declare(strict_types=1);

namespace {{namespace}}\Entity;

use Doctrine\ORM\Mapping as ORM;
use Lang;

#[ORM\Entity]
#[ORM\Table(name: '{{table_name}}_lang')]
class {{entity_name}}Lang
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: {{entity_name}}::class, inversedBy: 'translations')]
    #[ORM\JoinColumn(name: 'id_{{entity_name_lower}}', referencedColumnName: 'id_{{entity_name_lower}}', nullable: false, onDelete: 'CASCADE')]
    private ${{entity_name_lower}};

        /**
     * @var Lang
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="PrestaShopBundle\Entity\Lang")
     * @ORM\JoinColumn(name="id_lang", referencedColumnName="id_lang", nullable=false, onDelete="CASCADE")
     */
    private $lang;

{{properties}}

    public function getLang(): int
    {
        return $this->lang;
    }

    public function setLang(int $lang): self
    {
        $this->lang = $lang;
        return $this;
    }

{{methods}}
}