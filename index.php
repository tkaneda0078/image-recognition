<!DOCTYPE html>
<html lang="ja">
<head>
  <meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
  <meta name="viewport" content="width=device-width">
  <title>画像認証</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
        integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
          integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
          crossorigin="anonymous"></script>
  <style type="text/css">
    .img-thumbnail {
      max-width: 50%;
    }
  </style>
</head>
<body>
<div class="container">
  <h3>画像認証</h3>
  <p class="help-block">※画像をアップロードして下さい(最大2MB、拡張子：jpgのみ)</p>
  <form class="form-group" name="form1" method="post" action="imageRecognition.php" enctype="multipart/form-data">
    <input type="file" id="files" name="files"><br>
    <input type="submit" class="btn btn-info btn-lg btn-secondary" value="判定実施">
    <output id="list"></output>
    <script>
        function handleFileSelect(evt) {
            var files = evt.target.files;
            for (var i = 0, f; f = files[i]; i++) {
                if (!f.type.match('image.*')) {
                    continue;
                }

                var reader = new FileReader();
                reader.onload = (function (theFile) {
                    return function (e) {
                        // Render thumbnail.
                        var span = document.createElement('span');
                        span.innerHTML = ['<img src="', e.target.result, '" class="img-thumbnail" title="',
                            escape(theFile.name), '" />'].join('');
                        document.getElementById('list').insertBefore(span, null);
                    };
                })(f);
                reader.readAsDataURL(f);
            }
        }
        document.getElementById('files').addEventListener('change', handleFileSelect, false);
    </script>
  </form>
</div>
</body>
</html>
