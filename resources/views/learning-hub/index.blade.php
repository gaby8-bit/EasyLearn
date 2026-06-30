<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Learning Hub') }}
        </h2>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl mx-auto">
            <!-- Header -->
            <div class="mb-8 text-center">
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Learning Hub</h1>
                <p class="text-gray-600">Încărcați un document și obțineți o rezumare inteligentă</p>
            </div>

            <!-- Success Message -->
            @if ($message = session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ $message }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Error Message -->
            @if ($message = session('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">{{ $message }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Upload Form -->
            <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6">Încărcați documentul</h2>

                <form action="{{ route('learning-hub.upload') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-6">
                    @csrf

                    <div
                        class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-indigo-400 transition-colors duration-200">
                        <div class="space-y-2">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                viewBox="0 0 48 48" aria-hidden="true">
                                <path
                                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 20M8 28l3.172-3.172a4 4 0 015.656 0L28 36"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600 justify-center">
                                <label for="document"
                                    class="relative cursor-pointer rounded-md bg-white font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2">
                                    <span>Selectați un fișier</span>
                                    <input id="document" name="document" type="file" class="sr-only"
                                        accept=".pdf,.doc,.docx,.txt" required>
                                </label>
                                <p class="pl-1">sau glisați și plasați</p>
                            </div>
                            <p class="text-xs text-gray-500">PDF, DOC, DOCX sau TXT până la 20MB</p>
                        </div>
                    </div>

                    @error('document')
                        <div class="rounded-md bg-red-50 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-red-800">{{ $message }}</p>
                                </div>
                            </div>
                        </div>
                    @enderror

                    <button type="submit"
                        class="w-full bg-indigo-600 text-white font-semibold py-3 px-4 rounded-lg hover:bg-indigo-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Procesați documentul
                    </button>
                </form>
            </div>

            <!-- Summary Display -->
            @if (session('summary'))
                <div class="bg-white rounded-lg shadow-lg p-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">
                        <span class="text-indigo-600">Rezumatul</span> documentului
                    </h2>

                    @if (session('file_name'))
                        <p class="text-sm text-gray-600 mb-4">
                            <span class="font-medium">Fișier:</span> {{ session('file_name') }}
                        </p>
                    @endif

                    <div class="bg-gray-50 rounded-lg p-6 border border-gray-200 max-h-96 overflow-y-auto mb-6">
                        <p class="text-gray-800 leading-relaxed whitespace-pre-wrap">{{ session('summary') }}</p>
                    </div>

                    <div class="mt-6 flex gap-4 flex-wrap">
                        <a href="{{ route('learning-hub.index') }}"
                            class="flex-1 min-w-[200px] text-center bg-indigo-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-indigo-700 transition-colors duration-200">
                            Procesați alt document
                        </a>
                        <button onclick="generateAudio()" id="generate-audio-button"
                            class="flex-1 min-w-[200px] text-center bg-purple-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-purple-700 transition-colors duration-200">
                            Generează audio
                        </button>
                        <button onclick="downloadSummary()"
                            class="flex-1 min-w-[200px] text-center bg-green-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-green-700 transition-colors duration-200">
                            Descarcă rezumatul
                        </button>
                        <button onclick="expandSummary()"
                            class="flex-1 min-w-[200px] text-center bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                            Citește integral
                        </button>
                    </div>

                    <p id="audio-status" class="text-sm text-gray-600 mt-4 hidden"></p>
                </div>
            @endif

            <!-- Audio Player -->
            @if (session('summary'))
                <div id="audio-section"
                    class="bg-white rounded-lg shadow-lg p-8 mt-8 {{ session('audio_url') ? '' : 'hidden' }}">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">
                        <span class="text-purple-600">Audio</span> al rezumatului
                    </h2>

                    <p class="text-sm text-gray-600 mb-6">
                        Ascultați rezumatul generat pentru a vă îmbunătăți înțelegerea
                    </p>

                    <audio id="summary-audio" controls class="w-full rounded-lg">
                        <source id="summary-audio-source"
                            src="{{ session('audio_url') ?? asset('storage/learning-hub-audio/summary.mp3') }}"
                            type="audio/mpeg">
                        Your browser does not support the audio element.
                    </audio>
                </div>
            @endif


            <!-- Quiz Display -->
            @if (session('quiz'))
                <div class="bg-white rounded-lg shadow-lg p-8 mt-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">
                        <span class="text-purple-600">Quiz interactiv</span> pentru învățare
                    </h2>

                    <p class="text-sm text-gray-600 mb-6">
                        Răspundeți la întrebări pentru a vă testa înțelegerea documentului
                    </p>

                    <div id="quiz-container" class="space-y-6">
                        @foreach(session('quiz')['questions'] as $index => $question)
                            <div class="quiz-question bg-gray-50 rounded-lg p-6 border border-gray-200">
                                <h3 class="font-semibold text-gray-900 mb-4">
                                    {{ $index + 1 }}. {{ $question['question'] }}
                                </h3>

                                <div class="space-y-3">
                                    @foreach($question['options'] as $optionIndex => $option)
                                        <label
                                            class="quiz-option flex items-center p-3 bg-white border-2 border-gray-300 rounded-lg cursor-pointer hover:border-purple-400 transition-colors duration-200"
                                            data-question="{{ $index }}" data-option="{{ $optionIndex }}"
                                            data-correct="{{ $question['correct'] }}">
                                            <input type="radio" name="question_{{ $index }}" value="{{ $optionIndex }}"
                                                class="w-5 h-5 text-purple-600 focus:ring-purple-500">
                                            <span class="ml-3 text-gray-800">{{ $option }}</span>
                                            <span class="ml-auto hidden correct-indicator">
                                                <svg class="h-6 w-6 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                            <span class="ml-auto hidden incorrect-indicator">
                                                <svg class="h-6 w-6 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        </label>
                                    @endforeach
                                </div>

                                <div class="quiz-feedback mt-4 p-3 rounded-lg hidden"></div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-8 flex flex-col gap-4">
                        <button onclick="checkAnswers()" id="check-button"
                            class="bg-purple-600 text-white font-semibold py-3 px-4 rounded-lg hover:bg-purple-700 transition-colors duration-200">
                            Verifică răspunsurile
                        </button>

                        <div id="quiz-result" class="hidden p-6 rounded-lg border-2"></div>

                        <button onclick="resetQuiz()" id="reset-button"
                            class="hidden bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-gray-700 transition-colors duration-200">
                            Reîncearcă quiz-ul
                        </button>
                    </div>
                </div>
            @endif

            <!-- Info Section -->
            @if (!session('summary'))
                <div class="bg-white rounded-lg shadow-lg p-8 mt-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Cum functionează?</h3>
                    <ul class="space-y-3 text-gray-700">
                        <li class="flex items-start">
                            <span class="flex-shrink-0 h-6 w-6 text-indigo-600 font-bold mr-3">1.</span>
                            <span>Selectați un document (PDF, DOCX, DOC sau TXT)</span>
                        </li>
                        <li class="flex items-start">
                            <span class="flex-shrink-0 h-6 w-6 text-indigo-600 font-bold mr-3">2.</span>
                            <span>Sistemul va extrage textul din document</span>
                        </li>
                        <li class="flex items-start">
                            <span class="flex-shrink-0 h-6 w-6 text-indigo-600 font-bold mr-3">3.</span>
                            <span>Va genera o rezumare inteligentă a conținutului</span>
                        </li>
                        <li class="flex items-start">
                            <span class="flex-shrink-0 h-6 w-6 text-indigo-600 font-bold mr-3">4.</span>
                            <span>Veți primi un quiz interactiv pentru a vă testa cunoștințele</span>
                        </li>
                        <li class="flex items-start">
                            <span class="flex-shrink-0 h-6 w-6 text-indigo-600 font-bold mr-3">5.</span>
                            <span>Puteți descărca rezumatul și exersa cu quiz-ul</span>
                        </li>
                    </ul>
                </div>
            @endif
        </div>
    </div>

    <script>
        let quizAnswered = false;

        function downloadSummary() {
            var fileName = "{{ session('file_name', 'rezumat') }}".replace(/\.[^/.]+$/, '');
            var summary = "{{ str_replace(["\r", "\n", '"', '\\'], ['', '', '\"', '\\\\'], session('summary')) }}";
            var element = document.createElement('a');
            element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(summary));
            element.setAttribute('download', fileName + '_REZUMAT.txt');
            element.style.display = 'none';
            document.body.appendChild(element);
            element.click();
            document.body.removeChild(element);
        }

        function expandSummary() {
            const summaryDiv = document.querySelector('.bg-gray-50');
            if (summaryDiv.classList.contains('max-h-96')) {
                summaryDiv.classList.remove('max-h-96', 'overflow-y-auto');
                summaryDiv.classList.add('max-h-none');
                event.target.textContent = 'Ascunde integral';
            } else {
                summaryDiv.classList.add('max-h-96', 'overflow-y-auto');
                summaryDiv.classList.remove('max-h-none');
                event.target.textContent = 'Citește integral';
            }
        }

        function generateAudio() {
            const button = document.getElementById('generate-audio-button');
            const status = document.getElementById('audio-status');
            const audioSection = document.getElementById('audio-section');
            const audioElement = document.getElementById('summary-audio');
            const audioSource = document.getElementById('summary-audio-source');
            const summaryText = @json(session('summary'));

            if (!summaryText) {
                return;
            }

            button.disabled = true;
            button.classList.add('opacity-70', 'cursor-not-allowed');
            button.textContent = 'Se generează audio...';

            status.classList.remove('hidden');
            status.textContent = 'Generare audio în curs...';

            fetch('{{ route("learning-hub.generate-audio") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    summary: summaryText
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (!data.success || !data.audio_url) {
                        throw new Error(data.message || 'Nu am putut genera audio.');
                    }

                    audioSource.src = data.audio_url;
                    audioElement.load();
                    audioSection.classList.remove('hidden');

                    status.textContent = 'Audio generat cu succes. Poți apăsa Play.';
                })
                .catch(error => {
                    status.textContent = error.message || 'A apărut o eroare la generarea audio.';
                })
                .finally(() => {
                    button.disabled = false;
                    button.classList.remove('opacity-70', 'cursor-not-allowed');
                    button.textContent = 'Generează audio';
                });
        }

        function checkAnswers() {
            if (quizAnswered) return;

            const questions = document.querySelectorAll('.quiz-question');
            let totalQuestions = questions.length;
            let correctAnswers = 0;
            let answeredQuestions = 0;

            questions.forEach((question) => {
                const selectedOption = question.querySelector('input[type="radio"]:checked');

                if (!selectedOption) {
                    return;
                }

                answeredQuestions++;
                const selectedLabel = selectedOption.closest('.quiz-option');
                const correctAnswer = parseInt(selectedLabel.dataset.correct);
                const selectedAnswer = parseInt(selectedLabel.dataset.option);

                const allOptions = question.querySelectorAll('.quiz-option');

                allOptions.forEach(opt => {
                    opt.querySelector('input').disabled = true;
                    opt.classList.add('pointer-events-none');
                });

                allOptions.forEach(opt => {
                    const optionIndex = parseInt(opt.dataset.option);

                    if (optionIndex === correctAnswer) {
                        opt.classList.remove('border-gray-300');
                        opt.classList.add('border-green-500', 'bg-green-50');
                        opt.querySelector('.correct-indicator').classList.remove('hidden');
                    } else if (optionIndex === selectedAnswer && selectedAnswer !== correctAnswer) {
                        opt.classList.remove('border-gray-300');
                        opt.classList.add('border-red-500', 'bg-red-50');
                        opt.querySelector('.incorrect-indicator').classList.remove('hidden');
                    }
                });

                if (selectedAnswer === correctAnswer) {
                    correctAnswers++;
                }
            });

            if (answeredQuestions === 0) {
                alert('Vă rugăm să răspundeți la cel puțin o întrebare!');
                return;
            }

            // Salvează scorul în BD
            const fileName = '{{ session("file_name") }}' || 'Quiz';

            fetch('{{ route("learning-hub.save-score") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    file_name: fileName,
                    correct_answers: correctAnswers,
                    total_questions: totalQuestions
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('✅ Scor salvat cu succes!');
                    }
                })
                .catch(error => {
                    console.error('❌ Eroare la salvarea scorului:', error);
                });

            quizAnswered = true;

            const resultDiv = document.getElementById('quiz-result');
            const percentage = Math.round((correctAnswers / totalQuestions) * 100);

            resultDiv.classList.remove('hidden');

            let resultClass = '';
            let resultMessage = '';

            if (percentage >= 80) {
                resultClass = 'bg-green-50 border-green-500';
                resultMessage = '🎉 Excelent!';
            } else if (percentage >= 60) {
                resultClass = 'bg-blue-50 border-blue-500';
                resultMessage = '👍 Bine!';
            } else {
                resultClass = 'bg-yellow-50 border-yellow-500';
                resultMessage = '📚 Mai exersează!';
            }

            resultDiv.className = 'p-6 rounded-lg border-2 ' + resultClass;
            resultDiv.innerHTML = `
                <div class="text-center">
                    <h3 class="text-2xl font-bold mb-2">${resultMessage}</h3>
                    <p class="text-lg mb-2">Ai răspuns corect la <span class="font-bold">${correctAnswers}</span> din <span class="font-bold">${totalQuestions}</span> întrebări.</p>
                    <p class="text-3xl font-bold">${percentage}%</p>
                </div>
            `;

            document.getElementById('check-button').classList.add('hidden');
            document.getElementById('reset-button').classList.remove('hidden');
            resultDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }

        function resetQuiz() {
            quizAnswered = false;

            const allOptions = document.querySelectorAll('.quiz-option');
            allOptions.forEach(opt => {
                opt.querySelector('input').disabled = false;
                opt.querySelector('input').checked = false;
                opt.classList.remove('pointer-events-none', 'border-green-500', 'bg-green-50', 'border-red-500', 'bg-red-50');
                opt.classList.add('border-gray-300');
                opt.querySelector('.correct-indicator').classList.add('hidden');
                opt.querySelector('.incorrect-indicator').classList.add('hidden');
            });

            document.getElementById('quiz-result').classList.add('hidden');
            document.getElementById('reset-button').classList.add('hidden');
            document.getElementById('check-button').classList.remove('hidden');
            document.getElementById('quiz-container').scrollIntoView({ behavior: 'smooth' });
        }

        // Drag and drop functionality - execute după ce DOM e gata
        document.addEventListener('DOMContentLoaded', function () {
            const dropZone = document.querySelector('.border-dashed');
            const fileInput = document.getElementById('document');

            if (!dropZone || !fileInput) return;

            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                dropZone.addEventListener(eventName, () => {
                    dropZone.classList.add('border-indigo-400', 'bg-indigo-50');
                }, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, () => {
                    dropZone.classList.remove('border-indigo-400', 'bg-indigo-50');
                }, false);
            });

            dropZone.addEventListener('drop', handleDrop, false);

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                fileInput.files = files;

                if (files.length > 0) {
                    const fileName = files[0].name;
                    const label = dropZone.querySelector('label span');
                    if (label) label.textContent = fileName;
                }
            }

            fileInput.addEventListener('change', function () {
                if (this.files.length > 0) {
                    const fileName = this.files[0].name;
                    const label = dropZone.querySelector('label span');
                    if (label) label.textContent = fileName;
                }
            });
        });
    </script>

</x-app-layout>