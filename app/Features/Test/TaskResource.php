<?php

namespace App\Features\Test;


use App\Core\Framework\Support\Resource\Contracts\BaseResource;
use App\Features\Test\Domain\Data\TaskDetailData;
use App\Features\Test\Domain\Data\TaskFormData;
use App\Features\Test\Domain\Data\TaskInsightData;
use App\Features\Test\Domain\Data\TaskListData;
use App\Features\Test\Domain\Models\Task;

class TaskResource extends BaseResource {
    public static function model(): string { return Task::class; }
    public static function listData(): string { return TaskListData::class; }
    public static function detailData(): string { return TaskDetailData::class; }
    public static function formData(): string { return TaskFormData::class; }
    public static function insightData(): string { return TaskInsightData::class; }
    public static function icon(): string { return 'clipboard-document-list'; }
}