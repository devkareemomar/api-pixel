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
        'order_project_id',
        'template'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }


    public function order_project()
    {
        return $this->belongsTo(OrderProject::class,'order_project_id');
    }

}
