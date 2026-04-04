<?php
namespace App\Core\Admin\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class InsightWidget extends Model
{
    use SoftDeletes;
    protected $fillable = ['uuid','insight_id', 'type', 'action_class', 'settings', 'sort_order'];
    protected $casts = ['settings' => 'array'];

    public function insight(): BelongsTo
    {
        return $this->belongsTo(Insight::class);
    }
}