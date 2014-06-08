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

  def rectangle_area
  		area = width*height
  end

  def rectangle_perimeter
  		perimeter = 2*(width+height)
  end

  def rectangle_diagonal
  		diagonal = Math.sqrt((width**2)+(height**2)).to_f
  end

  def rectangle_square?
  	if height == width 
  		return true
  	else
  		return false
  	end
  end

end




# 4. Refactored Solution






# 1. DRIVER TESTS GO BELOW THIS LINE

rec = Rectangle.new(20,30)
puts rec.rectangle_area
puts rec.rectangle_perimeter
puts rec.rectangle_diagonal
puts rec.rectangle_square?




# 5. Reflection 