var trickStart=4,maxResult=4,maxTrick=0;
$(document).ready(function () { 
  requestGetCountTrick();
  var button = document.querySelector('#buttonMoreJs');
  button.addEventListener('click', function(){
    checkNumberTrick()
  });
});

function requestGetCountTrick(){
  return $.ajax({
    url: 'http://localhost:8000/tricks/api/count',
    type: "GET",
    success: handleGetData,
  });
}
function handleGetData(data){
  maxTrick = parseInt(data.trickCount);
  if (maxTrick > 4){
    showButton();
  }
}
function checkNumberTrick(){
  if (maxTrick > 4 && trickStart < maxTrick){
    requestPostTrick(trickStart, maxResult);
    if(trickStart+maxResult >= maxTrick){
      hiddenButton();
    }
    trickStart+=maxResult;
  }
}

function hiddenButton(){
  var button = document.querySelector('#buttonMoreJs');
  button.addClass("hiddenJs");
}
function showButton(){
  var button = document.querySelector('#buttonMoreJs');
  button.removeClass("hiddenJs");
}

function requestPostTrick(trickStart, maxResult){
  var data = {
    "trickStart": trickStart,
    "maxResult": maxResult
  }
  $.ajax({
    url: 'http://localhost:8000/tricks/api/found',
    type: "POST",
    data: JSON.stringify(data),
    contentType: "application/json",
    success : handlePostData
  });
  }
  function handlePostData(responsedata) {
    var trickRow = $('#trickRow');
    trickRow.append(responsedata);
  }