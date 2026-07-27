<?php

declare(strict_types=1);

namespace App\Shared\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BusinessException extends Exception
{
    protected int $statusCode;
    protected array $details;

    public function __construct(
        string $message = 'A business logic violation occurred.',
        int $statusCode = 400,
        array $details = [],
        ?Exception $previous = null
    ) {
        parent::__construct($message, 0, $previous);
        $this->statusCode = $statusCode;
        $this->details = $details;
    }

    /**
     * Get the HTTP status code.
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Get additional contextual details.
     */
    public function getDetails(): array
    {
        return $this->details;
    }

    /**
     * Render the exception as an HTTP JSON response.
     */
    public function render(Request $request): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $this->getMessage(),
            'errors' => $this->getDetails(),
        ], $this->getStatusCode());
    }
}
