<?php

namespace App\Infrastructure\DataExport\SkelirCSVExport;

use Symfony\Component\HttpFoundation\Response;

interface SkelirCsvExportInterface
{
    public function generate(array $data): Response;
}