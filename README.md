# Barbearia: Agendamento Online

Desafio técnico Laravel (Skala Code). Sistema simples de agendamento para uma barbearia.

## Como rodar

Requisitos: PHP 8.3 ou superior, Composer, Git e MySQL (XAMPP ou similar).

1. `git clone https://github.com/gianoliveira935-max/barbearia-agendamento.git`
2. `cd barbearia-agendamento`
3. `composer install`
4. `copy .env.example .env` (Windows) ou `cp .env.example .env` (Linux/Mac)
5. `php artisan key:generate`
6. Ligue o MySQL e crie um banco chamado `barbearia` (pelo phpMyAdmin ou com `CREATE DATABASE barbearia;`)
7. Confira no `.env`: `DB_CONNECTION=mysql`, `DB_DATABASE=barbearia`, `DB_USERNAME=root`, `DB_PASSWORD=` (vazio) e `APP_TIMEZONE=America/Sao_Paulo`
8. `php artisan migrate`
9. `php artisan serve`
10. Abra no navegador:
    - Serviços: http://localhost:8000/servicos
    - Agendamento do cliente: http://localhost:8000/agendar
    - Agenda do barbeiro: http://localhost:8000/agenda

Para testar: cadastre "Corte" (40 min, R$ 50,00) e "Barba" (20 min, R$ 30,00), agende um Corte às 10:00 e confira que a Barba não oferece 10:00 nem 10:20.

## O que foi feito

- Cadastro, listagem e exclusão de serviços (nome, duração em minutos e preço).
- Agendamento pelo cliente: escolhe serviço e data, vê os horários livres, informa nome e telefone.
- Horário de atendimento das 9h às 18h, com intervalo de almoço das 12h às 13h.
- Sem conflito: não é possível marcar dois agendamentos sobrepostos.
- Agenda do dia: lista dos agendamentos de uma data, em ordem de horário.

## Decisões e suposições

- **MySQL** (via XAMPP), seguindo a orientação do meu professor. SQLite seria mais fácil de rodar em outra máquina, mas o MySQL exige criar o banco antes.
- **Blade puro, sem CSS.** O visual não é avaliado e o prazo era curto.
- **Preço em centavos** (`price_cents`, inteiro), para evitar erros de arredondamento.
- **Cada agendamento guarda início e fim** (`starts_at` e `ends_at`). O conflito é checado por sobreposição: `existente.inicio < novo.fim E existente.fim > novo.inicio`. Assim, uma Barba às 9h20 é bloqueada se existe um Corte às 9h.
- **Horários de 20 em 20 minutos**, a menor duração dos serviços de exemplo (constante `STEP_MINUTES`).
- **Um único barbeiro:** qualquer agendamento ocupa a agenda inteira.
- **Expediente fixo** das 9h às 18h, todos os dias, sem folga. O serviço não pode terminar depois das 18h.
- **Disponibilidade conferida duas vezes:** ao mostrar os horários e ao gravar, para o caso de outra pessoa ter pego o horário.
- **Fuso horário** `America/Sao_Paulo`.
- Nomes de tabelas, colunas e classes em inglês (padrão do Laravel); interface e documentação em português.

## Planejado x entregue

Planejado e entregue: serviços, agendamento, expediente, conflito e agenda do dia.

Ficou de fora:
- **Opcionais** (cancelamento, remarcação, lembrete, faturamento do dia): priorizei os itens obrigatórios com qualidade.
- **Login para o barbeiro:** as telas `/servicos` e `/agenda` são públicas. Seria o próximo passo.
- **Concorrência:** a checagem dupla reduz o problema, mas dois pedidos simultâneos poderiam passar. Com mais tempo, eu usaria transação com `lockForUpdate()`.
- **Exclusão de serviço com agendamentos:** a chave estrangeira impede, mas o erro não é tratado de forma amigável.
- **Testes automatizados** da regra de conflito.
- Horário de almoço e de expediente estão fixos no código, não configuráveis pela tela.

## Uso de IA

Ferramenta: **Claude (Anthropic)**, como guia passo a passo. Sou iniciante em Laravel e usei a IA para aprender enquanto construía.

Principais prompts (parafraseados):
1. Enviei o PDF do desafio e pedi ajuda para fazê-lo, avisando que era iniciante e nunca tinha usado Laravel.
2. Pedi para explicar o que é um framework usando o que já conheço de lógica em C e Python.
3. Perguntei se podia usar XAMPP com MySQL (como o professor sugeriu) e qual banco seria melhor para quem for avaliar.
4.  Pedi o passo a passo do agendamento com horários livres, conflito e expediente, e depois o código com a lógica separada em partes.

Revisei e testei o código gerado, e adaptei partes durante o desenvolvimento (por exemplo, o intervalo de almoço).