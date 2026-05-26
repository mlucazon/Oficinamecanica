-- ============================================================
-- AutoTech Pro - Banco consolidado
-- Compatível com MySQL/Railway/TablePlus
--
-- Como usar no Railway:
-- 1. Conecte no banco `railway` pelo TablePlus.
-- 2. Execute este arquivo com o banco `railway` selecionado.
-- 3. No Railway, deixe RUN_MIGRATIONS=false se importar este arquivo completo.
--
-- Este arquivo ja inclui as tabelas das migrations e os dados iniciais
-- que estavam espalhados nos seeders e scripts SQL do projeto.
-- ============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS conta_acesso_solicitacoes;
DROP TABLE IF EXISTS avaliacoes_os;
DROP TABLE IF EXISTS notificacoes;
DROP TABLE IF EXISTS garantias;
DROP TABLE IF EXISTS fotos_os;
DROP TABLE IF EXISTS itens_os;
DROP TABLE IF EXISTS ordens_servico;
DROP TABLE IF EXISTS modelos_veiculos;
DROP TABLE IF EXISTS marcas_veiculos;
DROP TABLE IF EXISTS cidades;
DROP TABLE IF EXISTS estados;
DROP TABLE IF EXISTS mecanicos;
DROP TABLE IF EXISTS veiculos;
DROP TABLE IF EXISTS clientes;
DROP TABLE IF EXISTS pecas;
DROP TABLE IF EXISTS servicos;
DROP TABLE IF EXISTS cache;
DROP TABLE IF EXISTS sessions;
DROP TABLE IF EXISTS password_reset_tokens;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS migrations;

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- Controle de migrations Laravel
-- ============================================================

CREATE TABLE migrations (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    migration VARCHAR(255) NOT NULL,
    batch INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Autenticacao e sessao
-- ============================================================

CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('gerente','atendente','mecanico','cliente') NOT NULL DEFAULT 'cliente',
    profile_photo_path VARCHAR(255) NULL,
    remember_token VARCHAR(100) NULL,
    password_change_requested_at TIMESTAMP NULL,
    last_seen_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE password_reset_tokens (
    email VARCHAR(255) PRIMARY KEY,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE sessions (
    id VARCHAR(255) PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    payload LONGTEXT NOT NULL,
    last_activity INT NOT NULL,
    INDEX sessions_user_id_index (user_id),
    INDEX sessions_last_activity_index (last_activity)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE cache (
    `key` VARCHAR(255) PRIMARY KEY,
    value MEDIUMTEXT NOT NULL,
    expiration INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Clientes, veiculos e mecanicos
-- ============================================================

CREATE TABLE clientes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    nome VARCHAR(150) NOT NULL,
    cpf VARCHAR(14) NOT NULL UNIQUE,
    telefone VARCHAR(20) NOT NULL,
    email VARCHAR(150) NULL,
    endereco VARCHAR(255) NULL,
    cidade VARCHAR(100) NULL,
    estado CHAR(2) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT clientes_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE veiculos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    cliente_id BIGINT UNSIGNED NOT NULL,
    placa VARCHAR(10) NOT NULL UNIQUE,
    marca VARCHAR(80) NOT NULL,
    modelo VARCHAR(80) NOT NULL,
    ano SMALLINT NOT NULL,
    cor VARCHAR(50) NULL,
    chassi VARCHAR(50) NULL,
    km_atual INT UNSIGNED NOT NULL DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT veiculos_cliente_id_foreign FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE mecanicos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    nome VARCHAR(150) NOT NULL,
    cpf VARCHAR(14) NULL UNIQUE,
    telefone VARCHAR(20) NULL,
    especialidade VARCHAR(100) NULL,
    ativo TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT mecanicos_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Localizacao
-- ============================================================

CREATE TABLE estados (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    uf CHAR(2) NOT NULL UNIQUE,
    nome VARCHAR(80) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE cidades (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    estado_id BIGINT UNSIGNED NOT NULL,
    nome VARCHAR(120) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    UNIQUE KEY cidades_estado_id_nome_unique (estado_id, nome),
    CONSTRAINT cidades_estado_id_foreign FOREIGN KEY (estado_id) REFERENCES estados(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Catalogo de marcas e modelos
-- ============================================================

CREATE TABLE marcas_veiculos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(80) NOT NULL UNIQUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE modelos_veiculos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    marca_id BIGINT UNSIGNED NOT NULL,
    nome VARCHAR(120) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    UNIQUE KEY modelos_veiculos_marca_id_nome_unique (marca_id, nome),
    CONSTRAINT modelos_veiculos_marca_id_foreign FOREIGN KEY (marca_id) REFERENCES marcas_veiculos(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Oficina
-- ============================================================

CREATE TABLE servicos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    descricao TEXT NULL,
    categoria VARCHAR(80) NULL,
    valor_mao_obra DECIMAL(10,2) NOT NULL DEFAULT 0,
    tempo_estimado INT NULL COMMENT 'minutos',
    ativo TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE pecas (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    codigo VARCHAR(80) NULL UNIQUE,
    fabricante VARCHAR(100) NULL,
    preco_custo DECIMAL(10,2) NOT NULL DEFAULT 0,
    preco_venda DECIMAL(10,2) NOT NULL DEFAULT 0,
    estoque INT NOT NULL DEFAULT 0,
    estoque_minimo INT NOT NULL DEFAULT 5,
    unidade VARCHAR(20) NOT NULL DEFAULT 'un',
    ativo TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX pecas_estoque_estoque_minimo_index (estoque, estoque_minimo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE ordens_servico (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    numero VARCHAR(20) NOT NULL UNIQUE,
    cliente_id BIGINT UNSIGNED NOT NULL,
    veiculo_id BIGINT UNSIGNED NOT NULL,
    mecanico_id BIGINT UNSIGNED NULL,
    status ENUM(
        'aguardando_aceitacao',
        'solicitacao_aceita',
        'solicitacao_recusada',
        'em_diagnostico',
        'orcamento_enviado_atendente',
        'aguardando_aprovacao',
        'aprovada',
        'em_execucao',
        'aguardando_pecas',
        'finalizada',
        'cancelada',
        'aberta'
    ) NOT NULL DEFAULT 'aguardando_aceitacao',
    sintomas TEXT NULL,
    diagnostico TEXT NULL,
    observacoes TEXT NULL,
    motivo_recusa VARCHAR(120) NULL,
    detalhes_recusa TEXT NULL,
    km_entrada INT UNSIGNED NULL,
    valor_servicos DECIMAL(10,2) NOT NULL DEFAULT 0,
    valor_pecas DECIMAL(10,2) NOT NULL DEFAULT 0,
    valor_desconto DECIMAL(10,2) NOT NULL DEFAULT 0,
    valor_total DECIMAL(10,2) NOT NULL DEFAULT 0,
    aprovado_cliente TINYINT(1) NOT NULL DEFAULT 0,
    data_aprovacao TIMESTAMP NULL,
    data_previsao DATE NULL,
    data_conclusao TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX ordens_servico_status_index (status),
    INDEX ordens_servico_created_at_index (created_at),
    CONSTRAINT ordens_servico_cliente_id_foreign FOREIGN KEY (cliente_id) REFERENCES clientes(id),
    CONSTRAINT ordens_servico_veiculo_id_foreign FOREIGN KEY (veiculo_id) REFERENCES veiculos(id),
    CONSTRAINT ordens_servico_mecanico_id_foreign FOREIGN KEY (mecanico_id) REFERENCES mecanicos(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE itens_os (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    os_id BIGINT UNSIGNED NOT NULL,
    tipo ENUM('servico','peca') NOT NULL,
    servico_id BIGINT UNSIGNED NULL,
    peca_id BIGINT UNSIGNED NULL,
    descricao VARCHAR(255) NOT NULL,
    quantidade DECIMAL(10,3) NOT NULL DEFAULT 1,
    valor_unitario DECIMAL(10,2) NOT NULL DEFAULT 0,
    valor_total DECIMAL(10,2) NOT NULL DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT itens_os_os_id_foreign FOREIGN KEY (os_id) REFERENCES ordens_servico(id) ON DELETE CASCADE,
    CONSTRAINT itens_os_servico_id_foreign FOREIGN KEY (servico_id) REFERENCES servicos(id) ON DELETE SET NULL,
    CONSTRAINT itens_os_peca_id_foreign FOREIGN KEY (peca_id) REFERENCES pecas(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE fotos_os (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    os_id BIGINT UNSIGNED NOT NULL,
    path VARCHAR(255) NOT NULL,
    tipo ENUM('entrada','saida','processo') NOT NULL DEFAULT 'entrada',
    lado ENUM('frontal','traseira','lateral_dir','lateral_esq','interior','outro') NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT fotos_os_os_id_foreign FOREIGN KEY (os_id) REFERENCES ordens_servico(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE garantias (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    os_id BIGINT UNSIGNED NOT NULL,
    descricao TEXT NOT NULL,
    valor DECIMAL(10,2) NOT NULL DEFAULT 0,
    status VARCHAR(20) NOT NULL DEFAULT 'aceita',
    data_inicio DATE NOT NULL,
    data_fim DATE NOT NULL,
    acionada TINYINT(1) NOT NULL DEFAULT 0,
    data_acionamento TIMESTAMP NULL,
    observacao TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT garantias_os_id_foreign FOREIGN KEY (os_id) REFERENCES ordens_servico(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE notificacoes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    os_id BIGINT UNSIGNED NOT NULL,
    tipo ENUM('solicitacao_os','atualizacao') NOT NULL DEFAULT 'solicitacao_os',
    status ENUM('pendente','aceita','recusada') NOT NULL DEFAULT 'pendente',
    lida TINYINT(1) NOT NULL DEFAULT 0,
    mensagem TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX notificacoes_user_id_status_lida_index (user_id, status, lida),
    CONSTRAINT notificacoes_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT notificacoes_os_id_foreign FOREIGN KEY (os_id) REFERENCES ordens_servico(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE avaliacoes_os (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    os_id BIGINT UNSIGNED NOT NULL UNIQUE,
    cliente_id BIGINT UNSIGNED NOT NULL,
    nota TINYINT UNSIGNED NOT NULL,
    comentario TEXT NOT NULL,
    foto_antes_path VARCHAR(255) NULL,
    foto_depois_path VARCHAR(255) NULL,
    resposta TEXT NULL,
    respondido_por BIGINT UNSIGNED NULL,
    respondido_em TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT avaliacoes_os_os_id_foreign FOREIGN KEY (os_id) REFERENCES ordens_servico(id) ON DELETE CASCADE,
    CONSTRAINT avaliacoes_os_cliente_id_foreign FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE,
    CONSTRAINT avaliacoes_os_respondido_por_foreign FOREIGN KEY (respondido_por) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE conta_acesso_solicitacoes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    solicitante_id BIGINT UNSIGNED NOT NULL,
    gerente_id BIGINT UNSIGNED NULL,
    status ENUM('pendente','aprovada','recusada') NOT NULL DEFAULT 'pendente',
    respondido_em TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT conta_acesso_solicitacoes_solicitante_id_foreign FOREIGN KEY (solicitante_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT conta_acesso_solicitacoes_gerente_id_foreign FOREIGN KEY (gerente_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Migrations marcadas como aplicadas
-- ============================================================

INSERT INTO migrations (migration, batch) VALUES
('2024_01_01_000001_create_users_table', 1),
('2024_01_01_000002_create_clientes_veiculos_mecanicos', 1),
('2024_01_01_000003_create_oficina_tables', 1),
('2024_01_01_000004_create_notificacoes_table', 1),
('2026_05_11_012353_update_os_status_enum', 1),
('2026_05_13_000001_add_password_change_requested_at_to_users_table', 1),
('2026_05_13_000002_update_ordens_servico_workflow', 1),
('2026_05_14_000001_add_profile_photo_path_to_users_table', 1),
('2026_05_14_000002_create_estados_cidades_tables', 1),
('2026_05_14_000003_add_last_seen_at_to_users_table', 1),
('2026_05_17_220000_add_offer_fields_to_garantias', 1),
('2026_05_25_000001_create_avaliacoes_os_table', 1),
('2026_05_25_000002_create_conta_acesso_solicitacoes_table', 1);

-- ============================================================
-- Dados iniciais: usuarios
-- Senhas:
-- - usuarios dos seeders: 12345678
-- - gerente legado: password
-- ============================================================

INSERT INTO users (id, name, email, password, role, created_at, updated_at) VALUES
(1, 'Joao Atendente', 'joao@autotech.com', '$2y$10$8J8TlVSdruspO64RS383FOPg2lEbZ02a4FkFjkZ4c.FTxYXJk/a.G', 'atendente', NOW(), NOW()),
(2, 'Antonio Gerente', 'antonio@autotech.com', '$2y$10$8J8TlVSdruspO64RS383FOPg2lEbZ02a4FkFjkZ4c.FTxYXJk/a.G', 'gerente', NOW(), NOW()),
(3, 'Carlos Mecanico', 'mecanico@autotech.com', '$2y$10$8J8TlVSdruspO64RS383FOPg2lEbZ02a4FkFjkZ4c.FTxYXJk/a.G', 'mecanico', NOW(), NOW()),
(4, 'Jose Mecanico', 'jose@autotech.com', '$2y$10$8J8TlVSdruspO64RS383FOPg2lEbZ02a4FkFjkZ4c.FTxYXJk/a.G', 'mecanico', NOW(), NOW()),
(5, 'Pedro Cliente', 'cliente@autotech.com', '$2y$10$8J8TlVSdruspO64RS383FOPg2lEbZ02a4FkFjkZ4c.FTxYXJk/a.G', 'cliente', NOW(), NOW()),
(6, 'Gerente AutoTech', 'gerente@autotech.com', '$2y$12$IWDNz1GFVJlXP0PH7a5YqO8fHGHkJvvgPlXqiYOQQwz6mYuT5WS7K', 'gerente', NOW(), NOW());

INSERT INTO mecanicos (user_id, nome, cpf, telefone, ativo, created_at, updated_at) VALUES
(3, 'Carlos Mecanico', '000.000.000-01', '(00) 00000-0001', 1, NOW(), NOW()),
(4, 'Jose Mecanico', '000.000.000-02', '(00) 00000-0002', 1, NOW(), NOW());

INSERT INTO clientes (id, user_id, nome, cpf, telefone, email, created_at, updated_at) VALUES
(1, 5, 'Pedro Cliente', '000.000.000-03', '(00) 00000-0003', 'cliente@autotech.com', NOW(), NOW());

-- ============================================================
-- Dados iniciais: estados e cidades
-- ============================================================

INSERT INTO estados (id, uf, nome, created_at, updated_at) VALUES
(1,'AC','Acre',NOW(),NOW()),(2,'AL','Alagoas',NOW(),NOW()),(3,'AP','Amapa',NOW(),NOW()),
(4,'AM','Amazonas',NOW(),NOW()),(5,'BA','Bahia',NOW(),NOW()),(6,'CE','Ceara',NOW(),NOW()),
(7,'DF','Distrito Federal',NOW(),NOW()),(8,'ES','Espirito Santo',NOW(),NOW()),(9,'GO','Goias',NOW(),NOW()),
(10,'MA','Maranhao',NOW(),NOW()),(11,'MT','Mato Grosso',NOW(),NOW()),(12,'MS','Mato Grosso do Sul',NOW(),NOW()),
(13,'MG','Minas Gerais',NOW(),NOW()),(14,'PA','Para',NOW(),NOW()),(15,'PB','Paraiba',NOW(),NOW()),
(16,'PR','Parana',NOW(),NOW()),(17,'PE','Pernambuco',NOW(),NOW()),(18,'PI','Piaui',NOW(),NOW()),
(19,'RJ','Rio de Janeiro',NOW(),NOW()),(20,'RN','Rio Grande do Norte',NOW(),NOW()),
(21,'RS','Rio Grande do Sul',NOW(),NOW()),(22,'RO','Rondonia',NOW(),NOW()),(23,'RR','Roraima',NOW(),NOW()),
(24,'SC','Santa Catarina',NOW(),NOW()),(25,'SP','Sao Paulo',NOW(),NOW()),(26,'SE','Sergipe',NOW(),NOW()),
(27,'TO','Tocantins',NOW(),NOW());

INSERT INTO cidades (estado_id, nome, created_at, updated_at) VALUES
(1,'Rio Branco',NOW(),NOW()),(1,'Cruzeiro do Sul',NOW(),NOW()),
(2,'Maceio',NOW(),NOW()),(2,'Arapiraca',NOW(),NOW()),
(3,'Macapa',NOW(),NOW()),(3,'Santana',NOW(),NOW()),
(4,'Manaus',NOW(),NOW()),(4,'Parintins',NOW(),NOW()),
(5,'Salvador',NOW(),NOW()),(5,'Feira de Santana',NOW(),NOW()),(5,'Vitoria da Conquista',NOW(),NOW()),
(6,'Fortaleza',NOW(),NOW()),(6,'Caucaia',NOW(),NOW()),(6,'Juazeiro do Norte',NOW(),NOW()),(6,'Maracanau',NOW(),NOW()),(6,'Sobral',NOW(),NOW()),(6,'Crato',NOW(),NOW()),(6,'Itapipoca',NOW(),NOW()),(6,'Maranguape',NOW(),NOW()),
(7,'Brasilia',NOW(),NOW()),
(8,'Vitoria',NOW(),NOW()),(8,'Vila Velha',NOW(),NOW()),(8,'Serra',NOW(),NOW()),
(9,'Goiania',NOW(),NOW()),(9,'Aparecida de Goiania',NOW(),NOW()),(9,'Anapolis',NOW(),NOW()),
(10,'Sao Luis',NOW(),NOW()),(10,'Imperatriz',NOW(),NOW()),
(11,'Cuiaba',NOW(),NOW()),(11,'Varzea Grande',NOW(),NOW()),
(12,'Campo Grande',NOW(),NOW()),(12,'Dourados',NOW(),NOW()),
(13,'Belo Horizonte',NOW(),NOW()),(13,'Uberlandia',NOW(),NOW()),(13,'Contagem',NOW(),NOW()),
(14,'Belem',NOW(),NOW()),(14,'Ananindeua',NOW(),NOW()),(14,'Santarem',NOW(),NOW()),
(15,'Joao Pessoa',NOW(),NOW()),(15,'Campina Grande',NOW(),NOW()),
(16,'Curitiba',NOW(),NOW()),(16,'Londrina',NOW(),NOW()),(16,'Maringa',NOW(),NOW()),
(17,'Recife',NOW(),NOW()),(17,'Jaboatao dos Guararapes',NOW(),NOW()),(17,'Olinda',NOW(),NOW()),
(18,'Teresina',NOW(),NOW()),(18,'Parnaiba',NOW(),NOW()),
(19,'Rio de Janeiro',NOW(),NOW()),(19,'Niteroi',NOW(),NOW()),(19,'Sao Goncalo',NOW(),NOW()),
(20,'Natal',NOW(),NOW()),(20,'Mossoro',NOW(),NOW()),
(21,'Porto Alegre',NOW(),NOW()),(21,'Caxias do Sul',NOW(),NOW()),(21,'Pelotas',NOW(),NOW()),
(22,'Porto Velho',NOW(),NOW()),(22,'Ji-Parana',NOW(),NOW()),
(23,'Boa Vista',NOW(),NOW()),
(24,'Florianopolis',NOW(),NOW()),(24,'Joinville',NOW(),NOW()),(24,'Blumenau',NOW(),NOW()),
(25,'Sao Paulo',NOW(),NOW()),(25,'Campinas',NOW(),NOW()),(25,'Santos',NOW(),NOW()),(25,'Guarulhos',NOW(),NOW()),
(26,'Aracaju',NOW(),NOW()),(26,'Nossa Senhora do Socorro',NOW(),NOW()),
(27,'Palmas',NOW(),NOW()),(27,'Araguaina',NOW(),NOW());

-- ============================================================
-- Dados iniciais: marcas e modelos
-- ============================================================

INSERT INTO marcas_veiculos (id, nome, created_at, updated_at) VALUES
(1,'Fiat',NOW(),NOW()),(2,'Volkswagen',NOW(),NOW()),(3,'Chevrolet',NOW(),NOW()),(4,'Ford',NOW(),NOW()),
(5,'Renault',NOW(),NOW()),(6,'Toyota',NOW(),NOW()),(7,'Honda',NOW(),NOW()),(8,'Nissan',NOW(),NOW()),
(9,'Hyundai',NOW(),NOW()),(10,'Kia',NOW(),NOW()),(11,'Peugeot',NOW(),NOW()),(12,'Citroen',NOW(),NOW()),
(13,'Jeep',NOW(),NOW()),(14,'Chery',NOW(),NOW()),(15,'Suzuki',NOW(),NOW()),(16,'BMW',NOW(),NOW()),
(17,'Mercedes-Benz',NOW(),NOW()),(18,'Audi',NOW(),NOW()),(19,'Lexus',NOW(),NOW()),(20,'Subaru',NOW(),NOW());

INSERT INTO modelos_veiculos (marca_id, nome, created_at, updated_at) VALUES
(1,'Palio',NOW(),NOW()),(1,'Uno',NOW(),NOW()),(1,'Punto',NOW(),NOW()),(1,'Argo',NOW(),NOW()),(1,'Cronos',NOW(),NOW()),(1,'Mobi',NOW(),NOW()),(1,'Siena',NOW(),NOW()),(1,'Strada',NOW(),NOW()),(1,'Toro',NOW(),NOW()),(1,'Doblo',NOW(),NOW()),
(2,'Gol',NOW(),NOW()),(2,'Voyage',NOW(),NOW()),(2,'Saveiro',NOW(),NOW()),(2,'Parati',NOW(),NOW()),(2,'Polo',NOW(),NOW()),(2,'Virtus',NOW(),NOW()),(2,'T-Cross',NOW(),NOW()),(2,'Tiguan',NOW(),NOW()),(2,'Jetta',NOW(),NOW()),(2,'Passat',NOW(),NOW()),
(3,'Onix',NOW(),NOW()),(3,'Prisma',NOW(),NOW()),(3,'Cobalt',NOW(),NOW()),(3,'Cruze',NOW(),NOW()),(3,'Tracker',NOW(),NOW()),(3,'Equinox',NOW(),NOW()),(3,'S10',NOW(),NOW()),(3,'Montana',NOW(),NOW()),(3,'Spin',NOW(),NOW()),(3,'Joy',NOW(),NOW()),
(4,'Ka',NOW(),NOW()),(4,'Ka+',NOW(),NOW()),(4,'Ecosport',NOW(),NOW()),(4,'Focus',NOW(),NOW()),(4,'Fusion',NOW(),NOW()),(4,'Ranger',NOW(),NOW()),(4,'Edge',NOW(),NOW()),(4,'Territory',NOW(),NOW()),(4,'Bronco',NOW(),NOW()),(4,'Mustang',NOW(),NOW()),
(5,'Sandero',NOW(),NOW()),(5,'Logan',NOW(),NOW()),(5,'Stepway',NOW(),NOW()),(5,'Duster',NOW(),NOW()),(5,'Captur',NOW(),NOW()),(5,'Kwid',NOW(),NOW()),(5,'Oroch',NOW(),NOW()),(5,'Fluence',NOW(),NOW()),(5,'Megane',NOW(),NOW()),(5,'Zoe',NOW(),NOW()),
(6,'Corolla',NOW(),NOW()),(6,'Etios',NOW(),NOW()),(6,'Yaris',NOW(),NOW()),(6,'Prius',NOW(),NOW()),(6,'Hilux',NOW(),NOW()),(6,'SW4',NOW(),NOW()),(6,'RAV4',NOW(),NOW()),(6,'Camry',NOW(),NOW()),(6,'Fielder',NOW(),NOW()),(6,'Land Cruiser',NOW(),NOW()),
(7,'Civic',NOW(),NOW()),(7,'City',NOW(),NOW()),(7,'Fit',NOW(),NOW()),(7,'HR-V',NOW(),NOW()),(7,'CR-V',NOW(),NOW()),(7,'Accord',NOW(),NOW()),(7,'Civic Type R',NOW(),NOW()),(7,'WR-V',NOW(),NOW()),(7,'Passport',NOW(),NOW()),(7,'Pilot',NOW(),NOW()),
(8,'Versa',NOW(),NOW()),(8,'Sentra',NOW(),NOW()),(8,'Tiida',NOW(),NOW()),(8,'March',NOW(),NOW()),(8,'Kicks',NOW(),NOW()),(8,'Rogue',NOW(),NOW()),(8,'X-Trail',NOW(),NOW()),(8,'Frontier',NOW(),NOW()),(8,'Frontier Attack',NOW(),NOW()),(8,'Leaf',NOW(),NOW()),
(9,'HB20',NOW(),NOW()),(9,'HB20S',NOW(),NOW()),(9,'Creta',NOW(),NOW()),(9,'Tucson',NOW(),NOW()),(9,'Elantra',NOW(),NOW()),(9,'Veloster',NOW(),NOW()),(9,'Santa Fe',NOW(),NOW()),(9,'Azera',NOW(),NOW()),(9,'i30',NOW(),NOW()),(9,'Kona',NOW(),NOW()),
(10,'Sportage',NOW(),NOW()),(10,'Sorento',NOW(),NOW()),(10,'Seltos',NOW(),NOW()),(10,'Cerato',NOW(),NOW()),(10,'Rio',NOW(),NOW()),(10,'K5',NOW(),NOW()),(10,'Stonic',NOW(),NOW()),(10,'Telluride',NOW(),NOW()),(10,'Mohave',NOW(),NOW()),(10,'EV6',NOW(),NOW()),
(11,'208',NOW(),NOW()),(11,'2008',NOW(),NOW()),(11,'3008',NOW(),NOW()),(11,'408',NOW(),NOW()),(11,'308',NOW(),NOW()),(11,'Partner',NOW(),NOW()),(11,'Rifter',NOW(),NOW()),(11,'508',NOW(),NOW()),(11,'208 GT',NOW(),NOW()),(11,'3008 Allure',NOW(),NOW()),
(12,'C3',NOW(),NOW()),(12,'C4',NOW(),NOW()),(12,'C4 Cactus',NOW(),NOW()),(12,'Aircross',NOW(),NOW()),(12,'C5 Aircross',NOW(),NOW()),(12,'Berlingo',NOW(),NOW()),(12,'Jumpy',NOW(),NOW()),(12,'C3 Picasso',NOW(),NOW()),(12,'Grand C4 Picasso',NOW(),NOW()),(12,'e-C4',NOW(),NOW()),
(13,'Renegade',NOW(),NOW()),(13,'Compass',NOW(),NOW()),(13,'Commander',NOW(),NOW()),(13,'Cherokee',NOW(),NOW()),(13,'Grand Cherokee',NOW(),NOW()),(13,'Wrangler',NOW(),NOW()),(13,'Gladiator',NOW(),NOW()),(13,'Renegade Turbo',NOW(),NOW()),(13,'Compass Trailhawk',NOW(),NOW()),(13,'Willys',NOW(),NOW()),
(14,'Tiggo 2',NOW(),NOW()),(14,'Tiggo 3',NOW(),NOW()),(14,'Tiggo 5',NOW(),NOW()),(14,'Tiggo 7',NOW(),NOW()),(14,'Arrizo 5',NOW(),NOW()),(14,'Arrizo 6',NOW(),NOW()),(14,'Arrizo 8',NOW(),NOW()),(14,'QQ',NOW(),NOW()),(14,'Karry',NOW(),NOW()),(14,'Face',NOW(),NOW()),
(15,'Jimny',NOW(),NOW()),(15,'Vitara',NOW(),NOW()),(15,'S-Cross',NOW(),NOW()),(15,'Swift',NOW(),NOW()),(15,'Baleno',NOW(),NOW()),(15,'Grand Vitara',NOW(),NOW()),(15,'Erzatz',NOW(),NOW()),(15,'Ignis',NOW(),NOW()),(15,'Celerio',NOW(),NOW()),(15,'Carry',NOW(),NOW()),
(16,'320i',NOW(),NOW()),(16,'318i',NOW(),NOW()),(16,'X1',NOW(),NOW()),(16,'X3',NOW(),NOW()),(16,'X5',NOW(),NOW()),(16,'X6',NOW(),NOW()),(16,'Z4',NOW(),NOW()),(16,'M3',NOW(),NOW()),(16,'M5',NOW(),NOW()),(16,'iX',NOW(),NOW()),
(17,'C180',NOW(),NOW()),(17,'C200',NOW(),NOW()),(17,'E200',NOW(),NOW()),(17,'E300',NOW(),NOW()),(17,'GLA',NOW(),NOW()),(17,'GLB',NOW(),NOW()),(17,'CLA',NOW(),NOW()),(17,'GLE',NOW(),NOW()),(17,'GLC',NOW(),NOW()),(17,'S500',NOW(),NOW()),
(18,'A3',NOW(),NOW()),(18,'A4',NOW(),NOW()),(18,'A5',NOW(),NOW()),(18,'A6',NOW(),NOW()),(18,'Q3',NOW(),NOW()),(18,'Q5',NOW(),NOW()),(18,'Q7',NOW(),NOW()),(18,'TT',NOW(),NOW()),(18,'RS3',NOW(),NOW()),(18,'E-tron',NOW(),NOW()),
(19,'IS',NOW(),NOW()),(19,'ES',NOW(),NOW()),(19,'GS',NOW(),NOW()),(19,'LS',NOW(),NOW()),(19,'NX',NOW(),NOW()),(19,'RX',NOW(),NOW()),(19,'GX',NOW(),NOW()),(19,'UX',NOW(),NOW()),(19,'LM',NOW(),NOW()),(19,'LX',NOW(),NOW()),
(20,'Impreza',NOW(),NOW()),(20,'Crosstrek',NOW(),NOW()),(20,'Forester',NOW(),NOW()),(20,'Outback',NOW(),NOW()),(20,'Legacy',NOW(),NOW()),(20,'WRX',NOW(),NOW()),(20,'BRZ',NOW(),NOW()),(20,'Ascent',NOW(),NOW()),(20,'Tribeca',NOW(),NOW()),(20,'XV',NOW(),NOW());

-- ============================================================
-- Dados iniciais: servicos e pecas
-- ============================================================

INSERT INTO servicos (nome, categoria, valor_mao_obra, tempo_estimado, ativo, created_at, updated_at) VALUES
('Troca de oleo e filtro','mecanica',80.00,30,1,NOW(),NOW()),
('Alinhamento e balanceamento','mecanica',120.00,60,1,NOW(),NOW()),
('Revisao de freios','mecanica',150.00,90,1,NOW(),NOW()),
('Diagnostico eletrico','eletrica',100.00,60,1,NOW(),NOW()),
('Troca de correia dentada','mecanica',200.00,120,1,NOW(),NOW()),
('Funilaria e pintura','funilaria',500.00,480,1,NOW(),NOW()),
('Revisao completa 10.000 km','mecanica',350.00,180,1,NOW(),NOW()),
('Troca de velas','mecanica',60.00,40,1,NOW(),NOW()),
('Higienizacao do ar-cond.','eletrica',90.00,45,1,NOW(),NOW()),
('Troca de amortecedores','mecanica',180.00,100,1,NOW(),NOW());

INSERT INTO pecas (nome, codigo, fabricante, preco_custo, preco_venda, estoque, estoque_minimo, ativo, created_at, updated_at) VALUES
('Filtro de oleo','FO-001','Fram',15.00,28.00,20,5,1,NOW(),NOW()),
('Pastilha de freio','PF-001','Bosch',45.00,89.00,10,3,1,NOW(),NOW()),
('Correia dentada','CD-001','Gates',80.00,150.00,8,2,1,NOW(),NOW()),
('Vela de ignicao','VI-001','NGK',12.00,22.00,30,8,1,NOW(),NOW()),
('Filtro de ar','FA-001','Mann',18.00,35.00,15,4,1,NOW(),NOW()),
('Fluido de freio','FF-001','Bosch',12.00,25.00,12,4,1,NOW(),NOW()),
('Oleo 5W30 sintetico','OL-001','Mobil',28.00,55.00,25,6,1,NOW(),NOW()),
('Amortecedor diant.','AM-001','Monroe',95.00,180.00,6,2,1,NOW(),NOW()),
('Disco de freio','DF-001','Fremax',60.00,115.00,8,2,1,NOW(),NOW()),
('Filtro combustivel','FC-001','Mann',22.00,42.00,10,3,1,NOW(),NOW());

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- Fim
-- ============================================================
