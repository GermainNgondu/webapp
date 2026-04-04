<?php

namespace App\Core\Admin\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Insight extends Model
{
    use SoftDeletes;
    protected $fillable = ['user_id','uuid', 'name','slug','description', 'is_primary', 'base_filters'];
    protected $casts = ['base_filters' => 'array','is_primary'=> 'boolean'];

    public function widgets(): HasMany
    {
        return $this->hasMany(InsightWidget::class)->orderBy('sort_order');
    }
}