
CREATE TABLE IF NOT EXISTS students (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  first_name VARCHAR(100) NOT NULL,
  last_name  VARCHAR(100) NOT NULL,
  email      VARCHAR(190) NOT NULL UNIQUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS plans (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  amount_cents INT UNSIGNED NOT NULL,
  interval_enum ENUM('monthly','annual') NOT NULL,
  active TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS agreements (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  student_id INT UNSIGNED NOT NULL,
  plan_id INT UNSIGNED NOT NULL,
  next_due_date DATE NOT NULL,
  status ENUM('active','paused','canceled') NOT NULL DEFAULT 'active',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (student_id) REFERENCES students(id),
  FOREIGN KEY (plan_id) REFERENCES plans(id),
  INDEX (next_due_date),
  INDEX (status, next_due_date)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS payments (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  agreement_id INT UNSIGNED NOT NULL,
  amount_cents INT UNSIGNED NOT NULL,
  paid_date DATE NOT NULL,
  method ENUM('cash','card','ach','other') NOT NULL DEFAULT 'other',
  note VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (agreement_id) REFERENCES agreements(id),
  INDEX (paid_date)
) ENGINE=InnoDB;
