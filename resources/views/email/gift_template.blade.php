<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif;">
    <div style="position: relative; width: 100%; max-width: 400px; margin: 0 auto;">
        <h4 style="display: block; position: absolute; left: 50%; transform: translateX(-50%); top: 450px; margin: 0; padding: 0;">
            {{$projectName}}
        </h4>
        <h4 style="display: block; position: absolute; left: 50%; transform: translateX(-50%); top: 310px; margin: 0; padding: 0;">
            {{$recipient_name}}
        </h4>
        <h4 style="display: block; position: absolute; left: 50%; transform: translateX(-50%); bottom: 90px; margin: 0; padding: 0;">
            {{$sender_name}}
        </h4>
        <img src="{{$template_url}}" height="800" width="100%" style="display: block; margin: 0 auto;">
    </div>
</body>
</html>
