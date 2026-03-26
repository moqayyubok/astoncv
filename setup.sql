-- ============================================================
--  AstonCV  –  Database setup for Railway MySQL
--  Run once:
--    mysql -h gondola.proxy.rlwy.net -P 10908 \
--          -u root -pQfdEtirHIXUdafwMFekevHhrcCPXzPPB \
--          railway < setup.sql
-- ============================================================

CREATE TABLE IF NOT EXISTS `cvs` (
  `id`             bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`           varchar(100)        NOT NULL,
  `email`          varchar(100)        NOT NULL,
  `password`       varchar(255)        NOT NULL,
  `keyprogramming` varchar(255)        DEFAULT NULL,
  `profile`        varchar(500)        DEFAULT NULL,
  `education`      varchar(500)        DEFAULT NULL,
  `URLlinks`       varchar(500)        DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
--  Sample data  –  all passwords are 'password123'
--  Hashes generated with PASSWORD_BCRYPT (cost 10)
--  INSERT IGNORE skips duplicate emails if run more than once
-- ============================================================

INSERT IGNORE INTO `cvs` (`name`, `email`, `password`, `keyprogramming`, `profile`, `education`, `URLlinks`) VALUES

(
  'Alice Thornton',
  'alice@example.com',
  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
  'PHP, MySQL, JavaScript, HTML, CSS',
  'Full-stack web developer with 7 years of experience building scalable SaaS products. Passionate about clean code, RESTful API design, and developer experience. Currently focused on Laravel and Vue.js.',
  'BSc Computer Science, University of Manchester (2015). MSc Software Engineering, University of Edinburgh (2017).',
  'https://github.com/alicethornton, https://linkedin.com/in/alicethornton'
),

(
  'Ben Okafor',
  'ben@example.com',
  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
  'Python, Django, PostgreSQL, Docker, AWS',
  'Back-end engineer specialising in data-intensive applications and machine learning pipelines. 5+ years building microservices on AWS. Contributor to several open-source Python libraries.',
  'BEng Electronic Engineering, University of Lagos (2016). Postgraduate Certificate in Data Science, Coursera/Stanford Online (2019).',
  'https://github.com/benokafor, https://benokafor.dev'
),

(
  'Chloe Nakamura',
  'chloe@example.com',
  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
  'JavaScript, TypeScript, React, Node.js, GraphQL',
  'Frontend architect with a product mindset. I care deeply about accessibility, performance, and pixel-perfect UIs. Previously led the design-system team at a Series-B fintech startup.',
  'BA Human-Computer Interaction, University of California San Diego (2014).',
  'https://github.com/chloenakamura, https://chloenakamura.io, https://linkedin.com/in/chloenakamura'
),

(
  'David Kowalski',
  'david@example.com',
  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
  'Java, Spring Boot, Kotlin, Kubernetes, Apache Kafka',
  'Senior Java/Kotlin developer with 10 years in enterprise software. Deep expertise in event-driven architectures, distributed systems, and JVM performance tuning. Open-source maintainer of a Kafka testing library.',
  'MSc Distributed Systems, Warsaw University of Technology (2013).',
  'https://github.com/dkowalski, https://linkedin.com/in/davidkowalski'
),

(
  'Elena Vasquez',
  'elena@example.com',
  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
  'C++, Rust, Embedded C, RTOS, CMake',
  'Systems programmer with 8 years in embedded and real-time systems. I write low-latency code for robotics and automotive applications. Advocate for memory-safe systems programming and modern C++20 idioms.',
  'MEng Electrical and Electronic Engineering, Imperial College London (2014).',
  'https://github.com/elenavasquez, https://linkedin.com/in/elenavasquez'
);
