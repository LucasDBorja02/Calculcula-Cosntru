<?php
require_once __DIR__ . '/../inc/utils.php';

$calc = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['calc'] ?? '') === 'muro') {
  $larg = postf('mlarg', 0);
  $alt  = postf('malt', 0);
  $aberturas = postf('maberturas', 0);

  $tipo = $_POST['tipo_bloco'] ?? 'ceramico_39x19';
  // blocos por m2 (com junta de 1cm) - valores típicos
  $blocosPorM2 = [
    'ceramico_39x19' => 12.5,
    'concreto_39x19' => 12.5,
    'tijolo_23x11'   => 48.0
  ];
  $bpm2 = $blocosPorM2[$tipo] ?? 12.5;

  $perda = clampf(postf('mperda', 10), 0, 30);

  $area = max(0, ($larg * $alt) - $aberturas);
  $qtdBlocos = $area * $bpm2 * wasteFactor($perda);

  // argamassa de assentamento (estimativa)
  // para bloco 39x19 costuma ficar ~0,02 m3 por m2; tijolo maciço pode ser maior
  $arg_m3_m2 = ($tipo === 'tijolo_23x11') ? 0.03 : 0.02;
  $argamassa_m3 = $area * $arg_m3_m2 * wasteFactor($perda);

  // traço 1:2:8 (cimento:cal:areia) como referência (argamassa comum) - volumes secos
  // fator de volume seco ~ 1,33
  $fatorSeco = 1.33;
  $volSeco = $argamassa_m3 * $fatorSeco;
  $partes = 1 + 2 + 8;
  $cimento_m3 = $volSeco * (1 / $partes);
  $cal_m3     = $volSeco * (2 / $partes);
  $areia_m3   = $volSeco * (8 / $partes);

  // converter cimento em sacos 50kg (densidade aparente ~1440 kg/m3)
  $densCimento = 1440; // kg/m3
  $cimento_kg = $cimento_m3 * $densCimento;
  $sacos = $cimento_kg / 50;

  $calc = [
    'area'=>$area,
    'qtdBlocos'=>$qtdBlocos,
    'argamassa_m3'=>$argamassa_m3,
    'sacos_cimento'=>$sacos,
    'areia_m3'=>$areia_m3,
    'cal_m3'=>$cal_m3,
    'tipo'=>$tipo,
    'perda'=>$perda
  ];
}
?>
<div class="card">
  <div class="card-h">
    <div>
      <h2>2) Muro / alvenaria (blocos/tijolos + argamassa)</h2>
      <p>Estimativa de quantidade de blocos/tijolos e argamassa de assentamento.</p>
    </div>
    <span class="badge"><b>Resultado</b> em unidades e m³</span>
  </div>
  <div class="card-b">
    <form method="post" action="index.php#muro">
      <input type="hidden" name="calc" value="muro"/>
      <div class="grid-2">
        <div class="field">
          <label>Largura do muro (m)</label>
          <input name="mlarg" inputmode="decimal" placeholder="Ex: 10" value="<?php echo htmlspecialchars($_POST['mlarg'] ?? ''); ?>"/>
        </div>
        <div class="field">
          <label>Altura do muro (m)</label>
          <input name="malt" inputmode="decimal" placeholder="Ex: 2.5" value="<?php echo htmlspecialchars($_POST['malt'] ?? ''); ?>"/>
        </div>
        <div class="field">
          <label>Área de aberturas (m²)</label>
          <input name="maberturas" inputmode="decimal" placeholder="Ex: 1.8" value="<?php echo htmlspecialchars($_POST['maberturas'] ?? '0'); ?>"/>
          <div class="hint">Portões/portas/janelas. Se não tiver, 0.</div>
        </div>
        <div class="field">
          <label>Tipo de alvenaria</label>
          <select name="tipo_bloco">
            <?php $sel = $_POST['tipo_bloco'] ?? 'ceramico_39x19'; ?>
            <option value="ceramico_39x19" <?php echo $sel==='ceramico_39x19'?'selected':''; ?>>Bloco cerâmico 39×19 (≈12,5 un/m²)</option>
            <option value="concreto_39x19" <?php echo $sel==='concreto_39x19'?'selected':''; ?>>Bloco de concreto 39×19 (≈12,5 un/m²)</option>
            <option value="tijolo_23x11" <?php echo $sel==='tijolo_23x11'?'selected':''; ?>>Tijolo 23×11 (≈48 un/m²)</option>
          </select>
          <div class="hint">Valores típicos com junta ~1cm. Pode variar conforme o bloco e o assentamento.</div>
        </div>
        <div class="field">
          <label>Perdas / sobra (%)</label>
          <input name="mperda" inputmode="decimal" placeholder="Ex: 10" value="<?php echo htmlspecialchars($_POST['mperda'] ?? '10'); ?>"/>
          <div class="hint">Sugestão: 5–10%.</div>
        </div>
        <div class="field">
          <label>Observação</label>
          <input disabled value="Inclui estimativa de argamassa e um traço de referência 1:2:8"/>
          <div class="hint">Traço usado apenas para estimar materiais (cimento/cal/areia).</div>
        </div>
      </div>

      <div class="btns">
        <button class="primary" type="submit">Calcular</button>
        <button class="small" type="button" onclick="location.href='index.php#muro'">Limpar</button>
      </div>
    </form>

    <?php if ($calc): ?>
      <div class="note"></div>
      <div class="results">
        <div class="result-box">
          <h3>Área de alvenaria</h3>
          <div class="v"><?php echo nf($calc['area'], 2); ?> m²</div>
          <div class="s">Área = largura × altura − aberturas.</div>
        </div>
        <div class="result-box">
          <h3>Blocos / tijolos</h3>
          <div class="v"><?php echo nf($calc['qtdBlocos'], 0); ?> un</div>
          <div class="s">Já com perdas de <?php echo nf($calc['perda'], 0); ?>%.</div>
        </div>
        <div class="result-box">
          <h3>Argamassa (assentamento)</h3>
          <div class="v"><?php echo nf($calc['argamassa_m3'], 3); ?> m³</div>
          <div class="s">Estimativa típica por m² (pode variar).</div>
        </div>
        <div class="result-box">
          <h3>Materiais p/ argamassa (traço 1:2:8)</h3>
          <div class="s">
            Cimento: <?php echo nf($calc['sacos_cimento'], 1); ?> saco(s) de 50kg<br/>
            Cal: <?php echo nf($calc['cal_m3'], 3); ?> m³ (aprox.)<br/>
            Areia: <?php echo nf($calc['areia_m3'], 3); ?> m³ (aprox.)
          </div>
        </div>
      </div>

      <div class="footer">
        <b>Atenção:</b> Isso é estimativa. Consumo real varia por prumo/nível, espessura de junta, tipo de bloco e mão de obra.
      </div>
    <?php endif; ?>
  </div>
</div>
