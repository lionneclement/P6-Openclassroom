var $collectionHolder;
var $addTagButton = $('<div class="col-12"><button type="button" class="btn btn-secondary add_video_link">Ajouter une video</button></div>');

jQuery(document).ready(function() {
    $collectionHolder = $('div.video');
    
    $collectionHolder.append($addTagButton);
    $collectionHolder.data('index', $collectionHolder.find('input').length);
    $addTagButton.on('click', function(e) {
        addTagForm($collectionHolder, $addTagButton);
    });
    $collectionHolder.find('div.col-3').each(function() {
        addTagFormDeleteLink($(this));
    });
});
function addTagForm($collectionHolder, $addTagButton) {
    var prototype = $collectionHolder.data('prototype');
    var index = $collectionHolder.data('index');
    var newForm = prototype;
    newForm = newForm.replace(/__name__/g, index);
    $collectionHolder.data('index', index + 1);
    var $newFormLi = $('<div class="col-3"></div>').append(newForm);
    $addTagButton.before($newFormLi);

    addTagFormDeleteLink($newFormLi);
}
function addTagFormDeleteLink($tagFormLi) {
  var $removeFormButton = $('<button class="btn btn-secondary mb-3" type="button">Supprimer</button>');
  $tagFormLi.append($removeFormButton);

  $removeFormButton.on('click', function(e) {
      $tagFormLi.remove();
  });
}