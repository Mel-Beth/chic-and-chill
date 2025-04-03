<?php
namespace Controllers;

require_once './vendor/tecnickcom/tcpdf/tcpdf.php';

class InvoiceGenerator
{
    public static function generateInvoice($reservation)
    {
        // Créer un objet TCPDF
        $pdf = new \TCPDF();
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Chic & Chill');
        $pdf->SetTitle('Facture');
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(true, 15);
        $pdf->AddPage();

        // Définir la police de base
        $pdf->SetFont('helvetica', '', 12);

        // En-tête avec logo et informations de l'entreprise
        $logoPath = __DIR__ . '/../../../assets/images/logo_magasin-chic.png';
        if (file_exists($logoPath)) {
            $pdf->Image($logoPath, 15, 15, 15, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        } else {
            $pdf->SetFont('helvetica', 'B', 14);
            $pdf->SetXY(15, 15);
            $pdf->Cell(40, 10, 'Chic & Chill', 0, 1, 'L');
        }

        // Informations de l'entreprise et du client
        $pdf->SetFont('helvetica', '', 10);
        $companyInfo = "
        <table cellpadding='5' cellspacing='0'>
            <tr>
                <td style='width:50%;'>
                    <strong style='font-size: 12pt;'>Chic & Chill</strong><br>
                    10 Rue Irénée Carré<br>
                    08000 Charleville-Mézières
                </td>
                <td style='width:50%; text-align: right;'>
                    <strong style='font-size: 12pt;'>Client :</strong><br>
                    " . htmlspecialchars($reservation['customer_name']) . "<br>" .
                    ($reservation['customer_type'] === 'entreprise' && !empty($reservation['company_name']) ? htmlspecialchars($reservation['company_name']) . "<br>" : "") .
                    ($reservation['customer_type'] === 'entreprise' && !empty($reservation['siret']) ? "SIRET : " . htmlspecialchars($reservation['siret']) . "<br>" : "") .
                    ($reservation['customer_type'] === 'entreprise' && !empty($reservation['address']) ? htmlspecialchars($reservation['address']) . "<br>" : "") .
                    htmlspecialchars($reservation['phone']) . "<br>" .
                    htmlspecialchars($reservation['email']) . "
                </td>
            </tr>
        </table>";

        // Ajouter l'en-tête et une ligne de séparation
        $pdf->writeHTML($companyInfo, true, false, true, false, '');
        $y = $pdf->GetY();
        $pdf->Line(15, $y, 195, $y, array('width' => 0.5, 'color' => array(0, 0, 0)));

        // Titre "FACTURE"
        $pdf->SetFont('helvetica', 'B', 24);
        $pdf->Ln(10);
        $y = $pdf->GetY();
        $pdf->Line(15, $y, 195, $y, array('width' => 0.5, 'color' => array(0, 0, 0)));
        $pdf->Ln(2);
        $pdf->Cell(0, 10, 'FACTURE', 0, 1, 'C');
        $y = $pdf->GetY();
        $pdf->Line(15, $y, 195, $y, array('width' => 0.5, 'color' => array(0, 0, 0)));
        $pdf->Ln(5);

        // Informations de la facture
        $pdf->SetFont('helvetica', '', 10);
        $invoiceInfo = "
        <table cellpadding='5' cellspacing='0'>
            <tr>
                <td style='width:50%;'>
                    <strong>Date de facture :</strong> " . date('d/m/Y') . "<br>
                    <strong>N° Client :</strong> " . substr(md5($reservation['customer_name'] . $reservation['created_at']), 0, 8) . "
                </td>
                <td style='width:50%; text-align: right;'>
                    Page <strong>1</strong>
                </td>
            </tr>
        </table>";

        $pdf->writeHTML($invoiceInfo, true, false, true, false, '');

        // Calculs des montants (total HT, TVA, TTC)
        $totalHT = 0;
        $totalTVA20 = 0;
        $totalTVA10 = 0;

        // Préparer les données du tableau principal
        $rows = [];
        $quantity = 1;
        $unit = "réservation";

        if ($reservation['type'] === 'event') {
            $description = "Événement - {$reservation['event_type']}";
            if (!empty($reservation['participants'])) {
                $description .= " (Participants : {$reservation['participants']})";
            }
            $reservationPrice = "À définir";
            $totalReservationHT = 0;
            $tvaRate = "-";
        } else {
            $description = "Pack - {$reservation['pack_title']}";
            $reservationPrice = $reservation['pack_price'];
            $totalReservationHT = $quantity * $reservationPrice;
            $tvaRate = 20;
            $totalHT += $totalReservationHT;
            $totalTVA20 += $totalReservationHT * 0.2;
        }

        $rows[] = [
            'description' => $description,
            'quantity' => $quantity,
            'unit' => $unit,
            'price' => (is_numeric($reservationPrice) ? number_format($reservationPrice, 2, ',', ' ') . " €" : $reservationPrice),
            'total' => (is_numeric($totalReservationHT) && $totalReservationHT > 0 ? number_format($totalReservationHT, 2, ',', ' ') . " €" : "-"),
            'tva' => $tvaRate . " %"
        ];

        // Ajouter les services
        if (!empty($reservation['services']) && $reservation['services'] !== 'Aucun') {
            $services = explode(', ', $reservation['services']);
            foreach ($services as $index => $service) {
                $quantity = 1;
                $unit = ($index % 3 == 0) ? 'h.' : (($index % 3 == 1) ? 'pce.' : 'stère');
                $servicePrice = 100;
                $totalServiceHT = $quantity * $servicePrice;
                $tvaRate = ($index % 2 == 0) ? 20 : 10;
                $totalHT += $totalServiceHT;

                $rows[] = [
                    'description' => "Service - {$service}",
                    'quantity' => $quantity,
                    'unit' => $unit,
                    'price' => number_format($servicePrice, 2, ',', ' ') . " €",
                    'total' => number_format($totalServiceHT, 2, ',', ' ') . " €",
                    'tva' => $tvaRate . " %"
                ];

                if ($tvaRate == 20) {
                    $totalTVA20 += $totalServiceHT * 0.2;
                } else {
                    $totalTVA10 += $totalServiceHT * 0.1;
                }
            }
        }

        // Ajouter les commentaires
        if (!empty($reservation['comments']) && $reservation['comments'] !== 'Aucun') {
            $rows[] = [
                'description' => "Commentaires : {$reservation['comments']}",
                'quantity' => '-',
                'unit' => '-',
                'price' => '-',
                'total' => '-',
                'tva' => '-'
            ];
        }

        // Dessiner le tableau principal manuellement avec Cell
        $pdf->Ln(10);
        $pdf->SetFont('helvetica', 'B', 10);
        $widths = [72, 18, 18, 27, 27, 18]; // Largeurs des colonnes (40%, 10%, 10%, 15%, 15%, 10% de 180mm)
        $lineHeight = 6; // Hauteur des lignes (déjà définie à 6mm)

        // Définir une épaisseur de ligne plus fine pour les bordures
        $pdf->SetLineWidth(0.1); // Réduction de l'épaisseur des bordures à 0.1mm

        // En-tête du tableau principal
        $pdf->SetFillColor(200, 200, 200); // Gris moyen (#C8C8C8)
        $pdf->Cell($widths[0], $lineHeight, 'Description', 1, 0, 'L', true);
        $pdf->Cell($widths[1], $lineHeight, 'Quantité', 1, 0, 'C', true);
        $pdf->Cell($widths[2], $lineHeight, 'Unité', 1, 0, 'C', true);
        $pdf->Cell($widths[3], $lineHeight, 'Prix unitaire HT', 1, 0, 'R', true);
        $pdf->Cell($widths[4], $lineHeight, 'Total HT', 1, 0, 'R', true);
        $pdf->Cell($widths[5], $lineHeight, 'TVA', 1, 1, 'R', true);

        // Lignes du tableau principal
        $pdf->SetFont('helvetica', '', 10);
        foreach ($rows as $index => $row) {
            // Alternance des couleurs pour les lignes
            if ($index % 2 == 0) {
                $pdf->SetFillColor(240, 240, 240); // Gris clair (#F0F0F0)
            } else {
                $pdf->SetFillColor(220, 220, 220); // Gris plus foncé (#DCDCDC)
            }

            $pdf->Cell($widths[0], $lineHeight, $row['description'], 1, 0, 'L', true);
            $pdf->Cell($widths[1], $lineHeight, $row['quantity'], 1, 0, 'C', true);
            $pdf->Cell($widths[2], $lineHeight, $row['unit'], 1, 0, 'C', true);
            $pdf->Cell($widths[3], $lineHeight, $row['price'], 1, 0, 'R', true);
            $pdf->Cell($widths[4], $lineHeight, $row['total'], 1, 0, 'R', true);
            $pdf->Cell($widths[5], $lineHeight, $row['tva'], 1, 1, 'R', true);
        }

        // Totaux
        $totalTVA = $totalTVA20 + $totalTVA10;
        $totalTTC = $totalHT + $totalTVA;

        if ($totalHT > 0) {
            $totalsRows = [
                ['label' => 'Total HT', 'value' => number_format($totalHT, 2, ',', ' ') . " €"],
                ['label' => 'TVA 20 %', 'value' => number_format($totalTVA20, 2, ',', ' ') . " €"],
                ['label' => 'TVA 10 %', 'value' => number_format($totalTVA10, 2, ',', ' ') . " €"],
                ['label' => 'Total TTC', 'value' => number_format($totalTTC, 2, ',', ' ') . " €"]
            ];

            // Dessiner le tableau des totaux manuellement avec Cell
            $pdf->Ln(10);
            $pdf->SetX(135); // Positionner à droite (180mm - 45mm = 135mm)
            $totalWidths = [30, 30]; // Largeurs des colonnes (50% de 60mm chacune)

            // Lignes du tableau des totaux
            $pdf->SetFont('helvetica', 'B', 10);
            foreach ($totalsRows as $index => $row) {
                // Alternance des couleurs pour les lignes
                if ($index % 2 == 0) {
                    $pdf->SetFillColor(240, 240, 240); // Gris clair (#F0F0F0)
                } else {
                    $pdf->SetFillColor(220, 220, 220); // Gris plus foncé (#DCDCDC)
                }

                $pdf->SetX(135); // Repositionner à droite pour chaque ligne
                $pdf->Cell($totalWidths[0], $lineHeight, $row['label'], 1, 0, 'L', true);
                $pdf->Cell($totalWidths[1], $lineHeight, $row['value'], 1, 1, 'R', true);
            }
        } else {
            $pdf->Ln(10);
            $pdf->SetFont('helvetica', 'I', 10);
            $pdf->SetX(135);
            $pdf->Cell(60, $lineHeight, 'Montant total à définir après accord.', 0, 1, 'R');
        }

        // Conditions de paiement
        $pdf->Ln(10);
        $pdf->SetFont('helvetica', '', 10);
        $paymentConditions = "
        <div style='font-size: 10pt;'>
            <p><strong>Conditions de paiement :</strong> 30 % à la commande, paiement à réception de facture</p>
            <p><strong>Mode de paiement :</strong> Par virement ou chèque</p>
            <p><strong>Date de réservation :</strong> " . date('d/m/Y', strtotime($reservation['created_at'])) . "</p>
            <p style='font-style: italic;'>Nous vous remercions de votre confiance.</p>
        </div>";

        // Ajouter une ligne de séparation avant le pied de page
        $pdf->writeHTML($paymentConditions, true, false, true, false, '');
        $y = $pdf->GetY();
        $pdf->Line(15, $y, 195, $y, array('width' => 0.5, 'color' => array(0, 0, 0)));

        // Pied de page
        $pdf->Ln(5);
        $footer = "
        <table cellpadding='5' cellspacing='0'>
            <tr>
                <td style='width:50%; font-size: 9pt;'>
                    <strong>Chic & Chill</strong><br>
                    10 Rue Irénée Carré<br>
                    08000 Charleville-Mézières
                </td>
                <td style='width:50%; font-size: 9pt;'>
                    <strong>Détails bancaires :</strong><br>
                    IBAN : FR76 1234 5678 9012 3456 7890 12<br>
                    BIC : ABCDEF12XXX<br>
                    Siret : 123 456 789 00012<br>
                    Code APE : 1234 Z<br>
                    N° TVA Intracom. : FR 12 345 678 900
                </td>
            </tr>
        </table>";

        // Ajouter le reste du contenu au PDF
        $pdf->writeHTML($footer, true, false, true, false, '');

        // Enregistrer la facture en PDF
        $filePath = __DIR__ . "/../../../assets/documents/invoices/facture_" . substr(md5($reservation['customer_name'] . $reservation['created_at']), 0, 8) . ".pdf";
        $pdf->Output($filePath, 'F');

        return $filePath;
    }
}