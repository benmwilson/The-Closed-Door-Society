USE forum;

-- Reset Tables

-- DROP TABLE UserComments;
-- DROP TABLE Comments;
-- DROP TABLE Threads;
-- DROP TABLE Forums;
-- DROP TABLE Users;

-- Create Tables

CREATE TABLE Users(

	ID INT NOT NULL AUTO_INCREMENT,
	Username VARCHAR(64) NOT NULL,
	Email VARCHAR(256) NOT NULL,
	Password VARCHAR(256) NOT NULL,
	ProfileDescription TEXT,
	Administrator BOOLEAN,
	
	UNIQUE (Username),
	UNIQUE (Email),
	
	PRIMARY KEY (ID),
	INDEX (Username),
	INDEX (Email)
	
);

CREATE TABLE Forums(

	ID INT NOT NULL AUTO_INCREMENT,
	Parent INT,
	UpdateTime DATETIME NOT NULL,
	Name VARCHAR(256) NOT NULL,
	Description TEXT,
	
	PRIMARY KEY (ID),
	FOREIGN KEY (Parent) REFERENCES Forums(ID),
	INDEX (UpdateTime)
	
);

CREATE TABLE Threads(

	ID INT NOT NULL AUTO_INCREMENT,
	ForumID INT NOT NULL,
	UpdateTime DATETIME NOT NULL,
	Title VARCHAR(256) NOT NULL,
	
	PRIMARY KEY (ID),
	FOREIGN KEY (ForumID) REFERENCES Forums(ID),
	INDEX (UpdateTime)
	
);

CREATE TABLE Comments(

	ID INT NOT NULL AUTO_INCREMENT,
	PosterID INT NOT NULL,
	ThreadID INT NOT NULL,
	UpdateTime DATETIME NOT NULL,
	Content TEXT NOT NULL,
	
	PRIMARY KEY (ID),
	FOREIGN KEY (PosterID) REFERENCES Users(ID),
	FOREIGN KEY (ThreadID) REFERENCES Threads(ID),
	INDEX (UpdateTime)
	
);



-- Populate Tables

-- Kyriau password: password
-- asdf password: asdf
INSERT INTO Users(Username, Email, Password, Administrator) VALUES
	('Kyriau', 'jeff.aj.thomson@alumni.ubc.ca', '$2y$10$pINMe25SdcTeCnQ/P2pveeVQwvVOGZC3L5upOejatOQnPgTjJPujG', TRUE),
	('asdf', 'asdf@asdf.asdf', '$2y$10$oEr685cXTzyOQvbTiW0B6eJpAJa.ZvekxFBDmQip9tD4UnZzTn2l6', FALSE)
;

INSERT INTO Forums(Parent, UpdateTime, Name, Description) VALUES
	(NULL, NOW(), 'Main', 'The central forum of the website.'),
	(1, NOW(), 'Subforum 1', 'A subforum of the Main forum.'),
	(1, NOW(), 'Subforum 2', 'Another subforum of the Main forum.'),
	(3, NOW(), 'Second-Level Forum', 'A Subforum of a subforum (Subforum 2).')
;

INSERT INTO Threads(ForumID, UpdateTime, Title) VALUES
	(1, NOW(), "The first thread"),
	(1, NOW(), "The second thread"),
	(2, NOW(), "A thread in a subforum"),
	(1, NOW(), "A very very very very very very very very very very long title")
;

INSERT INTO Comments(PosterID, ThreadID, UpdateTime, Content) VALUES
	(2, 1, NOW(), "This is the content of a comment."),
	(1, 1, NOW(), "This is another comment."),
	(1, 2, NOW(), "This is a comment in thread 2"),
	(1, 3, NOW(), "This is a comment in thread 3"),
	(1, 4, NOW(), "This is a comment in thread 4")
;