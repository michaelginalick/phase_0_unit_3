# U3.W8-9: Reverse Words


# I worked on this challenge [by myself, with: ].

# 2. Pseudocode

#for each element in the array reverse that element

# 3. Initial Solution

	def reverse_words(string)
		a = string.split(" ")
		b = a.length
		
		if b > 1
			 a.map{|value| value = value.reverse}.join(" ")
			else
			 string.reverse
		end
	end


# 4. Refactored Solution


# 1. DRIVER TESTS/ASSERT STATEMENTS GO BELOW THIS LINE

#reverse_words("this is the place")

		#arr = string.split(" ")
		#barr = arr.each{|value|}
		#barr.each do |n|
		#	n.reverse
		#end 


# 5. Reflection 