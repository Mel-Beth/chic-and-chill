<?php

require_once './vendor/tecnickcom/tcpdf/tcpdf.php';

class InvoiceGenerator
{
    public static function generateInvoice($reservation)
    {
        // Créer un objet TCPDF
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Chic & Chill');
        $pdf->SetTitle('Facture');
        $pdf->SetMargins(10, 10, 10);
        $pdf->SetAutoPageBreak(true, 10);
        $pdf->AddPage();

        // Définir la police (Helvetica pour correspondre au style de la photo)
        $pdf->SetFont('helvetica', '', 10);

        // Informations de l'entreprise (En-tête à gauche) et client à droite
        $companyInfo = "
        <table cellpadding='2' cellspacing='0'>
            <tr>
                <td style='width:50%;'>
                    <span style='color: #ff0000; font-size: 12pt; font-weight: bold;'>Chic & Chill</span><br>
                    10 Rue Irénée Carré<br>
                    Charleville-Mézières
                </td>
                <td style='width:50%; text-align: right; vertical-align: top;'>
                    <strong>Client :</strong><br>
                    {$reservation['customer_name']}<br>
                    {$reservation['address']}<br>
                    {$reservation['phone']}<br>
                    {$reservation['email']}
                </td>
            </tr>
        </table>";

        // Titre "FACTURE" et informations de facture
        $invoiceInfo = "
        <div style='text-align: center; margin-top: 20px;'>
            <h1 style='font-size: 18pt; color: #000000; font-weight: bold; text-transform: uppercase;'>FACTURE</h1>
        </div>
        <table cellpadding='2' cellspacing='0'>
            <tr>
                <td style='width:50%;'>
                    <strong>Date de facture :</strong> " . date('d/m/Y') . "<br>
                    <strong>N° Client :</strong> {$reservation['id']}
                </td>
                <td style='width:50%; text-align: right;'>
                    page <strong>1</strong>
                </td>
            </tr>
        </table>
        <hr style='border: 1px solid #d3d3d3; margin-top: 5px; margin-bottom: 10px;'>";

        // Calculs des montants (total HT, TVA, TTC)
        $totalHT = 0;
        $totalTVA20 = 0;
        $totalTVA10 = 0;

        // Table de détails des services
        $html = "
        <table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse;'>
            <tr style='background-color: #d3d3d3; font-weight: bold;'>
                <th style='width: 40%; border: 1px solid #000000; padding: 5px;'>Description</th>
                <th style='width: 10%; border: 1px solid #000000; text-align: center; padding: 5px;'>Quantité</th>
                <th style='width: 10%; border: 1px solid #000000; text-align: center; padding: 5px;'>Unité</th>
                <th style='width: 15%; border: 1px solid #000000; text-align: right; padding: 5px;'>Prix unitaire HT</th>
                <th style='width: 15%; border: 1px solid #000000; text-align: right; padding: 5px;'>Total HT</th>
                <th style='width: 10%; border: 1px solid #000000; text-align: right; padding: 5px;'>TVA</th>
            </tr>";

        // Ajouter les services avec vos données
        if ($reservation['services']) {
            $services = explode(', ', $reservation['services']);
            foreach ($services as $index => $service) {
                $quantity = 1; // Quantité par défaut (ajustez si vous avez des données spécifiques)
                $unit = ($index % 3 == 0) ? 'h.' : (($index % 3 == 1) ? 'pce.' : 'stère'); // Variation des unités comme dans la photo
                $servicePrice = 100; // Prix fictif (comme dans votre code initial)
                $totalServiceHT = $quantity * $servicePrice;
                $tvaRate = ($index % 2 == 0) ? 20 : 10; // Alternance entre 20% et 10% comme dans la photo
                $totalHT += $totalServiceHT;

                $html .= "
                <tr>
                    <td style='border: 1px solid #000000; padding: 5px;'>{$service}</td>
                    <td style='border: 1px solid #000000; text-align: center; padding: 5px;'>{$quantity}</td>
                    <td style='border: 1px solid #000000; text-align: center; padding: 5px;'>{$unit}</td>
                    <td style='border: 1px solid #000000; text-align: right; padding: 5px;'>" . number_format($servicePrice, 2, ',', ' ') . " €</td>
                    <td style='border: 1px solid #000000; text-align: right; padding: 5px;'>" . number_format($totalServiceHT, 2, ',', ' ') . " €</td>
                    <td style='border: 1px solid #000000; text-align: right; padding: 5px;'>{$tvaRate} %</td>
                </tr>";

                if ($tvaRate == 20) {
                    $totalTVA20 += $totalServiceHT * 0.2;
                } else {
                    $totalTVA10 += $totalServiceHT * 0.1;
                }
            }
        }

        $html .= "</table>";

        // Totaux en bas de la facture (style exact de la photo)
        $totalTVA = $totalTVA20 + $totalTVA10;
        $totalTTC = $totalHT + $totalTVA;

        $totals = "
        <br><br>
        <table style='width:100%;'>
            <tr>
                <td style='width:60%;'></td>
                <td style='width:40%;'>
                    <table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse;'>
                        <tr>
                            <td style='border: 1px solid #000000; padding: 5px;'><strong>Total HT</strong></td>
                            <td style='border: 1px solid #000000; text-align: right; padding: 5px;'>" . number_format($totalHT, 2, ',', ' ') . " €</td>
                        </tr>
                        <tr>
                            <td style='border: 1px solid #000000; padding: 5px;'>TVA 20 %</td>
                            <td style='border: 1px solid #000000; text-align: right; padding: 5px;'>" . number_format($totalTVA20, 2, ',', ' ') . " €</td>
                        </tr>
                        <tr>
                            <td style='border: 1px solid #000000; padding: 5px;'>TVA 10 %</td>
                            <td style='border: 1px solid #000000; text-align: right; padding: 5px;'>" . number_format($totalTVA10, 2, ',', ' ') . " €</td>
                        </tr>
                        <tr>
                            <td style='border: 1px solid #000000; padding: 5px;'><strong>Total TTC</strong></td>
                            <td style='border: 1px solid #000000; text-align: right; padding: 5px;'><strong>" . number_format($totalTTC, 2, ',', ' ') . " €</strong></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>";

        // Conditions de paiement (style exact de la photo)
        $paymentConditions = "
        <br><br>
        <p><strong>Conditions de paiement :</strong> 30 % à la commande, paiement à réception de facture</p>
        <p><strong>Mode de paiement :</strong> Par virement ou chèque</p>
        <p>Nous vous remercions de votre confiance.</p>
        <hr style='border: 1px solid #000000; margin-top: 5px; margin-bottom: 10px;'>";

        // Pied de page (style exact de la photo)
        $footer = "
        <table cellpadding='2' cellspacing='0'>
            <tr>
                <td style='width:50%;'>
                    <strong>Chic & Chill</strong><br>
                    10 Rue Irénée Carré<br>
                    Charleville-Mézières
                </td>
                <td style='width:50%;'>
                    <strong>Détails bancaires :</strong><br>
                    IBAN : FR76 1234 5678 9012 3456 7890 12<br>
                    BIC : ABCDEF12XXX<br>
                    Siret : 123 456 789 00012<br>
                    Code APE : 1234 Z<br>
                    N° TVA Intracom. : FR 12 345 678 900
                </td>
            </tr>
        </table>";

        // Ajouter le contenu au PDF
        $pdf->writeHTML($companyInfo . $invoiceInfo . $html . $totals . $paymentConditions . $footer, true, false, true, false, '');

        // Enregistrer la facture en PDF
        $filePath = __DIR__ . "/../../../assets/documents/invoices/facture_{$reservation['id']}.pdf";
        $pdf->Output($filePath, 'F');

        return $filePath;
    }
}
?>