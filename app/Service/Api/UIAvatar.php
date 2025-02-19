<?php

namespace App\Service\Api;

class UIAvatar
{
    const URL = 'https://ui-avatars.com/api/';
    private string $name;

    private array $options = [
        'background' => 'random',
        'size' => 64
    ];

    public function __construct(string $name)
    {
        $this->name = $name;
    }
    public static function make($name): static
    {
        return new static($name);
    }

    public function background($color): static
    {
        $this->options['background'] = $color;
        return $this;
    }

    public function size($size): static
    {
        $this->options['size'] = $size;
        return $this;
    }

    public function get(): string
    {
        return self::URL . '?' . http_build_query(array_merge($this->options, ['name' => $this->name]));
    }
}
