---
title: "Arquitetura de Baixo Atrito"
slug: "arquitetura-baixo-atrito"
date: "2026-05-09"
excerpt: "Por que escolher a simplicidade sobre a complexidade em projetos modernos?"
tags:
  - arquitetura
  - filosofia
published: true
---

Comecei a revisitar algumas ideias sobre arquitetura depois de ver várias discussões na bolha dev sobre IA e produtividade.

Hoje uso principalmente NestJS com React, Java com Angular. Funciona bem e faz sentido principalmente em ambientes maiores e mais organizados. Mas comecei a perceber um custo da arquitetura moderna que fica mais evidente no uso diário com IA: o custo de contexto.

Para resolver um problema simples, muitas vezes preciso abrir:

* frontend
* backend
* DTO
* hook
* client HTTP
* autenticação
* estado React

Não é só “mais código”. É fragmentação mental.

E isso fica ainda mais evidente quando usamos IA o tempo inteiro para programar.

A IA funciona melhor quando:

* o fluxo é linear
* os arquivos ficam próximos
* as convenções são previsíveis
* existe menos indireção

Foi aí que comecei a revisitar mentalmente frameworks como Laravel e Rails.

Uma coisa que me marcou no Laravel era a sensação de imediatismo. Você alterava um arquivo, atualizava a página e pronto. Sem rebuild, sem hot reload, sem um pipeline inteiro funcionando por trás.

Na época isso parecia apenas uma característica do PHP. Hoje vejo que isso também reduz bastante atrito no desenvolvimento.

Curiosamente, várias tendências atuais parecem caminhar para reduzir complexidade operacional:

* Server Actions
* HTMX
* Hotwire
* Livewire
* monólitos modulares
* frameworks fullstack

Não acho que exista stack perfeita. E também acho que frameworks mais enterprise continuam fazendo bastante sentido, principalmente em organizações públicas e ambientes mais tradicionais, onde as stacks são definidas institucionalmente e mudanças costumam acontecer mais devagar.

Mas tenho a impressão de que a era IA começa a valorizar algumas coisas diferentes:

* previsibilidade
* convenção
* proximidade entre partes do sistema
* menos arquivos
* menos indireção
* loops mais curtos entre mudar e testar

Talvez frameworks como Laravel tenham envelhecido melhor do que muita gente imaginava.
