<?php
require_once __DIR__ . '/../../config/database.php';

$db = new Database();
$conn = $db->getConnection();
$format = $_GET['format'] ?? 'csv';

$result = $conn->query("SELECT id, produs, furnizor, cantitate, data_comanda FROM comenzi_furnizori");
$rows = [];
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $rows[] = $row;
}

if ($format === 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename=\"comenzi_furnizori.csv\"');
    $output = fopen('php://output', 'w');
    if (!empty($rows)) {
        fputcsv($output, array_keys($rows[0]));
        foreach ($rows as $row) {
            fputcsv($output, $row);
        }
    }
    fclose($output);
} elseif ($format === 'json') {
    header('Content-Type: application/json');
    header('Content-Disposition: attachment; filename=\"comenzi_furnizori.json\"');
    echo json_encode($rows, JSON_PRETTY_PRINT);
} elseif ($format === 'pdf') {
    require_once __DIR__ . '/../../../fpdf/fpdf.php';
    $pdf = new FPDF('L', 'mm', 'A4');
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 12);

    $widths = [15, 70, 70, 30, 40];

    // Header
    $i = 0;
    if (!empty($rows)) {
        foreach (array_keys($rows[0]) as $col) {
            $pdf->Cell($widths[$i++], 10, $col, 1);
        }
        $pdf->Ln();

        // Date
        $pdf->SetFont('Arial', '', 10);
        foreach ($rows as $row) {
            $i = 0;
            foreach ($row as $cell) {
                $pdf->Cell($widths[$i++], 10, mb_substr($cell, 0, 40), 1);
            }
            $pdf->Ln();
        }
    }
    $pdf->Output('D', 'comenzi_furnizori.pdf');
}
exit;
