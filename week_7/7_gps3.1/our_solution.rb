# U3.W7: Build an Electronic Grocery List!
 
# Your full names:
# 1.
# 2.
 
# User Stories (As many as you want. Delete the statements you don't need)
# As a user, I want to
# As a user, I want to
# As a user, I want to
# As a user, I want to
# As a user, I want to
 
# Pseudocode
# 
# 
# 
# 
# 
# 
 
 
# Your fabulous code goes here....


#GROCERY LIST

#Release 0: User Stories
#With your partner, talk about what you would like to do with your grocery list. Start each statement with 
#"As a user, I want to ..." These are what we call "user stories."

#Release 1: Pseudocode
#What objects, classes, and methods will you need to do each of the things you identified in your user stories?

#Release 2: Write Driver Code
#Translate your pseudocode into driver code. Write driver code at the bottom (to call the method on the object).



# U3.W7: Build an Electronic Grocery List!
 
# Your full names:
# 1. Antonio Perez
# 2. Michael Ginalick
 
# User Stories (As many as you want. Delete the statements you don't need)

# As a user, I want to add quantities of things and remove items
# As a user, I want to calculate the total price of the list
# As a user, I want to
# As a user, I want to
# As a user, I want to 
 
# Pseudocode
# create class Grocercy List
# initialize data input
# create methods
# 
# 
# 
 
 
# Your fabulous code goes here....


        
class List
        
    def initialize
        @list = []
    end    
    
    def total_price
        puts "The total price per item is :"
        @list.map { |item_object| puts item_object.total.to_s + " " + "for" + " " + item_object.quantity.to_s + " " +item_object.name}
        puts "The grand total is:"
        sum = 0
        @list.map do |a| sum += a.total end
            puts sum
    end

    def view_contents
        puts "The list contains:"
        @list.map {|object| puts "-" + object.quantity.to_s + " " + object.name}
    end    

    def add(stuff)
        @list << stuff
    end

    def remove(stuff)
        @list.delete(stuff)
    end
      

end


class Item
    
    attr_reader :name, :quantity, :price
    
    def initialize(name, quantity, price)
        @name = name
        @quantity = quantity
        @price = price
    end    
    
        
    def total
        @quantity * @price
    end

    def name
        @name
    end  
 
end




# DRIVER CODE GOES HERE. 

new_list = List.new()

banana = Item.new('banana(s)', 5, 1)
apple = Item.new('apple(s)', 2, 0.5)

new_list.add(banana)
new_list.add(apple)


puts new_list.total_price
puts new_list.view_contents

















# DRIVER CODE GOES HERE. 
 
 
 
