<?php

namespace App\Enums;

enum AiModels : string
{
    case QWEN = 'qwen2.5-7b-instruct-1m';

    case TEST = 'text-embedding-nomic-embed-text-v1.5';
}
