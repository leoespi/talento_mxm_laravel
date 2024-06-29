<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cesantía Denegada</title>
</head>
<body>
    <h2>Solicitud de Cesantía Denegada</h2>
    <p>Hola {{ $justificacion->user->name }},</p>
    <p>Lamentamos informarte que tu solicitud de cesantía ha sido denegada. A continuación encontrarás detalles adicionales:</p>
    <ul>
        <li><strong>Tipo de cesantía reportada:</strong> {{ $justificacion->tipo_cesantia_reportada }}</li>
        <li><strong>Justificación:</strong> {{ $justificacion->justificacion }}</li>
    </ul>
    <p>Esta dirección de e-mail es utilizada solamente para envíos automáticos de información.
    Por favor no responda este correo con consultas ya que no podrán ser atendidas.</p>
    <p>Gracias.</p>
</body>
</html>
