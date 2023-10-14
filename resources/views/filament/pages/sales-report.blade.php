<x-filament-panels::page>
    <div>
        <div>
            <style>
                .fi-ta-header-cell {
                    background: #213780;
                    color: white !important;
                    font-weight: bold
                }

                .fi-ta-header-cell * {
                    color: white !important;
                    font-weight: bold
                }

                .fi-header {
                    display: none !important;
                    visibility: hidden !important;
                }

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
            <hr class="my-4 hidens">

            <header class="fi-headBtn flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div></div>
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
            <h1
                class="fi-header-heading text-2xl font-bold text-center tracking-center text-gray-950 dark:text-white sm:text-3xl">
                ڕاپۆرتی فرۆشتن
            </h1>

            <hr class="my-4">

            <table style="width: 100%">

                <div>
                    {{ $this->table }}
                </div>


        </div>

    </div>


</x-filament-panels::page>
