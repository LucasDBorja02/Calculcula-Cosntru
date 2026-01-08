<?php
require_once __DIR__ . '/../inc/utils.php';

$calc = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['calc'] ?? '') === 'telhado') {
  $comp = postf('tcomp', 0);
  $larg = postf('tlarg', 0);

  $inclinacao = clampf(postf('tincl', 30), 5, 60); // graus
  $perda = clampf(postf('tperda', 10), 0, 25);

  $tipo = $_POST['ttipo'] ?? 'ceramica';
  // telhas por m2 (valores típicos)
  $telhas_m2 = [
    'ceramica' => 16,
    'fibro'    => 1.7, // placas
    'metal'    => 1.1  // m2 efetivo ~ 1 telha cobre ~1m2 (estimativa)
  ];
  $tm2 = $telhas_m2[$tipo] ?? 16;

  $area_planta = max(0, $comp * $larg);
  // área inclinada aproximada: A_incl = A_planta / cos(theta)
  $theta = deg2rad($inclinacao);
  $cosv = cos($theta);
  if ($cosv < 0.2) $cosv = 0.2;
  $area_incl = $area_planta / $cosv;
  $area_total = $area_incl * wasteFactor($perda);

  $qtd_telhas = $area_total * $tm2;

  // cumeeira (estimativa): assume 1 cumeeira a cada 0,45 m (cerâmica) ou 1m (outros)
  $cum_len = postf('tcum', 0); // comprimento da cumeeira
  $cum_step = ($tipo === 'ceramica') ? 0.45 : 1.0;
  $qtd_cum = ($cum_len > 0) ? ceil(($cum_len / $cum_step) * wasteFactor($perda)) : 0;

  $calc = [
    'area_planta'=>$area_planta,
    'area_incl'=>$area_incl,
    'area_total'=>$area_total,
    'inclinacao'=>$inclinacao,
    'perda'=>$perda,
    'tipo'=>$tipo,
    'qtd_telhas'=>$qtd_telhas,
    'cum_len'=>$cum_len,
    'qtd_cum'=>$qtd_cum
  ];
}
?>
<div class="card">
  <div class="card-h">
    <div>
      <h2>6) Telhado (área inclinada + telhas + cumeeira)</h2>
      <p>Estimativa simples para compra de telhas e cumeeiras. Ajuste os fatores conforme o modelo de telha.</p>
    </div>
    <span class="badge"><b>Resultado</b> em m² e unidades</span>
  </div>
  <div class="card-b">
    <form method="post" action="index.php#telhado">
      <input type="hidden" name="calc" value="telhado"/>
      <div class="grid-2">
        <div class="field">
          <label>Comprimento da projeção (m)</label>
          <input name="tcomp" inputmode="decimal" placeholder="Ex: 8" value="<?php echo htmlspecialchars($_POST['tcomp'] ?? ''); ?>"/>
          <div class="hint">Medida em planta (vista de cima).</div>
        </div>
        <div class="field">
          <label>Largura da projeção (m)</label>
          <input name="tlarg" inputmode="decimal" placeholder="Ex: 6" value="<?php echo htmlspecialchars($_POST['tlarg'] ?? ''); ?>"/>
        </div>
        <div class="field">
          <label>Inclinação (graus)</label>
          <input name="tincl" inputmode="decimal" placeholder="Ex: 30" value="<?php echo htmlspecialchars($_POST['tincl'] ?? '30'); ?>"/>
          <div class="hint">A área inclinada aumenta com a inclinação.</div>
        </div>
        <div class="field">
          <label>Tipo de telha</label>
          <select name="ttipo">
            <?php $sel = $_POST['ttipo'] ?? 'ceramica'; ?>
            <option value="ceramica" <?php echo $sel==='ceramica'?'selected':''; ?>>Cerâmica (≈16 telhas/m²)</option>
            <option value="fibro" <?php echo $sel==='fibro'?'selected':''; ?>>Fibrocimento (≈1,7 placa/m²)</option>
            <option value="metal" <?php echo $sel==='metal'?'selected':''; ?>>Metálica/galvalume (≈1,1 “telha”/m²)</option>
          </select>
          <div class="hint">Valor típico. Verifique a ficha do fabricante.</div>
        </div>
        <div class="field">
          <label>Comprimento de cumeeira (m)</label>
          <input name="tcum" inputmode="decimal" placeholder="Ex: 8" value="<?php echo htmlspecialchars($_POST['tcum'] ?? '0'); ?>"/>
          <div class="hint">Se não souber, deixe 0 (não calcula cumeeiras).</div>
        </div>
        <div class="field">
          <label>Perdas / sobra (%)</label>
          <input name="tperda" inputmode="decimal" placeholder="Ex: 10" value="<?php echo htmlspecialchars($_POST['tperda'] ?? '10'); ?>"/>
        </div>
      </div>

      <div class="btns">
        <button class="primary" type="submit">Calcular</button>
        <button class="small" type="button" onclick="location.href='index.php#telhado'">Limpar</button>
      </div>
    </form>

    <?php if ($calc): ?>
      <div class="note"></div>
      <div class="results">
        <div class="result-box">
          <h3>Área em planta</h3>
          <div class="v"><?php echo nf($calc['area_planta'], 2); ?> m²</div>
          <div class="s">Comprimento × largura.</div>
        </div>
        <div class="result-box">
          <h3>Área inclinada</h3>
          <div class="v"><?php echo nf($calc['area_total'], 2); ?> m²</div>
          <div class="s">Inclinação: <?php echo nf($calc['inclinacao'], 0); ?>° • perdas: <?php echo nf($calc['perda'], 0); ?>%.</div>
        </div>
        <div class="result-box">
          <h3>Telhas estimadas</h3>
          <div class="v"><?php echo nf(ceil($calc['qtd_telhas']), 0); ?> un</div>
          <div class="s">Tipo: <?php echo htmlspecialchars($calc['tipo']); ?> (fator típico).</div>
        </div>
        <div class="result-box">
          <h3>Cumeeiras (se informado)</h3>
          <div class="v"><?php echo nf($calc['qtd_cum'], 0); ?> un</div>
          <div class="s">Comprimento de cumeeira: <?php echo nf($calc['cum_len'], 2); ?> m.</div>
        </div>
      </div>

      <div class="footer">
        <b>Observação:</b> Telhas metálicas/fibro são vendidas por comprimento/placa e têm sobreposição. Ajuste o fator conforme o modelo.
      </div>
    <?php endif; ?>
  </div>
</div>
