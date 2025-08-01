<?php
require('fpdf/fpdf.php');

class PDF extends FPDF {
    function Header() {
        // Set title
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, 'Inventory Management Report', 0, 1, 'C');
        $this->Ln(5);
    }

    function Footer() {
        // Page number
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }

    function BasicTable($headers, $data) {
        // Table headers
        $this->SetFont('Arial', 'B', 10);
        foreach ($headers as $col) {
            $this->Cell(40, 7, $col, 1);
        }
        $this->Ln();

        // Table data
        $this->SetFont('Arial', '', 9);
        foreach ($data as $row) {
            foreach ($row as $col) {
                $this->Cell(40, 6, $col, 1);
            }
            $this->Ln();
        }
    }
}

$type = isset($_GET['report']) ? $_GET['report'] : 'product';
$headers = [];
$data = [];

include('connection.php');

// Example: PRODUCT REPORT
if ($type === 'product') {
    $headers = ['ID', 'Product Name', 'Stock', 'Created At'];
    $stmt = $conn->prepare("SELECT id, product_name, stock, created_at FROM products ORDER BY created_at DESC");
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($rows as $row) {
        $data[] = [
            $row['id'],
            $row['product_name'],
            $row['stock'],
            date('M d, Y', strtotime($row['created_at']))
        ];
    }
}

// Generate PDF
$pdf = new PDF();
$pdf->AddPage();
$pdf->BasicTable($headers, $data);

// Clean buffer and output
ob_end_clean();
$pdf->Output();
?>
