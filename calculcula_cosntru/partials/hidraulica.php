<?php
require_once __DIR__ . '/../inc/utils.php';

$calc = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['calc'] ?? '') === 'hidraulica') {
  $p_agua = posti('hagua', 0);
  $p_esgoto = posti('hesgoto', 0);
  $perda = clampf(postf('hperda', 10), 0, 30);

  // estimativa de metros de tubo por ponto
  $m_agua = clampf(postf('hmagua', 6), 2, 20);
  $m_esgoto = clampf(postf('hmesgoto', 4), 2, 20);

  $agua_m = max(0, $p_agua) * $m_agua * wasteFactor($perda);
  $esgoto_m = max(0, $p_esgoto) * $m_esgoto * wasteFactor($perda);

  // conexões: regra simples: 4 conexões por ponto
  $conx_agua = ceil($p_agua * 4 * wasteFactor($perda));
  $conx_esgoto = ceil($p_esgoto * 4 * wasteFactor($perda));

  $calc = [
    'p_agua'=>$p_agua,
    'p_esgoto'=>$p_esgoto,
    'perda'=>$perda,
    'agua_m'=>$agua_m,
    'esgoto_m'=>$esgoto_m,
    'm_agua'=>$m_agua,
    'm_esgoto'=>$m_esgoto,
    'conx_agua'=>$conx_agua,
    'conx_esgoto'=>$conx_esgoto
  ];
}
?>
<div class="card">
  <div class="card-h">
    <div>
      <h2>8) Hidráulica básica (água e esgoto)</h2>
      <p>Estimativa rápida de tubos e conexões por ponto. Ajuste os “metros por ponto” conforme o layout.</p>
    </div>
    <span class="badge"><b>Estimativa</b> (metros e conexões)</span>
  </div>
  <div class="card-b">
    <form method="post" action="index.php#hidraulica">
      <input type="hidden" name="calc" value="hidraulica"/>
      <div class="grid-2">
        <div class="field">
          <label>Pontos de água (torneira/chuveiro/caixa etc.)</label>
          <input name="hagua" inputmode="numeric" placeholder="Ex: 6" value="<?php echo htmlspecialchars($_POST['hagua'] ?? ''); ?>"/>
        </div>
        <div class="field">
          <label>Pontos de esgoto (ralo/vaso/lavatório etc.)</label>
          <input name="hesgoto" inputmode="numeric" placeholder="Ex: 5" value="<?php echo htmlspecialchars($_POST['hesgoto'] ?? ''); ?>"/>
        </div>
        <div class="field">
          <label>Metros de tubo de água por ponto</label>
          <input name="hmagua" inputmode="decimal" placeholder="Ex: 6" value="<?php echo htmlspecialchars($_POST['hmagua'] ?? '6'); ?>"/>
          <div class="hint">Muito variável. Ajuste conforme o caminho real.</div>
        </div>
        <div class="field">
          <label>Metros de tubo de esgoto por ponto</label>
          <input name="hmesgoto" inputmode="decimal" placeholder="Ex: 4" value="<?php echo htmlspecialchars($_POST['hmesgoto'] ?? '4'); ?>"/>
        </div>
        <div class="field">
          <label>Perdas / sobra (%)</label>
          <input name="hperda" inputmode="decimal" placeholder="Ex: 10" value="<?php echo htmlspecialchars($_POST['hperda'] ?? '10'); ?>"/>
        </div>
        <div class="field">
          <label>Observação</label>
          <input disabled value="Conexões estimadas como 4 por ponto (ajuste na prática)"/>
        </div>
      </div>

      <div class="btns">
        <button class="primary" type="submit">Calcular</button>
        <button class="small" type="button" onclick="location.href='index.php#hidraulica'">Limpar</button>
      </div>
    </form>

    <?php if ($calc): ?>
      <div class="note"></div>
      <div class="results">
        <div class="result-box">
          <h3>Tubos de água</h3>
          <div class="v"><?php echo nf($calc['agua_m'], 0); ?> m</div>
          <div class="s"><?php echo nf($calc['p_agua'], 0); ?> ponto(s) • <?php echo nf($calc['m_agua'], 0); ?> m/ponto • perdas: <?php echo nf($calc['perda'], 0); ?>%.</div>
        </div>
        <div class="result-box">
          <h3>Tubos de esgoto</h3>
          <div class="v"><?php echo nf($calc['esgoto_m'], 0); ?> m</div>
          <div class="s"><?php echo nf($calc['p_esgoto'], 0); ?> ponto(s) • <?php echo nf($calc['m_esgoto'], 0); ?> m/ponto.</div>
        </div>
        <div class="result-box">
          <h3>Conexões (estimativa)</h3>
          <div class="s">
            Conexões água: <?php echo nf($calc['conx_agua'], 0); ?><br/>
            Conexões esgoto: <?php echo nf($calc['conx_esgoto'], 0); ?>
          </div>
        </div>
      </div>

      <div class="footer">
        <b>Importante:</b> bitolas, pressões, ventilação, caixa de gordura e caimento precisam de projeto/execução correta.
      </div>
    <?php endif; ?>
  </div>
</div>
