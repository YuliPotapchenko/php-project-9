<?php

declare(strict_types=1);

namespace App\ReadModel;

use PDO;

class UrlFetcher
{
    public function __construct(private PDO $pdoConnection)
    {
    }

    public function getAllDetail(): array
    {
        $queryUrls = 'SELECT id, name FROM urls ORDER BY created_at DESC';

        $queryChecks = '
            SELECT url_id, created_at, status_code
            FROM url_checks
            WHERE (url_id, created_at) IN (
                SELECT url_id, MAX(created_at)
                FROM url_checks
                GROUP BY url_id
            )
        ';

        $urls = collect($this->pdoConnection->query($queryUrls)->fetchAll(PDO::FETCH_ASSOC));
        $checks = collect($this->pdoConnection->query($queryChecks)->fetchAll(PDO::FETCH_ASSOC));

        $result = $urls->map(function ($url) use ($checks) {
            $check = $checks->where('url_id', $url['id'])->first();
            return array_merge($url, [
                'last_check_date' => $check['created_at'] ?? null,
                'status_code' => $check['status_code'] ?? null,
            ]);
        });

        return $result->all();
    }
}
