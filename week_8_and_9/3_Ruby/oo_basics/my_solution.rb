# U3.W8-9: OO Basics: Student


# I worked on this challenge [by myself, with: ].

# 2. Pseudocode



# 3. Initial Solution

class Students
  attr_accessor :first_name, :scores

  def initialize(first_name,scores)   #Use named arguments!
    @first_name = first_name
    @scores = scores
    @average = average
    @students = []
  end

	def average
  		  average = @scores.inject{|sum,element| sum + element} / @scores.length
	end

	def letter_grade
		if @average >= 90
			"A"
		elsif @average <=89
			"B"
		elsif @average <=79
			"C"
		elsif @average <=69
			"D"
		else @average <=59
			"F"
		end
	end
end
	
 def linear_search(array,value)
        len = array.length
        i = 0
    while i < len do
        if value == Alex.first_name
            return 0
        end
        i = i + 1
    end
        return -1
end


Alex = Students.new("Alex", [100,100,100,0,100])
students=[Alex]
4.times do Students.new("student",[rand(100),rand(100),rand(100),rand(100),rand(100)]) end

# 4. Refactored Solution

   





# 1. DRIVER TESTS GO BELOW THIS LINE
# Tests for release 0:

p students[0].first_name == "Alex"
p students[0].scores.length == 5
p students[0].scores[0] == students[0].scores[4]
p students[0].scores[3] == 0


# Tests for release 1:

p students[0].average == 80
p students[0].letter_grade == 'B'

# Tests for release 2:

p linear_search(students, "Alex") == 0
p linear_search(students, "NOT A STUDENT") == -1





# 5. Reflection 