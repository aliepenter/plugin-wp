const addButton = $('#img-upload-button');
const img = $('#img-tag');
const hidden = $('#img-hidden-field');
const customUploader = wp.media({
  title: 'Select an image!',
  button: {
    text: 'Use this Image'
  },
  multiple: false
});
addButton.click(function(){
  if(customUploader){
    customUploader.open();
  }
});
customUploader.on('select',function(){
    const attachment = customUploader.state().get('selection').first().toJSON();
    img.attr('src', attachment.url);
    img.attr('style', 'width: 200px; height: 100px;');
    hidden.attr('value', JSON.stringify([{id: attachment.id, url: attachment.url}]));
});