<?php

namespace Controllers;

use Models\ExportModel;
use Models\DashboardModel;

class ExportController
{
    public function exportData($type)
    {
        $exportModel = new ExportModel();

        // Ajout du type 'dashboard' aux types valides
        if (!in_array($type, ['reservations', 'users', 'payments', 'dashboard'])) {
            $code_erreur = 404;
            $description_erreur = "Oups... La page que vous cherchez n'existe pas.";
            include('src/app/Views/erreur.php');
            return;
        }

        // Si type est 'dashboard', on utilise DashboardModel
        if ($type === 'dashboard') {
            $dashboardModel = new DashboardModel();
            $data = $this->formatDashboardDataForExport($dashboardModel->getDashboardStats());
        } else {
            $data = $exportModel->getDataForExport($type);
        }

        // Définir l'en-tête HTTP pour le téléchargement CSV
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="export_' . $type . '_' . date('Y-m-d') . '.csv"');

        $output = fopen('php://output', 'w');

        foreach ($data as $row) {
            fputcsv($output, $row);
        }

        fclose($output);
        exit();
    }

    // Méthode pour formater les données du dashboard en tableau CSV
    public function formatDashboardDataForExport($stats)
    {
        $data = [
            ['Statistique', 'Valeur'],
            ['Nombre de messages', $stats['messages_count'] ?? 0],
            ['Événements actifs', $stats['active_events'] ?? 0],
            ['Total événements', $stats['total_events'] ?? 0],
            ['Réservations en attente', $stats['pending_reservations'] ?? 0],
            ['Abonnés newsletter', $stats['subscribers_count'] ?? 0],
            ['Mois des réservations', implode(',', $stats['reservation_months'] ?? [])],
            ['Nombre de réservations', implode(',', $stats['reservation_counts'] ?? [])],
            ['Labels des packs', implode(',', $stats['packs_labels'] ?? [])],
            ['Nombre de packs', implode(',', $stats['packs_counts'] ?? [])],
            ['Sources des messages', implode(',', $stats['message_sources']['labels'] ?? [])],
            ['Nombre par source', implode(',', $stats['message_sources']['counts'] ?? [])],
        ];

        return $data;
    }
}
