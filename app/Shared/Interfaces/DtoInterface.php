<?php

namespace App\Shared\Interfaces;

use Illuminate\Http\Request;

interface DtoInterface
{
    public function toArray(): array;
    public static function fromRequest(Request $request): self;
}
