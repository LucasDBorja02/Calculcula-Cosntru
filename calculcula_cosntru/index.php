<?php
// CalculCula Cosntru - single page (v2)
$initialTab = 'inicio';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $calc = $_POST['calc'] ?? '';
  $map = [
    'tinta' => 'tinta',
    'muro' => 'muro',
    'concreto' => 'concreto',
    'piso' => 'piso',
    'reboco' => 'reboco',
    'telhado' => 'telhado',
    'eletrica' => 'eletrica',
    'hidraulica' => 'hidraulica',
  ];
  if (isset($map[$calc])) $initialTab = $map[$calc];
}
?><!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>CalculCula Cosntru - Calculadora de Construção</title>
  <link rel="stylesheet" href="assets/css/style.css"/>
  <script>window.__INITIAL_TAB__ = <?php echo json_encode($initialTab); ?>;</script>
</head>
<body>
  <div class="container">
    <header class="header">
      <div class="brand">
        <img src="assets/img/logo.svg" alt="CalculCula Cosntru"/>
      </div>
      <nav class="nav" aria-label="Navegação">
        <a href="#inicio" data-tab="inicio">Início</a>
        <a href="#tinta" data-tab="tinta">Tinta</a>
        <a href="#muro" data-tab="muro">Muro</a>
        <a href="#concreto" data-tab="concreto">Concreto</a>
        <a href="#piso" data-tab="piso">Piso</a>
        <a href="#reboco" data-tab="reboco">Reboco</a>
        <a href="#telhado" data-tab="telhado">Telhado</a>
        <a href="#eletrica" data-tab="eletrica">Elétrica</a>
        <a href="#hidraulica" data-tab="hidraulica">Hidráulica</a>
        <a href="#orcamento" data-tab="orcamento">Orçamento</a>
        <a href="#sobre" data-tab="sobre">Sobre</a>
      </nav>
    </header>

    <section class="hero">
      <div class="card">
        <div class="card-h">
          <div>
            <h2>Estimativas rápidas para obra</h2>
            <p>Preencha as medidas e obtenha uma estimativa de materiais. Ajuste perdas, rendimentos e padrões de acordo com sua obra.</p>
          </div>
          <span class="badge"><span class="kbd">Dica</span> Use vírgula ou ponto</span>
        </div>
        <div class="card-b">
          <div class="footer">
            <b>Importante:</b> os cálculos são estimativas e podem variar por marca, mão de obra, qualidade do material e condições da superfície.
            Para projetos estruturais e instalações (elétrica/hidráulica), consulte um profissional habilitado.
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-h">
          <div>
            <h2>Como rodar no XAMPP</h2>
            <p>Coloque a pasta do projeto em <span class="kbd">htdocs</span> e abra no navegador.</p>
          </div>
          <span class="badge"><b>XAMPP</b></span>
        </div>
        <div class="card-b">
          <div class="footer">
            1) Copie a pasta <span class="kbd">calculcula_cosntru</span> para <span class="kbd">C:\xampp\htdocs\</span><br/>
            2) Inicie o Apache no XAMPP<br/>
            3) Abra: <span class="kbd">http://localhost/calculcula_cosntru/</span>
          </div>
        </div>
      </div>
    </section>

    <main style="margin-top:18px">
      <section class="tab" id="inicio" style="display:none">
        <?php include __DIR__ . '/partials/inicio.php'; ?>
      </section>

      <section class="tab" id="tinta" style="display:none">
        <?php include __DIR__ . '/partials/tinta.php'; ?>
      </section>

      <section class="tab" id="muro" style="display:none">
        <?php include __DIR__ . '/partials/muro.php'; ?>
      </section>

      <section class="tab" id="concreto" style="display:none">
        <?php include __DIR__ . '/partials/concreto.php'; ?>
      </section>

      <section class="tab" id="piso" style="display:none">
        <?php include __DIR__ . '/partials/piso.php'; ?>
      </section>

      <section class="tab" id="reboco" style="display:none">
        <?php include __DIR__ . '/partials/reboco.php'; ?>
      </section>

      <section class="tab" id="telhado" style="display:none">
        <?php include __DIR__ . '/partials/telhado.php'; ?>
      </section>

      <section class="tab" id="eletrica" style="display:none">
        <?php include __DIR__ . '/partials/eletrica.php'; ?>
      </section>

      <section class="tab" id="hidraulica" style="display:none">

      <section class="tab" id="orcamento" style="display:none">
        <?php include __DIR__ . '/partials/orcamento.php'; ?>
      </section>

        <?php include __DIR__ . '/partials/hidraulica.php'; ?>
      </section>

      <section class="tab" id="sobre" style="display:none">
        <?php include __DIR__ . '/partials/sobre.php'; ?>
      </section>
    </main>

    <footer class="footer">
      <span class="badge"><b>CalculCula Cosntru</b> — projeto simples em PHP/HTML/CSS/JS</span>
      <span style="display:block; margin-top:10px">
        Personalize as fórmulas em <span class="kbd">partials/</span>. Funções auxiliares em <span class="kbd">inc/utils.php</span>.
      </span>
    </footer>
  </div>

  <script src="assets/js/app.js"></script>
</body>
</html>
