<?php

namespace App\Core\Framework\Support\Data\View\Traits;

use App\Core\Framework\Support\Data\View\Traits\Shared\HasDataViewCommon;
use Illuminate\Support\Str;

trait HasDataClassView
{
    use HasDataViewCommon;

    /**
     * Ces méthodes doivent être définies dans le composant qui utilise le trait
     */
    abstract protected function getDataClass(): string;
    abstract protected function getActionClass(): string;

    public function getItemsActionClass(): string
    {
        return $this->getActionClass();
    }
    
    /**
     * On définit l'action à utiliser pour le détail.
     */
    protected function getShowAction(): string
    {
        $path =  Str::beforeLast($this->getDataClass(), 'Domain');
        $name = Str::beforeLast(Str::afterLast($path, 'Features\\'), '\\');
        $action = str_replace($this->getDataClass(), $path.'Actions\Find'.$name.'Action', $this->getDataClass());
        return str_replace('Get', 'Find', $action);
    }
}