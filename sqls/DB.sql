CREATE DATABASE tydyshka;
  
CREATE TABLE users (
  user_id INTEGER NOT NULL AUTO_INCREMENT,
  name VARCHAR(128),
  email VARCHAR(128),
  password VARCHAR(128),
  PRIMARY KEY(user_id)
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE users ADD INDEX(email);
ALTER TABLE users ADD INDEX(password);


CREATE TABLE Entities(
  item_id INTEGER NOT NULL AUTO_INCREMENT,
  user_id INTEGER NOT NULL,
  headline TEXT,
  description TEXT,
  priority INTEGER,
  deadline DATE,
  PRIMARY KEY(item_id),
  CONSTRAINT entities_ibfk_2
  FOREIGN KEY (user_id)
  REFERENCES users (user_id)
  ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO users(name, email, password) VALUES ('Test Account', 'test@example.com', '9c36e5fbb16ab509c1eb80a652dcf446');
