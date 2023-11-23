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
        $req = 1;
        $req1 = 2;
        $reqD = 0;
        $reqI = 0;
        $reqD1 = 0;
        $reqI1 = 0;
    @endphp
    @foreach ($data['partnersBalance'] as $partner)
        @if ($partner->balance > 0)
            @php
                $req += 1;
            @endphp
        @else
            @php
                $req1 += 1;
            @endphp
        @endif
    @endforeach

    <header class="fi-headBtn flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="fi-ac  gap-3 flex flex-wrap items-center justify-start shrink-0 sm:mt-7">
            <button style="--c-400:var(--primary-400);--c-500:var(--primary-500);--c-600:var(--primary-600);"
                class="fi-btn relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus:ring-2 rounded-lg fi-color-custom fi-btn-color-primary fi-size-md fi-btn-size-md gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-custom-600 text-white hover:bg-custom-500 dark:bg-custom-500 dark:hover:bg-custom-400 focus:ring-custom-500/50 dark:focus:ring-custom-400/50 fi-ac-btn-action"
                type="button" onclick="window.print()">
                <span class="fi-btn-label">
                    الطباعة
                </span>
            </button>
        </div>
    </header>
    <div class="w-full hidens">
        <div class=" flex justify-between items-center align-middle border-b-2"
            style="background: #2c3862;border-bottom:6px solid #475569">
            <div class="px-4 text-white font-bold   " style="font-size: 30pt">
                مواد نانکلي
                <div style="font-size: 16pt; font-weight: bold; margin:10px 0 ">
                    للمواد الصناعي
                </div>
            </div>
            <div class="bg-white">
                <img src="{{ asset('logo.png') }}" style="width: 250px" alt="">
            </div>
        </div>

    </div>

    <h1
        class="fi-header-heading text-2xl font-bold text-center tracking-center text-gray-950 dark:text-white sm:text-3xl">
        ملخص المربع<span class="text-base">({{ now()->format('Y-m-d') }})</span>
    </h1>
    <hr class="my-4">

    <div style="display: flex">
        <div style="width:50%" class="">
            <div style="background:#172554;color:white !important ;font-weight:bold"
                class="py-2 text-center border px-2">
                يأتي
            </div>
            <div style="display: flex;">
                <div style="background: #475569;color:white;width:50%" class="py-2 px-2 border">
                    إجمالي الوصلات
                </div>
                <div style="width:50%" class="py-2 border px-2">
                    {{ number_format($data['recivedMoney'], 2) }} $
                </div>
            </div>

            @php
                $reqD += $data['recivedMoney'];
                $reqI += 0;
            @endphp

            @foreach ($data['partnersBalance'] as $partner)
                @if ($partner->balance > 0)
                    @if ($partner->priceType == '$')
                        @php
                            $reqD += $partner->balance;
                            $reqI += 0;
                        @endphp
                        <div style="display: flex;">
                            <div style="background: #475569;color:white;width:50%" class="py-2 px-2 border">
                                بواسطة {{ $partner->partnersName }}
                            </div>
                            <div style="width:50%" class="py-2 border px-2">
                                {{ number_format($partner->balance, 2) }} $
                            </div>
                        </div>
                    @else
                        @php
                            $reqD += 0;
                            $reqI += $partner->balance;
                        @endphp
                        <div style="display: flex;">
                            <div style="background: #475569;color:white;font-weight:bold:bold;width:50%"
                                class="py-2 px-2 border">
                                بواسطة {{ $partner->partnersName }}
                            </div>
                            <div style="width:50%" class="py-2 border px-2">
                                {{ number_format(round($partner->balance / 250) * 250, 0) }} د.ع
                            </div>
                        </div>
                    @endif
                @endif
            @endforeach
            <div style="display: flex;">
                <div style="background: #475569;color:white;width:50%" class="py-2 px-2 border">
                    إجمالي الوصلات
                </div>
                <div style="width:50%" class="py-2 border px-2">
                    {{ number_format(round($data['recivedMoneyDinar'] / 250) * 250, 0) }} د.ع
                </div>
            </div>
            @php
                $reqD += 0;
                $reqI += $data['recivedMoneyDinar'];
            @endphp





        </div>
        <div style="width:50%" class="">
            <div style="background:#172554;color:white !important ;font-weight:bold"
                class="py-2 text-center border px-2">
                دەرچوون
            </div>

            <div style="display: flex;">
                <div style="background: #475569;color:white;width:50%" class="py-2 px-2 border">
                    کۆی گشتی پسولەکانی پارەدان
                </div>
                <div style="width:50%" class="py-2 border px-2">
                    {{ number_format($data['sendedMoney'], 2) }} $
                </div>
            </div>

            @foreach ($data['partnersBalance'] as $partner)
                @if ($partner->balance < 0)
                    @if ($partner->priceType == '$')
                        @php
                            $reqD1 += $partner->balance;
                            $reqI1 += 0;
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
                            $reqD1 += 0;
                            $reqI1 += $partner->balance;
                        @endphp
                        <div style="display: flex;">
                            <div style="background: #475569;color:white;font-weight:bold:bold;width:50%"
                                class="py-2 px-2 border">
                                داهاتی {{ $partner->partnersName }}
                            </div>
                            <div style="width:50%" class="py-2 border px-2">
                                {{ number_format(round((-1 * $partner->balance) / 250) * 250, 0) }} د.ع
                            </div>
                        </div>
                    @endif
                @endif
            @endforeach

            @foreach ($data['expenses'] as $expenses)
                <div style="display: flex;">
                    <div style="background: #475569;color:white;width:50%" class="py-2 px-2 border">
                        المصاریف
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
                            {{ number_format(round($expenses->total / 250) * 250, 0) }} د.ع
                        @endif
                    </div>
                </div>
            @endforeach
            <div style="display: flex;">
                <div style="background: #475569;color:white;width:50%" class="py-2 px-2 border">
                    کۆی گشتی پسولەکانی پارەدان
                </div>
                <div style="width:50%" class="py-2 border px-2">
                    {{ number_format(round($data['sendedMoneyDinar'] / 250) * 250, 0) }} د.ع
                </div>
            </div>
            @php
                $reqD1 += $data['sendedMoney'];
                $reqI1 += $data['sendedMoneyDinar'];
            @endphp
        </div>
    </div>
    <div style="display: flex" class="text-center">
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
                    {{ number_format(round($reqI / 250) * 250, 0) }} د.ع</div>
            </div>
            <div style="display: flex;" class="text-center">
                <div style="width:50%" class="py-2  px-2">
                </div>
                <div style="background:#172554;color:white !important ;font-weight:bold;width:50%"
                    class="py-2 px-2 border">
                    کۆی گشتی دۆلاری ئەمریکی
                </div>
            </div>
            <div style="display: flex;" class="text-center">
                <div style="width:50%" class="py-2  px-2">
                </div>
                <div style="background:#172554;color:white !important ;font-weight:bold;width:50%"
                    class="py-2 px-2 border">
                    کۆی گشتی دیناری عێراقی
                </div>
            </div>
        </div>
        <div style="width:50%" class="text-center">
            <div style="display: flex;">
                <div style="background:#172554;color:white !important ;font-weight:bold;width:50%"
                    class="py-2 px-2 border text-center">
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
                    {{ number_format(round($reqI1 / 250) * 250, 0) }} د.ع</div>
            </div>
            <div style="display: flex;">
                <div style="width:50%" class="py-2 border px-2">
                    {{ number_format($reqD - $reqD1, 0) }}$</div>
                <div style="width:50%" class="py-2  px-2">
                </div>
            </div>
            <div style="display: flex;">
                <div style="width:50%" class="py-2 border px-2">
                    {{ number_format(round(($reqI - $reqI1) / 250) * 250, 0) }} د.ع</div>
                <div style="width:50%" class="py-2  px-2">
                </div>
            </div>
        </div>
    </div>
    <div class="my-10" style="margin: 50px">
    </div>

</x-filament-panels::page>
