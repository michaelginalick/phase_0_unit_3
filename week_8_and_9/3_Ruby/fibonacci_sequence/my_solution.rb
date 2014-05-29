# U3.W8-9: 


# I worked on this challenge [by myself, with: ].

# 2. Pseudocode



# 3. Initial Solution

def is_fibonacci?(num)

		fibonacci = [0,1]
	while fibonacci.last <= num 
		fibonacci << fibonacci.last(2).reduce(:+)	
	end

	fibonacci.include?(num)
end

# 4. Refactored Solution






# 1. DRIVER TESTS GO BELOW THIS LINE


puts is_fibonacci?(8670007398507948658051921)



# 5. Reflection 