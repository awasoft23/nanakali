<x-filament-panels::page>
    <style>
        .fi-header {
            display: none !important;
            visibility: hidden !important;
        }

        @page {
            size: A4;
        }

        @media print {

            .fi-sidebar,
            .fi-topbar,
            .fi-headBtn {
                display: none !important;
                visibility: hidden !important;
            }

            main,
            .fi-main,
            .fi-main div section {
                margin: 0;
                padding: 2px;
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

            th,
            td {
                font-size: 8pt !important;
            }

        }
    </style>

    <div style="border: 1px solid black; " class="rounded-md overflow-hidden">
        <div class="w-full hidens">
            <div class=" flex justify-between items-center align-middle border-b-2"
                style="background: #2c3862;border-bottom:6px solid #475569">
                <div class="px-4 text-white font-bold   " style="font-size: 28pt">
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
        <div style="padding: 20px">
            <div class="mt-1  grid grid-cols-3 items-center align-middle ">
                <div class="text-lg font-bold">

                    <div class="mt-1">
                        ئەیاد : 6427 755 0750
                    </div>

                </div>
                <div class="text-2xl text-center">
                    <b>پسولەی فرۆشتن</b>
                </div>
                <div class="text-left">
                    <b>بەروار: </b> {{ $data->created_at->format('d/m/Y') }}
                </div>
            </div>
            <hr class="my-2">
            <div class="mt-2 pb-2 grid grid-cols-3 items-center align-middle "
                style="padding-bottom: 2px;font-size:14pt">
                <div>
                    <b>بەڕێز: </b> <span
                        style="border-bottom: 1px #2c3862 dashed; padding-bottom: 2px">{{ $data->name }}</span>
                </div>
                <div style="color: red" class="text-center text-base">
                    <b>ژمارەی پسوولە: </b> {{ $data->id }}
                </div>
                <div class="text-left">
                    <b>ژ.م: </b> <span
                        style="border-bottom: 1px #2c3862 dashed; padding-bottom: 2px">{{ $data->phone }}</span>
                </div>
            </div>
        </div>
    </div>

    <table style="font-size: 12pt">
        <thead style="background: #2c3862;color: white;">
            <th class="py-2">
                #
            </th>
            <th class="border">
                جۆری مەڕمەڕ
            </th>
            <th class="border">
                جۆری قالب
            </th>
            <th class="border">
                کۆدی ڕەنگ
            </th>
            <th class="border">
                نرخ
            </th>
            <th class="border">
                قیاس
            </th>
            <th class="border">
                کۆی گشتی
            </th>
        </thead>
        <tbody>
            @php
                $dd = 1;
                $total = 0;
            @endphp
            @foreach ($invoiceProducts as $pro)
                <tr @if ($dd % 2 == 0) style="background: #e9e9e9" @endif class="border">
                    <td class="py-2 text-center border">
                        {{ $dd }}
                    </td>
                    <td class="text-center border">
                        {{ $pro->name }}
                    </td>
                    <td class="text-center border">
                        {{ $pro->code }}
                    </td>
                    <td class="text-center border">
                        {{ $pro->colorCode }}
                    </td>
                    <td class="text-center border">
                        $ {{ number_format($pro->sallingPrice) }}
                    </td>
                    <td class="text-center border">
                        {{ number_format($pro->qty) }} - {{ $pro->unit }}
                    </td>
                    <td class="text-center border">
                        $ {{ number_format($pro->qty * $pro->sallingPrice) }}
                    </td>
                </tr>
                @php
                    $dd++;
                    $total += $pro->qty * $pro->sallingPrice;
                @endphp
            @endforeach

        </tbody>
    </table>
    @foreach ($data->images as $image)
        <img src="{{asset($image)}}" alt="">
    @endforeach
    <table style="width: 60%; margin-right: auto;" class="border">
        <tr class="border">
            <th class="p-2 border" style="background: #2c3862;color: white;">
                کۆی گشتی
            </th>
            <td class="border p-2 text-center font-bold" style="background: #e9e9e9; ">
                $ {{ number_format($total) }}
            </td>
        </tr>
        <tr class="border">
            <th class="p-2 border" style="background: #6b3535;color: white;">
                قەرزی پێشوو
            </th>
            <td class="border p-2 text-center font-bold" style="background: #e9e9e9; ">
                $ {{ number_format($customerDebt) }}
            </td>
        </tr>
        <tr class="border">
            <th class="p-2 border" style="background: #2c3862;color: white;">
                کۆی گشتی
            </th>
            <td class="border p-2 text-center font-bold" style="background: #e9e9e9; ">
                $ {{ number_format($customerDebt + $total) }}
            </td>
        </tr>
        @if ($data->priceType == '$')
            <tr class="border">
                <th class="p-2 border" style="background: #2a5236;color: white;">
                    بڕی واصلکراو
                </th>
                <td class="border p-2 text-center font-bold" style="background: #e9e9e9; ">
                    $ {{ number_format($data->paymented) }}
                </td>
            </tr>
        @else
            <tr class="border">
                <th class="p-2 border" style="background: #2a5236;color: white;">
                    بڕی واصلکراو
                </th>
                <td class="border p-2 text-center font-bold" style="background: #e9e9e9; ">
                    {{ number_format($data->paymented * $data->dolarPrice) }} د.ع
                </td>
            </tr>
            <tr class="border">
                <th class="p-2 border" style="background: #4a588b;color: white;">
                    نرخی دۆلار
                </th>
                <td class="border p-2 text-center font-bold" style="background: #e9e9e9; ">
                    {{ number_format($data->dolarPrice) }} د.ع
                </td>
            </tr>
        @endif
        <tr class="border">
            <th class="p-2 border" style="background: #6b3535;color: white;">
                قەرزی ماوە
            </th>
            <td class="border p-2 text-center font-bold" style="background: #e9e9e9; ">
                $ {{ number_format($customerDebt + $total - $data->paymented) }}
            </td>
        </tr>
    </table>

</x-filament-panels::page>
