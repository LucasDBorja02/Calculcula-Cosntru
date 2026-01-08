<?php
require_once __DIR__ . '/../inc/utils.php';

$calc = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['calc'] ?? '') === 'concreto') {
  $comp = postf('comp', 0);
  $larg = postf('clarg', 0);
  $esp_cm = postf('esp', 0); // cm
  $perda = clampf(postf('cperda', 8), 0, 25);

  $esp_m = $esp_cm / 100.0;
  $volume = max(0, $comp * $larg * $esp_m); // m3 concreto
  $volume_total = $volume * wasteFactor($perda);

  $traco = $_POST['traco'] ?? '1:2:3'; // cimento:areia:brita
  $map = [
    '1:2:3' => [1,2,3],
    '1:2:4' => [1,2,4],
    '1:1.5:3' => [1,1.5,3]
  ];
  $partsArr = $map[$traco] ?? [1,2,3];
  $sumParts = array_sum($partsArr);

  // Estimativa por método de volumes:
  // volume seco ≈ 1,54 × volume úmido (concreto)
  $fatorSeco = 1.54;
  $volSeco = $volume_total * $fatorSeco;

  $cimento_m3 = $volSeco * ($partsArr[0] / $sumParts);
  $areia_m3   = $volSeco * ($partsArr[1] / $sumParts);
  $brita_m3   = $volSeco * ($partsArr[2] / $sumParts);

  $densCimento = 1440; // kg/m3
  $cimento_kg = $cimento_m3 * $densCimento;
  $sacos50 = $cimento_kg / 50.0;

  // água (aprox): relação água/cimento 0,5 em massa
  $agua_L = $cimento_kg * 0.5; // 1kg ~ 1L

  $calc = [
    'volume'=>$volume,
    'volume_total'=>$volume_total,
    'traco'=>$traco,
    'sacos50'=>$sacos50,
    'areia_m3'=>$areia_m3,
    'brita_m3'=>$brita_m3,
    'agua_L'=>$agua_L,
    'perda'=>$perda
  ];
}
?>
<div class="card">
  <div class="card-h">
    <div>
      <h2>3) Concreto (laje/contrapiso/calçada)</h2>
      <p>Calcule volume e estime cimento/areia/brita com base em um traço típico.</p>
    </div>
    <span class="badge"><b>Resultado</b> em m³ e sacos</span>
  </div>
  <div class="card-b">
    <form method="post" action="index.php#concreto">
      <input type="hidden" name="calc" value="concreto"/>
      <div class="grid-2">
        <div class="field">
          <label>Comprimento (m)</label>
          <input name="comp" inputmode="decimal" placeholder="Ex: 5" value="<?php echo htmlspecialchars($_POST['comp'] ?? ''); ?>"/>
        </div>
        <div class="field">
          <label>Largura (m)</label>
          <input name="clarg" inputmode="decimal" placeholder="Ex: 3" value="<?php echo htmlspecialchars($_POST['clarg'] ?? ''); ?>"/>
        </div>
        <div class="field">
          <label>Espessura (cm)</label>
          <input name="esp" inputmode="decimal" placeholder="Ex: 8" value="<?php echo htmlspecialchars($_POST['esp'] ?? ''); ?>"/>
          <div class="hint">Contrapiso comum: 4–8cm. Calçada: 8–12cm (depende do uso).</div>
        </div>
        <div class="field">
          <label>Traço (cimento:areia:brita)</label>
          <select name="traco">
            <?php $sel = $_POST['traco'] ?? '1:2:3'; ?>
            <option value="1:2:3" <?php echo $sel==='1:2:3'?'selected':''; ?>>1:2:3 (geral / estrutural leve)</option>
            <option value="1:2:4" <?php echo $sel==='1:2:4'?'selected':''; ?>>1:2:4 (mais econômico)</option>
            <option value="1:1.5:3" <?php echo $sel==='1:1.5:3'?'selected':''; ?>>1:1,5:3 (mais rico)</option>
          </select>
          <div class="hint">Estimativa. Para projeto estrutural, siga engenheiro e normas.</div>
        </div>
        <div class="field">
          <label>Perdas / sobra (%)</label>
          <input name="cperda" inputmode="decimal" placeholder="Ex: 8" value="<?php echo htmlspecialchars($_POST['cperda'] ?? '8'); ?>"/>
          <div class="hint">Sugestão: 5–10%.</div>
        </div>
        <div class="field">
          <label>Observação</label>
          <input disabled value="Água estimada com a/c ≈ 0,5 (ajuste no canteiro)"/>
          <div class="hint">A quantidade de água depende da umidade da areia e trabalhabilidade.</div>
        </div>
      </div>

      <div class="btns">
        <button class="primary" type="submit">Calcular</button>
        <button class="small" type="button" onclick="location.href='index.php#concreto'">Limpar</button>
      </div>
    </form>

    <?php if ($calc): ?>
      <div class="note"></div>
      <div class="results">
        <div class="result-box">
          <h3>Volume geométrico</h3>
          <div class="v"><?php echo nf($calc['volume'], 3); ?> m³</div>
          <div class="s">V = comprimento × largura × espessura.</div>
        </div>
        <div class="result-box">
          <h3>Volume com perdas</h3>
          <div class="v"><?php echo nf($calc['volume_total'], 3); ?> m³</div>
          <div class="s">Perdas: <?php echo nf($calc['perda'], 0); ?>%.</div>
        </div>
        <div class="result-box">
          <h3>Cimento</h3>
          <div class="v"><?php echo nf($calc['sacos50'], 1); ?> sacos (50kg)</div>
          <div class="s">Estimativa baseada em volume seco (fator 1,54).</div>
        </div>
        <div class="result-box">
          <h3>Areia / Brita / Água</h3>
          <div class="s">
            Areia: <?php echo nf($calc['areia_m3'], 3); ?> m³<br/>
            Brita: <?php echo nf($calc['brita_m3'], 3); ?> m³<br/>
            Água (aprox.): <?php echo nf($calc['agua_L'], 0); ?> L
          </div>
        </div>
      </div>

      <div class="footer">
        <b>Dica prática:</b> Para compra, arredonde para cima (principalmente cimento). Areia/brita costumam ser vendidas em m³ ou “carretas”.
      </div>
    <?php endif; ?>
  </div>
</div>
