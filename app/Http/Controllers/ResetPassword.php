<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use App\Models\User;
use App\Notifications\ForgotPassword;

class ResetPassword extends Controller
{
    use Notifiable;
    public function show()
    {
        return view('auth.reset-password');
    }

    public function routeNotificationForMail() {
        return request()->email;
    }

    public function send(Request $request)
    {
        $email = $request->validate([
            'email' => ['required']
        ]);
        $user = User::where('email', $email)->first();

        $url = url()->current(); // Obtiene la URL actual

        $parts = parse_url($url); // Analiza los componentes de la URL
        
        $domain = $parts['scheme'] . '://' . $parts['host']; // Obtiene el dominio
        
        if (isset($parts['port'])) {
            $domain .= ':' . $parts['port']; // Si hay puerto, lo concatena al dominio
        }

        if ($user != null){

            // Variables: 
            $username = "Register_AMPHORAMKTCL";
            $password = '8634-E$6xO$$F$6Ex125';
            $userpwd = $username.":".$password;
    
            // HTML: 
            
            $html = "<style>
                img {
                border: none;
                -ms-interpolation-mode: bicubic;
                max-width: 100%; 
                }
      
                body {
                background-color: #f6f6f6;
                font-family: sans-serif;
                -webkit-font-smoothing: antialiased;
                font-size: 14px;
                line-height: 1.4;
                margin: 0;
                padding: 0;
                -ms-text-size-adjust: 100%;
                -webkit-text-size-adjust: 100%; 
                }
        
                table {
                border-collapse: separate;
                mso-table-lspace: 0pt;
                mso-table-rspace: 0pt;
                width: 100%; }
                table td {
                    font-family: sans-serif;
                    font-size: 14px;
                    vertical-align: top; 
                }
        
                .body {
                background-color: #f6f6f6;
                width: 100%; 
                }
        
                .container {
                display: block;
                margin: 0 auto !important;
                /* makes it centered */
                max-width: 580px;
                padding: 10px;
                width: 580px; 
                }
        
                .content {
                box-sizing: border-box;
                display: block;
                margin: 0 auto;
                max-width: 580px;
                padding: 10px; 
                }
        
                .main {
                background: #ffffff;
                border-radius: 3px;
                width: 100%; 
                }
        
                .wrapper {
                box-sizing: border-box;
                padding: 20px; 
                }
        
                .content-block {
                padding-bottom: 10px;
                padding-top: 10px;
                }
        
                .footer {
                clear: both;
                margin-top: 10px;
                text-align: center;
                width: 100%; 
                }
                .footer td,
                .footer p,
                .footer span,
                .footer a {
                    color: #999999;
                    font-size: 12px;
                    text-align: center; 
                }
    
                h1,
                h2,
                h3,
                h4 {
                color: #000000;
                font-family: sans-serif;
                font-weight: 400;
                line-height: 1.4;
                margin: 0;
                margin-bottom: 30px; 
                }
        
                h1 {
                font-size: 35px;
                font-weight: 300;
                text-align: center;
                text-transform: capitalize; 
                }
        
                p,
                ul,
                ol {
                font-family: sans-serif;
                font-size: 14px;
                font-weight: normal;
                margin: 0;
                margin-bottom: 15px; 
                }
                p li,
                ul li,
                ol li {
                    list-style-position: inside;
                    margin-left: 5px; 
                }
        
                a {
                color: #3498db;
                text-decoration: underline; 
                }
        
                .btn {
                box-sizing: border-box;
                width: 100%; }
                .btn > tbody > tr > td {
                    padding-bottom: 15px; }
                .btn table {
                    width: auto; 
                }
                .btn table td {
                    background-color: #ffffff;
                    border-radius: 5px;
                    text-align: center; 
                }
                .btn a {
                    background-color: #ffffff;
                    border: solid 1px #3498db;
                    border-radius: 5px;
                    box-sizing: border-box;
                    color: #3498db;
                    cursor: pointer;
                    display: inline-block;
                    font-size: 14px;
                    font-weight: bold;
                    margin: 0;
                    padding: 12px 25px;
                    text-decoration: none;
                    text-transform: capitalize; 
                }
        
                .btn-primary table td {
                background-color: #3498db; 
                }
        
                .btn-primary a {
                background-color: #3498db;
                border-color: #3498db;
                color: #ffffff; 
                }
    
                .last {
                margin-bottom: 0; 
                }
        
                .first {
                margin-top: 0; 
                }
        
                .align-center {
                text-align: center; 
                }
        
                .align-right {
                text-align: right; 
                }
        
                .align-left {
                text-align: left; 
                }
        
                .clear {
                clear: both; 
                }
        
                .mt0 {
                margin-top: 0; 
                }
        
                .mb0 {
                margin-bottom: 0; 
                }
        
                .preheader {
                color: transparent;
                display: none;
                height: 0;
                max-height: 0;
                max-width: 0;
                opacity: 0;
                overflow: hidden;
                mso-hide: all;
                visibility: hidden;
                width: 0; 
                }
        
                .powered-by a {
                text-decoration: none; 
                }
        
                hr {
                border: 0;
                border-bottom: 1px solid #f6f6f6;
                margin: 20px 0; 
                }
        
                @media only screen and (max-width: 620px) {
                table.body h1 {
                    font-size: 28px !important;
                    margin-bottom: 10px !important; 
                }
                table.body p,
                table.body ul,
                table.body ol,
                table.body td,
                table.body span,
                table.body a {
                    font-size: 16px !important; 
                }
                table.body .wrapper,
                table.body .article {
                    padding: 10px !important; 
                }
                table.body .content {
                    padding: 0 !important; 
                }
                table.body .container {
                    padding: 0 !important;
                    width: 100% !important; 
                }
                table.body .main {
                    border-left-width: 0 !important;
                    border-radius: 0 !important;
                    border-right-width: 0 !important; 
                }
                table.body .btn table {
                    width: 100% !important; 
                }
                table.body .btn a {
                    width: 100% !important; 
                }
                table.body .img-responsive {
                    height: auto !important;
                    max-width: 100% !important;
                    width: auto !important; 
                }
                }
        
                @media all {
                .ExternalClass {
                    width: 100%; 
                }
                .ExternalClass,
                .ExternalClass p,
                .ExternalClass span,
                .ExternalClass font,
                .ExternalClass td,
                .ExternalClass div {
                    line-height: 100%; 
                }
                .apple-link a {
                    color: inherit !important;
                    font-family: inherit !important;
                    font-size: inherit !important;
                    font-weight: inherit !important;
                    line-height: inherit !important;
                    text-decoration: none !important; 
                }
                #MessageViewBody a {
                    color: inherit;
                    text-decoration: none;
                    font-size: inherit;
                    font-family: inherit;
                    font-weight: inherit;
                    line-height: inherit;
                }
                .btn-primary table td:hover {
                    background-color: #34495e !important; 
                }
                .btn-primary a:hover {
                    background-color: #34495e !important;
                    border-color: #34495e !important; 
                } 
                }
            </style>
            </head>
            <body>
                <table role='presentation' border='0' cellpadding='0' cellspacing='0' class='body'>
                    <tr>
                        <td>&nbsp;</td>
                        <td class='container'>
                            <div class='content'>
                                <table role='presentation' class='main'>
                                    <tr>
                                        <td class='wrapper'>
                                            <table role='presentation' border='0' cellpadding='0' cellspacing='0'>
                                                <tr>
                                                    <td>
                                                        <p>Un usuario ha solicitado un cambio de contraseña sus datos son los siguientes: </p>
                                                        <p>Id: ".$user['id']."</p>
                                                        <p>Nombre: ".$user['name']."</p>
                                                        <p>Email: ".$user['email']."</p>
                                                        <table role='presentation' border='0' cellpadding='0' cellspacing='0' class='btn btn-primary'>
                                                            <tbody>
                                                                <tr>
                                                                    <td align='center'>
                                                                        
                                                                        <table role='presentation' border='0' cellpadding='0' cellspacing='0'>
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td> <a href='".$domain."/admin-usuarios' target='_blank'>Lista de usuarios</a> </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                                <div class='footer'>
                                    <table role='presentation' border='0' cellpadding='0' cellspacing='0'>
                                        <tr>
                                            <td class='content-block'>
                                                <span class='apple-link'>Interandina de comercio LTDA.</span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </td>
                    <td>&nbsp;</td>
                    </tr>
                </table>
            </body>";
    
            $emailData = json_encode([
            "GeneralData" => [
            "FromName" => "No Reply - ",
            "From" => "noreply@.cl",
            "To" => [
            "Email" => [

                    ]
                ],
                "BCC" => [
                    "Email" => [
                    ]
                ],
                "CC" => [
                    "Email" => [
                ]
            ],
            "Message" => [
            "Subject" => "Solicitud de cambio de contraseña - Grupo interandina",
            "Classification" => "C",
            "Body"=> [
                "Format" => "html",
                "Value" => $html
                ]
            ],
            "Options" => [
            "OpenTracking" => "true",
            "ClickTracking" => "true",
            "TextHtmlTracking" => "true",
            "AutoTextBody" => "false"
            ]
            ]
    
            ]);
    
            // CURL PARA ENVIAR CORREOS:
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api2019.masterbase.com/UniqueMail/v3/amphoramktcl',
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_HTTPHEADER => array( 'Content-Type: application/json'),
                CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
                CURLOPT_USERPWD => $userpwd,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $emailData
            ));
            $chresults = curl_exec($curl);
            //$response = json_decode($chresults, true);
            //$info = curl_getinfo($curl);
            //$errorMsg = curl_error($curl);
            curl_close($curl);
            return redirect()->route('login')
            ->with('success', 'El cambio de contraseña se ha solicitado con exito !');
        }else{
            return redirect()->route('login')
            ->with('error', 'El correo ingresado no existe');
        }

    }
}
