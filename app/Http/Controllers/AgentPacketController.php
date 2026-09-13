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
        $payload = $this->packet->site();
        $schema = is_string($payload['$schema'] ?? null) ? $payload['$schema'] : null;

        $response = $this->json($payload);
        if ($schema !== null) {
            $response->headers->set('Link', '<'.$schema.'>; rel="describedby"', false);
        }

        return $response;
    }

    public function mcp(): JsonResponse
    {
        return $this->json($this->packet->mcp());
    }

    public function agentCard(): JsonResponse
    {
        return $this->json($this->packet->agentCard());
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
