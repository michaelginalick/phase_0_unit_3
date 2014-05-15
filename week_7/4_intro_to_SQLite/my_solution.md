# U3.W7: Intro to SQLite

## Release 0: Create a dummy database

<!-- paste your terminal output here -->

Last login: Thu May 15 18:07:40 on ttys000
MG:~ michaelginalick$ cat << EOF > ~/.sqliterc
> .headers on
> .mode column
> EOF
MG:~ michaelginalick$ sqlite3 dummy.db
-- Loading resources from /Users/michaelginalick/.sqliterc

SQLite version 3.7.13 2012-07-17 17:46:21
Enter ".help" for instructions
Enter SQL statements terminated with a ";"
sqlite> CREATE TABLE users (
   ...>   id INTEGER PRIMARY KEY AUTOINCREMENT,
   ...>   first_name VARCHAR(64) NOT NULL,
   ...>   last_name  VARCHAR(64) NOT NULL,
   ...>   email VARCHAR(128) UNIQUE NOT NULL,
   ...>   created_at DATETIME NOT NULL,
   ...>   updated_at DATETIME NOT NULL
   ...> );
sqlite> 


## Release 1: Insert Data 
<!-- paste your terminal output here -->

sqlite> INSERT INTO users
   ...> (first_name, last_name,email,created_at,updated_at)
   ...> VALUES
   ...> ('Mike','Ginalick','michael.ginalick@gmail.com',DATETIME('now'),DATETIME('now'));
sqlite> SELECT * FROM users;
id          first_name  last_name   email                  created_at           updated_at         
----------  ----------  ----------  ---------------------  -------------------  -------------------
1           Kimmey      Lin         kimmy@devbootcamp.com  2014-05-15 23:14:33  2014-05-15 23:14:33
2           Mike        Ginalick    michael.ginalick@gmai  2014-05-15 23:17:55  2014-05-15 23:17:55
sqlite> 



## Release 2: Multi-line commands
<!-- paste your terminal output here -->

sqlite> INSERT INTO users
   ...> (first_name, last_name, email, created_at, updated_at)
   ...> VALUES
   ...> ('Kimmey', 'Lin', 'kimmy@devbootcamp.com', DATETIME('now'), DATETIME('now'));
Error: column email is not unique
sqlite> select * from users;
id          first_name  last_name   email                  created_at           updated_at         
----------  ----------  ----------  ---------------------  -------------------  -------------------
1           Kimmey      Lin         kimmy@devbootcamp.com  2014-05-15 23:14:33  2014-05-15 23:14:33
2           Mike        Ginalick    michael.ginalick@gmai  2014-05-15 23:17:55  2014-05-15 23:17:55
sqlite> 

## Release 3: Add a column
<!-- paste your terminal output here -->

## Release 4: Change a value
<!-- paste your terminal output here -->

## Release 5: Reflect
<!-- Add your reflection here -->
