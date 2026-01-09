<?php

namespace App\Services;

use App\Models\TemporaryLink;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class TemporaryLinkService
{
    /**
     * Generate a secure temporary link
     *
     * @param string $email
     * @param string $type
     * @param int $expiryMinutes
     * @param array $metadata
     * @param bool $singleUse
     * @return array ['token' => string, 'url' => string, 'expires_at' => Carbon]
     */
    public function generateLink(
        string $email,
        string $type,
        int $expiryMinutes = 72 * 60, // 72 hours default
        array $metadata = [],
        bool $singleUse = true
    ): array {
        // Generate cryptographically secure token
        $token = $this->generateSecureToken();

        // Calculate expiry
        $expiresAt = Carbon::now()->addMinutes($expiryMinutes);

        // Store metadata about single-use requirement
        $metadata['single_use'] = $singleUse;

        // Create the temporary link record
        $link = TemporaryLink::create([
            'email' => $email,
            'token' => $token,
            'type' => $type,
            'expires_at' => $expiresAt,
            'metadata' => $metadata,
        ]);

        // Generate full URL based on type
        $url = $this->generateUrl($type, $token);

        return [
            'token' => $token,
            'url' => $url,
            'expires_at' => $expiresAt,
            'link_id' => $link->id,
        ];
    }

    /**
     * Generate a cryptographically secure token
     */
    private function generateSecureToken(): string
    {
        // Try up to 5 times to generate a unique token
        $attempts = 0;
        do {
            // Generate 64-character secure random token
            $token = Str::random(64);
            $attempts++;

            // Check if token already exists
            $exists = TemporaryLink::where('token', $token)->exists();

            if (!$exists) {
                return $token;
            }
        } while ($attempts < 5);

        // If still not unique after 5 attempts, use hash for extra uniqueness
        return hash('sha256', Str::random(64) . microtime(true) . random_bytes(32));
    }

    /**
     * Generate URL based on link type
     */
    private function generateUrl(string $type, string $token): string
    {
        $routes = [
            'speaker_feedback' => 'speaker.feedback.show',
            // Add more types here as needed
        ];

        $routeName = $routes[$type] ?? null;

        if (!$routeName) {
            throw new \InvalidArgumentException("Unknown link type: {$type}");
        }

        return route($routeName, ['token' => $token]);
    }

    /**
     * Validate a temporary link token
     *
     * @return TemporaryLink|null
     */
    public function validateToken(string $token, string $expectedType = null): ?TemporaryLink
    {
        $query = TemporaryLink::where('token', $token)->valid();

        if ($expectedType) {
            $query->ofType($expectedType);
        }

        return $query->first();
    }

    /**
     * Mark a link as used (for single-use links)
     */
    public function markAsUsed(TemporaryLink $link): bool
    {
        // Check if it's supposed to be single-use
        $metadata = $link->metadata ?? [];
        $singleUse = $metadata['single_use'] ?? true;

        if (!$singleUse) {
            // Multi-use link, don't mark as used
            return true;
        }

        return $link->markAsUsed();
    }

    /**
     * Clean up expired links (call this via scheduled job)
     */
    public function cleanupExpiredLinks(int $daysOld = 30): int
    {
        return TemporaryLink::where('expires_at', '<', Carbon::now()->subDays($daysOld))
            ->delete();
    }

    /**
     * Revoke a specific link (mark as used immediately)
     */
    public function revokeLink(string $token): bool
    {
        $link = TemporaryLink::where('token', $token)->first();

        if (!$link) {
            return false;
        }

        return $link->markAsUsed();
    }

    /**
     * Get link statistics for monitoring
     */
    public function getStatistics(string $type = null): array
    {
        $query = TemporaryLink::query();

        if ($type) {
            $query->ofType($type);
        }

        return [
            'total' => $query->count(),
            'valid' => (clone $query)->valid()->count(),
            'expired' => (clone $query)->expired()->count(),
            'used' => (clone $query)->used()->count(),
        ];
    }
}
