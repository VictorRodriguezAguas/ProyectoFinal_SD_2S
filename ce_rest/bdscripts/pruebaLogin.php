<?php
date_default_timezone_set('America/Guayaquil');
header('Content-Type: text/html; charset=utf-8');
use Firebase\JWT\JWT;

class Auth
{
    private static $secret_key = 'Ep1c0C3AuthenticKey';
    private static $encrypt = ['HS256'];
    private static $aud = null;

    public static function SignIn($data)
    {
        $time = time();

        $token = array(
        	'init' => $time,
            'exp' => $time + (60*60),
            'aud' => self::Aud(),
            'data' => $data
        );

        return JWT::encode($token, self::$secret_key);
    }

    public static function Check($token)
    {
        if(empty($token))
        {
            throw new Exception("Invalid token supplied.");
        }

        $decode = JWT::decode(
            $token,
            self::$secret_key,
            self::$encrypt
        );

        if($decode->aud !== self::Aud())
        {
            throw new Exception("Invalid user logged in.");
        }
    }

    public static function GetData($token)
    {
        return JWT::decode(
            $token,
            self::$secret_key,
            self::$encrypt
        )->data;
    }

    private static function Aud()
    {
        $aud = '';

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $aud = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $aud = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $aud = $_SERVER['REMOTE_ADDR'];
        }

        $aud .= @$_SERVER['HTTP_USER_AGENT'];
        $aud .= gethostname();

        return sha1($aud);
    }
}

$data = [
	"id"=> "1447",
	"usuario"=> "informacion@epico.gob.ec",
	"password"=> "",
	"password2"=> "",
	"estado"=> "A",
	"nombre"=> "ASISTENTE",
	"apellido"=> "TECNICO 1",
	"foto"=> "",
	"url_foto"=> "",
	"id_persona"=> "1759",
	"correo"=> "informacion@epico.gob.ec",
	"id_emprendedor"=> "",
	"emprendedor"=> 0,
	"mesa_servicio"=> 0,
	"mentor"=> 0,
	"asistencia_tecnica"=> 1 	
];

$respuesta = Auth::SignIn($data);

//$respuesta = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpbml0IjoxNTk2NDkyMDQwLCJleHAiOjE1OTY0OTU2NDAsImF1ZCI6IjU5MTMwZWJkMjllZDljOWU5NDliZjNjNTczZGMwOGU0ZGY3MmQwZjciLCJkYXRhIjp7ImlkIjoiMTczMyIsInVzdWFyaW8iOiJpbmZvcm1hY2lvbkBlcGljby5nb2IuZWMiLCJwYXNzd29yZCI6IiIsInBhc3N3b3JkMiI6IiIsImVzdGFkbyI6IkEiLCJub21icmUiOiJBU0lTVEVOVEUiLCJhcGVsbGlkbyI6IlRFQ05JQ08gMSIsImZvdG8iOm51bGwsImlkX3BlcnNvbmEiOiIyMDUyIiwiY29ycmVvIjoiaW5mb3JtYWNpb25AZXBpY28uZ29iLmVjIiwiaWRfZW1wcmVuZGVkb3IiOm51bGwsImVtcHJlbmRlZG9yIjowLCJtZXNhX3NlcnZpY2lvIjowLCJtZW50b3IiOjAsImFzaXN0ZW5jaWFfdGVjbmljYSI6MX19.oEp_RlZtB1qI7B1J9kyklePSu2KzyVY4PflkrUxKP7g";

//echo $respuesta;
//die();

header("Location: http://localhost:4200/centro_emprendimiento/asistencia_tecnica/login/$respuesta");
die();
?>