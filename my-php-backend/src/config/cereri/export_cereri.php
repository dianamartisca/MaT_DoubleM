<?php
require_once __DIR__ . '/../database.php';

$db = new Database();
$conn = $db->getConnection();
$format = $_GET['format'] ?? 'csv';
$result = $conn->query("SELECT name, email, problem_type, date_requested, description, images, response, status FROM requests");
$rows = [];
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $rows[] = $row;
}

if ($format === 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="cereri.csv"');
    $output = fopen('php://output', 'w');
    fputcsv($output, array_keys($rows[0]));
    foreach ($rows as $row) {
        fputcsv($output, $row);
    }
    fclose($output);
} elseif ($format === 'json') {
    header('Content-Type: application/json');
    header('Content-Disposition: attachment; filename="cereri.json"');
    echo json_encode($rows, JSON_PRETTY_PRINT);
} elseif ($format === 'pdf') {
    require_once __DIR__ . '/../../../fpdf/fpdf.php';
    $pdf = new FPDF('L', 'mm', 'A4'); // Landscape
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 12);

    $widths = [30, 30, 30, 38, 40, 50, 40, 25]; 

    // Header
    $i = 0;
    foreach (array_keys($rows[0]) as $col) {
        $pdf->Cell($widths[$i++], 10, $col, 1);
    }
    $pdf->Ln();

    // Date
    $pdf->SetFont('Arial', '', 10);
    foreach ($rows as $row) {
        $i = 0;
        foreach ($row as $cell) {
            $pdf->Cell($widths[$i++], 10, mb_substr($cell, 0, 30), 1);
        }
        $pdf->Ln();
    }
    $pdf->Output('D', 'cereri.pdf');
}
exit;
