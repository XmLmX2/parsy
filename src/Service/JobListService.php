<?php
/**
 * User: Marius Mertoiu
 * Date: 11/04/2022 12:40
 * Email: marius.mertoiu@gmail.com
 */

namespace Parsy\Service;

use function Webmozart\Assert\Tests\StaticAnalysis\nullOrCount;

class JobListService
{
    public const DATATABLE_COLUMNS_MAP = [
        'id',
        'reference_id',
        'name',
        'description',
        'expires_at',
        'openings',
        'c.name',
        'p.name',
    ];

    public static function generateDatatableSearchFilters(array $payload): array
    {
        $response = [];

        // Generate search param
        if (!empty($_GET['search']['value'])) {
            $response['search'] = trim($_GET['search']['value']);
        }

        return $response;
    }

    public static function generateDatatableOrderByFilters(array $payload): array
    {
        $orderBy = [];

        // Generate order by
        if (!empty($payload['order'][0]['column']) &&
            isset(self::DATATABLE_COLUMNS_MAP[$payload['order'][0]['column']])
        ) {
            $orderBy[] = [
                'column' => self::DATATABLE_COLUMNS_MAP[$payload['order'][0]['column']],
                'direction' => $payload['order'][0]['dir']
            ];
        }

        return $orderBy;
    }
}