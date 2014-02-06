<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>AD Replacer Tool</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <style>
            #header{
                height: 100px;
                border-bottom: 1px solid black;
            }
            #url-input{
                width: 320px;
            }
            #preview-container{
                width: 100%;
                margin-top: 10px;
                min-height: 256px;

                text-align: center;
            }
            #snap-img{
                width: 100%;
            }
            #ad-placement-select-container, #replace-button, #ad-placement-image-container{
                display: none;
            }
            #show-button, #replace-button{
                margin-left: 124px;
            }
            label{
                display: inline-block;
                width: 120px;
                text-align: right;
            }
            .user-input-container, .info-container{
                display: inline-block;
            }
            .info-container img{
                margin-left: 20px;
                height: 80px;
            }
            .loading-message{
                font-style: italic;
            }
            .loading-bar{
                position: absolute;
                left: 0;
                top: 0;
                z-index: 1;
                background-color: rgba(12,56,78,0.5);
                width: 0;
                height: 109px;
            }
            .start-over{
                position: absolute;
                right: 0;
                top: 0;
                padding: 2px;
                margin: 5px;
                border: 1px solid gray;
                color: black;
                text-decoration: none;
            }
            .start-over:hover{
                background-color: gray;
            }
            input[type=num],input[type=url]{
                border: 1px solid black;
                margin: 2px 0 2px 0;
            }
            .error{
                border: 1px solid red;
            }
        </style>
    </head>

    <body>

        <div id="header">

            <a href="index.php" class='start-over'>START OVER</a>

            <div class="loading-bar"></div>

            <span class="user-input-container">

                <div id='url-input-container'>
                    <label>URL:</label>
                    <input id='url-input' type='url' placeholder='target site url' />
                </div>

                <div id='ad-placement-select-container'>
                    <label>Placement:</label>
                    <select id='ad-placement-select'>
                        <option value="yrailTop300x250_frame">yrailTop300x250_frame</option>
                        <option value="collage300x250_frame">collage300x250_frame</option>
                        <option value="header728x90_frame">header728x90_frame</option>
                    </select>
                </div>

                <div id='ad-placement-image-container'>
                    <label>Image:</label>
                    <input id='ad-placement-image' type='file' placeholder='choose image file' accept="image/*" />
                </div>

                <div id='render-delay-input-container'>
                    <label>Render delay(ms):</label>
                    <input id='render-delay-input' type='num' placeholder='optional js delay' />
                </div>

                <button id='replace-button'>replace</button>
                <button id='show-button'>show placements</button><br />


            </span>

            <span class="info-container">

                <div class="image-preview-container"></div>

            </span>

        </div>

        <div id='preview-container'></div>

        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script type='text/javascript'>
            var image_data, form_image;

            $('#replace-button').click(function() {
                var url = $('#url-input').val();
                var ad_target = $('#ad-placement-select').val();
                var replacement_image = "http://userserve-ak.last.fm/serve/_/88031855/wutang+png.png";
                var render_delay = $('#render-delay-input').val();

                if (render_delay == '')
                    render_delay = 0;

                var loading_time = 20000 + parseInt(render_delay);
                console.log(loading_time);
                $('#preview-container').html('<img src="css/images/loading.gif" />');
                $('.loading-bar').animate({width: '100%'}, loading_time);

                var formdata = new FormData();
                formdata.append("image", form_image);

                var ajax_url = "tools/generate_snap.php?ad_target=" + ad_target + "&replacement_image=" + replacement_image + "&url=" + url + "&render_delay=" + render_delay;
                $.ajax({
                    url: ajax_url,
                    type: 'POST',
                    data: formdata,
                    processData: false,
                    contentType: false,
                    success: function(result) {
                        var snap = 'snaps/snap.png';
                        console.log(result);
                        $('#preview-container').html('<img id="snap-img" src="' + snap + '" />');
                        $('.loading-bar').stop().animate({width: 0}, 1);
                    }
                });
            });

            $('#show-button').click(function() {
                var url = $('#url-input').val();

                if (url.length == 0) {
                    $('#url-input').addClass('error');
                    return;
                }

                var render_delay = $('#render-delay-input').val();

                if (render_delay == '')
                    render_delay = 0;
                
                var loading_time = 20000 + parseInt(render_delay);
                console.log(loading_time);
                $('#preview-container').html('<img src="css/images/loading.gif" />');
                $('.loading-bar').animate({width: '100%'}, loading_time);

                var ajax_url = "tools/generate_snap_all.php?url=" + url + "&render_delay=" + render_delay;
                $.ajax({
                    url: ajax_url,
                    async: true,
                    success: function(result) {
                        var snap = 'snaps/all.png';
                        console.log(result);
                        $('#preview-container').html('<img id="snap-img" src="' + snap + '" />');
                        $('#ad-placement-select').html('');
                        $.each(result.split(','), function(key, val) {
                            if (val.indexOf('_frame') !== -1)
                                $('#ad-placement-select').append("<option value=" + val + ">" + val + "</option>");
                        });
                        $('#url-input-container').hide();
                        $('#ad-placement-select-container').show();
                        $('#show-button').hide();
                        $('#replace-button').show();

                        $('#ad-placement-image-container').show();
                        $('.loading-bar').stop().animate({width: 0}, 1);
                    }
                });
            });

            /* Media Previews */
            function showPreview(input) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('.image-preview-container').html('<img src=' + e.target.result + ' />');
                    image_data = e.target.result;
                };

                reader.readAsDataURL(input.files[0]);
                //var new_media = $(input);
            }

            $(document).on('change', '#ad-placement-image', function() {
                form_image = this.files[0];
                showPreview(this);
            });
        </script>

    </body>
</html>
