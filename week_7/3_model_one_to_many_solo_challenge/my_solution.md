# U3.W7: Modeling a Real-World Database (SOLO CHALLENGE)

## Release 0: Users Fields
<!-- Identify the fields Twitter collects data for -->

  Full Name, user_id, email,url

## Release 1: Tweet Fields
<!-- Identify the fields Twitter uses to represent/display a tweet. What are you required or allowed to enter? -->

  Tweet_id, tweet_text, entities, created_at, user_id, screen_name, name, image

## Release 2: Explain the relationship
The relationship between `users` and `tweets` is: one to many
<!-- because... -->
one user can have many tweets
## Release 3: Schema Design
<!-- Include your image (inline) of your schema -->

![twitter](https://raw.githubusercontent.com/michaelginalick/phase_0_unit_3/master/week_7/imgs/Screen Shot 2014-05-15 at 3.57.11 PM.png)

## Release 4: SQL Statements
<!-- Include your SQL Statements. How can you make markdown files show blocks of code? -->

all the tweets for a certain user id

  SELECT * FROM tweets WHERE user_id = "twitter_user"
the tweets for a certain user id that were made after last Wednesday (whenever last Wednesday was for you)
all the tweets associated with a given user's twitter handle
the twitter handle associated with a given tweet id

## Release 5: Reflection
<!-- Be sure to add your reflection here!!! -->
