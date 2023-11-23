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
                font-size: 12pt !important;
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
                    <b>پسولەی پارە دان</b>
                </div>
                <div class="text-left">
                    <b>بەروار: </b> {{ $data->created_at->format('d/m/Y') }}
                </div>
            </div>
            <hr class="my-2">
            <div class="mt-2 pb-2 grid grid-cols-3 items-center align-middle "
                style="padding-bottom: 2px;font-size:14pt">
                <div>
                    <b>بڕ: </b> <span
                        style="border-bottom: 1px #2c3862 dashed; padding-bottom: 2px">{{ number_format($data->priceType == '$' ? $data->amount : $data->amount * $data->dolarPrice) }}
                        {{ $data->priceType }} </span>
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

        <tbody class="border">
            <tr class="border">
                <td class="border p-2 text-center font-bold" style="background: #e9e9e9">
                    وەرگیرا لە لایەن
                </td>
                <td colspan="3" class="border p-2 ">
                    {{ $data->name }}
                </td>

            </tr>
            <tr class="border">
                <td class="border p-2 text-center font-bold" style="background: #e9e9e9">
                    بەنوسین
                </td>
                <td colspan="3" class="border p-2 ">
                    <span id="kurdishText"></span> {{ $data->priceType == '$' ? 'الدولار الأمريكي' : 'الدينار العراقي' }}.
                </td>

            </tr>
            <tr class="border">
                <td class="border p-2 text-center font-bold" style="background: #e9e9e9">
                    لەبڕی
                </td>
                <td colspan="3" class="border p-2 ">
                    {{ $data->note }}.
                </td>
            </tr>
            <tr class="border">
                <td class="border p-2 text-center font-bold" style="background: #e9e9e9">
                    وەرگیرا لە
                </td>
                <td colspan="3" class="border p-2 ">
                    {{ $data->user_name }}
                </td>
            </tr>
        </tbody>
    </table>
    {{-- <table style="width: 60%; margin-right: auto;" class="border">

        <tr class="border">
            <th class="p-2 border" style="background: #6b3535;color: white;">
                قەرزی پێشوو
            </th>
            <td class="border p-2 text-center font-bold" style="background: #e9e9e9; ">
                {{ number_format($customerDebt + $data->amount + $data->discount) }} $
            </td>
        </tr>
        @if ($data->discount > 0)
            @if ($data->priceType == '$')
                <tr class="border">
                    <th class="p-2 border" style="background: #2a5236;color: white;">
                        داشکان
                    </th>
                    <td class="border p-2 text-center font-bold" style="background: #e9e9e9; ">
                        {{ number_format($data->discount) }} $
                    </td>
                </tr>
            @else
                <tr class="border">
                    <th class="p-2 border" style="background: #2a5236;color: white;">
                        داشکان
                    </th>
                    <td class="border p-2 text-center font-bold" style="background: #e9e9e9; ">
                        {{ number_format($data->discount * $data->dolarPrice) }} د.ع
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
                <tr class="border">
                    <th class="p-2 border" style="background: #2a5236;color: white;">
                        بڕی داشکان بە دۆلار
                    </th>
                    <td class="border p-2 text-center font-bold" style="background: #e9e9e9; ">
                        {{ number_format($data->discount) }} $
                    </td>
                </tr>
            @endif
        @endif
        @if ($data->priceType == '$')
            <tr class="border">
                <th class="p-2 border" style="background: #2a5236;color: white;">
                    بڕی واصلکراو
                </th>
                <td class="border p-2 text-center font-bold" style="background: #e9e9e9; ">
                    {{ number_format($data->amount) }} $
                </td>
            </tr>
        @else
            <tr class="border">
                <th class="p-2 border" style="background: #2a5236;color: white;">
                    بڕی واصلکراو
                </th>
                <td class="border p-2 text-center font-bold" style="background: #e9e9e9; ">
                    {{ number_format($data->amount * $data->dolarPrice) }} د.ع
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
            <tr class="border">
                <th class="p-2 border" style="background: #2a5236;color: white;">
                    بڕی واصلکراو بە دۆلار
                </th>
                <td class="border p-2 text-center font-bold" style="background: #e9e9e9; ">
                    {{ number_format($data->amount) }} $
                </td>
            </tr>
        @endif
        <tr class="border">
            <th class="p-2 border" style="background: #6b3535;color: white;">
                قەرزی ماوە
            </th>
            <td class="border p-2 text-center font-bold" style="background: #e9e9e9; ">
                {{ number_format($customerDebt) }} $
            </td>
        </tr>
    </table> --}}
    <hr class="my-4">
    <div class="flex justify-between p-4 mt-4">
        <div class="text-center">
            <div>
                وەرگیرا لە
            </div>
            <div>
                {{ $data->user_name }}
            </div>
        </div>
        <div class="text-center">
            <div>
                وەگیرا لەلایەن
            </div>
            <div>
                {{ $data->name }}
            </div>
        </div>
    </div>
    <hr class="my-4">
    <script type="module">
        //Sorani version of numbers-to-kurdish-words
        export default class KurdishSoraniNumbersToWords {
            static convert(number) {
                if (typeof number !== 'number') {
                    return 'The entered number was not valid'
                } else {
                    number = parseInt(number).toString()
                    if (this._get_number_type(number) === 'ones') {
                        return this._calculate_ones(number)
                    } else if (this._get_number_type(number) === 'tens') {
                        return this._calculate_tens(number)
                    } else if (this._get_number_type(number) === 'hundreds') {
                        return this._calculate_hundreds(number)
                    } else if (this._get_number_type(number) === 'thousands') {
                        return this._calculate_thousands(number)
                    } else if (this._get_number_type(number) === 'tens-thousands') {
                        return this._calculate_tens_thousands(number)
                    } else if (this._get_number_type(number) === 'hundreds-thousands') {
                        return this._calculate_hundreds_thousands(number)
                    } else if (this._get_number_type(number) === 'millions') {
                        return this._calculate_millions(number)
                    } else if (this._get_number_type(number) === 'tens-millions') {
                        return this._calculate_tens_millions(number)
                    } else if (this._get_number_type(number) === 'hundreds-millions') {
                        return this._calculate_hundreds_millions(number)
                    } else if (this._get_number_type(number) === 'large-number') {
                        return this._calculate_large_number(number)
                    }
                }
            }
            static _calculate_ones(n) {
                const _dict = {
                    '0': 'سفڕ',
                    '1': 'یەک',
                    '2': 'دوو',
                    '3': 'سێ',
                    '4': 'چوار',
                    '5': 'پێنج',
                    '6': 'شەش',
                    '7': 'حەوت',
                    '8': 'هەشت',
                    '9': 'نۆ'
                }

                return _dict[n]
            }

            static _calculate_tens(n) {
                if (n.toString().startsWith('0')) {
                    return this._calculate_ones(parseInt(n.substr(1)))
                }
                const _dict = {
                    '10': 'دە',
                    '11': 'یازدە',
                    '12': 'دوازدە',
                    '13': 'سێزدە',
                    '14': 'چواردە',
                    '15': 'پازدە',
                    '16': 'شازدە',
                    '17': 'حەڤدە',
                    '18': 'هەژدە',
                    '19': 'نۆزدە',
                    '20': 'بیست',
                    '30': 'سی',
                    '40': 'چل',
                    '50': 'پەنجا',
                    '60': 'شەست',
                    '70': 'حەفتا',
                    '80': 'هەشتا',
                    '90': 'نەوەد'
                }

                if (_dict[n] !== undefined) {
                    return _dict[n]
                } else {
                    const first_n = (parseInt(n.toString().substr(0, 1)) * 10).toString()
                    const second_n = (parseInt(n.toString().substr(1, 1))).toString()
                    return _dict[first_n] + this._get_joint() + this._calculate_ones(second_n)
                }

            }

            static _calculate_hundreds(n) {
                if (n === '100') {
                    return 'سەد'
                } else if (n.endsWith('00')) {
                    const first_n = n.substr(0, 1)
                    return this._calculate_ones(first_n) + 'سەد'
                } else {
                    const first_n = n.substr(0, 1)
                    const second_n = parseInt(n.substr(1)).toString()
                    switch (second_n.length) {
                        case 1: {
                            return this._calculate_hundreds(((parseInt(first_n) * 100).toString())) + this
                                ._get_joint() + this._calculate_ones(second_n)
                        }
                        case 2: {
                            return this._calculate_hundreds(((parseInt(first_n) * 100).toString())) + this
                                ._get_joint() + this._calculate_tens(second_n)
                        }

                    }
                }
            }

            static _calculate_thousands(n) {
                //Better than yek hezar
                if (n === '1000') {
                    return 'هەزار'
                }

                //hezar
                if (n.endsWith('000')) {
                    const current_n = n.substr(0, 1)
                    if (n.startsWith('5')) {
                        return 'پێنج' + ' ' + 'هەزار'
                    } else {
                        return this._calculate_ones(current_n) + ' ' + 'هەزار'
                    }
                }

                // could be 123, 012, 001 (3 different possibilities)
                const rest_int = parseInt(n.substr(1))
                let rest = ''
                switch (rest_int.toString().length) {
                    case 1: {
                        rest = this._calculate_ones(rest_int.toString())
                        break
                    }
                    case 2: {
                        rest = this._calculate_tens(rest_int.toString())
                        break
                    }
                    default: {
                        rest = this._calculate_hundreds(rest_int.toString())
                        break
                    }
                }

                if (n.startsWith('5')) {
                    return 'پێنج' + ' ' + 'هەزار' + this._get_joint() + rest
                } else if (n.startsWith('1')) {
                    return 'هەزار' + this._get_joint() + rest
                } else {
                    const current_n = parseInt(n.substr(0, 1))
                    return this._calculate_ones(current_n.toString()) + ' ' + 'هەزار' + this._get_joint() + rest
                }

            }

            static _calculate_tens_thousands(n) {
                //hezar
                if (n.endsWith('000')) {
                    const current_n = n.substr(0, 2)
                    return this._calculate_tens(current_n) + ' ' + 'هەزار'
                }

                // could be 123, 012, 001 (3 different possibilities)
                const rest_int = parseInt(n.substr(2))
                let rest = ''
                switch (rest_int.toString().length) {
                    case 1: {
                        rest = this._calculate_ones(rest_int.toString())
                        break
                    }
                    case 2: {
                        rest = this._calculate_tens(rest_int.toString())
                        break
                    }
                    default: {
                        rest = this._calculate_hundreds(rest_int.toString())
                        break
                    }
                }

                const current_n = parseInt(n.substr(0, 2))
                return this._calculate_tens(current_n.toString()) + ' ' + 'هەزار' + this._get_joint() + rest

            }

            static _calculate_hundreds_thousands(n) {
                //hezar
                if (n.endsWith('000')) {
                    const current_n = n.substr(0, 3)
                    return this._calculate_hundreds(current_n) + ' ' + 'هەزار'
                }

                // could be 123, 012, 001 (3 different possibilities)
                const rest_int = parseInt(n.substr(3))
                let rest = ''
                switch (rest_int.toString().length) {
                    case 1: {
                        rest = this._calculate_ones(rest_int.toString())
                        break
                    }
                    case 2: {
                        rest = this._calculate_tens(rest_int.toString())
                        break
                    }
                    default: {
                        rest = this._calculate_hundreds(rest_int.toString())
                        break
                    }
                }

                const current_n = parseInt(n.substr(0, 3))
                return this._calculate_hundreds(current_n.toString()) + ' ' + 'هەزار' + this._get_joint() + rest

            }

            static _calculate_millions(n) {
                //Better than yek milyon
                if (n === '1000000') {
                    return 'ملیۆن'
                }

                //milyon
                if (n.endsWith('000000')) {
                    const current_n = n.substr(0, 1)
                    if (n.startsWith('5')) {
                        return 'پێنج' + ' ' + 'ملیۆن'
                    } else {
                        return this._calculate_ones(current_n) + ' ' + 'ملیۆن'
                    }
                }

                // (6 different possibilities)
                const rest_int = parseInt(n.substr(1))
                let rest = ''
                switch (rest_int.toString().length) {
                    case 1: {
                        rest = this._calculate_ones(rest_int.toString())
                        break
                    }
                    case 2: {
                        rest = this._calculate_tens(rest_int.toString())
                        break
                    }
                    case 3: {
                        rest = this._calculate_hundreds(rest_int.toString())
                        break
                    }
                    case 4: {
                        rest = this._calculate_thousands(rest_int.toString())
                        break
                    }
                    case 5: {
                        rest = this._calculate_tens_thousands(rest_int.toString())
                        break
                    }
                    default: {
                        rest = this._calculate_hundreds_thousands(rest_int.toString())
                        break
                    }
                }

                if (n.startsWith('5')) {
                    return 'پێنج' + ' ' + 'ملیۆن' + this._get_joint() + rest
                } else if (n.startsWith('1')) {
                    return 'ملیۆن' + this._get_joint() + rest
                } else {
                    const current_n = parseInt(n.substr(0, 1))
                    return this._calculate_ones(current_n.toString()) + ' ' + 'ملیۆن' + this._get_joint() + rest
                }
            }

            static _calculate_tens_millions(n) {
                //hezar
                if (n.endsWith('000000')) {
                    const current_n = n.substr(0, 2)
                    return this._calculate_tens(current_n) + ' ' + 'ملیۆن'
                }

                // (6 different possibilities)
                const rest_int = parseInt(n.substr(2))
                let rest = ''
                switch (rest_int.toString().length) {
                    case 1: {
                        rest = this._calculate_ones(rest_int.toString())
                        break
                    }
                    case 2: {
                        rest = this._calculate_tens(rest_int.toString())
                        break
                    }
                    case 3: {
                        rest = this._calculate_hundreds(rest_int.toString())
                        break
                    }
                    case 4: {
                        rest = this._calculate_thousands(rest_int.toString())
                        break
                    }
                    case 5: {
                        rest = this._calculate_tens_thousands(rest_int.toString())
                        break
                    }
                    default: {
                        rest = this._calculate_hundreds_thousands(rest_int.toString())
                        break
                    }
                }

                const current_n = parseInt(n.substr(0, 2))
                return this._calculate_tens(current_n.toString()) + ' ' + 'ملیۆن' + this._get_joint() + rest

            }

            static _calculate_hundreds_millions(n) {
                //hezar
                if (n.endsWith('000000')) {
                    const current_n = n.substr(0, 3)
                    return this._calculate_hundreds(current_n) + ' ' + 'ملیۆن'
                }

                // (6 different possibilities)
                const rest_int = parseInt(n.substr(3))
                let rest = ''
                switch (rest_int.toString().length) {
                    case 1: {
                        rest = this._calculate_ones(rest_int.toString())
                        break
                    }
                    case 2: {
                        rest = this._calculate_tens(rest_int.toString())
                        break
                    }
                    case 3: {
                        rest = this._calculate_hundreds(rest_int.toString())
                        break
                    }
                    case 4: {
                        rest = this._calculate_thousands(rest_int.toString())
                        break
                    }
                    case 5: {
                        rest = this._calculate_tens_thousands(rest_int.toString())
                        break
                    }
                    default: {
                        rest = this._calculate_hundreds_thousands(rest_int.toString())
                        break
                    }
                }

                const current_n = parseInt(n.substr(0, 3))
                return this._calculate_hundreds(current_n.toString()) + ' ' + 'ملیۆن' + this._get_joint() + rest

            }

            static _calculate_large_number(n) {
                if (n === '1000000000') {
                    return 'ملیار'
                } else {
                    return n.split('').map((current_number) => KurdishSoraniNumbersToWords._calculate_ones(
                        current_number)).join(' ')
                }
            }

            static _get_number_type(n) {
                switch (n.length) {
                    case 1:
                        return 'ones'
                    case 2:
                        return 'tens'
                    case 3:
                        return 'hundreds';
                    case 4:
                        return 'thousands';
                    case 5:
                        return 'tens-thousands';
                    case 6:
                        return 'hundreds-thousands';
                    case 7:
                        return 'millions'
                    case 8:
                        return 'tens-millions'
                    case 9:
                        return 'hundreds-millions'
                    default:
                        return 'large-number'
                }
            }

            static _get_joint() {
                return ' و '
            }
        }

        document.getElementById('kurdishText').innerHTML = KurdishSoraniNumbersToWords.convert(
            {{ $data->priceType == '$' ? $data->amount : $data->amount * $data->dolarPrice }});
    </script>
</x-filament-panels::page>
