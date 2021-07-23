<?php

namespace Nordcomputer\Stockfilter\Model\Config\Source;

class Options extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    /**
     * Get all options
     *
     * @return array
     */
    public function getAllOptions()
    {
        $this->_options = [
                ['label' => __('No'), 'value'=> '' ],
                ['label' => __('Yes'), 'value'=> 1]
        ];
        return $this->_options;
    }
}
