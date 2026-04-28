<x-filament-panels::page>
    <div
        class="bg-white dark:bg-gray-900 rounded-3xl shadow-xl overflow-hidden
               border border-gray-200 dark:border-gray-700 transition-colors duration-300">

        {{-- Header --}}
        <div
            class="p-6 bg-gray-50 dark:bg-gray-800
                   border-b border-gray-200 dark:border-gray-700
                   flex justify-between items-center transition-colors">

            <div>
                <h3 class="text-2xl font-black text-gray-900 dark:text-white">
                    سجل القرارات الاذالة
                </h3>

                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    بيان إحصائي بالأعمال المنفذة والقرارات الصادرة
                </p>
            </div>

            <x-filament::button color="gray" icon="heroicon-m-printer" onclick="window.print()" class="rounded-xl">
                طباعة التقرير الرسمي
            </x-filament::button>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">

                <thead>
                    <tr
                        class="bg-gray-100 dark:bg-gray-800
                               text-gray-700 dark:text-gray-300
                               text-sm uppercase">
                        <th class="p-4 border-b border-gray-200 dark:border-gray-700">رقم القرار</th>
                        <th class="p-4 border-b border-gray-200 dark:border-gray-700">المالك</th>
                        <th class="p-4 border-b border-gray-200 dark:border-gray-700">المركز</th>
                        <th class="p-4 border-b border-gray-200 dark:border-gray-700 text-center">حالة القرار</th>
                        <th class="p-4 border-b border-gray-200 dark:border-gray-700">تاريخ الورود</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">

                    @foreach ($reports as $order)
                        <tr
                            class="hover:bg-gray-50 dark:hover:bg-gray-800/40
                                   transition-colors duration-200">

                            {{-- رقم القرار --}}
                            <td class="p-4 font-mono font-bold text-gray-900 dark:text-white">
                                {{ $order->stop_order_number }}
                            </td>

                            {{-- المالك --}}
                            <td class="p-4 font-semibold text-gray-800 dark:text-gray-200">
                                {{ $order->owner_name }}
                            </td>

                            {{-- المركز --}}
                            <td class="p-4">
                                <span
                                    class="px-3 py-1 text-xs rounded-lg
                                           bg-gray-200 dark:bg-gray-700
                                           text-gray-700 dark:text-gray-300">
                                    {{ $order->center }}
                                </span>
                            </td>

                            {{-- الحالة --}}
                            <td class="p-4 text-center">
                                <span
                                    class="px-4 py-1 rounded-full text-[11px] font-bold
                                    {{ $order->status == 'تم التنفيذ'
                                        ? 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-400'
                                        : 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-400' }}">
                                    {{ $order->status }}
                                </span>
                            </td>

                            {{-- التاريخ --}}
                            <td class="p-4 text-sm text-gray-500 dark:text-gray-400">
                                {{ $order->created_at->format('Y/m/d') }}
                            </td>

                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

        {{-- Footer --}}
        <div class="p-6 bg-gray-50 dark:bg-gray-800
                    text-center transition-colors">
            <p class="text-xs text-gray-500 dark:text-gray-400 font-medium italic">
                تم استخراج هذا التقرير آلياً من منظومة حوكمة الإزالات
                بمحافظة كفر الشيخ بتاريخ {{ now()->format('Y-m-d H:i') }}
            </p>
        </div>
    </div>


    {{-- Print Style --}}
    <style>
        @media print {

            .fi-sidebar,
            .fi-topbar,
            .fi-header,
            button {
                display: none !important;
            }

            body {
                background: #fff !important;
            }

            .dark\:bg-gray-900,
            .dark\:bg-gray-800 {
                background: #fff !important;
            }

            table {
                color: #000 !important;
            }
        }
    </style>

</x-filament-panels::page>
