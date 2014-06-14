 var bodyElement
 var mascotClass
 var h1Element
 var mascotImage
$(document).ready(function(){
 
//RELEASE 0: 
  //Link this script and the jQuery library to the jquery_example.html file and analyze what this code does. 
 
$('body').css({'background-color': 'pink'})
 
//RELEASE 1:
  //Add code here to select elements of the DOM 

   bodyElement = $('body');
   mascotClass = $('.mascot');
   h1Element = $('h1');
   mascotImage = $('img');

 
 
//RELEASE 2: 
  // Add code here to modify the css and html of DOM elements

$('h1').css({'color': 'blue'})
$('h1').css({'border-style': 'solid'})
$('h1').css({'visibility': 'visible'})
$('.mascot h1').html('Salamanders')

 
 
//RELEASE 3: Event Listener
  // Add the code for the event listener here 

 $('img').on('mouseover', function(e){
     e.preventDefault()
    $(this).attr('src', 'http://images.nationalgeographic.com/wpf/media-live/photos/000/007/cache/spotted-salamander_721_600x450.jpg')
  }) 

   $('img').on('mouseleave', function(e){
     e.preventDefault()
    $(this).attr('src', 'dbc_logo.jpg')
  })
 
 
//RELEASE 4 : Experiment on your own
 
 
 
 
 
 
})  // end of the document.ready function: do not remove or write DOM manipulation below this.
