<?php

namespace App\Http\Controllers;

use duncan3dc\Speaker\Providers\GoogleProvider;
use duncan3dc\Speaker\TextToSpeech;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use App\Models\QuizAttempt;


class LearningHubController extends Controller
{
    public function index()
    {
        return view('learning-hub.index');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'document' => 'required|file|mimes:pdf,doc,docx,txt|max:20480', // max 20MB
        ]);

        try {
            // Depoziteaza fisierul incarcat
            $fileName = $request->file('document')->getClientOriginalName();
            $path = $request->file('document')->storeAs('learning-hub-documents', $fileName, 'public');

            // Extrage textul din document
            $documentText = $this->extractTextFromDocument($request->file('document'));

            // Genereaza rezumat folosind Gemini AI
            $summary = $this->generateSummaryWithGemini($documentText);

            \Log::info('Summary generated', [
                'length' => strlen($summary),
                'summary_preview' => substr($summary, 0, 200)
            ]);

            // Genereaza quiz folosind Gemini AI
            $quiz = $this->generateQuizWithGemini($documentText);
            
            \Log::info('Quiz generated', [
                'quiz_data' => $quiz
            ]);

            return back()->with('success', 'Document processed successfully!')
                        ->with('file_name', $fileName)
                        ->with('summary', $summary)
                        ->with('quiz', $quiz)
                        ->with('path', $path);
        } catch (\Exception $e) {
            return back()->with('error', 'Eroare la procesarea documentului: ' . $e->getMessage());
        }
    }

public function generateAudio(Request $request)
{
    $request->validate([
        'summary' => 'required|string',
    ]);

    try {
        $audioRelativePath = $this->SummarytoAudio($request->input('summary'));
        $audioUrl = asset('storage/' . $audioRelativePath) . '?v=' . now()->timestamp;

        return response()->json([
            'success' => true,
            'audio_url' => $audioUrl,
        ]);
    } catch (\Throwable $e) {
        \Log::error('Audio generation failed', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Nu am putut genera audio. Încearcă din nou.',
        ], 500);
    }
}

    private function extractTextFromDocument($file)
    {
        $extension = $file->getClientOriginalExtension();

        if ($extension === 'txt') {
            return file_get_contents($file->path());
        }

        if (in_array($extension, ['doc', 'docx'])) {
            return $this->extractFromWord($file);
        }

        if ($extension === 'pdf') {
            return $this->extractFromPdf($file);
        }

        throw new \Exception('Format de fișier nesuportat');
    }

    private function extractFromWord($file)
    {
        // Extragere din format docx
        $path = $file->path();
        $zip = new \ZipArchive();
        $zip->open($path);

        $xml = $zip->getFromName('word/document.xml');
        $zip->close();

        // Transformare in XML și extragere text
        $dom = new \DOMDocument();
        $dom->loadXML($xml);
        $xpath = new \DOMXPath($dom);

        $texts = $xpath->query('//w:t');
        $fullText = '';

        foreach ($texts as $text) {
            $fullText .= $text->nodeValue;
        }

        return $fullText ?: 'Nu am putut extrage text din document';
    }

    private function extractFromPdf($file)
    {
        try {
            $parser = new \Smalot\PdfParser\Parser();
            $pdf = $parser->parseFile($file->path());
            
            $text = '';
            $pages = $pdf->getPages();
            
            foreach ($pages as $page) {
                $text .= $page->getText();
            }
            
            return $text ?: 'Nu am putut extrage text din PDF';
        } catch (\Exception $e) {
            \Log::error('PDF Extraction Error: ' . $e->getMessage());
            return 'Eroare la extragerea textului din PDF. Încearcă cu format DOCX sau TXT.';
        }
    }

    private function generateSummaryWithGemini($text)
    {
        try {
            $apiKey = config('services.gemini.api_key');
            
            if (!$apiKey) {
                throw new \Exception('Gemini API key not configured');
            }

            // Limit text to avoid token limits
            $maxChars = 30000;
            if (strlen($text) > $maxChars) {
                $text = substr($text, 0, $maxChars) . '...';
            }

            // Folosesc gemini api cu endoint corect si parametri de generare
            $response = Http::timeout(30)
                ->withoutVerifying()
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->post(
                    'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . config('services.gemini.api_key'),
                    [
                        'contents' => [
                            [
                                'parts' => [
                                    [
                                        'text' => "Fă o rezumare detaliată și concisă în limba română a următorului text. Rezumatul trebuie să captureze ideile principale și informații importante. Nu folosi markdown, doar text curat.\n\nText:\n" . $text
                                    ]
                                ]
                            ]
                        ],
                        'generationConfig' => [
                            'temperature' => 0.7,
                            'topK' => 40,
                            'topP' => 0.95,
                            'maxOutputTokens' => 8000,
                        ]
                    ]
                );

            if ($response->failed()) {
                \Log::error('Gemini API Error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                throw new \Exception('Nu am putut genera rezumatul. Verifică API key-ul Gemini.');
            }

            $result = $response->json();
            
            \Log::info('Gemini API Response', $result);

            // Extragere text din raspunsul Gemini
            if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
                return $result['candidates'][0]['content']['parts'][0]['text'];
            }

            throw new \Exception('Răspuns invalid de la API Gemini: ' . json_encode($result));

        } catch (\Exception $e) {
            \Log::error('Gemini Summary Generation Error: ' . $e->getMessage());
            throw $e;
        }
    }

    private function generateQuizWithGemini($text)
    {
        try {
            $apiKey = config('services.gemini.api_key');
            
            if (!$apiKey) {
                throw new \Exception('API key-ul Gemini nu este configurat');
            }

            // Limitez textul pentru a evita depasirea limitelor de tokene
            $maxChars = 30000;
            if (strlen($text) > $maxChars) {
                $text = substr($text, 0, $maxChars) . '...';
            }

            $prompt = "Acționează ca un expert în educație și design de examinare. Analizează textul furnizat și generează un set de 10 întrebări de tip grilă (multiple choice) pentru a testa cunoștințele studenților.\n\n" .
                      "IMPORTANT: Răspunde DOAR cu un JSON valid, fără text suplimentar înainte sau după. Formatul JSON trebuie să fie:\n" .
                      "{\n" .
                      "  \"questions\": [\n" .
                      "    {\n" .
                      "      \"question\": \"Textul întrebării?\",\n" .
                      "      \"options\": [\"Opțiunea A\", \"Opțiunea B\", \"Opțiunea C\", \"Opțiunea D\"],\n" .
                      "      \"correct\": 0\n" .
                      "    }\n" .
                      "  ]\n" .
                      "}\n\n" .
                      "unde 'correct' este indexul răspunsului corect (0, 1, 2, sau 3).\n\n" .
                      "Text:\n" . $text;

            $response = Http::timeout(30)
                ->withoutVerifying()
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->post(
                    'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . $apiKey,
                    [
                        'contents' => [
                            [
                                'parts' => [
                                    [
                                        'text' => $prompt
                                    ]
                                ]
                            ]
                        ],
                        'generationConfig' => [
                            'temperature' => 0.7,
                            'topK' => 40,
                            'topP' => 0.95,
                            'maxOutputTokens' => 8000,
                        ]
                    ]
                );

            if ($response->failed()) {
                \Log::error('Gemini Quiz API Error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                throw new \Exception('Nu am putut genera quiz-ul');
            }

            $result = $response->json();
            
            if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
                $quizText = $result['candidates'][0]['content']['parts'][0]['text'];
                
                // curat raspunsul pentru a extrage doar JSON-ul
                $quizText = preg_replace('/```json\s*/', '', $quizText);
                $quizText = preg_replace('/```\s*$/', '', $quizText);
                $quizText = trim($quizText);
                
                // Parse JSON
                $quizData = json_decode($quizText, true);
                
                if (json_last_error() !== JSON_ERROR_NONE) {
                    \Log::error('Quiz JSON Parse Error', [
                        'error' => json_last_error_msg(),
                        'text' => $quizText
                    ]);
                    throw new \Exception('Nu am putut analiza datele quiz-ului');
                }
                
                return $quizData;
            }

            throw new \Exception('Răspuns invalid de la API Gemini');

        } catch (\Exception $e) {
            \Log::error('Gemini Quiz Generation Error: ' . $e->getMessage());
            throw $e;
        }
    }
//depozitare rezultate quiz
public function saveQuizScore(Request $request)
{
    $request->validate([
        'file_name' => 'required|string',
        'correct_answers' => 'required|integer',
        'total_questions' => 'required|integer',
    ]);
    $correctAnswers = $request->input('correct_answers');
    $totalQuestions = $request->input('total_questions');
    $percentage=($correctAnswers / $totalQuestions) * 100;

    QuizAttempt::create([
        'user_id' => auth()->id(),
        'file_name' => $request->input('file_name'),
        'quiz_data'=>session('quiz'), // stocam structura quiz-ului in baza de date
        'correct_answers' => $correctAnswers,
        'total_questions' => $totalQuestions,
        'percentage' => $percentage
    ]);
    return response()->json([
        'success'=>true,
        'message'=>'Scor salvat cu succes!'
    ]);
}

public function history()
{
    $attempts=QuizAttempt::where('user_id', auth()->id())->orderBy('created_at', 'desc')->get()->groupBy('file_name');//preia toate incercarile userului curent
    $stats=[];

    foreach($attempts as $fileName =>$fileAttempts){
        $stats[$fileName]=[
            'attempts'=>$fileAttempts,
            'total_attempts'=>$fileAttempts->count(),
            'best_score'=>$fileAttempts->max('percentage'),
            'last_score'=>$fileAttempts->first()->percentage,
            'average_score'=>$fileAttempts->avg('percentage'),
            'last_attempt_date'=>$fileAttempts->first()->created_at
        ];
    }
    return view('learning-hub.history', compact('stats'));
}
private function SummarytoAudio($summary)
{
    $relativePath = 'learning-hub-audio/summary.mp3';

    if (!Storage::disk('public')->exists('learning-hub-audio')) {
        Storage::disk('public')->makeDirectory('learning-hub-audio');
    }

    $absolutePath = Storage::disk('public')->path($relativePath);
    $provider = new GoogleProvider('ro');
    $chunks = $this->chunkTextForGoogleTts($summary, 95);

    $audioData = '';

    foreach ($chunks as $chunk) {
        $tts = new TextToSpeech($chunk, $provider);
        $audioData .= $tts->getAudioData();
    }

    file_put_contents($absolutePath, $audioData);

    if (!file_exists($absolutePath) || filesize($absolutePath) === 0) {
        throw new \RuntimeException('Audio file was generated empty.');
    }

    return $relativePath;
}

private function chunkTextForGoogleTts(string $text, int $maxBytes = 95): array//pentru ca Google tts are limita de 100 caractere
{
    $normalized = preg_replace('/\s+/', ' ', trim($text));

    if ($normalized === '') {
        throw new \RuntimeException('Summary is empty.');
    }

    $words = explode(' ', $normalized);
    $chunks = [];
    $current = '';

    foreach ($words as $word) {
        $candidate = $current === '' ? $word : $current . ' ' . $word;

        if (strlen($candidate) <= $maxBytes) {
            $current = $candidate;
            continue;
        }

        if ($current !== '') {
            $chunks[] = $current;
            $current = '';
        }

        while (strlen($word) > $maxBytes) {
            $chunks[] = substr($word, 0, $maxBytes);
            $word = substr($word, $maxBytes);
        }

        $current = $word;
    }

    if ($current !== '') {
        $chunks[] = $current;
    }

    return $chunks;
}



    
}
