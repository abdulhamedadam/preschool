<?php

namespace App\Filament\Clusters;
use App\Filament\Clusters\Settings\ExpenseItemsResource;
use App\Filament\Clusters\Settings\PaymentMethodsResource;
use Filament\Clusters\Cluster;

class Settings extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?int $navigationSort = 3;

    // public static function getNavigationGroup(): string
    // {
    //     return __('common.general');
    // }
    public static function getNavigationLabel(): string
    {
        return __('common.settings');
    }

    public static function getPluralLabel(): ?string
    {
        return __('common.settings');
    }

    public static function getLabel(): string
    {
        return __('common.settings');
    }

    public static function getModelLabel(): string
    {
        return __('common.settings');
    }

    public static function getPluralModelLabel(): string
    {
        return __('common.settings');
    }
    public static function getBreadCrumb(): string
    {
        return __('common.settings');
    }





}
