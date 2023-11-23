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
                    <b>کشف حساب</b>
                </div>
                <div class="text-left">
                    <b>التاریخ: </b> {{ \Carbon\Carbon::parse($data['from'])->format('d/m/Y') }} - {{\Carbon\Carbon::parse($data['to'])->format('d/m/Y') }}
                </div>
            </div>
            <hr class="my-2">
            <div class="mt-2 pb-2 grid grid-cols-3 items-center align-middle "
                style="padding-bottom: 2px;font-size:14pt">
                <div>
                    <b>السید: </b> <span
                        style="border-bottom: 1px #2c3862 dashed; padding-bottom: 2px">{{ $data['customer']->name }}</span>
                </div>

                <div class="text-left">
                    <b>رقم هاتف: </b> <span
                        style="border-bottom: 1px #2c3862 dashed; padding-bottom: 2px">{{ $data['customer']->phone }}</span>
                </div>
            </div>
        </div>
    </div>

    <table style="font-size: 12pt">
        <thead style="background: #2c3862;color: white;">

            <th class="border">
                حساب </th>
            <th class="border">
                مبلغ
            </th>
            <th class="border">
                نوع
            </th>
            <th class="border">
                تاریخ
            </th>

        </thead>
        <tbody>
            <tr class="border">
                <td class="py-2 text-center border">
                    {{ number_format($data['old']) }} $
                </td>
                <td class="text-center border">
                    {{ number_format($data['old']) }} $
                </td>
                <td class="text-center border">
                    حساب قدیم
                </td>
                <td class="text-center border">
                    {{ \Carbon\Carbon::parse($data['from'])->format('d/m/Y') }}
                </td>

            </tr>

            @php
                $dd = 0;
                $total = 0;
            @endphp
            @foreach ($data['sorted'] as $dat)
                @if ($dat['amount'] != 0)
                    <tr @if ($dd % 2 == 0) style="background: #e9e9e9" @endif class="border">
                        <td class="py-2 text-center border">
                            @php
                                $data['old'] += $dat['amount'];
                            @endphp
                            $
                            @if ($data['old'] >= 0)
                                {{ number_format($data['old']) }}
                            @else
                                ({{ number_format($data['old'] * -1) }})
                            @endif
                        </td>
                        <td class="text-center border">
                            @if ($dat['amount'] > 0)
                                $ {{ number_format($dat['amount']) }}
                            @else
                                $ {{ number_format($dat['amount'] * -1) }}
                            @endif
                        </td>
                        <td class="text-center border">
                            {{ $dat['type'] == 1 ? 'قائمة' : 'واصل' }}
                        </td>
                        <td class="text-center border">
                            {{ \Carbon\Carbon::parse($dat['created'])->format('d/m/Y') }}
                        </td>

                    </tr>
                    @php
                        $dd++;
                    @endphp
                @endif
            @endforeach

        </tbody>
    </table>



</x-filament-panels::page>
