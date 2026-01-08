<?php
// Minimal FPDF (single-file)
class FPDF{
  protected $content='';
  function AddPage(){ $this->content=''; }
  function SetFont($f,$s='',$z=12){}
  function Cell($w,$h,$txt,$b=0,$ln=1){ $this->content .= $txt."\n"; }
  function Ln($h=5){ $this->content .= "\n"; }
  function Output($dest='I',$name='doc.pdf'){
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="'.$name.'"');
    // Very simple PDF wrapper
    $text = str_replace(['(',')'], ['\(','\)'], $this->content);
    echo "%PDF-1.4
1 0 obj<<>>endobj
2 0 obj<< /Length 44 >>stream
BT /F1 12 Tf 72 720 Td (".$text.") Tj ET
endstream endobj
3 0 obj<< /Type /Page /Parent 4 0 R /Contents 2 0 R >>endobj
4 0 obj<< /Type /Pages /Kids [3 0 R] /Count 1 >>endobj
5 0 obj<< /Type /Catalog /Pages 4 0 R >>endobj
xref
0 6
0000000000 65535 f 
0000000010 00000 n 
0000000034 00000 n 
0000000120 00000 n 
0000000180 00000 n 
0000000245 00000 n 
trailer<< /Size 6 /Root 5 0 R >>
startxref
300
%%EOF";
    exit;
  }
}
?>