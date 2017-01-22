<?php
namespace Affilicious\Product\Model;

use Affilicious\Common\Exception\Invalid_Type_Exception;
use Affilicious\Common\Model\Simple_Value_Trait;

if (!defined('ABSPATH')) {
    exit('Not allowed to access pages directly.');
}

class Tag
{
    use Simple_Value_Trait {
        Simple_Value_Trait::__construct as private set_value;
    }

    /**
     * @inheritdoc
     * @since 0.7.1
     * @throws Invalid_Type_Exception
     */
    public function __construct($value)
    {
        $value = strval($value);

        if (!is_string($value)) {
            throw new Invalid_Type_Exception($value, 'int');
        }

        $this->set_value($value);
    }
}