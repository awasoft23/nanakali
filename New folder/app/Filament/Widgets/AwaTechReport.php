<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class AwaTechReport extends Widget
{
    protected int|string|array $columnSpan = "full";
    protected static string $view = 'filament.widgets.awa-tech-report';
}