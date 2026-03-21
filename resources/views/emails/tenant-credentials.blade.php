<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vos identifiants NexaERP</title>
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
                                Bienvenue dans votre espace de gestion
                            </p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px;">
                            <h2 style="margin: 0 0 20px; color: #1e3a5f; font-size: 24px; font-weight: 600;">
                                Bonjour {{ $name }},
                            </h2>
                            
                            <p style="margin: 0 0 20px; color: #4a5568; font-size: 16px; line-height: 1.6;">
                                Félicitations ! Votre compte <strong style="color: #1e3a5f;">{{ $company }}</strong> a été créé avec succès sur NexaERP. 
                                Vous bénéficiez d'un <strong>essai gratuit de 14 jours</strong> pour découvrir toutes nos fonctionnalités.
                            </p>

                            <!-- Credentials Box -->
                            <table role="presentation" style="width: 100%; margin: 30px 0; background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border-radius: 12px; border: 1px solid #e2e8f0;">
                                <tr>
                                    <td style="padding: 25px;">
                                        <h3 style="margin: 0 0 20px; color: #1e3a5f; font-size: 16px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">
                                            🔐 Vos identifiants de connexion
                                        </h3>
                                        
                                        <table role="presentation" style="width: 100%;">
                                            <tr>
                                                <td style="padding: 12px 0; border-bottom: 1px solid #e2e8f0;">
                                                    <span style="color: #64748b; font-size: 14px;">Email</span>
                                                </td>
                                                <td style="padding: 12px 0; border-bottom: 1px solid #e2e8f0; text-align: right;">
                                                    <span style="color: #1e3a5f; font-size: 15px; font-weight: 600;">{{ $email }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 12px 0;">
                                                    <span style="color: #64748b; font-size: 14px;">Mot de passe</span>
                                                </td>
                                                <td style="padding: 12px 0; text-align: right;">
                                                    <code style="background-color: #fef3c7; color: #92400e; padding: 6px 12px; border-radius: 6px; font-size: 15px; font-weight: 600; font-family: monospace;">{{ $password }}</code>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Security Warning -->
                            <table role="presentation" style="width: 100%; margin: 20px 0; background-color: #fef2f2; border-radius: 12px; border-left: 4px solid #ef4444;">
                                <tr>
                                    <td style="padding: 15px 20px;">
                                        <p style="margin: 0; color: #991b1b; font-size: 14px;">
                                            <strong>⚠️ Important :</strong> Pour votre sécurité, nous vous recommandons de changer votre mot de passe dès votre première connexion.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- CTA Button -->
                            <table role="presentation" style="width: 100%; margin: 35px 0;">
                                <tr>
                                    <td style="text-align: center;">
                                        <a href="{{ $loginUrl }}" style="display: inline-block; padding: 18px 50px; background: linear-gradient(135deg, #7ab929 0%, #5a9a1a 100%); color: #ffffff; text-decoration: none; font-size: 16px; font-weight: 600; border-radius: 30px; box-shadow: 0 4px 15px rgba(122, 185, 41, 0.3);">
                                            Se connecter maintenant
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- What's included -->
                            <h3 style="margin: 30px 0 15px; color: #1e3a5f; font-size: 18px; font-weight: 600;">
                                Ce qui est inclus dans votre essai :
                            </h3>
                            
                            <table role="presentation" style="width: 100%;">
                                <tr>
                                    <td style="padding: 8px 0;">
                                        <table role="presentation">
                                            <tr>
                                                <td style="width: 30px; vertical-align: top;">
                                                    <span style="color: #7ab929; font-size: 16px;">✓</span>
                                                </td>
                                                <td style="color: #4a5568; font-size: 15px; line-height: 1.5;">
                                                    <strong>Facturation</strong> - Devis, factures et paiements
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 8px 0;">
                                        <table role="presentation">
                                            <tr>
                                                <td style="width: 30px; vertical-align: top;">
                                                    <span style="color: #7ab929; font-size: 16px;">✓</span>
                                                </td>
                                                <td style="color: #4a5568; font-size: 15px; line-height: 1.5;">
                                                    <strong>Inventaire</strong> - Gestion des stocks et entrepôts
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 8px 0;">
                                        <table role="presentation">
                                            <tr>
                                                <td style="width: 30px; vertical-align: top;">
                                                    <span style="color: #7ab929; font-size: 16px;">✓</span>
                                                </td>
                                                <td style="color: #4a5568; font-size: 15px; line-height: 1.5;">
                                                    <strong>CRM</strong> - Gestion des clients et prospects
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 8px 0;">
                                        <table role="presentation">
                                            <tr>
                                                <td style="width: 30px; vertical-align: top;">
                                                    <span style="color: #7ab929; font-size: 16px;">✓</span>
                                                </td>
                                                <td style="color: #4a5568; font-size: 15px; line-height: 1.5;">
                                                    <strong>Rapports</strong> - Tableaux de bord et analyses
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 30px 0 0; color: #4a5568; font-size: 15px; line-height: 1.6;">
                                Notre équipe est à votre disposition pour vous accompagner. N'hésitez pas à nous contacter si vous avez des questions.
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

                <!-- Sub-footer -->
                <table role="presentation" style="max-width: 600px; margin: 20px auto 0;">
                    <tr>
                        <td style="text-align: center;">
                            <p style="margin: 0; color: #94a3b8; font-size: 12px;">
                                Cet email contient des informations confidentielles. Ne le partagez pas.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
