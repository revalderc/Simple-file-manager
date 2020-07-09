function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            let result = e.target.result;
            let type = e.target.result.split(';base64,')[0];
            let dataType = type.split(':')[1];
            let elementResult = null;
            let isVideo = false;

            console.log(dataType);

            switch(dataType) {
                case 'image/png':
                case 'image/jpeg':
                case 'image/gif':
                    elementResult = renderImage(result);
                    break;

                case 'application/pdf':
                    elementResult = renderPDF(result);
                    break;

                case 'video/mp4':
                    elementResult = renderVideo(result);
                    isVideo = true;
                    break;

                default:
                    elementResult = renderNoPreview();

            }

            // preview
            $('#preview').html(elementResult);

            // add controls to video
            if(isVideo === true) {
                addVideoControls();
            }else {
                removeVideoControls();
            }

        }

        reader.readAsDataURL(input.files[0]);
    }
}

function renderImage(base64Image) {
    let imageElement = document.createElement('img');
    imageElement.src = base64Image;
    imageElement.className = 'img-fluid';

    return imageElement;
}

function renderPDF(base64PDF) {
    let pdfElement = document.createElement('embed');
    pdfElement.src = base64PDF;
    pdfElement.width = '100%';
    pdfElement.height = 400;

    return pdfElement;
}

function renderVideo(base64Video) {
    let videoElement = '<video id="video-preview" width="100%">'+
                            '<source src="'+base64Video+'" type="video/mp4">'+
                            'Your browser does not support HTML video.'+
                        '</video>';
    return videoElement;
}

function renderNoPreview() {
    return '<p><em>No available preview.</em></p>';
}

function addVideoControls() {
    $('#video-controls').css('display', 'block');
    $('#video-controls > .play-pause-button').html('⏵').attr('title', 'Play');
}

function removeVideoControls() {
    $('#video-controls').css('display', 'none');
}

function play() {
    let videoPreview = document.getElementById("video-preview");

    if(videoPreview.paused) {
        videoPreview.play();
        $('#video-controls > .play-pause-button').html('⏸').attr('title', 'Pause');
    }else {
        videoPreview.pause();
        $('#video-controls > .play-pause-button').html('⏵').attr('title', 'Play');
    }
}

$("#file-input").change(function(){
    console.log('Initializing...');
    readURL(this);
});
