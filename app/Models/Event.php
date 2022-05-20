<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    const ID = 'id';

    const MSG = 'msg';

    const TITLE = 'title';

    const TIME = 'time';

    const USER_ID = 'user_id';

    protected $table = 'events';

    protected $primaryKey = self::ID;

    protected $guarded = [self::ID];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
