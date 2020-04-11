
$(document).ready(function () {
  var listsSelector = $('#myscript').attr("listsId");
  var buttonSelector = $('#myscript').attr("buttonId");
  var maxInit = parseInt($('#myscript').attr("max"));

  var lists = document.querySelectorAll(listsSelector);
  var button = document.querySelector(buttonSelector);
  var hiddenJs = "hiddenJs";
  var max = maxInit-1;

  if(lists.length-1 <= max){
    button.classList.add(hiddenJs);
  }
  [].forEach.call(lists, function(list, key){
    if (key > max) {
      list.classList.add(hiddenJs);
    }
  });

  button.addEventListener('click', function(){
    max += maxInit; 
    [].forEach.call(lists, function(list, key){
      if (key <= max) {
        list.classList.remove(hiddenJs);
      }
    });
    if(lists.length-1 <= max){
      button.classList.add(hiddenJs);
    }
  }) 
})