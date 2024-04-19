document.addEventListener('DOMContentLoaded', function() {
    var imageRadio = document.getElementById('imageRadio');
    var videoRadio = document.getElementById('videoRadio');
    var imageField = document.getElementById('imageField');
    var videoField = document.getElementById('videoField');

    imageRadio.addEventListener('click', function() {
        imageField.style.display = 'block';
        videoField.style.display = 'none';
        videoField.querySelector('input').value = '';
    });

    videoRadio.addEventListener('click', function() {
        imageField.style.display = 'none';
        videoField.style.display = 'block';
        imageField.querySelector('input').value = '';
    });
});