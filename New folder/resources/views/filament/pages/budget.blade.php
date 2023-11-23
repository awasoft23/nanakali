<x-filament-panels::page>
    <style>
        .fi-header,
        .hidens {
            display: none !important;
            visibility: hidden !important;
        }

        @media print {

            .fi-sidebar,
            .fi-topbar,
            .fi-headBtn {
                display: none !important;
                visibility: hidden !important;
            }

            .hidens {
                display: block !important;
                visibility: visible !important;
            }

            .fi-body {
                background: white !important;
                margin: 0;
                padding: 0;
            }

        }
    </style>
    @php

        $reqD = 0;
        $reqI = 0;
        $reqD1 = 0;
        $reqI1 = 0;
    @endphp


    <header class="fi-headBtn flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="fi-ac  gap-3 flex flex-wrap items-center justify-start shrink-0 sm:mt-7">
            <button style="--c-400:var(--primary-400);--c-500:var(--primary-500);--c-600:var(--primary-600);"
                class="fi-btn relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus:ring-2 rounded-lg fi-color-custom fi-btn-color-primary fi-size-md fi-btn-size-md gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-custom-600 text-white hover:bg-custom-500 dark:bg-custom-500 dark:hover:bg-custom-400 focus:ring-custom-500/50 dark:focus:ring-custom-400/50 fi-ac-btn-action"
                type="button" onclick="window.print()">
                <span class="fi-btn-label">
                    چاپکردن
                </span>
            </button>
        </div>
    </header>
    <div class="w-full hidens">
        <div class=" flex justify-between items-center align-middle border-b-2"
            style="background: #2c3862;border-bottom:6px solid #475569">
            <div class="px-4 text-white font-bold   " style="font-size: 30pt">
                نانــەکەلــی مــەڕمــەڕ
                <div style="font-size: 16pt; font-weight: bold; margin:10px 0 ">
                    بۆ مەڕمەڕی دەستکرد
                </div>
            </div>
            <div class="bg-white">
                <img src="{{ asset('logo.png') }}" style="width: 250px" alt="">
            </div>
        </div>

    </div>

    <h1
        class="fi-header-heading text-2xl font-bold text-center tracking-center text-gray-950 dark:text-white sm:text-3xl">
        پوختەی کۆمپانیا <span class="text-base">({{ now()->format('Y-m-d') }})</span>
    </h1>
    <hr class="my-4">
    <h1 class="text-3xl font-bold text-center">سەروەت و داواکراوەکان</h1>
    <div style="display: flex">
        <div style="width:50%" class="">
            <div style="background:#172554;color:white !important ;font-weight:bold"
                class="py-2 text-center border px-2">
                سەروەت
            </div>
            <div style="display: flex;">
                <div style="background: #475569;color:white;width:50%" class="py-2 px-2 border">
                    قەرزی کڕیارەکان
                </div>
                <div style="width:50%" class="py-2 border px-2">
                    @php
                        $reqD += $data['CustomerDebts'] + $data['recivedMoney'] + $data['productsBalance'];
                        $reqI += 0;
                    @endphp
                    {{ number_format($data['CustomerDebts'], 2) }} $
                </div>
            </div>

            <div style="display: flex;">
                <div style="background: #475569;color:white;width:50%" class="py-2 px-2 border">
                    کۆی گشتی پسولە واصلکراوەکان
                </div>
                <div style="width:50%" class="py-2 border px-2">
                    {{ number_format($data['recivedMoney'], 2) }} $
                </div>
            </div>
            <div style="display: flex;">
                <div style="background: #475569;color:white;width:50%" class="py-2 px-2 border">
                    کۆی گشتی کاڵاکان
                </div>
                <div style="width:50%" class="py-2 border px-2">
                    {{ number_format($data['productsBalance'], 2) }} $
                </div>
            </div>
            @foreach ($data['partnersBalance'] as $partner)
                @if ($partner->balance < 0)
                    @if ($partner->priceType == '$')
                        @php
                            $reqD -= $partner->balance;
                            $reqI += 0;
                        @endphp
                        <div style="display: flex;">
                            <div style="background: #475569;color:white;width:50%" class="py-2 px-2 border">
                                قەرزی {{ $partner->partnersName }}
                            </div>
                            <div style="width:50%" class="py-2 border px-2">
                                {{ number_format(-1 * $partner->balance, 2) }} $
                            </div>
                        </div>
                    @else
                        @php
                            $reqD += 0;
                            $reqI -= $partner->balance;
                        @endphp
                        <div style="display: flex;">
                            <div style="background: #475569;color:white;font-weight:bold:bold;width:50%"
                                class="py-2 px-2 border">
                                قەرزی {{ $partner->partnersName }}
                            </div>
                            <div style="width:50%" class="py-2 border px-2">
                                {{ number_format(-1 * $partner->balance, 0) }} د.ع
                            </div>
                        </div>
                    @endif
                @endif
            @endforeach


        </div>
        <div style="width:50%" class="">
            <div style="background:#172554;color:white !important ;font-weight:bold"
                class="py-2 text-center border px-2">
                داواکراوەکان
            </div>
            <div style="display: flex;">
                <div style="background: #475569;color:white;width:50%" class="py-2 px-2 border">
                    قەرزی فرۆشیارەکان
                </div>
                <div style="width:50%" class="py-2 border px-2">
                    @php
                        $reqD1 += $data['VendorDebts'] + $data['sendedMoney'];
                        $reqI1 += 0;
                    @endphp
                    {{ number_format($data['VendorDebts'], 2) }} $
                </div>
            </div>
            <div style="display: flex;">
                <div style="background: #475569;color:white;width:50%" class="py-2 px-2 border">
                    کۆی گشتی پسولە واصلکراوەکان
                </div>
                <div style="width:50%" class="py-2 border px-2">
                    {{ number_format($data['sendedMoney'], 2) }} $
                </div>
            </div>
            @foreach ($data['partnersBalance'] as $partner)
                @if ($partner->balance > 0)
                    @if ($partner->priceType == '$')
                        @php
                            $reqD1 += $partner->balance;
                            $reqI1 += 0;
                        @endphp
                        <div style="display: flex;">
                            <div style="background: #475569;color:white;width:50%" class="py-2 px-2 border">
                                داهاتی {{ $partner->partnersName }}
                            </div>
                            <div style="width:50%" class="py-2 border px-2">
                                {{ number_format($partner->balance, 2) }} $
                            </div>
                        </div>
                    @else
                        @php
                            $reqD1 += 0;
                            $reqI1 += $partner->balance;
                        @endphp
                        <div style="display: flex;">
                            <div style="background: #475569;color:white;font-weight:bold:bold;width:50%"
                                class="py-2 px-2 border">
                                داهاتی {{ $partner->partnersName }}
                            </div>
                            <div style="width:50%" class="py-2 border px-2">
                                {{ number_format($partner->balance, 0) }} د.ع
                            </div>
                        </div>
                    @endif
                @endif
            @endforeach
            @foreach ($data['expenses'] as $expenses)
                <div style="display: flex;">
                    <div style="background: #475569;color:white;width:50%" class="py-2 px-2 border">
                        خەرجییەکان
                    </div>
                    <div style="width:50%" class="py-2 border px-2">
                        @if ($expenses->priceType == 0)
                            @php
                                $reqD1 += $expenses->total;
                                $reqI1 += 0;

                            @endphp
                            {{ number_format($expenses->total, 2) }} $
                        @else
                            @php
                                $reqD1 += 0;
                                $reqI1 += $expenses->total;
                            @endphp
                            {{ number_format($expenses->total, 0) }} د.ع
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div style="display: flex">
        <div style="width:50%" class="">
            <div style="display: flex;">
                <div style="background:#172554;color:white !important ;font-weight:bold;width:50%"
                    class="py-2 px-2 border">
                    کۆی گشتی دۆلاری ئەمریکی
                </div>
                <div style="width:50%" class="py-2 border px-2">
                    {{ number_format($reqD, 2) }} $ </div>
            </div>
            <div style="display: flex;">
                <div style="background:#172554;color:white !important ;font-weight:bold;width:50%"
                    class="py-2 px-2 border">
                    کۆی گشتی دیناری عێراقی
                </div>
                <div style="width:50%" class="py-2 border px-2">
                    {{ number_format($reqI, 0) }} د.ع</div>
            </div>
            <div style="display: flex;">
                <div style="background: #475569;color:white;width:50%" class="py-2 border px-2">
                    نرخی دۆلار
                </div>
                <div style="width:50%" class="py-2 border px-2">
                    {{ number_format($data['dollarPrice'], 2) }} د.ع
                </div>
            </div>
            <div style="display: flex;">
                <div style="background:#172554;color:white !important ;font-weight:bold;width:50%"
                    class="py-2 px-2 border">
                    کۆی گشتی بەدۆلار
                </div>
                <div style="width:50%" class="py-2 border px-2">
                    {{ number_format($reqD + $reqI / $data['dollarPrice'], 2) }} $</div>
            </div>
            <div style="display: flex;">
                <div style="background:#172554;color:white !important ;font-weight:bold;width:100%"
                    class="py-2 px-2 border text-left">
                    داهات
                </div>
            </div>
        </div>
        <div style="width:50%" class="">
            <div style="display: flex;">
                <div style="background:#172554;color:white !important ;font-weight:bold;width:50%"
                    class="py-2 px-2 border">
                    کۆی گشتی دۆلاری ئەمریکی
                </div>
                <div style="width:50%" class="py-2 border px-2">
                    {{ number_format($reqD1, 2) }} $ </div>
            </div>
            <div style="display: flex;">
                <div style="background:#172554;color:white !important ;font-weight:bold;width:50%"
                    class="py-2 px-2 border">
                    کۆی گشتی دیناری عێراقی
                </div>
                <div style="width:50%" class="py-2 border px-2">
                    {{ number_format($reqI1, 0) }} د.ع</div>
            </div>
            <div style="display: flex;">
                <div style="background: #475569;color:white;width:50%" class="py-2 border px-2">
                    نرخی دۆلار
                </div>
                <div style="width:50%" class="py-2 border px-2">
                    {{ number_format($data['dollarPrice'], 2) }} د.ع
                </div>
            </div>
            <div style="display: flex;">
                <div style="background:#172554;color:white !important ;font-weight:bold;width:50%"
                    class="py-2 px-2 border">
                    کۆی گشتی بەدۆلار
                </div>
                <div style="width:50%" class="py-2 border px-2">
                    {{ number_format($reqD1 + $reqI1 / $data['dollarPrice'], 2) }} $</div>
            </div>
            <div style="display: flex;">
                <div style="background:#172554;color:white !important ;font-weight:bold;width:100%"
                    class="py-2 px-2 border text-right">
                    {{ number_format($reqD + $reqI / $data['dollarPrice'] - ($reqD1 + $reqI1 / $data['dollarPrice']), 2) }}
                    $
                </div>
            </div>


        </div>

    </div>
    <div class="my-10" style="margin: 50px">
    </div>

    <h1 class="text-3xl font-bold text-center">داهاتی خاوەن پشکەکان</h1>
    <div style="">
        @php
            $dt = [];
            $profit = ($reqD + $reqI / $data['dollarPrice'] - ($reqD1 + $reqI1 / $data['dollarPrice'])) / 3;
        @endphp
        @foreach ($data['partnersBalance'] as $partner)
            @if ($partner->priceType == '$')
                @php
                    $dt[$partner->partnersName] = ['name' => $partner->partnersName, 'balance' => $partner->balance];
                @endphp
            @else
                @php
                    $dt[$partner->partnersName]['balance'] += $partner->balance / $data['dollarPrice'];
                @endphp
            @endif
        @endforeach
        <div style="display: flex;">
            <div class="border p-2"
                style="display: flex; width:25%;background:#213780;color:white !important ;font-weight:bold">
                خاوەن پشک
            </div>
            <div class="border p-2" style="display: flex; width:25%;background:#213780;color:white">
                داهاتی پێشوو
            </div>
            <div class="border p-2" style="display: flex; width:25%;background:#213780;color:white">
                کۆی گشتی داهات
            </div>
            <div class="border p-2" style="display: flex; width:25%;background:#213780;color:white">
                داهاتی ئێستا
            </div>
        </div>
        @foreach ($dt as $dtt)
            <div style="display: flex">
                <div class="border p-2"
                    style="display: flex; width:25%;background:#172554;color:white !important ;font-weight:bold">
                    {{ $dtt['name'] }}
                </div>
                <div class="border p-2" style="display: flex; width:25%">
                    @if ($dtt['balance'] < 0)
                        ({{ number_format(-1 * $dtt['balance'], 2) }} $)
                    @else
                        {{ number_format($dtt['balance'], 2) }} $
                    @endif
                </div>
                <div class="border p-2" style="display: flex; width:25%">
                    @if ($profit >= 0)
                        {{ number_format($profit, 2) }} $
                    @else
                        ({{ number_format(-1 * $profit, 2) }})
                        $
                    @endif
                </div>
                <div class="border p-2" style="display: flex; width:25%;background:#172554;color:white">
                    @if ($dtt['balance'] + $profit < 0)
                        ({{ number_format($profit - $dtt['balance'] < 0 ? -1 * $profit - $dtt['balance'] : $profit - $dtt['balance'], 2) }}
                        $)
                    @else
                        {{ number_format($dtt['balance'] + $profit, 2) }} $
                    @endif
                </div>
            </div>
            @php
                $dt[$dtt['name']]['balance'] = $dtt['balance'] + $profit;
            @endphp
        @endforeach
    </div>
    <div class="my-10">
    </div>
    @php
        $reqD = 0;
        $reqI = 0;
        $reqD1 = 0;
        $reqI1 = 0;
    @endphp
    <h1 class="text-3xl font-bold text-center">میزانییەی کۆمپانیا</h1>
    <div style="display: flex">
        <div style="width:50%" class="">
            <div style="background:#172554;color:white !important ;font-weight:bold"
                class="py-2 text-center border px-2">
                سەروەت
            </div>
            <div style="display: flex;">
                <div style="background: #475569;color:white;width:50%" class="py-2 px-2 border">
                    قەرزی کڕیارەکان
                </div>
                <div style="width:50%" class="py-2 border px-2">
                    @php
                        $reqD += $data['CustomerDebts'] + $data['recivedMoney'] + $data['productsBalance'];
                        $reqI += 0;
                    @endphp
                    {{ number_format($data['CustomerDebts'], 2) }} $
                </div>
            </div>
            <div style="display: flex;">
                <div style="background: #475569;color:white;width:50%" class="py-2 px-2 border">
                    کۆی گشتی پسولە واصلکراوەکان
                </div>
                <div style="width:50%" class="py-2 border px-2">
                    {{ number_format($data['recivedMoney'], 2) }} $
                </div>
            </div>
            <div style="display: flex;">
                <div style="background: #475569;color:white;width:50%" class="py-2 px-2 border">
                    کۆی گشتی کاڵاکان
                </div>
                <div style="width:50%" class="py-2 border px-2">
                    {{ number_format($data['productsBalance'], 2) }} $
                </div>
            </div>
            @foreach ($dt as $dtt)
                @if ($dtt['balance'] < 0)
                    @php
                        $reqD -= $dtt['balance'];
                        $reqI += 0;
                    @endphp
                    <div style="display: flex;">
                        <div style="background: #475569;color:white;width:50%" class="py-2 px-2 border">
                            قەرزی {{ $dtt['name'] }}
                        </div>
                        <div style="width:50%" class="py-2 border px-2">
                            {{ number_format(-1 * $dtt['balance'], 2) }} $
                        </div>
                    </div>
                @endif
            @endforeach



        </div>
        <div style="width:50%" class="">
            <div style="background:#172554;color:white !important ;font-weight:bold"
                class="py-2 text-center border px-2">
                داواکراوەکان
            </div>
            <div style="display: flex;">
                <div style="background: #475569;color:white;width:50%" class="py-2 px-2 border">
                    قەرزی فرۆشیارەکان
                </div>
                <div style="width:50%" class="py-2 border px-2">
                    @php
                        $reqD1 += $data['VendorDebts'] + $data['sendedMoney'];
                        $reqI1 += 0;
                    @endphp
                    {{ number_format($data['VendorDebts'], 2) }} $
                </div>
            </div>
            <div style="display: flex;">
                <div style="background: #475569;color:white;width:50%" class="py-2 px-2 border">
                    کۆی گشتی پسولە واصلکراوەکان
                </div>
                <div style="width:50%" class="py-2 border px-2">
                    {{ number_format($data['sendedMoney'], 2) }} $
                </div>
            </div>
            @foreach ($dt as $dtt)
                @if ($dtt['balance'] > 0)
                    @php
                        $reqD1 += $dtt['balance'];
                        $reqI1 += 0;
                    @endphp
                    <div style="display: flex;">
                        <div style="background: #475569;color:white;width:50%" class="py-2 px-2 border">
                            داهاتی {{ $dtt['name'] }}
                        </div>
                        <div style="width:50%" class="py-2 border px-2">
                            {{ number_format($dtt['balance'], 2) }} $
                        </div>
                    </div>
                @endif
            @endforeach
            @foreach ($data['expenses'] as $expenses)
                <div style="display: flex;">
                    <div style="background: #475569;color:white;width:50%" class="py-2 px-2 border">
                        خەرجییەکان
                    </div>
                    <div style="width:50%" class="py-2 border px-2">
                        @if ($expenses->priceType == 0)
                            @php
                                $reqD1 += $expenses->total;
                                $reqI1 += 0;
                            @endphp
                            {{ number_format($expenses->total, 2) }} $
                        @else
                            @php
                                $reqD1 += 0;
                                $reqI1 += $expenses->total;
                            @endphp
                            {{ number_format($expenses->total, 0) }} د.ع
                        @endif
                    </div>
                </div>
            @endforeach



        </div>
    </div>
    <div style="display: flex">
        <div style="width:50%" class="">
            <div style="display: flex;">
                <div style="background:#172554;color:white !important ;font-weight:bold;width:50%"
                    class="py-2 px-2 border">
                    کۆی گشتی دۆلاری ئەمریکی
                </div>
                <div style="width:50%" class="py-2 border px-2">
                    {{ number_format($reqD, 2) }} $ </div>
            </div>
            <div style="display: flex;">
                <div style="background:#172554;color:white !important ;font-weight:bold;width:50%"
                    class="py-2 px-2 border">
                    کۆی گشتی دیناری عێراقی
                </div>
                <div style="width:50%" class="py-2 border px-2">
                    {{ number_format($reqI, 0) }} د.ع</div>
            </div>
            <div style="display: flex;">
                <div style="background: #475569;color:white;width:50%" class="py-2 border px-2">
                    نرخی دۆلار
                </div>
                <div style="width:50%" class="py-2 border px-2">
                    {{ number_format($data['dollarPrice'], 2) }} د.ع
                </div>
            </div>
            <div style="display: flex;">
                <div style="background:#172554;color:white !important ;font-weight:bold;width:50%"
                    class="py-2 px-2 border">
                    کۆی گشتی بەدۆلار
                </div>
                <div style="width:50%" class="py-2 border px-2">
                    {{ number_format($reqD + $reqI / $data['dollarPrice'], 2) }} $</div>
            </div>
        </div>
        <div style="width:50%" class="">
            <div style="display: flex;">
                <div style="background:#172554;color:white !important ;font-weight:bold;width:50%"
                    class="py-2 px-2 border">
                    کۆی گشتی دۆلاری ئەمریکی
                </div>
                <div style="width:50%" class="py-2 border px-2">
                    {{ number_format($reqD1, 2) }} $ </div>
            </div>
            <div style="display: flex;">
                <div style="background:#172554;color:white !important ;font-weight:bold;width:50%"
                    class="py-2 px-2 border">
                    کۆی گشتی دیناری عێراقی
                </div>
                <div style="width:50%" class="py-2 border px-2">
                    {{ number_format($reqI1, 0) }} د.ع</div>
            </div>
            <div style="display: flex;">
                <div style="background: #475569;color:white;width:50%" class="py-2 border px-2">
                    نرخی دۆلار
                </div>
                <div style="width:50%" class="py-2 border px-2">
                    {{ number_format($data['dollarPrice'], 2) }} د.ع
                </div>
            </div>
            <div style="display: flex;">
                <div style="background:#172554;color:white !important ;font-weight:bold;width:50%"
                    class="py-2 px-2 border">
                    کۆی گشتی بەدۆلار
                </div>
                <div style="width:50%" class="py-2 border px-2">
                    {{ number_format($reqD1 + $reqI1 / $data['dollarPrice'], 2) }} $</div>
            </div>
        </div>
    </div>
    <div class="my-20">
    </div>
</x-filament-panels::page>
