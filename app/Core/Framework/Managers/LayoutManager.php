<?php

namespace App\Core\Framework\Managers;

use App\Core\Framework\Data\BrandData;
use App\Core\Framework\Data\NavigationItemData;
use Illuminate\Support\Collection;

class LayoutManager
{
    protected BrandData $brand;
    protected Collection $primaryNav;
    protected Collection $secondaryNav;
    protected Collection $userNav;
    protected ?string $searchAlias = null;
    public function __construct()
    {
        $this->brand = new BrandData(name: config('app.name'));
        $this->primaryNav = collect();
        $this->secondaryNav = collect();
        $this->userNav = collect();
    }

    // --- Setters for Features ---
    public function setBrand(BrandData $brand): void { $this->brand = $brand; }
    
    public function addPrimary(NavigationItemData $item): void { $this->primaryNav->push($item); }
    
    public function addSecondary(NavigationItemData $item): void { $this->secondaryNav->push($item); }
    
    public function addUserMenu(NavigationItemData $item): void { $this->userNav->push($item); }
    
    public function setGlobalSearch(string $livewireAlias): void { $this->searchAlias = $livewireAlias; }

    // --- Getters for Blade Components ---
    public function getBrand(): BrandData {
        return $this->brand;
    }
    public function getPrimary(): Collection {
        return $this->primaryNav->sortBy('order');
    }
    public function getSecondary(): Collection {
        return $this->secondaryNav->sortBy('order');
    }
    public function getUserMenu(): Collection {
        return $this->userNav->sortBy('order');
    }
    public function getSearchAlias(): ?string {
        return $this->searchAlias;
    }

    public function getCurrentLayout(): string
    {
        return config('core.layout');
    }
}