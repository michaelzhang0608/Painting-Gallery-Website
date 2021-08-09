
CREATE TABLE art (
  id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
  user_id TEXT NOT NULL,
  title TEXT NOT NULL UNIQUE,
  artist TEXT NOT NULL,
  buyer TEXT NOT NULL,
  seller TEXT NOT NULL,
  created_date INTEGER NOT NULL,
  sale_date TEXT NOT NULL,
  price REAL NOT NULL,
  file_name TEXT NOT NULL UNIQUE,
  file_ext TEXT NOT NULL,
  FOREIGN KEY(user_id) REFERENCES users(id)
);


INSERT INTO art (id, user_id, title, artist, buyer, seller, created_date, sale_date, price, file_name, file_ext) VALUES (1, 1, 'Salvator Mundi', 'Leonardo Da Vinci', 'Prince Badr bin Abdullah bin Mohammed bin Farhan Al Saud', 'Dmitry Rybolovlev', 1500, 'November 15, 2017', 469.7, "1.jpg", 'jpg');
INSERT INTO art (id, user_id,title, artist, buyer, seller, created_date, sale_date, price, file_name, file_ext) VALUES (2, 1, 'Interchange', 'Wilhelm de Kooning', 'Kenneth Griffin', 'David Geffen Foundation', 1955, 'September 2015', 324, "2.jpg", 'jpg');
INSERT INTO art (id, user_id,title, artist, buyer, seller, created_date, sale_date, price, file_name, file_ext) VALUES (3,1, 'The Card Players', 'Paul Cezanne', 'Qatar', 'George Embiricos', 1892, 'April 2011', 284, "3.jpg", 'jpg');
INSERT INTO art (id, user_id,title, artist, buyer, seller, created_date, sale_date, price, file_name, file_ext) VALUES (4,1, 'When Will You Marry?', 'Paul Gauguin', 'Qatar', "Rudolf Staechelin's Heirs", 1892, 'September 2014', 210, "4.jpg", 'jpg');
INSERT INTO art (id, user_id,title, artist, buyer, seller, created_date, sale_date, price, file_name, file_ext) VALUES (5,1, 'Number 17A', 'Jackson Pollock', 'Kenneth Griffin', 'David Geffen Foundation', 1948, 'September 2015', 216, "5.jpg", 'jpg');
INSERT INTO art (id, user_id,title, artist, buyer, seller, created_date, sale_date, price, file_name, file_ext) VALUES (6,1,'Wasserschlangen II', 'Gustav Klimt', 'Dmitry Rybolovlev', 'Yves Bouvier', 1907, '2013', 201.7, "6.jpeg", 'jpeg');
INSERT INTO art (id, user_id,title, artist, buyer, seller, created_date, sale_date, price, file_name, file_ext) VALUES (7,1,'No. 6 (Violet, Green, and Red)', 'Mark Rothco', 'Dmitry Rybolovlev', 'Cherise Moueix', 1951, 'August 2014', 201, "7.jpg", 'jpg');
INSERT INTO art (id, user_id,title, artist, buyer, seller, created_date, sale_date, price, file_name, file_ext) VALUES (8,1,'Portraits of Marten Soolmans and Oopjen Coppit', 'Rembrandt', 'Rijksmuseum & the Louvre', 'Eric de Rothschild', 1634, 'February 1, 2016', 194, "8.jpeg", "jpeg");
INSERT INTO art (id, user_id,title, artist, buyer, seller, created_date, sale_date, price, file_name, file_ext) VALUES (9,1,"Les Femmes d'Alger", 'Pablo Picasso', 'Sheikh Hamad bin Jassim bin Jaber bin Mohammed bin Thani Al Thani', 'Private Collection', 1955, 'May 11, 2015', 193.5, "9.jpg", "jpg");
INSERT INTO art (id, user_id,title, artist, buyer, seller, created_date, sale_date, price, file_name, file_ext) VALUES (10,1,"Nu couché", 'Amedeo Modigliani', 'Liu Yiqian', 'Laura Mattioli', 1917, 'November 9, 2015', 183.8, "10.jpg", 'jpg');



CREATE TABLE tags (
  id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
  tag_name TEXT NOT NULL UNIQUE
);

INSERT INTO tags (id, tag_name) VALUES (1, 'Realist');
INSERT INTO tags (id, tag_name) VALUES (2, 'Abstract');
INSERT INTO tags (id, tag_name) VALUES (3, 'Modern');


CREATE TABLE art_tags (
  id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
  tag_id INTEGER NOT NULL,
  art_id INTEGER NOT NULL,
  FOREIGN KEY (tag_id) REFERENCES tags(id),
  FOREIGN KEY (art_id) REFERENCES art(id)
);



INSERT INTO art_tags (id, tag_id, art_id) VALUES (1, 1, 1);
INSERT INTO art_tags (id, tag_id, art_id) VALUES (2, 2, 2);
INSERT INTO art_tags (id, tag_id, art_id) VALUES (3, 3, 2);
INSERT INTO art_tags (id, tag_id, art_id) VALUES (4, 1, 3);
INSERT INTO art_tags (id, tag_id, art_id) VALUES (5, 3, 3);
INSERT INTO art_tags (id, tag_id, art_id) VALUES (6, 1, 4);
INSERT INTO art_tags (id, tag_id, art_id) VALUES (7, 3, 4);
INSERT INTO art_tags (id, tag_id, art_id) VALUES (8, 2, 5);
INSERT INTO art_tags (id, tag_id, art_id) VALUES (9, 3, 5);
INSERT INTO art_tags (id, tag_id, art_id) VALUES (10, 2, 6);
INSERT INTO art_tags (id, tag_id, art_id) VALUES (11, 3, 6);
INSERT INTO art_tags (id, tag_id, art_id) VALUES (12, 2, 7);
INSERT INTO art_tags (id, tag_id, art_id) VALUES (13, 3, 7);
INSERT INTO art_tags (id, tag_id, art_id) VALUES (14, 1, 8);
INSERT INTO art_tags (id, tag_id, art_id) VALUES (15, 2, 9);
INSERT INTO art_tags (id, tag_id, art_id) VALUES (16, 3, 9);
INSERT INTO art_tags (id, tag_id, art_id) VALUES (17, 2, 10);
INSERT INTO art_tags (id, tag_id, art_id) VALUES (18, 1, 10);



CREATE TABLE users (
	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	name TEXT NOT NULL,
	uname TEXT NOT NULL UNIQUE,
	pword TEXT NOT NULL
);

INSERT INTO users (id, name, uname, pword) VALUES (1, 'Michael Zhang', 'michael', '$2y$10$QtCybkpkzh7x5VN11APHned4J8fu78.eFXlyAMmahuAaNcbwZ7FH.');
INSERT INTO users (id, name, uname, pword) VALUES (2, 'Michael Zhang', 'michael2', '$2y$10$QtCybkpkzh7x5VN11APHned4J8fu78.eFXlyAMmahuAaNcbwZ7FH.');

--- Sessions ---

CREATE TABLE sessions (
	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	user_id INTEGER NOT NULL,
	session TEXT NOT NULL UNIQUE,
  last_login  TEXT NOT NULL,

  FOREIGN KEY(user_id) REFERENCES users(id)
);


--- Groups ----

CREATE TABLE groups (
	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	group_name TEXT NOT NULL UNIQUE
);

INSERT INTO groups (id, group_name) VALUES (1, 'admin');
INSERT INTO groups (id, group_name) VALUES (0, 'non-admin');


--- Group Membership

CREATE TABLE memberships (
	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
  group_id INTEGER NOT NULL,
  user_id INTEGER NOT NULL,

  FOREIGN KEY(group_id) REFERENCES groups(id),
  FOREIGN KEY(user_id) REFERENCES users(id)
);

INSERT INTO memberships (group_id, user_id) VALUES (1, 1);
INSERT INTO memberships (group_id, user_id) VALUES (0, 2);
