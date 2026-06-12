<?php

return [
    'providers' => [
        'deepseek' => [
            'api_key' => env('DEEPSEEK_API_KEY'),
            'url' => 'https://api.deepseek.com/chat/completions',
            'model' => 'deepseek-chat',
        ],
        // Gemini Models Stack
        'gemini_3_5_flash' => [
            'api_key' => env('GEMINI_API_KEY'),
            'url' => 'https://generativelanguage.googleapis.com/v1beta/openai/chat/completions',
            'model' => 'gemini-3.5-flash',
        ],
        'gemini_3_1_flash_lite' => [
            'api_key' => env('GEMINI_API_KEY'),
            'url' => 'https://generativelanguage.googleapis.com/v1beta/openai/chat/completions',
            'model' => 'gemini-3.1-flash-lite',
        ],
        'gemini_2_5_pro' => [
            'api_key' => env('GEMINI_API_KEY'),
            'url' => 'https://generativelanguage.googleapis.com/v1beta/openai/chat/completions',
            'model' => 'gemini-2.5-pro',
        ],
        'gemini_2_5_flash' => [
            'api_key' => env('GEMINI_API_KEY'),
            'url' => 'https://generativelanguage.googleapis.com/v1beta/openai/chat/completions',
            'model' => 'gemini-2.5-flash',
        ],
        'gemini_2_0_flash' => [
            'api_key' => env('GEMINI_API_KEY'),
            'url' => 'https://generativelanguage.googleapis.com/v1beta/openai/chat/completions',
            'model' => 'gemini-2.0-flash',
        ],
        'gemini_2_0_flash_lite' => [
            'api_key' => env('GEMINI_API_KEY'),
            'url' => 'https://generativelanguage.googleapis.com/v1beta/openai/chat/completions',
            'model' => 'gemini-2.0-flash-lite',
        ],
        'huggingface' => [
            'api_key' => env('HUGGINGFACE_API_KEY'),
            'url' => 'https://router.huggingface.co/v1/chat/completions',
            'model' => 'Qwen/Qwen2.5-Coder-32B-Instruct',
        ],
        'groq' => [
            'api_key' => env('GROQ_API_KEY'),
            'url' => 'https://api.groq.com/openai/v1/chat/completions',
            'model' => 'llama-3.3-70b-versatile',
        ],
    ],
    // The sequence in which the system tries the AI providers
    'routing' => [
        'deepseek', 
        'gemini_3_5_flash', 
        'gemini_2_5_pro', 
        'gemini_2_5_flash', 
        'gemini_2_0_flash', 
        'gemini_3_1_flash_lite', 
        'gemini_2_0_flash_lite', 
        'groq', 
        'huggingface'
    ],
];
