<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérifiez votre email - NexaERP</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7fa;">
    <table role="presentation" style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="padding: 40px 0;">
                <table role="presentation" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #1e3a5f 0%, #2d5a87 100%); padding: 40px 40px 30px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: 700;">
                                <span style="color: #7ab929;">Nexa</span>ERP
                            </h1>
                            <p style="margin: 10px 0 0; color: #80d9f2; font-size: 14px;">
                                Vérification de votre adresse email
                            </p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px;">
                            <h2 style="margin: 0 0 20px; color: #1e3a5f; font-size: 24px; font-weight: 600;">
                                Bonjour {{ $tenant->name }},
                            </h2>
                            
                            <p style="margin: 0 0 20px; color: #4a5568; font-size: 16px; line-height: 1.6;">
                                Vous avez demandé à modifier l'adresse email associée à votre compte NexaERP. 
                                Pour confirmer ce changement, veuillez cliquer sur le bouton ci-dessous.
                            </p>

                            <!-- Info Box -->
                            <table role="presentation" style="width: 100%; margin: 30px 0; background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border-radius: 12px; border: 1px solid #e2e8f0;">
                                <tr>
                                    <td style="padding: 25px;">
                                        <h3 style="margin: 0 0 15px; color: #1e3a5f; font-size: 16px; font-weight: 600;">
                                            📧 Nouvelle adresse email
                                        </h3>
                                        <p style="margin: 0; color: #1e3a5f; font-size: 18px; font-weight: 600;">
                                            {{ $tenant->pending_email }}
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- CTA Button -->
                            <table role="presentation" style="width: 100%; margin: 35px 0;">
                                <tr>
                                    <td style="text-align: center;">
                                        <a href="{{ $verificationUrl }}" style="display: inline-block; padding: 18px 50px; background: linear-gradient(135deg, #7ab929 0%, #5a9a1a 100%); color: #ffffff; text-decoration: none; font-size: 16px; font-weight: 600; border-radius: 30px; box-shadow: 0 4px 15px rgba(122, 185, 41, 0.3);">
                                            Vérifier mon email
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Security Warning -->
                            <table role="presentation" style="width: 100%; margin: 20px 0; background-color: #fef2f2; border-radius: 12px; border-left: 4px solid #ef4444;">
                                <tr>
                                    <td style="padding: 15px 20px;">
                                        <p style="margin: 0; color: #991b1b; font-size: 14px;">
                                            <strong>⚠️ Important :</strong> Si vous n'avez pas demandé ce changement, ignorez cet email. Votre adresse email actuelle restera inchangée.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 30px 0 0; color: #64748b; font-size: 14px; line-height: 1.6;">
                                Ce lien de vérification expirera dans 24 heures.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8fafc; padding: 30px 40px; border-top: 1px solid #e2e8f0;">
                            <table role="presentation" style="width: 100%;">
                                <tr>
                                    <td style="text-align: center;">
                                        <p style="margin: 0 0 10px; color: #1e3a5f; font-size: 16px; font-weight: 600;">
                                            <span style="color: #7ab929;">Nexa</span>ERP
                                        </p>
                                        <p style="margin: 0 0 15px; color: #64748b; font-size: 13px;">
                                            La solution ERP complète pour votre entreprise
                                        </p>
                                        <p style="margin: 0; color: #94a3b8; font-size: 12px;">
                                            © {{ date('Y') }} NexaERP. Tous droits réservés.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
