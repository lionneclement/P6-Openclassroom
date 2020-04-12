var $collectionHolder1;
var $addTagButton1 = $('<div class="col-12"><button type="button" class="btn btn-secondary add_photo_link">Ajouter une photo</button></div>');

jQuery(document).ready(function() {
    $collectionHolder1 = $('div.photo');
    
    $collectionHolder1.append($addTagButton1);
    $collectionHolder1.data('index', $collectionHolder1.find('input').length);
    $addTagButton1.on('click', function(e) {
        addTagForm($collectionHolder1, $addTagButton1);
    });
    $collectionHolder1.find('div.col-3').each(function() {
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