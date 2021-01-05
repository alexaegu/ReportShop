CREATE DATABASE IF NOT EXISTS SHOPBASE
  CHARACTER SET utf8 COLLATE utf8_unicode_ci;

USE SHOPBASE;

CREATE USER IF NOT EXISTS 'admin1'@'localhost'
  IDENTIFIED BY '12345';

/****************************************************************************/

CREATE TABLE IF NOT EXISTS UsersT
(
  LoginUser VARCHAR(10) PRIMARY KEY NOT NULL,
  PasswordUser CHAR(255) NOT NULL,
  EmailUser VARCHAR(45) UNIQUE NOT NULL,
  DateAut DATETIME,
  IndexAut TINYINT(1) UNSIGNED NOT NULL DEFAULT 0
)
  DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
  
CREATE TABLE IF NOT EXISTS CategoryT
(
  NumberCat INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
  NameCat VARCHAR(50) UNIQUE NOT NULL
)
  DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS GoodsT
(
  NameGood VARCHAR(50) PRIMARY KEY NOT NULL,
  NumberCat INT UNSIGNED NOT NULL,
  PriceGood FLOAT(8,2) NOT NULL,
  QuantityGood INT UNSIGNED NOT NULL,
  PermittedQuantity INT UNSIGNED NOT NULL,
    FOREIGN KEY (NumberCat) REFERENCES CategoryT(NumberCat)
)
  DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS PaymentsT
(
  LoginUser VARCHAR(10) PRIMARY KEY NOT NULL,
  AddressUser VARCHAR(100),
  FullPrice FLOAT(10,2),
  CardNumber CHAR(16),
  ValidTo CHAR(4),
  CvvCode CHAR(3),
  ShopAccount CHAR(20)
)
  DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS DeliveryT
(
  LoginUser VARCHAR(10) NOT NULL,
  AddressUser VARCHAR(100) NOT NULL,
  NameGood VARCHAR(50) NOT NULL,
  AmountGood INT UNSIGNED NOT NULL,
  DatePay DATETIME NOT NULL,
  IndexDeliv TINYINT(1) UNSIGNED NOT NULL,
    FOREIGN KEY (LoginUser) REFERENCES PaymentsT(LoginUser)
)
  DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS CartT
(
  LoginUser VARCHAR(10) NOT NULL,
  NameGood VARCHAR(50) NOT NULL,
  AmountGood INT UNSIGNED NOT NULL,
    FOREIGN KEY (LoginUser) REFERENCES UsersT(LoginUser),
    FOREIGN KEY (NameGood) REFERENCES GoodsT(NameGood),
    FOREIGN KEY (LoginUser) REFERENCES PaymentsT(LoginUser)
)
  DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;

/****************************************************************************/

GRANT SELECT, INSERT, UPDATE, DELETE
  ON SHOPBASE.*
  TO 'admin1'@'localhost' IDENTIFIED BY '12345';
