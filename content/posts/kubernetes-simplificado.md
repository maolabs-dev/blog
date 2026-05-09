---
title: "Estrutura Básica de Deploy no Kubernetes: Namespaces, Deployments e Ingress"
date: 2026-05-10
tags:
  - kubernetes
  - devops
  - arquitetura
  - infraestrutura
---

**TL;DR;** A implantação de serviços no Kubernetes baseia-se na coordenação de três frentes arquiteturais: isolamento lógico (Namespaces e Secrets), gerenciamento declarativo de estado e rede interna (Deployments e Services), e roteamento de tráfego externo (Ingress). A união desses manifestos atende, na maioria dos cenários, aos requisitos básicos de operação de aplicações conteinerizadas.

A implantação de serviços no Kubernetes exige a definição de recursos específicos para garantir isolamento, execução e exposição da aplicação. Usualmente, uma arquitetura funcional é estabelecida coordenando três frentes principais de configuração.

### 1. Isolamento Lógico e Dados Sensíveis (Namespace e Secrets)

O `Namespace` fornece um mecanismo de escopo lógico dentro do cluster. Seu uso prático evita colisões de nomes de recursos e permite a segregação de ambientes, como homologação e produção.

Para o gerenciamento de credenciais, normalmente emprega-se o objeto `Secret`. Ele armazena dados confidenciais separadamente do código e dos manifestos de aplicação, isolando o estado sensível do controle de versão.

```yaml
apiVersion: v1
kind: Secret
metadata:
  name: app-credentials
  namespace: producao
type: Opaque
stringData:
  APP_KEY: "sua-chave-sensivel"

```

### 2. Estado Declarativo e Descoberta de Serviço (Deployment e Service)

O `Deployment` é o recurso da API responsável por gerenciar o estado declarativo de Pods e ReplicaSets. Ele assegura que a quantidade especificada de réplicas da aplicação esteja em execução e gerencia os limites computacionais de CPU e memória.

Como os Pods são inerentemente efêmeros e seus endereços IP mudam dinamicamente, usualmente utiliza-se um `Service` para atuar como um balanceador de carga interno. Ele provê um IP e um nome DNS estáveis, roteando o tráfego para os Pods que correspondam a um seletor específico (*label selector*).

```yaml
apiVersion: v1
kind: Service
metadata:
  name: backend-service
spec:
  selector:
    app: backend-api
  ports:
    - protocol: TCP
      port: 80
      targetPort: 8080

```

### 3. Exposição HTTP/HTTPS (Ingress)

Segundo a documentação da API, um `Ingress` gerencia o roteamento de tráfego externo para os serviços internos do cluster.

Na prática, ele consolida regras de roteamento baseadas em *host* ou *path* e, normalmente, centraliza a terminação SSL/TLS. Ele atua mapeando requisições (ex: `api.dominio.com`) para o `Service` correto configurado previamente.

```yaml
apiVersion: networking.k8s.io/v1
kind: Ingress
metadata:
  name: api-ingress
spec:
  rules:
  - host: api.dominio.com
    http:
      paths:
      - path: /
        pathType: Prefix
        backend:
          service:
            name: backend-service
            port: 
              number: 80

```

---

### Referências

* **Kubernetes Documentation:** Namespaces. Disponível em: [https://kubernetes.io/docs/concepts/overview/working-with-objects/namespaces/](https://kubernetes.io/docs/concepts/overview/working-with-objects/namespaces/)
* **Kubernetes Documentation:** Secrets. Disponível em: [https://kubernetes.io/docs/concepts/configuration/secret/](https://kubernetes.io/docs/concepts/configuration/secret/)
* **Kubernetes Documentation:** Deployments. Disponível em: [https://kubernetes.io/docs/concepts/workloads/controllers/deployment/](https://kubernetes.io/docs/concepts/workloads/controllers/deployment/)
* **Kubernetes Documentation:** Services. Disponível em: [https://kubernetes.io/docs/concepts/services-networking/service/](https://kubernetes.io/docs/concepts/services-networking/service/)
* **Kubernetes Documentation:** Ingress. Disponível em: [https://kubernetes.io/docs/concepts/services-networking/ingress/](https://kubernetes.io/docs/concepts/services-networking/ingress/)