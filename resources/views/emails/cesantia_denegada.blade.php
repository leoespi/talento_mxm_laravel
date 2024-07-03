<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cesantía Denegada</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            padding: 20px;
        }
        .container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 30px;
            max-width: 600px;
            margin: 0 auto;
        }
        h1 {
            color: #333333;
        }
        p {
            color: #666666;
            line-height: 1.6;
        }
        .footer {
            margin-top: 20px;
            font-size: 0.8em;
            color: #999999;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Cesantía Denegada</h1>
        <p>Hola <strong>{{ $nombre_usuario }}</strong>,</p>
        <p>Lamentamos informarte que tu solicitud de cesantía ha sido denegada. A continuación encontrarás detalles adicionales:</p>
        <p><strong>Tipo de cesantía reportada:</strong> {{ $tipo_cesantia_reportada }}</p>
        <p><strong>Justificación:</strong> {{ $justificacion }}</p>
        
        <p>Esta dirección de e-mail es utilizada solamente para envíos automáticos de información. Por favor no responda este correo con consultas ya que no podrán ser atendidas.</p>
        <p>Gracias.</p>
        <div class="footer">
            <p>Este es un mensaje automático, por favor no responder.</p>
        </div>
    </div>
</body>
</html>
