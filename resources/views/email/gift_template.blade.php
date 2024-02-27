
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>

    <style>
        .position-relative {
            position: relative;
            width: 400px;
        }

        .cover__item {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
        }

        .cover_project_name {
            top: 450px;
        }

        .cover_sender_name {
            top: 310px;
        }

        .cover_recipient_name {
            bottom: 90px;
        }
    </style>
</head>

<body>
    <div class="position-relative">
        <h4 class="d-block cover__item cover_project_name">
            {{$projectName}}
        </h4>
        <h4 class="d-block cover__item cover_sender_name">
            {{$recipient_name}}
        </h4>

        <h4 class="d-block cover__item cover_recipient_name">
            {{$sender_name}}
        </h4>

        <img src="{{$template_url}}" height="800" width="100%" />
    </div>
</body>

</html>


