<?php
require_once __DIR__ . '/../inc/utils.php';
$total = 0;
$items = $_POST['item'] ?? [];
$prices = $_POST['price'] ?? [];
$qtys = $_POST['qty'] ?? [];
$rows = [];
for($i=0;$i<count($items);$i++){
  if(trim($items[$i])==='') continue;
  $p = postf('price') ?? 0;
  $q = (float)($qtys[$i] ?? 0);
  $pr = (float)str_replace(',','.', $prices[$i]);
  $sub = $q * $pr;
  $total += $sub;
  $rows[] = [$items[$i], $q, $pr, $sub];
}
?>
<div class="card">
  <div class="card-h">
    <div>
      <h2>9) Orçamento da Obra</h2>
      <p>Informe materiais, quantidades e preços para obter o total geral.</p>
    </div>
    <span class="badge"><b>Total</b> automático</span>
  </div>
  <div class="card-b">
    <form method="post" action="index.php#orcamento">
      <table class="table">
        <tr><th>Item</th><th>Qtd</th><th>Preço</th></tr>
        <?php for($i=0;$i<8;$i++): ?>
        <tr>
          <td><input name="item[]" placeholder="Ex: Cimento 50kg"/></td>
          <td><input name="qty[]" placeholder="Ex: 10"/></td>
          <td><input name="price[]" placeholder="Ex: 45,90"/></td>
        </tr>
        <?php endfor; ?>
      </table>
      <div class="btns">
        <button class="primary" type="submit">Calcular Total</button>
      </div>
    </form>

    <?php if(count($rows)): ?>
      <hr class="soft"/>
      <div class="results">
        <?php foreach($rows as $r): ?>
          <div class="result-box">
            <h3><?php echo htmlspecialchars($r[0]); ?></h3>
            <div class="s"><?php echo nf($r[1],2); ?> × R$ <?php echo nf($r[2],2); ?></div>
            <div class="v">R$ <?php echo nf($r[3],2); ?></div>
          </div>
        <?php endforeach; ?>
        <div class="result-box">
          <h3>Total Geral</h3>
          <div class="v">R$ <?php echo nf($total,2); ?></div>
        </div>
      </div>
      <div class="btns">
        <a class="primary" href="export/pdf.php?title=Orçamento&data=<?php
          $lines=[];
          foreach($rows as $r){ $lines[] = urlencode($r[0].': R$ '.number_format($r[3],2,',','.')); }
          $lines[] = urlencode('TOTAL: R$ '.number_format($total,2,',','.'));
          echo implode('|',$lines);
        ?>">Exportar PDF</a>
      </div>
    <?php endif; ?>
  </div>
</div>
