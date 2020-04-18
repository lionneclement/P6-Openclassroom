$(document).ready(function () {
    var trickId=$('.show-comment').data('id'), commentStart=4, maxResult=4, maxComment=0;
    requestGetComment(trickId);
    var button = document.querySelector('#buttonMoreJs');
    button.addEventListener('click', function(){
      checkNumberComment()
    });

    function requestGetComment(id){
      $.ajax({
        url: 'http://localhost:8000/comment/api/count/'+id+'',
        type: "GET",
        success: handleGetData,
      });
    }
    function handleGetData(data){
      maxComment=parseInt(data.commentCount);
      if (maxComment > 4){
        showButton();
      }
    }
    function checkNumberComment(){
      if (maxComment > 4 && commentStart < maxComment){
        requestPostComment(trickId, commentStart, maxResult);
        if(commentStart+maxResult >= maxComment){
          hiddenButton();
        }
        commentStart+=maxResult;
      }
    }
    function hiddenButton(){
      button.classList.add("hiddenJs");
    }
    function showButton(){
      button.classList.remove("hiddenJs");
    }
    function requestPostComment(trickId, commentStart, maxResult){
    var data = {
      "trickId": trickId,
      "commentStart": commentStart,
      "maxResult": maxResult
    }
    $.ajax({
      url: 'http://localhost:8000/comment/api/found',
      type: "POST",
      data: JSON.stringify(data),
      contentType: "application/json",
      success : handlePostData
    });
    }
    function handlePostData(responsedata) {
      var button = $('#buttonMoreJs');
      button.before(responsedata);
    }
})