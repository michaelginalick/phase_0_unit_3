# U3.W7: BONUS Using the SQLite Gem

# I worked on this challenge [by myself, with:]

require 'sqlite3'


$db = SQLite3::Database.open "congress_poll_results.db"


def print_arizona_reps
  puts "AZ REPRESENTATIVES"
  az_reps = $db.execute("SELECT name FROM congress_members WHERE location = 'AZ'")
  az_reps.each { |rep| puts rep }
end

def print_longest_serving_reps(minimum_years)  #sorry guys, oracle needs me, i didn't finish this!
  puts "LONGEST SERVING REPRESENTATIVES"
  longest_reps =  $db.execute("SELECT name,years_in_congress FROM congress_members WHERE years_in_congress > #{minimum_years}")
  longest_reps.each { |rep,year| puts rep + "-"+ year.to_s}
end

def print_lowest_grade_level_speaker(low_grade)
  puts "LOWEST GRADE LEVEL SPEAKERS (less than < 8th grade)"
  lowest_grade = $db.execute("SELECT name FROM congress_members WHERE grade_current < #{low_grade}")
  lowest_grade.each { |rep| puts rep}
end

def print_state_reps
  puts "Representatives for New Jersey, New York, Maine, Florida, and Alaska"
  five_states = $db.execute("SELECT name,location FROM congress_members WHERE location = 'NJ' OR location = 'NY' OR location = 'FL' OR location = 'AL' OR location = 'ME' ")

  five_states.each {|rep, state| puts rep + "-"+ state}
end

# Create a listing of all of the Politicians and the number of votes they recieved
# output should look like:  Sen. John McCain - 7,323 votes (This is an example, yours will not return this value, it should just 
#    have a similar format)

def print_politicaians_votes
   puts "Politicians and the number of votes they recieved"
   number_of_votes = $db.execute("SELECT name, SUM(voter_id) FROM congress_members JOIN votes ON (congress_members.id=politician_id) GROUP BY name;")
   number_of_votes.each {|rep, votes| puts rep + "-"+ votes}
end

# Create a listing of each Politician and the voter that voted for them

def print_voter_name
  puts "A listing of each Politician and the voter that voted for them"
  voter_name = $db.execute("SELECT name, a.first_name FROM congress_members,voters a JOIN voters ON (voters.id=votes_id) GROUP BY name;")
  voter_name.each {|rep,voter_name| puts rep + "-" voter_name} 
end

def print_separator
  puts 
  puts "------------------------------------------------------------------------------"
  puts 
end


SELECT name, first_name, last_name FROM congress_members,voters JOIN votes ON (voter_id=voters.id) GROUP BY name;

print_arizona_reps

print_separator

print_longest_serving_reps(35)
# TODO - Print out the number of years served as well as the name of the longest running reps
# output should look like:  Rep. C. W. Bill Young - 41 years

print_separator
print_lowest_grade_level_speaker(8.0)

# TODO - Need to be able to pass the grade level as an argument, look in schema for "grade_current" column



#
print_separator
print_state_reps

print_separator
print_voter_name

# TODO - Make a method to print the following states representatives as well:
# (New Jersey, New York, Maine, Florida, and Alaska)


##### BONUS #######
# TODO (bonus) - Stop SQL injection attacks!  Statmaster learned that interpolation of variables in SQL statements leaves some security vulnerabilities.  Use the google to figure out how to protect from this type of attack.

# TODO (bonus)
# Create a listing of all of the Politicians and the number of votes they recieved
# output should look like:  Sen. John McCain - 7,323 votes (This is an example, yours will not return this value, it should just 
#    have a similar format)
# Create a listing of each Politician and the voter that voted for them
# output should include the senators name, then a long list of voters separated by a comma
#
# * you'll need to do some join statements to complete these last queries!


# REFLECTION- Include your reflection as a comment below.
# How does the sqlite3 gem work?  What is the variable `$db` holding?  
# Try to use your knowledge of ruby and OO to decipher this as well as h
# ow the `#execute` method works.  Take a stab at explaining the line 
# `$db.execute("SELECT name FROM congress_members WHERE years_in_congress 
#   > #{minimum_years}")`.  Try to explain this as clearly as possible for 
# your fellow students.  
# If you're having trouble, find someone to pair on this explanation with you.
