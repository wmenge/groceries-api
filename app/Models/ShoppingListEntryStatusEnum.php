<?php

namespace App\Models;

enum ShoppingListEntryStatusEnum: string
{
	case Open = 'open';
	case Closed = 'closed';
    case Rejected = 'rejected';
}