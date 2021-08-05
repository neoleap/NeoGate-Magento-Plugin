<?php
namespace Alrajhi\iPay\Model;


class Encryption implements \Magento\Framework\Option\ArrayInterface
{
    const ENCRYPTION_AESIV    = 'aesiv';
    const ENCRYPTION_TDES     = 'tdes';

    /**
     * Possible encryption types
     * 
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => self::ENCRYPTION_AESIV,
                'label' => 'AES-IV',
            ],
            [
                'value' => self::ENCRYPTION_TDES,
                'label' => 'TDES'
            ]
        ];
    }
}
