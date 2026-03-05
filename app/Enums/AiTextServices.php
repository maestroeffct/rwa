<?php

namespace App\Enums;

class AiTextServices
{
    // /v1/chat/completions ENDPOINT
    const GPT_5 = "gpt-5";
    const GPT_4O = "gpt-4o";
    const GPT_4_TURBO = "gpt-4-turbo";
    const GPT_4 = "gpt-4";
    const GPT_35_TURBO = "gpt-3.5-turbo";
    const GPT_35_TURBO_0613 = "gpt-3.5-turbo-0613";

    const types = [
        self::GPT_5,
        self::GPT_4O,
        self::GPT_4_TURBO,
        self::GPT_4,
        self::GPT_35_TURBO,
        self::GPT_35_TURBO_0613,
    ];

    const chatCompletionsEndpoint = [
        self::GPT_5,
        self::GPT_4O,
        self::GPT_4_TURBO,
        self::GPT_4,
        self::GPT_35_TURBO,
        self::GPT_35_TURBO_0613,
    ];

}
