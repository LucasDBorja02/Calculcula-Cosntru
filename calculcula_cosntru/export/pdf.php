<?php
require_once __DIR__ . '/../inc/fpdf.php';
$title = $_GET['title'] ?? 'CalculCula Cosntru';
$data = $_GET['data'] ?? '';
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,10,$title);
$pdf->Ln(8);
foreach (explode('|', $data) as $line){
  $pdf->Cell(0,8,urldecode($line));
}
$pdf->Output('I','calculo.pdf');
