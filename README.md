# blog.maolabs

Um blog pessoal focado em **baixíssimo atrito**, minimalismo e performance.

## Características
- 📄 **Single Source of Truth**: Posts em Markdown (`content/posts/`).
- 🛠️ **Zero Build**: CSS Puro e PHP nativo. Sem processos de build complexos no dev.
- 🌓 **Dark Mode**: Nativo e persistente.
- ♿ **Acessibilidade**: Foco em navegação por teclado e leitores de tela.
- 🔍 **Busca Minimalista**: Filtro instantâneo direto no filesystem.
- ⚡ **Performance**: SSR puro, sem JavaScript pesado.
- 🐳 **Docker Ready**: Imagens otimizadas com Alpine Linux.
- ☸️ **Kubernetes**: Manifestos inclusos para deploy em VPS.

## Como usar
1. Adicione seus posts em `content/posts/`.
2. Rode `php artisan serve`.
3. Acesse `http://localhost:8000`.

## Testes
Para garantir a integridade dos posts e das rotas:
```bash
php artisan test
```

## Deploy (Kubernetes)
1. Gere sua chave: `php artisan key:generate --show`.
2. Configure o `k8s/setup.yaml` com a sua chave.
3. Aplique os manifestos:
   ```bash
   kubectl apply -f k8s/setup.yaml
   kubectl apply -f k8s/app.yaml
   kubectl apply -f k8s/ingress.yaml
   ```
