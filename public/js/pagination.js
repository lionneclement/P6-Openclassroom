$(document).ready(function () {
    var trickId=$('.show-comment').data('id'), commentStart=0, maxResult=4, maxComment=0;
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
      checkNumberComment();
    }
    function checkNumberComment(){
      if (maxComment == 0){
        maxResult=0
        hiddenButton();
      }else if(maxComment <= maxResult){
        maxResult=0
        hiddenButton();
      }else if (commentStart+maxResult == maxComment) {
        maxResult=0
        hiddenButton();
      }else if (commentStart+maxResult > maxComment){
        maxResult = maxComment-commentStart
        hiddenButton();
      }
      if (maxResult != 0){
        requestPostComment(trickId, commentStart, maxResult);
        commentStart+=maxResult;
      }
    }
    function hiddenButton(){
      button.classList.add("hiddenJs");
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
      var responsedata = responsedata;
      var button = $('#buttonMoreJs');
      responsedata.comments.forEach(function(comment){
        var directory = 'upload';
       if (comment.userImageName == 'default-user.png'){
        directory = 'images';
       }
        var htmlComment = $("<div class='container pt-4' id='commentJs'><div class='media'><div class='media-body'><img src='/"+directory+"/"+comment.userImageName+"'class='avatar-min'alt='Photo de profil' /><h5 class='mt-0'>"+comment.userName+"</h5><i>"+comment.date.date.split(' ')[0]+"</i><p class='card-text'>"+comment.message+"</p></div></div></div>");
        button.before(htmlComment);
      })
    }
})