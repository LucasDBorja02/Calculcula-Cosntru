# CalculCula Cosntru (v2)

Site simples (sem login) para estimar **materiais de construção**: tinta, alvenaria (muro), concreto, piso/revestimento e mais calculadoras.

## Tecnologias
- PHP (servidor)
- HTML + CSS + JavaScript (front)
- XAMPP (Apache)

## Como rodar no XAMPP (Windows)
1. Copie a pasta `calculcula_cosntru` para `C:\xampp\htdocs\`
2. Abra o XAMPP e inicie o **Apache**
3. Acesse: `http://localhost/calculcula_cosntru/`

## Calculadoras incluídas
1. **Tinta para parede/muro** (área, demãos, rendimento, perdas + sugestão de latas)
2. **Muro / Alvenaria** (blocos/tijolos + argamassa + estimativa de materiais do traço)
3. **Concreto** (volume + cimento/areia/brita/água por traço)
4. **Piso / Revestimento** (peças + argamassa colante + rejunte)
5. **Reboco / Emboço** (argamassa por espessura + materiais do traço)
6. **Telhado** (área inclinada + telhas + cumeeiras – estimativa)
7. **Elétrica básica** (cabos, conduíte, circuitos/disjuntores – estimativa)
8. **Hidráulica básica** (tubos e conexões por ponto – estimativa)

## Onde editar os cálculos
- `partials/` (um arquivo por calculadora)
- `inc/utils.php` (funções auxiliares)

## Observação importante
Este projeto faz **estimativas**. Consumos reais variam conforme:
- marca e tipo do material,
- superfície e execução,
- recortes/perdas,
- e condições da obra.

Para dimensionamento estrutural e instalações (elétrica/hidráulica), consulte um profissional habilitado.

## Próximas ideias (se quiser que eu implemente)
- Orçamento por preços (tabela de materiais + total)
- Exportar PDF / imprimir por seção
- Drywall/gesso, forro, pintura de teto
- Esquadrias (portas/janelas) e lista de materiais
- Checklist de obra e cronograma
