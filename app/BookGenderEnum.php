<?php

namespace App;

enum BookGenderEnum: string
{
    case DRAMATIC ='dramatic';
    case FANTASTIC = 'fantastic';
    case HORROR = 'horror';
    case ROMANCE = 'romance';
    case TRAGEDY = 'tragedy';
    case NONE = 'none';
}
