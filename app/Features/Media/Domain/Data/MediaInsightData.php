<?php

namespace App\Features\Media\Domain\Data;

use App\Core\Framework\Support\Data\Insight\Attributes\{Metric, Chart,Trend,Card,Activity};
use App\Core\Framework\Support\Data\Insight\Enums\ChartTypeInsightEnum;
use App\Features\Media\Actions\{GetMediaInsightsAction,GetRecentActivityAction};

class MediaInsightData
{

    #[Card(
        label: 'Total Médias',
        icon: 'images',
        description: 'Nombre total de fichiers stockés',
        colSpan:1
    )]
    public mixed $header;
    #[Metric(
        label: 'Total Médias',
        icon: 'images',
        description: 'Nombre total de fichiers stockés',
        action: GetMediaInsightsAction::class
    )]
    public int $totalCount;

    #[Metric(
        label: 'Espace Disque',
        format: 'bytes',
        action: GetMediaInsightsAction::class
    )]
    public float $diskUsage;
    #[Trend(
        label: 'Uploads par mois',
        action: GetMediaInsightsAction::class
    )]
    public array $uploadTrend;
    #[Chart(
        type: ChartTypeInsightEnum::BAR,
        label: 'Uploads par mois',
        action: GetMediaInsightsAction::class
    )]
    public array $history;
    #[Activity(
        label: 'Activités Récentes', 
        action: GetRecentActivityAction::class,
        limit: 6,
        colSpan: 6 // Occupe la moitié de la largeur
    )]
    public array $recentLogs;

}