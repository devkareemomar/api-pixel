{{-- <!DOCTYPE html>
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
</html> --}}


<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title></title>
  </head>

  <body>
    <table role="presentation" border="0" cellspacing="0" cellpadding="0" style="
				background-image: url('{{$template_url}}');
				background-size: contain;
				background-repeat: no-repeat;
				width: 320px;
				height:720px;
			">
      <tr style="height:220px">
        <td align="center" style="vertical-align :bottom">
          <h3>
            {{$recipient_name}}

          </h3>
        </td>
      </tr>
      <tr style="height:180px">
        <td align="center" style="vertical-align :middle">
          <h3>
            {{$projectName}}

          </h3>
        </td>
      </tr>
      <tr style="height:220px">
        <td align="center" style="vertical-align :top">
          <h3>
            {{$sender_name}}

          </h3>
        </td>
      </tr>
    </table>
  </body>

</html>
