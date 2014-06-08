# U3.W8-9: Implement a Rectangle Class


# I worked on this challenge [by myself, with: ].

# 2. Pseudocode



# 3. Initial Solution
class Rectangle
  attr_accessor :width, :height

  def initialize(width, height)
    @width  = width
    @height = height
  end

  def ==(other)
    (other.width  == self.width && other.height == self.height ) ||
    (other.height == self.width && other.width  == self.height )
  end

  def area
  		area = width*height
  end

  def perimeter
  		perimeter = 2*(width+height)
  end

  def diagonal
  		diagonal = Math.sqrt((width**2)+(height**2)).to_f
  end

  def square?
  	if @height == @width 
  		return true
  	else
  		return false
  	end
  end

end




# 4. Refactored Solution






# 1. DRIVER TESTS GO BELOW THIS LINE

rec = Rectangle.new(20,30)
puts rec.area
puts rec.perimeter
puts rec.diagonal
puts rec.square?




# 5. Reflection 