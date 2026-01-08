<?php
require_once __DIR__ . '/../inc/utils.php';

$calc = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['calc'] ?? '') === 'piso') {
  $comp = postf('pcomp', 0);
  $larg = postf('plarg', 0);
  $perda = clampf(postf('pperda', 10), 0, 25);

  $tile_w_cm = max(1, postf('tile_w', 60));
  $tile_h_cm = max(1, postf('tile_h', 60));

  $area = max(0, $comp * $larg);
  $area_total = $area * wasteFactor($perda);

  $tile_area = ($tile_w_cm/100.0) * ($tile_h_cm/100.0);
  $qtd = ($tile_area > 0) ? ceil($area_total / $tile_area) : 0;

  // argamassa colante (estimativa) - depende do dente da desempenadeira e tamanho
  // regra simples: 4–6 kg/m²; usa 5 kg/m² default
  $kg_m2 = clampf(postf('kgm2', 5), 3, 9);
  $argamassa_kg = $area_total * $kg_m2;
  $sacos20 = $argamassa_kg / 20;

  $rejunte_g_m2 = clampf(postf('rej_gm2', 250), 80, 600); // grams/m2
  $rejunte_kg = ($area_total * $rejunte_g_m2) / 1000.0;

  $calc = [
    'area'=>$area,
    'area_total'=>$area_total,
    'qtd'=>$qtd,
    'tile_w_cm'=>$tile_w_cm,
    'tile_h_cm'=>$tile_h_cm,
    'perda'=>$perda,
    'argamassa_kg'=>$argamassa_kg,
    'sacos20'=>$sacos20,
    'rejunte_kg'=>$rejunte_kg
  ];
}
?>
<div class="card">
  <div class="card-h">
    <div>
      <h2>4) Piso / revestimento (cerâmica/porcelanato)</h2>
      <p>Calcule quantidade de peças e estimativa de argamassa colante e rejunte.</p>
    </div>
    <span class="badge"><b>Resultado</b> em peças e kg</span>
  </div>
  <div class="card-b">
    <form method="post" action="index.php#piso">
      <input type="hidden" name="calc" value="piso"/>
      <div class="grid-2">
        <div class="field">
          <label>Comprimento do ambiente (m)</label>
          <input name="pcomp" inputmode="decimal" placeholder="Ex: 4" value="<?php echo htmlspecialchars($_POST['pcomp'] ?? ''); ?>"/>
        </div>
        <div class="field">
          <label>Largura do ambiente (m)</label>
          <input name="plarg" inputmode="decimal" placeholder="Ex: 3" value="<?php echo htmlspecialchars($_POST['plarg'] ?? ''); ?>"/>
        </div>
        <div class="field">
          <label>Tamanho da peça (cm) - largura</label>
          <input name="tile_w" inputmode="decimal" placeholder="Ex: 60" value="<?php echo htmlspecialchars($_POST['tile_w'] ?? '60'); ?>"/>
        </div>
        <div class="field">
          <label>Tamanho da peça (cm) - altura</label>
          <input name="tile_h" inputmode="decimal" placeholder="Ex: 60" value="<?php echo htmlspecialchars($_POST['tile_h'] ?? '60'); ?>"/>
        </div>
        <div class="field">
          <label>Perdas / recortes (%)</label>
          <input name="pperda" inputmode="decimal" placeholder="Ex: 10" value="<?php echo htmlspecialchars($_POST['pperda'] ?? '10'); ?>"/>
          <div class="hint">Sugestão: 10% (15% em paginação diagonal / muitos recortes).</div>
        </div>
        <div class="field">
          <label>Consumo de argamassa (kg/m²)</label>
          <input name="kgm2" inputmode="decimal" placeholder="Ex: 5" value="<?php echo htmlspecialchars($_POST['kgm2'] ?? '5'); ?>"/>
          <div class="hint">Varia com desempenadeira/superfície. Use a recomendação do fabricante.</div>
        </div>
        <div class="field">
          <label>Rejunte (g/m²)</label>
          <input name="rej_gm2" inputmode="decimal" placeholder="Ex: 250" value="<?php echo htmlspecialchars($_POST['rej_gm2'] ?? '250'); ?>"/>
          <div class="hint">Depende da junta e tamanho da peça. 200–300 g/m² é comum.</div>
        </div>
        <div class="field">
          <label>Observação</label>
          <input disabled value="Argamassa em sacos de 20kg (estimativa)"/>
        </div>
      </div>

      <div class="btns">
        <button class="primary" type="submit">Calcular</button>
        <button class="small" type="button" onclick="location.href='index.php#piso'">Limpar</button>
      </div>
    </form>

    <?php if ($calc): ?>
      <div class="note"></div>
      <div class="results">
        <div class="result-box">
          <h3>Área do piso</h3>
          <div class="v"><?php echo nf($calc['area'], 2); ?> m²</div>
          <div class="s">Sem perdas.</div>
        </div>
        <div class="result-box">
          <h3>Área com perdas</h3>
          <div class="v"><?php echo nf($calc['area_total'], 2); ?> m²</div>
          <div class="s">Perdas: <?php echo nf($calc['perda'], 0); ?>%.</div>
        </div>
        <div class="result-box">
          <h3>Quantidade de peças</h3>
          <div class="v"><?php echo nf($calc['qtd'], 0); ?> peças</div>
          <div class="s">Peça: <?php echo nf($calc['tile_w_cm'], 0); ?>×<?php echo nf($calc['tile_h_cm'], 0); ?> cm.</div>
        </div>
        <div class="result-box">
          <h3>Argamassa e rejunte</h3>
          <div class="s">
            Argamassa: <?php echo nf($calc['argamassa_kg'], 0); ?> kg (≈ <?php echo nf(ceil($calc['sacos20']), 0); ?> sacos de 20kg)<br/>
            Rejunte: <?php echo nf($calc['rejunte_kg'], 1); ?> kg
          </div>
        </div>
      </div>

      <div class="footer">
        <b>Dica:</b> Confira na caixa quantos m² ela cobre. Se quiser, você pode adaptar o cálculo para “caixas” (m² por caixa).
      </div>
    <?php endif; ?>
  </div>
</div>
