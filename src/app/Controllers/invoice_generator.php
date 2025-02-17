<?php

require_once './vendor/tecnickcom/tcpdf/tcpdf.php';

class InvoiceGenerator
{
    public static function generateInvoice($reservation)
    {
        // Créer un objet TCPDF
        $pdf = new TCPDF();
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Chic & Chill');
        $pdf->SetTitle('Facture');
        $pdf->SetMargins(10, 10, 10);
        $pdf->AddPage();

        // Contenu de la facture
        $html = "
        <h1>Facture</h1>
        <p><strong>Client :</strong> {$reservation['customer_name']}</p>
        <p><strong>Email :</strong> {$reservation['email']}</p>
        <p><strong>Téléphone :</strong> {$reservation['phone']}</p>
        <p><strong>Réservation :</strong> " . ($reservation['type'] === 'event' ? 'Événement' : 'Pack') . "</p>
        <p><strong>Statut :</strong> {$reservation['status']}</p>
        <p><strong>Date :</strong> {$reservation['created_at']}</p>
        ";

        // Ajouter le HTML au PDF
        $pdf->writeHTML($html, true, false, true, false, '');

        // Enregistrer la facture
        $filePath = "./invoices/facture_{$reservation['id']}.pdf";
        $pdf->Output($filePath, 'F');

        return $filePath;
    }
}
?>
