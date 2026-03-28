<?php

namespace App\Core\Framework\Support\Resource\Contracts;

use Illuminate\Support\Str;

abstract class BaseResource
{
    abstract public static function model(): string;

    // Le Quatuor de Data (Contextes séparés)
    abstract public static function listData(): string;    // Pour Table/Grid
    abstract public static function detailData(): string;  // Pour Show/ItemView
    abstract public static function formData(): string;    // Pour Formulaires
    abstract public static function insightData(): string; // Pour Analytics

    /**
     * Configuration de la Navigation
     */
    public static function label(): string 
    {
        return (string) Str::of(static::class)->afterLast('\\')->replace('Resource', '')->plural();
    }

    public static function icon(): string {
        return 'cube';
    }

    public static function route(): string 
    {
        return (string) Str::of(static::class)->afterLast('\\')->replace('Resource', '')->lower()->kebab();
    }

    /**
     * Section 1: DataView (Index / Liste)
     */
    public static function getIndexAction(): string 
    {
        return static::moduleNamespace() . '\\Actions\\Get' . static::resourceName() . 'Action';
    }

    public static function defaultSort(): string {
        return '-created_at';
    }

    /**
     * Section 2: Show (Détail / Item View)
     */
    public static function getShowAction(): string 
    {
        return static::moduleNamespace() . '\Actions\Find' . static::resourceName() . 'Action';
    }

    /**
     * Section 3: DataForm (Create / Edit)
     */
    public static function getFormAction(): string 
    {
        return static::moduleNamespace() . '\Actions\Upsert' . static::resourceName() . 'Action';
    }

    // Permet de définir des règles de validation ou un schéma spécifique au formulaire
    public static function formSchema(): array {
        return [];
    }

    /**
     * Section 4: Data Insights (Analytics / Dashboard)
     */
    public static function insights(): array
    {
        // Ici on définira plus tard des widgets (ex: StatWidget, ChartWidget)
        return [];
    }

    /**
     * Autorisations (Policies)
     */
    public static function can(string $action, $user = null): bool
    {
        // Délègue la vérification aux Policies de Laravel
        return ($user ?? auth()->user())->can($action, static::model());
    }
    /**
     * Génère le namespace de base du module (ex: App\Features\Media)
     */
    public static function moduleNamespace(): string
    {
        return (string) Str::beforeLast(static::class, '\\');
    }
    /**
     * Helper interne pour récupérer le nom pur de la ressource (ex: Media)
     */
    protected static function resourceName(): string
    {
       return (string) Str::of(static::class)->afterLast('\\')->replace('Resource', '');
    }
}