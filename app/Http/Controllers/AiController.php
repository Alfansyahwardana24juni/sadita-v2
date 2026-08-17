<?php

namespace App\Http\Controllers;

use App\Models\ConsultationLog;
use App\Services\AiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AiController extends Controller
{
    public function __construct(private AiService $ai) {}

    public function chat(Request $request): JsonResponse
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'history' => 'nullable|array',
            'history.*.role' => 'required|in:user,assistant',
            'history.*.text' => 'required|string',
            'animal_type' => 'nullable|string|max:50',
        ]);

        $sessionId = $request->session()->getId();
        $message = $request->string('message');
        $history = $request->input('history', []);
        $animalType = $request->input('animal_type');

        // Get AI response
        $reply = $this->ai->chat($history, $message);

        // Detect if AI service returned an exhaustion/error marker
        $isExhausted = str_contains($reply, '[AI_UNAVAILABLE]');
        if ($isExhausted) {
            $reply = str_replace('[AI_UNAVAILABLE]', '', $reply);
            return response()->json([
                'reply' => trim($reply),
                'exhausted' => true,
            ], 503);
        }

        // Log consultation
        $log = ConsultationLog::firstOrCreate(
            ['session_id' => $sessionId, 'created_at' => now()->startOfDay()],
            [
                'animal_type' => $animalType,
                'messages' => [],
                'ip_address' => $request->ip(),
            ]
        );

        $log->animal_type = $animalType ?? $log->animal_type;
        $log->addMessage('user', $message);
        $log->addMessage('assistant', $reply);

        return response()->json([
            'reply' => $reply,
            'session_id' => $sessionId,
        ]);
    }
}
