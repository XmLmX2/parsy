<?php
/**
 * User: Marius Mertoiu
 * Date: 11/04/2022 10:07
 * Email: marius.mertoiu@gmail.com
 */

namespace Parsy\Controller;

use DateTime;
use Exception;
use Parsy\Repository\JobRepository;
use Parsy\Service\JobListService;

class JobController
{
    public function list()
    {
        if (!empty($_GET['ajax_action']) && $_GET['ajax_action'] === 'load_table') {
            $filters = JobListService::generateDatatableSearchFilters($_GET);
            $orderBy = JobListService::generateDatatableOrderByFilters($_GET);

            $jobRepository = new JobRepository();
            $jobs = $jobRepository->findBy($filters, false, $orderBy);

            $data = [];

            foreach ($jobs as $job) {
                $expireInfo = $job['expires_at'];

                try {
                    $expiresAt = new DateTime($job['expires_at']);

                    $expireInfo = $expiresAt->format('d-m-Y');

                    if ($expiresAt < (new DateTime())) {
                        $expireInfo .= ' <span class="badge bg-warning">expired</span>';
                    }

                } catch (Exception $e) {
                    continue;
                }

                $data[] = [
                    $job['id'],
                    $job['reference_id'],
                    $job['name'],
                    $job['description'],
                    $expireInfo,
                    $job['openings'],
                    $job['company_name'],
                    $job['profession_name'],
                ];
            }

            header('Content-Type: application/json');

            echo json_encode([
                'data' => $data,
                'recordsTotal' => count($data),
                'recordsFiltered' => count($data),
            ]);

            die;
        }

        $pageTitle = 'Jobs list';

        include_once $_ENV['VIEW_PATH'] . 'job/list.php';
    }
}