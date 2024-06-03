<?php

namespace App\Enums;

enum Status: string
{
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case COMPLETED = 'completed';
    case CANCELED = 'canceled';
}
