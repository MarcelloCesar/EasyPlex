# EasyPlex

Repositório para a implementação do algoritmo Simplex e Mochila.

Projeto de Pesquisa Operacional
5º Semestre BSI UNIVEM

O Simplex permite que se encontre valores ideais em situações em que diversos aspectos precisam ser respeitados. Diante de um problema, são estabelecidas inequações que representam restrições para as variáveis. A partir daí, testa-se possibilidades de maneira a otimizar, isto é, maximizar ou minimizar o resultado da forma mais rápida possível.

O algoritmo da mochila consiste em preencher a mochila com objetos diferentes de pesos e valores. O objetivo é que preencha a mochila com o maior valor possível, não ultrapassando o peso máximo.


## Ferramentas

- Javascript
- PHP
- JQuery
- GitHub para hospedagem e versionamento
- Bootstrap

### Simplex

- Algoritmo Simplex para problemas de maximização.
- Algoritmo Simplex para problemas de minimização.
- Pode ser visualizado o passo a passo das tabelas geradas pelo método Simplex.
- Quadro de Análise de Sensibilidade.
- Apresentação resumida da solução otimizada do problema.
- Tratamento de soluções infinitas.
- Tratamento de soluções impossíveis.

### Mochila

- Apresentação da solução, dos itens a serem considerados e a tabela de cálculo.


## Entradas personalizadas para:

### Simplex

- Limite máximo de iterações
- Tipo de Simplex (MAX ou MIN)
- Quantidade de variáveis e restrições
- Nome das variáveis do problema

### Mochila
- Capacidade da mochila
- Peso e valor dos itens
- Nome para cada item

## Limitações

### Simplex

- Somente a possibilidade de restrições de tipo menor igual.


### Mochila

- Não utilizar itens com nomes iguais
- Serão permitidos somente valores positivos
- Serão permitidos somente pesos positivos


## Datas Importantes

### Simplex

Datas       | Eventos
---------   | ------
30/03/19    | Início do Planejamento
15/04/19    | Criação da Estrutura Principal
22/04/19    | Cálculo de maximização
29/04/19    | Visualização da solução resumida - MVP 1
01/05/19    | Cálculo da minimização
06/05/19    | Análise de sensibilidade e passo a passo - MVP 2
30/05/2019  | Atualização do README


### Mochila

Datas | Eventos
---------   | ------
13/05/19    | Início do Planejamento
20/05/19    | Realização do algoritmo 
27/05/19    | Finalização da interface de usuário
30/05/2019  | Atualização do README

## Compatibilidade

Requisitos          | Ferramentas
---------           | ------
Navegadores         | Mozila Firefox, Chrome, Internet Explorer
Sistema Operacional | Ubuntu, Windows, Mac, RedHat

## Tecnologias

Tecnologias     | Ferramentas
---------       | ------
Front-End       | HTML, Javascript, JQuery, Bootstrap
Back-End        | PHP
Editor de Texto | Visual Studio Code

## Atividades Realizadas no Período

### Simplex

Código | Título | Tarefa | Situação | Observação
--------- | ------ | -------| -------| -------
1 | Maximizar | Montar a Tabela Simplex, e possibilitar o usuário a maximizar modelos de simplex com sistemas lineares. | Concluído | Apenas restrições de “<=”
2 | Minimizar | Montar a Tabela Simplex, e possibilitar o usuário a minimizar modelos de simplex com sistemas lineares. | Concluído | Apenas restrições de “<=”
3 | Adição de restrições | Possibilitar o usuário a adicionar inputs para maiores números de restrições. | Concluído |
4 | Remoção de restrições | Possibilitar o usuário a remover inputs para menores números de restrições. | Concluído |
5 | Demonstrar passo a passo | Demonstrar ao usuário as alterações na tabela causada pelas iterações do método simplex. | Concluído|
6  | Tabela de sensibilidade | Demonstrar ao usuário a tabela de sensibilidade. |Concluído|

### Mochila

Código | Título | Tarefa | Situação | Observação
--------- | ------ | -------| -------| -------
1 | Entrada de Dados | Permitir ao usuário a definição dos itens e suas caracteróisticas tais como nome, valor e peso |
2 | Solução do problema | Mostrar ao usuário os itens selecionados pelo algoritmo como qualificados | Concluído |
