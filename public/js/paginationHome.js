$(document).ready(function () {
  var tricks  = document.querySelectorAll('#trickJs');
  var button =  document.querySelector('#buttonMoreJs');
  var max = 3;
  var hiddenJs = "hiddenJs";
  [].forEach.call(tricks, function(trick, key){
    if (key > max) {
      trick.classList.add(hiddenJs);
    }
  });

  button.addEventListener('click', function(){
    max += 4;
    [].forEach.call(tricks, function(trick, key){
      if (key <= max) {
        trick.classList.remove(hiddenJs);
      }
    });
    if(tricks.length <= max){
      button.classList.add(hiddenJs);
    }
  }) 
})