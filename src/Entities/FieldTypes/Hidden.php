<?php

namespace twa\cmsv2\Entities\FieldTypes;


class Hidden extends FieldType
{

    public function component()
    {
        return "elements.hidden";
    }

}
