<?php

declare(strict_types=1);

namespace {{namespace}}\Entity;

use PrestaShopBundle\Entity\Lang;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class {{entity_name}}Lang
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="{{namespace}}\Entity\{{entity_name}}", inversedBy="{{entity_name_lower}}Langs")
     * @ORM\JoinColumn(name="id_{{entity_name_lower}}", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private ${{entity_name_lower}};

    /**
     * @var Lang
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="PrestaShopBundle\Entity\Lang")
     * @ORM\JoinColumn(name="id_lang", referencedColumnName="id_lang", nullable=false, onDelete="CASCADE")
     */
    private $lang;

{{properties}}

    public function getLang(): Lang
    {
        return $this->lang;
    }

    public function setLang(Lang $lang): self
    {
        $this->lang = $lang;
        return $this;
    }

{{methods}}

    public function get{{entity_name}}(): {{entity_name}}
    {
        return $this->{{entity_name_lower}};
    }

    public function set{{entity_name}}({{entity_name}} ${{entity_name_lower}}): self
    {
        $this->{{entity_name_lower}} = ${{entity_name_lower}};
        return $this;
    }
}