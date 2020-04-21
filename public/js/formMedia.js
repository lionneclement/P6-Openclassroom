jQuery(document).ready(function() {
    //startVideo
    var $videoButton = "<div class='col-12'><button type='button' class='btn btn-secondary add_video_link'>Ajouter une video</button></div>";
    var $videoButton1 = "<a href='https://bit.ly/3amy1Tw' target='_blank' type='button' class='btn btn-secondary m-2'>Comment ajouter une video</a>";
    $videoButton.append($videoButton1);
    var $collectionVideo = $("div.video");
    $collectionVideo.append($videoButton);
    $collectionVideo.data("index", $collectionVideo.find("input").length);
    $videoButton.on("click", function() {
        addVideoForm($collectionVideo, $videoButton);
    });
    $collectionVideo.find("div.col-3").each(function() {
      buttonUpdate($(this));
      buttonDelete($(this));
    });
    $("input.classVideo").on("change",function(e) {
        onChangeVideo(e);
    })
    //endVideo
  
    //startPhoto
    var $collectionPhoto = $("div.photo");
    var $photoButton = "<div class='col-12'><button type='button' class='btn btn-secondary add_photo_link m-2'>Ajouter une photo</button></div>";
    $collectionPhoto.append($photoButton);
    $collectionPhoto.data("index", $collectionPhoto.find("input").length);
    $photoButton.on("click", function() {
        addPhotoForm($collectionPhoto, $photoButton);
    });
    $collectionPhoto.find("div.col-3").each(function() {
      buttonUpdate($(this));
      buttonDelete($(this));
    });
    $("input.classPhoto").on("change",function(e) {
        onChangePhoto(e);
    })
    //endPhoto
    $(".inputMedia").addClass("hiddenJs");
});
//function
function intForm($collectionHolder){
  var prototype = $collectionHolder.data("prototype");
  var index = $collectionHolder.data("index");
  $collectionHolder.data("index", index + 1);
  var newForm = prototype;
  newForm = newForm.replace(/__name__/g, index);
  return [newForm,index];
}
function addPhotoForm($collectionHolder, $button) {
    var newForm = intForm($collectionHolder);
    var $col = "<div class='col-3'><div class='photo-container'><img src='/images/download.png' class='tricks_photos_'+newForm[1]+'_file' alt='main picture'></div></div>";
    var $formBootstrap = $("<div class='pt-3 inputMedia'></div>").append(newForm[0]);
    var $newFormLi = $col.append($formBootstrap);
    $button.before($newFormLi);
    buttonUpdate($newFormLi);
    buttonDelete($newFormLi);
    $("input.classPhoto").on("input",function(e) {
      onChangePhoto(e);
    })
}
function addVideoForm($collectionHolder, $button) {
    var newForm = intForm($collectionHolder);
    var $col = "<div class='col-3'><div class='video-container'><iframe src='https://www.youtube.com' class='tricks_videos_'+newForm[1]+'_name' frameborder='0' allowfullscreen></iframe></div></div>";
    var $formBootstrap = "<div class='pt-3 inputMedia'></div>".append(newForm[0]);
    var $newFormLi = $col.append($formBootstrap);
    $button.before($newFormLi);

    buttonUpdate($newFormLi);
    buttonDelete($newFormLi);
    $("input.classVideo").on("change",function(e) {
      onChangeVideo(e);
    })
}
function onChangeVideo(e){
  const regex = RegExp("^https:\/\/");
  var http = "https://";
  var valueVideo =  e.originalEvent.srcElement.value;
  if(!regex.test(valueVideo)){
    var valueVideo = http.concat(valueVideo);
  }
  $("."+e.target.id).attr("src", valueVideo)
}
function onChangePhoto(e) {
  var idInput = e.target.id;
  var file = e.originalEvent.srcElement.files[0];
  var reader = new FileReader();
  reader.onload = function (e1) {
      $("."+idInput).attr("src", e1.target.result)
  };
  reader.readAsDataURL(file);
}
function buttonDelete($form) {
  var $removeFormButton = "<button class='btn btn-secondary m-2' type='button'><i class='fas fa-trash-restore'></i></button>";
  $form.append($removeFormButton);

  $removeFormButton.on("click", function() {
      $form.remove();
  });
}
function buttonUpdate($form) {
  var $updateFormButton = "<button class='btn btn-secondary m-2' type='button'><i class='fas fa-pen'></i></button>";
  $form.append($updateFormButton);

  $updateFormButton.on("click", function() {
    if($form.find(".hiddenJs").length){
      $form.find(".inputMedia").removeClass("hiddenJs");
    }else{
      $form.find(".inputMedia").addClass("hiddenJs");
    }
  });
}