/* Created by Andrew Krall */
DROP TABLE IF EXISTS LOGIN;
DROP TABLE IF EXISTS SAVED_RECIPES;
DROP TABLE IF EXISTS RECIPE_INGREDIENTS;
DROP TABLE IF EXISTS INGREDIENTS;
DROP TABLE IF EXISTS PROFILE;

CREATE TABLE PROFILE(
	userid int(11) unsigned NOT NULL AUTO_INCREMENT,
	username varchar(255) UNIQUE NOT NULL,
	name varchar(255),
	dob date,
	gender varchar(255),
	profession varchar(255),
	affiliation varchar(255),
	PRIMARY KEY(userid, username)
);

CREATE TABLE LOGIN(
	userid int(11) unsigned,
	username varchar(255),
	password varchar(255),
    CONSTRAINT login_delete
        FOREIGN KEY(userid)
        REFERENCES PROFILE(userid)
        ON DELETE CASCADE, -- Will delete the login row associated with the user's id if a user is deleted.
	PRIMARY KEY(userid, username)
);

/*CREATE TABLE RECIPES(
	recipe_id varchar(255),
	dishname varchar(255),
	category varchar(255),
	recipe_name varchar(255),
	description varchar(255),
	PRIMARY KEY(recipe_id)
);*/

CREATE TABLE SAVED_RECIPES( 
	userid int(11) unsigned,
	recipe_id varchar(255),
    PRIMARY KEY(userid, recipe_id),
    CONSTRAINT saved_recipe_delete
        FOREIGN KEY(userid) 
        REFERENCES PROFILE(userid)
        ON DELETE CASCADE -- Will delete the saved recipes associated with the user's id if a user is deleted.
);
