<?php
require_once __DIR__ . '/../inc/utils.php';

$calc = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['calc'] ?? '') === 'tinta') {
  $larg = postf('larg', 0);
  $alt  = postf('alt', 0);
  $aberturas = postf('aberturas', 0);
  $dem = max(1, posti('dem', 2));
  $rendimento = max(1, postf('rendimento', 10)); // m2/L
  $perda = clampf(postf('perda', 10), 0, 50);

  $area = max(0, ($larg * $alt) - $aberturas);
  $litros = ($area / $rendimento) * $dem;
  $litros_total = $litros * wasteFactor($perda);

  // sugestão de latas
  $latas = [18, 3.6, 0.9];
  $rest = $litros_total;
  $sug = [];
  foreach ($latas as $cap){
    $q = (int)floor($rest / $cap);
    if ($q > 0){
      $sug[] = ['cap'=>$cap,'q'=>$q];
      $rest -= $q*$cap;
    }
  }
  // se sobrou, completa com a menor combinação simples: 1 lata 18/3.6/0.9
  if ($rest > 0.01){
    // escolhe o menor excesso
    $best = null;
    foreach ($latas as $cap){
      $q = (int)ceil($rest / $cap);
      $total = $q*$cap;
      $excesso = $total - $rest;
      if ($best === null || $excesso < $best['excesso']){
        $best = ['cap'=>$cap,'q'=>$q,'excesso'=>$excesso];
      }
    }
    if ($best) $sug[] = ['cap'=>$best['cap'], 'q'=>$best['q']];
  }

  $calc = [
    'area' => $area,
    'litros' => $litros_total,
    'dem' => $dem,
    'rendimento' => $rendimento,
    'perda' => $perda,
    'sug' => $sug
  ];
}
?>
<div class="card">
  <div class="card-h">
    <div>
      <h2>1) Tinta para parede / muro</h2>
      <p>Calcule a área (descontando portas/janelas) e estime litros de tinta por demão.</p>
    </div>
    <span class="badge"><b>Resultado</b> em litros</span>
  </div>
  <div class="card-b">
    <form method="post" action="index.php#tinta">
      <input type="hidden" name="calc" value="tinta"/>
      <div class="grid-2">
        <div class="field">
          <label>Largura do muro (m)</label>
          <input name="larg" inputmode="decimal" placeholder="Ex: 6" value="<?php echo htmlspecialchars($_POST['larg'] ?? ''); ?>"/>
          <div class="hint">Medida horizontal.</div>
        </div>
        <div class="field">
          <label>Altura do muro (m)</label>
          <input name="alt" inputmode="decimal" placeholder="Ex: 2.2" value="<?php echo htmlspecialchars($_POST['alt'] ?? ''); ?>"/>
          <div class="hint">Medida vertical.</div>
        </div>

        <div class="field">
          <label>Área de aberturas (m²)</label>
          <input name="aberturas" inputmode="decimal" placeholder="Ex: 1.8" value="<?php echo htmlspecialchars($_POST['aberturas'] ?? '0'); ?>"/>
          <div class="hint">Portas/janelas a descontar. Se não tiver, deixe 0.</div>
        </div>
        <div class="field">
          <label>Demãos</label>
          <select name="dem">
            <?php $demSel = (int)($_POST['dem'] ?? 2); for($i=1;$i<=4;$i++): ?>
              <option value="<?php echo $i; ?>" <?php echo $demSel===$i?'selected':''; ?>><?php echo $i; ?></option>
            <?php endfor; ?>
          </select>
          <div class="hint">Normal: 2 demãos (pode ser 3 em cores fortes/reboco novo).</div>
        </div>

        <div class="field">
          <label>Rendimento (m² por litro)</label>
          <input name="rendimento" inputmode="decimal" placeholder="Ex: 10" value="<?php echo htmlspecialchars($_POST['rendimento'] ?? '10'); ?>"/>
          <div class="hint">Olhe na lata: normalmente 8–12 m²/L (varia por tinta/superfície).</div>
        </div>
        <div class="field">
          <label>Perdas / sobra (%)</label>
          <input name="perda" inputmode="decimal" placeholder="Ex: 10" value="<?php echo htmlspecialchars($_POST['perda'] ?? '10'); ?>"/>
          <div class="hint">Sugestão: 10% (até 15% em superfície porosa).</div>
        </div>
      </div>

      <div class="btns">
        <button class="primary" type="submit">Calcular</button>
        <button class="small" type="button" onclick="location.href='index.php#tinta'">Limpar</button>
      </div>
    </form>

    <?php if ($calc): ?>
      <div class="note"></div>
      <div class="results">
        <div class="result-box">
          <h3>Área total a pintar</h3>
          <div class="v"><?php echo nf($calc['area'], 2); ?> m²</div>
          <div class="s">Área = largura × altura − aberturas.</div>
        </div>
        <div class="result-box">
          <h3>Tinta estimada</h3>
          <div class="v"><?php echo nf($calc['litros'], 2); ?> L</div>
          <div class="s"><?php echo (int)$calc['dem']; ?> demão(s), rendimento <?php echo nf($calc['rendimento'], 2); ?> m²/L, perdas <?php echo nf($calc['perda'], 0); ?>%.</div>
        </div>
        <div class="result-box">
          <h3>Sugestão de latas</h3>
          <div class="s">
            <?php if (count($calc['sug'])===0): ?>
              Informe medidas para gerar a sugestão.
            <?php else: ?>
              <?php
                $parts = [];
                foreach ($calc['sug'] as $it){
                  $parts[] = $it['q'] . "× " . nf($it['cap'], 1) . "L";
                }
                echo implode(" + ", $parts);
              ?>
              <div class="hint">Combinação simples (18L / 3,6L / 0,9L). Você pode ajustar conforme o que vende na sua região.</div>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <div class="footer">
        <span class="badge"><span class="kbd">Dica</span> Para reboco novo, selador/fundo pode reduzir consumo e melhorar acabamento.</span>
      </div>
    <?php endif; ?>
  </div>
</div>
