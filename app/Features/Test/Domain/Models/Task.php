<?php

namespace App\Features\Test\Domain\Models;

use App\Features\Test\Domain\Database\Factories\TaskFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model {
    use HasUuids, HasFactory;
    protected $fillable = [
        'title', 'description', 'status', 'priority', 'user_id', 
        'started_at', 'due_at'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'due_at' => 'datetime',
    ];
    /**
     * Crée une nouvelle instance de la factory pour le modèle.
     */
    protected static function newFactory()
    {
        return TaskFactory::new();
    }
}