var trickStart=4,maxResult=4,maxTrick=0,trickPerPage=4;
var urlCountTrick="/tricks/api/count",urlFoundTrick="/tricks/api/found";
$(document).ready(function () { 
  requestGetCountTrick();
  var button = document.querySelector("#buttonMoreJs");
  button.onclick = function(){
    checkNumberTrick()
  };
});

function requestGetCountTrick(){
  return $.ajax({
    url: urlCountTrick,
    type: "GET",
    success: handleGetData,
  });
}
function handleGetData(data){
  maxTrick = parseInt(data.trickCount);
  if (maxTrick > trickPerPage){
    showButton();
  }
}
function checkNumberTrick(){
  if (maxTrick > trickPerPage && trickStart < maxTrick){
    requestPostTrick(trickStart, maxResult);
    if(trickStart+maxResult >= maxTrick){
      hiddenButton();
    }
    trickStart+=maxResult;
  }
}

function hiddenButton(){
  var button = document.querySelector("#buttonMoreJs");
  button.classList.add("hiddenJs");
}
function showButton(){
  var button = document.querySelector("#buttonMoreJs");
  button.classList.remove("hiddenJs");
}

function requestPostTrick(trickStart, maxResult){
  var data = {
    "trickStart": trickStart,
    "maxResult": maxResult
  }
  $.ajax({
    url: urlFoundTrick,
    type: "POST",
    data: JSON.stringify(data),
    contentType: "application/json",
    success : handlePostData
  });
  }
  function handlePostData(responsedata) {
    var trickRow = $("#trickRow");
    trickRow.append(responsedata);
  }