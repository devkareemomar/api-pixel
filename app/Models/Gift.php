<?php

namespace App\Models;

use App\Traits\Filter;
use App\Traits\Sort;
use App\Traits\Visitable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gift extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Visitable;
    use Filter;
    use Sort;

    protected $fillable = [
        'user_id',
        'project_id',
        'sender_name',
        'sender_email',
        'recipient_name',
        'recipient_email',
        'price',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
