<?php

namespace App\Filament\Resources\RecieptsResource\Widgets;

use App\Models\Reciepts;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PartnersOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('$', number_format(Reciepts::where('partnersName', 'صبر محمد مولود')->where('priceType', '$')->where('type', 0)->sum('amount') - Reciepts::where('partnersName', 'صبر محمد مولود')->where('priceType', '$')->where('type', 1)->sum('amount'), 2))->description('صبر محمد مولود'),
            Stat::make('$', number_format(Reciepts::where('partnersName', 'ئەیاد عبدولشریف')->where('priceType', '$')->where('type', 0)->sum('amount') - Reciepts::where('partnersName', 'ئەیاد عبدولشریف')->where('priceType', '$')->where('type', 1)->sum('amount'), 2))->description('ئەیاد عبدولشریف'),
            Stat::make('$', number_format(Reciepts::where('partnersName', 'ئەشقی ئەحمەد ئیبراهیم')->where('priceType', '$')->where('type', 0)->sum('amount') - Reciepts::where('partnersName', 'ئەشقی ئەحمەد ئیبراهیم')->where('priceType', '$')->where('type', 1)->sum('amount'), 2))->description('ئەشقی ئەحمەد ئیبراهیم'),
            Stat::make('د.ع', number_format(Reciepts::where('partnersName', 'صبر محمد مولود')->where('priceType', '!=', '$')->where('type', 0)->sum('amount') - Reciepts::where('partnersName', 'صبر محمد مولود')->where('priceType', '!=', '$')->where('type', 1)->sum('amount'), 0))->description('صبر محمد مولود'),
            Stat::make('د.ع', number_format(Reciepts::where('partnersName', 'ئەیاد عبدولشریف')->where('priceType', '!=', '$')->where('type', 0)->sum('amount') - Reciepts::where('partnersName', 'ئەیاد عبدولشریف')->where('priceType', '!=', '$')->where('type', 1)->sum('amount'), 0))->description('ئەیاد عبدولشریف'),
            Stat::make('د.ع', number_format(Reciepts::where('partnersName', 'ئەشقی ئەحمەد ئیبراهیم')->where('priceType', '!=', '$')->where('type', 0)->sum('amount') - Reciepts::where('partnersName', 'ئەشقی ئەحمەد ئیبراهیم')->where('priceType', '!=', '$')->where('type', 1)->sum('amount'), 0))->description('ئەشقی ئەحمەد ئیبراهیم'),

            Stat::make(
                '$',
                number_format(
                    Reciepts::where('partnersName', 'صبر محمد مولود')->where('priceType', '$')->where('type', 0)->sum('amount')
                    - Reciepts::where('partnersName', 'صبر محمد مولود')->where('priceType', '$')->where('type', 1)->sum('amount')
                    + Reciepts::where('partnersName', 'ئەیاد عبدولشریف')->where('priceType', '$')->where('type', 0)->sum('amount')
                    - Reciepts::where('partnersName', 'ئەیاد عبدولشریف')->where('priceType', '$')->where('type', 1)->sum('amount')
                    + Reciepts::where('partnersName', 'ئەشقی ئەحمەد ئیبراهیم')->where('priceType', '$')->where('type', 0)->sum('amount')
                    - Reciepts::where('partnersName', 'ئەشقی ئەحمەد ئیبراهیم')->where('priceType', '$')->where('type', 1)->sum('amount')
                    ,
                    2
                )
            )->description('کۆی گشتی'),
            Stat::make(
                'د.ع',
                number_format(
                    Reciepts::where('partnersName', 'ئەشقی ئەحمەد ئیبراهیم')->where('priceType', '!=', '$')->where('type', 0)->sum('amount')
                    - Reciepts::where('partnersName', 'ئەشقی ئەحمەد ئیبراهیم')->where('priceType', '!=', '$')->where('type', 1)->sum('amount')
                    + Reciepts::where('partnersName', 'صبر محمد مولود')->where('priceType', '!=', '$')->where('type', 0)->sum('amount')
                    - Reciepts::where('partnersName', 'صبر محمد مولود')->where('priceType', '!=', '$')->where('type', 1)->sum('amount')
                    + Reciepts::where('partnersName', 'ئەیاد عبدولشریف')->where('priceType', '!=', '$')->where('type', 0)->sum('amount')
                    - Reciepts::where('partnersName', 'ئەیاد عبدولشریف')->where('priceType', '!=', '$')->where('type', 1)->sum('amount')
                    ,
                    0
                )
            )->description('کۆی گشتی'),


        ];
    }
}