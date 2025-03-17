<?php

namespace Controllers;

use Models\ExportModel;

class ExportController
{
    public function exportData($type)
    {
        $exportModel = new ExportModel();

        if (!in_array($type, ['reservations', 'users', 'payments'])) {
            include 'app/Views/404.php';
            return;
        }

        $data = $exportModel->getDataForExport($type);

        // Définir l'en-tête HTTP pour le téléchargement CSV
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="export_' . $type . '.csv"');

        $output = fopen('php://output', 'w');

        foreach ($data as $row) {
            fputcsv($output, $row);
        }

        fclose($output);
        exit();
    }
}
