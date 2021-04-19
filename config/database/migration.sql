
/*
    support langganan
*/
CREATE TABLE IF NOT EXISTS langganan
(
    id BIGINT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    price DOUBLE DEFAULT 0,
    name VARCHAR(100) NOT NULL,
    description VARCHAR(255) NOT NULL
) engine= InnoDB;

ALTER TABLE langganan
ADD COLUMN `status` TINYINT(1) NOT NULL AFTER `name`,
ADD COLUMN `siswa` INT(10) NOT NULL AFTER `status`,
ADD COLUMN `guru` INT(10) NOT NULL AFTER `siswa`,
ADD COLUMN `is_premium` TINYINT(1) NOT NULL DEFAULT 0 AFTER `guru`;

/*
    support langganan detail
*/
CREATE TABLE IF NOT EXISTS langganan_detail
(
    id BIGINT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    langganan_id bigint(20) NOT NULL,
    administrator_id bigint(20) NOT NULL,
    type TINYINT(1) NOT NULL,
    quantity INT(10) NOT NULL,
    fix_price DOUBLE DEFAULT 0,
    request_date DATETIME NOT NULL,
    buy_date DATETIME,
    exp_date DATETIME,
    status TINYINT(1)
) engine= InnoDB;

/*
    administrator bisa memiliki banyak sekolah
*/
CREATE TABLE IF NOT EXISTS administrator
(
    id BIGINT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(150) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    email varchar(150) NOT NULL UNIQUE,
    phone_number VARCHAR(20),
    image_url TEXT,
    `status` TINYINT(1),
    langganan_id BIGINT(20) NOT NULL,
    exp_date DATE,
    createdate DATETIME,
    createby BIGINT(20),
    updatedate DATETIME ,
    updateby BIGINT(20)
) engine= InnoDB;


/*
    harus memiliki administrator
*/
CREATE TABLE IF NOT EXISTS sekolah
(
    id BIGINT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    NPSN VARCHAR(255) NOT NULL UNIQUE,
    email varchar(150) NOT NULL UNIQUE,
    phone_number VARCHAR(20),
    address varchar(200),
    image_url TEXT,
    `status` TINYINT(1),
    administrator_id bigint(20) NOT NULL,
    createdate DATETIME,
    createby BIGINT(20),
    updatedate DATETIME ,
    updateby BIGINT(20)
) engine= InnoDB;

/*
    tahun ajaran berdasarkan sekolah
*/
CREATE TABLE IF NOT EXISTS tahun_ajaran
(
    id BIGINT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    NPSN VARCHAR(255) NOT NULL,
    sekolah_id bigint(20) NOT NULL,
    `year` YEAR NOT NULL,
    `status` TINYINT(1),
    description VARCHAR(255),
    createdate DATETIME,
    createby BIGINT(20),
    updatedate DATETIME ,
    updateby BIGINT(20)
) engine= InnoDB;

/*
    guru berdasarkan sekolah
*/
CREATE TABLE IF NOT EXISTS guru
(
    id BIGINT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    NPSN VARCHAR(255) NOT NULL,
    sekolah_id bigint(20) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    email varchar(150) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    NIP varchar(255) NOT NULL UNIQUE,
    phone_number VARCHAR(20),
    address varchar(200),
    image_url TEXT,
    `status` TINYINT(1),
    createdate DATETIME,
    createby BIGINT(20),
    updatedate DATETIME ,
    updateby BIGINT(20)
) engine= InnoDB;

/*
    kelas berdasarkan sekolah
*/
CREATE TABLE IF NOT EXISTS kelas
(
    id BIGINT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    NPSN VARCHAR(255) NOT NULL,
    sekolah_id bigint(20) NOT NULL,
    tahun_ajaran_id bigint(20) NOT NULL,
    guru_id bigint(20) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    description VARCHAR(255),
    `status` TINYINT(1),
    createdate DATETIME,
    createby BIGINT(20),
    updatedate DATETIME ,
    updateby BIGINT(20)
) engine= InnoDB;

/*
    siswa berdasarkan sekolah
*/
CREATE TABLE IF NOT EXISTS siswa
(
    id BIGINT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    NPSN VARCHAR(255) NOT NULL,
    sekolah_id bigint(20) NOT NULL,
    NIS varchar(255) NOT NULL UNIQUE,
    `name` VARCHAR(255) NOT NULL,
    email varchar(150) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    phone_number VARCHAR(20),
    address varchar(200),
    image_url TEXT,
    `status` TINYINT(1),
    createdate DATETIME,
    createby BIGINT(20),
    updatedate DATETIME ,
    updateby BIGINT(20)
) engine= InnoDB;

/*
    kelas memiliki siswa berdasarkan sekolah
*/
CREATE TABLE IF NOT EXISTS kelas_has_siswa
(
    id BIGINT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    NPSN VARCHAR(255) NOT NULL,
    sekolah_id bigint(20) NOT NULL,
    kelas_id bigint(20) NOT NULL,
    siswa_id bigint(20) NOT NULL,
    `status` TINYINT(1),
    createdate DATETIME,
    createby BIGINT(20),
    updatedate DATETIME,
    updateby BIGINT(20)
) engine= InnoDB;

/*
    mata pelajaran berdasarkan sekolah
*/
CREATE TABLE IF NOT EXISTS mata_pelajaran
(
    id BIGINT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    NPSN VARCHAR(255) NOT NULL,
    sekolah_id bigint(20) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `status` TINYINT(1),
    description VARCHAR(255),
    image_url TEXT,
    createdate DATETIME,
    createby BIGINT(20),
    updatedate DATETIME,
    updateby BIGINT(20)
) engine= InnoDB;

/*
    kelas memiliki siswa & guru berdasarkan mata pelajaran & sekolah
*/
CREATE TABLE IF NOT EXISTS kelas_has_mata_pelajaran
(
    id BIGINT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    NPSN VARCHAR(255) NOT NULL,
    sekolah_id bigint(20) NOT NULL,
    kelas_id bigint(20) NOT NULL,
    mata_pelajaran_id bigint(20) NOT NULL,
    guru_id bigint(20) NOT NULL,
    siswa_id bigint(20) NOT NULL,
    createdate DATETIME,
    createby BIGINT(20),
    updatedate DATETIME,
    updateby BIGINT(20)
) engine= InnoDB;


/*
    mata pelajaran memiliki jadwal berdasarkan kelas,tahun_ajaran & sekolah
*/
CREATE TABLE IF NOT EXISTS mata_pelajaran_active_day
(
    id BIGINT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    NPSN VARCHAR(255) NOT NULL,
    sekolah_id bigint(20) NOT NULL,
    mata_pelajaran_id bigint(20) NOT NULL,
    kelas_id bigint(20) NOT NULL,
	`day` TINYINT(1) NOT NULL,
	`start_time` TIME NOT NULL,
	`end_time` TIME NOT NULL,
    createdate DATETIME,
    createby BIGINT(20),
    updatedate DATETIME,
    updateby BIGINT(20)
) engine= InnoDB;

/*
    absensi siswa berdasarkan tahun ajaran & sekolah
*/
CREATE TABLE IF NOT EXISTS siswa_has_attendance
(
    id BIGINT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    NPSN VARCHAR(255) NOT NULL,
    sekolah_id bigint(20) NOT NULL,
    siswa_id bigint(20) NOT NULL,
    mata_pelajaran_id bigint(20) NOT NULL,
    kelas_id bigint(20) NOT NULL,
	`attendance_time` TIME NOT NULL,
	`attendance_date` DATE NOT NULL,
    createdate DATETIME,
    createby BIGINT(20),
    updatedate DATETIME,
    updateby BIGINT(20)
) engine= InnoDB;
