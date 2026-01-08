<?php
require_once __DIR__ . '/../inc/utils.php';

$calc = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['calc'] ?? '') === 'eletrica') {
  $area = postf('earea', 0); // m2
  $pontos = posti('epontos', 0); // pontos de tomada/iluminação
  $perda = clampf(postf('eperda', 10), 0, 30);

  $tensao = $_POST['etensao'] ?? '127';

  // regras simples (NÃO norma): pontos sugeridos
  $pontos_sug = max(1, ceil($area / 6)); // 1 ponto a cada 6 m2 (bem genérico)

  // cabos: estimativa por ponto (ida+volta + sobra)
  $m_por_ponto = clampf(postf('empp', 18), 8, 40); // metros por ponto (estimativa)
  $cabos_m = ($pontos > 0 ? $pontos : $pontos_sug) * $m_por_ponto * wasteFactor($perda);

  // circuitos sugeridos
  $circuitos = max(1, ceil(($pontos > 0 ? $pontos : $pontos_sug) / 8));

  // disjuntores: 1 geral + 1 por circuito
  $disjuntores = 1 + $circuitos;

  // conduíte: estimativa (metade do cabo, muito genérico)
  $conduite_m = ($cabos_m * 0.55);

  $calc = [
    'area'=>$area,
    'pontos'=>$pontos,
    'pontos_sug'=>$pontos_sug,
    'cabos_m'=>$cabos_m,
    'm_por_ponto'=>$m_por_ponto,
    'perda'=>$perda,
    'circuitos'=>$circuitos,
    'disjuntores'=>$disjuntores,
    'conduite_m'=>$conduite_m,
    'tensao'=>$tensao
  ];
}
?>
<div class="card">
  <div class="card-h">
    <div>
      <h2>7) Elétrica básica (estimativa rápida)</h2>
      <p>Estimativa de cabos, conduíte e disjuntores. <b>Não substitui</b> dimensionamento por norma/profissional.</p>
    </div>
    <span class="badge"><b>Estimativa</b> (metros e unidades)</span>
  </div>
  <div class="card-b">
    <form method="post" action="index.php#eletrica">
      <input type="hidden" name="calc" value="eletrica"/>
      <div class="grid-2">
        <div class="field">
          <label>Área do ambiente (m²)</label>
          <input name="earea" inputmode="decimal" placeholder="Ex: 45" value="<?php echo htmlspecialchars($_POST['earea'] ?? ''); ?>"/>
          <div class="hint">Se você não sabe os pontos, a calculadora sugere uma quantidade.</div>
        </div>
        <div class="field">
          <label>Nº de pontos (tomadas + iluminação)</label>
          <input name="epontos" inputmode="numeric" placeholder="Ex: 12" value="<?php echo htmlspecialchars($_POST['epontos'] ?? ''); ?>"/>
          <div class="hint">Se deixar vazio/0, usa sugestão por área.</div>
        </div>
        <div class="field">
          <label>Metros de cabo por ponto (estimativa)</label>
          <input name="empp" inputmode="decimal" placeholder="Ex: 18" value="<?php echo htmlspecialchars($_POST['empp'] ?? '18'); ?>"/>
          <div class="hint">Inclui percurso + ida/volta + sobra. Ajuste conforme a planta.</div>
        </div>
        <div class="field">
          <label>Tensão (informativa)</label>
          <select name="etensao">
            <?php $sel = $_POST['etensao'] ?? '127'; ?>
            <option value="127" <?php echo $sel==='127'?'selected':''; ?>>127 V</option>
            <option value="220" <?php echo $sel==='220'?'selected':''; ?>>220 V</option>
          </select>
          <div class="hint">A tensão não muda as quantidades aqui, é só informativa.</div>
        </div>
        <div class="field">
          <label>Perdas / sobra (%)</label>
          <input name="eperda" inputmode="decimal" placeholder="Ex: 10" value="<?php echo htmlspecialchars($_POST['eperda'] ?? '10'); ?>"/>
        </div>
        <div class="field">
          <label>Observação</label>
          <input disabled value="Use eletricista/engenheiro para projeto e proteção correta"/>
        </div>
      </div>

      <div class="btns">
        <button class="primary" type="submit">Calcular</button>
        <button class="small" type="button" onclick="location.href='index.php#eletrica'">Limpar</button>
      </div>
    </form>

    <?php if ($calc): ?>
      <div class="note"></div>
      <div class="results">
        <div class="result-box">
          <h3>Pontos considerados</h3>
          <div class="v"><?php echo nf(($calc['pontos']>0?$calc['pontos']:$calc['pontos_sug']), 0); ?> pontos</div>
          <div class="s">Sugestão por área: <?php echo nf($calc['pontos_sug'], 0); ?> (se você não informou).</div>
        </div>
        <div class="result-box">
          <h3>Cabo (estimativa total)</h3>
          <div class="v"><?php echo nf($calc['cabos_m'], 0); ?> m</div>
          <div class="s"><?php echo nf($calc['m_por_ponto'], 0); ?> m/ponto • perdas: <?php echo nf($calc['perda'], 0); ?>%.</div>
        </div>
        <div class="result-box">
          <h3>Conduíte (estimativa)</h3>
          <div class="v"><?php echo nf($calc['conduite_m'], 0); ?> m</div>
          <div class="s">Regra simples (aprox. 55% do cabo). Ajuste conforme o traçado.</div>
        </div>
        <div class="result-box">
          <h3>Circuitos e disjuntores</h3>
          <div class="s">
            Circuitos sugeridos: <?php echo nf($calc['circuitos'], 0); ?><br/>
            Disjuntores (1 geral + 1 por circuito): <?php echo nf($calc['disjuntores'], 0); ?>
          </div>
        </div>
      </div>

      <div class="footer">
        <b>Importante:</b> para dimensionar bitola, disjuntores e DPS/DR, use NBR 5410 e profissional habilitado.
      </div>
    <?php endif; ?>
  </div>
</div>
