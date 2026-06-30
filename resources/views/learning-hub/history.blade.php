{{-- filepath: resources/views/learning-hub/history.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                 Istoricul Quiz-urilor
            </h2>
            <a href="{{ route('learning-hub.index') }}" 
               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                ← Înapoi la Learning Hub
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(count($stats) === 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-2 text-lg font-medium text-gray-900">Niciun quiz completat</h3>
                        <p class="mt-1 text-sm text-gray-500">Încarcă un document și completează primul quiz!</p>
                        <div class="mt-6">
                            <a href="{{ route('learning-hub.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                Începe acum
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="space-y-6">
                    @foreach($stats as $fileName => $data)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <!-- Header cu numele documentului -->
                            <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6">
                                <h3 class="text-xl font-bold text-white flex items-center">
                                    📄 {{ $fileName }}
                                </h3>
                                <p class="text-blue-100 text-sm mt-1">
                                    Ultima încercare: {{ $data['last_attempt_date']->diffForHumans() }}
                                </p>
                            </div>

                            <!-- Statistici generale -->
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-6 bg-gray-50">
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-blue-600">{{ $data['total_attempts'] }}</div>
                                    <div class="text-sm text-gray-600">Încercări</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-green-600">{{ $data['best_score'] }}%</div>
                                    <div class="text-sm text-gray-600">Cel mai bun</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-orange-600">{{ $data['last_score'] }}%</div>
                                    <div class="text-sm text-gray-600">Ultima încercare</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-purple-600">{{ $data['average_score'] }}%</div>
                                    <div class="text-sm text-gray-600">Media</div>
                                </div>
                            </div>

                            <!-- Tabel cu toate încercările -->
                            <div class="p-6">
                                <h4 class="font-semibold text-gray-700 mb-4">📋 Toate încercările:</h4>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Data</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Scor</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Procentaj</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rezultat</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($data['attempts'] as $index => $attempt)
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                        {{ $index + 1 }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ $attempt->created_at->format('d.m.Y H:i') }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                        {{ $attempt->correct_answers }} / {{ $attempt->total_questions }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="flex items-center">
                                                            <div class="w-full bg-gray-200 rounded-full h-2.5 mr-2">
                                                                <div class="bg-blue-600 h-2.5 rounded-full" 
                                                                     style="width: {{ $attempt->percentage }}%"></div>
                                                            </div>
                                                            <span class="text-sm font-medium text-gray-900">
                                                                {{ $attempt->percentage }}%
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        @if($attempt->percentage >= 80)
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                                🎉 Excelent
                                                            </span>
                                                        @elseif($attempt->percentage >= 60)
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                                👍 Bine
                                                            </span>
                                                        @else
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                                📚 Mai exersează
                                                            </span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>