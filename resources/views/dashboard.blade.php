<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Конвертер валют
            <a href="{{url('update-rate')}}"
               class="rounded-md bg-indigo-600 px-3 py-2 ml-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Обновить
                курс</a>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('error'))
                <div class="rounded-md px-2 py-1 my-2 font-medium bg-red-50 text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            <div class="rounded-md px-2 py-1 my-2 font-medium bg-indigo-50 text-indigo-700 text-center">
                Дата расчета: {{ $last_date_rate }}
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg lg:h-80 sm:h-full md:h-full">
                <div class="p-6 bg-white border-gray-200">
                    <form method="post" action="{{url('convert')}}">
                        @csrf
                        <div class="space-y-12">
                            <div class="border-b border-gray-900/10 pb-12">
                                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-10">
                                    <div class="sm:col-span-2 sm:col-start-1">
                                        <label for="amount" class="block text-sm font-medium leading-6 text-gray-900">Сумма</label>
                                        <div class="mt-2">
                                            <input type="text" value="{{ $amount }}" name="amount" id="amount"
                                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        </div>
                                    </div>

                                    <div class="sm:col-span-2">
                                        <label for="from_currency"
                                               class="block text-sm font-medium leading-6 text-gray-900">Из</label>
                                        <div class="mt-2">
                                            <select id="from_currency" name="from_currency"
                                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">
                                                @foreach($currencies as $currency )
                                                    <option value="{{ $currency->char_code }}"
                                                            @if ($currency->char_code == $defaultFrom) selected @endif>{{ $currency->char_code }}
                                                        ({{ $currency->name_currency }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="sm:col-span-1">
                                        <div class="mt-8 text-center">
                                            <input id="swap" name="swap" type="button" value="<=>"/>
                                        </div>
                                    </div>

                                    <div class="sm:col-span-2">
                                        <label for="to_currency"
                                               class="block text-sm font-medium leading-6 text-gray-900">В</label>
                                        <div class="mt-2">
                                            <select id="to_currency" name="to_currency"
                                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">
                                                @foreach($currencies as $currency )
                                                    <option value="{{ $currency->char_code }}"
                                                            @if ($currency->char_code == $defaultTo) selected @endif>{{ $currency->char_code }}
                                                        ({{ $currency->name_currency }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="sm:col-span-2">
                                        <div class="mt-8">
                                            <button type="submit"
                                                    class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                                Рассчитать
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    @if ($rate)
                        <p id="amount_panel" class="mt-6 flex items-baseline justify-center gap-x-2">
                            <span
                                class="text-3xl font-bold tracking-tight text-gray-600">{{ $amount }} {{ $defaultFrom }} = </span>
                            <span
                                class="text-5xl font-bold tracking-tight text-gray-900">{{ rtrim(number_format($rate, 4, '.', ' '), '.0') }}</span>
                            <span class="text-3xl font-bold tracking-tight text-gray-900">{{ $defaultTo }}</span>
                        </p>
                    @endif
                </div>
            </div>
            <div class="rounded-md px-2 py-1 my-2 font-medium bg-indigo-50 text-indigo-700 text-center">
                Последнее обновление: {{ $last_update_rate }}
            </div>
        </div>
    </div>
    <script>
        let swapButton = document.getElementById("swap");
        let fromCurrency = document.getElementById("from_currency");
        let toCurrency = document.getElementById("to_currency");
        let amount_panel = document.getElementById("amount_panel");

        swapButton.addEventListener(
            "click",
            () => {
                const temp = fromCurrency.value;
                fromCurrency.value = toCurrency.value;
                toCurrency.value = temp;
                amount_panel.style.display = 'none';
            },
            false
        );
    </script>
</x-app-layout>
