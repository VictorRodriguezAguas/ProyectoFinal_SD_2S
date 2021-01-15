<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Mail
 *
 * @author ernesto.ruales
 */
//require_once '../php-mailer/PHPMailerAutoload.php';

class Mail {

    public $error = null;

    //put your code here
    public function enviarCorreo($destinatario, $texto, $Subject) {
        try {
            $this->error = null;
            $email = "informacion@epico.gob.ec";
            $pass = "Ep1c0*2020AD";
            $host = "smtp.gmail.com";

            $body = "";
            $mail = new PHPMailer();
            $mail->IsSMTP();
            $mail->SMTPAuth = true;

            //Temporal
            $mail->SMTPSecure = "tls";  // tls
            $mail->Host = $host; // SMTP a utilizar. Por ej. smtp.elserver.com
            $mail->Username = $email; // Correo completo a utilizar
            $mail->Password = $pass; // Contrase�a	/
            $mail->Port = 587; // Puerto a utilizar 465 587

            $mail->From = $email;
            $mail->FromName = "Empresa Publica Municipal para la Gestion de la Innovacion y Competitividad, Epico";
            $mail->IsHTML(true); // El correo se envia como HTML
            $mail->Subject = $Subject;
            $correos = explode(";", $destinatario);

            foreach ($correos as &$correo) {
                $mail->AddAddress(trim($correo));
            }
            // $mail->addBCC("$correoDestinatario");
            $mail->Body = $texto;
            $mail->CharSet = 'UTF-8';
            if (!$mail->Send()) {
                $this->error = "Error: " . $mail->ErrorInfo;
                return false;
            }
            return true;
        } catch (Exception $e) {
            $this->error = "Error: " . $e->getMessage();
            return false;
        }
    }

    public function enviarCorreoArchivo($destinatario, $texto, $Subject, $archivo) {
        try {
            $this->error = null;
            $email = "informacion@epico.gob.ec";
            $pass = "Ep1c0*2020AD";
            $host = "smtp.gmail.com";

            $body = "";
            $mail = new PHPMailer();
            $mail->IsSMTP();
            $mail->SMTPAuth = true;

            //Temporal
            $mail->SMTPSecure = "tls";  // tls
            $mail->Host = $host; // SMTP a utilizar. Por ej. smtp.elserver.com
            $mail->Username = $email; // Correo completo a utilizar
            $mail->Password = $pass; // Contrase�a	/
            $mail->Port = 587; // Puerto a utilizar 465 587

            $mail->From = $email;
            $mail->FromName = "Empresa Publica Municipal para la Gestion de la Innovacion y Competitividad, Epico";
            $mail->IsHTML(true); // El correo se envia como HTML
            $mail->Subject = $Subject;

            $correos = $n = explode(";", $destinatario);
            foreach ($correos as &$correo) {
                $mail->AddAddress(trim($correo));
            }

            // $mail->addBCC("$correoDestinatario");
            $mail->Body = $texto;
            $mail->CharSet = 'UTF-8';
            if (isset($archivo)) {
                $mail->addAttachment($archivo->url, $archivo->nombre);
            }
            if (!$mail->Send()) {
                $this->error = "Error: " . $mail->ErrorInfo;
                return false;
            }
            return true;
        } catch (Exception $e) {
            $this->error = "Error: " . $e->getMessage();
            return false;
        }
    }

    public function getCorreoAprobacionEmprendedor($destinatario, $nombres, $apellidos, $usuario, $password) {
        $texto = '
            <p>Estimado(a) ' . $nombres . ' ' . str_replace("'", "", $apellidos) .
                '<br>
            <br></p>
            <p>La Empresa Publica Municipal para la Gestion de Innovacion y Competitividad, EP agradece su registro.</p>
 
            <p>Le comunicamos que hemos validado la informacion ingresada, ahora dispones de un usuario y contrasena. </p>
            <p>El siguiente paso sera llenar el formulario de inscripcion para la plataforma de tienda virtual. </p>
            <p>Te invitamos a leer los requisitos previos al  formulario que se encuentran en el archivo adjunto en PDF o accede al link <a href="http://epico.gob.ec/wp-content/uploads/2020/05/Requisitos-para-inscripcio%CC%81n-Mercado-593-Guayaco.pdf">Requisitos</a>. </p>
            <p>Aqui encontras el link para completar el formulario, el mismo que debe ser llenado en su totalidad para completar la inscripcion.</p>
            
            <br>
            <br>
            Usuario: <b>' . $usuario . '</b> <br>
            Password: <b>' . $password . '</b> <br>
            link: <b> <a href=\'https://epico.gob.ec/epicoPI/login.php\' target="_blank">Sistema epico</a>
            <br>
            <br>
            <p>¡Juntos construiremos un Guayaquil EPICO!</p>
            
            <br><p style=\'color: red\'>*La tildes en el correo se han omitido intencionalmente</p>'
        ;
        return $texto;
    }

    public function enviarCorreoAprobacionEmprendedorPersonalizado($destinatario, $texto) {
        $Subject = "Registro Emprendedor";
        $archivo = new stdClass();
        $archivo->url = "http://epico.gob.ec/archivos/Requisitos_para_inscripcio%CC%81n_Mercado_593_Guayaco.pdf";
        $archivo->nombre = "Requerimientos";
        //return $this->enviarCorreoArchivo($destinatario, $texto, $Subject, $archivo);
        return true;
    }

    public function getCorreoRechazoEmprendedor($destinatario, $nombres, $apellidos) {
        $texto = '
            <p>Estimado(a) ' . $nombres . ' ' . str_replace("'", "", $apellidos) .
                '<br>
            <br></p>
            <p>La Empresa Publica Municipal para la Gestion de la Innovacion y Competitividad, EP agradece tu registro.</p>
 
            <p>Queremos informarte que, por el momento no cumples con los requisitos que necesitas para acceder a la plataforma de la tienda virtual. Para mas informacion puedes revisar los requerimientos basicos que se encuentran disponibles en <a href="https://epico.gob.ec/wp-content/uploads/2020/05/Requisitos-para-inscripcio%CC%81n-Mercado-593-Guayaco.pdf">https://epico.gob.ec/wp-content/uploads/2020/05/Requisitos-para-inscripcio%CC%81n-Mercado-593-Guayaco.pdf</a></p>

            <p>Sin embargo, te invitamos a estar pendiente de nuestras redes sociales <a href="@epicogye">@epicogye</a> para futuras capacitaciones y talleres que seran muy beneficiosos para fortalecer el modelo de negocio y ventas de tu emprendimiento. </p>

            <br>
            <p>¡Juntos construiremos un Guayaquil EPICO!</p>


            <br>
            <br>
            <br><p style="color: red">*La tildes en el correo se han omitido intencionalmente</p>'
        ;
        return $texto;
    }

    public function enviarCorreoAprobacionTiendaVirtual($destinatario, $nombres, $apellidos, $usuario, $password) {
        $texto = '
            <p>Estimado(a) ' . $nombres . ' ' . str_replace("'", "", $apellidos) .
                '<br>
            <br></p>
            <h4>La Empresa Publica Municipal para la Gestion de Innovacion y Competitividad, EP te da la bienvenida para formar parte de algo EPICO.</h4>

            <p>Agradecemos tu registro.</p>

            <p>Te informamos que aproximadamente en 24 horas se te habilitara un usuario para formar parte de la tienda virtual Mercado593.</p>

            <p>Nos estaremos contactando contigo para validar la informacion que ingresaste en la plataforma.</p>
            <br>
            <p>¡Por un Guayaquil EPICO que se levanta!</p>
            <br>
            <br>
            <br><p style="color: red">*La tildes en el correo se han omitido intencionalmente</p>'
        ;
        $Subject = "Registro Emprendedor";
        return $this->enviarCorreo($destinatario, $texto, $Subject);
    }

    public function enviarCorreoAprobacionEmprendedor($destinatario, $nombres, $apellidos, $usuario, $password) {
        $Subject = "Registro Emprendedor";
        $archivos = array();
        $archivo = new stdClass();
        $archivo->url = "emprendimiento_1_certificado_bancario.pdf";
        $archivo->nombre = "Requerimientos.pdf";
        $archivos[0] = $archivo;

        $archivo = new stdClass();
        $archivo->url = "emprendimiento_1_productos.xlsx";
        $archivo->nombre = "Productos.xlsx";
        $archivos[1] = $archivo;

        $archivo = new stdClass();
        $archivo->url = ArchivosBO::crearZip("Mercado593/prueba.zip", $archivos);
        $archivo->nombre = "documentos.zip";

        return $this->enviarCorreoArchivo($destinatario, $archivo->url, $Subject, $archivo);
    }

    public function enviarCorreoRegistroServicioMercado593($destinatario, $nombres, $apellidos) {
        $texto = "
            <p>Estimado(a) $nombres $apellidos,
            <br>
            <br></p>

            <p>¡Hemos recibido tu formulario de inscripcion en la tienda virtual MERCADO593 guayaco! </p>

            <p>En las proximas 24 horas laborables estaremos validando la informacion ingresada en la plataforma y nos estaremos contactando contigo.</p>

            <p>¡Juntos construiremos un Guayaquil EPICO!</p>
            <br>
            <br>

            <br><p style='color: red'>*La tildes en el correo se han omitido intencionalmente</p>
        ";
        $Subject = "Registro Mercado 593";
        return $this->enviarCorreo($destinatario, $texto, $Subject);
    }

    public function getCorreoAprobacionServicioMercado593($nombres, $apellidos) {
        $texto = "<p>Estimado(a) $nombres $apellidos,
            <br>
            <br>

            <p>La Empresa Publica Municipal para la Gestion de Innovacion y Competitividad, EP te felicita, ya formas parte de la tienda virtual MERCADO593 guayaco.</p>
            <p>Te comunicamos que hemos validado la informacion y la documentacion ingresada. En las proximas 24 horas laborables se estaran contactando contigo, para habilitarte un usuario y contrasena, y comenzar a vender en la tienda virtual.</p>
            <p>Te invitamos a estar pendiente de nuestras redes sociales <b>@epicogye</b> para futuras capacitaciones y talleres que seran muy beneficiosos para fortalecer el modelo de negocio y ventas de tu emprendimiento. </p>
            <br>
            <br>
            <p>¡Juntos construiremos un Guayaquil EPICO!</p>
            <br>
            <br>

            <br><p style='color: red'>*La tildes en el correo se han omitido intencionalmente</p>"
        ;
        return $texto;
    }

    public function getCorreoRechazoServicioMercado593($nombres, $apellidos) {
        $texto = "<p>Estimado(a) $nombres $apellidos,
            <br>
            <br>

            <p>La Empresa Publica Municipal para la Gestion de la Innovacion y Competitividad, EP agradece tu participacion en la convocatoria de la tienda virtual MERCADO593 guayaco.</p>
             
            <p>Queremos informarte que, por el momento no cumples con los requisitos que necesitas para acceder a la plataforma de la tienda virtual. Para mas informacion puedes revisar los requerimientos basicos que se encuentran disponibles en <a href='https://epico.gob.ec/wp-content/uploads/2020/05/Requisitos-para-inscripcio%CC%81n-Mercado-593-Guayaco.pdf'>https://epico.gob.ec/wp-content/uploads/2020/05/Requisitos-para-inscripcio%CC%81n-Mercado-593-Guayaco.pdf</a></p>
             
            <p>Sin embargo, te invitamos a estar pendiente de nuestras redes sociales <a>@epicogye</a> para futuras capacitaciones y talleres que seran muy beneficiosos para fortalecer el modelo de negocio y ventas de tu emprendimiento. </p>
             
            <p>¡Juntos construiremos un Guayaquil EPICO!</p>
            <br>
            <br>

            <br><p style='color: red'>*La tildes en el correo se han omitido intencionalmente</p>"
        ;
        return $texto;
    }

    public function getCorreoASupervisor($nombres, $apellidos, $usuario) {
        $texto = "<p>El emprededor $nombres $apellidos se encuentra aprobado por $usuario->nombre $usuario->apellido para que proceda a revisarlo y confirme la aprobacion.</p>

            <br><p style='color: red'>*La tildes en el correo se han omitido intencionalmente</p>"
        ;
        return $texto;
    }

    public function getCorreoAMesaServicio($datos) {
        $texto = "<p>El emprededor $datos->nombre $datos->apellido acaba de completar la informacion del formulario 2</p>
            
            <p><b>Emprendedor:</b> $datos->nombre $datos->apellido</p>
            <p><b>Emprendimiento</b> $datos->nombre_emprendimiento</p>
            <p><b>Correo:</b> $datos->email</p>
            <p><b>Identificacion:</b> $datos->identificacion</p>
            <p><b>Ruc:</b> $datos->ruc</p>
            

            <br><p style='color: red'>*La tildes en el correo se han omitido intencionalmente</p>"
        ;
        return $texto;
    }

    public function getCorreoSolicitud($datos) {
        $texto = "<p>El emprededor $datos->nombre $datos->apellido acaba de realizar la solicitud de $datos->tipo</p>
            
            <p><b>Emprendedor:</b> $datos->nombre $datos->apellido</p>
            <p><b>Emprendimiento</b> $datos->nombre_emprendimiento</p>
            <p><b>Correo:</b> $datos->email</p>
            <p><b>Identificacion:</b> $datos->identificacion</p>
            <p><b>Ruc:</b> $datos->ruc</p>
            

            <br><p style='color: red'>*La tildes en el correo se han omitido intencionalmente</p>"
        ;
        return $texto;
    }

    public function getCorreoRechazoPorMesaAyuda($nombres, $apellidos, $tabla) {
        $texto = "<p>Estimado(a) $nombres $apellidos,</p>
            <br>
            <br>
            
            <p>La Empresa Publica Municipal para la Gestion de la Innovacion y Competitividad, EP agradece tu participacion en la convocatoria de la tienda virtual MERCADO593 guayaco.</p>
            
            <br>
            
            <p>Queremos informarte que al momento no cumples con los siguientes requimientos:</p>
            
            <p>
            $tabla
            </p>
            
            <p>Te habilitamos el registro para que puedas llenar la informacion faltante</p>
             
            <p>Queremos informarte que, por el momento no cumples con los requisitos que necesitas para acceder a la plataforma de la tienda virtual. Para mas informacion puedes revisar los requerimientos basicos que se encuentran disponibles en <a href='https://epico.gob.ec/wp-content/uploads/2020/05/Requisitos-para-inscripcio%CC%81n-Mercado-593-Guayaco.pdf'>https://epico.gob.ec/wp-content/uploads/2020/05/Requisitos-para-inscripcio%CC%81n-Mercado-593-Guayaco.pdf</a></p>

            <br>
            <p>Si tienes alguna inquietud no dudes en escribirnos al siguiente correo: <b>mesadeservicio@epico.gob.ec</b></p>
            
            <br>
            <br>
            <p>¡Juntos construiremos un Guayaquil EPICO!</p>
            <br>
            <br>

            <br><p style='color: red'>*La tildes en el correo se han omitido intencionalmente</p>"
        ;
        return $texto;
    }

    public function getCorreoAprobacionMercado593() {
        $html = "<p><b>¡Estas listo para comenzar a vender en Mercado 593 Guayaco!</b></p>
                <br>
                <br>

                <p>Hemos recibido tu informacion completa para el ingreso en la tienda virtual MERCADO593 Guayaco. ¡Te comunicamos que estas listo para vender tus productos!</p>
                <br>

                <p>En las proximas 8 horas laborables Contifico estara comunicandose contigo para guiarte en la activacion y funcionamiento de la plataforma Mercado593 Guayaco.</p>
                <br>
                <br>


                <p>¡Juntos construiremos un Guayaquil EPICO!</p>

                <b>RUC:</b> <<ruc>> <br>
                <b>Razon Social:</b> <<razon_social>> <br>
                <b>Nombre Comercial:</b> <<nombre_comercial>> <br>
                <b>Direccion:</b> <<direccion>> <br>
                <b>Tipo:</b> <<tipo>> <br>
                <br>
                <b>USUARIO</b><br>
                <b>Nombres:</b> <<nombre>> <br>
                <b>Apellidos:</b> <<apellido>> <br>
                <b>Cedula:</b> <<cedula>> <br>
                <b>Correo:</b> <<email>> <br>
                <b>Celular:</b> <<celular>> <br>
                <br>
                <b>SERVICIOS:</b><br>
                <b>Boton de pago:</b> <<boton_pago>> <br>
                <b>Whatsapp:</b> <<whatsapp>> <br>
                <b>Envios:</b> <<delivery>> <br>
                <b>Link documentos:</b> <<link>> <br>

                <br><br><br>
                <p style='color: red'>*La tildes en el correo se han omitido intencionalmente</p>";
        return $html;
    }

    public function getCorreoFase1() {
        $correo = new stdClass();
        $correo->asunto = "Bienvenido al programa DESCUBRIENDO";
        $correo->tipo = "Centro de emprendimiento: Fase1";
        $correo->texto_correo = '
                   <div style="background-color:#352d54;"><table bgcolor="#352d54" height="100%" width="100%" cellpadding="0" cellspacing="0" border="0"><tbody><tr><td valign="top" align="center" bgcolor="#352d54" background="" style="background-color:#352d54;background-image: url(\'\');background-position:top center;background-repeat:repeat;"><center class="wrapper" style="width:100%;table-layout:fixed;text-align:inherit;"><table cellpadding="0" cellspacing="0" border="0" width="100%"><tbody><tr><td><table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color: #352d54">
  <tbody><tr>
    <td style="background-color: #352d54">
      <!--[if (gte mso 9)|(IE)]> <table class="outlook-container " width="600" align="center" bgcolor="#FFFFFF" style="background-color:#FFFFFF;box-sizing:border-box;border-spacing:0;border-collapse:collapse;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;" > <tr><td width="100%" valign="top" align="center"> <![endif]-->
      <table class="wrapper--outer" align="center" style="box-sizing:border-box;border-spacing:0;border-collapse:collapse;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;width:100%;max-width:600px;margin-top:0;margin-bottom:0;margin-right:auto;margin-left:auto; background-color:#FFFFFF" bgcolor="#FFFFFF">
        <tbody><tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
          <td class="column--1 image" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;font-size:0;padding-top:10px;padding-bottom:10px;padding-right:10px;padding-left:10px;">
            <table width="100%" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;">
              <tbody><tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                <td class="wrapper--column image" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;padding-top:10px;padding-bottom:10px;padding-right:10px;padding-left:10px;">
                  <table class="wrapper--content" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;width:100%;">
                    <tbody><tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                      <td class="wrapper--inner" align="center" style="padding:0;line-height:0px;border-collapse:collapse !important;word-break:break-word;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;">
                        <img ondragstart="return false;" width="560" src="https://app2.dopplerfiles.com/Templates/221877/mail.jpg" alt="mail" style="clear:both;width:560px;max-width:100%;text-decoration:none;border-style:none;outline-style:none;-ms-interpolation-mode:bicubic;text-align:center;">
                      </td>
                    </tr>
                  </tbody></table>
                </td>
              </tr>
            </tbody></table>
          </td>
        </tr>
      </tbody></table>
      <!--[if (gte mso 9)|(IE)]> </td> </tr> </table> <![endif]-->
    </td>
  </tr>
</tbody></table>
</td></tr><tr><td><table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color: #352d54">
  <tbody><tr>
    <td style="background-color: #352d54">
      <!--[if (gte mso 9)|(IE)]> <table class="outlook-container " width="600" align="center" bgcolor="#FDB913" style="background-color:#FDB913;box-sizing:border-box;border-spacing:0;border-collapse:collapse;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;" > <tr><td width="100%" valign="top" align="center"> <![endif]-->
      <table class="wrapper--outer" align="center" style="box-sizing:border-box;border-spacing:0;border-collapse:collapse;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;width:100%;max-width:600px;margin-top:0;margin-bottom:0;margin-right:auto;margin-left:auto; background-color:#FDB913" bgcolor="#FDB913">
        <tbody><tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
          <td class="column--1" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;font-size:0;padding-top:10px;padding-bottom:10px;padding-right:10px;padding-left:10px;text-align:center;">
            <table width="100%" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;">
              <tbody><tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                <td class="wrapper--column" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;padding-top:10px;padding-bottom:10px;padding-right:10px;padding-left:10px;">
                  <table class="wrapper--content" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;width:100%;">
                    <tbody><tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                      <td align="center" class="wrapper--inner" style="padding:0;line-height:120%;font-size:12px;border-collapse:collapse !important;word-break:break-word;word-wrap:break-word; margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;">
                        <span style="display: block;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;line-height:normal;"><div style="text-align: center;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl">Hola <<nombre>></span></div><div style="text-align: center;"><br></div><div style="text-align: center;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 16px; line-height: 1.3;" class="font-line-height-xl"><b>¡Te estamos esperando para llevar tu emprendimiento a un nuevo nivel! </b></span></div><div style="text-align: center;"><br></div></span>
                      </td>
                    </tr>
                  </tbody></table>
                </td>
              </tr>
            </tbody></table>
          </td>
        </tr>
      </tbody></table>
      <!--[if (gte mso 9)|(IE)]> </td> </tr> </table> <![endif]-->
    </td>
  </tr>
</tbody></table>
</td></tr><tr><td><table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color: #352d54">
  <tbody><tr>
    <td style="background-color: #352d54">
      <!--[if (gte mso 9)|(IE)]> <table class="outlook-container " width="600" align="center" bgcolor="#F7F7F7" style="background-color:#F7F7F7;box-sizing:border-box;border-spacing:0;border-collapse:collapse;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;" > <tr><td width="100%" valign="top" align="center"> <![endif]-->
      <table class="wrapper--outer" align="center" style="box-sizing:border-box;border-spacing:0;border-collapse:collapse;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;width:100%;max-width:600px;margin-top:0;margin-bottom:0;margin-right:auto;margin-left:auto; background-color:#F7F7F7" bgcolor="#F7F7F7">
        <tbody><tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
          <td class="column--1" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;font-size:0;padding-top:10px;padding-bottom:10px;padding-right:10px;padding-left:10px;text-align:center;">
            <table width="100%" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;">
              <tbody><tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                <td class="wrapper--column" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;padding-top:10px;padding-bottom:10px;padding-right:10px;padding-left:10px;">
                  <table class="wrapper--content" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;width:100%;">
                    <tbody><tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                      <td align="left" class="wrapper--inner" style="padding:0;line-height:120%;font-size:12px;border-collapse:collapse !important;word-break:break-word;word-wrap:break-word; margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;">
                        <span style="display: block;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;line-height:normal;"><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl">Hoy es un día lleno de nuevas oportunidades, estás a punto de arrancar el plan de trabajo que hemos diseñado para ti y tu deseo de emprender.</span></div><div style="text-align: left;"><br></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl">Te invitamos a una <b>sesión de inducción</b> que te permitirá conocer tu plan de trabajo, las instalaciones y cómo utilizar nuestra plataforma de servicios para el emprendedor. &nbsp;Todo para facilitar tu transitar en la ruta del emprendimiento ÉPICO. </span></div><div style="text-align: left;"><br></div><div style="text-align: left;"><br></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl">Tendremos 2 horarios disponibles<b>:</b></span></div><div style="text-align: left;"><br></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl"><b>Lunes 17 de agosto 10H00 a 11H00 virtual</b></span></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl">https://docs.google.com/forms/d/e/1FAIpQLSfs-jeF8f59womCaSpY5HRLTPP6EGIeHeLyYU8YMZ_XHrflPw/viewform?usp=pp_url</span></div><div style="text-align: left;"><br></div><div style="text-align: left;"><br></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl"><b>Lunes 24 de agosto 16H00 a 18H00 presencial</b></span></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl">Registro máximo 30 personas </span></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl">https://docs.google.com/forms/d/e/1FAIpQLSfs-jeF8f59womCaSpY5HRLTPP6EGIeHeLyYU8YMZ_XHrflPw/viewform?usp=pp_url</span></div><div style="text-align: left;"><br></div><div style="text-align: left;">También te adjuntamos el usuario y contraseña para que puedas ingresar al sistema una vez que participes de la inducción:</div><div style="text-align: left;"><br></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl">Usuario: <<email>></span></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl">Password: <<password>></span></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl">Link: http://epico.gob.ec/centro_emprendimiento/login.php</span></div><div style="text-align: left;"><br></div><div style="text-align: left;"><br></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl">Si tienes alguna inquietud o duda adicional, escríbenos al siguiente correo: <i><b>mesadeservicio@epico.gob.ec</b></i> ¡Con gusto te atenderemos!</span></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl"><b>&nbsp;</b></span></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl"><b>&nbsp;</b></span></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl"><b>¡Construyamos juntos un Guayaquil ÉPICO!</b></span></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl"><b>&nbsp;</b></span></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl">Saludos cordiales,</span></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl"><b>El equipo ÉPICO</b></span></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl"><b>&nbsp;</b></span></div></span>
                      </td>
                    </tr>
                  </tbody></table>
                </td>
              </tr>
            </tbody></table>
          </td>
        </tr>
      </tbody></table>
      <!--[if (gte mso 9)|(IE)]> </td> </tr> </table> <![endif]-->
    </td>
  </tr>
</tbody></table>
</td></tr><tr><td><table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color: #352d54">
  <tbody><tr>
    <td style="background-color: #352d54">
      <!--[if (gte mso 9)|(IE)]> <table class="outlook-container " width="600" align="center" bgcolor="#FFFFFF" style="background-color:#FFFFFF;box-sizing:border-box;border-spacing:0;border-collapse:collapse;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;" > <tr><td width="100%" align="center" valign="top"> <![endif]-->
      <table class="wrapper--outer" align="center" style="box-sizing:border-box;border-spacing:0;border-collapse:collapse;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;width:100%;max-width:600px;margin-top:0;margin-bottom:0;margin-right:auto;margin-left:auto; background-color:#FFFFFF" bgcolor="#FFFFFF">
        <tbody><tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
          <td class="column--1" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;font-size:0;padding-top:10px;padding-bottom:10px;padding-right:10px;padding-left:10px;text-align:center;">
            <table width="100%" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;">
              <tbody><tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                <td class="wrapper--column" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;padding-top:5px;padding-bottom:10px;padding-right:10px;padding-left:10px;">
                  <table class="wrapper--content" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;width:100%;">
                    <tbody><tr style="vertical-align: top">
                      <td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;padding: 0;" align="center">
                        <table class="table_center" border="0" cellpadding="0" cellspacing="0" style="text-align: center">
                          <tbody><tr>
                            <td style="display: inline-block; padding-right: 5px; padding-top: 5px; line-height: 0px;" valign="middle">
                              <a ondragstart="return false;" href="http://facebook.com/https://es-la.facebook.com/centroemprendimientogye/" target="_blank">
                                <img ondragstart="return false;" width="32" src="https://app2.dopplerfiles.com/MSEditor/images/color_rounded_facebook.png" alt="Facebook">
                              </a>
                            </td>
                            <td style="display: inline-block; padding-right: 5px; padding-top: 5px; line-height: 0px;" valign="middle">
                              <a ondragstart="return false;" href="http://instagram.com/https://www.instagram.com/epicogye/" target="_blank">
                                <img ondragstart="return false;" width="32" src="https://app2.dopplerfiles.com/MSEditor/images/color_rounded_instagram.png" alt="Instagram">
                              </a>
                            </td>
                            <td style="display: inline-block; padding-right: 0px; padding-top: 5px; line-height: 0px;" valign="middle">
                              <a ondragstart="return false;" href="http://linkedin.com/https://www.linkedin.com/organization-guest/company/epicogye?challengeId=AQE_o3qKvYZJlQAAAXNt80cH7Tw4HJGFvMoxs01VyGqWeI7tVlIGsgP1YYNDnmSdw4EWzEs5m0wWVFv_rOCVTx86x8asyc1gFQ&amp;submissionId=35e2cc50-7590-2316-ee4f-d015707dcc45" target="_blank">
                                <img ondragstart="return false;" width="32" src="https://app2.dopplerfiles.com/MSEditor/images/color_rounded_linkedin.png" alt="Linkedin">
                              </a>
                            </td>
                            
                            
                            
                            
                            
                            
                            
                          </tr>
                        </tbody></table>
                      </td>
                    </tr>
                  </tbody></table>
                </td>
              </tr>
            </tbody></table>
          </td>
        </tr>
      </tbody></table>
      <!--[if (gte mso 9)|(IE)]> </td> </tr> </table> <![endif]-->
    </td>
  </tr>
</tbody></table>
</td></tr><tr><td><table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color: #352d54">
  <tbody><tr>
    <td style="background-color: #352d54">
      <!--[if (gte mso 9)|(IE)]> <table class="outlook-container " width="600" align="center" bgcolor="#ffffff" style="background-color:#ffffff;box-sizing:border-box;border-spacing:0;border-collapse:collapse;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;" > <tr><td width="100%" valign="top" align="center"> <![endif]-->
      <table class="wrapper--outer" align="center" style="box-sizing:border-box;border-spacing:0;border-collapse:collapse;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;width:100%;max-width:600px;margin-top:0;margin-bottom:0;margin-right:auto;margin-left:auto; background-color:#ffffff" bgcolor="#ffffff">
        <tbody><tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
          <td class="column--1" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;font-size:0;padding-top:5px;padding-bottom:5px;padding-right:10px;padding-left:10px;text-align:center;">
            <table width="100%" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;">
              <tbody><tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                <td class="wrapper--column" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;padding-top:5px;padding-bottom:5px;padding-right:10px;padding-left:10px;">
                  <table class="wrapper--content" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;width:100%;">
                    <tbody><tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                      <td align="center" class="wrapper--inner" style="padding:0;line-height:120%;font-size:12px;border-collapse:collapse !important;word-break:break-word;word-wrap:break-word; margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;">
                        <span style="display: block;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;line-height:normal;"><div style="text-align: center;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif;"><b>@epicogye @empredimiento_epicogye</b></span></div></span>
                      </td>
                    </tr>
                  </tbody></table>
                </td>
              </tr>
            </tbody></table>
          </td>
        </tr>
      </tbody></table>
      <!--[if (gte mso 9)|(IE)]> </td> </tr> </table> <![endif]-->
    </td>
  </tr>
</tbody></table>
</td></tr></tbody></table></center></td></tr></tbody></table></div>
                ';
        return $correo;
    }

    public function getCorreoFase2() {
        $correo = new stdClass();
        $correo->asunto = "Te damos la Bienvenida al programa (RE) CREANDO";
        $correo->tipo = "Centro de emprendimiento: Fase2";
        $correo->texto_correo = '
                    <div style="background-color:#352d54;"><table bgcolor="#352d54" height="100%" width="100%" cellpadding="0" cellspacing="0" border="0"><tbody><tr><td valign="top" align="center" bgcolor="#352d54" background="" style="background-color:#352d54;background-image: url(\'\');background-position:top center;background-repeat:repeat;"><center class="wrapper" style="width:100%;table-layout:fixed;text-align:inherit;"><table cellpadding="0" cellspacing="0" border="0" width="100%"><tbody><tr><td><table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color: #352d54">
  <tbody><tr>
    <td style="background-color: #352d54">
      <!--[if (gte mso 9)|(IE)]> <table class="outlook-container " width="600" align="center" bgcolor="#FFFFFF" style="background-color:#FFFFFF;box-sizing:border-box;border-spacing:0;border-collapse:collapse;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;" > <tr><td width="100%" valign="top" align="center"> <![endif]-->
      <table class="wrapper--outer" align="center" style="box-sizing:border-box;border-spacing:0;border-collapse:collapse;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;width:100%;max-width:600px;margin-top:0;margin-bottom:0;margin-right:auto;margin-left:auto; background-color:#FFFFFF" bgcolor="#FFFFFF">
        <tbody><tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
          <td class="column--1 image" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;font-size:0;padding-top:10px;padding-bottom:10px;padding-right:10px;padding-left:10px;">
            <table width="100%" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;">
              <tbody><tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                <td class="wrapper--column image" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;padding-top:10px;padding-bottom:10px;padding-right:10px;padding-left:10px;">
                  <table class="wrapper--content" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;width:100%;">
                    <tbody><tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                      <td class="wrapper--inner" align="center" style="padding:0;line-height:0px;border-collapse:collapse !important;word-break:break-word;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;">
                        <img ondragstart="return false;" width="560" src="https://app2.dopplerfiles.com/Templates/221877/mail.jpg" alt="mail" style="clear:both;width:560px;max-width:100%;text-decoration:none;border-style:none;outline-style:none;-ms-interpolation-mode:bicubic;text-align:center;">
                      </td>
                    </tr>
                  </tbody></table>
                </td>
              </tr>
            </tbody></table>
          </td>
        </tr>
      </tbody></table>
      <!--[if (gte mso 9)|(IE)]> </td> </tr> </table> <![endif]-->
    </td>
  </tr>
</tbody></table>
</td></tr><tr><td><table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color: #352d54">
  <tbody><tr>
    <td style="background-color: #352d54">
      <!--[if (gte mso 9)|(IE)]> <table class="outlook-container " width="600" align="center" bgcolor="#FDB913" style="background-color:#FDB913;box-sizing:border-box;border-spacing:0;border-collapse:collapse;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;" > <tr><td width="100%" valign="top" align="center"> <![endif]-->
      <table class="wrapper--outer" align="center" style="box-sizing:border-box;border-spacing:0;border-collapse:collapse;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;width:100%;max-width:600px;margin-top:0;margin-bottom:0;margin-right:auto;margin-left:auto; background-color:#FDB913" bgcolor="#FDB913">
        <tbody><tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
          <td class="column--1" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;font-size:0;padding-top:10px;padding-bottom:10px;padding-right:10px;padding-left:10px;text-align:center;">
            <table width="100%" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;">
              <tbody><tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                <td class="wrapper--column" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;padding-top:10px;padding-bottom:10px;padding-right:10px;padding-left:10px;">
                  <table class="wrapper--content" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;width:100%;">
                    <tbody><tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                      <td align="center" class="wrapper--inner" style="padding:0;line-height:120%;font-size:12px;border-collapse:collapse !important;word-break:break-word;word-wrap:break-word; margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;">
                        <span style="display: block;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;line-height:normal;"><div style="text-align: center;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl">Hola <<nombre>></span></div><div style="text-align: center;"><br></div><div style="text-align: center;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 16px; line-height: 1.3;" class="font-line-height-xl"><b>¡Te estamos esperando para llevar tu emprendimiento a un nuevo nivel! </b></span></div><div style="text-align: center;"><br></div></span>
                      </td>
                    </tr>
                  </tbody></table>
                </td>
              </tr>
            </tbody></table>
          </td>
        </tr>
      </tbody></table>
      <!--[if (gte mso 9)|(IE)]> </td> </tr> </table> <![endif]-->
    </td>
  </tr>
</tbody></table>
</td></tr><tr><td><table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color: #352d54">
  <tbody><tr>
    <td style="background-color: #352d54">
      <!--[if (gte mso 9)|(IE)]> <table class="outlook-container " width="600" align="center" bgcolor="#F7F7F7" style="background-color:#F7F7F7;box-sizing:border-box;border-spacing:0;border-collapse:collapse;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;" > <tr><td width="100%" valign="top" align="center"> <![endif]-->
      <table class="wrapper--outer" align="center" style="box-sizing:border-box;border-spacing:0;border-collapse:collapse;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;width:100%;max-width:600px;margin-top:0;margin-bottom:0;margin-right:auto;margin-left:auto; background-color:#F7F7F7" bgcolor="#F7F7F7">
        <tbody><tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
          <td class="column--1" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;font-size:0;padding-top:10px;padding-bottom:10px;padding-right:10px;padding-left:10px;text-align:center;">
            <table width="100%" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;">
              <tbody><tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                <td class="wrapper--column" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;padding-top:10px;padding-bottom:10px;padding-right:10px;padding-left:10px;">
                  <table class="wrapper--content" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;width:100%;">
                    <tbody><tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                      <td align="left" class="wrapper--inner" style="padding:0;line-height:120%;font-size:12px;border-collapse:collapse !important;word-break:break-word;word-wrap:break-word; margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;">
                        <span style="display: block;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;line-height:normal;"><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl">Hoy es un día lleno de nuevas oportunidades, estás a punto de arrancar el plan de trabajo que hemos diseñado para ti y tu deseo de emprender.</span></div><div style="text-align: left;"><br></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl">Te invitamos a una <b>sesión de inducción</b> que te permitirá conocer tu plan de trabajo, las instalaciones y cómo utilizar nuestra plataforma de servicios para el emprendedor. &nbsp;Todo para facilitar tu transitar en la ruta del emprendimiento ÉPICO. </span></div><div style="text-align: left;"><br></div><div style="text-align: left;"><br></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl">Tendremos 2 horarios disponibles<b>:</b></span></div><div style="text-align: left;"><br></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl"><b>Lunes 17 de agosto 10H00 a 11H00 virtual</b></span></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl">https://docs.google.com/forms/d/e/1FAIpQLSfs-jeF8f59womCaSpY5HRLTPP6EGIeHeLyYU8YMZ_XHrflPw/viewform?usp=pp_url</span></div><div style="text-align: left;"><br></div><div style="text-align: left;"><br></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl"><b>Lunes 24 de agosto 16H00 a 18H00 presencial</b></span></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl">Registro máximo 30 personas </span></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl">https://docs.google.com/forms/d/e/1FAIpQLSfs-jeF8f59womCaSpY5HRLTPP6EGIeHeLyYU8YMZ_XHrflPw/viewform?usp=pp_url</span></div><div style="text-align: left;"><br></div><div style="text-align: left;">También te adjuntamos el usuario y contraseña para que puedas ingresar al sistema una vez que participes de la inducción:</div><div style="text-align: left;"><br></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl">Usuario: <<email>></span></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl">Password: <<password>></span></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl">Link: http://epico.gob.ec/centro_emprendimiento/login.php</span></div><div style="text-align: left;"><br></div><div style="text-align: left;"><br></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl">Si tienes alguna inquietud o duda adicional, escríbenos al siguiente correo: <i><b>mesadeservicio@epico.gob.ec</b></i> ¡Con gusto te atenderemos!</span></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl"><b>&nbsp;</b></span></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl"><b>&nbsp;</b></span></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl"><b>¡Construyamos juntos un Guayaquil ÉPICO!</b></span></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl"><b>&nbsp;</b></span></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl">Saludos cordiales,</span></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl"><b>El equipo ÉPICO</b></span></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl"><b>&nbsp;</b></span></div></span>
                      </td>
                    </tr>
                  </tbody></table>
                </td>
              </tr>
            </tbody></table>
          </td>
        </tr>
      </tbody></table>
      <!--[if (gte mso 9)|(IE)]> </td> </tr> </table> <![endif]-->
    </td>
  </tr>
</tbody></table>
</td></tr><tr><td><table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color: #352d54">
  <tbody><tr>
    <td style="background-color: #352d54">
      <!--[if (gte mso 9)|(IE)]> <table class="outlook-container " width="600" align="center" bgcolor="#FFFFFF" style="background-color:#FFFFFF;box-sizing:border-box;border-spacing:0;border-collapse:collapse;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;" > <tr><td width="100%" align="center" valign="top"> <![endif]-->
      <table class="wrapper--outer" align="center" style="box-sizing:border-box;border-spacing:0;border-collapse:collapse;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;width:100%;max-width:600px;margin-top:0;margin-bottom:0;margin-right:auto;margin-left:auto; background-color:#FFFFFF" bgcolor="#FFFFFF">
        <tbody><tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
          <td class="column--1" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;font-size:0;padding-top:10px;padding-bottom:10px;padding-right:10px;padding-left:10px;text-align:center;">
            <table width="100%" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;">
              <tbody><tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                <td class="wrapper--column" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;padding-top:5px;padding-bottom:10px;padding-right:10px;padding-left:10px;">
                  <table class="wrapper--content" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;width:100%;">
                    <tbody><tr style="vertical-align: top">
                      <td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;padding: 0;" align="center">
                        <table class="table_center" border="0" cellpadding="0" cellspacing="0" style="text-align: center">
                          <tbody><tr>
                            <td style="display: inline-block; padding-right: 5px; padding-top: 5px; line-height: 0px;" valign="middle">
                              <a ondragstart="return false;" href="http://facebook.com/https://es-la.facebook.com/centroemprendimientogye/" target="_blank">
                                <img ondragstart="return false;" width="32" src="https://app2.dopplerfiles.com/MSEditor/images/color_rounded_facebook.png" alt="Facebook">
                              </a>
                            </td>
                            <td style="display: inline-block; padding-right: 5px; padding-top: 5px; line-height: 0px;" valign="middle">
                              <a ondragstart="return false;" href="http://instagram.com/https://www.instagram.com/epicogye/" target="_blank">
                                <img ondragstart="return false;" width="32" src="https://app2.dopplerfiles.com/MSEditor/images/color_rounded_instagram.png" alt="Instagram">
                              </a>
                            </td>
                            <td style="display: inline-block; padding-right: 0px; padding-top: 5px; line-height: 0px;" valign="middle">
                              <a ondragstart="return false;" href="http://linkedin.com/https://www.linkedin.com/organization-guest/company/epicogye?challengeId=AQE_o3qKvYZJlQAAAXNt80cH7Tw4HJGFvMoxs01VyGqWeI7tVlIGsgP1YYNDnmSdw4EWzEs5m0wWVFv_rOCVTx86x8asyc1gFQ&amp;submissionId=35e2cc50-7590-2316-ee4f-d015707dcc45" target="_blank">
                                <img ondragstart="return false;" width="32" src="https://app2.dopplerfiles.com/MSEditor/images/color_rounded_linkedin.png" alt="Linkedin">
                              </a>
                            </td>
                            
                            
                            
                            
                            
                            
                            
                          </tr>
                        </tbody></table>
                      </td>
                    </tr>
                  </tbody></table>
                </td>
              </tr>
            </tbody></table>
          </td>
        </tr>
      </tbody></table>
      <!--[if (gte mso 9)|(IE)]> </td> </tr> </table> <![endif]-->
    </td>
  </tr>
</tbody></table>
</td></tr><tr><td><table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color: #352d54">
  <tbody><tr>
    <td style="background-color: #352d54">
      <!--[if (gte mso 9)|(IE)]> <table class="outlook-container " width="600" align="center" bgcolor="#ffffff" style="background-color:#ffffff;box-sizing:border-box;border-spacing:0;border-collapse:collapse;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;" > <tr><td width="100%" valign="top" align="center"> <![endif]-->
      <table class="wrapper--outer" align="center" style="box-sizing:border-box;border-spacing:0;border-collapse:collapse;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;width:100%;max-width:600px;margin-top:0;margin-bottom:0;margin-right:auto;margin-left:auto; background-color:#ffffff" bgcolor="#ffffff">
        <tbody><tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
          <td class="column--1" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;font-size:0;padding-top:5px;padding-bottom:5px;padding-right:10px;padding-left:10px;text-align:center;">
            <table width="100%" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;">
              <tbody><tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                <td class="wrapper--column" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;padding-top:5px;padding-bottom:5px;padding-right:10px;padding-left:10px;">
                  <table class="wrapper--content" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;width:100%;">
                    <tbody><tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                      <td align="center" class="wrapper--inner" style="padding:0;line-height:120%;font-size:12px;border-collapse:collapse !important;word-break:break-word;word-wrap:break-word; margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;">
                        <span style="display: block;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;line-height:normal;"><div style="text-align: center;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif;"><b>@epicogye @empredimiento_epicogye</b></span></div></span>
                      </td>
                    </tr>
                  </tbody></table>
                </td>
              </tr>
            </tbody></table>
          </td>
        </tr>
      </tbody></table>
      <!--[if (gte mso 9)|(IE)]> </td> </tr> </table> <![endif]-->
    </td>
  </tr>
</tbody></table>
</td></tr></tbody></table></center></td></tr></tbody></table></div>
                ';
        return $correo;
    }

    public function getCorreoCEMentoria() {
        $correo = new stdClass();
        $correo->asunto = "Mentor nuevo registrado";
        $correo->tipo = "CORREO MENTORIA CE";
        $correo->texto_correo = "
            datos: <<json>> <br>
            cv: <a href='<<link>>'>link</a><br>
            ";
        return $correo;
    }

    public function getCorreoEmprendimientoInnovador() {
        $correo = new stdClass();
        $correo->asunto = "Centro de emprendimiento";
        $correo->tipo = "Centro de emprendimiento: Proyecto Innovador";
        $correo->texto_correo = "
            <p><b>¡Epico te da la bienvenida al Centro de Emprendimiento de Guayaquil!</b></p>
            <br>
            
            <p>Segun el status de tu emprendimiento es necesario validar la informacion para brindarte los cursos necesarios que puedan ayudarte en tu emprendimiento.</p>
            <p>En 24 horas un asesor se comunicara contigo</p>
            <br>
            <p>En el caso que tengas una duda especifica podemos atenderte en el correo <a href='mesadeservicio@epico.gob.ec'>mesadeservicio@epico.gob.ec</a></p>
            <br>
            <p><b>¡Por un Guayaquil EPICO!</b></p>
            <br><br><br>
            <p style='color: red'>*La tildes en el correo se han omitido intencionalmente</p>
            ";
        return $correo;
    }

    public function getCorreoCreacionUsuario($nombres, $apellidos, $usuario, $password) {
        $correo = new stdClass();
        $correo->asunto = "Creacion de usuario";
        $correo->tipo = "Creacion de usuario";
        $correo->texto_correo = "<p>Estimado(a) " . $nombres . " " . str_replace("'", "", $apellidos) .
                "
            <p><b>¡Epico te da la bienvenida al Centro de Emprendimiento de Guayaquil!</b></p>
            <br>
            <p></p>
            <p>Ahora dispones de un usuario y contrasena con el cual podras acceder a nuestros servcios: </p>

            <br>
            Usuario: <b>$usuario</b> <br>
            Password: <b>$password</b> <br>
            link: <b> <a href='https://epico.gob.ec/ce_desarrollo/login.php' target='_blank'>Sistema Epico</a>
            <br>

            <br>
            <p>En el caso que tengas una duda especifica podemos atenderte en el correo <a href='mesadeservicio@epico.gob.ec'>mesadeservicio@epico.gob.ec</a></p>
            <br>
            <p><b>¡Por un Guayaquil EPICO!</b></p>
            <br><br><br>
            <p style='color: red'>*La tildes en el correo se han omitido intencionalmente</p>
            ";
        return $correo;
    }

    public function getCorreoRegistroMentor() {
        $correo = new stdClass();
        $correo->asunto = "Gracias por registrarte!";
        $correo->tipo = "REGISTRO MENTOR";
        $correo->texto_correo = '<table border="0" align="center" width="100%" cellpadding="0" cellspacing="0" bgcolor="#2724a0" style="background-color:rgb(39,36,160)">

    <tbody><tr>
        <td align="center" valign="top">
        
            <table border="0" cellpadding="0" cellspacing="0" width="590" style="max-width:590px!important;width:590px">
        <tbody><tr>

        <td align="center" valign="top">

            <table width="100%" cellpadding="0" border="0" cellspacing="0" style="min-width:590px" name="Layout_0" id="m_7786664943076552646m_-4707227785843609320Layout_0">
                <tbody><tr>
                    <td valign="top" align="center" style="min-width:590px">
                        <a href="#m_7786664943076552646_m_-4707227785843609320_" name="m_7786664943076552646_m_-4707227785843609320_Layout_0"></a>
                        <table width="100%" cellpadding="0" border="0" height="38" cellspacing="0">
                            <tbody><tr>
                                <td valign="top" height="38">
                                    <img width="20" height="38" style="display:block;max-height:38px;max-width:20px" alt="" src="https://ci4.googleusercontent.com/proxy/PUuB2b9c3GQJ7tFLr9Yd7Z79jE5qsYWTxl7LiBWrcOqWz6zps66FddsUPw7YeDbYtjEjGU2oGlKPkG-6M-zpJl36sH3UwPi97fcj7PtumTV8ce0U97kXGHz0PF7R1woHnZY_MBEkI2U_5UkuTLgkYUFX5y7cJAw68y1iky3p_tvNoRnFP4VcaWiQZnoHSw--5e00WuQjhap0OLVYcnh_eZiIDwyANrUYF9R1cCzPmPrDdAuFMER-r1FwJ0LuODuFd88-0eYs8Bsg-Conyf2-59miwEXOBXZZc_wxHAosXD0As91Ydbt-MrdFTH1-qN_LtoA2MgxOvljH1BFjiwyyXKxR8aifGdlAXUEegdjGl_VZjpsu_oEdQFUMdU9GKfEIk1uPD7ZyVC3OeIhyvWK3ugIZ7I6RtETITQFkIrINez2hWvc0LQ=s0-d-e1-ft#http://4xwtx.img.bh.d.sendibt3.com/im/2943759/15fd9f264001efa0668072cabf04073d203e1c628b776e87506daf3661b832d6.gif?e=BYFbh9Vg7NSlFXzYYBAebESfmfChBrGG0DEgMnGmfWshduuQOhOtTfioM8HPGrWm8wl116K3uMVBa5DfzcRzhG8_pSDo8JUd2OUytOfvSCRkfAgSJ_mnGfzavO9N8APcwgw10UX3OExqAHuAIC7V-vqk86FrPw4OLdD4Tu55lfl35zogyniKfomC1hqG" class="CToWUd">
                                </td>
                            </tr>
                        </tbody></table>
                    </td>
                </tr>
            </tbody></table>
            </td>
    </tr><tr>

        <td align="center" valign="top">

            <div style="background-color:rgb(255,255,255);border-radius:0px">
                
                
                
                
                <table width="100%" cellpadding="0" border="0" cellspacing="0" style="min-width:590px" name="Layout_1" id="m_7786664943076552646m_-4707227785843609320Layout_1">
                <tbody><tr>
                    <td align="center" valign="top" style="min-width:590px">
                        <a href="#m_7786664943076552646_m_-4707227785843609320_" name="m_7786664943076552646_m_-4707227785843609320_Layout_1"></a>
                        <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#ffffff" style="background-color:rgb(255,255,255);border-radius:0px;padding-left:20px;padding-right:20px;border-collapse:separate">
                            <tbody><tr>
                                <td height="20" style="font-size:1px;line-height:0px">&nbsp;</td>
                            </tr>
                            <tr>
                                <td valign="top" align="left">
                                    <table width="100%" cellpadding="0" border="0" align="center" cellspacing="0">
                                        <tbody><tr>
                                            <td valign="top" align="center">
                                                <table cellpadding="0" border="0" align="center" cellspacing="0"> 
                                                    <tbody><tr>
                                                        <td valign="middle" align="center" style="line-height:1px">
                                                            <div style="border-top:0px None #000;border-right:0px None #000;border-bottom:0px None #000;border-left:0px None #000;display:inline-block" cellspacing="0" cellpadding="0" border="0"><div><img width="529" vspace="0" hspace="0" border="0" alt="ÉPICO" style="float:left;max-width:529px;display:block" src="https://ci6.googleusercontent.com/proxy/MZ6v0hlr972cPSbCxoBqrgRY5mSPPcf5G3RpqP7sXWmCIGjkyjmDvcpBPgXbToM3f_JM9BYzqOAhtbdtD1kWmEZ6Iq_0d_fDosZgq295dtV7ed_PU0shIJ5gSTEnIxY7e90klmHkwA6voKr1bm0HtSyxySR6An2oXFeaM1aRLc0LEzhIrULcR-61GfjPrjC2sTBEDn5vLhUiKXUiz4nOZyPeVazg9e2m1iHnMwphA-LBcBc8a5SOIjfVU-TfB5ldEdvo5VHFe83k0bjTfbIsAUdaBJFslnsjhC7eFdA-jEF94Bij7NEFpv-ykVPmx6LHoX2mH-_ogzaxF4rwoYSFcP6FFYsOAV7FDoB_9xMYVuMRQDbLra8F8QRENXvkyB16QWGdTEeJFS1kyVOd58AFlgVVJj7Iu38iLf0uPTXvYcvXqXb1S9UikWOQeEoXk2RrZ07p1TnqlrVIJynkockxqSwrLO4ITaPfLpBu1jsw_Dg=s0-d-e1-ft#http://4xwtx.img.bh.d.sendibt3.com/im/2943759/2bced385a789c43d12f5c1c4135ea44c0a2a6d2713a9c5c7197acb4d37a024a8.png?e=xDBgFi2kHSbVEZH5dspmKDPmweF6UKNZh3BeTawJJV-WKrAMHRTjBM10u-tdAtHTnwttfaTUHcv9O1BBMs93dsntCsc6Rx2IbSD3QfU5TsX-OluYcMDY4VI1CCqTGEQZg-S2RAUUKUhGKpP_VQCVctW-rM3oxzbX6IrUkLbnAE29dBlsl_0Bf3Z38mQDiMQFvxm9XQBbLw7GnPxJiMivKG2R6SgjWXf6i8iTMkA" class="CToWUd a6T" tabindex="0"><div class="a6S" dir="ltr" style="opacity: 0.01; left: 749.324px; top: 488.455px;"><div id=":4vl" class="T-I J-J5-Ji aQv T-I-ax7 L3 a5q" role="button" tabindex="0" aria-label="Descargar el archivo adjunto " data-tooltip-class="a1V" data-tooltip="Descargar"><div class="aSK J-J5-Ji aYr"></div></div></div></div></div></td>
                                                    </tr>
                                                </tbody></table>
                                                </td>
                                        </tr>
                                    </tbody></table></td>
                            </tr>
                            <tr>
                                <td height="20" style="font-size:1px;line-height:0px">&nbsp;</td>
                            </tr>
                        </tbody></table>
                    </td>
                </tr>
            </tbody></table>
            
                
                
            
        </div></td>
    </tr><tr>

        <td align="center" valign="top">

            <div style="background-color:rgb(255,255,255);border-radius:0px">
                
                
                
                
            
                <table width="100%" cellpadding="0" border="0" cellspacing="0" name="Layout_10" id="m_7786664943076552646m_-4707227785843609320Layout_10"><tbody><tr>
                    <td align="center" valign="top"><a href="#m_7786664943076552646_m_-4707227785843609320_" name="m_7786664943076552646_m_-4707227785843609320_Layout_10"></a>
                        <table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="#ffffff" style="height:0px;background-color:rgb(255,255,255);border-radius:0px;border-collapse:separate;padding-left:20px;padding-right:20px"><tbody><tr>
                                <td>

                                    <table border="0" cellpadding="0" cellspacing="0" align="left" style="margin:auto">
                                        <tbody><tr>

                                            <th align="left" style="text-align:center;font-weight:normal">

                                                <table border="0" cellspacing="0" cellpadding="0" align="center">

                                                    <tbody><tr>
                                                        <td height="10"></td>
                                                    </tr>

                                                    <tr>
                                                        <td style="font-family:Arial,Helvetica,sans-serif;color:#3c4858;text-align:left">

                                                            <span style="color:#3c4858"><span style="font-size:19px"><strong>Estimado(a) <<nombre>> </strong></span></span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td height="10"></td>
                                                    </tr>
                                                    </tbody></table>
                                                </th></tr>
                                    </tbody></table></td>
                            </tr>

                        </tbody></table>

                    </td>
                </tr>

            </tbody></table>
                
                
            
        </div></td>
    </tr><tr>

        <td align="center" valign="top">

            <div style="background-color:rgb(255,255,255);border-radius:0px">
            
                
                
                
                <table width="100%" cellpadding="0" border="0" cellspacing="0" style="min-width:100%" name="Layout_5">
                <tbody><tr>
                    <td align="center" valign="top">
                        <a href="#m_7786664943076552646_m_-4707227785843609320_" name="m_7786664943076552646_m_-4707227785843609320_Layout_5"></a>
                        <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#ffffff" style="background-color:rgb(255,255,255);padding-left:20px;padding-right:20px;border-collapse:separate;border-radius:0px;border-bottom:0px none rgb(200,200,200)">

                                        <tbody><tr>
                                            <td height="20" style="font-size:1px;line-height:0px">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td valign="top" align="left">

                                                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                                    <tbody><tr>
                                                        <th style="text-align:left;font-weight:normal;padding-right:0px" valign="top">

                                                            <table border="0" valign="top" cellspacing="0" cellpadding="0" width="100%" align="left">

                                                                <tbody><tr>
                                                                    <td style="font-size:14px;font-family:Arial,Helvetica,sans-serif,sans-serif;color:#3c4858;line-height:21px"><div>
<div>¡Su registro ha sido recibido!</div>

<div>&nbsp;</div>

<div>La Empresa Pública Municipal para la Gestión de la Innovación y Competitividad, EP, agradece su interés en formar parte de la RED DE MENTORES ÉPICOS.</div>

<div>&nbsp;</div>

<div>Nos estaremos contactando con usted para validar la información ingresada en la plataforma.</div>

<div>&nbsp;</div>

<div>¡Juntos construiremos un Guayaquil ÉPICO!</div>

<div>&nbsp;</div>

<div><span style="background-color:transparent">Saludos cordiales,</span></div>
</div>

<div>Centro de Emprendimiento de Guayaquil</div>
</td>
                                                                </tr>
                                                                </tbody></table>

                                                            </th></tr>
                                                </tbody></table></td>
                                        </tr>
                                        <tr>
                                            <td height="20" style="font-size:1px;line-height:0px">&nbsp;</td>
                                        </tr>
                                    </tbody></table>
                    </td>
                </tr>
            </tbody></table>
                
                

            </div></td>
    </tr><tr>

        <td align="center" valign="top">

            <table width="100%" cellpadding="0" border="0" cellspacing="0" style="min-width:590px" name="Layout_" id="m_7786664943076552646m_-4707227785843609320Layout_">
                <tbody><tr>
                    <td valign="top" align="center" style="min-width:590px">
                        <a href="#m_7786664943076552646_m_-4707227785843609320_" name="m_7786664943076552646_m_-4707227785843609320_Layout_"></a>
                        <table width="100%" cellpadding="0" border="0" height="30" cellspacing="0">
                            <tbody><tr>
                                <td valign="top" height="30">
                                    <img width="20" height="30" style="display:block;max-height:30px;max-width:20px" alt="" src="https://ci6.googleusercontent.com/proxy/BRnz2ig6oInH8t7KQ8vMhJUAFp2nnOc-yp-pRjNjy-YQVJ3p4gMTirlisRbq-rs1je0d12M4CXuQb3goY_FgXnu8CmpAyjJX9AgLA733eayiHDkC8Z1d0wH4Zl-kktvtxN4FyqecftZI1FQH54RpowY-Q3qomrK03XpPwJmLecNNeaS-KSZ4_5A4_pI_akgIzTEcSriecFZ-FsntXPVxq6OaYoAykTvZr8GZvtijWVK6Ytew80sljoYTU66MiKTLsN57RU0rwihM5VzFNQx3T89L_BxMBJ4GaiDL6tTNc1WZh850Ho-yZ14Y6G1dWwHf8nBgBxn4Ey7glOJGUgcVZ0VL2sRLEZgygxV0KQkoIZhTsQXv5xCQEW2HMSI3yLMFvNSQYLi8D4eHHvnSjFRKijj9m-eMxMg4izM7901EtN8lYb_o-w=s0-d-e1-ft#http://4xwtx.img.bh.d.sendibt3.com/im/2943759/15fd9f264001efa0668072cabf04073d203e1c628b776e87506daf3661b832d6.gif?e=pEASqWh7WzXLp8h1LRHk9sP1cqe_pp81-xty2pirf2cdR__27KEjXXWivIzPqhPDuwThKfaHkkXciauX42c95ilMnvibw6IclVXOibE5Zy3Dj4sIUGnshd1Kh3toX-6JCgKYUJw-S3SIEP41A2BUYf9mj7ccBM28BmZhsrSaPCD071JWUvg7SpYdGPgv" class="CToWUd">
                                </td>
                            </tr>
                        </tbody></table>
                    </td>
                </tr>
            </tbody></table>
            </td>
    </tr><tr>

        <td align="center" valign="top">

            <div style="background-color:rgb(255,255,255)">
                
                
                
                
                <table width="100%" cellpadding="0" border="0" cellspacing="0" style="min-width:100%;line-height:10px" name="Layout_8" id="m_7786664943076552646m_-4707227785843609320Layout_8">
                <tbody><tr>
                    <td valign="top" align="center" style="min-width:590px">
                        <a href="#m_7786664943076552646_m_-4707227785843609320_" name="m_7786664943076552646_m_-4707227785843609320_Layout_8"></a>
                        <table width="100%" cellpadding="0" border="0" bgcolor="#ffffff" align="center" cellspacing="0" style="background-color:rgb(255,255,255)">
                            <tbody><tr>
                                <td valign="top" align="center">
                                    <table cellspacing="0" cellpadding="0" border="0">
                                        <tbody><tr>
                                            <td>
                                                <div style="display:inherit;border-radius:5px;width:590;max-width:760px!important;border-top:0px None #000;border-right:0px None #000;border-bottom:0px None #000;border-left:0px None #000;border-collapse:separate;border-radius:0px">
                                                    <div><img border="0" hspace="0" vspace="0" width="590" alt="" style="display:block;float:left;border-radius:5px" src="https://ci4.googleusercontent.com/proxy/E0bQgj8yDdwW_u1IujfB4VvUMhyi9Yh7RKwlpP4E8eAC55f9DggSuEJS_nxAhvLo-1p2uIXMSjRrhaS8oVF1I_TPodpHp6t5jzFOxOPwkEo8I7PqXZHBb9x9MEqnAPBT--xChWjvblHsWnl54SvDZgywU_Ah7wM4BkizRmd7sYdxtiArn4XWSbCUKNxVkr8QHni8W4ap3TXmneHKI13E426vRbraO4jHpNYeYgcP7BjXyaD1AQ0lIZAkriO-ax6lKKRMYPVJn5K6cE_m9DX3WfDBKFEjLQTaEZtSFRsR0g1Jgt9rlEBwFpLhrPlExOZfYDAXd8U6eTMZ37n4hLWKGC3mR5oVBReuaWNPTLBAQm1J5Sx3tP2k_2mIZaWpajKZELbRlyJAIcqeojjjaj3jbBifrR3gxogXbLpQ1eURDuB177kyk233YvyVJmvA-PU1JunhGgIRtaje7bwzv5-XM-sKKdiYF2DnMYbkdhQgzyI=s0-d-e1-ft#http://4xwtx.img.bh.d.sendibt3.com/im/2943759/1e435429eddb2847b9f198c04f7f58d22a0f2ecf1e707fd1dda29bbdd9e44b00.jpg?e=NDoOfFIrk98NQI4_Kawo9b6qZIPY8rlHt552cKtf7QPlgi-xwLGmU41j7ITLZh77JdXPRWGMF4mP7zk5o-3C64HzOg-qDYumlUBmufeH2J9SEf0NOkInW2LfiXTJgYDHvXREFKHoEEE6srjXcKpFzWExuSU2ewaRhAA6ths56ikXB2yvNB8s0OR9ba96fT4aRCmyqt-G6lesiKCqDQ22pkxzfAI19ejOzkQN4cw" class="CToWUd"></div><div style="clear:both"></div>
                                                    </div></td>
                                        </tr>
                                    </tbody></table>

                                </td>
                            </tr>
                        </tbody></table>
                    </td>
                </tr></tbody></table>
            
                
                
            
        </div></td>
    </tr><tr>

        <td align="center" valign="top">

            <div style="background-color:rgb(249,250,252)">
                
                <table width="100%" cellpadding="0" border="0" cellspacing="0" style="min-width:590px" name="Layout_4" id="m_7786664943076552646m_-4707227785843609320Layout_4">
                    <tbody><tr>
                        <td align="center" valign="top" style="min-width:590px">
                            <a href="#m_7786664943076552646_m_-4707227785843609320_" name="m_7786664943076552646_m_-4707227785843609320_Layout_4"></a>
                            <table width="100%" cellpadding="0" border="0" align="center" cellspacing="0" bgcolor="#f9fafc" style="padding-right:20px;padding-left:20px;background-color:rgb(249,250,252)">
                                <tbody><tr>
                                    <td height="20" style="font-size:1px;line-height:0px">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td style="font-size:14px;color:#888888;font-weight:normal;text-align:center;font-family:Arial,Helvetica,sans-serif">
                                        <div>© 2020 ÉPICO</div>
                                    </td></tr>
                                <tr>
                                    <td height="20" style="font-size:1px;line-height:0px">&nbsp;</td>
                                </tr>
                            </tbody></table>
                        </td>
                    </tr>
                </tbody></table>
                
            </div></td>
    </tr><tr>

        <td align="center" valign="top">

            <div style="background-color:rgb(249,250,252)">
                
                <table width="100%" cellpadding="0" border="0" cellspacing="0" style="min-width:590px" name="Layout_6" id="m_7786664943076552646m_-4707227785843609320Layout_6">
                    <tbody><tr>
                        <td align="center" valign="top" bgcolor="#f9fafc" style="min-width:590px;background-color:#f9fafc;text-align:center">
                            <a href="#m_7786664943076552646_m_-4707227785843609320_" name="m_7786664943076552646_m_-4707227785843609320_Layout_6"></a>
                            <table width="590" cellpadding="0" border="0" align="center" cellspacing="0" bgcolor="#f9fafc" style="padding-right:20px;padding-left:20px;background-color:rgb(249,250,252)">
                                <tbody><tr>
                                    <td height="10" style="font-size:1px;line-height:0px">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td>
                                        <div style="font-size:14px;color:#888888;font-weight:normal;text-align:center;font-family:Arial,Helvetica,sans-serif">Se ha enviado este e-mail a <a href="<<mail>>" target="_blank"><span class="il"><<mail>></span></a><div>Ha recibido este e-mail porque está suscrito a ÉPICO</div><div>&nbsp;</div></div>
                                        
                                    </td></tr>
                                <tr>
                                    <td height="10" style="font-size:1px;line-height:0px">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td height="10" style="font-size:1px;line-height:0px">&nbsp;</td>
                                </tr></tbody></table>
                        </td>
                    </tr>
                </tbody></table>
                
            </div></td>
    </tr></tbody></table>
            
                        </td>
        </tr>
        </tbody></table>';
        return $correo;
    }

    public function getCorreoFase3() {
        $correo = new stdClass();
        $correo->asunto = "Te damos la Bienvenida al programa VALIDANDO";
        $correo->tipo = "Centro de emprendimiento: Fase3";
        $correo->texto_correo = '
            <div style="background-color:#352d54;"><table bgcolor="#352d54" height="100%" width="100%" cellpadding="0" cellspacing="0" border="0"><tbody><tr><td valign="top" align="center" bgcolor="#352d54" background="" style="background-color:#352d54;background-image: url(\'\');background-position:top center;background-repeat:repeat;"><center class="wrapper" style="width:100%;table-layout:fixed;text-align:inherit;"><table cellpadding="0" cellspacing="0" border="0" width="100%"><tbody><tr><td><table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color: #352d54">
  <tbody><tr>
    <td style="background-color: #352d54">
      <!--[if (gte mso 9)|(IE)]> <table class="outlook-container " width="600" align="center" bgcolor="#FFFFFF" style="background-color:#FFFFFF;box-sizing:border-box;border-spacing:0;border-collapse:collapse;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;" > <tr><td width="100%" valign="top" align="center"> <![endif]-->
      <table class="wrapper--outer" align="center" style="box-sizing:border-box;border-spacing:0;border-collapse:collapse;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;width:100%;max-width:600px;margin-top:0;margin-bottom:0;margin-right:auto;margin-left:auto; background-color:#FFFFFF" bgcolor="#FFFFFF">
        <tbody><tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
          <td class="column--1 image" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;font-size:0;padding-top:10px;padding-bottom:10px;padding-right:10px;padding-left:10px;">
            <table width="100%" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;">
              <tbody><tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                <td class="wrapper--column image" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;padding-top:10px;padding-bottom:10px;padding-right:10px;padding-left:10px;">
                  <table class="wrapper--content" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;width:100%;">
                    <tbody><tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                      <td class="wrapper--inner" align="center" style="padding:0;line-height:0px;border-collapse:collapse !important;word-break:break-word;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;">
                        <img ondragstart="return false;" width="560" src="https://app2.dopplerfiles.com/Templates/221877/mail.jpg" alt="mail" style="clear:both;width:560px;max-width:100%;text-decoration:none;border-style:none;outline-style:none;-ms-interpolation-mode:bicubic;text-align:center;">
                      </td>
                    </tr>
                  </tbody></table>
                </td>
              </tr>
            </tbody></table>
          </td>
        </tr>
      </tbody></table>
      <!--[if (gte mso 9)|(IE)]> </td> </tr> </table> <![endif]-->
    </td>
  </tr>
</tbody></table>
</td></tr><tr><td><table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color: #352d54">
  <tbody><tr>
    <td style="background-color: #352d54">
      <!--[if (gte mso 9)|(IE)]> <table class="outlook-container " width="600" align="center" bgcolor="#FDB913" style="background-color:#FDB913;box-sizing:border-box;border-spacing:0;border-collapse:collapse;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;" > <tr><td width="100%" valign="top" align="center"> <![endif]-->
      <table class="wrapper--outer" align="center" style="box-sizing:border-box;border-spacing:0;border-collapse:collapse;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;width:100%;max-width:600px;margin-top:0;margin-bottom:0;margin-right:auto;margin-left:auto; background-color:#FDB913" bgcolor="#FDB913">
        <tbody><tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
          <td class="column--1" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;font-size:0;padding-top:10px;padding-bottom:10px;padding-right:10px;padding-left:10px;text-align:center;">
            <table width="100%" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;">
              <tbody><tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                <td class="wrapper--column" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;padding-top:10px;padding-bottom:10px;padding-right:10px;padding-left:10px;">
                  <table class="wrapper--content" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;width:100%;">
                    <tbody><tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                      <td align="center" class="wrapper--inner" style="padding:0;line-height:120%;font-size:12px;border-collapse:collapse !important;word-break:break-word;word-wrap:break-word; margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;">
                        <span style="display: block;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;line-height:normal;"><div style="text-align: center;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl">Hola <<nombre>></span></div><div style="text-align: center;"><br></div><div style="text-align: center;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 16px; line-height: 1.3;" class="font-line-height-xl"><b>¡Te estamos esperando para llevar tu emprendimiento a un nuevo nivel! </b></span></div><div style="text-align: center;"><br></div></span>
                      </td>
                    </tr>
                  </tbody></table>
                </td>
              </tr>
            </tbody></table>
          </td>
        </tr>
      </tbody></table>
      <!--[if (gte mso 9)|(IE)]> </td> </tr> </table> <![endif]-->
    </td>
  </tr>
</tbody></table>
</td></tr><tr><td><table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color: #352d54">
  <tbody><tr>
    <td style="background-color: #352d54">
      <!--[if (gte mso 9)|(IE)]> <table class="outlook-container " width="600" align="center" bgcolor="#F7F7F7" style="background-color:#F7F7F7;box-sizing:border-box;border-spacing:0;border-collapse:collapse;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;" > <tr><td width="100%" valign="top" align="center"> <![endif]-->
      <table class="wrapper--outer" align="center" style="box-sizing:border-box;border-spacing:0;border-collapse:collapse;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;width:100%;max-width:600px;margin-top:0;margin-bottom:0;margin-right:auto;margin-left:auto; background-color:#F7F7F7" bgcolor="#F7F7F7">
        <tbody><tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
          <td class="column--1" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;font-size:0;padding-top:10px;padding-bottom:10px;padding-right:10px;padding-left:10px;text-align:center;">
            <table width="100%" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;">
              <tbody><tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                <td class="wrapper--column" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;padding-top:10px;padding-bottom:10px;padding-right:10px;padding-left:10px;">
                  <table class="wrapper--content" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;width:100%;">
                    <tbody><tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                      <td align="left" class="wrapper--inner" style="padding:0;line-height:120%;font-size:12px;border-collapse:collapse !important;word-break:break-word;word-wrap:break-word; margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;">
                        <span style="display: block;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;line-height:normal;"><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl">Hoy es un día lleno de nuevas oportunidades, estás a punto de arrancar el plan de trabajo que hemos diseñado para ti y tu deseo de emprender.</span></div><div style="text-align: left;"><br></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl">Te invitamos a una <b>sesión de inducción</b> que te permitirá conocer tu plan de trabajo, las instalaciones y cómo utilizar nuestra plataforma de servicios para el emprendedor. &nbsp;Todo para facilitar tu transitar en la ruta del emprendimiento ÉPICO. </span></div><div style="text-align: left;"><br></div><div style="text-align: left;"><br></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl">Tendremos 2 horarios disponibles<b>:</b></span></div><div style="text-align: left;"><br></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl"><b>Lunes 17 de agosto 10H00 a 11H00 virtual</b></span></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl">https://docs.google.com/forms/d/e/1FAIpQLSfs-jeF8f59womCaSpY5HRLTPP6EGIeHeLyYU8YMZ_XHrflPw/viewform?usp=pp_url</span></div><div style="text-align: left;"><br></div><div style="text-align: left;"><br></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl"><b>Lunes 24 de agosto 16H00 a 18H00 presencial</b></span></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl">Registro máximo 30 personas </span></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl">https://docs.google.com/forms/d/e/1FAIpQLSfs-jeF8f59womCaSpY5HRLTPP6EGIeHeLyYU8YMZ_XHrflPw/viewform?usp=pp_url</span></div><div style="text-align: left;"><br></div><div style="text-align: left;">También te adjuntamos el usuario y contraseña para que puedas ingresar al sistema una vez que participes de la inducción:</div><div style="text-align: left;"><br></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl">Usuario: <<email>></span></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl">Password: <<password>></span></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl">Link: http://epico.gob.ec/centro_emprendimiento/login.php</span></div><div style="text-align: left;"><br></div><div style="text-align: left;"><br></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl">Si tienes alguna inquietud o duda adicional, escríbenos al siguiente correo: <i><b>mesadeservicio@epico.gob.ec</b></i> ¡Con gusto te atenderemos!</span></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl"><b>&nbsp;</b></span></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl"><b>&nbsp;</b></span></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl"><b>¡Construyamos juntos un Guayaquil ÉPICO!</b></span></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl"><b>&nbsp;</b></span></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl">Saludos cordiales,</span></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl"><b>El equipo ÉPICO</b></span></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl"><b>&nbsp;</b></span></div></span>
                      </td>
                    </tr>
                  </tbody></table>
                </td>
              </tr>
            </tbody></table>
          </td>
        </tr>
      </tbody></table>
      <!--[if (gte mso 9)|(IE)]> </td> </tr> </table> <![endif]-->
    </td>
  </tr>
</tbody></table>
</td></tr><tr><td><table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color: #352d54">
  <tbody><tr>
    <td style="background-color: #352d54">
      <!--[if (gte mso 9)|(IE)]> <table class="outlook-container " width="600" align="center" bgcolor="#FFFFFF" style="background-color:#FFFFFF;box-sizing:border-box;border-spacing:0;border-collapse:collapse;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;" > <tr><td width="100%" align="center" valign="top"> <![endif]-->
      <table class="wrapper--outer" align="center" style="box-sizing:border-box;border-spacing:0;border-collapse:collapse;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;width:100%;max-width:600px;margin-top:0;margin-bottom:0;margin-right:auto;margin-left:auto; background-color:#FFFFFF" bgcolor="#FFFFFF">
        <tbody><tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
          <td class="column--1" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;font-size:0;padding-top:10px;padding-bottom:10px;padding-right:10px;padding-left:10px;text-align:center;">
            <table width="100%" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;">
              <tbody><tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                <td class="wrapper--column" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;padding-top:5px;padding-bottom:10px;padding-right:10px;padding-left:10px;">
                  <table class="wrapper--content" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;width:100%;">
                    <tbody><tr style="vertical-align: top">
                      <td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;padding: 0;" align="center">
                        <table class="table_center" border="0" cellpadding="0" cellspacing="0" style="text-align: center">
                          <tbody><tr>
                            <td style="display: inline-block; padding-right: 5px; padding-top: 5px; line-height: 0px;" valign="middle">
                              <a ondragstart="return false;" href="http://facebook.com/https://es-la.facebook.com/centroemprendimientogye/" target="_blank">
                                <img ondragstart="return false;" width="32" src="https://app2.dopplerfiles.com/MSEditor/images/color_rounded_facebook.png" alt="Facebook">
                              </a>
                            </td>
                            <td style="display: inline-block; padding-right: 5px; padding-top: 5px; line-height: 0px;" valign="middle">
                              <a ondragstart="return false;" href="http://instagram.com/https://www.instagram.com/epicogye/" target="_blank">
                                <img ondragstart="return false;" width="32" src="https://app2.dopplerfiles.com/MSEditor/images/color_rounded_instagram.png" alt="Instagram">
                              </a>
                            </td>
                            <td style="display: inline-block; padding-right: 0px; padding-top: 5px; line-height: 0px;" valign="middle">
                              <a ondragstart="return false;" href="http://linkedin.com/https://www.linkedin.com/organization-guest/company/epicogye?challengeId=AQE_o3qKvYZJlQAAAXNt80cH7Tw4HJGFvMoxs01VyGqWeI7tVlIGsgP1YYNDnmSdw4EWzEs5m0wWVFv_rOCVTx86x8asyc1gFQ&amp;submissionId=35e2cc50-7590-2316-ee4f-d015707dcc45" target="_blank">
                                <img ondragstart="return false;" width="32" src="https://app2.dopplerfiles.com/MSEditor/images/color_rounded_linkedin.png" alt="Linkedin">
                              </a>
                            </td>
                            
                            
                            
                            
                            
                            
                            
                          </tr>
                        </tbody></table>
                      </td>
                    </tr>
                  </tbody></table>
                </td>
              </tr>
            </tbody></table>
          </td>
        </tr>
      </tbody></table>
      <!--[if (gte mso 9)|(IE)]> </td> </tr> </table> <![endif]-->
    </td>
  </tr>
</tbody></table>
</td></tr><tr><td><table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color: #352d54">
  <tbody><tr>
    <td style="background-color: #352d54">
      <!--[if (gte mso 9)|(IE)]> <table class="outlook-container " width="600" align="center" bgcolor="#ffffff" style="background-color:#ffffff;box-sizing:border-box;border-spacing:0;border-collapse:collapse;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;" > <tr><td width="100%" valign="top" align="center"> <![endif]-->
      <table class="wrapper--outer" align="center" style="box-sizing:border-box;border-spacing:0;border-collapse:collapse;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;width:100%;max-width:600px;margin-top:0;margin-bottom:0;margin-right:auto;margin-left:auto; background-color:#ffffff" bgcolor="#ffffff">
        <tbody><tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
          <td class="column--1" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;font-size:0;padding-top:5px;padding-bottom:5px;padding-right:10px;padding-left:10px;text-align:center;">
            <table width="100%" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;">
              <tbody><tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                <td class="wrapper--column" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;padding-top:5px;padding-bottom:5px;padding-right:10px;padding-left:10px;">
                  <table class="wrapper--content" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;width:100%;">
                    <tbody><tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                      <td align="center" class="wrapper--inner" style="padding:0;line-height:120%;font-size:12px;border-collapse:collapse !important;word-break:break-word;word-wrap:break-word; margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;">
                        <span style="display: block;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;line-height:normal;"><div style="text-align: center;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif;"><b>@epicogye @empredimiento_epicogye</b></span></div></span>
                      </td>
                    </tr>
                  </tbody></table>
                </td>
              </tr>
            </tbody></table>
          </td>
        </tr>
      </tbody></table>
      <!--[if (gte mso 9)|(IE)]> </td> </tr> </table> <![endif]-->
    </td>
  </tr>
</tbody></table>
</td></tr></tbody></table></center></td></tr></tbody></table></div>
                '
        ;
        return $correo;
    }

    public function getCorreoFase4() {
        $correo = new stdClass();
        $correo->asunto = "Te damos la Bienvenida al programa DESPEGANDO";
        $correo->tipo = "Centro de emprendimiento: Fase4";
        $correo->texto_correo = '
            <div style="background-color:#352d54;"><table bgcolor="#352d54" height="100%" width="100%" cellpadding="0" cellspacing="0" border="0"><tbody><tr><td valign="top" align="center" bgcolor="#352d54" background="" style="background-color:#352d54;background-image: url(\'\');background-position:top center;background-repeat:repeat;"><center class="wrapper" style="width:100%;table-layout:fixed;text-align:inherit;"><table cellpadding="0" cellspacing="0" border="0" width="100%"><tbody><tr><td><table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color: #352d54">
  <tbody><tr>
    <td style="background-color: #352d54">
      <!--[if (gte mso 9)|(IE)]> <table class="outlook-container " width="600" align="center" bgcolor="#FFFFFF" style="background-color:#FFFFFF;box-sizing:border-box;border-spacing:0;border-collapse:collapse;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;" > <tr><td width="100%" valign="top" align="center"> <![endif]-->
      <table class="wrapper--outer" align="center" style="box-sizing:border-box;border-spacing:0;border-collapse:collapse;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;width:100%;max-width:600px;margin-top:0;margin-bottom:0;margin-right:auto;margin-left:auto; background-color:#FFFFFF" bgcolor="#FFFFFF">
        <tbody><tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
          <td class="column--1 image" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;font-size:0;padding-top:10px;padding-bottom:10px;padding-right:10px;padding-left:10px;">
            <table width="100%" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;">
              <tbody><tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                <td class="wrapper--column image" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;padding-top:10px;padding-bottom:10px;padding-right:10px;padding-left:10px;">
                  <table class="wrapper--content" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;width:100%;">
                    <tbody><tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                      <td class="wrapper--inner" align="center" style="padding:0;line-height:0px;border-collapse:collapse !important;word-break:break-word;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;">
                        <img ondragstart="return false;" width="560" src="https://app2.dopplerfiles.com/Templates/221877/mail.jpg" alt="mail" style="clear:both;width:560px;max-width:100%;text-decoration:none;border-style:none;outline-style:none;-ms-interpolation-mode:bicubic;text-align:center;">
                      </td>
                    </tr>
                  </tbody></table>
                </td>
              </tr>
            </tbody></table>
          </td>
        </tr>
      </tbody></table>
      <!--[if (gte mso 9)|(IE)]> </td> </tr> </table> <![endif]-->
    </td>
  </tr>
</tbody></table>
</td></tr><tr><td><table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color: #352d54">
  <tbody><tr>
    <td style="background-color: #352d54">
      <!--[if (gte mso 9)|(IE)]> <table class="outlook-container " width="600" align="center" bgcolor="#FDB913" style="background-color:#FDB913;box-sizing:border-box;border-spacing:0;border-collapse:collapse;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;" > <tr><td width="100%" valign="top" align="center"> <![endif]-->
      <table class="wrapper--outer" align="center" style="box-sizing:border-box;border-spacing:0;border-collapse:collapse;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;width:100%;max-width:600px;margin-top:0;margin-bottom:0;margin-right:auto;margin-left:auto; background-color:#FDB913" bgcolor="#FDB913">
        <tbody><tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
          <td class="column--1" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;font-size:0;padding-top:10px;padding-bottom:10px;padding-right:10px;padding-left:10px;text-align:center;">
            <table width="100%" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;">
              <tbody><tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                <td class="wrapper--column" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;padding-top:10px;padding-bottom:10px;padding-right:10px;padding-left:10px;">
                  <table class="wrapper--content" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;width:100%;">
                    <tbody><tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                      <td align="center" class="wrapper--inner" style="padding:0;line-height:120%;font-size:12px;border-collapse:collapse !important;word-break:break-word;word-wrap:break-word; margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;">
                        <span style="display: block;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;line-height:normal;"><div style="text-align: center;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl">Hola <<nombre>></span></div><div style="text-align: center;"><br></div><div style="text-align: center;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 16px; line-height: 1.3;" class="font-line-height-xl"><b>¡Te estamos esperando para llevar tu emprendimiento a un nuevo nivel! </b></span></div><div style="text-align: center;"><br></div></span>
                      </td>
                    </tr>
                  </tbody></table>
                </td>
              </tr>
            </tbody></table>
          </td>
        </tr>
      </tbody></table>
      <!--[if (gte mso 9)|(IE)]> </td> </tr> </table> <![endif]-->
    </td>
  </tr>
</tbody></table>
</td></tr><tr><td><table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color: #352d54">
  <tbody><tr>
    <td style="background-color: #352d54">
      <!--[if (gte mso 9)|(IE)]> <table class="outlook-container " width="600" align="center" bgcolor="#F7F7F7" style="background-color:#F7F7F7;box-sizing:border-box;border-spacing:0;border-collapse:collapse;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;" > <tr><td width="100%" valign="top" align="center"> <![endif]-->
      <table class="wrapper--outer" align="center" style="box-sizing:border-box;border-spacing:0;border-collapse:collapse;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;width:100%;max-width:600px;margin-top:0;margin-bottom:0;margin-right:auto;margin-left:auto; background-color:#F7F7F7" bgcolor="#F7F7F7">
        <tbody><tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
          <td class="column--1" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;font-size:0;padding-top:10px;padding-bottom:10px;padding-right:10px;padding-left:10px;text-align:center;">
            <table width="100%" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;">
              <tbody><tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                <td class="wrapper--column" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;padding-top:10px;padding-bottom:10px;padding-right:10px;padding-left:10px;">
                  <table class="wrapper--content" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;width:100%;">
                    <tbody><tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                      <td align="left" class="wrapper--inner" style="padding:0;line-height:120%;font-size:12px;border-collapse:collapse !important;word-break:break-word;word-wrap:break-word; margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;">
                        <span style="display: block;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;line-height:normal;"><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl">Hoy es un día lleno de nuevas oportunidades, estás a punto de arrancar el plan de trabajo que hemos diseñado para ti y tu deseo de emprender.</span></div><div style="text-align: left;"><br></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl">Te invitamos a una <b>sesión de inducción</b> que te permitirá conocer tu plan de trabajo, las instalaciones y cómo utilizar nuestra plataforma de servicios para el emprendedor. &nbsp;Todo para facilitar tu transitar en la ruta del emprendimiento ÉPICO. </span></div><div style="text-align: left;"><br></div><div style="text-align: left;"><br></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl">Tendremos 2 horarios disponibles<b>:</b></span></div><div style="text-align: left;"><br></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl"><b>Lunes 17 de agosto 10H00 a 11H00 virtual</b></span></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl">https://docs.google.com/forms/d/e/1FAIpQLSfs-jeF8f59womCaSpY5HRLTPP6EGIeHeLyYU8YMZ_XHrflPw/viewform?usp=pp_url</span></div><div style="text-align: left;"><br></div><div style="text-align: left;"><br></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl"><b>Lunes 24 de agosto 16H00 a 18H00 presencial</b></span></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl">Registro máximo 30 personas </span></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl">https://docs.google.com/forms/d/e/1FAIpQLSfs-jeF8f59womCaSpY5HRLTPP6EGIeHeLyYU8YMZ_XHrflPw/viewform?usp=pp_url</span></div><div style="text-align: left;"><br></div><div style="text-align: left;">También te adjuntamos el usuario y contraseña para que puedas ingresar al sistema una vez que participes de la inducción:</div><div style="text-align: left;"><br></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl">Usuario: <<email>></span></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl">Password: <<password>></span></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl">Link: http://epico.gob.ec/centro_emprendimiento/login.php</span></div><div style="text-align: left;"><br></div><div style="text-align: left;"><br></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl">Si tienes alguna inquietud o duda adicional, escríbenos al siguiente correo: <i><b>mesadeservicio@epico.gob.ec</b></i> ¡Con gusto te atenderemos!</span></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl"><b>&nbsp;</b></span></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl"><b>&nbsp;</b></span></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl"><b>¡Construyamos juntos un Guayaquil ÉPICO!</b></span></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl"><b>&nbsp;</b></span></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl">Saludos cordiales,</span></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl"><b>El equipo ÉPICO</b></span></div><div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl"><b>&nbsp;</b></span></div></span>
                      </td>
                    </tr>
                  </tbody></table>
                </td>
              </tr>
            </tbody></table>
          </td>
        </tr>
      </tbody></table>
      <!--[if (gte mso 9)|(IE)]> </td> </tr> </table> <![endif]-->
    </td>
  </tr>
</tbody></table>
</td></tr><tr><td><table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color: #352d54">
  <tbody><tr>
    <td style="background-color: #352d54">
      <!--[if (gte mso 9)|(IE)]> <table class="outlook-container " width="600" align="center" bgcolor="#FFFFFF" style="background-color:#FFFFFF;box-sizing:border-box;border-spacing:0;border-collapse:collapse;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;" > <tr><td width="100%" align="center" valign="top"> <![endif]-->
      <table class="wrapper--outer" align="center" style="box-sizing:border-box;border-spacing:0;border-collapse:collapse;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;width:100%;max-width:600px;margin-top:0;margin-bottom:0;margin-right:auto;margin-left:auto; background-color:#FFFFFF" bgcolor="#FFFFFF">
        <tbody><tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
          <td class="column--1" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;font-size:0;padding-top:10px;padding-bottom:10px;padding-right:10px;padding-left:10px;text-align:center;">
            <table width="100%" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;">
              <tbody><tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                <td class="wrapper--column" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;padding-top:5px;padding-bottom:10px;padding-right:10px;padding-left:10px;">
                  <table class="wrapper--content" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;width:100%;">
                    <tbody><tr style="vertical-align: top">
                      <td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;padding: 0;" align="center">
                        <table class="table_center" border="0" cellpadding="0" cellspacing="0" style="text-align: center">
                          <tbody><tr>
                            <td style="display: inline-block; padding-right: 5px; padding-top: 5px; line-height: 0px;" valign="middle">
                              <a ondragstart="return false;" href="http://facebook.com/https://es-la.facebook.com/centroemprendimientogye/" target="_blank">
                                <img ondragstart="return false;" width="32" src="https://app2.dopplerfiles.com/MSEditor/images/color_rounded_facebook.png" alt="Facebook">
                              </a>
                            </td>
                            <td style="display: inline-block; padding-right: 5px; padding-top: 5px; line-height: 0px;" valign="middle">
                              <a ondragstart="return false;" href="http://instagram.com/https://www.instagram.com/epicogye/" target="_blank">
                                <img ondragstart="return false;" width="32" src="https://app2.dopplerfiles.com/MSEditor/images/color_rounded_instagram.png" alt="Instagram">
                              </a>
                            </td>
                            <td style="display: inline-block; padding-right: 0px; padding-top: 5px; line-height: 0px;" valign="middle">
                              <a ondragstart="return false;" href="http://linkedin.com/https://www.linkedin.com/organization-guest/company/epicogye?challengeId=AQE_o3qKvYZJlQAAAXNt80cH7Tw4HJGFvMoxs01VyGqWeI7tVlIGsgP1YYNDnmSdw4EWzEs5m0wWVFv_rOCVTx86x8asyc1gFQ&amp;submissionId=35e2cc50-7590-2316-ee4f-d015707dcc45" target="_blank">
                                <img ondragstart="return false;" width="32" src="https://app2.dopplerfiles.com/MSEditor/images/color_rounded_linkedin.png" alt="Linkedin">
                              </a>
                            </td>
                            
                            
                            
                            
                            
                            
                            
                          </tr>
                        </tbody></table>
                      </td>
                    </tr>
                  </tbody></table>
                </td>
              </tr>
            </tbody></table>
          </td>
        </tr>
      </tbody></table>
      <!--[if (gte mso 9)|(IE)]> </td> </tr> </table> <![endif]-->
    </td>
  </tr>
</tbody></table>
</td></tr><tr><td><table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color: #352d54">
  <tbody><tr>
    <td style="background-color: #352d54">
      <!--[if (gte mso 9)|(IE)]> <table class="outlook-container " width="600" align="center" bgcolor="#ffffff" style="background-color:#ffffff;box-sizing:border-box;border-spacing:0;border-collapse:collapse;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;" > <tr><td width="100%" valign="top" align="center"> <![endif]-->
      <table class="wrapper--outer" align="center" style="box-sizing:border-box;border-spacing:0;border-collapse:collapse;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;width:100%;max-width:600px;margin-top:0;margin-bottom:0;margin-right:auto;margin-left:auto; background-color:#ffffff" bgcolor="#ffffff">
        <tbody><tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
          <td class="column--1" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;font-size:0;padding-top:5px;padding-bottom:5px;padding-right:10px;padding-left:10px;text-align:center;">
            <table width="100%" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;">
              <tbody><tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                <td class="wrapper--column" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;padding-top:5px;padding-bottom:5px;padding-right:10px;padding-left:10px;">
                  <table class="wrapper--content" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;width:100%;">
                    <tbody><tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                      <td align="center" class="wrapper--inner" style="padding:0;line-height:120%;font-size:12px;border-collapse:collapse !important;word-break:break-word;word-wrap:break-word; margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;">
                        <span style="display: block;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;line-height:normal;"><div style="text-align: center;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif;"><b>@epicogye @empredimiento_epicogye</b></span></div></span>
                      </td>
                    </tr>
                  </tbody></table>
                </td>
              </tr>
            </tbody></table>
          </td>
        </tr>
      </tbody></table>
      <!--[if (gte mso 9)|(IE)]> </td> </tr> </table> <![endif]-->
    </td>
  </tr>
</tbody></table>
</td></tr></tbody></table></center></td></tr></tbody></table></div>
            ';
        return $correo;
    }

    public function getCorreoAsistenteTecnico() {
        $correo = new stdClass();
        $correo->asunto = "Reunion asistencia tecnica";
        $correo->tipo = "AGENDA EMPRENDEDOR";
        $correo->texto_correo = '
            <div style="background-color:#352d54;">
   <table bgcolor="#352d54" height="100%" width="100%" cellpadding="0" cellspacing="0" border="0">
      <tbody>
         <tr>
            <td valign="top" align="center" bgcolor="#352d54" background="" style="background-color:#352d54;background-image: url(\'\');background-position:top center;background-repeat:repeat;">
               <center class="wrapper" style="width:100%;table-layout:fixed;text-align:inherit;">
                  <table cellpadding="0" cellspacing="0" border="0" width="100%">
                     <tbody>
                        <tr>
                           <td>
                              <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color: #352d54">
                                 <tbody>
                                    <tr>
                                       <td style="background-color: #352d54">
                                          <!--[if (gte mso 9)|(IE)]> 
                                          <table class="outlook-container " width="600" align="center" bgcolor="#FFFFFF" style="background-color:#FFFFFF;box-sizing:border-box;border-spacing:0;border-collapse:collapse;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;" >
                                             <tr>
                                                <td width="100%" valign="top" align="center">
                                                   <![endif]-->
                                                   <table class="wrapper--outer" align="center" style="box-sizing:border-box;border-spacing:0;border-collapse:collapse;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;width:100%;max-width:600px;margin-top:0;margin-bottom:0;margin-right:auto;margin-left:auto; background-color:#FFFFFF" bgcolor="#FFFFFF">
                                                      <tbody>
                                                         <tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                                                            <td class="column--1 image" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;font-size:0;padding-top:10px;padding-bottom:10px;padding-right:10px;padding-left:10px;">
                                                               <table width="100%" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;">
                                                                  <tbody>
                                                                     <tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                                                                        <td class="wrapper--column image" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;padding-top:10px;padding-bottom:10px;padding-right:10px;padding-left:10px;">
                                                                           <table class="wrapper--content" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;width:100%;">
                                                                              <tbody>
                                                                                 <tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                                                                                    <td class="wrapper--inner" align="center" style="padding:0;line-height:0px;border-collapse:collapse !important;word-break:break-word;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;">
                                                                                       <img ondragstart="return false;" width="560" src="https://app2.dopplerfiles.com/Templates/225908/mail.jpg" alt="mail" style="clear:both;width:560px;max-width:100%;text-decoration:none;border-style:none;outline-style:none;-ms-interpolation-mode:bicubic;text-align:center;">
                                                                                    </td>
                                                                                 </tr>
                                                                              </tbody>
                                                                           </table>
                                                                        </td>
                                                                     </tr>
                                                                  </tbody>
                                                               </table>
                                                            </td>
                                                         </tr>
                                                      </tbody>
                                                   </table>
                                                   <!--[if (gte mso 9)|(IE)]> 
                                                </td>
                                             </tr>
                                          </table>
                                          <![endif]-->
                                       </td>
                                    </tr>
                                 </tbody>
                              </table>
                           </td>
                        </tr>
                        <tr>
                           <td>
                              <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color: #352d54">
                                 <tbody>
                                    <tr>
                                       <td style="background-color: #352d54">
                                          <!--[if (gte mso 9)|(IE)]> 
                                          <table class="outlook-container " width="600" align="center" bgcolor="#FDB913" style="background-color:#FDB913;box-sizing:border-box;border-spacing:0;border-collapse:collapse;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;" >
                                             <tr>
                                                <td width="100%" valign="top" align="center">
                                                   <![endif]-->
                                                   <table class="wrapper--outer" align="center" style="box-sizing:border-box;border-spacing:0;border-collapse:collapse;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;width:100%;max-width:600px;margin-top:0;margin-bottom:0;margin-right:auto;margin-left:auto; background-color:#FDB913" bgcolor="#FDB913">
                                                      <tbody>
                                                         <tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                                                            <td class="column--1" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;font-size:0;padding-top:10px;padding-bottom:10px;padding-right:10px;padding-left:10px;text-align:center;">
                                                               <table width="100%" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;">
                                                                  <tbody>
                                                                     <tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                                                                        <td class="wrapper--column" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;padding-top:10px;padding-bottom:10px;padding-right:10px;padding-left:10px;">
                                                                           <table class="wrapper--content" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;width:100%;">
                                                                              <tbody>
                                                                                 <tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                                                                                    <td align="center" class="wrapper--inner" style="padding:0;line-height:120%;font-size:12px;border-collapse:collapse !important;word-break:break-word;word-wrap:break-word; margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;">
                                                                                       <span style="display: block;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;line-height:normal;">
                                                                                          <div style="text-align: center;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl">Hola <<nombre_asistente>></span></div>
                                                                                          <div style="text-align: center;"><br></div>
                                                                                          <div style="text-align: center;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 16px; line-height: 1.3;" class="font-line-height-xl"><b>¡Te han agendado a una reunión!</b></span></div>
                                                                                          <div style="text-align: center;"><br></div>
                                                                                       </span>
                                                                                    </td>
                                                                                 </tr>
                                                                              </tbody>
                                                                           </table>
                                                                        </td>
                                                                     </tr>
                                                                  </tbody>
                                                               </table>
                                                            </td>
                                                         </tr>
                                                      </tbody>
                                                   </table>
                                                   <!--[if (gte mso 9)|(IE)]> 
                                                </td>
                                             </tr>
                                          </table>
                                          <![endif]-->
                                       </td>
                                    </tr>
                                 </tbody>
                              </table>
                           </td>
                        </tr>
                        <tr>
                           <td>
                              <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color: #352d54">
                                 <tbody>
                                    <tr>
                                       <td style="background-color: #352d54">
                                          <!--[if (gte mso 9)|(IE)]> 
                                          <table class="outlook-container " width="600" align="center" bgcolor="#F7F7F7" style="background-color:#F7F7F7;box-sizing:border-box;border-spacing:0;border-collapse:collapse;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;" >
                                             <tr>
                                                <td width="100%" valign="top" align="center">
                                                   <![endif]-->
                                                   <table class="wrapper--outer" align="center" style="box-sizing:border-box;border-spacing:0;border-collapse:collapse;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;width:100%;max-width:600px;margin-top:0;margin-bottom:0;margin-right:auto;margin-left:auto; background-color:#F7F7F7" bgcolor="#F7F7F7">
                                                      <tbody>
                                                         <tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                                                            <td class="column--1" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;font-size:0;padding-top:10px;padding-bottom:10px;padding-right:10px;padding-left:10px;text-align:center;">
                                                               <table width="100%" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;">
                                                                  <tbody>
                                                                     <tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                                                                        <td class="wrapper--column" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;padding-top:10px;padding-bottom:10px;padding-right:10px;padding-left:10px;">
                                                                           <table class="wrapper--content" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;width:100%;">
                                                                              <tbody>
                                                                                 <tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                                                                                    <td align="left" class="wrapper--inner" style="padding:0;line-height:120%;font-size:12px;border-collapse:collapse !important;word-break:break-word;word-wrap:break-word; margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;">
                                                                                       <span style="display: block;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;line-height:normal;">
                                                                                          <div style="text-align: left;">Estimado(a):</div>
                                                                                          <div style="text-align: left;"><br></div>
                                                                                          <div style="text-align: left;">Has sido agendado para una reunión con el emprendedor <<nombre_emprendedor>>.</div>
                                                                                          <div style="text-align: left;"><br></div>
                                                                                          <div style="text-align: left;">Fecha: <<fecha>></div>
                                                                                          <div style="text-align: left;">Hora: <<hora>></div>
                                                                                          <div style="text-align: left;"><br></div>
                                                                                          <div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl">Si deseas cancelar o reagendar escríbenos a <i><b>mesadeservicio@epico.gob.ec</b></i> ¡Con gusto te atenderemos!</span></div>
                                                                                          <div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl"><b>&nbsp;</b></span></div>
                                                                                          <div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl"><b>&nbsp;</b></span></div>
                                                                                          <div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl"><b>¡Construyamos juntos un Guayaquil ÉPICO!</b></span></div>
                                                                                          <div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl"><b>&nbsp;</b></span></div>
                                                                                          <div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl">Saludos cordiales,</span></div>
                                                                                          <div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl"><b>El equipo ÉPICO</b></span></div>
                                                                                          <div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl"><b>&nbsp;</b></span></div>
                                                                                       </span>
                                                                                    </td>
                                                                                 </tr>
                                                                              </tbody>
                                                                           </table>
                                                                        </td>
                                                                     </tr>
                                                                  </tbody>
                                                               </table>
                                                            </td>
                                                         </tr>
                                                      </tbody>
                                                   </table>
                                                   <!--[if (gte mso 9)|(IE)]> 
                                                </td>
                                             </tr>
                                          </table>
                                          <![endif]-->
                                       </td>
                                    </tr>
                                 </tbody>
                              </table>
                           </td>
                        </tr>
                        <tr>
                           <td>
                              <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color: #352d54">
                                 <tbody>
                                    <tr>
                                       <td style="background-color: #352d54">
                                          <!--[if (gte mso 9)|(IE)]> 
                                          <table class="outlook-container " width="600" align="center" bgcolor="#FFFFFF" style="background-color:#FFFFFF;box-sizing:border-box;border-spacing:0;border-collapse:collapse;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;" >
                                             <tr>
                                                <td width="100%" align="center" valign="top">
                                                   <![endif]-->
                                                   <table class="wrapper--outer" align="center" style="box-sizing:border-box;border-spacing:0;border-collapse:collapse;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;width:100%;max-width:600px;margin-top:0;margin-bottom:0;margin-right:auto;margin-left:auto; background-color:#FFFFFF" bgcolor="#FFFFFF">
                                                      <tbody>
                                                         <tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                                                            <td class="column--1" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;font-size:0;padding-top:10px;padding-bottom:10px;padding-right:10px;padding-left:10px;text-align:center;">
                                                               <table width="100%" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;">
                                                                  <tbody>
                                                                     <tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                                                                        <td class="wrapper--column" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;padding-top:5px;padding-bottom:10px;padding-right:10px;padding-left:10px;">
                                                                           <table class="wrapper--content" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;width:100%;">
                                                                              <tbody>
                                                                                 <tr style="vertical-align: top">
                                                                                    <td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;padding: 0;" align="center">
                                                                                       <table class="table_center" border="0" cellpadding="0" cellspacing="0" style="text-align: center">
                                                                                          <tbody>
                                                                                             <tr>
                                                                                                <td style="display: inline-block; padding-right: 5px; padding-top: 5px; line-height: 0px;" valign="middle">
                                                                                                   <a ondragstart="return false;" href="http://facebook.com/https://es-la.facebook.com/centroemprendimientogye/" target="_blank">
                                                                                                   <img ondragstart="return false;" width="32" src="https://app2.dopplerfiles.com/MSEditor/images/color_rounded_facebook.png" alt="Facebook">
                                                                                                   </a>
                                                                                                </td>
                                                                                                <td style="display: inline-block; padding-right: 5px; padding-top: 5px; line-height: 0px;" valign="middle">
                                                                                                   <a ondragstart="return false;" href="http://instagram.com/https://www.instagram.com/epicogye/" target="_blank">
                                                                                                   <img ondragstart="return false;" width="32" src="https://app2.dopplerfiles.com/MSEditor/images/color_rounded_instagram.png" alt="Instagram">
                                                                                                   </a>
                                                                                                </td>
                                                                                                <td style="display: inline-block; padding-right: 0px; padding-top: 5px; line-height: 0px;" valign="middle">
                                                                                                   <a ondragstart="return false;" href="http://linkedin.com/https://www.linkedin.com/organization-guest/company/epicogye?challengeId=AQE_o3qKvYZJlQAAAXNt80cH7Tw4HJGFvMoxs01VyGqWeI7tVlIGsgP1YYNDnmSdw4EWzEs5m0wWVFv_rOCVTx86x8asyc1gFQ&amp;submissionId=35e2cc50-7590-2316-ee4f-d015707dcc45" target="_blank">
                                                                                                   <img ondragstart="return false;" width="32" src="https://app2.dopplerfiles.com/MSEditor/images/color_rounded_linkedin.png" alt="Linkedin">
                                                                                                   </a>
                                                                                                </td>
                                                                                             </tr>
                                                                                          </tbody>
                                                                                       </table>
                                                                                    </td>
                                                                                 </tr>
                                                                              </tbody>
                                                                           </table>
                                                                        </td>
                                                                     </tr>
                                                                  </tbody>
                                                               </table>
                                                            </td>
                                                         </tr>
                                                      </tbody>
                                                   </table>
                                                   <!--[if (gte mso 9)|(IE)]> 
                                                </td>
                                             </tr>
                                          </table>
                                          <![endif]-->
                                       </td>
                                    </tr>
                                 </tbody>
                              </table>
                           </td>
                        </tr>
                        <tr>
                           <td>
                              <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color: #352d54">
                                 <tbody>
                                    <tr>
                                       <td style="background-color: #352d54">
                                          <!--[if (gte mso 9)|(IE)]> 
                                          <table class="outlook-container " width="600" align="center" bgcolor="#ffffff" style="background-color:#ffffff;box-sizing:border-box;border-spacing:0;border-collapse:collapse;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;" >
                                             <tr>
                                                <td width="100%" valign="top" align="center">
                                                   <![endif]-->
                                                   <table class="wrapper--outer" align="center" style="box-sizing:border-box;border-spacing:0;border-collapse:collapse;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;width:100%;max-width:600px;margin-top:0;margin-bottom:0;margin-right:auto;margin-left:auto; background-color:#ffffff" bgcolor="#ffffff">
                                                      <tbody>
                                                         <tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                                                            <td class="column--1" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;font-size:0;padding-top:5px;padding-bottom:5px;padding-right:10px;padding-left:10px;text-align:center;">
                                                               <table width="100%" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;">
                                                                  <tbody>
                                                                     <tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                                                                        <td class="wrapper--column" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;padding-top:5px;padding-bottom:5px;padding-right:10px;padding-left:10px;">
                                                                           <table class="wrapper--content" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;width:100%;">
                                                                              <tbody>
                                                                                 <tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                                                                                    <td align="center" class="wrapper--inner" style="padding:0;line-height:120%;font-size:12px;border-collapse:collapse !important;word-break:break-word;word-wrap:break-word; margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;">
                                                                                       <span style="display: block;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;line-height:normal;">
                                                                                          <div style="text-align: center;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif;"><b>@epicogye @empredimiento_epicogye</b></span></div>
                                                                                       </span>
                                                                                    </td>
                                                                                 </tr>
                                                                              </tbody>
                                                                           </table>
                                                                        </td>
                                                                     </tr>
                                                                  </tbody>
                                                               </table>
                                                            </td>
                                                         </tr>
                                                      </tbody>
                                                   </table>
                                                   <!--[if (gte mso 9)|(IE)]> 
                                                </td>
                                             </tr>
                                          </table>
                                          <![endif]-->
                                       </td>
                                    </tr>
                                 </tbody>
                              </table>
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </center>
            </td>
         </tr>
      </tbody>
   </table>
</div>
            ';
        return $correo;
    }

    public function getCorreoEmprendedorAgenda() {
        $correo = new stdClass();
        $correo->asunto = "Reunion asistencia tecnica";
        $correo->tipo = "AGENDA EMPRENDEDOR";
        $correo->texto_correo = '
            <div style="background-color:#352d54;">
   <table bgcolor="#352d54" height="100%" width="100%" cellpadding="0" cellspacing="0" border="0">
      <tbody>
         <tr>
            <td valign="top" align="center" bgcolor="#352d54" background="" style="background-color:#352d54;background-image: url(\'\');background-position:top center;background-repeat:repeat;">
               <center class="wrapper" style="width:100%;table-layout:fixed;text-align:inherit;">
                  <table cellpadding="0" cellspacing="0" border="0" width="100%">
                     <tbody>
                        <tr>
                           <td>
                              <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color: #352d54">
                                 <tbody>
                                    <tr>
                                       <td style="background-color: #352d54">
                                          <!--[if (gte mso 9)|(IE)]> 
                                          <table class="outlook-container " width="600" align="center" bgcolor="#FFFFFF" style="background-color:#FFFFFF;box-sizing:border-box;border-spacing:0;border-collapse:collapse;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;" >
                                             <tr>
                                                <td width="100%" valign="top" align="center">
                                                   <![endif]-->
                                                   <table class="wrapper--outer" align="center" style="box-sizing:border-box;border-spacing:0;border-collapse:collapse;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;width:100%;max-width:600px;margin-top:0;margin-bottom:0;margin-right:auto;margin-left:auto; background-color:#FFFFFF" bgcolor="#FFFFFF">
                                                      <tbody>
                                                         <tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                                                            <td class="column--1 image" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;font-size:0;padding-top:10px;padding-bottom:10px;padding-right:10px;padding-left:10px;">
                                                               <table width="100%" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;">
                                                                  <tbody>
                                                                     <tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                                                                        <td class="wrapper--column image" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;padding-top:10px;padding-bottom:10px;padding-right:10px;padding-left:10px;">
                                                                           <table class="wrapper--content" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;width:100%;">
                                                                              <tbody>
                                                                                 <tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                                                                                    <td class="wrapper--inner" align="center" style="padding:0;line-height:0px;border-collapse:collapse !important;word-break:break-word;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;">
                                                                                       <img ondragstart="return false;" width="560" src="https://app2.dopplerfiles.com/Templates/225927/mail.jpg" alt="mail" style="clear:both;width:560px;max-width:100%;text-decoration:none;border-style:none;outline-style:none;-ms-interpolation-mode:bicubic;text-align:center;">
                                                                                    </td>
                                                                                 </tr>
                                                                              </tbody>
                                                                           </table>
                                                                        </td>
                                                                     </tr>
                                                                  </tbody>
                                                               </table>
                                                            </td>
                                                         </tr>
                                                      </tbody>
                                                   </table>
                                                   <!--[if (gte mso 9)|(IE)]> 
                                                </td>
                                             </tr>
                                          </table>
                                          <![endif]-->
                                       </td>
                                    </tr>
                                 </tbody>
                              </table>
                           </td>
                        </tr>
                        <tr>
                           <td>
                              <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color: #352d54">
                                 <tbody>
                                    <tr>
                                       <td style="background-color: #352d54">
                                          <!--[if (gte mso 9)|(IE)]> 
                                          <table class="outlook-container " width="600" align="center" bgcolor="#FDB913" style="background-color:#FDB913;box-sizing:border-box;border-spacing:0;border-collapse:collapse;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;" >
                                             <tr>
                                                <td width="100%" valign="top" align="center">
                                                   <![endif]-->
                                                   <table class="wrapper--outer" align="center" style="box-sizing:border-box;border-spacing:0;border-collapse:collapse;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;width:100%;max-width:600px;margin-top:0;margin-bottom:0;margin-right:auto;margin-left:auto; background-color:#FDB913" bgcolor="#FDB913">
                                                      <tbody>
                                                         <tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                                                            <td class="column--1" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;font-size:0;padding-top:10px;padding-bottom:10px;padding-right:10px;padding-left:10px;text-align:center;">
                                                               <table width="100%" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;">
                                                                  <tbody>
                                                                     <tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                                                                        <td class="wrapper--column" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;padding-top:10px;padding-bottom:10px;padding-right:10px;padding-left:10px;">
                                                                           <table class="wrapper--content" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;width:100%;">
                                                                              <tbody>
                                                                                 <tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                                                                                    <td align="center" class="wrapper--inner" style="padding:0;line-height:120%;font-size:12px;border-collapse:collapse !important;word-break:break-word;word-wrap:break-word; margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;">
                                                                                       <span style="display: block;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;line-height:normal;">
                                                                                          <div style="text-align: center;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl">Hola <<nombre_emprendedor>></span></div>
                                                                                          <div style="text-align: center;"><br></div>
                                                                                          <div style="text-align: center;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 16px; line-height: 1.3;" class="font-line-height-xl"><b>¡Reunión agendada con éxito!</b></span></div>
                                                                                          <div style="text-align: center;"><br></div>
                                                                                       </span>
                                                                                    </td>
                                                                                 </tr>
                                                                              </tbody>
                                                                           </table>
                                                                        </td>
                                                                     </tr>
                                                                  </tbody>
                                                               </table>
                                                            </td>
                                                         </tr>
                                                      </tbody>
                                                   </table>
                                                   <!--[if (gte mso 9)|(IE)]> 
                                                </td>
                                             </tr>
                                          </table>
                                          <![endif]-->
                                       </td>
                                    </tr>
                                 </tbody>
                              </table>
                           </td>
                        </tr>
                        <tr>
                           <td>
                              <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color: #352d54">
                                 <tbody>
                                    <tr>
                                       <td style="background-color: #352d54">
                                          <!--[if (gte mso 9)|(IE)]> 
                                          <table class="outlook-container " width="600" align="center" bgcolor="#F7F7F7" style="background-color:#F7F7F7;box-sizing:border-box;border-spacing:0;border-collapse:collapse;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;" >
                                             <tr>
                                                <td width="100%" valign="top" align="center">
                                                   <![endif]-->
                                                   <table class="wrapper--outer" align="center" style="box-sizing:border-box;border-spacing:0;border-collapse:collapse;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;width:100%;max-width:600px;margin-top:0;margin-bottom:0;margin-right:auto;margin-left:auto; background-color:#F7F7F7" bgcolor="#F7F7F7">
                                                      <tbody>
                                                         <tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                                                            <td class="column--1" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;font-size:0;padding-top:10px;padding-bottom:10px;padding-right:10px;padding-left:10px;text-align:center;">
                                                               <table width="100%" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;">
                                                                  <tbody>
                                                                     <tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                                                                        <td class="wrapper--column" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;padding-top:10px;padding-bottom:10px;padding-right:10px;padding-left:10px;">
                                                                           <table class="wrapper--content" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;width:100%;">
                                                                              <tbody>
                                                                                 <tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                                                                                    <td align="left" class="wrapper--inner" style="padding:0;line-height:120%;font-size:12px;border-collapse:collapse !important;word-break:break-word;word-wrap:break-word; margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;">
                                                                                       <span style="display: block;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;line-height:normal;">
                                                                                          <div style="text-align: left;">Estimado(a):</div>
                                                                                          <div style="text-align: left;"><br></div>
                                                                                          <div style="text-align: left;">Su reunión ha sido agendada con éxito.</div>
                                                                                          <div style="text-align: left;"><br></div>
                                                                                          <div style="text-align: left;">Asistente técnico: <<nombre_asistente>></div>
                                                                                          <div style="text-align: left;">Fecha: <<fecha>></div>
                                                                                          <div style="text-align: left;">Hora: <<hora>></div>
                                                                                          <div style="text-align: left;"><br></div>
                                                                                          <div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl">Si deseas cancelar o reagendar escríbenos a <i><b>mesadeservicio@epico.gob.ec</b></i> ¡Con gusto te atenderemos!</span></div>
                                                                                          <div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl"><b>&nbsp;</b></span></div>
                                                                                          <div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl"><b>&nbsp;</b></span></div>
                                                                                          <div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl"><b>¡Construyamos juntos un Guayaquil ÉPICO!</b></span></div>
                                                                                          <div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl"><b>&nbsp;</b></span></div>
                                                                                          <div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl">Saludos cordiales,</span></div>
                                                                                          <div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl"><b>El equipo ÉPICO</b></span></div>
                                                                                          <div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl"><b>&nbsp;</b></span></div>
                                                                                       </span>
                                                                                    </td>
                                                                                 </tr>
                                                                              </tbody>
                                                                           </table>
                                                                        </td>
                                                                     </tr>
                                                                  </tbody>
                                                               </table>
                                                            </td>
                                                         </tr>
                                                      </tbody>
                                                   </table>
                                                   <!--[if (gte mso 9)|(IE)]> 
                                                </td>
                                             </tr>
                                          </table>
                                          <![endif]-->
                                       </td>
                                    </tr>
                                 </tbody>
                              </table>
                           </td>
                        </tr>
                        <tr>
                           <td>
                              <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color: #352d54">
                                 <tbody>
                                    <tr>
                                       <td style="background-color: #352d54">
                                          <!--[if (gte mso 9)|(IE)]> 
                                          <table class="outlook-container " width="600" align="center" bgcolor="#FFFFFF" style="background-color:#FFFFFF;box-sizing:border-box;border-spacing:0;border-collapse:collapse;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;" >
                                             <tr>
                                                <td width="100%" align="center" valign="top">
                                                   <![endif]-->
                                                   <table class="wrapper--outer" align="center" style="box-sizing:border-box;border-spacing:0;border-collapse:collapse;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;width:100%;max-width:600px;margin-top:0;margin-bottom:0;margin-right:auto;margin-left:auto; background-color:#FFFFFF" bgcolor="#FFFFFF">
                                                      <tbody>
                                                         <tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                                                            <td class="column--1" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;font-size:0;padding-top:10px;padding-bottom:10px;padding-right:10px;padding-left:10px;text-align:center;">
                                                               <table width="100%" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;">
                                                                  <tbody>
                                                                     <tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                                                                        <td class="wrapper--column" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;padding-top:5px;padding-bottom:10px;padding-right:10px;padding-left:10px;">
                                                                           <table class="wrapper--content" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;width:100%;">
                                                                              <tbody>
                                                                                 <tr style="vertical-align: top">
                                                                                    <td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;padding: 0;" align="center">
                                                                                       <table class="table_center" border="0" cellpadding="0" cellspacing="0" style="text-align: center">
                                                                                          <tbody>
                                                                                             <tr>
                                                                                                <td style="display: inline-block; padding-right: 5px; padding-top: 5px; line-height: 0px;" valign="middle">
                                                                                                   <a ondragstart="return false;" href="http://facebook.com/https://es-la.facebook.com/centroemprendimientogye/" target="_blank">
                                                                                                   <img ondragstart="return false;" width="32" src="https://app2.dopplerfiles.com/MSEditor/images/color_rounded_facebook.png" alt="Facebook">
                                                                                                   </a>
                                                                                                </td>
                                                                                                <td style="display: inline-block; padding-right: 5px; padding-top: 5px; line-height: 0px;" valign="middle">
                                                                                                   <a ondragstart="return false;" href="http://instagram.com/https://www.instagram.com/epicogye/" target="_blank">
                                                                                                   <img ondragstart="return false;" width="32" src="https://app2.dopplerfiles.com/MSEditor/images/color_rounded_instagram.png" alt="Instagram">
                                                                                                   </a>
                                                                                                </td>
                                                                                                <td style="display: inline-block; padding-right: 0px; padding-top: 5px; line-height: 0px;" valign="middle">
                                                                                                   <a ondragstart="return false;" href="http://linkedin.com/https://www.linkedin.com/organization-guest/company/epicogye?challengeId=AQE_o3qKvYZJlQAAAXNt80cH7Tw4HJGFvMoxs01VyGqWeI7tVlIGsgP1YYNDnmSdw4EWzEs5m0wWVFv_rOCVTx86x8asyc1gFQ&amp;submissionId=35e2cc50-7590-2316-ee4f-d015707dcc45" target="_blank">
                                                                                                   <img ondragstart="return false;" width="32" src="https://app2.dopplerfiles.com/MSEditor/images/color_rounded_linkedin.png" alt="Linkedin">
                                                                                                   </a>
                                                                                                </td>
                                                                                             </tr>
                                                                                          </tbody>
                                                                                       </table>
                                                                                    </td>
                                                                                 </tr>
                                                                              </tbody>
                                                                           </table>
                                                                        </td>
                                                                     </tr>
                                                                  </tbody>
                                                               </table>
                                                            </td>
                                                         </tr>
                                                      </tbody>
                                                   </table>
                                                   <!--[if (gte mso 9)|(IE)]> 
                                                </td>
                                             </tr>
                                          </table>
                                          <![endif]-->
                                       </td>
                                    </tr>
                                 </tbody>
                              </table>
                           </td>
                        </tr>
                        <tr>
                           <td>
                              <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color: #352d54">
                                 <tbody>
                                    <tr>
                                       <td style="background-color: #352d54">
                                          <!--[if (gte mso 9)|(IE)]> 
                                          <table class="outlook-container " width="600" align="center" bgcolor="#ffffff" style="background-color:#ffffff;box-sizing:border-box;border-spacing:0;border-collapse:collapse;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;" >
                                             <tr>
                                                <td width="100%" valign="top" align="center">
                                                   <![endif]-->
                                                   <table class="wrapper--outer" align="center" style="box-sizing:border-box;border-spacing:0;border-collapse:collapse;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;width:100%;max-width:600px;margin-top:0;margin-bottom:0;margin-right:auto;margin-left:auto; background-color:#ffffff" bgcolor="#ffffff">
                                                      <tbody>
                                                         <tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                                                            <td class="column--1" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;font-size:0;padding-top:5px;padding-bottom:5px;padding-right:10px;padding-left:10px;text-align:center;">
                                                               <table width="100%" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;">
                                                                  <tbody>
                                                                     <tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                                                                        <td class="wrapper--column" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;padding-top:5px;padding-bottom:5px;padding-right:10px;padding-left:10px;">
                                                                           <table class="wrapper--content" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;width:100%;">
                                                                              <tbody>
                                                                                 <tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                                                                                    <td align="center" class="wrapper--inner" style="padding:0;line-height:120%;font-size:12px;border-collapse:collapse !important;word-break:break-word;word-wrap:break-word; margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;">
                                                                                       <span style="display: block;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;line-height:normal;">
                                                                                          <div style="text-align: center;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif;"><b>@epicogye @empredimiento_epicogye</b></span></div>
                                                                                       </span>
                                                                                    </td>
                                                                                 </tr>
                                                                              </tbody>
                                                                           </table>
                                                                        </td>
                                                                     </tr>
                                                                  </tbody>
                                                               </table>
                                                            </td>
                                                         </tr>
                                                      </tbody>
                                                   </table>
                                                   <!--[if (gte mso 9)|(IE)]> 
                                                </td>
                                             </tr>
                                          </table>
                                          <![endif]-->
                                       </td>
                                    </tr>
                                 </tbody>
                              </table>
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </center>
            </td>
         </tr>
      </tbody>
   </table>
</div>
                ';
        return $correo;
    }

    public function getCorreoMesaAgendamiento() {
        $correo = new stdClass();
        $correo->asunto = "Reunion asistencia tecnica";
        $correo->tipo = "AGENDA ASISTENCIA TECNICA";
        $correo->texto_correo = '
            <div style="background-color:#352d54;">
   <table bgcolor="#352d54" height="100%" width="100%" cellpadding="0" cellspacing="0" border="0">
      <tbody>
         <tr>
            <td valign="top" align="center" bgcolor="#352d54" background="" style="background-color:#352d54;background-image: url(\'\');background-position:top center;background-repeat:repeat;">
               <center class="wrapper" style="width:100%;table-layout:fixed;text-align:inherit;">
                  <table cellpadding="0" cellspacing="0" border="0" width="100%">
                     <tbody>
                        <tr>
                           <td>
                              <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color: #352d54">
                                 <tbody>
                                    <tr>
                                       <td style="background-color: #352d54">
                                          <!--[if (gte mso 9)|(IE)]> 
                                          <table class="outlook-container " width="600" align="center" bgcolor="#FFFFFF" style="background-color:#FFFFFF;box-sizing:border-box;border-spacing:0;border-collapse:collapse;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;" >
                                             <tr>
                                                <td width="100%" valign="top" align="center">
                                                   <![endif]-->
                                                   <table class="wrapper--outer" align="center" style="box-sizing:border-box;border-spacing:0;border-collapse:collapse;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;width:100%;max-width:600px;margin-top:0;margin-bottom:0;margin-right:auto;margin-left:auto; background-color:#FFFFFF" bgcolor="#FFFFFF">
                                                      <tbody>
                                                         <tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                                                            <td class="column--1 image" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;font-size:0;padding-top:10px;padding-bottom:10px;padding-right:10px;padding-left:10px;">
                                                               <table width="100%" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;">
                                                                  <tbody>
                                                                     <tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                                                                        <td class="wrapper--column image" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;padding-top:10px;padding-bottom:10px;padding-right:10px;padding-left:10px;">
                                                                           <table class="wrapper--content" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;width:100%;">
                                                                              <tbody>
                                                                                 <tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                                                                                    <td class="wrapper--inner" align="center" style="padding:0;line-height:0px;border-collapse:collapse !important;word-break:break-word;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;">
                                                                                       <img ondragstart="return false;" width="560" src="https://app2.dopplerfiles.com/Templates/225937/mail.jpg" alt="mail" style="clear:both;width:560px;max-width:100%;text-decoration:none;border-style:none;outline-style:none;-ms-interpolation-mode:bicubic;text-align:center;">
                                                                                    </td>
                                                                                 </tr>
                                                                              </tbody>
                                                                           </table>
                                                                        </td>
                                                                     </tr>
                                                                  </tbody>
                                                               </table>
                                                            </td>
                                                         </tr>
                                                      </tbody>
                                                   </table>
                                                   <!--[if (gte mso 9)|(IE)]> 
                                                </td>
                                             </tr>
                                          </table>
                                          <![endif]-->
                                       </td>
                                    </tr>
                                 </tbody>
                              </table>
                           </td>
                        </tr>
                        <tr>
                           <td>
                              <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color: #352d54">
                                 <tbody>
                                    <tr>
                                       <td style="background-color: #352d54">
                                          <!--[if (gte mso 9)|(IE)]> 
                                          <table class="outlook-container " width="600" align="center" bgcolor="#FDB913" style="background-color:#FDB913;box-sizing:border-box;border-spacing:0;border-collapse:collapse;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;" >
                                             <tr>
                                                <td width="100%" valign="top" align="center">
                                                   <![endif]-->
                                                   <table class="wrapper--outer" align="center" style="box-sizing:border-box;border-spacing:0;border-collapse:collapse;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;width:100%;max-width:600px;margin-top:0;margin-bottom:0;margin-right:auto;margin-left:auto; background-color:#FDB913" bgcolor="#FDB913">
                                                      <tbody>
                                                         <tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                                                            <td class="column--1" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;font-size:0;padding-top:10px;padding-bottom:10px;padding-right:10px;padding-left:10px;text-align:center;">
                                                               <table width="100%" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;">
                                                                  <tbody>
                                                                     <tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                                                                        <td class="wrapper--column" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;padding-top:10px;padding-bottom:10px;padding-right:10px;padding-left:10px;">
                                                                           <table class="wrapper--content" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;width:100%;">
                                                                              <tbody>
                                                                                 <tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                                                                                    <td align="center" class="wrapper--inner" style="padding:0;line-height:120%;font-size:12px;border-collapse:collapse !important;word-break:break-word;word-wrap:break-word; margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;">
                                                                                       <span style="display: block;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;line-height:normal;">
                                                                                          <div style="text-align: center;"><br></div>
                                                                                          <div style="text-align: center;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 16px; line-height: 1.3;" class="font-line-height-xl"><b>¡Se ha realizado un agendamiento de asistencia técnica!</b></span></div>
                                                                                          <div style="text-align: center;"><br></div>
                                                                                       </span>
                                                                                    </td>
                                                                                 </tr>
                                                                              </tbody>
                                                                           </table>
                                                                        </td>
                                                                     </tr>
                                                                  </tbody>
                                                               </table>
                                                            </td>
                                                         </tr>
                                                      </tbody>
                                                   </table>
                                                   <!--[if (gte mso 9)|(IE)]> 
                                                </td>
                                             </tr>
                                          </table>
                                          <![endif]-->
                                       </td>
                                    </tr>
                                 </tbody>
                              </table>
                           </td>
                        </tr>
                        <tr>
                           <td>
                              <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color: #352d54">
                                 <tbody>
                                    <tr>
                                       <td style="background-color: #352d54">
                                          <!--[if (gte mso 9)|(IE)]> 
                                          <table class="outlook-container " width="600" align="center" bgcolor="#F7F7F7" style="background-color:#F7F7F7;box-sizing:border-box;border-spacing:0;border-collapse:collapse;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;" >
                                             <tr>
                                                <td width="100%" valign="top" align="center">
                                                   <![endif]-->
                                                   <table class="wrapper--outer" align="center" style="box-sizing:border-box;border-spacing:0;border-collapse:collapse;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;width:100%;max-width:600px;margin-top:0;margin-bottom:0;margin-right:auto;margin-left:auto; background-color:#F7F7F7" bgcolor="#F7F7F7">
                                                      <tbody>
                                                         <tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                                                            <td class="column--1" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;font-size:0;padding-top:10px;padding-bottom:10px;padding-right:10px;padding-left:10px;text-align:center;">
                                                               <table width="100%" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;">
                                                                  <tbody>
                                                                     <tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                                                                        <td class="wrapper--column" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;padding-top:10px;padding-bottom:10px;padding-right:10px;padding-left:10px;">
                                                                           <table class="wrapper--content" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;width:100%;">
                                                                              <tbody>
                                                                                 <tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                                                                                    <td align="left" class="wrapper--inner" style="padding:0;line-height:120%;font-size:12px;border-collapse:collapse !important;word-break:break-word;word-wrap:break-word; margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;">
                                                                                       <span style="display: block;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;line-height:normal;">
                                                                                          <div style="text-align: left;">Estimados(as):</div>
                                                                                          <div style="text-align: left;"><br></div>
                                                                                          <div style="text-align: left;">Se ha realizado un agendamiento de asistencia técnica.</div>
                                                                                          <div style="text-align: left;"><br></div>
                                                                                          <div style="text-align: left;">Emprendedor: <<nombre_emprendedor>></div>
                                                                                          <div style="text-align: left;">Asistente tecnico: <<nombre_asistente>></div>
                                                                                          <div style="text-align: left;">Fecha: <<fecha>></div>
                                                                                          <div style="text-align: left;">Hora: <<hora>></div>
                                                                                          <div style="text-align: left;"><br></div>
                                                                                          <div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl"><b>¡Construyamos juntos un Guayaquil ÉPICO!</b></span></div>
                                                                                          <div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl"><b>&nbsp;</b></span></div>
                                                                                          <div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl">Saludos cordiales,</span></div>
                                                                                          <div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl"><b>El equipo ÉPICO</b></span></div>
                                                                                          <div style="text-align: left;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 13px; line-height: 1.3;" class="font-line-height-xl"><b>&nbsp;</b></span></div>
                                                                                       </span>
                                                                                    </td>
                                                                                 </tr>
                                                                              </tbody>
                                                                           </table>
                                                                        </td>
                                                                     </tr>
                                                                  </tbody>
                                                               </table>
                                                            </td>
                                                         </tr>
                                                      </tbody>
                                                   </table>
                                                   <!--[if (gte mso 9)|(IE)]> 
                                                </td>
                                             </tr>
                                          </table>
                                          <![endif]-->
                                       </td>
                                    </tr>
                                 </tbody>
                              </table>
                           </td>
                        </tr>
                        <tr>
                           <td>
                              <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color: #352d54">
                                 <tbody>
                                    <tr>
                                       <td style="background-color: #352d54">
                                          <!--[if (gte mso 9)|(IE)]> 
                                          <table class="outlook-container " width="600" align="center" bgcolor="#FFFFFF" style="background-color:#FFFFFF;box-sizing:border-box;border-spacing:0;border-collapse:collapse;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;" >
                                             <tr>
                                                <td width="100%" align="center" valign="top">
                                                   <![endif]-->
                                                   <table class="wrapper--outer" align="center" style="box-sizing:border-box;border-spacing:0;border-collapse:collapse;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;width:100%;max-width:600px;margin-top:0;margin-bottom:0;margin-right:auto;margin-left:auto; background-color:#FFFFFF" bgcolor="#FFFFFF">
                                                      <tbody>
                                                         <tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                                                            <td class="column--1" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;font-size:0;padding-top:10px;padding-bottom:10px;padding-right:10px;padding-left:10px;text-align:center;">
                                                               <table width="100%" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;">
                                                                  <tbody>
                                                                     <tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                                                                        <td class="wrapper--column" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;padding-top:5px;padding-bottom:10px;padding-right:10px;padding-left:10px;">
                                                                           <table class="wrapper--content" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;width:100%;">
                                                                              <tbody>
                                                                                 <tr style="vertical-align: top">
                                                                                    <td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;padding: 0;" align="center">
                                                                                       <table class="table_center" border="0" cellpadding="0" cellspacing="0" style="text-align: center">
                                                                                          <tbody>
                                                                                             <tr>
                                                                                                <td style="display: inline-block; padding-right: 5px; padding-top: 5px; line-height: 0px;" valign="middle">
                                                                                                   <a ondragstart="return false;" href="http://facebook.com/https://es-la.facebook.com/centroemprendimientogye/" target="_blank">
                                                                                                   <img ondragstart="return false;" width="32" src="https://app2.dopplerfiles.com/MSEditor/images/color_rounded_facebook.png" alt="Facebook">
                                                                                                   </a>
                                                                                                </td>
                                                                                                <td style="display: inline-block; padding-right: 5px; padding-top: 5px; line-height: 0px;" valign="middle">
                                                                                                   <a ondragstart="return false;" href="http://instagram.com/https://www.instagram.com/epicogye/" target="_blank">
                                                                                                   <img ondragstart="return false;" width="32" src="https://app2.dopplerfiles.com/MSEditor/images/color_rounded_instagram.png" alt="Instagram">
                                                                                                   </a>
                                                                                                </td>
                                                                                                <td style="display: inline-block; padding-right: 0px; padding-top: 5px; line-height: 0px;" valign="middle">
                                                                                                   <a ondragstart="return false;" href="http://linkedin.com/https://www.linkedin.com/organization-guest/company/epicogye?challengeId=AQE_o3qKvYZJlQAAAXNt80cH7Tw4HJGFvMoxs01VyGqWeI7tVlIGsgP1YYNDnmSdw4EWzEs5m0wWVFv_rOCVTx86x8asyc1gFQ&amp;submissionId=35e2cc50-7590-2316-ee4f-d015707dcc45" target="_blank">
                                                                                                   <img ondragstart="return false;" width="32" src="https://app2.dopplerfiles.com/MSEditor/images/color_rounded_linkedin.png" alt="Linkedin">
                                                                                                   </a>
                                                                                                </td>
                                                                                             </tr>
                                                                                          </tbody>
                                                                                       </table>
                                                                                    </td>
                                                                                 </tr>
                                                                              </tbody>
                                                                           </table>
                                                                        </td>
                                                                     </tr>
                                                                  </tbody>
                                                               </table>
                                                            </td>
                                                         </tr>
                                                      </tbody>
                                                   </table>
                                                   <!--[if (gte mso 9)|(IE)]> 
                                                </td>
                                             </tr>
                                          </table>
                                          <![endif]-->
                                       </td>
                                    </tr>
                                 </tbody>
                              </table>
                           </td>
                        </tr>
                        <tr>
                           <td>
                              <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color: #352d54">
                                 <tbody>
                                    <tr>
                                       <td style="background-color: #352d54">
                                          <!--[if (gte mso 9)|(IE)]> 
                                          <table class="outlook-container " width="600" align="center" bgcolor="#ffffff" style="background-color:#ffffff;box-sizing:border-box;border-spacing:0;border-collapse:collapse;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;" >
                                             <tr>
                                                <td width="100%" valign="top" align="center">
                                                   <![endif]-->
                                                   <table class="wrapper--outer" align="center" style="box-sizing:border-box;border-spacing:0;border-collapse:collapse;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;width:100%;max-width:600px;margin-top:0;margin-bottom:0;margin-right:auto;margin-left:auto; background-color:#ffffff" bgcolor="#ffffff">
                                                      <tbody>
                                                         <tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                                                            <td class="column--1" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;font-size:0;padding-top:5px;padding-bottom:5px;padding-right:10px;padding-left:10px;text-align:center;">
                                                               <table width="100%" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;">
                                                                  <tbody>
                                                                     <tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                                                                        <td class="wrapper--column" style="border-collapse:collapse !important;word-break:break-word;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#333333;padding-top:5px;padding-bottom:5px;padding-right:10px;padding-left:10px;">
                                                                           <table class="wrapper--content" style="border-spacing:0;border-collapse:collapse;font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif;font-weight:400;line-height:15.6px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333333;width:100%;">
                                                                              <tbody>
                                                                                 <tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
                                                                                    <td align="center" class="wrapper--inner" style="padding:0;line-height:120%;font-size:12px;border-collapse:collapse !important;word-break:break-word;word-wrap:break-word; margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;">
                                                                                       <span style="display: block;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;line-height:normal;">
                                                                                          <div style="text-align: center;"><span style="font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif;"><b>@epicogye @empredimiento_epicogye</b></span></div>
                                                                                       </span>
                                                                                    </td>
                                                                                 </tr>
                                                                              </tbody>
                                                                           </table>
                                                                        </td>
                                                                     </tr>
                                                                  </tbody>
                                                               </table>
                                                            </td>
                                                         </tr>
                                                      </tbody>
                                                   </table>
                                                   <!--[if (gte mso 9)|(IE)]> 
                                                </td>
                                             </tr>
                                          </table>
                                          <![endif]-->
                                       </td>
                                    </tr>
                                 </tbody>
                              </table>
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </center>
            </td>
         </tr>
      </tbody>
   </table>
</div>
                ';
        return $correo;
    }

}
