Aqui está um **README completo, profissional e pronto para GitHub**, em **PT-BR**, já descrevendo tudo o que o projeto faz hoje 👇
Pode **copiar e colar direto** no repositório.

---

#  CalculCula Cosntru

**CalculCula Cosntru** é um site web desenvolvido em **PHP, HTML, CSS e JavaScript**, criado para **calcular materiais de construção e gerar orçamentos completos de obras**, de forma simples, rápida e sem necessidade de login ou banco de dados.

O projeto foi pensado para uso prático no dia a dia, tanto por **pedreiros, mestres de obra, engenheiros, estudantes** ou qualquer pessoa que precise estimar materiais e custos antes de comprar.

---

##  Funcionalidades

###  Calculadoras de Materiais

O sistema possui várias calculadoras independentes:

1. **Tinta (paredes e muros)**

   * Área total
   * Demãos
   * Rendimento por litro
   * Perdas
   * Sugestão de latas

2. **Muro / Alvenaria**

   * Blocos ou tijolos por m²
   * Argamassa de assentamento
   * Estimativa de cimento, cal e areia

3. **Concreto (laje, contrapiso, calçada)**

   * Volume total
   * Traço selecionável
   * Quantidade de cimento, areia, brita e água

4. **Piso / Revestimento**

   * Quantidade de peças
   * Argamassa colante
   * Rejunte

5. **Reboco / Emboço**

   * Espessura média
   * Volume de argamassa
   * Materiais do traço (cimento, cal e areia)

6. **Telhado**

   * Área inclinada
   * Quantidade de telhas
   * Cumeeiras (estimativa)

7. **Elétrica básica**

   * Pontos de tomada e iluminação
   * Estimativa de cabos
   * Conduítes
   * Circuitos e disjuntores (estimativa)

8. **Hidráulica básica**

   * Pontos de água
   * Pontos de esgoto
   * Tubos e conexões (estimativa)

---

###  Módulo de Orçamento

* Cadastro de materiais
* Quantidade por item
* Preço unitário
* Subtotal automático
* **Total geral da obra**
* Ideal para transformar os cálculos em orçamento real

---

###  Exportação em PDF

* Exportação de **orçamento em PDF**
* PDF gerado no servidor via PHP
* Pronto para:

  * imprimir
  * salvar
  * enviar para clientes

---

##  Tecnologias Utilizadas

* **PHP** (backend)
* **HTML5**
* **CSS3**
* **JavaScript**
* **XAMPP / Apache**
* Sem banco de dados
* Sem frameworks
* Sem login

---

##  Como rodar o projeto no XAMPP

1. Baixe o projeto ou clone o repositório
2. Copie a pasta `calculcula_cosntru` para:

   ```
   C:\xampp\htdocs\
   ```
3. Abra o XAMPP e inicie o **Apache**
4. Acesse no navegador:

   ```
   http://localhost/calculcula_cosntru/
   ```

---

##  Estrutura do Projeto

```
calculcula_cosntru/
│
├── assets/
│   ├── css/
│   ├── js/
│   └── img/
│
├── inc/
│   ├── utils.php
│   └── fpdf.php
│
├── partials/
│   ├── tinta.php
│   ├── muro.php
│   ├── concreto.php
│   ├── piso.php
│   ├── reboco.php
│   ├── telhado.php
│   ├── eletrica.php
│   ├── hidraulica.php
│   └── orcamento.php
│
├── export/
│   └── pdf.php
│
├── index.php
└── README.md
```

---

##  Aviso Importante

Este sistema realiza **estimativas**.
Os valores podem variar conforme:

* tipo e marca do material
* execução da obra
* mão de obra
* perdas reais no canteiro

Para projetos estruturais, elétricos e hidráulicos, consulte sempre um **profissional habilitado**.

---

##  Possíveis Evoluções Futuras

* Orçamento automático integrado às calculadoras
* Mão de obra no orçamento
* Exportação de PDF por calculadora
* Versão online (deploy)
* Modo comercial (margem de lucro)
* Histórico de orçamentos
* Checklist e cronograma de obra

---

##  Licença

Projeto livre para estudo, uso pessoal e adaptação.
Sinta-se à vontade para modificar e evoluir.

---

Se quiser, posso:

* adaptar esse README para **inglês**
* criar uma **descrição curta (até 300 caracteres)** para o GitHub
* ajudar a **publicar o site online**
* transformar isso em um **produto comercial**

Só me dizer 
