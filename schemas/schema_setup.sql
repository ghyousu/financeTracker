-- UFT = yoU Finance Tracker
DROP TABLE IF EXISTS uft.transaction;
DROP TABLE IF EXISTS uft.uft_user;
DROP TABLE IF EXISTS uft.banks;

DROP   SCHEMA IF EXISTS uft CASCADE;
CREATE SCHEMA IF NOT EXISTS uft;

CREATE TABLE IF NOT EXISTS uft.uft_user (
   user_id     serial,
   user_name   VARCHAR(100) NOT NULL,
   fname       VARCHAR(50) NOT NULL,
   lname       VARCHAR(50) NOT NULL,
   pw          VARCHAR(255) NOT NULL,
   unique(user_name),
   unique(fname,  lname),
   PRIMARY KEY(user_id)
);

CREATE TABLE IF NOT EXISTS uft.banks (
   bank_db_id                serial,
   owner_name                VARCHAR(50) NOT NULL,
   bank_name                 VARCHAR(50) NOT NULL,
   bank_alias                VARCHAR(30) DEFAULT NULL,  -- can be used to add owner initials when multiple accounts exist in the same bank
   min_balance               SMALLINT,
   bank_routing_num          VARCHAR(15),
   bank_account_num          VARCHAR(15),
   statement_date            SMALLINT, -- date of the month
   display_order             SMALLINT, -- order to be display on web page for each owner
   PRIMARY KEY(bank_db_id),
   unique(bank_alias),
   unique(bank_routing_num, bank_account_num)
);

CREATE TABLE IF NOT EXISTS uft.transaction (
   trans_id     serial,
   bank_db_id   INT NOT NULL, -- foreign key
   user_id      INT NOT NULL, -- foreign key
   amount       NUMERIC(10, 2)  NOT NULL,
   notes        VARCHAR(200),
   trans_date   TIMESTAMPTZ NOT NULL DEFAULT NOW(),
   FOREIGN KEY(bank_db_id) REFERENCES uft.banks(bank_db_id) ON DELETE CASCADE,
   FOREIGN KEY(user_id) REFERENCES uft.uft_user(user_id) ON DELETE CASCADE,
   PRIMARY KEY(trans_id)
);

