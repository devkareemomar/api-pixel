<?php

namespace App\Models;

use App\Traits\Filter;
use App\Traits\Sort;
use App\Traits\Visitable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Donation extends Model
{
    use HasFactory, SoftDeletes;
    use Sort;
    use Filter;

    protected $fillable = [
        'tag_id',
        'category_id',
        'project_id',
        'project_code',
        'transaction_id',
        'reference',
        'payment_method',
        'amount',
        'paid_amount',
        'result',
        'user_id',
        'donor_name',
        'donor_phone',
        'is_zakat',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
