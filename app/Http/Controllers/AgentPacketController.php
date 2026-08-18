<?php

namespace App\Http\Controllers;

use App\Support\AgentPacket;
use App\Support\CommandIndex;
use Illuminate\Http\JsonResponse;

class AgentPacketController extends Controller
{
    public function __construct(
        protected readonly AgentPacket $packet,
        protected readonly CommandIndex $commands,
    ) {}

    public function site(): JsonResponse
    {
        return $this->json($this->packet->site());
    }

    public function mcp(): JsonResponse
    {
        return $this->json($this->packet->mcp());
    }

    public function commands(): JsonResponse
    {
        return $this->json($this->commands->build());
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    protected function json(array $payload): JsonResponse
    {
        return response()
            ->json($payload, 200, [
                'Access-Control-Allow-Origin' => '*',
            ]);
    }
}
