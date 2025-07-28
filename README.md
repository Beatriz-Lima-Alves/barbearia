# 🪒 Sistema de Barbearia - Gestão Completa

Um sistema web completo e moderno para gerenciamento de barbearias, desenvolvido em PHP com foco na facilidade de uso, funcionalidades essenciais para o negócio e automações inteligentes.

![Sistema de Barbearia](https://img.shields.io/badge/PHP-7.4+-blue) ![MySQL](https://img.shields.io/badge/MySQL-5.7+-orange) ![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-purple) ![License](https://img.shields.io/badge/License-MIT-green)

## 🌟 Características Principais

### 👥 **Gestão Completa de Clientes**

* ✅ Cadastro completo com dados pessoais e histórico
* ✅ Sistema de busca avançada com filtros
* ✅ Controle de aniversários para promoções especiais
* ✅ Histórico detalhado de agendamentos por cliente
* ✅ Estatísticas de fidelidade e frequência

### 📅 **Sistema de Agendamentos Inteligente**

* ✅ Interface intuitiva para agendamento
* ✅ **Verificação automática de horários disponíveis**
* ✅ Múltiplos status (agendado, confirmado, realizado, cancelado)
* ✅ **Validação inteligente de conflitos de horário**
* ✅ **Sistema de status por agendamento**
* ✅ Dashboard personalizado por barbeiro
* ✅ Controle de duração por serviço

### 👨‍💼 **Gestão de Usuários e Equipe**

* ✅ Sistema de permissões (Administrador vs Barbeiro)
* ✅ Controle de acesso seguro com autenticação robusta
* ✅ Perfis personalizados para cada barbeiro
* ✅ Especialidades por profissional
* ✅ Ativação/desativação de usuários

### ✂️ **Catálogo Avançado de Serviços**

* ✅ Organização por categorias (Corte, Barba, Combo, Especial)
* ✅ Controle detalhado de preços e duração
* ✅ **Estatísticas de popularidade e receita por serviço**
* ✅ Sistema de ativação/desativação
* ✅ Duplicação inteligente de serviços

### 💰 **Relatórios Financeiros Completos**

* ✅ **Dashboard financeiro com métricas em tempo real**
* ✅ **Receita por período, barbeiro e serviço**
* ✅ **Análise de performance individual**
* ✅ **Top clientes e serviços mais vendidos**
* ✅ **Gráficos interativos com Chart.js**
* ✅ **Exportação de relatórios em CSV**
* ✅ **Comparação com períodos anteriores**

### 🔒 **Sistema de Segurança e Controle**

* ✅ **Autenticação segura com controle de sessões**
* ✅ **Controle de permissões por tipo de usuário**
* ✅ **Validação de dados em todas as operações**
* ✅ **Proteção contra acessos não autorizados**
* ✅ **Logs de atividades do sistema**

## 🛠️ **Tecnologias Utilizadas**

| Tecnologia       | Versão | Função                      |
| ---------------- | ------ | --------------------------- |
| **PHP**          | 7.4+   | Backend e lógica de negócio |
| **MySQL**        | 5.7+   | Banco de dados              |
| **Bootstrap**    | 5.3    | Framework CSS responsivo    |
| **Chart.js**     | 3.x    | Gráficos interativos        |
| **Font Awesome** | 6.0    | Ícones                      |
| **PHPMailer**    | 6.x    | Sistema de emails           |
| **JavaScript**   | ES6+   | Interatividade frontend     |

## 🎯 **Arquitetura do Sistema**

```
Sistema Barbearia/
├── 📁 config/           # Configurações gerais
│   ├── config.php       # Configurações principais
│   ├── database.php     # Conexão com banco
│   └── email.php        # Configurações de email
├── 📁 app/
│   ├── 📁 controllers/  # Lógica de controle
│   ├── 📁 models/       # Modelos de dados
│   ├── 📁 views/        # Interfaces de usuário
│   └── 📁 helpers/      # Funções auxiliares
├── 📁 public/          # Arquivos públicos
└── 📁 database/        # Schemas e migrações
```

## 🚀 **Instalação Rápida**

### **Pré-requisitos**

* PHP 7.4 ou superior
* MySQL 5.7 ou superior
* Servidor web (Apache/Nginx)
* Servidor web configurado (Apache/Nginx)

### **1. Clone o Repositório**

```bash
git clone https://github.com/seu-usuario/sistema-barbearia.git
cd sistema-barbearia
```

### **2. Configure o Ambiente**

```bash
# Configure as permissões (Linux/Mac)
chmod -R 755 .
```

### **3. Configure o Banco de Dados**

```bash
# Crie o banco no MySQL
mysql -u root -p
CREATE DATABASE barbearia_db;
exit

# Importe o schema
mysql -u root -p barbearia_db < database/schema.sql
```

### **4. Configure as Variáveis**

```php
// config/config.php
define('DB_HOST', 'localhost');
define('DB_NAME', 'barbearia_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('SITE_URL', 'http://localhost/sistema-barbearia');

// Configurações adicionais conforme necessário
```

### **5. Acesse o Sistema**

```
URL: http://localhost/sistema-barbearia
👤 Email: admin@barbearia.com
🔑 Senha: admin123
```

## 🎮 **Como Usar**

### **Para Administradores**

1. **Dashboard Completo**: Visão geral de métricas e KPIs
2. **Gestão Total**: Controle de equipe, clientes e serviços
3. **Relatórios Avançados**: Análises financeiras detalhadas
4. **Configurações**: Personalização do sistema

### **Para Barbeiros**

1. **Agenda Pessoal**: Visualização dos próprios agendamentos
2. **Gestão de Clientes**: Acesso ao histórico dos clientes
3. **Atualização de Status**: Controle dos serviços realizados
4. **Dashboard Personalizado**: Métricas individuais

## 📊 **Funcionalidades Avançadas**

### **🎯 Gestão Inteligente de Agendamentos**

* **Controle de Status**: Acompanhamento completo do ciclo de vida
* **Validação de Conflitos**: Prevenção automática de sobreposições
* **Filtros Avançados**: Busca por data, barbeiro e status

### **📈 Relatórios Financeiros Inteligentes**

* **Receita por Barbeiro**: Performance individual detalhada
* **Análise de Serviços**: Popularidade e rentabilidade
* **Evolução Temporal**: Gráficos de crescimento
* **Top Clientes**: Identificação de clientes VIP
* **Exportação**: Relatórios em CSV para análise externa

### **🔐 Sistema de Segurança**

* **Autenticação Robusta**: Login seguro com sessões
* **Controle de Permissões**: Admin vs Barbeiro
* **Validações**: Proteção contra conflitos e erros
* **Logs de Auditoria**: Rastreamento de ações importantes

## 🎨 **Interface e Design**

* **🎨 Design Moderno**: Interface limpa e profissional
* **📱 Totalmente Responsivo**: Funciona em desktop, tablet e móvel
* **🌈 Tema Customizável**: Cores e branding personalizáveis
* **⚡ Performance Otimizada**: Carregamento rápido
* **🎯 UX Intuitiva**: Navegação fácil e intuitiva

## 📸 **Screenshots**

| Dashboard                               | Agendamentos                                  | Relatórios                                |
| --------------------------------------- | --------------------------------------------- | ----------------------------------------- |
| ![Dashboard](screenshots/dashboard.png) | ![Agendamentos](screenshots/agendamentos.png) | ![Relatórios](screenshots/relatorios.png) |

## 🔧 **Configurações Avançadas**

### **Banco de Dados**

1. Certifique-se que o MySQL está rodando
2. Verifique as credenciais em `config/config.php`
3. Importe o schema corretamente

### **Personalização Visual**

* Edite `public/css/custom.css` para cores personalizadas
* Modifique `app/views/layouts/main.php` para layout
* Ajuste logos e branding conforme necessário

### **Backup Automático**

```bash
# Script de backup diário
0 2 * * * mysqldump -u root -p barbearia_db > backup_$(date +%Y%m%d).sql
```

## 🤝 **Contribuindo**

1. **Fork** o projeto
2. **Crie** uma branch para sua feature (`git checkout -b feature/nova-funcionalidade`)
3. **Commit** suas mudanças (`git commit -m 'Adiciona nova funcionalidade'`)
4. **Push** para a branch (`git push origin feature/nova-funcionalidade`)
5. **Abra** um Pull Request

### **Diretrizes de Contribuição**

* Siga os padrões de código existentes
* Adicione testes para novas funcionalidades
* Documente mudanças significativas
* Mantenha compatibilidade com versões anteriores

## 🐛 **Resolução de Problemas**

### **Problemas Comuns**

| Problema                      | Solução                                             |
| ----------------------------- | --------------------------------------------------- |
| **Erro de conexão com banco** | Verifique credenciais em `config/config.php`        |
| **Dashboard não carrega**     | Verifique se o banco está configurado corretamente  |
| **Erro 500**                  | Verifique logs do servidor e permissões de arquivos |
| **Páginas não carregam**      | Verifique configuração do servidor web              |

### **Logs de Sistema**

* **Apache/Nginx**: `/var/log/apache2/error.log`
* **Sistema**: `logs/sistema.log`

## 📋 **Roadmap**

### **Versão 2.0** (Planejado)
* [ ] **API REST** completa
* [ ] **App mobile** nativo
* [ ] **Sistema de fidelidade** com pontos
* [ ] **Agendamento online** para clientes
* [ ] **Relatórios em PDF**
* [ ] **Sistema de backup automático**

### **Versão 1.5** (Em desenvolvimento)
* [ ] **Dashboard em tempo real**
* [ ] **Backup automático**
* [ ] **Tema escuro**
* [ ] **Múltiplas unidades**

## 📄 **Licença**

Este projeto está sob a licença **MIT**. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.

```
MIT License

Copyright (c) 2024 Sistema Barbearia

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction...
```

## 📞 **Suporte e Contato**

### **Canais de Suporte**

* 🐛 **Issues**: [GitHub Issues](https://github.com/seu-usuario/sistema-barbearia/issues)
* 📚 **Documentação**: [Wiki do Projeto](https://github.com/seu-usuario/sistema-barbearia/wiki)
* 💬 **Discussões**: [GitHub Discussions](https://github.com/seu-usuario/sistema-barbearia/discussions)
* 📧 **Email**: <blima0978@gmail.com>

### **Comunidade**

## 🏆 **Agradecimentos**

Agradecimentos especiais a:

* **Bootstrap Team** - Framework CSS incrível
* **Chart.js Team** - Gráficos interativos
* **PHPMailer Team** - Sistema de emails confiável
* **Font Awesome** - Ícones profissionais
* **Comunidade PHP** - Suporte e recursos

## ⭐ **Apoie o Projeto**

Se este projeto foi útil para você, considere:

* ⭐ **Dar uma estrela** no GitHub
* 🐛 **Reportar bugs** e sugerir melhorias
* 🤝 **Contribuir** com código ou documentação
* 💬 **Compartilhar** com outros desenvolvedores
* ☕ **Comprar um café** para os desenvolvedores

***

**Desenvolvido com ❤️ para modernizar a gestão de barbearias**


**🚀 Demonstração ao Vivo** | **📚 Documentação** | **💬 Suporte**
