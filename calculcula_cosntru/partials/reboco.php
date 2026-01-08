<?php
require_once __DIR__ . '/../inc/utils.php';

$calc = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['calc'] ?? '') === 'reboco') {
  $larg = postf('rlarg', 0);
  $alt  = postf('ralt', 0);
  $aberturas = postf('raberturas', 0);

  $esp_mm = clampf(postf('resp', 15), 5, 40); // mm
  $perda = clampf(postf('rperda', 10), 0, 30);

  $area = max(0, ($larg * $alt) - $aberturas);
  $esp_m = $esp_mm / 1000.0;

  // volume de argamassa (m3) = area * espessura
  $vol = $area * $esp_m;
  $vol_total = $vol * wasteFactor($perda);

  // traço referência 1:2:8 (cimento:cal:areia) para reboco/emboço
  $fatorSeco = 1.33;
  $volSeco = $vol_total * $fatorSeco;
  $partes = 1 + 2 + 8;

  $cimento_m3 = $volSeco * (1/$partes);
  $cal_m3     = $volSeco * (2/$partes);
  $areia_m3   = $volSeco * (8/$partes);

  $densCimento = 1440; // kg/m3
  $cimento_kg = $cimento_m3 * $densCimento;
  $sacos50 = $cimento_kg / 50;

  $calc = [
    'area'=>$area,
    'vol'=>$vol,
    'vol_total'=>$vol_total,
    'esp_mm'=>$esp_mm,
    'perda'=>$perda,
    'sacos50'=>$sacos50,
    'cal_m3'=>$cal_m3,
    'areia_m3'=>$areia_m3
  ];
}
?>
<div class="card">
  <div class="card-h">
    <div>
      <h2>5) Reboco / Emboço (argamassa por espessura)</h2>
      <p>Estimativa de argamassa (m³) e materiais usando traço de referência 1:2:8.</p>
    </div>
    <span class="badge"><b>Resultado</b> em m³ e sacos</span>
  </div>
  <div class="card-b">
    <form method="post" action="index.php#reboco">
      <input type="hidden" name="calc" value="reboco"/>
      <div class="grid-2">
        <div class="field">
          <label>Largura da parede (m)</label>
          <input name="rlarg" inputmode="decimal" placeholder="Ex: 6" value="<?php echo htmlspecialchars($_POST['rlarg'] ?? ''); ?>"/>
        </div>
        <div class="field">
          <label>Altura da parede (m)</label>
          <input name="ralt" inputmode="decimal" placeholder="Ex: 2.6" value="<?php echo htmlspecialchars($_POST['ralt'] ?? ''); ?>"/>
        </div>
        <div class="field">
          <label>Área de aberturas (m²)</label>
          <input name="raberturas" inputmode="decimal" placeholder="Ex: 1.8" value="<?php echo htmlspecialchars($_POST['raberturas'] ?? '0'); ?>"/>
          <div class="hint">Desconte portas/janelas. Se não tiver, 0.</div>
        </div>
        <div class="field">
          <label>Espessura média (mm)</label>
          <input name="resp" inputmode="decimal" placeholder="Ex: 15" value="<?php echo htmlspecialchars($_POST['resp'] ?? '15'); ?>"/>
          <div class="hint">Valores comuns: 10–20mm (depende do prumo/regularização).</div>
        </div>
        <div class="field">
          <label>Perdas / sobra (%)</label>
          <input name="rperda" inputmode="decimal" placeholder="Ex: 10" value="<?php echo htmlspecialchars($_POST['rperda'] ?? '10'); ?>"/>
          <div class="hint">Sugestão: 10%.</div>
        </div>
        <div class="field">
          <label>Observação</label>
          <input disabled value="Traço de referência 1:2:8 (cimento:cal:areia)"/>
          <div class="hint">A obra pode usar traço diferente. Ajuste no código se quiser.</div>
        </div>
      </div>

      <div class="btns">
        <button class="primary" type="submit">Calcular</button>
        <button class="small" type="button" onclick="location.href='index.php#reboco'">Limpar</button>
      </div>
    </form>

    <?php if ($calc): ?>
      <div class="note"></div>
      <div class="results">
        <div class="result-box">
          <h3>Área</h3>
          <div class="v"><?php echo nf($calc['area'], 2); ?> m²</div>
          <div class="s">Largura × altura − aberturas.</div>
        </div>
        <div class="result-box">
          <h3>Argamassa (volume)</h3>
          <div class="v"><?php echo nf($calc['vol_total'], 3); ?> m³</div>
          <div class="s">Espessura: <?php echo nf($calc['esp_mm'], 0); ?> mm • perdas: <?php echo nf($calc['perda'], 0); ?>%.</div>
        </div>
        <div class="result-box">
          <h3>Materiais (traço 1:2:8)</h3>
          <div class="s">
            Cimento: <?php echo nf($calc['sacos50'], 1); ?> sacos (50kg)<br/>
            Cal: <?php echo nf($calc['cal_m3'], 3); ?> m³ (aprox.)<br/>
            Areia: <?php echo nf($calc['areia_m3'], 3); ?> m³ (aprox.)
          </div>
        </div>
      </div>

      <div class="footer">
        <b>Atenção:</b> Reboco pode variar muito conforme espessura real e técnica. Use como referência.
      </div>
    <?php endif; ?>
  </div>
</div>
