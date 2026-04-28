<x-filament-panels::page>
    <div class="space-y-8">

        {{-- سكشن ملخص الأداء --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div
                class="p-6 bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 border-t-4 border-t-gold">
                <p class="text-sm font-medium text-gray-500">نسبة المنتهي</p>
                <div class="flex items-end gap-2 mt-2">
                    <h3 class="text-4xl font-black text-gray-900 dark:text-white">{{ $chartsData['completion_rate'] }}%
                    </h3>
                    <span class="text-green-500 text-sm mb-1 font-bold">منتهي</span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-700 h-2 rounded-full mt-4">
                    <div class="bg-gold h-2 rounded-full" style="width: {{ $chartsData['completion_rate'] }}%"></div>
                </div>
            </div>

            <div
                class="p-6 bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 border-t-4 border-t-navy">
                <p class="text-sm font-medium text-gray-500">مخالفات بدون ترخيص</p>
                <h3 class="text-4xl font-black text-red-600 mt-2">{{ number_format($chartsData['types']['new']) }}</h3>
                <p class="text-xs text-gray-400 mt-2">قرار إزالة كلي</p>
            </div>

            <div
                class="p-6 bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 border-t-4 border-t-blue-500">
                <p class="text-sm font-medium text-gray-500">تجاوز شروط الترخيص</p>
                <h3 class="text-4xl font-black text-blue-600 mt-2">{{ number_format($chartsData['types']['licensed']) }}
                </h3>
                <p class="text-xs text-gray-400 mt-2">مخالفات هندسية</p>
            </div>
        </div>

        {{-- توزيع التكاليف التقديرية للمخالفات --}}
        <div
            class="mt-4 bg-white dark:bg-gray-900 p-8 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-800">
            <h4 class="p-4 text-xl font-black mb-6 flex items-center gap-2">
                <x-heroicon-o-banknotes class="w-6 h-6 text-gold" />
                أعلى المراكز من حيث القيمة التقديرية للمخالفات
            </h4>
            <div class="space-y-6">
                @foreach ($chartsData['top_costs'] as $item)
                    <div>
                        <div class="flex justify-between mb-2">
                            <span class="font-bold text-gray-700 dark:text-gray-300">{{ $item->center }}</span>
                            <span class="font-black text-navy dark:text-gold">{{ number_format($item->total_cost, 0) }}
                                ج.م</span>
                        </div>
                        <div class="w-full bg-gray-100 dark:bg-gray-800 h-4 rounded-lg overflow-hidden">
                            <div class="bg-navy dark:bg-gold h-full animate-pulse"
                                style="width: {{ ($item->total_cost / $chartsData['top_costs']->max('total_cost')) * 100 }}%">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <style>
        .text-gold {
            color: #e1b12c;
        }

        .bg-gold {
            background-color: #e1b12c;
        }

        .text-navy {
            color: #1e272e;
        }

        .bg-navy {
            background-color: #1e272e;
        }
    </style>
</x-filament-panels::page>
