---
title: "php2shell: atualizei o blog no dia seguinte ao patch"
slug: "php2shell"
date: "2026-10-22"
excerpt: "Duas falhas críticas no PHP permitem executar código remotamente sem login. Como conferi se o blog estava exposto e o que fiz para corrigir."
tags:
  - seguranca
  - php
  - devops
published: true
---

**TL;DR;** Em 21 de outubro de 2026 o PHP lançou correções para duas falhas críticas, apelidadas de "php2shell" (CVE-2026-109618 e CVE-2026-109619). Elas permitem execução remota de código sem autenticação em qualquer PHP que atenda requisições web. Este blog roda em PHP com FrankenPHP e estava na versão 8.3.31, vulnerável. Fixei a imagem base na 8.3.36, o pipeline fez o deploy e a produção já está corrigida.

### O que aconteceu

As duas falhas receberam nota 9.2 no CVSS v4.0. Uma está no tratamento de falta de memória, que deixa dados da requisição inicializados pela metade. A outra envolve um estado interno da requisição que continua sendo usado depois de a memória ter sido liberada.

O alcance é o que assusta: PHP-FPM, mod_php, php-cgi, FrankenPHP e LiteSpeed estão todos na lista. O próprio time do PHP aponta o mod_php como o cenário de maior risco, porque o ataque demonstrado dispara antes de qualquer código da aplicação rodar. Já existe código de prova de conceito público e há relatos de exploração ativa.

As versões corrigidas são **8.5.12, 8.4.27, 8.3.36 e 8.2.35**. Quem ainda está no 8.1 ou anterior não recebe correção, e a orientação da CISA é tirar esses sistemas do ar até migrar.

### Conferindo o blog

A primeira pergunta foi simples: qual PHP está rodando de verdade em produção? O Dockerfile dizia `dunglas/frankenphp:1-php8.3-alpine`, mas essa tag é flutuante. O que importa é a imagem que estava no pod:

```bash
kubectl exec -n maolabs deploy/maolabs-blog -- php -v
# PHP 8.3.31 (cli) (built: May  7 2026 16:48:36) (ZTS)
```

8.3.31 está abaixo do 8.3.36. Vulnerável.

### A correção

A imagem do FrankenPHP com o PHP 8.3.36 já tinha sido publicada no Docker Hub. Em vez de apenas refazer o build e torcer para o cache não reaproveitar a base antiga, troquei a tag flutuante por uma versão fixa:

```dockerfile
FROM dunglas/frankenphp:1-php8.3.36-alpine
```

Um commit, um push, e o pipeline do GitHub Actions fez o resto: teste, build, envio da imagem e reinício do deployment no Kubernetes. Poucos minutos depois:

```bash
kubectl exec -n maolabs deploy/maolabs-blog -- php -v
# PHP 8.3.36 (cli) (built: Oct 21 2026 22:54:28) (ZTS)
```

### O que fica

Fixar a versão resolve o problema de hoje, mas cria uma obrigação: a imagem não vai mais pegar patches sozinha. A troca vale a pena porque deixa explícito o que está em produção, desde que alguém acompanhe os alertas de segurança.

E a lição mais útil foi conferir o que realmente está rodando, não o que o arquivo de configuração diz. A tag `1-php8.3-alpine` passava a impressão de estar atualizada, mas o pod continuava com uma imagem de maio.

### Referências

* **PHP.net:** Security advisory. Disponível em: [https://www.php.net/archive/2026.php](https://www.php.net/archive/2026.php)
* **CISA:** Critical Vulnerabilities in PHP Require Immediate Patching. Disponível em: [https://www.cisa.gov/news-events/alerts/2026/10/21/critical-vulnerabilities-php-require-immediate-patching](https://www.cisa.gov/news-events/alerts/2026/10/21/critical-vulnerabilities-php-require-immediate-patching)
* **Rapid7:** CVE-2026-109618 and CVE-2026-109619. Disponível em: [https://www.rapid7.com/blog/post/etr-cve-2026-109618-and-cve-2026-109619-critical-unauthenticated-remote-code-execution-in-php/](https://www.rapid7.com/blog/post/etr-cve-2026-109618-and-cve-2026-109619-critical-unauthenticated-remote-code-execution-in-php/)
* **Tenable:** php2shell FAQ. Disponível em: [https://www.tenable.com/blog/php2shell-cve-2026-109618-cve-2026-109619-frequently-asked-questions-about-critical-php-remote](https://www.tenable.com/blog/php2shell-cve-2026-109618-cve-2026-109619-frequently-asked-questions-about-critical-php-remote)
