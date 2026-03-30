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
     * Label
     */
    public static function label(): string 
    {
        return (string) Str::of(static::class)->afterLast('\\')->replace('Resource', '')->plural();
    }

    /**
     * Icon
     */
    public static function icon(): string {
        return 'cube';
    }

    /**
     * Route
     */
    public static function route(): string 
    {
        return (string) Str::of(static::class)->afterLast('\\')->replace('Resource', '')->lower()->kebab();
    }

    /**
     * Default Sort
     */
    public static function defaultSort(): string {
        return '-created_at';
    }

    /**
     * Autorisations (Policies)
     */
    public static function can(string $action, $user = null): bool
    {
        return ($user ?? auth()->user())->can($action, static::model());
    }

    protected static array $resolutionCache = [];

        public static function resolveMetadata(): array
        {
            $class = static::class;
            
            if (!isset(self::$resolutionCache[$class])) {
                $name = (string) Str::of($class)->afterLast('\\')->replace('Resource', '');
                $namespace = (string) Str::beforeLast($class, '\\');
                
                self::$resolutionCache[$class] = [
                    'name'      => $name,
                    'namespace' => $namespace,
                    'actions'   => [
                        'index' => "{$namespace}\\Actions\\Get{$name}Action",
                        'show'  => "{$namespace}\\Actions\\Find{$name}Action",
                        'form'  => "{$namespace}\\Actions\\Upsert{$name}Action",
                    ]
                ];
            }

            return self::$resolutionCache[$class];
        }

    public static function getIndexAction(): string { return static::resolveMetadata()['actions']['index']; }
    public static function getShowAction(): string  { return static::resolveMetadata()['actions']['show']; }
    public static function getFormAction(): string  { return static::resolveMetadata()['actions']['form']; }
    public static function resourceName(): string   { return static::resolveMetadata()['name']; }    
}