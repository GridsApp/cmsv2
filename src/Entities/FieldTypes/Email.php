<?php

namespace twa\cmsv2\Entities\FieldTypes;


class Email extends FieldType
{

    public function component()
    {
        return "elements.email";
    }

}
