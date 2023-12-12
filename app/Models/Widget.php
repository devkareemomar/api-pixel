<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Widget extends Model
{
    use HasFactory;

    protected $fillable = ['section_id', 'widget_category_id', 'name', 'thumbnail', 'payload', 'order', 'size_percentage'];

    protected function payload(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => json_encode($value),
        );
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function category()
    {
        return $this->belongsTo(WidgetCategory::class);
    }}
