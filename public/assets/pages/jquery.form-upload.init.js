/**
 * Theme: Dastone - Responsive Bootstrap 5 Admin Dashboard
 * Author: Mannatthemes
 * Upload Js
 */


 // dropify js
 
$(function () {
  // Basic
  $('.dropify').dropify();

  // Translated
    // Translated
    $('.dropify-ru').dropify({
        messages: {
            default: 'Перетащите файл сюда или нажмите',
            replace: 'Перетащите файл сюда или нажмите для замены',
            remove:  'Удалить',
            error:   'Извините, файл слишком большой'
        }
    });

  $('.dropify-fr').dropify({
      messages: {
          default: 'Glissez-déposez un fichier ici ou cliquez',
          replace: 'Glissez-déposez un fichier ou cliquez pour remplacer',
          remove:  'Supprimer',
          error:   'Désolé, le fichier trop volumineux'
      }
  });

  // Used events
  var drEvent = $('#input-file-events').dropify();

  drEvent.on('dropify.beforeClear', function(event, element){
      return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
  });

  drEvent.on('dropify.afterClear', function(event, element){
      alert('File deleted');
  });

  drEvent.on('dropify.errors', function(event, element){
      console.log('Has Errors');
  });

  var drDestroy = $('#input-file-to-destroy').dropify();
  drDestroy = drDestroy.data('dropify')
  $('#toggleDropify').on('click', function(e){
      e.preventDefault();
      if (drDestroy.isDropified()) {
          drDestroy.destroy();
      } else {
          drDestroy.init();
      }
  })
});