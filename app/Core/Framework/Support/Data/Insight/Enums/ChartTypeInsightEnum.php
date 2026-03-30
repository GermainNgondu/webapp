<?php

namespace App\Core\Framework\Support\Data\Insight\Enums;

enum ChartTypeInsightEnum: string
{
    case LINE = 'line';
    case BAR = 'bar';
    case PIE = 'pie';
    case DOUGHNUT = 'doughnut';
    case SCATTER = 'scatter';
    case BUBBLE = 'bubble';
    case RADAR = "radar";
    case POLAR = "polarArea";
    case AREA = "area";
    case MIXED = "mixed";
    case HORIZONTAL_BAR = "horizontalBar";
    case STACKED_BAR = "stackedBar";
    case STACKED_HORIZONTAL_BAR = "stackedHorizontalBar";
    case STACKED_AREA = "stackedArea";
    case STACKED_HORIZONTAL_AREA = "stackedHorizontalArea";
    case STACKED_POLAR = "stackedPolar";
    case STACKED_RADAR = "stackedRadar";
    case STACKED_SCATTER = "stackedScatter";
    case STACKED_BUBBLE = "stackedBubble";
    case STACKED_MIXED = "stackedMixed";
    case STACKED_HORIZONTAL_MIXED = "stackedHorizontalMixed";
    case STACKED_HORIZONTAL_AREA_MIXED = "stackedHorizontalAreaMixed";
    case STACKED_HORIZONTAL_POLAR_MIXED = "stackedHorizontalPolarMixed";
    case STACKED_HORIZONTAL_RADAR_MIXED = "stackedHorizontalRadarMixed";
    case STACKED_HORIZONTAL_SCATTER_MIXED = "stackedHorizontalScatterMixed";
    case STACKED_HORIZONTAL_BUBBLE_MIXED = "stackedHorizontalBubbleMixed";
    case STACKED_HORIZONTAL_AREA_POLAR_MIXED = "stackedHorizontalAreaPolarMixed";
    case STACKED_HORIZONTAL_AREA_RADAR_MIXED = "stackedHorizontalAreaRadarMixed";
    case STACKED_HORIZONTAL_AREA_SCATTER_MIXED = "stackedHorizontalAreaScatterMixed";
    case STACKED_HORIZONTAL_AREA_BUBBLE_MIXED = "stackedHorizontalAreaBubbleMixed";
    case STACKED_HORIZONTAL_POLAR_RADAR_MIXED = "stackedHorizontalPolarRadarMixed";
    case STACKED_HORIZONTAL_POLAR_SCATTER_MIXED = "stackedHorizontalPolarScatterMixed";
    case STACKED_HORIZONTAL_POLAR_BUBBLE_MIXED = "stackedHorizontalPolarBubbleMixed";
    case STACKED_HORIZONTAL_RADAR_SCATTER_MIXED = "stackedHorizontalRadarScatterMixed";
    case STACKED_HORIZONTAL_RADAR_BUBBLE_MIXED = "stackedHorizontalRadarBubbleMixed";
    case STACKED_HORIZONTAL_SCATTER_BUBBLE_MIXED = "stackedHorizontalScatterBubbleMixed";
    case STACKED_HORIZONTAL_AREA_POLAR_RADAR_MIXED = "stackedHorizontalAreaPolarRadarMixed";
    case STACKED_HORIZONTAL_AREA_POLAR_SCATTER_MIXED = "stackedHorizontalAreaPolarScatterMixed";
    case STACKED_HORIZONTAL_AREA_POLAR_BUBBLE_MIXED = "stackedHorizontalAreaPolarBubbleMixed";
    case STACKED_HORIZONTAL_AREA_RADAR_SCATTER_MIXED = "stackedHorizontalAreaRadarScatterMixed";
    case STACKED_HORIZONTAL_AREA_RADAR_BUBBLE_MIXED = "stackedHorizontalAreaRadarBubbleMixed";
    case STACKED_HORIZONTAL_AREA_SCATTER_BUBBLE_MIXED = "stackedHorizontalAreaScatterBubbleMixed";
    case STACKED_HORIZONTAL_POLAR_RADAR_SCATTER_MIXED = "stackedHorizontalPolarRadarScatterMixed";
    case STACKED_HORIZONTAL_POLAR_RADAR_BUBBLE_MIXED = "stackedHorizontalPolarRadarBubbleMixed";
    case STACKED_HORIZONTAL_POLAR_SCATTER_BUBBLE_MIXED = "stackedHorizontalPolarScatterBubbleMixed";
    case STACKED_HORIZONTAL_RADAR_SCATTER_BUBBLE_MIXED = "stackedHorizontalRadarScatterBubbleMixed";
    case STACKED_HORIZONTAL_AREA_POLAR_RADAR_SCATTER_MIXED = "stackedHorizontalAreaPolarRadarScatterMixed";
    case STACKED_HORIZONTAL_AREA_POLAR_RADAR_BUBBLE_MIXED = "stackedHorizontalAreaPolarRadarBubbleMixed";
    case STACKED_HORIZONTAL_AREA_POLAR_SCATTER_BUBBLE_MIXED = "stackedHorizontalAreaPolarScatterBubbleMixed";
    case STACKED_HORIZONTAL_AREA_RADAR_SCATTER_BUBBLE_MIXED = "stackedHorizontalAreaRadarScatterBubbleMixed";
    case STACKED_HORIZONTAL_AREA_POLAR_RADAR_SCATTER_BUBBLE_MIXED = "stackedHorizontalAreaPolarRadarScatterBubbleMixed";
}