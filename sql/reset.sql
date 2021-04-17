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
	Disabled BOOLEAN,
	
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
	FOREIGN KEY (Parent) REFERENCES Forums(ID) ON DELETE CASCADE,
	INDEX (UpdateTime)
	
);

CREATE TABLE Threads(

	ID INT NOT NULL AUTO_INCREMENT,
	ForumID INT NOT NULL,
	UpdateTime DATETIME NOT NULL,
	Title VARCHAR(256) NOT NULL,
	Comments INT NOT NULL DEFAULT 1,
	
	PRIMARY KEY (ID),
	FOREIGN KEY (ForumID) REFERENCES Forums(ID) ON DELETE CASCADE,
	INDEX (UpdateTime)
	
);

CREATE TABLE Comments(

	ID INT NOT NULL AUTO_INCREMENT,
	PosterID INT NOT NULL,
	ThreadID INT NOT NULL,
	UpdateTime DATETIME NOT NULL,
	Content TEXT NOT NULL,
	
	PRIMARY KEY (ID),
	FOREIGN KEY (PosterID) REFERENCES Users(ID) ON DELETE CASCADE,
	FOREIGN KEY (ThreadID) REFERENCES Threads(ID) ON DELETE CASCADE,
	INDEX (UpdateTime)
	
);

-- CREATE TABLE Images(

-- 	ID INT NOT NULL AUTO_INCREMENT,
-- 	UpdateTime DATETIME NOT NULL,
-- 	Image BLOB NOT NULL,
	
-- 	PRIMARY KEY (ID),
-- 	INDEX (UpdateTime)
	
-- );

CREATE TABLE News(

	ID INT NOT NULL AUTO_INCREMENT,
	UpdateTime DATETIME NOT NULL,
	Title VARCHAR(56) NOT NULL,
	Content TEXT NOT NULL,
	
	PRIMARY KEY (ID),
	INDEX (UpdateTime)
	
);

-- Code from the comment liking system I was working on, couldn't get it done in time :( -BW

-- CREATE TABLE Likes(

-- 	ID INT NOT NULL AUTO_INCREMENT,
-- 	UpdateTime DATETIME NOT NULL,
-- 	UserID INT NOT NULL,
-- 	CommentID INT NOT NULL,
	
-- 	PRIMARY KEY (ID),
-- 	INDEX (UpdateTime)
	
-- );

-- Populate Tables

INSERT INTO Forums(Parent, UpdateTime, Name, Description) VALUES
	(NULL, NOW(), 'Main', 'Main Forum'),
	(1, NOW(), 'World Domination Plans', 'Talk about how you will take over the world!'),
	(1, NOW(), 'Weapons of Mass Destruction ', 'Talk about what The Department of Homeland Security doesnt want you to have!'),
	(3, NOW(), 'Henchmen for Hire', 'A board to see out henchmen.')
;

INSERT INTO News(UpdateTime, Title, Content) VALUES (NOW(), 'Title', 'Content');

-- INSERT INTO Users(Username, Email, Password, Administrator) VALUES
-- 	("user1", "user1email@closeddoor.net", "pass", 0),
-- 	("user2", "user2email@closeddoor.net", "p@ss", 0)	
-- ;


-- INSERT INTO Threads(ForumID, UpdateTime, Title) VALUES
-- 	(1, NOW(), "The first thread"),
-- 	(2, NOW(), "The second thread")
-- ;

-- INSERT INTO Comments(PosterID, ThreadID, UpdateTime, Content) VALUES
-- 	(2, 1, NOW(), "This is a comment."),
-- 	(1, 1, NOW(), "This is another comment.")
-- ;