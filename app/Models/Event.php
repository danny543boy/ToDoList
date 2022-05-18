<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    const ID = 'id';

    const MSG = 'msg';

    const TITLE = 'title';

    const TIME = 'time';

    protected $table = 'events';

    protected $primaryKey = self::ID;

    protected $guarded = [self::ID];

}
